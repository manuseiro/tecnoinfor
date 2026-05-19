<?php
/**
 * Theme Update Checker
 *
 * Verifica e atualiza temas WordPress a partir de um repositório GitHub.
 */

function get_theme_info_from_github($github_username, $repository_name, $access_token = null)
{
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
        error_log('Resposta completa: ' . print_r($response, true));
        return false;
    }

    $data = json_decode(wp_remote_retrieve_body($response));
    if (!$data || empty($data->tag_name) || empty($data->zipball_url)) {
        error_log('Erro ao obter dados do GitHub: ' . print_r($data, true));
        return false;
    }

    debug_theme_update_process("Informações do GitHub obtidas: Versão {$data->tag_name}, URL: {$data->zipball_url}");
    return [
        'version' => $data->tag_name,
        'download_url' => $data->zipball_url,
        'access_token' => $access_token
    ];
}

function check_for_theme_update($current_version, $theme_info)
{
    return ($theme_info && version_compare($current_version, $theme_info['version'], '<')) ? $theme_info : false;
}

function delete_directory($dir)
{
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

function download_and_install_theme_update($theme_info)
{
    if (!$theme_info || empty($theme_info['download_url']) || empty($theme_info['access_token'])) {
        error_log('Erro: URL de download ou token de acesso inválidos.');
        return false;
    }

    debug_theme_update_process("Iniciando download da URL: {$theme_info['download_url']}");

    // Faz o download do arquivo ZIP com autenticação
    $temp_file = wp_remote_get($theme_info['download_url'], array(
        'headers' => array(
            'Authorization' => 'token ' . $theme_info['access_token'],
            'Accept' => 'application/vnd.github.v3+json',
            'User-Agent' => 'WordPress Theme Update Checker',
        ),
    ));

    if (is_wp_error($temp_file) || wp_remote_retrieve_response_code($temp_file) !== 200) {
        error_log('Erro ao baixar atualização: Código ' . wp_remote_retrieve_response_code($temp_file));
        error_log('Resposta completa: ' . print_r($temp_file, true));
        return false;
    }

    debug_theme_update_process("Download concluído com sucesso.");

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
        error_log('Erro ao descompactar o arquivo ZIP: ' . $result->get_error_message());
        return false;
    }

    debug_theme_update_process("Arquivo ZIP descompactado com sucesso.");

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

    debug_theme_update_process("Tema substituído com sucesso.");

    delete_directory($backup_dir); // Excluir backup se tudo deu certo
    update_option('theme_version', $theme_info['version']);

    debug_theme_update_process("Atualização concluída: Versão {$theme_info['version']}");

    return true;
}

function update_theme_from_github($github_username, $repository_name, $access_token = GITHUB_AUTH_TOKEN)
{
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

function debug_theme_update_process($message)
{
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log($message);
    }
}

function check_for_theme_updates($checked_data, $github_username, $repository_name, $access_token)
{
    if (empty($checked_data->checked)) {
        return $checked_data;
    }

    $theme_data = wp_get_theme();
    $theme_slug = $theme_data->get_stylesheet();
    $theme_version = $theme_data->get('Version');

    $theme_info = get_theme_info_from_github($github_username, $repository_name, $access_token);
    $update_available = check_for_theme_update($theme_version, $theme_info);

    if ($update_available) {
        $checked_data->response[$theme_slug] = [
            'new_version' => $update_available['version'],
            'package' => '', // Deixe vazio para evitar o download padrão
            'url' => "https://github.com/$github_username/$repository_name",
        ];
        debug_theme_update_process("Atualização disponível para $theme_slug: Nova versão {$update_available['version']}");
        // Armazene as informações da atualização
        update_option('tecnoinfor_update_info', $update_available);
    } else {
        debug_theme_update_process("Nenhuma atualização disponível para $theme_slug. Versão atual: $theme_version");
    }

    return $checked_data;
}

function theme_api_check($false, $action, $response, $github_username, $repository_name, $access_token)
{
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

function add_theme_update_hooks($github_username, $repository_name, $access_token = GITHUB_AUTH_TOKEN)
{
    add_filter('pre_set_site_transient_update_themes', fn($checked_data) => check_for_theme_updates($checked_data, $github_username, $repository_name, $access_token));
    add_filter('themes_api', fn($false, $action, $response) => theme_api_check($false, $action, $response, $github_username, $repository_name, $access_token), 10, 3);
}

// Intercepta o processo de download do WordPress
add_filter('upgrader_pre_download', function ($reply, $package, $upgrader) {
    // Verifica se estamos atualizando o tema Tecnoinfor
    if (isset($upgrader->skin->theme) && $upgrader->skin->theme === 'tecnoinfor') {
        // Recupera as informações da atualização
        $update_info = get_option('tecnoinfor_update_info', false);
        if ($update_info && !empty($update_info['download_url']) && !empty($update_info['access_token'])) {
            // Executa o download e instalação personalizados
            $result = download_and_install_theme_update($update_info);
            if ($result) {
                // Retorna o caminho do diretório do tema atualizado
                return get_template_directory();
            } else {
                return new WP_Error('download_failed', __('Falha ao baixar e instalar a atualização do tema.', 'tecnoinfor'));
            }
        } else {
            return new WP_Error('no_update_info', __('Informações de atualização não encontradas.', 'tecnoinfor'));
        }
    }
    return $reply;
}, 10, 3);

// Adiciona um menu no admin para atualização manual
function tecnoinfor_add_update_menu()
{
    add_submenu_page(
        'themes.php',
        'Atualizar Tema Tecnoinfor',
        'Atualizar Tema',
        'manage_options',
        'tecnoinfor-update',
        'tecnoinfor_update_page'
    );
}
add_action('admin_menu', 'tecnoinfor_add_update_menu');

function tecnoinfor_update_page()
{
    if (isset($_POST['update_theme']) && check_admin_referer('tecnoinfor_update_theme')) {
        $result = update_theme_from_github('manuseiro', 'tecnoinfor', GITHUB_AUTH_TOKEN);
        echo '<div class="notice notice-' . ($result === 'Nenhuma atualização disponível.' ? 'info' : ($result ? 'success' : 'error')) . '"><p>' . esc_html($result) . '</p></div>';
    }
    ?>
    <div class="wrap">
        <h1>Atualizar Tema Tecnoinfor</h1>
        <form method="post">
            <?php wp_nonce_field('tecnoinfor_update_theme'); ?>
            <p><input type="submit" name="update_theme" class="button button-primary" value="Verificar e Atualizar Tema">
            </p>
        </form>
    </div>
    <?php
}
?>