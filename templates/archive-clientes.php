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
        <!-- Filtro de Categorias e Busca -->
        <div class="row mb-4 bg-white p-3 rounded shadow-sm align-items-center">
            <div class="col-12 col-md-12">
                <form action="<?php echo esc_url(get_post_type_archive_link('clientes') ?: get_permalink()); ?>" method="GET" class="row g-3">
                    <div class="col-md-6 col-lg-8">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="s" class="form-control border-start-0 ps-0" placeholder="<?php esc_attr_e('Buscar clientes por nome...', 'tecnoinfor'); ?>" value="<?php echo isset($_GET['s']) ? esc_attr(sanitize_text_field($_GET['s'])) : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <?php
                        $categories = get_terms(array('taxonomy' => 'tipo-cliente', 'hide_empty' => true));
                        if (!empty($categories) && !is_wp_error($categories)) :
                            echo '<select name="category" class="form-select">';
                            echo '<option value="">' . esc_html__('Todas as categorias', 'tecnoinfor') . '</option>';
                            foreach ($categories as $category) :
                                $selected = isset($_GET['category']) && $_GET['category'] === $category->slug ? 'selected' : '';
                                echo '<option value="' . esc_attr($category->slug) . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
                            endforeach;
                            echo '</select>';
                        endif;
                        ?>
                    </div>
                    <div class="col-md-2 col-lg-1">
                        <button type="submit" class="btn btn-primary w-100"><?php esc_html_e('Filtrar', 'tecnoinfor'); ?></button>
                    </div>
                </form>
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

            // Adicionar busca
            if (!empty($_GET['s'])) {
                $args['s'] = sanitize_text_field($_GET['s']);
            }

            // Adicionar filtro por taxonomia, se aplicável
            if (!empty($_GET['category'])) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'tipo-cliente',
                        'field' => 'slug',
                        'terms' => sanitize_text_field($_GET['category']),
                    ),
                );
            }

            $clientes_query = tecnoinfor_get_clientes_query($args);

            if ($clientes_query->have_posts()) :
                while ($clientes_query->have_posts()) : $clientes_query->the_post();
                    $cliente_url = get_post_meta(get_the_ID(), '_cliente_url', true);
                    $link_target = !empty($cliente_url) ? 'target="_blank" rel="noopener noreferrer"' : '';
                    $card_link = !empty($cliente_url) ? esc_url($cliente_url) : get_permalink();
                    
                    // Pega as taxonomias do cliente
                    $termos = get_the_terms(get_the_ID(), 'tipo-cliente');
            ?>
            <div class="col">
                <div class="card h-100 shadow-sm rounded-3 border-0 transition-hover">
                    <!-- Imagem do cliente -->
                    <div class="card-img-top position-relative overflow-hidden bg-light p-4 d-flex align-items-center justify-content-center" style="height: 200px;">
                        
                        <?php if ($termos && !is_wp_error($termos)) : ?>
                            <div class="position-absolute top-0 end-0 p-2">
                                <span class="badge bg-primary rounded-pill opacity-75 shadow-sm"><?php echo esc_html($termos[0]->name); ?></span>
                            </div>
                        <?php endif; ?>

                        <a href="<?php echo $card_link; ?>" <?php echo $link_target; ?> class="d-block w-100 h-100 text-center">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php 
                                the_post_thumbnail('medium', [
                                    'class' => 'img-fluid',
                                    'style' => 'object-fit: contain; max-height: 100%; width: auto;',
                                    'alt' => esc_attr(sprintf(__('Client image %s', 'tecnoinfor'), get_the_title())),
                                ]); 
                                ?>
                            <?php else : ?>
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/cliente-default.jpg'); ?>"
                                    class="img-fluid opacity-50" style="object-fit: contain; max-height: 100%; width: auto;"
                                    alt="<?php echo esc_attr(sprintf(__('Default client image %s', 'tecnoinfor'), get_the_title())); ?>">
                            <?php endif; ?>
                        </a>
                    </div>
                    <!-- Conteúdo do cartão -->
                    <div class="card-body text-center d-flex flex-column p-4">
                        <h5 class="card-title fw-bold mb-3">
                            <a href="<?php echo $card_link; ?>" <?php echo $link_target; ?> class="text-dark text-decoration-none text-hover-primary">
                                <?php the_title(); ?>
                            </a>
                        </h5>
                        <p class="card-text text-muted small flex-grow-1"><?php the_excerpt(); ?></p>
                        <?php if (!empty($cliente_url)) : ?>
                            <div class="mt-3">
                                <a href="<?php echo esc_url($cliente_url); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-secondary rounded-pill">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> <?php esc_html_e('Visitar Site', 'tecnoinfor'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Paginação -->
        <div class="row justify-content-center mt-5">
            <nav aria-label="Paginação de clientes">
                <?php
                $pagination_args = array(
                    'total' => $clientes_query->max_num_pages,
                    'current' => $paged,
                    'prev_text' => esc_html__('« Anterior', 'tecnoinfor'),
                    'next_text' => esc_html__('Próximo »', 'tecnoinfor'),
                    'type' => 'list',
                    'add_args' => array()
                );
                
                if (!empty($_GET['category'])) {
                    $pagination_args['add_args']['category'] = sanitize_text_field($_GET['category']);
                }
                if (!empty($_GET['s'])) {
                    $pagination_args['add_args']['s'] = sanitize_text_field($_GET['s']);
                }
                
                echo paginate_links($pagination_args);
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