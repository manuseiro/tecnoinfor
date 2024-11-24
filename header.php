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
      <!-- Topbar Start -->
      <div class="container-fluid bg-light p-0">
          <div class="row gx-0 d-none d-lg-flex">
              <div class="col-lg-7 px-5 text-start fw-bold">
                  <div class="h-100 d-inline-flex align-items-center py-3 me-4 ">
                      <small class="bi bi-geo-fill text-primary me-2"></small>
                      <small>Eusébio Ceará, Brasil</small>
                  </div>
                  <div class="h-100 d-inline-flex align-items-center py-3">
                      <small class="bi bi-clock text-primary me-2"></small>
                      <small>Seg - Sex: 09:00 AM - 17:00 PM</small>
                  </div>
              </div>
              <div class="col-lg-5 px-5 text-end">
                  <div class="h-100 d-inline-flex align-items-center py-3 me-4 ">
                      <small class="bi bi-telephone-fill text-primary me-2"></small>
                      <small class="fw-bold">+55 85 99970.1025</small>
                  </div>
                  <div class="h-100 d-inline-flex align-items-center">
                        <a class="btn btn-sm-square bg-white text-primary me-0" href="https://www.instagram.com/tecnoinfor9/"><i class="bi bi-instagram"></i></a>
                      <a class="btn btn-sm-square bg-white text-primary me-1" href="https://twitter.com/tecnoinfor_/"><i class="bi bi-twitter-x"></i></a>
                      <a class="btn btn-sm-square bg-white text-primary me-1" href="https://www.facebook.com/tecnoinfor.com.br/"><i class="bi bi-facebook"></i></a>
                      <a class="btn btn-sm-square bg-white text-primary me-1" href="https://www.youtube.com/@tecnoinforsti/"><i class="bi bi-youtube"></i></a>
                      <a class="btn btn-sm-square bg-white text-primary me-1" href="https://www.linkedin.com/company/tecnoinfor"><i class="bi bi-linkedin"></i></a>
                  </div>
              </div>
          </div>
      </div>
      <!-- Topbar End -->

      <!-- Navbar Start -->
      <nav class="navbar navbar-expand-lg bg-white navbar-light p-0 shadow">
          <a class="navbar-brand d-flex align-items-center px-4 px-lg-5" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
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
          <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-label="<?php esc_attr_e('Toggle navigation', 'tecnoinfor'); ?>">
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
              <a href="#" class="btn btn-primary py-4 px-lg-4 d-none d-lg-block rounded-0">Fale Conosco<i class="bi bi-arrow-right ms-3"></i></a>
          </div>
      </nav>
      <!-- Navbar End -->
  </header>
  <?php wp_body_open(); ?>
        
