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
$update_checker_file = TECNOINFOR_INC . 'theme-update-checker.php';
if (file_exists($update_checker_file)) {
    require_once $update_checker_file;
}
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
require_once TECNOINFOR_INC . 'api-lgpd.php';          // Endpoints REST API para consentimento LGPD
require_once TECNOINFOR_INC . 'theme-cache.php';       // Transients e cache

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

    // Section for Benefits
    $wp_customize->add_section('tecnoinfor_benefits', [
      'title'    => __('Benefits Settings', 'tecnoinfor'),
      'priority' => 35,
    ]);

    $benefit_defaults = [
        1 => [
            'title' => __('Efficiency', 'tecnoinfor'),
            'desc'  => __('Organize and automate your main routines, reducing administrative time.', 'tecnoinfor'),
        ],
        2 => [
            'title' => __('Trust', 'tecnoinfor'),
            'desc'  => __('Powered by a robust open-source SQL database ensuring data integrity.', 'tecnoinfor'),
        ],
        3 => [
            'title' => __('Control', 'tecnoinfor'),
            'desc'  => __('Get detailed real-time reports on contracts and key business metrics.', 'tecnoinfor'),
        ],
    ];

    for ($i = 1; $i <= 3; $i++) {
        $wp_customize->add_setting("benefit_title_$i", [
            'default' => $benefit_defaults[$i]['title'],
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control("benefit_title_$i", [
            'label' => sprintf(__('Título do Benefício %d', 'tecnoinfor'), $i),
            'section' => 'tecnoinfor_benefits',
            'type' => 'text',
        ]);

        $wp_customize->add_setting("benefit_desc_$i", [
            'default' => $benefit_defaults[$i]['desc'],
            'sanitize_callback' => 'sanitize_textarea_field',
        ]);
        $wp_customize->add_control("benefit_desc_$i", [
            'label' => sprintf(__('Descrição do Benefício %d', 'tecnoinfor'), $i),
            'section' => 'tecnoinfor_benefits',
            'type' => 'textarea',
        ]);
    }
}
add_action('customize_register', 'tecnoinfor_customize_register');