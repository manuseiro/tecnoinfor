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
    // Inclui o conteúdo do header
    get_template_part('template-parts/content', 'header');
    ?>

    <div class="container my-5 p-5">
        <div class="db-main row">
            <div class="db-breadcrumb col-12">
                <?php if (function_exists('bcn_display')) : ?>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <?php bcn_display(); ?>
                        </ol>
                    </nav>
                <?php endif; ?>
            </div>
            <div class="db-toc col-md-3 col-12 ">
                <h5 class="d-none d-md-block h6 my-2 ms-3"><?php _e('On this page', 'tecnoinfor'); ?></h5>
                <hr class="d-none d-md-block my-2 ms-3">
                <nav class="sticky-top">
                    <ul class="nav flex-column">
                        <?php
                        // Query personalizada
                        $args = array(
                            'post_type'      => 'changelog',
                            'posts_per_page' => 10,
                            'paged'          => get_query_var('paged') ? get_query_var('paged') : 1
                        );

                        $changelog_query = new WP_Query($args);

                        if ($changelog_query->have_posts()) :
                            $index = 0; // Contador para IDs únicos
                            while ($changelog_query->have_posts()) : $changelog_query->the_post();
                                $index++;
                        ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="#heading<?php echo $index; ?>"><?php the_title(); ?></a>
                                </li>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <li class="nav-item"><?php _e('No changelogs found.', 'tecnoinfor'); ?></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <div class="bd-content col-md-9 col-12">
                <?php
                // Reseta a consulta
                wp_reset_postdata();
                $changelog_query->rewind_posts(); // Rewind para usar novamente a query

                if ($changelog_query->have_posts()) : ?>
                    <div class="accordion" id="accordionExample">
                        <?php $index = 0; // Contador para IDs únicos 
                        ?>
                        <?php while ($changelog_query->have_posts()) : $changelog_query->the_post(); ?>
                            <?php
                            // Gerando um ID único para cada item do accordion
                            $index++;
                            $collapse_id = 'collapse' . $index; // ID para o colapso
                            ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading<?php echo $index; ?>" aria-labelledby="heading<?php echo $index; ?>" aria-expanded="<?php echo $index === 1 ? 'true' : 'false'; ?>">
                                    <button class="accordion-button <?php echo $index === 1 ? '' : 'collapsed'; ?>"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#<?php echo $collapse_id; ?>"
                                        aria-controls="<?php echo $collapse_id; ?>">
                                        <?php the_title(); ?>
                                    </button>
                                </h2>
                                <div id="<?php echo $collapse_id; ?>" class="accordion-collapse collapse <?php echo $index === 1 ? 'show' : ''; ?>"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
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
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>

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