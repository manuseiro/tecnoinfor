<?php
/**
 * Theme Update Checker
 *
 * Verifica e atualiza temas WordPress a partir de um repositório GitHub.
 */

 function get_theme_info_from_github($github_username, $repository_name, $access_token = null) {
    if (empty($access_token) && !defined('GITHUB_AUTH_TOKEN')) {
        error_log('Erro: Token de autenticação do GitHub não definido.');
        return false;
    }
    $access_token = $access_token ?: GITHUB_AUTH_TOKEN;

    $api_url = "https://api.github.com/repos/$github_username/$repository_name/releases/latest";

    $response = wp_remote_get($api_url, array(
        'headers' => array(
            'Accept' => 'application/vnd.github.v3+json',
            'User-Agent' => 'WordPress Theme Update Checker',
            'Authorization' => "token $access_token",
        ),
    ));

    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        error_log('Erro ao consultar o GitHub: Código de resposta ' . wp_remote_retrieve_response_code($response));
        return false;
    }

    $data = json_decode(wp_remote_retrieve_body($response));
    if (!$data || empty($data->tag_name) || empty($data->zipball_url)) {
        error_log('Erro ao obter dados do GitHub: ' . print_r($data, true));
        return false;
    }

    return [
        'version' => $data->tag_name,
        'download_url' => $data->zipball_url,
        'access_token' => $access_token
    ];
}

function check_for_theme_update($current_version, $theme_info) {
    return ($theme_info && version_compare($current_version, $theme_info['version'], '<')) ? $theme_info : false;
}

// Função para deletar um diretório recursivamente
function delete_directory($dir) {
    if (!is_dir($dir)) {
        return false;
    }
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        $file_path = $dir . DIRECTORY_SEPARATOR . $file;
        is_dir($file_path) ? delete_directory($file_path) : unlink($file_path);
    }
    return rmdir($dir);
}

function download_and_install_theme_update($theme_info) {
    if (!$theme_info || empty($theme_info['download_url'])) {
        error_log('Erro: URL de download inválida.');
        return false;
    }

    // Faz o download do arquivo ZIP
    $temp_file = wp_remote_get($theme_info['download_url']);

    if (is_wp_error($temp_file) || wp_remote_retrieve_response_code($temp_file) !== 200) {
        error_log('Erro ao baixar atualização: Código ' . wp_remote_retrieve_response_code($temp_file));
        return false;
    }

    $theme_dir = get_template_directory();
    $temp_dir = WP_CONTENT_DIR . '/tmp';

    if (!is_writable($theme_dir)) {
        error_log('Erro: Sem permissão para criar diretórios no WP_CONTENT_DIR.');
        return false;
    }
    
    if (!is_dir($temp_dir) && !@mkdir($temp_dir, 0755, true)) {
        error_log('Erro ao criar diretório temporário.');
        return false;
    }

    $temp_zip = $temp_dir . '/theme-update.zip';
    file_put_contents($temp_zip, wp_remote_retrieve_body($temp_file));

    $result = unzip_file($temp_zip, $temp_dir);
    unlink($temp_zip);

    if (is_wp_error($result)) {
        error_log('Erro ao descompactar o arquivo ZIP.');
        return false;
    }

    $backup_dir = $theme_dir . '-backup';
    $counter = 1;
while (file_exists($backup_dir . "-$counter")) {
    $counter++;
}
$backup_dir .= "-$counter";

if (!rename($theme_dir, $backup_dir)) {
    error_log('Erro ao criar backup do tema antigo.');
    return false;
}

    if (!rename($temp_dir . '/' . basename($theme_dir), $theme_dir)) {
        rename($backup_dir, $theme_dir); // Restaurar backup se falhar
        error_log('Erro ao substituir o tema antigo pelo novo.');
        return false;
    }

    delete_directory($backup_dir); // Excluir backup se tudo deu certo
    update_option('theme_version', $theme_info['version']);

    return true;
}


function update_theme_from_github($github_username, $repository_name, $access_token = GITHUB_AUTH_TOKEN) {
    $current_version = wp_get_theme()->get('Version');
    $theme_info = get_theme_info_from_github($github_username, $repository_name, $access_token);

    $update_available = check_for_theme_update($current_version, $theme_info);

    if ($update_available) {
        return download_and_install_theme_update($update_available)
            ? 'Tema atualizado com sucesso para a versão ' . $update_available['version'] . '.'
            : 'Falha na atualização do tema.';
    } else {
        return 'Nenhuma atualização disponível.';
    }
}

function debug_theme_update_process($message) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log($message);
    }
}

function check_for_theme_updates($checked_data, $github_username, $repository_name, $access_token) {
    if (empty($checked_data->checked)) return $checked_data;

    $theme_data = wp_get_theme();
    $theme_slug = $theme_data->get_stylesheet();
    $theme_version = $theme_data->get('Version');

    $theme_info = get_theme_info_from_github($github_username, $repository_name, $access_token);
    $update_available = check_for_theme_update($theme_version, $theme_info);

    if ($update_available) {
        $checked_data->response[$theme_slug] = [
            'new_version' => $update_available['version'],
            'package' => $update_available['download_url'],
            'url' => "https://github.com/$github_username/$repository_name",
        ];
    }

    return $checked_data;
}

function theme_api_check($false, $action, $response, $github_username, $repository_name, $access_token) {
    if ($action === 'theme_information' && isset($response->slug) && $response->slug === $repository_name) {
        $theme_info = get_theme_info_from_github($github_username, $repository_name, $access_token);

        if ($theme_info) {
            $theme_data = wp_get_theme();
return (object) [
    'slug' => $repository_name,
    'name' => $repository_name,
    'version' => $theme_info['version'],
    'author' => 'Autor do Tema',
    'requires' => $theme_data->get('RequiresWP') ?: '5.0',
    'tested' => $theme_data->get('TestedUpTo') ?: '6.0',
    'requires_php' => $theme_data->get('RequiresPHP') ?: '7.0',
    'download_link' => $theme_info['download_url'],
    'sections' => [
        'description' => 'Descrição do tema.',
        'changelog' => 'Notas da versão do tema.',
    ],
];
        }
    }

    return $response;
}

function add_theme_update_hooks($github_username, $repository_name, $access_token = GITHUB_AUTH_TOKEN) {
    add_filter('pre_set_site_transient_update_themes', fn($checked_data) => check_for_theme_updates($checked_data, $github_username, $repository_name, $access_token));
    add_filter('themes_api', fn($false, $action, $response) => theme_api_check($false, $action, $response, $github_username, $repository_name, $access_token), 10, 3);
}
?>