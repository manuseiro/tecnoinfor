<?php
/**
 * Template para exibir a página Changelog.
 *
 * @package Tecnoinfor
 * Template Name: Changelog
 */

get_header(); ?>

<main class="main-content">
    <?php
    // Inclui o conteúdo do header puxado do arquivo template-parts/content-header.php
    get_template_part('template-parts/content', 'header');
    ?>

    <div class="container my-5 p-5">
        <?php
        // Argumentos para o loop personalizado
        $args = array(
            'post_type'      => 'changelog',  // O CPT registrado
            'posts_per_page' => 10,           // Número de posts por página
            'paged'          => get_query_var('paged') ? get_query_var('paged') : 1 // Paginação
        );

        // Query personalizada
        $changelog_query = new WP_Query($args);

        // Verifica se existem posts
        if ($changelog_query->have_posts()) : ?>
            <ul class="changelog-list">
                <?php while ($changelog_query->have_posts()) : $changelog_query->the_post(); ?>
                    <li class="changelog-item">
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p class="changelog-date">
                            <?php _e('Version:', 'tecnoinfor'); ?> <?php echo get_post_meta(get_the_ID(), '_changelog_version', true); ?>
                            | <?php _e('Released on:', 'tecnoinfor'); ?> <?php echo get_post_meta(get_the_ID(), '_changelog_release_date', true); ?>
                        </p>
                        <p class="changelog-excerpt"><?php the_excerpt(); ?></p>
                    </li>
                <?php endwhile; ?>
            </ul>

            <div class="pagination">
                <?php
                // Paginação
                echo paginate_links(array(
                    'total' => $changelog_query->max_num_pages
                ));
                ?>
            </div>

        <?php else : ?>
            <p><?php _e('No changelogs found.', 'tecnoinfor'); ?></p>
        <?php endif; ?>

        <?php
        // Restaura o post global após o loop personalizado
        wp_reset_postdata();
        ?>
    </div>
</main>

<?php get_footer(); ?>
