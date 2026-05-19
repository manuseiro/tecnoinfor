<?php
/**
 * Template para exibir páginas de categorias.
 *
 * @package Tecnoinfor
 */
get_header();
?>

<main class="main-content">
    <?php get_template_part('template-parts/content', 'header'); ?>

    <div class="container my-5 px-4 px-lg-5">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
            ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm rounded-3 border-0">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-thumbnail overflow-hidden">
                                    <?php the_post_thumbnail('post-thumbnail', ['class' => 'card-img-top rounded-top-3 img-fluid', 'loading' => 'lazy']); ?>
                                </div>
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column p-4">
                                <h5 class="card-title h4 fw-bold mb-3">Teste Categoria <?php the_title(); ?></h5>
                                <div class="card-text text-muted mb-4 flex-grow-1">
                                    <?php the_excerpt(); ?>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="btn btn-primary">Leia mais</a>
                            </div>
                        </div>
                    </div>
            <?php
                endwhile;

                // Paginação
                the_posts_pagination(array(
                    'prev_text' => __('« Anterior', 'tecnoinfor'),
                    'next_text' => __('Próximo »', 'tecnoinfor'),
                ));

            else :
                echo '<p>Nenhum post encontrado nesta categoria.</p>';
            endif;
            ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>