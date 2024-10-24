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
        'secondary' => __('Institutional', 'tecnoinfor'),
        'three' => __('Contact', 'tecnoinfor'),
        'fourth' => __('Help', 'tecnoinfor'),
        'Fifth' => __('Support', 'tecnoinfor'),
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
        'name'              => _x('Subjects', 'taxonomy general name'),
        'singular_name'     => _x('Subject', 'taxonomy singular name'),
        'search_items'      => __('Search Subjects'),
        'all_items'         => __('All Subjects'),
        'parent_item'       => __('Parent Subject'),
        'parent_item_colon' => __('Parent Subject:'),
        'edit_item'         => __('Edit Subject'),
        'update_item'       => __('Update Subject'),
        'add_new_item'      => __('Add New Subject'),
        'new_item_name'     => __('New Subject Name'),
        'menu_name'         => __('Subjects'),
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

// Função para registrar o Custom Post Type Changelog
function custom_post_type_changelog() {
    $labels = array(
        'name' => 'Changelogs',
        'singular_name' => 'Changelog',
        'menu_name' => 'Changelogs',
        'name_admin_bar' => 'Changelog',
        'add_new' => 'Adicionar Novo',
        'add_new_item' => 'Adicionar Novo Changelog',
        'new_item' => 'Novo Changelog',
        'edit_item' => 'Editar Changelog',
        'view_item' => 'Ver Changelog',
        'all_items' => 'Todos os Changelogs',
        'search_items' => 'Pesquisar Changelog',
        'not_found' => 'Nenhum Changelog encontrado',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'changelogs'),
        'supports' => array('title', 'editor'),
        'show_in_rest' => true,  // Suporte ao editor Gutenberg
        'menu_position' => 5,
        'menu_icon' => 'dashicons-list-view',
    );

    register_post_type('changelog', $args);
}
add_action('init', 'custom_post_type_changelog');

function changelog_meta_boxes() {
    add_meta_box('changelog_details', 'Detalhes do Changelog', 'changelog_meta_callback', 'changelog', 'normal', 'high');
}
add_action('add_meta_boxes', 'changelog_meta_boxes');


function changelog_meta_callback($post) {
    // Recupera os valores dos campos meta
    $added = get_post_meta($post->ID, '_changelog_added', true);
    $fixed = get_post_meta($post->ID, '_changelog_fixed', true);
    $updated = get_post_meta($post->ID, '_changelog_updated', true);
    $improved = get_post_meta($post->ID, '_changelog_improved', true);
    $removed = get_post_meta($post->ID, '_changelog_removed', true);
    $deprecated = get_post_meta($post->ID, '_changelog_deprecated', true);
    $compatibility = get_post_meta($post->ID, '_changelog_compatibility', true);
    
    // Estilos CSS para melhorar a organização e visualização
    ?>
    <style>
        .changelog-meta-boxes {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        .changelog-meta-boxes label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        .changelog-meta-boxes textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            line-height: 1.5;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>

    <div class="changelog-meta-boxes">
        <div class="changelog-meta-field">
            <label for="changelog_added"><?php echo __('Added','tecnoinfor')?></label>
            <textarea name="changelog_added" id="changelog_added" rows="5" placeholder="<?php echo __('Ex.: New features or improvements','tecnoinfor')?>"><?php echo esc_textarea($added); ?></textarea>
        </div>

        <div class="changelog-meta-field">
            <label for="changelog_fixed"><?php echo __('Fixed','tecnoinfor')?></label>
            <textarea name="changelog_fixed" id="changelog_fixed" rows="5" placeholder="<?php echo __('Ex.: Bug fixes or known errors','tecnoinfor')?>"><?php echo esc_textarea($fixed); ?></textarea>
        </div>

        <div class="changelog-meta-field">
            <label for="changelog_updated"><?php echo __('Update','tecnoinfor')?></label>
            <textarea name="changelog_updated" id="changelog_updated" rows="5" placeholder="<?php echo __('Ex.: General feature updates','tecnoinfor')?>"><?php echo esc_textarea($updated); ?></textarea>
        </div>

        <div class="changelog-meta-field">
            <label for="changelog_improved"><?php echo __('Improved','tecnoinfor')?></label>
            <textarea name="changelog_improved" id="changelog_improved" rows="5" placeholder="<?php echo __('Ex.: Process or performance improvements','tecnoinfor')?>"><?php echo esc_textarea($improved); ?></textarea>
        </div>

        <div class="changelog-meta-field">
            <label for="changelog_removed"><?php echo __('Removed','tecnoinfor')?></label>
            <textarea name="changelog_removed" id="changelog_removed" rows="5" placeholder="<?php echo __('Ex.: Removed features or elements','tecnoinfor')?>"><?php echo esc_textarea($removed); ?></textarea>
        </div>

        <div class="changelog-meta-field">
            <label for="changelog_deprecated"><?php echo __('Deprecated','tecnoinfor')?></label>
            <textarea name="changelog_deprecated" id="changelog_deprecated" rows="5" placeholder="<?php echo __('Ex.: Features or elements Discontinued','tecnoinfor')?>"><?php echo esc_textarea($deprecated); ?></textarea>
        </div>

        <div class="changelog-meta-field">
            <label for="changelog_compatibility"><?php echo __('Compatibility','tecnoinfor')?></label>
            <textarea name="changelog_compatibility" id="changelog_compatibility" rows="5" placeholder="<?php echo __('Ex.: Compatible Features or Elements','tecnoinfor')?>"><?php echo esc_textarea($compatibility); ?></textarea>
        </div>
    </div>
    <?php
}

function get_changelog_meta($post_id) {
    return array(
        'added'        => get_post_meta($post_id, '_changelog_added', true),
        'fixed'        => get_post_meta($post_id, '_changelog_fixed', true),
        'updated'      => get_post_meta($post_id, '_changelog_updated', true),
        'improved'     => get_post_meta($post_id, '_changelog_improved', true),
        'removed'      => get_post_meta($post_id, '_changelog_removed', true),
        'deprecated'   => get_post_meta($post_id, '_changelog_deprecated', true),
        'compatibility'=> get_post_meta($post_id, '_changelog_compatibility', true)
    );
}
function save_changelog_meta($post_id) {
    // Salvar os campos personalizados
    if (isset($_POST['changelog_added'])) {
        update_post_meta($post_id, '_changelog_added', sanitize_textarea_field($_POST['changelog_added']));
    }
    if (isset($_POST['changelog_fixed'])) {
        update_post_meta($post_id, '_changelog_fixed', sanitize_textarea_field($_POST['changelog_fixed']));
    }
    if (isset($_POST['changelog_updated'])) {
        update_post_meta($post_id, '_changelog_updated', sanitize_textarea_field($_POST['changelog_updated']));
    }
    if (isset($_POST['changelog_improved'])) {
        update_post_meta($post_id, '_changelog_improved', sanitize_textarea_field($_POST['changelog_improved']));
    }
    if (isset($_POST['changelog_removed'])) {
        update_post_meta($post_id, '_changelog_removed', sanitize_textarea_field($_POST['changelog_removed']));
    }
    if (isset($_POST['changelog_deprecated'])) {
        update_post_meta($post_id, '_changelog_deprecated', sanitize_textarea_field($_POST['changelog_deprecated']));
    }
    if (isset($_POST['changelog_compatibility'])) {
        update_post_meta($post_id, '_changelog_compatibility', sanitize_textarea_field($_POST['changelog_compatibility']));
    }
}
add_action('save_post', 'save_changelog_meta');

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

