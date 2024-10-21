<?php get_header(); ?>
<main class="main-content">
  <?php 
// Verifica se a página tem uma imagem destacada
if (has_post_thumbnail()) {
    $banner_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
} else {
    // Adiciona uma imagem padrão se não houver imagem destacada
    $banner_image = get_template_directory_uri() . '/assets/images/default-banner.jpg';
}
?>

<section class="banner-breadcrumb bg-delp" style="
    background: linear-gradient(to bottom, rgb(11, 94, 215, 0.3) 0%, rgba(11, 94, 215, 0.4) 50%, rgba(11, 94, 215, 0.8) 100%), url('<?php echo esc_url($banner_image); ?>');
    background-size: cover; 
    background-position: center;">
    
    <div class="row p-5 pb-0 pe-lg-0 py-lg-5 align-items-center" style="position: relative; z-index: 2;">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb fw-bolder text-white text-shadow">
                <li class="breadcrumb-item"><a href="<?php echo home_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php the_title(); ?></li>
            </ol>
        </nav>

        <!-- Título e Descrição -->
        <div class="d-flex flex-column align-items-start text-left">
            <h1 class="display-4 text-white fw-bolder">
                <?php the_title(); ?>
            </h1>
            <div style="max-width:400px" class="text-white">
                <!-- Descrição opcional, pode ser a introdução ou campo personalizado -->
                <div class="page-summary">
                    <?php if (has_excerpt()) : ?>
                        <p><?php echo get_the_excerpt(); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

  <!-- Conteúdo da página -->
  <div class="container my-5 p-5">
    <div class="row">
      <div class="col-lg-12">
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
