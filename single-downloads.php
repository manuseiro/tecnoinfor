<?php get_header(); ?>

<main class="main-content">
<?php 
// Inclui o conteúdo do header puxado do arquivo template-parts/content-header.php
get_template_part('template-parts/content', 'header'); 
?>

    <!-- Conteúdo da página -->
    <div class="container my-5 p-5">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="editor-wp">
                    <?php
                    while (have_posts()) : the_post();
                        the_content();
                    endwhile;
                    ?>
                </div>

                <div class="mt-4 text-center">
                    <a href="<?php echo esc_url(get_post_type_archive_link('downloads')); ?>"
                        class="btn btn-outline-secondary" aria-label="<?php _e('Back to Downloads List', 'tecnoinfor'); ?>">
                        <?php _e('Back to Downloads', 'tecnoinfor'); ?>
                    </a>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="sticky-top">
                    <div class="download-info text-muted mb-4">
                    <h4 class="fw-bold text-primary mb-3"><?php _e('Information about', 'tecnoinfor'); ?></h4>
                        <?php 
                        $terms = get_the_terms(get_the_ID(), 'download_category');
                        $versao = get_post_meta(get_the_ID(), 'download_versao', true);
                        $tamanho = get_post_meta(get_the_ID(), 'download_tamanho', true);
                        $sistema_operacional = get_post_meta(get_the_ID(), 'download_sistema_operacional', true);
                        $arquitetura = get_post_meta(get_the_ID(), 'download_arquitetura', true);
                        ?>
                        <?php if ($terms && !is_wp_error($terms)) : ?>
                            <p><strong><?php _e('Category:', 'tecnoinfor'); ?></strong> <?php echo esc_html($terms[0]->name); ?></p>
                        <?php else : ?>
                            <p><strong><?php _e('Category:', 'tecnoinfor'); ?></strong> <?php _e('Not specified', 'tecnoinfor'); ?></p>
                        <?php endif; ?>
                        <p><strong><?php _e('Operating System:', 'tecnoinfor'); ?></strong> <?php echo esc_html($sistema_operacional ?: __('Not specified', 'tecnoinfor')); ?></p>
                        <p><strong><?php _e('Architecture:', 'tecnoinfor'); ?></strong> <?php echo esc_html($arquitetura ?: __('Not specified', 'tecnoinfor')); ?></p>
                        <p><strong><?php _e('Version:', 'tecnoinfor'); ?></strong> <?php echo esc_html($versao ?: __('Not specified', 'tecnoinfor')); ?></p>
                        <p><strong><?php _e('Size:', 'tecnoinfor'); ?></strong> <?php echo esc_html($tamanho ?: __('Not specified', 'tecnoinfor')); ?></p>
                    </div>

                    <!-- Alerta de Compatibilidade 
                    <div class="alert alert-warning alert-dismissible fade show my-2" role="alert">
                        <strong><?php _e('Note:', 'tecnoinfor'); ?></strong> <?php _e('This program is compatible with desktop operating systems only.', 'tecnoinfor'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?php _e('Close', 'tecnoinfor'); ?>"></button>
                    </div> -->

                    <?php 
                    $link_download = get_post_meta(get_the_ID(), 'link_download', true);
                    if ($link_download && filter_var($link_download, FILTER_VALIDATE_URL)) : 
                        $file_name = basename(parse_url($link_download, PHP_URL_PATH));
                    ?>
                        <div class="download-link mt-3 d-grid gap-2 text-center">
                            <a class="btn btn-lg btn-success py-3 px-5 d-flex justify-content-between align-items-center"
                               href="<?php echo esc_url($link_download); ?>" target="_blank" rel="noopener noreferrer"
                               download="<?php echo esc_attr($file_name); ?>"
                               aria-label="<?php _e('Download', 'tecnoinfor') . ' ' . esc_attr($file_name); ?>">
                                <span><?php _e('Download', 'tecnoinfor'); ?></span>
                                <i class="bi bi-download fs-4"></i>
                            </a>
                        </div>
                    <?php else : ?>
                        <div class="mt-3 text-muted text-center">
                            <p><?php _e('Download link unavailable or invalid.', 'tecnoinfor'); ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Downloads Relacionados -->
                    <div class="related-downloads mt-4">
                        <h4 class="fw-bold text-primary mb-3"><?php _e('Related Downloads', 'tecnoinfor'); ?></h4>
                        <?php
                        $terms = get_the_terms(get_the_ID(), 'download_category');
                        $category_ids = $terms && !is_wp_error($terms) ? wp_list_pluck($terms, 'term_id') : array();
                        $related_args = array(
                            'post_type' => 'downloads',
                            'posts_per_page' => 3,
                            'post__not_in' => array(get_the_ID()),
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'download_category',
                                    'field' => 'term_id',
                                    'terms' => $category_ids,
                                ),
                            ),
                        );
                        $related_query = new WP_Query($related_args);
                        if ($related_query->have_posts()) :
                            while ($related_query->have_posts()) : $related_query->the_post();
                        ?>
                                <div class="mb-3">
                                    <h5 class="fs-6"><a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none"><?php the_title(); ?></a></h5>
                                    <small class="text-muted"><?php echo esc_html(get_post_meta(get_the_ID(), 'download_versao', true) ?: __('N/A', 'tecnoinfor')); ?></small>
                                </div>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        else :
                            echo '<p class="text-muted">' . __('No related downloads found.', 'tecnoinfor') . '</p>';
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>