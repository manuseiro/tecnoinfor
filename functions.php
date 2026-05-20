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

