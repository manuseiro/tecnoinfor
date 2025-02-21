<?php
/**
 * Template para exibir o arquivo de "Clientes".
 *
 * @package Tecnoinfor
 * Template Name: Clientes
 */

get_header();
?>
<main class="main-content">
    <?php 
    // Inclui o conteúdo do header puxado do arquivo template-parts/content-header.php
    get_template_part('template-parts/content', 'header'); 
    ?>

    <!-- Conteúdo da página -->
    <div class="container my-5">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            <?php
            // Consulta manual para exibir os posts do tipo 'clientes'
            $args = array(
                'post_type' => 'clientes', // Define o tipo de post customizado
                'posts_per_page' => -1, // Exibe todos os posts
                'orderby' => 'date',
                'order' => 'DESC',
            );
            $clientes_query = new WP_Query($args);

            if ($clientes_query->have_posts()) :
                while ($clientes_query->have_posts()) : $clientes_query->the_post();
            ?>
            <div class="col">
                <div class="card h-100 border-0 shadow-sm">
                    <!-- Imagem do cliente -->
                    <div class="card-img-top position-relative">
                        <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>" class="d-block">
                            <?php the_post_thumbnail('full', [
                'class' => 'img-fluid rounded-top cliente-thumbnail w-100',
                'style' => 'object-fit: cover; height: auto;'
            ]); ?>
                        </a>
                        <?php else : ?>
                        <a href="<?php the_permalink(); ?>" class="d-block">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/cliente-default.jpg'); ?>"
                                class="img-fluid rounded-top w-100" style="object-fit: cover; height: auto;"
                                alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>">
                        </a>
                        <?php endif; ?>
                    </div>
                    <!-- Conteúdo do cartão -->
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold"><?php the_title(); ?></h5>
                        <p class="card-text text-muted small"><?php the_excerpt(); ?></p>

                    </div>
                </div>
            </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); // Reseta a consulta ?>
            <?php else : ?>
            <div class="col-12 text-center">
                <p class="text-muted">Nenhum cliente encontrado.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>