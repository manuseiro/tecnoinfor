<?php

/**
 * Template para exibir a página Changelog.
 *
 * @package Tecnoinfor
 * Template Name: Changelogs
 */

get_header(); ?>

<main class="main-content">
    <?php
    // Inclui o conteúdo do header puxado do arquivo template-parts/content-header.php
    get_template_part('template-parts/content', 'header');
    ?>

    <div class="container my-5 p-5">
        <div class="db-main">
            <div class="db-toc">

            </div>
            <div class="bd-content">

                <?php
                // Argumentos para o loop personalizado
                $args = array(
                    'post_type'      => 'changelog',
                    'posts_per_page' => 10,
                    'paged'          => get_query_var('paged') ? get_query_var('paged') : 1
                );

                // Query personalizada
                $changelog_query = new WP_Query($args);

                // Verifica se existem posts
                if ($changelog_query->have_posts()) : ?>
                    <ul class="changelog-list">
                        <?php while ($changelog_query->have_posts()) : $changelog_query->the_post(); ?>
                            <li class="changelog-item p-4 mb-4 border">
                                <h2>
                                    <a href="<?php the_permalink(); ?>" class="text-decoration-none text-primary">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                                <p class="changelog-date text-muted">
                                    <span class="text-dark">
                                        <?php _e('Version:', 'tecnoinfor'); ?> <?php echo esc_html(get_post_meta(get_the_ID(), '_changelog_version', true)); ?>
                                    </span>
                                    |
                                    <span>
                                        <?php _e('Released on:', 'tecnoinfor'); ?> <?php echo esc_html(date('F j, Y', strtotime(get_post_meta(get_the_ID(), '_changelog_release_date', true)))); ?>
                                    </span>
                                </p>

                                <div class="changelog-content">
                                    <?php
                                    $meta_keys = [
                                        '_changelog_added' => __('Added', 'tecnoinfor'),
                                        '_changelog_fixed' => __('Fixed', 'tecnoinfor'),
                                        '_changelog_removed' => __('Removed', 'tecnoinfor'),
                                        '_changelog_updated' => __('Updated', 'tecnoinfor'),
                                        '_changelog_deprecated' => __('Deprecated', 'tecnoinfor'),
                                        '_changelog_improved' => __('Improved', 'tecnoinfor'),
                                        '_changelog_compatibility' => __('Compatibility', 'tecnoinfor'),
                                    ];

                                    foreach ($meta_keys as $meta_key => $label) {
                                        $value = get_post_meta(get_the_ID(), $meta_key, true);
                                        if (!empty($value)) : ?>
                                            <span class="badge text-bg-info"><?php echo esc_html($label); ?>:</span>
                                            <p><?php echo nl2br(esc_html($value)); ?></p>
                                    <?php endif;
                                    } ?>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    </ul>

                    <div class="pagination mt-4">
                        <?php
                        // Paginação
                        echo paginate_links(array(
                            'total' => $changelog_query->max_num_pages,
                            'prev_text' => __('« Previous', 'tecnoinfor'),
                            'next_text' => __('Next »', 'tecnoinfor'),
                        ));
                        ?>
                    </div>

                <?php else : ?>
                    <p><?php _e('No changelogs found.', 'tecnoinfor'); ?></p>
                <?php endif; ?>

                <?php wp_reset_postdata(); ?>

            </div>
        </div>
    </div>
    <div class="z-1 flex shrink-0 grow basis-auto justify-center px-5 sm:px-10">
        <section class="max-w-full w-240" data-testid="main-content"></section>
    </div>
</main>

<?php get_footer(); ?>