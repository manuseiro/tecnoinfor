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
    <div class="container my-5 px-4 px-lg-5">
        <!-- Filtro de Categorias -->
        <div class="row justify-content-end mb-4">
            <div class="col-md-6 col-lg-4">
                <?php
                // Substitua 'tipo-cliente' pelo nome real da sua taxonomia
                $categories = get_terms(array('taxonomy' => 'tipo-cliente', 'hide_empty' => true));
                if (!empty($categories) && !is_wp_error($categories)) :
                    echo '<select class="form-select " onchange="window.location.href=this.value">';
                    echo '<option value="' . esc_url(get_permalink()) . '">Todas as categorias</option>';
                    foreach ($categories as $category) :
                        $selected = isset($_GET['category']) && $_GET['category'] === $category->slug ? 'selected' : '';
                        echo '<option value="' . esc_url(add_query_arg('category', $category->slug)) . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
                    endforeach;
                    echo '</select>';
                endif;
                ?>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php
            // Configuração da consulta com paginação
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            $args = array(
                'post_type' => 'clientes',
                'posts_per_page' => 12,
                'paged' => $paged,
                'orderby' => apply_filters('tecnoinfor_clientes_orderby', 'date'),
                'order' => apply_filters('tecnoinfor_clientes_order', 'DESC'),
            );

            // Adicionar filtro por taxonomia, se aplicável
            if (isset($_GET['category'])) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'tipo-cliente', // Substitua pelo nome real da sua taxonomia
                        'field' => 'slug',
                        'terms' => sanitize_text_field($_GET['category']),
                    ),
                );
            }

            $clientes_query = tecnoinfor_get_clientes_query($args);

            if ($clientes_query->have_posts()) :
                while ($clientes_query->have_posts()) : $clientes_query->the_post();
            ?>
            <div class="col">
                <div class="card h-100 shadow-sm rounded-3 border-0">
                    <!-- Imagem do cliente -->
                    <div class="card-img-top position-relative overflow-hidden">
                        <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>" class="d-block">
                            <?php 
                            the_post_thumbnail('full', [
                                'class' => 'img-fluid rounded-top-3 w-100',
                                'style' => 'object-fit: cover; height: 200px;',
                                'alt' => esc_attr(sprintf(__('Client image %s'), get_the_title())),
                            ]); 
                            ?>
                        </a>
                        <?php else : ?>
                        <a href="<?php the_permalink(); ?>" class="d-block">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/cliente-default.jpg'); ?>"
                                class="img-fluid rounded-top-3 w-100" style="object-fit: cover; height: 200px;"
                                alt="<?php echo esc_attr(sprintf(__('Default client image %s'), get_the_title())); ?>"
                                title="<?php the_title_attribute(); ?>">
                        </a>
                        <?php endif; ?>
                    </div>
                    <!-- Conteúdo do cartão -->
                    <div class="card-body text-center d-flex flex-column p-4">
                        <h5 class="card-title fw-bold mb-3"><?php the_title(); ?></h5>
                        <p class="card-text text-muted small flex-grow-1"><?php the_excerpt(); ?></p>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Paginação -->
        <div class="row justify-content-center mt-5">
            <nav aria-label="Paginação de clientes">
                <?php
                echo paginate_links(array(
                    'total' => $clientes_query->max_num_pages,
                    'current' => $paged,
                    'prev_text' => __('« Anterior'),
                    'next_text' => __('Próximo »'),
                    'type' => 'list',
                    'add_args' => isset($_GET['category']) ? array('category' => sanitize_text_field($_GET['category'])) : false,
                ));
                ?>
            </nav>
        </div>
        <?php
            wp_reset_postdata();
        else :
        ?>
        <div class="col text-center">
            <p class="text-muted fs-4">Nenhum cliente encontrado.</p>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>