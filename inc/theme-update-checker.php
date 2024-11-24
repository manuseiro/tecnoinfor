<?php
/**
 * Theme Update Checker
 *
 * Este arquivo verifica e atualiza temas WordPress a partir de um repositório GitHub.
 */

// Função para obter informações do tema do GitHub
function get_theme_info_from_github($github_username, $repository_name, $access_token) {
    $api_url = 'https://api.github.com/repos/' . $github_username . '/' . $repository_name . '/releases/latest';

    // Requisição à API do GitHub
    $response = wp_remote_get($api_url, array(
        'headers' => array(
            'Accept' => 'application/vnd.github.v3+json',
            'User-Agent' => 'WordPress Theme Update Checker',
            'Authorization' => 'token ' . $access_token,
        ),
    ));

    // Verifica se a resposta é válida
    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        error_log('Erro ao consultar o GitHub: Código de resposta ' . wp_remote_retrieve_response_code($response));
        return false;
    }

    $data = json_decode(wp_remote_retrieve_body($response));
    // Verifica se os dados são válidos e possuem as propriedades necessárias
    if (!$data || empty($data->tag_name) || empty($data->zipball_url)) {
        // Log detalhado do que a API retornou
        error_log('Erro ao obter dados do GitHub: ' . print_r($data, true), 3, __DIR__ . '/debug.log');
        return false;
    }
    return [
        'version' => $data->tag_name,
        'download_url' => $data->zipball_url,
    ];
}

// Função para verificar se há uma atualização disponível
function check_for_theme_update($current_version, $theme_info) {
    if (!$theme_info) {
        return false;
    }

    // Compara a versão atual com a versão do GitHub
    if (version_compare($current_version, $theme_info['version'], '<')) {
        return $theme_info;
    }

    return false;
}

// Função para deletar um diretório
function delete_directory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }

    return rmdir($dir);
}

// Função para baixar e instalar a atualização do tema
function download_and_install_theme_update($theme_info) {
    if (!$theme_info) {
        return false;
    }

    // Adiciona autenticação ao download
    $temp_file = wp_remote_get($theme_info['download_url'], array(
        'headers' => array(
            'Authorization' => 'token SEU_TOKEN_DO_GITHUB_AQUI',
            'Accept' => 'application/vnd.github.v3+json',
            'User-Agent' => 'WordPress Theme Update Checker'
        ),
    ));

    // Verifica se o download foi bem-sucedido
    if (is_wp_error($temp_file) || wp_remote_retrieve_response_code($temp_file) !== 200) {
        error_log('Erro ao baixar atualização: ' . print_r($temp_file, true));
        return false;
    }

    $theme_dir = get_template_directory();
    $temp_dir = WP_CONTENT_DIR . '/tmp';

    // Cria diretório temporário se não existir
    if (!file_exists($temp_dir)) {
        mkdir($temp_dir, 0755, true);
    }

    $result = unzip_file($temp_file['body'], $temp_dir);

    // Remove arquivo temporário
    unlink($temp_file['body']);

    // Verifica se a extração foi bem-sucedida
    if (is_wp_error($result)) {
        return false;
    }

    // Remove arquivos e pastas existentes do tema atual
    delete_directory($theme_dir);

    // Move os arquivos extraídos para o diretório do tema
    rename($temp_dir . '/' . basename($theme_dir), $theme_dir);

    // Atualiza a versão do tema
    update_option('theme_version', $theme_info['version']);

    return true;
}

// Função principal para atualizar o tema a partir do GitHub
function update_theme_from_github($github_username, $repository_name, $access_token) {
    $current_version = wp_get_theme()->get('Version');
    $theme_info = get_theme_info_from_github($github_username, $repository_name, $access_token);

    $update_available = check_for_theme_update($current_version, $theme_info);

    if ($update_available) {
        if (download_and_install_theme_update($update_available)) {
            return 'Tema atualizado com sucesso para a versão ' . $update_available['version'] . '.';
        } else {
            return 'Falha na atualização do tema.';
        }
    } else {
        return 'Nenhuma atualização disponível.';
    }
}

// Função para adicionar logging
function debug_theme_update_process($message) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log($message);
    }
}

// Função para verificar atualizações de tema
function check_for_theme_updates($checked_data, $github_username, $repository_name, $access_token) {
    if (empty($checked_data->checked)) {
        return $checked_data;
    }

    // Obter dados do tema atual
    $theme_data = wp_get_theme();
    $theme_slug = $theme_data->get_stylesheet();
    $theme_version = $theme_data->get('Version');

    // Verificar atualização
    $theme_info = get_theme_info_from_github($github_username, $repository_name, $access_token);
    $update_available = check_for_theme_update($theme_version, $theme_info);

    if ($update_available) {
        $checked_data->response[$theme_slug] = array(
            'new_version' => $update_available['version'],
            'package' => $update_available['download_url'],
            'url' => 'https://github.com/' . $github_username . '/' . $repository_name,
        );
    }

    return $checked_data;
}

// Função para adicionar informações adicionais sobre o tema
function theme_api_check($false, $action, $response, $github_username, $repository_name, $access_token) {
    if ($action === 'theme_information' && isset($response->slug) && $response->slug === $repository_name) {
        $theme_info = get_theme_info_from_github($github_username, $repository_name, $access_token);

        if ($theme_info) {
            $response = (object) array(
                'slug' => $repository_name,
                'name' => $repository_name,
                'version' => $theme_info['version'],
                'author' => 'Autor do Tema',
                'requires' => '5.0',
                'tested' => '5.9',
                'requires_php' => '7.0',
                'download_link' => $theme_info['download_url'],
                'sections' => array(
                    'description' => 'Descrição do tema.',
                    'changelog' => 'Notas da versão do tema.',
                ),
            );
        }
    }

    return $response;
}

// Função para adicionar hooks de atualização do tema
function add_theme_update_hooks($github_username, $repository_name, $access_token) {
    add_filter('pre_set_site_transient_update_themes', function($checked_data) use ($github_username, $repository_name, $access_token) {
        return check_for_theme_updates($checked_data, $github_username, $repository_name, $access_token);
    });

    add_filter('themes_api', function($false, $action, $response) use ($github_username, $repository_name, $access_token) {
        return theme_api_check($false, $action, $response, $github_username, $repository_name, $access_token);
    }, 10, 3);
}

?>