<?php
/**
 * Template para exibir o arquivo de Changelogs.
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
        <div class="db-main row">
            <div class="db-toc col-md-3 col-12">
                <h5 class="d-none d-md-block h6 my-2 ms-3"><?php _e('On this page', 'tecnoinfor'); ?></h5>
                <hr class="d-none d-md-block my-2 ms-3">
                <nav class="sticky-top">
                    <ul class="nav flex-column">
                        <?php
                        $args = array(
                            'post_type' => 'changelogs',
                            'posts_per_page' => 10,
                            'paged' => get_query_var('paged') ? get_query_var('paged') : 1
                        );
                        $changelog_query = new WP_Query($args);
                        if ($changelog_query->have_posts()) :
                            $index = 0;
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
                $changelog_query->rewind_posts();
                if ($changelog_query->have_posts()) : ?>
                <div class="accordion" id="accordionExample">
                    <?php $index = 0; ?>
                    <?php while ($changelog_query->have_posts()) : $changelog_query->the_post(); ?>
                    <?php $index++; $collapse_id = 'collapse' . $index; ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                            <button class="accordion-button <?php echo $index === 1 ? '' : 'collapsed'; ?>"
                                type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $collapse_id; ?>"
                                aria-expanded="<?php echo $index === 1 ? 'true' : 'false'; ?>"
                                aria-controls="<?php echo $collapse_id; ?>">
                                <?php the_title(); ?>
                            </button>
                        </h2>
                        <div id="<?php echo $collapse_id; ?>"
                            class="accordion-collapse collapse <?php echo $index === 1 ? 'show' : ''; ?>"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                            <div class="content-header">
                <p><strong><?php _e('Version:', 'tecnoinfor'); ?></strong> <?php the_title(); ?> - <strong><?php _e('Released on:', 'tecnoinfor'); ?></strong> <?php the_date('F j, Y'); ?></p>
            </div>
                                <?php
                                    $meta = get_changelog_meta(get_the_ID());
                    $labels = [
                        'added' => ['Adicionado', 'text-bg-success'],
                        'fixed' => ['Correção', 'text-bg-primary'],
                        'updated' => ['Atualização', 'text-bg-info'],
                        'improved' => ['Melhoria', 'text-bg-warning'],
                        'removed' => ['Removido', 'text-bg-danger'],
                        'deprecated' => ['Descontinuado', 'text-bg-secondary'],
                        'compatibility' => ['Compatibilidade', 'text-bg-dark'],
                    ];
                                        foreach ($labels as $key => $value) {
                                            if (!empty($meta[$key])) {
                                                echo '<span class="badge ' . $value[1] . ' rounded-pill">' . $value[0] . ':</span>';
                                                echo '<p>' . nl2br(esc_html($meta[$key])) . '</p>';
                                            }
                                        }
                                        ?>
                                        
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                <div class="pagination mt-4">
                    <?php
                        echo paginate_links(array(
                            'total' => $changelog_query->max_num_pages,
                            'prev_text' => __('<i class="bi bi-chevron-left"></i> Previous', 'tecnoinfor'),
                            'next_text' => __('Next <i class="bi bi-chevron-right"></i>', 'tecnoinfor'),
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
</main>

<?php get_footer(); ?>