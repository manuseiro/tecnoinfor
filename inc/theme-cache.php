<?php
/**
 * Funções de Cache e Transients
 *
 * @package Tecnoinfor
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Retorna os dados agregados para a página do software usando Transients
 * para evitar N+1 queries na renderização.
 *
 * @param int $software_id O ID do software
 * @return array Array associativo com changelog, planos, etc.
 */
function tecnoinfor_get_software_page_data(int $software_id): array {
    $cache_key = "software_page_data_{$software_id}";
    $data      = get_transient($cache_key);

    if (false !== $data) {
        return $data;
    }

    // Uma única query para changelogs
    $changelog = get_posts(array(
        'post_type'   => 'changelogs',
        'numberposts' => 1,
        'meta_key'    => '_related_software',
        'meta_value'  => $software_id,
        'orderby'     => 'date',
        'order'       => 'DESC',
    ));

    // Uma query para planos com todos os meta em um go
    $planos = get_posts(array(
        'post_type'   => 'planos',
        'numberposts' => 20, // limite explícito
        'orderby'     => 'menu_order',
        'order'       => 'ASC',
        'meta_query'  => array(
            array(
                'key' => '_plano_software_id',
                'value' => $software_id,
                'compare' => '='
            )
        )
    ));

    // Pre-load meta em batch (evita N+1)
    if ($planos) {
        $plano_ids = wp_list_pluck($planos, 'ID');
        update_postmeta_cache($plano_ids); // carrega todos os meta de uma vez
    }

    // FAQs relacionadas
    $faqs = get_posts(array(
        'post_type'   => 'faqs',
        'numberposts' => 20,
        'meta_key'    => '_related_software',
        'meta_value'  => $software_id,
    ));

    $data = compact('changelog', 'planos', 'faqs');
    set_transient($cache_key, $data, HOUR_IN_SECONDS);

    return $data;
}

// Invalidar cache ao salvar/atualizar post
add_action('save_post_planos', function ($id) {
    global $wpdb;
    $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_software_page_data_%'");
});
add_action('save_post_changelogs', function ($id) {
    global $wpdb;
    $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_software_page_data_%'");
});
add_action('save_post_software', function ($id) {
    delete_transient("software_page_data_{$id}");
});
add_action('save_post_faqs', function ($id) {
    global $wpdb;
    $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_software_page_data_%'");
});
