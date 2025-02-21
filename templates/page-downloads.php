<?php
/**
 * Template para exibir a página Downloads.
 *
 * @package Tecnoinfor
 * Template Name: Downloads
 */
get_header();
?>

<main class="main-content">
    <?php get_template_part('template-parts/content', 'header'); ?>

    <div class="container my-5 p-5">
        <div class="row g-4">

            <?php
            $args = array(
                'post_type' => 'downloads',
                'posts_per_page' => -1,
                'orderby' => 'id',
                'order' => 'ASC',
            );
            $downloads_query = new WP_Query($args);

            if ($downloads_query->have_posts()) :
                while ($downloads_query->have_posts()) : $downloads_query->the_post();
                ?>
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm h-100">
                    <?php if (has_post_thumbnail()) : ?>
                    <div class="download-thumbnail mb-3">
                        <?php the_post_thumbnail('download_thumb', ['class' => 'card-img-top img-fluid rounded-top', 'alt' => esc_attr(get_the_title())]); ?>
                    </div>
                    <?php endif; ?>
                    <div class="card-body">
                    <h5 class="card-title h4 mt-2 fw-bold"><?php echo esc_html(get_the_title()); ?></h5>
                    <div class="card-text text-muted mb-3">
                        <?php echo wp_kses_post(get_the_excerpt()); ?> </div>
                        <?php
                        $link_download = get_post_meta(get_the_ID(), 'link_download', true);
                        if ($link_download) :
                         $file_name = basename(parse_url($link_download, PHP_URL_PATH)); // Get file name
                        ?>
                        <a class="download-button btn btn-primary align-items-center" href="<?php echo esc_url($link_download); ?>"
                            target="_blank" rel="noopener noreferrer">
                            Download <i class="bi bi-download mx-2"></i>
                        </a>
                        <?php else : ?>
                        <p class="text-center text-warning">Link de download indisponível.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
                    endwhile;
                wp_reset_postdata();
            else :
                ?>
            <div class="col">
                <p class="text-muted">Nenhum programa encontrado.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>