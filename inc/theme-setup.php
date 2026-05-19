<?php
/**
 * Configurações básicas e setup do tema Tecnoinfor.
 *
 * @package Tecnoinfor
 */

// Funções relacionadas à configuração do tema
function tecnoinfor_setup() {
    // Suporte a títulos gerenciados pelo WordPress
    add_theme_support('title-tag');

    // Suporte a thumbnails e excerpts para páginas
    add_theme_support('post-thumbnails');
    add_image_size('download_thumb', 1280, 720, true);
    add_image_size('post_thumb', 350, 200, true);
    add_image_size('related_post_thumb', 90, 90, true);
    add_image_size('cliente-thumbnail', 241, 200, true);
    add_post_type_support('page', 'thumbnail');
    add_post_type_support('page', 'excerpt');

    // Suporte para logotipo personalizado
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Registrar menus
    register_nav_menus(array(
        'primary'   => __('Primary Menu', 'tecnoinfor'),
        'secondary' => __('Institutional', 'tecnoinfor'),
        'three'     => __('Contact', 'tecnoinfor'),
        'fourth'    => __('Help', 'tecnoinfor'),
        'fifth'     => __('Support', 'tecnoinfor'), // Standardized to lowercase 'fifth'
    ));

    // Suporte à tradução
    load_theme_textdomain('tecnoinfor', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'tecnoinfor_setup');

// Função para personalizar o srcset dinamicamente
function personalizar_srcset_dinamico($sources, $size_array, $image_src, $image_meta, $attachment_id) {
    global $tecnoinfor_custom_srcset_sizes;

    if (!empty($tecnoinfor_custom_srcset_sizes) && is_array($tecnoinfor_custom_srcset_sizes)) {
        $sources = array();

        foreach ($tecnoinfor_custom_srcset_sizes as $size => $width) {
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

// Registrar e adicionar scripts e estilos ao tema
function tecnoinfor_enqueue_assets() {
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
    wp_enqueue_script('main-js', get_template_directory_uri().'/js/main.js', array('jquery'), '0.0.4', true );
    wp_localize_script('main-js', 'tecnoinforStrings', array(
        'readMore' => __('Read more', 'tecnoinfor'),
        'readLess' => __('Read less', 'tecnoinfor'),
        'ajaxurl'  => admin_url('admin-ajax.php'),
    ));
    wp_enqueue_script('custom-logo-script', get_template_directory_uri().'/js/custom-logo.js', array('jquery'), '0.0.3', true );
    wp_enqueue_script('tecnoinfor_empresa-admin', get_template_directory_uri().'/assets/js/empresa-admin.js', array('jquery'), '0.0.1', true );
}
add_action('wp_enqueue_scripts', 'tecnoinfor_enqueue_assets');
