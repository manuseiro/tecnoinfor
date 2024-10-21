<?php
// Definir token do GitHub no wp-config.php
if (!defined('GITHUB_AUTH_TOKEN')) {
    define('GITHUB_AUTH_TOKEN', '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');
}

// Incluir arquivos necessários
require_once get_template_directory() . '/inc/theme-update-checker.php';
require_once get_template_directory() . '/inc/navbar_walker_custom.php';

// Funções relacionadas à configuração do tema
function tecnoinfor_setup()
{
    // Suporte a títulos gerenciados pelo WordPress
    add_theme_support('title-tag');

    // Suporte a thumbnails e excerpts para páginas
    add_theme_support('post-thumbnails');
    add_post_type_support('page', 'thumbnail');
    add_post_type_support('page', 'excerpt');

    // Suporte para logotipo personalizado
    add_theme_support('custom-logo', array(
        'height'      => 100, // altura do logotipo
        'width'       => 400, // largura do logotipo
        'flex-height' => true, // permite a altura flexível
        'flex-width'  => true, // permite a largura flexível
    ));

    // Registrar menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'tecnoinfor'),
    ));
    // Suporte à tradução
    load_theme_textdomain('tecnoinfor', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'tecnoinfor_setup');

// Adicionar suporte para atualização do tema via GitHub
function tecnoinfor_theme_update_setup()
{
    $github_username = 'manuseiro'; // Nome do usuário no github.com
    $repository_name = 'tecnoinfor'; // Nome do repositório usado para hospedar o tema

    add_theme_update_hooks($github_username, $repository_name, GITHUB_AUTH_TOKEN);
    // Opcional: Notificação visual para o administrador
    // notify_theme_update($github_username, $repository_name, GITHUB_AUTH_TOKEN);
}
add_action('after_setup_theme', 'tecnoinfor_theme_update_setup');

// Registrar e adicionar scripts e estilos ao tema
function tecnoinfor_enqueue_assets()
{
    // Estilos
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
    wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css');
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap');
    wp_enqueue_style('style', get_stylesheet_uri());

    // Scripts
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'tecnoinfor_enqueue_assets');

// Função para registrar o Custom Post Type "Clientes"
function registrar_cpt_clientes()
{
    $labels = array(
        'name'               => __('Clients', 'tecnoinfor'),
        'singular_name'      => __('Client', 'tecnoinfor'),
        'menu_name'          => __('Clients', 'tecnoinfor'),
        'name_admin_bar'     => __('Client', 'tecnoinfor'),
        'add_new'            => __('Add New', 'tecnoinfor'),
        'add_new_item'       => __('Add New Client', 'tecnoinfor'),
        'new_item'           => __('New Client', 'tecnoinfor'),
        'edit_item'          => __('Edit Client', 'tecnoinfor'),
        'view_item'          => __('View Client', 'tecnoinfor'),
        'all_items'          => __('All Clients', 'tecnoinfor'),
        'search_items'       => __('Search Clients', 'tecnoinfor'),
        'not_found'          => __('No clients found.', 'tecnoinfor'),
        'not_found_in_trash' => __('No clients found in trash.', 'tecnoinfor'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable'  => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-businessman',  // Ícone do menu
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'clientes'), // Slug da URL
        'show_in_rest'       => true,  // Habilita o Gutenberg
    );

    register_post_type('clientes', $args);
}
// Hook para registrar o Custom Post Type
add_action('init', 'registrar_cpt_clientes');

// Função para registrar o Custom Post Type "FAQs"
function registrar_cpt_faqs()
{
    $labels = array(
        'name' => _x('FAQs', 'post type general name'),
        'singular_name' => _x('FAQ', 'post type singular name'),
        'add_new' => _x('Add New FAQ', 'FAQ'),
        'add_new_item' => __('Add New FAQ'),
        'edit_item' => __('Edit FAQ'),
        'new_item' => __('New FAQ'),
        'view_item' => __('View FAQ'),
        'search_items' => __('Search FAQs'),
        'not_found' =>  __('No FAQs Found'),
        'not_found_in_trash' => __('No FAQs found in the Trash'),
        'menu_icon' => 'dashicons-info',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'faqs'),
        'capability_type' => 'post', // Alterado para 'post' para permissões mais amplas
        'hierarchical' => false,
        'menu_position' => 5,
        'supports' => array('title', 'editor'), // 'excerpt' pode ser removido
        'has_archive' => true,
    );

    register_post_type('faq', $args);
}

// Hook para registrar o Custom Post Type
add_action('init', 'registrar_cpt_faqs');


// Função para registrar a Taxonomia "Assunto"
function registrar_taxonomia_assunto() {
    $labels = array(
        'name'              => _x('Assuntos', 'taxonomy general name'),
        'singular_name'     => _x('Assunto', 'taxonomy singular name'),
        'search_items'      => __('Search Assuntos'),
        'all_items'         => __('All Assuntos'),
        'parent_item'       => __('Parent Assunto'),
        'parent_item_colon' => __('Parent Assunto:'),
        'edit_item'         => __('Edit Assunto'),
        'update_item'       => __('Update Assunto'),
        'add_new_item'      => __('Add New Assunto'),
        'new_item_name'     => __('New Assunto Name'),
        'menu_name'         => __('Assuntos'),
    );

    $args = array(
        'hierarchical'      => true, // Como categorias, use true para hierarquia
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'assunto'),
    );

    register_taxonomy('assunto', array('faq'), $args);
}

// Hook para registrar a Taxonomia
add_action('init', 'registrar_taxonomia_assunto');

// Função para registrar o Custom Post Type de Changelogs
function registrar_cpt_changelog()
{
    $labels = array(
        'name'                  => _x('Changelogs', 'Post type general name', 'tecnoinfor'),
        'singular_name'         => _x('Changelog', 'Post type singular name', 'tecnoinfor'),
        'menu_name'             => _x('Changelogs', 'Admin Menu text', 'tecnoinfor'),
        'name_admin_bar'        => _x('Changelog', 'Add New on Toolbar', 'tecnoinfor'),
        'add_new'               => __('Add New', 'tecnoinfor'),
        'add_new_item'          => __('Add New Changelog', 'tecnoinfor'),
        'new_item'              => __('New Changelog', 'tecnoinfor'),
        'edit_item'             => __('Edit Changelog', 'tecnoinfor'),
        'view_item'             => __('View Changelog', 'tecnoinfor'),
        'all_items'             => __('All Changelogs', 'tecnoinfor'),
        'search_items'          => __('Search Changelogs', 'tecnoinfor'),
        'not_found'             => __('No changelogs found.', 'tecnoinfor'),
        'not_found_in_trash'    => __('No changelogs found in Trash.', 'tecnoinfor'),
        'featured_image'        => _x('Changelog Cover Image', 'Overrides the “Featured Image” phrase', 'tecnoinfor'),
        'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase', 'tecnoinfor'),
        'remove_featured_image' => _x('Remove cover image', 'tecnoinfor'),
        'use_featured_image'    => _x('Use as cover image', 'tecnoinfor'),
        'archives'              => _x('Changelog archives', 'The post type archive label', 'tecnoinfor'),
        'insert_into_item'      => _x('Insert into changelog', 'Overrides the “Insert into post” phrase', 'tecnoinfor'),
        'uploaded_to_this_item' => _x('Uploaded to this changelog', 'Overrides the “Uploaded to this post” phrase', 'tecnoinfor'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'changelog'), // URL amigável
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-list-view', // Ícone do menu no painel
        'supports'           => array('title', 'editor', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true, // Habilita o editor Gutenberg
    );

    register_post_type('changelog', $args);
}

// Hook para registrar o CPT no 'init'
add_action('init', 'registrar_cpt_changelog');

// Adiciona meta box personalizada para Changelogs
function changelog_add_meta_box()
{
    add_meta_box(
        'changelog_meta_box',           // ID da meta box
        __('Changelog Details', 'tecnoinfor'), // Título da meta box
        'changelog_meta_box_callback',  // Função de callback que renderiza a box
        'changelog',                    // Custom Post Type onde a meta box será exibida
        'normal',                       // Posição (normal, side, etc.)
        'high'                          // Prioridade
    );
}
add_action('add_meta_boxes', 'changelog_add_meta_box');

// Função que renderiza os campos dentro da meta box
function changelog_meta_box_callback($post)
{
    // Adiciona um nonce para validação
    wp_nonce_field('save_changelog_meta_box_data', 'changelog_meta_box_nonce');

    // Recupera os valores atuais (se houver)
    $version = get_post_meta($post->ID, '_changelog_version', true);
    $release_date = get_post_meta($post->ID, '_changelog_release_date', true);

?>
    <p>
        <label for="changelog_version"><?php _e('Version', 'tecnoinfor'); ?></label>
        <input type="text" id="changelog_version" name="changelog_version" value="<?php echo esc_attr($version); ?>" />
    </p>
    <p>
        <label for="changelog_release_date"><?php _e('Release Date', 'tecnoinfor'); ?></label>
        <input type="date" id="changelog_release_date" name="changelog_release_date" value="<?php echo esc_attr($release_date); ?>" />
    </p>
<?php
}
// Salva os dados da meta box
function save_changelog_meta_box_data($post_id)
{
    // Verifica se o nonce é válido
    if (! isset($_POST['changelog_meta_box_nonce']) || ! wp_verify_nonce($_POST['changelog_meta_box_nonce'], 'save_changelog_meta_box_data')) {
        return;
    }

    // Verifica se o usuário pode salvar os dados
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (! current_user_can('edit_post', $post_id)) {
        return;
    }

    // Verifica se os campos estão presentes e salva os dados
    if (isset($_POST['changelog_version'])) {
        update_post_meta($post_id, '_changelog_version', sanitize_text_field($_POST['changelog_version']));
    }

    if (isset($_POST['changelog_release_date'])) {
        update_post_meta($post_id, '_changelog_release_date', sanitize_text_field($_POST['changelog_release_date']));
    }
}
add_action('save_post', 'save_changelog_meta_box_data');

// Função para adicionar a página de administração para traduções
function tecnoinfor_menu()
{
    add_menu_page(
        'Translation Settings',
        'Translation',
        'manage_options',
        'tecnoinfor-traducao',
        'tecnoinfor_traducao_page',
        'dashicons-translation',
        20
    );
}
add_action('admin_menu', 'tecnoinfor_menu');

// Função para a página de administração de traduções
function tecnoinfor_traducao_page()
{
    // Caminho para o arquivo .pot
    $pot_file = get_template_directory() . '/languages/tecnoinfor.pot';

    // Verifica se o arquivo .pot existe
    if (!file_exists($pot_file)) {
        echo '<div class="notice notice-error"><p>' . __('POT file not found.', 'tecnoinfor') . '</p></div>';
        return;
    }

    // Selecionar o idioma para traduções
    $selected_language = isset($_POST['language']) ? sanitize_text_field($_POST['language']) : 'pt_BR';
    $po_file = get_template_directory() . "/languages/{$selected_language}.po";

    // Criar o arquivo .po se não existir
    if (!file_exists($po_file)) {
        file_put_contents($po_file, '');
        echo '<div class="notice notice-warning"><p>' . __('PO file was created. Please add your translations now.', 'tecnoinfor') . '</p></div>';
    }

    // Processar o envio do formulário para adicionar/atualizar tradução
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['original_string'], $_POST['translated_string'])) {
        $original_string = sanitize_text_field($_POST['original_string']);
        $translated_string = sanitize_text_field($_POST['translated_string']);

        // Adicionar ou atualizar a tradução no arquivo .po
        $current_content = file_get_contents($po_file);
        $new_translation = "msgid \"$original_string\"\nmsgstr \"$translated_string\"\n\n";

        // Atualiza ou adiciona a tradução
        if (strpos($current_content, $original_string) !== false) {
            // Atualiza a tradução existente
            $current_content = preg_replace("/msgid \"$original_string\"\nmsgstr \".*?\"/", $new_translation, $current_content);
        } else {
            // Adiciona nova tradução
            $current_content .= $new_translation;
        }

        file_put_contents($po_file, $current_content);
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Translation saved successfully!', 'tecnoinfor') . '</p></div>';
    }

    // Processar exclusão de tradução
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_original_string'])) {
        $original_string_to_delete = sanitize_text_field($_POST['delete_original_string']);

        // Verifica se o arquivo .po existe antes de tentar ler
        if (!file_exists($po_file)) {
            echo '<div class="notice notice-error"><p>' . __('PO file not found. Cannot delete translation.', 'tecnoinfor') . '</p></div>';
            return;
        }

        // Ler conteúdo atual do arquivo .po
        $current_content = file_get_contents($po_file);

        // Remover a tradução
        $new_content = preg_replace("/msgid \"$original_string_to_delete\"\nmsgstr \".*?\"\n\n/", '', $current_content);

        // Verifica se a tradução foi removida com sucesso
        if ($new_content !== $current_content) {
            // Salvar o conteúdo atualizado de volta ao arquivo .po
            file_put_contents($po_file, $new_content);
            echo '<div class="notice notice-success is-dismissible"><p>' . __('Translation deleted successfully!', 'tecnoinfor') . '</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>' . __('Translation not found for deletion.', 'tecnoinfor') . '</p></div>';
        }
    }

    // Ler traduções do arquivo .po
    $translations = [];
    $content = file_get_contents($po_file);

    // Usar regex para extrair strings
    preg_match_all('/msgid "(.*?)"\s*msgstr "(.*?)"/', $content, $matches);
    $translations = array_combine($matches[1], $matches[2]);

?>
    <div class="wrap">
        <h1><?php _e('Translation Settings', 'tecnoinfor'); ?></h1>

        <form method="post" action="">
            <h2><?php _e('Select Language', 'tecnoinfor'); ?></h2>
            <select name="language" onchange="this.form.submit()">
                <option value="pt_BR" <?php selected($selected_language, 'pt_BR'); ?>>Português do Brasil</option>
                <option value="en_US" <?php selected($selected_language, 'en_US'); ?>>English (US)</option>
                <option value="es_ES" <?php selected($selected_language, 'es_ES'); ?>>Español</option>
                <!-- Adicione mais opções de idioma aqui -->
            </select>
        </form>

        <form method="post" action="">
            <h2><?php _e('Add or Update Translation', 'tecnoinfor'); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="original_string"><?php _e('Original String', 'tecnoinfor'); ?></label></th>
                    <td><input name="original_string" type="text" id="original_string" class="regular-text" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="translated_string"><?php _e('Translated String', 'tecnoinfor'); ?></label></th>
                    <td><input name="translated_string" type="text" id="translated_string" class="regular-text" required></td>
                </tr>
            </table>
            <?php submit_button(__('Save Translation', 'tecnoinfor')); ?>
        </form>

        <hr>

        <h2><?php _e('Existing Translations', 'tecnoinfor'); ?></h2>
        <table class="widefat">
            <thead>
                <tr>
                    <th><?php _e('Original String', 'tecnoinfor'); ?></th>
                    <th><?php _e('Translated String', 'tecnoinfor'); ?></th>
                    <th><?php _e('Actions', 'tecnoinfor'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Exibir as traduções existentes
                if ($translations) {
                    foreach ($translations as $original => $translated) {
                        echo '<tr>';
                        echo '<td>' . esc_html($original) . '</td>';
                        echo '<td>';
                        echo '<form method="post" action="" style="display:inline;">';
                        echo '<input type="hidden" name="original_string" value="' . esc_attr($original) . '">';
                        echo '<input type="text" name="translated_string" value="' . esc_attr($translated) . '" class="regular-text" required>';
                        echo '</td>';
                        echo '<td>';
                        echo '<input type="submit" value="' . __('Update', 'tecnoinfor') . '" class="button button-secondary">';
                        echo '</form>';
                        echo '<form method="post" action="" style="display:inline;">';
                        echo '<input type="hidden" name="delete_original_string" value="' . esc_attr($original) . '">';
                        echo '<input type="submit" value="' . __('Delete', 'tecnoinfor') . '" class="button button-secondary" onclick="return confirm(\'Are you sure you want to delete this translation?\');">';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="3">' . __('No translations found.', 'tecnoinfor') . '</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
<?php
}

