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
        'menu_icon'          => 'dashicons-businessman',  // Ícone do menu
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'has_archive'        => false,
        'hierarchical'       => false, // Estrutura não hierárquica como posts
        'rewrite'            => array('slug' => 'clientes', 'with_front' => false), // Slug da URL
        'capability_type' => 'post', // Alterado para 'post' para permissões mais amplas
        'show_in_rest'       => true,  // Habilita o Gutenberg e API REST
        'query_var'          => true,  // Permite consultas usando query_var
    );

    register_post_type('clientes', $args);
}
// Hook para registrar o Custom Post Type
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
        'hierarchical'      => true, // Como categorias, use true para hierarquia
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true, // Habilita o Gutenberg e API REST
        'query_var'         => true,
        'rewrite'           => array('slug' => 'tipo-cliente'),
    );

    register_taxonomy('tipo-cliente', array('clientes'), $args);
}

// Hook para registrar a Taxonomia
add_action('init', 'registrar_taxonomia_tipo_cliente');

/**
 * Função para obter a query de clientes com argumentos personalizados.
 *
 * @param array $args Argumentos para a WP_Query.
 * @return WP_Query Resultado da consulta.
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