<?php
/**
 * Tecnoinfor functions and definitions.
 *
 * @package Tecnoinfor
 */

// Definir a constante para o caminho base das funções
define('TECNOINFOR_INC', get_template_directory() . '/inc/');
define('TECNOINFOR_FUNCTIONS', TECNOINFOR_INC . 'functions/');

// Incluir arquivos do núcleo e de utilitários
require_once TECNOINFOR_INC . 'theme-update-checker.php';
require_once TECNOINFOR_INC . 'navbar_walker_custom.php';
require_once TECNOINFOR_INC . 'theme-custom-business.php';
require_once TECNOINFOR_INC . 'theme-custom-maintenance.php';

// Custom Post Types organizados
require_once TECNOINFOR_FUNCTIONS . 'cpt_planos.php';
require_once TECNOINFOR_FUNCTIONS . 'cpt_clientes.php';
require_once TECNOINFOR_FUNCTIONS . 'cpt_depoimentos.php';
require_once TECNOINFOR_FUNCTIONS . 'cpt_faqs.php';
require_once TECNOINFOR_FUNCTIONS . 'cpt_downloads.php';
require_once TECNOINFOR_FUNCTIONS . 'cpt_changelogs.php';
require_once TECNOINFOR_FUNCTIONS . 'cpt_software.php';
require_once TECNOINFOR_FUNCTIONS . 'cpt_about.php';

// Novos módulos do tema desmembrados para fácil manutenção
require_once TECNOINFOR_INC . 'theme-setup.php';       // Configuração, assets e suporte do tema
require_once TECNOINFOR_INC . 'theme-helpers.php';     // Breadcrumbs e helpers utilitários
require_once TECNOINFOR_INC . 'theme-seo.php';         // Meta tags dinâmicas e schemas JSON-LD
require_once TECNOINFOR_INC . 'theme-translation.php'; // Painel interno de tradução de strings

// Status Sobre a Empresa (Customizer)
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