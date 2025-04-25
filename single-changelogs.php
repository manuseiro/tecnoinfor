<?php
get_header();
?>
<main class="main-content">
<?php 
// Inclui o conteúdo do header puxado do arquivo template-parts/content-header.php
get_template_part('template-parts/content', 'header'); 
?>

    <!-- Conteúdo da página -->
    <div class="container changelog-container my-5">
        <div class="col-12 col-lg-8">
            <?php while (have_posts()) : the_post(); ?>

            <div class="content-header">
                <p><strong><?php _e('Version:', 'tecnoinfor'); ?></strong> <?php the_title(); ?> -
                    <strong><?php _e('Released on:', 'tecnoinfor'); ?></strong> <?php the_date('F j, Y'); ?></p>
            </div>
            <div class="content">
                <div class="d-flex flex-column gap-3 py-2">
                    <?php
                    $meta = get_changelog_meta(get_the_ID());
                    $labels = [
                        'added' => ['Adicionado', 'btn-success'],
                        'fixed' => ['Correção', 'btn-primary'],
                        'updated' => ['Atualização', 'btn-info'],
                        'improved' => ['Melhoria', 'btn-warning'],
                        'removed' => ['Removido', 'btn-danger'],
                        'deprecated' => ['Descontinuado', 'btn-secondary'],
                        'compatibility' => ['Compatibilidade', 'btn-dark'],
                    ];

                    foreach ($labels as $key => $info) {
                        if (!empty($meta[$key])) {
                            echo '<div>';
                            echo '<button class="btn ' . $info[1] . ' rounded-pill px-3 mb-2" type="button">' . $info[0] . '</button>';
                            echo '<p>' . nl2br(esc_html($meta[$key])) . '</p>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <div class="col-12 col-lg-4">
        <?php
                    // Obtém as categorias do post atual
                    $categories = get_the_category();
                    $category_ids = !empty($categories) ? wp_list_pluck($categories, 'term_id') : array();

                    if (!empty($category_ids)) :
                        $related_args = array(
                            'post_type' => 'changelogs',
                            'posts_per_page' => 5, // Limite de posts exibidos
                            'post__not_in' => array(get_the_ID()), // Exclui o post atual
                            'category__in' => $category_ids, // Filtra por categorias do post atual
                            'orderby' => 'date',
                            'order' => 'DESC',
                        );
                        $related_query = new WP_Query($related_args);

                        if ($related_query->have_posts()) :
                            while ($related_query->have_posts()) : $related_query->the_post();
                    ?>
                            <div class="related-post mb-4 d-flex align-items-center sticky-top">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>" class="me-3">
                                        <?php the_post_thumbnail('thumbnail', array(
                                            'class' => 'img-fluid rounded',
                                            'loading' => 'lazy',
                                            'style' => 'width: 100px; height: auto;'
                                        )); ?>
                                    </a>
                                <?php endif; ?>
                                <div class="flex-grow-1">
                                    <h3 class="fs-6 fw-bold mb-0">
                                        <a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                </div>
                            </div>
                    <?php
                            endwhile;
                        else :
                    ?>
                            <p class="text-muted"><?php _e('No related posts found.', 'tecnoinfor'); ?></p>
                    <?php
                        endif;
                        wp_reset_postdata();
                    else :
                    ?>
                        <p class="text-muted"><?php _e('No categories associated with this post.', 'tecnoinfor'); ?></p>
                    <?php endif; ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>