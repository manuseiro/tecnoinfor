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
                        $current_paged = get_query_var('paged') ? get_query_var('paged') : 1;
                        $args = array(
                            'post_type' => 'changelogs',
                            'posts_per_page' => 10,
                            'paged' => $current_paged
                        );
                        $changelog_query = new WP_Query($args);
                        if ($changelog_query->have_posts()) :
                            $index = 0;
                            $last_major_sidebar = null;
                            while ($changelog_query->have_posts()) : $changelog_query->the_post();
                                $index++;
                                $title = get_the_title();
                                preg_match('/v?(\d+)\./i', $title, $matches);
                                $major_version = !empty($matches[1]) ? 'Versão ' . $matches[1] . '.x' : 'Outros';
                                if ($major_version !== $last_major_sidebar) {
                                    $last_major_sidebar = $major_version;
                                    echo '<li class="nav-item group-header mt-2 fw-bold text-muted ps-3 small text-uppercase">' . esc_html($major_version) . '</li>';
                                }
                                $is_latest = ($index === 1 && $current_paged <= 1);
                        ?>
                        <li class="nav-item">
                            <a class="nav-link py-1" href="#heading<?php echo $index; ?>">
                                <?php the_title(); ?>
                                <?php if ($is_latest) : ?>
                                    <span class="badge bg-success ms-1 text-white" style="font-size: 0.7rem;"><?php _e('Atual', 'tecnoinfor'); ?></span>
                                <?php endif; ?>
                            </a>
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
                    <?php 
                    $index = 0; 
                    $last_major_version = null;
                    ?>
                    <?php while ($changelog_query->have_posts()) : $changelog_query->the_post(); ?>
                    <?php 
                    $index++; 
                    $collapse_id = 'collapse' . $index; 
                    $title = get_the_title();
                    preg_match('/v?(\d+)\./i', $title, $matches);
                    $major_version = !empty($matches[1]) ? 'Versão ' . $matches[1] . '.x' : 'Outros';
                    
                    if ($major_version !== $last_major_version) {
                        $last_major_version = $major_version;
                        echo '<h4 class="mt-4 mb-3 text-secondary border-bottom pb-2 wow fadeIn">' . esc_html($major_version) . '</h4>';
                    }
                    $is_latest = ($index === 1 && $current_paged <= 1);
                    ?>
                    <div class="accordion-item mb-2 border rounded shadow-sm wow fadeInUp" data-wow-delay="0.1s">
                        <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                            <button class="accordion-button <?php echo $index === 1 ? '' : 'collapsed'; ?>"
                                type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $collapse_id; ?>"
                                aria-expanded="<?php echo $index === 1 ? 'true' : 'false'; ?>"
                                aria-controls="<?php echo $collapse_id; ?>">
                                <span class="fw-bold"><?php the_title(); ?></span>
                                <?php if ($is_latest) : ?>
                                    <span class="badge bg-success ms-2 text-white"><?php _e('Versão Atual', 'tecnoinfor'); ?></span>
                                <?php endif; ?>
                            </button>
                        </h2>
                        <div id="<?php echo $collapse_id; ?>"
                             class="accordion-collapse collapse <?php echo $index === 1 ? 'show' : ''; ?>"
                             data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                            <div class="content-header mb-3">
                                <p class="text-muted mb-0"><strong><?php _e('Version:', 'tecnoinfor'); ?></strong> <?php the_title(); ?> - <strong><?php _e('Released on:', 'tecnoinfor'); ?></strong> <?php the_date('F j, Y'); ?></p>
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
                                            echo '<span class="badge ' . $value[1] . ' rounded-pill mb-2">' . $value[0] . ':</span>';
                                            echo '<p class="mb-3 text-secondary">' . nl2br(esc_html($meta[$key])) . '</p>';
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