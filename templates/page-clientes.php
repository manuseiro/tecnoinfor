<?php
/**
 * Template para exibir a página Clientes.
 *
 * @package Tecnoinfor
 * Template Name: Clientes
 */

get_header();
?>

<main class="main-content">
    <?php 
        // Inclui o conteúdo do header
        get_template_part('template-parts/content', 'header'); 
    ?>
    
    <!-- Conteúdo da página -->
    <div class="container my-5 p-5">
    <div class="row content-separator justify-content-center">
        <?php
        $args = array(
            'post_type' => 'clientes',
            'posts_per_page' => -1,
            'orderby' => 'id',
            'order' => 'ASC',
        );

        $clientes_query = new WP_Query($args);

        if ($clientes_query->have_posts()) :
            while ($clientes_query->have_posts()) : $clientes_query->the_post(); ?>
                <div class="col-sm-6 col-md-4 mb-4">
                    <div class="card h-100 shadow-sm cliente-card">
                        <div class="card-img-top text-center position-relative">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>" class="d-block overflow-hidden">
                                    <?php the_post_thumbnail('cliente-post', ['class' => 'img-fluid cliente-thumbnail']); ?>
                                </a>
                            <?php else : ?>
                                <a href="<?php the_permalink(); ?>" class="d-block overflow-hidden">
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/cliente-default.jpg'); ?>" 
                                         class="img-fluid cliente-thumbnail" 
                                         alt="<?php the_title_attribute(); ?>" 
                                         title="<?php the_title_attribute(); ?>">
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title cliente-title"><?php the_title(); ?></h5>
                            <p class="card-text cliente-excerpt"><?php the_excerpt(); ?></p>
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm mt-2">Ver mais</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="col-12 text-center">
                <p>Nenhum cliente encontrado.</p>
            </div>
        <?php endif;

        wp_reset_postdata();
        ?>
    </div>
</div>

</main>

<?php get_footer(); ?>
