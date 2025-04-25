<?php
/**
 * Template para exibir um Software individual.
 *
 * @package Tecnoinfor
 */
get_header();
?>

<header id="banner-header" class="px-4 py-5 text-center text-white bg-primary">
    <div class="container">
        <h1 class="display-4 pt-5 fw-bold"><?php the_title(); ?></h1>
        <div class="col-lg-8 mx-auto pb-5">
            <?php if (has_excerpt()) : ?>
                <p class="lead my-4"><?php the_excerpt(); ?></p>
            <?php endif; ?>
            <div class="d-grid gap-3 d-sm-flex justify-content-sm-center mt-4">
                <?php
                $download_link = get_post_meta(get_the_ID(), '_software_download_link', true);
                $trial_period = get_post_meta(get_the_ID(), '_software_trial_period', true) ?: '15 dias';
                $contact_link = esc_url(home_url('/contato/'));
                ?>
                <?php if ($download_link) : ?>
                    <a href="<?php echo esc_url($download_link); ?>" 
                       class="btn btn-success btn-lg py-3 px-5" 
                       target="_blank" rel="noopener noreferrer" 
                       aria-label="<?php _e('Download Software', 'tecnoinfor'); ?>">
                        <?php _e('Download Software', 'tecnoinfor'); ?> <i class="bi bi-download ms-2"></i>
                    </a>
                <?php endif; ?>
                <a href="<?php echo $contact_link; ?>" 
                   class="btn btn-outline-light btn-lg py-3 px-5" 
                   aria-label="<?php _e('Commercial Contact', 'tecnoinfor'); ?>">
                    <?php _e('Commercial Contact', 'tecnoinfor'); ?>
                </a>
            </div>
            <?php if ($download_link) : ?>
                <small class="d-block mt-3 text-white-50">
                    <?php printf(__('Download the trial version and enjoy it for %s days*', 'tecnoinfor'), esc_html($trial_period)); ?>
                </small>
            <?php endif; ?>
            <?php
            $changelog_query = new WP_Query(array(
                'post_type' => 'changelogs',
                'posts_per_page' => 1,
                'meta_query' => array(
                    array(
                        'key' => '_related_software',
                        'value' => get_the_ID(),
                        'compare' => '=',
                    ),
                ),
                'orderby' => 'date',
                'order' => 'DESC',
            ));
            if ($changelog_query->have_posts()) : 
                $changelog_query->the_post();
            ?>
                <span class="d-block mt-4">
                    <?php printf(__('Current Version: <a href="%s" class="text-white">%s</a>', 'tecnoinfor'), 
                        esc_url(get_permalink()), 
                        esc_html(get_the_title())); ?>
                </span>
            <?php 
                wp_reset_postdata(); 
            endif; 
            ?>
        </div>
    </div>
</header>

<main class="main-content" id="main-content">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="py-3 bg-light">
        <div class="container">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php _e('Home', 'tecnoinfor'); ?></a></li>
                <li class="breadcrumb-item"><a href="<?php echo esc_url(get_post_type_archive_link('software')); ?>"><?php _e('Softwares', 'tecnoinfor'); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php the_title(); ?></li>
            </ol>
        </div>
    </nav>

    <!-- Overview -->
    <section class="overview py-5 bg-light">
        <div class="container">
            <h2 class="display-6 fw-bold text-primary text-center"><?php printf(__('Discover %s', 'tecnoinfor'), get_the_title()); ?></h2>
            <p class="col-lg-10 mx-auto lead text-muted text-center mb-5"><?php echo wp_trim_words(get_the_content(), 30, '...'); ?></p>
            <div class="row g-5 align-items-center">
                <div class="col-12 col-lg-6">
                    <div class="software-content"><?php the_content(); ?></div>
                    <?php
                    $categories = get_the_terms(get_the_ID(), 'software_category');
                    if ($categories && !is_wp_error($categories)) :
                    ?>
                        <p><strong><?php _e('Category:', 'tecnoinfor'); ?></strong> <?php echo esc_html(implode(', ', wp_list_pluck($categories, 'name'))); ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-12 col-lg-6 text-center">
                    <?php
                    $video_url = get_post_meta(get_the_ID(), '_software_video_url', true);
                    if ($video_url && $video_embed = wp_oembed_get($video_url, array('width' => 500))) :
                    ?>
                        <div class="ratio ratio-16x9 shadow-lg rounded" style="max-width: 500px; margin: 0 auto;">
                            <?php echo $video_embed; ?>
                        </div>
                    <?php elseif (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large', array(
                            'class' => 'img-fluid rounded shadow-lg',
                            'alt' => esc_attr(get_the_title()),
                            'loading' => 'lazy'
                        )); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Funcionalidades -->
    <section class="functionalities py-5 bg-light">
        <div class="container">
            <h2 class="display-6 fw-bold text-primary text-center"><?php _e('Key Features', 'tecnoinfor'); ?></h2>
            <p class="col-lg-10 mx-auto lead text-muted text-center mb-5">
                <?php printf(__('Learn about the features that make %s special.', 'tecnoinfor'), get_the_title()); ?>
            </p>
            <?php
            $functionalities = get_post_meta(get_the_ID(), '_software_functionalities', true);
            if (is_array($functionalities) && !empty($functionalities)) :
                $limited_functionalities = array_slice($functionalities, 0, 6);
            ?>
                <div class="row g-4 py-5 row-cols-1 row-cols-lg-2">
                    <?php foreach ($limited_functionalities as $func) : ?>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body p-4 feature-item">
                                    <?php if (!empty($func['icon'])) : ?>
                                        <i class="<?php echo esc_attr($func['icon']); ?> fs-2 text-primary d-block mb-3"></i>
                                    <?php endif; ?>
                                    <h3 class="card-title fs-4 fw-bold text-primary mb-3"><?php echo esc_html($func['title']); ?></h3>
                                    <p class="card-text text-muted"><?php echo esc_html($func['description']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#functionalitiesModal">
                        <?php _e('View All Features', 'tecnoinfor'); ?>
                    </button>
                </div>
            <?php else : ?>
                <p class="text-center text-muted"><?php _e('No features registered yet.', 'tecnoinfor'); ?></p>
            <?php endif; ?>
        </div>
        
    </section>

    <!-- Modal de Funcionalidades -->
    <div class="modal fade" id="functionalitiesModal" tabindex="-1" aria-labelledby="functionalitiesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="functionalitiesModalLabel"><?php printf(__('All Features of %s', 'tecnoinfor'), get_the_title()); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php _e('Close', 'tecnoinfor'); ?>"></button>
                </div>
                <div class="modal-body">
                    <?php if (is_array($functionalities) && !empty($functionalities)) : ?>
                        <?php foreach ($functionalities as $func) : ?>
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <?php if (!empty($func['icon'])) : ?>
                                        <i class="<?php echo esc_attr($func['icon']); ?> fs-4 text-primary me-2"></i>
                                    <?php endif; ?>
                                    <h6 class="fw-bold text-primary mb-0"><?php echo esc_html($func['title']); ?></h6>
                                </div>
                                <p class="text-muted"><?php echo esc_html($func['description']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="text-muted"><?php _e('No features available.', 'tecnoinfor'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php _e('Close', 'tecnoinfor'); ?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Produtos Relacionados -->
    <section class="related-softwares py-5 bg-light">
        <div class="container">
            <h2 class="display-6 fw-bold text-primary text-center"><?php _e('Related Products', 'tecnoinfor'); ?></h2>
            <div class="row g-4 mt-4">
                <?php
                $current_categories = wp_get_post_terms(get_the_ID(), 'software_category', array('fields' => 'ids'));
                $related_args = array(
                    'post_type' => 'software',
                    'posts_per_page' => 3,
                    'post__not_in' => array(get_the_ID()),
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'software_category',
                            'field' => 'id',
                            'terms' => $current_categories,
                        ),
                    ),
                );
                $related_query = new WP_Query($related_args);
                if ($related_query->have_posts()) :
                    while ($related_query->have_posts()) : $related_query->the_post();
                ?>
                    <div class="col-12 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium', array('class' => 'card-img-top', 'alt' => esc_attr(get_the_title()), 'loading' => 'lazy')); ?>
                            <?php endif; ?>
                            <div class="card-body">
                                <h3 class="card-title fs-5 fw-bold"><a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none"><?php the_title(); ?></a></h3>
                                <p class="card-text text-muted"><?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?></p>
                            </div>
                        </div>
                    </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                ?>
                    <p class="text-center text-muted"><?php _e('No related software found.', 'tecnoinfor'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta py-5 bg-primary text-white text-center">
        <div class="container my-5 px-4 px-lg-5">
            <h2 class="display-6 fw-bold"><?php printf(__('Ready to Try %s?', 'tecnoinfor'), get_the_title()); ?></h2>
            <p class="lead mt-3 col-lg-8 mx-auto"><?php _e('Try it now and see how it can optimize your processes.', 'tecnoinfor'); ?></p>
            <?php if ($download_link) : ?>
                <a href="<?php echo esc_url($download_link); ?>" 
                   class="btn btn-success btn-lg mt-4 py-3 px-5" 
                   target="_blank" rel="noopener noreferrer" 
                   aria-label="<?php _e('Try for Free', 'tecnoinfor'); ?>">
                    <?php _e('Try for Free', 'tecnoinfor'); ?> <i class="bi bi-download ms-2"></i>
                </a>
            <?php endif; ?>
        </div>
    </section>

    <!-- FAQs -->
    <section class="faq py-5">
        <div class="container my-5 px-4 px-lg-5">
            <h2 class="display-6 fw-bold text-primary text-center"><?php _e('Frequently Asked Questions', 'tecnoinfor'); ?></h2>
            <p class="col-lg-10 mx-auto lead text-muted text-center mb-5">
                <?php printf(__('Check out FAQs related to %s.', 'tecnoinfor'), get_the_title()); ?>
            </p>
            <?php
            $faq_query = new WP_Query(array(
                'post_type' => 'faqs',
                'meta_query' => array(
                    array(
                        'key' => '_related_software',
                        'value' => get_the_ID(),
                        'compare' => '=',
                    ),
                ),
            ));
            if ($faq_query->have_posts()) :
            ?>
                <div class="col-lg-8 mx-auto">
                    <div class="accordion accordion-flush mb-5 border" id="accordionFaqs">
                        <?php while ($faq_query->have_posts()) : $faq_query->the_post(); ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-<?php the_ID(); ?>">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#collapse-<?php the_ID(); ?>" aria-expanded="false" 
                                            aria-controls="collapse-<?php the_ID(); ?>">
                                        <?php the_title(); ?>
                                    </button>
                                </h2>
                                <div id="collapse-<?php the_ID(); ?>" class="accordion-collapse collapse" 
                                    aria-labelledby="heading-<?php the_ID(); ?>" data-bs-parent="#accordionFaqs">
                                    <div class="accordion-body"><?php the_content(); ?></div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php else : ?>
                <p class="text-center text-muted"><?php _e('No FAQs related to this software.', 'tecnoinfor'); ?></p>
            <?php endif; wp_reset_postdata(); ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>