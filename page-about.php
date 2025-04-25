<?php

/**
 * Template Name: About Page
 * Description: A customizable About page template showcasing company history, mission, and services.
 *
 * @package Tecnoinfor
 * @since 1.0.0
 */
get_header();
?>

<main id="main-content" class="main-content" role="main">
    <?php
    // Recupera a imagem destacada ou usa uma padrão
    $banner_image = has_post_thumbnail()
        ? get_the_post_thumbnail_url(get_the_ID(), 'large')
        : get_template_directory_uri() . '/assets/images/default-banner.jpg';

    // Recupera o subtítulo personalizado
    $subtitle = get_post_meta(get_the_ID(), '_tecnoinfor_subtitle', true);

    // Recupera o logo da empresa
    $logo = get_informacao_empresa('logo');
    ?>

    <!-- Banner Section -->
    <section class="banner-breadcrumb py-5 bg-primary-subtle position-relative overflow-hidden"
        style="background: linear-gradient(to bottom, rgba(28, 68, 127, 0.5) 0%, rgba(28, 68, 127, 0.9) 100%), url('<?php echo esc_url($banner_image); ?>'); background-size: cover; background-position: center;"
        role="region" aria-labelledby="banner-heading">
        <div class="container py-5 py-lg-7">
            <div class="row align-items-center text-center text-lg-start">
                <div class="col-12 col-lg-8">
                    <h1 class="display-4 text-white fw-bold mb-3" id="banner-heading"><?php echo esc_html(get_the_title()); ?></h1>
                    <?php if ($subtitle) : ?>
                        <p class="lead text-white mb-0"><?php echo esc_html($subtitle); ?></p>
                    <?php elseif (has_excerpt()) : ?>
                        <p class="lead text-white mb-0"><?php echo wp_kses_post(get_the_excerpt()); ?></p>
                    <?php endif; ?>
                </div>
                <?php if ($logo) : ?>
                    <div class="col-12 col-lg-4 text-center mt-4 mt-lg-0">
                        <?php echo wp_kses_post($logo); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Company History Section -->
    <section class="history-section py-5" role="region" aria-labelledby="history-heading">
        <div class="container my-5 px-4 px-lg-5">
            <div class="row">
                <div class="col-12">
                    <h2 class="fs-3 fw-bold text-primary mb-4 wow fadeIn" id="history-heading"><?php _e('Our History', 'tecnoinfor'); ?></h2>
                    <div class="card shadow-sm border-0 p-4 lead bg-white wow fadeInUp">
                        <?php echo wp_kses_post(get_the_content()); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Systems Section -->
    <section class="systems-section py-5 bg-light" role="region" aria-labelledby="systems-heading">
        <div class="container my-5 px-4 px-lg-5">
            <h2 class="fs-3 fw-bold text-primary text-center mb-5 wow fadeIn" id="systems-heading"><?php _e('Our Systems', 'tecnoinfor'); ?></h2>
            <?php
            $args = array(
                'post_type' => 'software',
                'posts_per_page' => 3, // Exibir todos os softwares
                'post_status' => 'publish',
            );
            $software_query = new WP_Query($args);
            if ($software_query->have_posts()) : ?>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php while ($software_query->have_posts()) : $software_query->the_post();
                        $thumbnail = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : get_template_directory_uri() . '/assets/images/default-software.jpg';
                        $excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 20);
                    ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm border-0 bg-white text-center p-3 wow fadeInUp" data-wow-delay="<?php echo esc_attr(0.1 * ($software_query->current_post + 1)); ?>s">
                                <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="card-img-top mb-3 rounded" style="height: 150px; object-fit: cover;">
                                <h3 class="fs-4 fw-bold text-dark mb-3"><?php the_title(); ?></h3>
                                <p class="text-muted mb-3"><?php echo esc_html($excerpt); ?></p>
                                <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm" aria-label="<?php printf(__('Learn more about %s', 'tecnoinfor'), esc_attr(get_the_title())); ?>">
                                    <?php _e('Learn More', 'tecnoinfor'); ?>
                                </a>
                            </div>
                        </div>
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                </div>
            <?php else : ?>
                <div class="text-center text-muted">
                    <p><?php _e('No software available at the moment.', 'tecnoinfor'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Differentials Section -->
    <section class="differentials-section py-5" role="region" aria-labelledby="differentials-heading">
        <div class="container my-5 px-4 px-lg-5">
            <h2 class="fs-3 fw-bold text-primary text-center mb-5 wow fadeIn" id="differentials-heading"><?php _e('Our Differentials', 'tecnoinfor'); ?></h2>
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <div class="card shadow-sm border-0 p-4 bg-white text-center wow fadeInUp">
                        <p class="lead text-muted mb-0"><?php _e('Our differential is <strong>personalized service</strong> and <strong>commitment to quality</strong> in every project. We develop tailored solutions that meet our clients\' specific needs.', 'tecnoinfor'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission, Vision, Values Section -->
    <section class="mission-section py-5 bg-light" role="region" aria-labelledby="mission-heading">
        <div class="container my-5 px-4 px-lg-5">
            <h2 class="fs-3 fw-bold text-primary text-center mb-5 wow fadeIn" id="mission-heading"><?php _e('Mission, Vision, and Values', 'tecnoinfor'); ?></h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 bg-white text-center p-4 wow fadeInUp" data-wow-delay="0.1s">
                        <i class="bi bi-bullseye text-primary fs-2 mb-3"></i>
                        <h3 class="fs-4 fw-bold text-dark mb-3"><?php _e('Mission', 'tecnoinfor'); ?></h3>
                        <p class="text-muted"><?php _e('Boost our clients\' productivity through innovative and personalized technological solutions.', 'tecnoinfor'); ?></p>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 bg-white text-center p-4 wow fadeInUp" data-wow-delay="0.2s">
                        <i class="bi bi-eye text-primary fs-2 mb-3"></i>
                        <h3 class="fs-4 fw-bold text-dark mb-3"><?php _e('Vision', 'tecnoinfor'); ?></h3>
                        <p class="text-muted"><?php _e('Be a reference in developing innovative technological solutions, recognized for excellence and commitment to our clients\' success.', 'tecnoinfor'); ?></p>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 bg-white text-center p-4 wow fadeInUp" data-wow-delay="0.3s">
                        <i class="bi bi-heart text-primary fs-2 mb-3"></i>
                        <h3 class="fs-4 fw-bold text-dark mb-3"><?php _e('Values', 'tecnoinfor'); ?></h3>
                        <ul class="list-unstyled text-muted mb-0">
                            <li><?php _e('Commitment to the client', 'tecnoinfor'); ?></li>
                            <li><?php _e('Ethics and transparency', 'tecnoinfor'); ?></li>
                            <li><?php _e('Quality and excellence', 'tecnoinfor'); ?></li>
                            <li><?php _e('Innovation and creativity', 'tecnoinfor'); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section py-5 bg-primary-subtle text-center" role="region" aria-labelledby="cta-heading">
        <div class="container my-5 px-4 px-lg-5">
            <h2 class="fs-3 fw-bold text-primary mb-4 wow fadeIn" id="cta-heading"><?php _e('Ready to Transform Your Business?', 'tecnoinfor'); ?></h2>
            <p class="lead text-muted mb-4 wow fadeInUp"><?php _e('Contact us and discover how our solutions can boost your productivity!', 'tecnoinfor'); ?></p>
            <div class="mb-4">
                <a href="<?php echo esc_url(home_url('/contato')); ?>" class="btn btn-primary btn-lg shadow-sm fw-bold py-3 px-5 wow fadeInUp" data-wow-delay="0.2s"><?php _e('Request a Quote', 'tecnoinfor'); ?></a>
            </div>
            <div class="d-flex justify-content-center gap-3 flex-wrap wow fadeInUp" data-wow-delay="0.3s">
                <?php
                foreach (get_redes_sociais() as $rede => $dados) {
                    echo exibir_rede_social($rede, true, false);
                }
                ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>