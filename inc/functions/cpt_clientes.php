<?php
// Função para registrar o Custom Post Type "Clientes"
function registrar_cpt_clientes(){
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
        'publicly_queryable' => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-businessman',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'has_archive'        => true, // Alterado para true
        'hierarchical'       => false,
        'rewrite'            => array('slug' => 'clientes', 'with_front' => false),
        'capability_type'    => 'post',
        'show_in_rest'       => true,
        'query_var'          => true,
    );

    register_post_type('clientes', $args);
}
add_action('init', 'registrar_cpt_clientes');

// Função para registrar a Taxonomia "Tipo Cliente"
function registrar_taxonomia_tipo_cliente() {
    $labels = array(
        'name'              => _x('Types', 'taxonomy general name', 'tecnoinfor'),
        'singular_name'     => _x('Type', 'taxonomy singular name', 'tecnoinfor'),
        'search_items'      => __('Search Types', 'tecnoinfor'),
        'all_items'         => __('All Types', 'tecnoinfor'),
        'parent_item'       => __('Parent Type', 'tecnoinfor'),
        'parent_item_colon' => __('Parent Type:', 'tecnoinfor'),
        'edit_item'         => __('Edit Type', 'tecnoinfor'),
        'update_item'       => __('Update Type', 'tecnoinfor'),
        'add_new_item'      => __('Add New Type', 'tecnoinfor'),
        'new_item_name'     => __('New Type Name', 'tecnoinfor'),
        'menu_name'         => __('Type of Clients', 'tecnoinfor'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'tipo-cliente'),
    );

    register_taxonomy('tipo-cliente', array('clientes'), $args);
}
add_action('init', 'registrar_taxonomia_tipo_cliente');

/**
 * Função para obter a query de clientes com argumentos personalizados.
 */
function tecnoinfor_get_clientes_query($args = array()) {
    $defaults = array(
        'post_type' => 'clientes',
        'posts_per_page' => 12,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    $args = wp_parse_args($args, $defaults);
    return new WP_Query($args);
}

// ----------------------------------------------------------------------------
// Meta Box: URL do Site do Cliente
// ----------------------------------------------------------------------------
function tecnoinfor_add_cliente_metaboxes() {
    add_meta_box(
        'tecnoinfor_cliente_url',
        __('URL do Site', 'tecnoinfor'),
        'tecnoinfor_cliente_url_callback',
        'clientes',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'tecnoinfor_add_cliente_metaboxes');

function tecnoinfor_cliente_url_callback($post) {
    wp_nonce_field('tecnoinfor_save_cliente_url', 'tecnoinfor_cliente_url_nonce');
    $url = get_post_meta($post->ID, '_cliente_url', true);
    echo '<label for="cliente_url">' . __('Insira o link oficial da empresa:', 'tecnoinfor') . '</label>';
    echo '<input type="url" id="cliente_url" name="cliente_url" value="' . esc_attr($url) . '" style="width:100%; margin-top:5px;" placeholder="https://www...">';
}

function tecnoinfor_save_cliente_url($post_id) {
    if (!isset($_POST['tecnoinfor_cliente_url_nonce']) || !wp_verify_nonce($_POST['tecnoinfor_cliente_url_nonce'], 'tecnoinfor_save_cliente_url')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['cliente_url'])) {
        update_post_meta($post_id, '_cliente_url', sanitize_url($_POST['cliente_url']));
    }
}
add_action('save_post_clientes', 'tecnoinfor_save_cliente_url');

// ----------------------------------------------------------------------------
// Colunas Personalizadas no WP-Admin
// ----------------------------------------------------------------------------
function tecnoinfor_clientes_columns($columns) {
    $new_columns = array();
    foreach($columns as $key => $title) {
        if ($key === 'title') {
            $new_columns['cb'] = $columns['cb'];
            $new_columns['cliente_logo'] = __('Logo', 'tecnoinfor');
            $new_columns['title'] = $title;
        } else if ($key === 'cb') {
            continue;
        } else {
            $new_columns[$key] = $title;
        }
    }
    return $new_columns;
}
add_filter('manage_clientes_posts_columns', 'tecnoinfor_clientes_columns');

function tecnoinfor_clientes_custom_column($column, $post_id) {
    if ($column === 'cliente_logo') {
        if (has_post_thumbnail($post_id)) {
            echo get_the_post_thumbnail($post_id, array(60, 60), array('style' => 'object-fit: contain; background: #f8f9fa; padding: 5px; border-radius: 4px;'));
        } else {
            echo '—';
        }
    }
}
add_action('manage_clientes_posts_custom_column', 'tecnoinfor_clientes_custom_column', 10, 2);