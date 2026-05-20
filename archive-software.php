<?php
/**
 * Template Name: Archive - Software
 * Description: Lista todos os softwares cadastrados em seções alternadas esquerda/direita.
 */
get_header();
?>

<main class="main-content">
    <?php $banner_image = get_template_directory_uri() . '/assets/images/default-banner.jpg'; ?>
    <section class="banner-breadcrumb bg-delp"
            style="background: linear-gradient(to bottom, rgba(11, 94, 215, 0.3) 0%, rgba(11, 94, 215, 0.4) 50%, rgba(11, 94, 215, 0.8) 100%), url('<?php echo esc_url($banner_image); ?>'); background-size: cover; background-position: center;">
            <div class="container">
                <div class="row p-5 pb-0 pe-lg-0 py-lg-5 align-items-center" style="position: relative; z-index: 2;">
                    <!-- Breadcrumb -->
                    <?php echo tecnoinfor_get_breadcrumb(); ?>

                    <!-- Título e Descrição -->
                    <div class="d-flex flex-column align-items-start text-left">
                        <?php 
                        if (is_post_type_archive('software')) {
                            $page_title = post_type_archive_title('', false);
                            $page_desc = get_the_archive_description();
                        } else {
                            $page_title = get_the_title();
                            $page_desc = has_excerpt() ? get_the_excerpt() : '';
                        }
                        ?>
                        <h1 class="display-4 text-white fw-bolder">
                            <?php echo esc_html($page_title); ?>
                        </h1>
                        <div class="text-white col-lg-8 page-summary">
                            <?php if ($page_desc) : ?>
                            <p><?php echo wp_kses_post($page_desc); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    <section class="software-archive py-5 bg-light">
        <?php
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $args = array(
                'post_type' => 'software',
                'posts_per_page' => 6,
                'paged' => $paged,
            );
            $software_query = new WP_Query($args);
            if ($software_query->have_posts()) :
                $count = 0;
                while ($software_query->have_posts()) : $software_query->the_post();
                    $count++;
                    $is_odd = ($count % 2 !== 0); // Determina se é ímpar (esquerda) ou par (direita)
                    $bg_class = $is_odd ? 'bg-white' : 'bg-light';
                    $download_link = get_post_meta(get_the_ID(), '_software_download_link', true);
                    $video_url = get_post_meta(get_the_ID(), '_software_video_url', true);
            ?>
        <!-- Seção Individual com Alternância -->
        <div class="software-section py-5 <?php echo esc_attr($bg_class); ?>">
            <div class="container py-5">
                <div class="row g-5 align-items-center <?php echo $is_odd ? '' : 'flex-row-reverse'; ?>">
                    <!-- Conteúdo -->
                    <div class="col-lg-6 text-center text-lg-start">
                        <h2 class="display-5 fw-bold text-primary mb-3"><?php the_title(); ?></h2>
                        <h4 class="display-7 fw-bold mb-4"><?php echo wp_trim_words(get_the_excerpt()); ?></h4>
                        <p class="lead text-muted mb-4"><?php echo wp_trim_words(get_the_content(), 30, '...'); ?></p>
                        <?php
                            $categories = get_the_terms(get_the_ID(), 'software_category');
                            if ($categories && !is_wp_error($categories)) :
                                $category_names = wp_list_pluck($categories, 'name');
                            ?>
                        <p class="mb-4">
                            <small class="text-muted">
                                <i class="bi bi-tags-fill text-primary me-1"></i>
                                <?php echo esc_html(implode(', ', $category_names)); ?>
                            </small>
                        </p>
                        <?php endif; ?>
                        <div class="d-flex gap-3 flex-wrap justify-content-center justify-content-lg-start">
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-lg px-4 py-2">
                            <small class="fw-bold"><?php _e('Find out more', 'tecnoinfor'); ?></small>
                            </a>
                        </div>
                    </div>

                    <!-- Imagem ou Vídeo -->
                    <div class="col-lg-6">
                        <?php if ($video_url && $video_embed = wp_oembed_get($video_url, array('width' => 500))) : ?>
                        <div class="ratio ratio-16x9 shadow-lg rounded" style="max-width: 500px; margin: 0 auto;">
                            <?php echo $video_embed; ?>
                        </div>
                        <?php elseif (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large', array(
                                    'class' => 'img-fluid rounded shadow-lg',
                                    'alt' => esc_attr(get_the_title()),
                                    'loading' => 'lazy'
                                )); ?>
                        <?php else : ?>
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/hero-img.png'); ?>"
                            class="img-fluid rounded shadow-lg" alt="<?php the_title(); ?>" loading="lazy">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
                endwhile;
            ?>
        <!-- Paginação -->
        <div class="pagination mt-5 text-center">
            <?php
                    echo paginate_links(array(
                        'total' => $software_query->max_num_pages,
                        'current' => $paged,
                        'prev_text' => __('<i class="bi bi-chevron-left"></i> Previous'),
                        'next_text' => __('Next <i class="bi bi-chevron-right"></i>'),
                        'type' => 'list',
                        'add_args' => false,
                        'add_fragment' => '',
                    ));
                    ?>
        </div>
        <?php
                wp_reset_postdata();
            else :
            ?>
        <p class="text-center text-muted py-5"><?php echo __('No software found.', 'tecnoinfor'); ?></p>
        <?php endif; ?>

    </section>
</main>

<?php get_footer(); ?>