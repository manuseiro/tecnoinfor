<?php
/**
 * Template para exibir a página Termo e Politicas.
 *
 * @package Tecnoinfor
 * Template Name: Termo e Politicas
 */

get_header(); ?>
<main class="main-content">
  <?php 
  // Inclui o conteúdo do header puxado do arquivo template-parts/content-header.php
  get_template_part('template-parts/content', 'header'); 
  ?>
  
  <!-- Conteúdo da página -->
  <div class="container my-5 p-5">
    <div class="row">
      <div class="col-lg-3">
        <!-- Menu de navegação entre páginas -->
        <nav class="sticky-top">
          <ul class="nav flex-column">
            <li class="nav-item ">
              <a href="<?php echo get_permalink(get_page_by_path('politica-de-privacidade')); ?>" class="nav-link custom-link py-3 <?php if ( is_page('politica-de-privacidade') ) echo 'active'; ?>">
              <?php _e('Privacy Policy', 'tecnoinfor'); ?>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo get_permalink(get_page_by_path('politica-de-cookies')); ?>" class="nav-link  custom-link py-3 <?php if ( is_page('politica-de-cookies') ) echo 'active'; ?>">
              <?php _e('Privacy Policy', 'tecnoinfor'); ?>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo get_permalink(get_page_by_path('termos-de-uso')); ?>" class="nav-link custom-link py-3 <?php if ( is_page('termos-de-uso') ) echo 'active'; ?>">
              <?php _e('Terms of Use', 'tecnoinfor'); ?>
              </a>
            </li>
          </ul>
        </nav>
      </div>
      
      <div class="col-lg-9">
        <?php
          while ( have_posts() ) : the_post();
            the_content();
          endwhile;
        ?>
      </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>
