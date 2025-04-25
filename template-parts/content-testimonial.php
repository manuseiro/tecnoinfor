<section class="testimonial bg-light py-5 py-xl-8">
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-7 col-xxl-6">
                <h3 class="display-6 fw-bold">
                    <?php _e('We help entrepreneurs simplify their businesses', 'tecnoinfor'); ?></h3>
                <p class="lead text-muted"><?php _e('See what they say about our solution!', 'tecnoinfor'); ?></p>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <?php
    $args = [
      'post_type'      => 'depoimentos',
      'posts_per_page' => 3,
      'post_status'    => 'publish',
    ];
    $depoimentos = new WP_Query($args);

    if ($depoimentos->have_posts()) : ?>
        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php
          $active = true;
          while ($depoimentos->have_posts()) : $depoimentos->the_post();
            $avaliacao = get_post_meta(get_the_ID(), '_avaliacao', true) ?: 5;
            $cliente   = get_post_meta(get_the_ID(), '_cliente', true) ?: 'Anônimo';
            $empresa   = get_post_meta(get_the_ID(), '_empresa', true);
            $cargo     = get_post_meta(get_the_ID(), '_cargo', true);
            $imagem    = get_the_post_thumbnail_url(get_the_ID(), 'medium') ?: get_template_directory_uri() . '/assets/images/testimonial-img-default.jpg';
            // Truncar o conteúdo
            $content   = wp_trim_words(get_the_content(), 30, '...');
          ?>
                <div class="carousel-item <?php echo $active ? 'active' : ''; ?>">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-8 col-lg-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center p-4 p-xl-5">
                                    <figure>
                                        <img class="img-fluid rounded-circle mb-4 border border-3 border-primary"
                                            loading="lazy" src="<?php echo esc_url($imagem); ?>"
                                            alt="<?php echo esc_attr($cliente); ?>"
                                            style="width: 100px; height: 100px; object-fit: cover;">
                                        <figcaption>
                                            <div class="rating mb-3"
                                                aria-label="Avaliação: <?php echo esc_attr($avaliacao); ?> de 5 estrelas">
                                                <?php
                            for ($i = 1; $i <= 5; $i++) {
                              $checked = $i <= $avaliacao ? 'text-warning' : 'text-muted';
                              echo '<i class="bi bi-star-fill ' . $checked . '" style="font-size: 1.2rem;"></i>';
                            }
                            ?>
                                            </div>
                                            <blockquote class="blockquote mb-4">
                                                <i class="bi bi-quote text-primary" style="font-size: 2rem;"></i>
                                                <p class="text-muted testimonial-text"
                                                    data-full-text="<?php echo esc_attr(get_the_content()); ?>">
                                                    <?php echo esc_html(wp_trim_words(get_the_content(), 30, '')); ?>
                                                    <?php if (str_word_count(get_the_content()) > 30) : ?>
                                                    <span class="read-more text-primary fw-bold" role="button"
                                                        tabindex="0"> <?php _e('Read more', 'tecnoinfor'); ?></span>
                                                    <?php endif; ?>
                                                </p>
                                            </blockquote>
                                            <h4 class="fw-bold mb-1"><?php echo esc_html($cliente); ?></h4>
                                            <p class="text-secondary fs-6 mb-0">
                                                <?php echo esc_html($cargo); ?><?php echo $empresa ? ', ' . esc_html($empresa) : ''; ?>
                                            </p>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            $active = false;
          endwhile;
          wp_reset_postdata();
          ?>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden"><?php _e('Previous', 'tecnoinfor'); ?></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden"><?php _e('Next', 'tecnoinfor'); ?></span>
            </button>

            <div class="carousel-indicators mb-3">
                <?php
          for ($i = 0; $i < $depoimentos->post_count; $i++) {
            echo '<button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="' . $i . '" ' . ($i === 0 ? 'class="active" aria-current="true"' : '') . ' aria-label="' . sprintf(__('Slide %d', 'tecnoinfor'), $i + 1) . '"></button>';
          }
          ?>
            </div>
        </div>
        <?php else : ?>
        <div class="text-center">
            <p class="text-muted"><?php _e('No testimonials found.', 'tecnoinfor'); ?></p>
        </div>
        <?php endif; ?>
    </div>
</section>