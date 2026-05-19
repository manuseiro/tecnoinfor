<?php
/**
 * Funções auxiliares (Helpers) para o tema Tecnoinfor.
 *
 * @package Tecnoinfor
 */

// Função para exibir o breadcrumb das páginas, categorias e posts do tema
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

// Função para retornar copyright anos, nome, descrição do site e direitos reservados
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
