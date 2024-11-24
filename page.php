<?php get_header(); ?>
<main class="main-content">
<?php 
// Inclui o conteúdo do header puxado do arquivo template-parts/content-header.php
get_template_part('template-parts/content', 'header'); 
?>

  <!-- Conteúdo da página -->
  <div class="container my-5 p-5">
    <div class="row">
      <div class="col-lg-12">
        <div class="editor-wp">
          <?php
            while ( have_posts() ) : the_post();
              the_content();
            endwhile;
          ?>
        </div>
      </div>
    </div>
  </div>
</main>
<?php get_footer(); ?>
