<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class('main-home'); ?> data-bs-spy="scroll" data-bs-target="#goTop" data-bs-offset="0">

    <header class="navbar-main">
        <div class="navbar-top">
            <!-- Topbar Start -->
            <div class="container-fluid bg-light p-0">
                <div class="row gx-0 d-none d-lg-flex">
                    <div class="col-lg-7 px-5 text-start fw-bold">
                        <div class="h-100 d-inline-flex align-items-center py-3 me-4 ">
                            <small class="bi bi-geo-fill text-primary me-2"></small>
                            <small><?php echo esc_html(get_informacao_empresa('endereco')); ?></small>
                        </div>
                        <div class="h-100 d-inline-flex align-items-center py-3">
                            <small class="bi bi-clock text-primary me-2"></small>

                            <small><?php echo esc_html(get_informacao_empresa('horarios')); ?></small>
                        </div>
                    </div>
                    <div class="col-lg-5 px-5 text-end">
                        <div class="h-100 d-inline-flex align-items-center py-3 me-4 ">
                            <small class="bi bi-telephone-fill text-primary me-2"></small>
                            <small class="fw-bold"><?php echo esc_html(get_informacao_empresa('telefone')); ?></small>
                        </div>

                        <div class="h-100 d-inline-flex align-items-center">
                            <?php
            $redes_sociais = get_redes_sociais();
            foreach ($redes_sociais as $rede => $dados) {
                $info = get_rede_social($rede);
                if (!empty($info['link'])) {
                    echo "<a href='{$info['link']}' class='btn btn-sm-square bg-white text-primary me-1' target='_blank' rel='noopener noreferrer' aria-label='{$info['nome']}'><i class='{$info['icone']}'></i>
                          </a>";
                }
            }
            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Topbar End -->
        <div class="navbar-principal shadow">
            <!-- Navbar Start -->
            <div class="container-xxl bd-gutter flex-wrap flex-lg-nowrap">
                <nav class="navbar navbar-expand-lg bg-white p-0" data-bs-theme="light">
                    <a class="navbar-brand d-flex align-items-center px-4 px-lg-5"
                        href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <?php 
              if (function_exists('the_custom_logo') && has_custom_logo()) {
                  $custom_logo = get_custom_logo();
                  $custom_logo = preg_replace('/<a[^>]*>(.*)<\/a>/', '$1', $custom_logo); // Remove o link em volta do logotipo
                  echo $custom_logo; 
              } else {
                  echo '<h2 class="m-0 text-primary">' . esc_html(get_bloginfo('name')) . '</h2>';
              }
              ?>
                    </a>
                    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse"
                        data-bs-target="#navbarCollapse"
                        aria-label="<?php esc_attr_e('Toggle navigation', 'tecnoinfor'); ?>">
                        <span class="navbar-toggler-icon"></span>
                    </button>
 
                    <div class="collapse navbar-collapse" id="navbarCollapse">
                        <?php
              wp_nav_menu(array(
                  'theme_location' => 'primary', // Localização do menu, definida no functions.php
                  'container'      => false, // Remove a div container padrão
                  'menu_class'     => 'navbar-nav ms-auto p-4 p-lg-0', // Aplica as classes de estilo
                  'fallback_cb'    => false, // Desabilita o fallback padrão
                  'depth'           => 2, // Nível de profundidade dos submenus
                  'walker'         => new navbar_walker_custom(), // Caso precise de uma customização adicional com walker
              ));
              ?>
                        <div class="d-flex align-items-center ms-auto gap-2 py-3 py-lg-0">
                            <?php
                            $whatsapp = get_informacao_empresa('whatsapp');
                            $whatsapp_clean = preg_replace('/[^0-9]/', '', $whatsapp);
                            if ($whatsapp_clean) :
                            ?>
                            <a href="https://wa.me/<?php echo esc_attr($whatsapp_clean); ?>" class="btn btn-outline-success rounded-pill py-2 px-3 d-none d-lg-inline-flex align-items-center" target="_blank" rel="noopener noreferrer">
                                <i class="bi bi-whatsapp me-2"></i> WhatsApp
                            </a>
                            <?php endif; ?>
                            <a href="<?php echo esc_url(home_url('/contato')); ?>" class="btn btn-primary py-2 px-4 d-none d-lg-block rounded-pill">Fale Conosco<i class="bi bi-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </nav>
 
            </div>
            <!-- Navbar End -->
        </div>
    </header>
    <?php wp_body_open(); ?>