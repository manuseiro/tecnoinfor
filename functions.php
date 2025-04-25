<?php

// Definir a constante para o caminho base das funções
define('TECNOINFOR_INC', get_template_directory() . '/inc/');
define('TECNOINFOR_FUNCTIONS', TECNOINFOR_INC . 'functions/');
// Incluir arquivos necessários usando a constante
require_once TECNOINFOR_INC . 'theme-update-checker.php';
require_once TECNOINFOR_INC . 'navbar_walker_custom.php';
//require_once TECNOINFOR_INC . 'theme-admin-functions.php';
require_once TECNOINFOR_INC . 'theme-custom-business.php';
// Função para registrar os Custom Post Type 'Planos'
require_once TECNOINFOR_FUNCTIONS . 'cpt_planos.php';
// Função para registrar os Custom Post Type e Taxonomia do 'Clientes'
require_once TECNOINFOR_FUNCTIONS . 'cpt_clientes.php';
// Função para registrar o Custom Post Type e Taxonomia do 'Depoimentos'
require_once TECNOINFOR_FUNCTIONS . 'cpt_depoimentos.php';
// Função para registrar o Custom Post Type e Taxonomia do 'faqs'
require_once TECNOINFOR_FUNCTIONS . 'cpt_faqs.php';
// Função para registrar o Custom Post Type e Taxonomia 'Downloads'
require_once TECNOINFOR_FUNCTIONS . 'cpt_downloads.php';
// Função para registrar o Custom Post Type e Taxonomia 'Changelog'
require_once TECNOINFOR_FUNCTIONS . 'cpt_changelogs.php';
// Função para registrar o Custom Post Type e Taxonomia 'Changelog'
require_once TECNOINFOR_FUNCTIONS . 'cpt_software.php';
// Função para registrar o Custom Post Type e Taxonomia 'About'
require_once TECNOINFOR_FUNCTIONS . 'cpt_about.php';


// Funções relacionadas à configuração do tema
function tecnoinfor_setup(){
    // Suporte a títulos gerenciados pelo WordPress
    add_theme_support('title-tag');

    // Suporte a thumbnails e excerpts para páginas
    add_theme_support('post-thumbnails');
    add_image_size( 'download_thumb', 1280, 720, true );
    add_image_size( 'post_thumb', 350, 200, true );
    add_image_size( 'related_post_thumb', 90, 90, true );
    add_image_size( 'cliente-thumbnail', 241, 200, true );
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
// Função para personalizar o srcset dinamicamente
function personalizar_srcset_dinamico($sources, $size_array, $image_src, $image_meta, $attachment_id) {
    // Verifica se os tamanhos personalizados foram definidos globalmente
    global $custom_srcset_sizes;

    if (!empty($custom_srcset_sizes) && is_array($custom_srcset_sizes)) {
        $sources = array(); // Limpa o srcset padrão

        foreach ($custom_srcset_sizes as $size => $width) {
            $image = wp_get_attachment_image_src($attachment_id, $size);
            if ($image) {
                $sources[$width] = array(
                    'url'        => $image[0],
                    'descriptor' => 'w',
                    'value'      => $width,
                );
            }
        }
    }

    return $sources;
}

// Função auxiliar para configurar os tamanhos dinamicamente
function configurar_srcset_dinamico($attr, $attachment, $size) {
    global $tecnoinfor_custom_srcset_sizes;
    $default_sizes = array(
        'custom_thumb' => 1200,
        'custom_medio' => 800,
        'custom_pequeno' => 400,
    );
    $tecnoinfor_custom_srcset_sizes = isset($attr['srcset_sizes']) && is_array($attr['srcset_sizes']) ? $attr['srcset_sizes'] : $default_sizes;
    add_filter('wp_calculate_image_srcset', 'personalizar_srcset_dinamico', 10, 5);
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'configurar_srcset_dinamico', 10, 3);

// Limpa a variável global após o uso
function limpar_srcset_dinamico() {
    global $tecnoinfor_custom_srcset_sizes;
    $tecnoinfor_custom_srcset_sizes = null;
    remove_filter('wp_calculate_image_srcset', 'personalizar_srcset_dinamico', 10);
}
add_action('wp_footer', 'limpar_srcset_dinamico');
//Função para exibir o breadcrumb das paginas, categoria e posts do tema
function tecnoinfor_get_breadcrumb() {
    $output = '<nav aria-label="breadcrumb"><ol class="breadcrumb fw-bolder text-white text-shadow">';
    $output .= '<li class="breadcrumb-item"><a href="' . esc_url(home_url('/')) . '">' . __('Home', 'tecnoinfor') . '</a></li>';

    if (is_home() || is_front_page()) {
        if (is_home() && ($blog_page_id = get_option('page_for_posts')) && get_post($blog_page_id)) {
            $output .= '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title($blog_page_id)) . '</li>';
        } else {
            $output .= '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_bloginfo('name')) . '</li>';
        }
    } elseif (is_singular()) {
        $post_type = get_post_type();
        $post_type_obj = get_post_type_object($post_type);
        
        if ($post_type_obj && !is_wp_error($post_type_obj)) {
            $archive_link = get_post_type_archive_link($post_type);
            if ($archive_link) {
                $output .= '<li class="breadcrumb-item"><a href="' . esc_url($archive_link) . '">' . esc_html($post_type_obj->labels->name) . '</a></li>';
            }
        }

        if ($post_type === 'post') {
            $blog_page_id = get_option('page_for_posts');
            if ($blog_page_id) {
                $output .= '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink($blog_page_id)) . '">' . esc_html(get_the_title($blog_page_id)) . '</a></li>';
            }
            $categories = get_the_category();
            if ($categories && !is_wp_error($categories)) {
                $category = reset($categories);
                $output .= '<li class="breadcrumb-item"><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></li>';
            }
        } else {
            $taxonomies = get_object_taxonomies($post_type, 'objects');
            $priority_taxonomy = apply_filters('tecnoinfor_breadcrumb_taxonomy', reset($taxonomies), $post_type);
            if ($priority_taxonomy) {
                $terms = get_the_terms(get_the_ID(), $priority_taxonomy->name);
                if ($terms && !is_wp_error($terms)) {
                    $term = reset($terms); // Apenas o primeiro termo
                    $output .= '<li class="breadcrumb-item"><a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a></li>';
                }
            }
        }
        $output .= '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title()) . '</li>';
    } elseif (is_post_type_archive()) {
        $post_type = get_queried_object();
        if ($post_type && isset($post_type->labels->name)) {
            $output .= '<li class="breadcrumb-item active" aria-current="page">' . esc_html($post_type->labels->name) . '</li>';
        } else {
            $output .= '<li class="breadcrumb-item active" aria-current="page">' . __('Archive', 'tecnoinfor') . '</li>';
        }
    } elseif (is_tax()) {
        $term = get_queried_object();
        $post_type = get_taxonomy($term->taxonomy)->object_type[0];
        $output .= '<li class="breadcrumb-item"><a href="' . esc_url(get_post_type_archive_link($post_type)) . '">' . esc_html(get_post_type_object($post_type)->labels->name) . '</a></li>';
        if ($term->parent) {
            $ancestors = get_ancestors($term->term_id, $term->taxonomy);
            foreach (array_reverse($ancestors) as $ancestor_id) {
                $ancestor = get_term($ancestor_id, $term->taxonomy);
                $output .= '<li class="breadcrumb-item"><a href="' . esc_url(get_term_link($ancestor)) . '">' . esc_html($ancestor->name) . '</a></li>';
            }
        }
        $output .= '<li class="breadcrumb-item active" aria-current="page">' . esc_html($term->name) . '</li>';
    } elseif (is_page() && !is_front_page()) {
        global $post;
        if ($post->post_parent) {
            $ancestors = array_reverse(get_post_ancestors($post->ID));
            foreach ($ancestors as $ancestor) {
                $output .= '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink($ancestor)) . '">' . esc_html(get_the_title($ancestor)) . '</a></li>';
            }
        }
        $output .= '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title()) . '</li>';
    } elseif (is_search()) {
        $output .= '<li class="breadcrumb-item active" aria-current="page">' . __('Search Results for: ', 'tecnoinfor') . esc_html(get_search_query()) . '</li>';
    } elseif (is_404()) {
        $output .= '<li class="breadcrumb-item active" aria-current="page">' . __('404 - Not Found', 'tecnoinfor') . '</li>';
    }

    $output .= '</ol></nav>';
    return apply_filters('tecnoinfor_breadcrumb_output', $output);
}

//Função para retornar copyright anos, nome, descrição do site e direitos reservados.
function tecnoinfor_copyright() {
    global $wpdb;

    // Recupera o nome e a descrição do site com fallbacks
    $company_name = get_bloginfo('name') ?: __('Unnamed Site', 'tecnoinfor');
    $slogan = get_bloginfo('description') ?: __('No description set', 'tecnoinfor');

    // Texto padrão para "Todos os direitos reservados" com suporte a tradução
    $rights_reserved_text = __("All rights reserved", "tecnoinfor");

    // Consulta ao banco para obter os anos da primeira e última postagem publicada
    $copyright_dates = $wpdb->get_results("
        SELECT
        YEAR(MIN(post_date_gmt)) AS firstdate,
        YEAR(MAX(post_date_gmt)) AS lastdate
        FROM
        $wpdb->posts
        WHERE
        post_status = 'publish'
        AND post_date_gmt > '1970-01-01'
    ");

    // Verificação de datas válidas
    if ($copyright_dates && !empty($copyright_dates[0]->firstdate) && $copyright_dates[0]->firstdate > 0) {
        $first_year = $copyright_dates[0]->firstdate;
        $last_year = $copyright_dates[0]->lastdate;
        $year_range = ($first_year != $last_year) ? "$first_year - $last_year" : $first_year;
    } else {
        $year_range = date("Y");
        error_log("Tecnoinfor: Falha ao recuperar datas de publicação. Usando ano atual: $year_range");
    }

    // Monta o texto final do rodapé
    $output = sprintf("© %s | %s - %s | %s", $year_range, $company_name, $slogan, $rights_reserved_text);

    return $output;
}
//Função de notificação de atualização do tema, conforme função definida no arquivo theme-update-checker.php
function notify_theme_update($github_username, $repository_name, $access_token) {
    $current_version = wp_get_theme()->get('Version');
    $theme_info = get_theme_info_from_github($github_username, $repository_name, $access_token);
    $update_available = check_for_theme_update($current_version, $theme_info);

    if ($update_available) {
        add_action('admin_notices', function() use ($update_available) {
            echo '<div class="notice notice-info is-dismissible">';
            echo '<p>Atualização disponível para o tema! Nova versão: ' . esc_html($update_available['version']) . '</p>';
            echo '</div>';
        });
    }
}
// Ajuste na função principal
function tecnoinfor_theme_update_setup() {
    if (!defined('GITHUB_AUTH_TOKEN')) {
        error_log('Erro: GITHUB_AUTH_TOKEN não definido no wp-config.php.');
        return;
    }

    add_theme_update_hooks('manuseiro', 'tecnoinfor');
    notify_theme_update('manuseiro', 'tecnoinfor', GITHUB_AUTH_TOKEN);
}
add_action('after_setup_theme', 'tecnoinfor_theme_update_setup');

// Registrar e adicionar scripts e estilos ao tema
function tecnoinfor_enqueue_assets(){
    // Estilos
    wp_enqueue_style('tecnoinfor_bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
    wp_enqueue_style('tecnoinfor_bootstrap-custom', get_template_directory_uri().'/css/custom.css');
    wp_enqueue_style('tecnoinfor_bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css');
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap');
    wp_enqueue_style('animate-css', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');
    
    wp_enqueue_style('tecnoinfor_empresa-admin', get_template_directory_uri().'/assets/css/empresa-admin.css');
    wp_enqueue_style('style', get_stylesheet_uri());

    // Scripts
    wp_enqueue_script('tecnoinfor_bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
    wp_enqueue_script('tecnoinfor_mask-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js', array('jquery'), '1.14.16', true);    
    wp_enqueue_script('wow', get_template_directory_uri().'/js/wow.min.js', array('jquery'), '1.1.3', true );
    wp_enqueue_script('main-js', get_template_directory_uri().'/js/main.js', array('jquery'), '0.0.3', true );
    wp_enqueue_script('custom-logo-script', get_template_directory_uri().'/js/custom-logo.js', array('jquery'), '0.0.3', true );
    wp_enqueue_script('tecnoinfor_empresa-admin', get_template_directory_uri().'/assets/js/empresa-admin.js', array('jquery'), '0.0.1', true );
}
add_action('wp_enqueue_scripts', 'tecnoinfor_enqueue_assets');
// Função para adicionar a página de administração para traduções
function tecnoinfor_menu(){
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
function tecnoinfor_traducao_page(){
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
                <th scope="row"><label for="translated_string"><?php _e('Translated String', 'tecnoinfor'); ?></label>
                </th>
                <td><input name="translated_string" type="text" id="translated_string" class="regular-text" required>
                </td>
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
// Status Sobre a Empresa
function tecnoinfor_customize_register($wp_customize) {
    $wp_customize->add_section('tecnoinfor_about_stats', [
      'title'    => __('About Stats', 'tecnoinfor'),
      'priority' => 30,
    ]);
  
    $stats = [
      'clients' => __('Satisfied Clients', 'tecnoinfor'),
      'years'   => __('Years of Experience', 'tecnoinfor'),
      'contracts' => __('Managed Contracts', 'tecnoinfor'),
    ];
  
    foreach ($stats as $key => $label) {
      $wp_customize->add_setting("tecnoinfor_$key", [
        'default'           => $key === 'clients' ? 125 : ($key === 'years' ? 15 : 21562),
        'sanitize_callback' => 'absint',
      ]);
  
      $wp_customize->add_control("tecnoinfor_$key", [
        'label'   => $label,
        'section' => 'tecnoinfor_about_stats',
        'type'    => 'number',
      ]);
    }
  }
  add_action('customize_register', 'tecnoinfor_customize_register');
//Função para adicionar Metas tags Dinamicas
  function tecnoinfor_dynamic_seo() {
    if (is_page_template('archive-downloads.php')) {
        $page_title = isset($_GET['category']) ? sprintf(__('Downloads - %s', 'tecnoinfor'), get_term_by('slug', sanitize_text_field($_GET['category']), 'download_category')->name) : __('All Downloads', 'tecnoinfor');
        $description = isset($_GET['category']) ? sprintf(__('Browse downloads in the %s category at %s.', 'tecnoinfor'), get_term_by('slug', sanitize_text_field($_GET['category']), 'download_category')->name, get_bloginfo('name')) : __('Explore all available downloads at %s.', 'tecnoinfor', get_bloginfo('name'));
        $canonical_url = get_permalink() . (isset($_GET['category']) ? '?category=' . sanitize_text_field($_GET['category']) : '');

        // Meta Tags
        echo '<title>' . esc_html($page_title) . ' | ' . esc_html(get_bloginfo('name')) . '</title>';
        echo '<meta name="description" content="' . esc_attr($description) . '">';
        echo '<meta name="robots" content="index, follow">';
        echo '<link rel="canonical" href="' . esc_url($canonical_url) . '">';

        // Open Graph
        echo '<meta property="og:title" content="' . esc_attr($page_title) . '">';
        echo '<meta property="og:description" content="' . esc_attr($description) . '">';
        echo '<meta property="og:type" content="website">';
        echo '<meta property="og:url" content="' . esc_url($canonical_url) . '">';
        echo '<meta property="og:site_name" content="' . esc_html(get_bloginfo('name')) . '">';
        echo '<meta property="og:image" content="' . esc_url(get_template_directory_uri() . '/assets/images/default-banner.jpg') . '">';

        // Twitter Cards
        echo '<meta name="twitter:card" content="summary_large_image">';
        echo '<meta name="twitter:title" content="' . esc_attr($page_title) . '">';
        echo '<meta name="twitter:description" content="' . esc_attr($description) . '">';
        echo '<meta name="twitter:image" content="' . esc_url(get_template_directory_uri() . '/assets/images/default-banner.jpg') . '">';

        // Schema CollectionPage
        ?>
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "CollectionPage",
            "name": "<?php echo esc_js($page_title); ?>",
            "description": "<?php echo esc_js($description); ?>",
            "url": "<?php echo esc_url($canonical_url); ?>",
            "publisher": {
                "@type": "Organization",
                "name": "<?php bloginfo('name'); ?>",
                "url": "<?php echo esc_url(home_url('/')); ?>"
            }
        }
        </script>
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
                {
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Home",
                    "item": "<?php echo esc_url(home_url('/')); ?>"
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "Downloads",
                    "item": "<?php echo esc_url(get_permalink()); ?>"
                }
                <?php if (isset($_GET['category'])) : ?>
                ,{
                    "@type": "ListItem",
                    "position": 3,
                    "name": "<?php echo esc_js(get_term_by('slug', sanitize_text_field($_GET['category']), 'download_category')->name); ?>",
                    "item": "<?php echo esc_url(add_query_arg('category', sanitize_text_field($_GET['category']), get_permalink())); ?>"
                }
                <?php endif; ?>
            ]
        }
        </script>
        <?php
    }
}
add_action('wp_head', 'tecnoinfor_dynamic_seo');