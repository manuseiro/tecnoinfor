<?php
/**
 * Template para exibir a página Downloads.
 *
 * @package Tecnoinfor
 * Template Name: Archive - Downloads
 */
get_header();
?>

<main class="main-content">
    <?php 
    // Inclui o conteúdo do header puxado do arquivo template-parts/content-header.php
    get_template_part('template-parts/content', 'header'); 
    ?>

    <div class="container my-5 px-4 px-lg-5">
        <!-- Filtro de Categorias e Busca -->
        <form method="get" action="<?php echo esc_url(get_permalink()); ?>" class="row mb-4 align-items-center">
            <div class="col-md-6 col-lg-4 mb-3 mb-md-0">
                <div class="input-group">
                    <input type="text" name="s" class="form-control" placeholder="<?php esc_attr_e('Search downloads...', 'tecnoinfor'); ?>" value="<?php echo isset($_GET['s']) ? esc_attr(sanitize_text_field($_GET['s'])) : ''; ?>">
                    <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 ms-auto">
                <select class="form-select" name="category" onchange="this.form.submit()" aria-label="<?php esc_attr_e('Filter by category', 'tecnoinfor'); ?>">
                    <option value=""><?php esc_html_e('All Categories', 'tecnoinfor'); ?></option>
                    <?php
                    $categories = get_terms(array('taxonomy' => 'download_category', 'hide_empty' => true));
                    if (!empty($categories) && !is_wp_error($categories)) :
                        foreach ($categories as $category) :
                            $selected = isset($_GET['category']) && $_GET['category'] === $category->slug ? 'selected' : '';
                            echo '<option value="' . esc_attr($category->slug) . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>
        </form>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            $args = array(
                'post_type' => 'downloads',
                'posts_per_page' => 9,
                'paged' => $paged,
                'orderby' => apply_filters('tecnoinfor_downloads_orderby', 'id'),
                'order' => apply_filters('tecnoinfor_downloads_order', 'ASC'),
            );

            // Filtro por categoria
            if (isset($_GET['category'])) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'download_category',
                        'field' => 'slug',
                        'terms' => sanitize_text_field($_GET['category']),
                    ),
                );
            }

            // Filtro por busca
            if (isset($_GET['s'])) {
                $args['s'] = sanitize_text_field($_GET['s']);
            }

            $downloads_query = tecnoinfor_get_downloads_query($args);

            if ($downloads_query->have_posts()) :
                while ($downloads_query->have_posts()) : $downloads_query->the_post();
            ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm rounded-3 border-0 download-<?php echo esc_attr(sanitize_html_class(get_post_meta(get_the_ID(), 'download_sistema_operacional', true))); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="download-thumbnail overflow-hidden">
                                    <?php 
                                    the_post_thumbnail('download_thumb', [
                                        'class' => 'card-img-top rounded-top-3 img-fluid',
                                        'alt' => esc_attr(sprintf(__('Download image %s', 'tecnoinfor'), get_the_title())),
                                        'loading' => 'lazy'
                                    ]); 
                                    ?>
                                </div>
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3 text-muted small">
                                    <?php
                                    $terms = get_the_terms(get_the_ID(), 'download_category');
                                    if ($terms && !is_wp_error($terms)) : ?>
                                        <span><?php echo esc_html($terms[0]->name); ?></span>
                                    <?php endif; ?>
                                    <?php
                                    $versao = get_post_meta(get_the_ID(), 'download_versao', true);
                                    $sistema_operacional = get_post_meta(get_the_ID(), 'download_sistema_operacional', true);
                                    $arquitetura = get_post_meta(get_the_ID(), 'download_arquitetura', true);
                                    ?>
                                    <?php if ($arquitetura) : ?>
                                        <span><i class="bi bi-cpu me-1"></i><?php echo esc_html($arquitetura); ?></span>
                                    <?php endif; ?>
                                </div>
                                <h5 class="card-title h4 fw-bold mb-3"><?php echo esc_html(get_the_title()); ?></h5>
                                <div class="card-text text-muted mb-4 flex-grow-1">
                                    <?php echo wp_kses_post(wp_trim_words(get_the_excerpt(), 20, '...')); ?>
                                </div>
                                <?php
                                $link_download = get_post_meta(get_the_ID(), 'link_download', true);
                                $tamanho = get_post_meta(get_the_ID(), 'download_tamanho', true);
                                if ($link_download && filter_var($link_download, FILTER_VALIDATE_URL)) :
                                    $file_name = basename(parse_url($link_download, PHP_URL_PATH));
                                ?>
                                    <div class="d-flex gap-2 align-self-end">
                                        <a class="btn btn-success py-2 px-4 d-flex align-items-center"
                                           href="<?php echo esc_url($link_download); ?>" target="_blank" rel="noopener noreferrer"
                                           download="<?php echo esc_attr($file_name); ?>"
                                           aria-label="<?php printf(__('Download %s, size %s', 'tecnoinfor'), esc_attr(get_the_title()), esc_html($tamanho)); ?>">
                                            <span class="fw-bold"><?php _e('Download', 'tecnoinfor'); ?></span>
                                            <?php if ($tamanho) : ?>
                                                <small class="ms-2"><?php echo esc_html($tamanho); ?></small>
                                            <?php endif; ?>
                                            <i class="bi bi-download ms-2 fs-5"></i>
                                        </a>
                                        <a class="btn btn-primary p-3 d-flex" href="<?php the_permalink(); ?>" aria-label="<?php _e('View details', 'tecnoinfor'); ?>">
                                            <i class="bi bi-chat-square-text"></i>
                                        </a>
                                    </div>
                                <?php else : ?>
                                    <p class="text-center text-warning mb-0"><?php _e('Link unavailable.', 'tecnoinfor'); ?> 
                                        <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts')) . '?report=' . get_the_ID()); ?>" 
                                           class="text-decoration-underline"><?php _e('report', 'tecnoinfor'); ?></a>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
            <?php
                endwhile;
            ?>
                <!-- Paginação -->
                <div class="row justify-content-center mt-5">
                    <nav aria-label="<?php _e('Downloads pagination', 'tecnoinfor'); ?>">
                        <?php
                        echo paginate_links(array(
                            'total' => $downloads_query->max_num_pages,
                            'current' => $paged,
                            'prev_text' => __('<i class="bi bi-chevron-left"></i> Previous', 'tecnoinfor'),
                            'next_text' => __('Next <i class="bi bi-chevron-right"></i>', 'tecnoinfor'),
                            'type' => 'list',
                            'add_args' => array_filter(array(
                                'category' => isset($_GET['category']) ? sanitize_text_field($_GET['category']) : false,
                                's' => isset($_GET['s']) ? sanitize_text_field($_GET['s']) : false,
                            )),
                        ));
                        ?>
                    </nav>
                </div>
            <?php
                wp_reset_postdata();
            else :
            ?>
                <div class="col text-center">
                    <p class="text-muted fs-4"><?php _e('No downloads available at the moment. Check back later!', 'tecnoinfor'); ?></p>
                    <!-- Downloads em Destaque (Fallback) -->
                    <h4 class="fw-bold text-primary mt-4"><?php _e('Featured Downloads', 'tecnoinfor'); ?></h4>
                    <?php
                    $featured_query = tecnoinfor_get_downloads_query(array('posts_per_page' => 3, 'orderby' => 'rand'));
                    if ($featured_query->have_posts()) :
                        while ($featured_query->have_posts()) : $featured_query->the_post();
                    ?>
                            <div class="mb-3">
                                <h5 class="fs-6"><a href="<?php the_permalink(); ?>" class="text-dark"><?php the_title(); ?></a></h5>
                                <small class="text-muted"><?php echo esc_html(get_post_meta(get_the_ID(), 'download_versao', true) ?: __('N/A', 'tecnoinfor')); ?></small>
                            </div>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>