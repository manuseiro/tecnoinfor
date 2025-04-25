<?php
// Função para registrar o Custom Post Type "Downloads"
function registrar_cpt_downloads() {
    $labels = array(
        'name'               => __('Downloads', 'tecnoinfor'),
        'singular_name'      => __('Download', 'tecnoinfor'),
        'menu_name'          => __('Downloads', 'tecnoinfor'),
        'name_admin_bar'     => __('Download', 'tecnoinfor'),
        'add_new'            => __('Add New', 'tecnoinfor'),
        'add_new_item'       => __('Add New Download', 'tecnoinfor'),
        'new_item'           => __('New Download', 'tecnoinfor'),
        'edit_item'          => __('Edit Download', 'tecnoinfor'),
        'view_item'          => __('View Download', 'tecnoinfor'),
        'all_items'          => __('All Downloads', 'tecnoinfor'),
        'search_items'       => __('Search Downloads', 'tecnoinfor'),
        'not_found'          => __('No downloads found.', 'tecnoinfor'),
        'not_found_in_trash' => __('No downloads found in trash.', 'tecnoinfor'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-download',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'author', 'revisions'),
        'has_archive'        => false,
        'hierarchical'       => false,
        'rewrite'            => array('slug' => 'downloads', 'with_front' => false),
        'capability_type'    => 'post',
        'show_in_rest'       => true,
        'query_var'          => true,
        'taxonomies'         => array('download_category'), // Adiciona suporte à taxonomia personalizada
    );

    register_post_type('downloads', $args);
}
add_action('init', 'registrar_cpt_downloads');

// Taxonomia Personalizada para Downloads
function registrar_taxonomia_downloads() {
    register_taxonomy(
        'download_category',
        'downloads',
        array(
            'label'        => __('Categories', 'tecnoinfor'),
            'rewrite'      => array('slug' => 'download-category'),
            'hierarchical' => true,
            'show_in_rest' => true, // Suporte ao Gutenberg/REST API
        )
    );
}
add_action('init', 'registrar_taxonomia_downloads');

// Adiciona o meta box para o link de download
function adicionar_meta_box_download() {
    add_meta_box(
        'link_download_meta_box',
        __('Download Link', 'tecnoinfor'),
        'exibir_meta_box_download',
        'downloads',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'adicionar_meta_box_download');

// Exibe o campo do link de download na meta box
function exibir_meta_box_download($post) {
    wp_nonce_field('salvar_link_download', 'link_download_nonce');
    $link_download = get_post_meta($post->ID, 'link_download', true);
    ?>
    <label for="link_download"><?php _e('Program URL (Download Link):', 'tecnoinfor'); ?></label>
    <input type="url" id="link_download" name="link_download" value="<?php echo esc_attr($link_download); ?>" 
           style="width:100%;" placeholder="https://exemplo.com/arquivo.zip"/>
    <?php
}

// Salva o valor do campo de link de download
function salvar_meta_box_download($post_id) {
    if (!isset($_POST['link_download_nonce']) || !wp_verify_nonce($_POST['link_download_nonce'], 'salvar_link_download')) {
        return;
    }
    if (isset($_POST['link_download'])) {
        update_post_meta($post_id, 'link_download', esc_url_raw($_POST['link_download']));
    }
}
add_action('save_post', 'salvar_meta_box_download');

// Adiciona meta box para informações adicionais (versão, tamanho, sistema operacional e arquitetura)
function adicionar_meta_box_info() {
    add_meta_box(
        'download_info',
        __('Download Information', 'tecnoinfor'),
        'exibir_meta_box_info',
        'downloads',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'adicionar_meta_box_info');

// Exibe os campos na meta box
function exibir_meta_box_info($post) {
    wp_nonce_field('salvar_download_info', 'download_info_nonce');
    $versao = get_post_meta($post->ID, 'download_versao', true);
    $tamanho = get_post_meta($post->ID, 'download_tamanho', true);
    $sistema_operacional = get_post_meta($post->ID, 'download_sistema_operacional', true);
    $arquitetura = get_post_meta($post->ID, 'download_arquitetura', true);
    ?>
    <p>
        <label for="download_sistema_operacional"><?php _e('Operating System:', 'tecnoinfor'); ?></label><br>
        <input type="text" id="download_sistema_operacional" name="download_sistema_operacional" value="<?php echo esc_attr($sistema_operacional); ?>" 
               style="width:100%;" placeholder="Ex.: Windows, macOS, Linux"/>
    </p>
    <p>
        <label for="download_arquitetura"><?php _e('Architecture:', 'tecnoinfor'); ?></label><br>
        <input type="text" id="download_arquitetura" name="download_arquitetura" value="<?php echo esc_attr($arquitetura); ?>" 
               style="width:100%;" placeholder="Ex.: 32-bit, 64-bit"/>
    </p>
    <p>
        <label for="download_versao"><?php _e('Version:', 'tecnoinfor'); ?></label><br>
        <input type="text" id="download_versao" name="download_versao" value="<?php echo esc_attr($versao); ?>" 
               style="width:100%;" placeholder="Ex.: 1.2.3"/>
    </p>
    <p>
        <label for="download_tamanho"><?php _e('Size:', 'tecnoinfor'); ?></label><br>
        <input type="text" id="download_tamanho" name="download_tamanho" value="<?php echo esc_attr($tamanho); ?>" 
               style="width:100%;" placeholder="Ex.: 5 MB"/>
    </p>
    <?php
}

// Salva os valores dos campos
function salvar_meta_box_info($post_id) {
    if (!isset($_POST['download_info_nonce']) || !wp_verify_nonce($_POST['download_info_nonce'], 'salvar_download_info')) {
        return;
    }
    if (isset($_POST['download_sistema_operacional'])) {
        update_post_meta($post_id, 'download_sistema_operacional', sanitize_text_field($_POST['download_sistema_operacional']));
    }
    if (isset($_POST['download_arquitetura'])) {
        update_post_meta($post_id, 'download_arquitetura', sanitize_text_field($_POST['download_arquitetura']));
    }
    if (isset($_POST['download_versao'])) {
        update_post_meta($post_id, 'download_versao', sanitize_text_field($_POST['download_versao']));
    }
    if (isset($_POST['download_tamanho'])) {
        update_post_meta($post_id, 'download_tamanho', sanitize_text_field($_POST['download_tamanho']));
    }
}
add_action('save_post', 'salvar_meta_box_info');

/**
 * Função para obter a query de downloads com argumentos personalizados.
 *
 * @param array $args Argumentos para a WP_Query.
 * @return WP_Query Resultado da consulta.
 */
function tecnoinfor_get_downloads_query($args = array()) {
    $defaults = array(
        'post_type' => 'downloads',
        'posts_per_page' => 9,
        'orderby' => 'id',
        'order' => 'ASC',
    );
    $args = wp_parse_args($args, $defaults);
    return new WP_Query($args);
}
