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
        // Inclui o conteúdo do header puxado do arquivo template-parts/content-header.php
        get_template_part('template-parts/content', 'header'); 
    ?>
    
    <!-- Conteúdo da página -->
    <div class="container my-5 p-5">
        <!-- Loop dos clientes -->
        <div class="row content-separator">
            <?php
            // Argumentos para o loop customizado (post_type: clientes)
            $args = array(
                'post_type' => 'clientes',
                'posts_per_page' => -1,
                'orderby' => 'id',
                'order' => 'ASC',
            );

            // Executa a consulta personalizada
            $clientes_query = new WP_Query($args);

            if ($clientes_query->have_posts()) :
                while ($clientes_query->have_posts()) : $clientes_query->the_post(); ?>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card h-100 shadow-sm">
                            <!-- Exibe a imagem destacada ou uma imagem padrão -->
                            <div class="card-img-top text-center">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('cliente-post', ['class' => 'img-fluid']); ?>
                                <?php else : ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/cliente-default.jpg" class="img-fluid" alt="<?php the_title(); ?>" title="<?php the_title(); ?>">
                                <?php endif; ?>
                            </div>
                            <!-- Título e resumo do post -->
                            <div class="card-body text-center">
                                <h4 class="card-title "><?php the_title(); ?></h4>
                                <p class="card-text"><?php the_excerpt(); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="col-12 text-center">
                    <p>Nenhum cliente encontrado.</p>
                </div>
            <?php endif;

            // Reseta a query global
            wp_reset_postdata();
            ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>
