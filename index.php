<?php
get_header();
$site_url = get_bloginfo('url');
$template_url = get_bloginfo('template_url');
?>
<main>
    <!--HERO HOME-->
    <section class="hero-home d-flex align-items-center wow fadeIn py-5 bg-primary-subtle min-vh-75 position-relative">
        <div class="container">
            <div id="softwareCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                <div class="carousel-indicators software-indicators justify-content-start ms-0 ps-5 mb-4 d-none d-lg-flex">
                    <?php
                    $softwares = wp_cache_get('tecnoinfor_home_softwares', 'tecnoinfor');
                    if (false === $softwares) {
                        $softwares = new WP_Query(array(
                            'post_type' => 'software',
                            'posts_per_page' => -1,
                        ));
                        wp_cache_set('tecnoinfor_home_softwares', $softwares, 'tecnoinfor', 3600);
                    }
                    if ($softwares->have_posts()) {
                        for ($i = 0; $i < $softwares->post_count; $i++) {
                            echo '<button type="button" data-bs-target="#softwareCarousel" data-bs-slide-to="' . $i . '" ' . ($i === 0 ? 'class="active" aria-current="true"' : '') . ' aria-label="Slide ' . ($i + 1) . '"></button>';
                        }
                    }
                    ?>
                </div>
                <!-- Progress autoplay indicator -->
                <div class="software-progress-container ms-5 mb-4 d-none d-lg-block" style="width: 120px; height: 3px; background: rgba(0,0,0,0.1); border-radius: 2px; overflow: hidden;">
                    <div class="software-progress-bar bg-primary h-100" style="width: 0%; transition: width 5s linear;"></div>
                </div>

                <div class="carousel-inner">
                    <?php
                    $is_first = true;
                    if ($softwares->have_posts()) :
                        while ($softwares->have_posts()) : $softwares->the_post();
                            $download_link = get_post_meta(get_the_ID(), '_software_download_link', true);
                            $trial_period = get_post_meta(get_the_ID(), '_software_trial_period', true) ?: '15 dias';
                            $categories = get_the_terms(get_the_ID(), 'software_category');
                            $category_name = ($categories && !is_wp_error($categories)) ? $categories[0]->name : 'Sistema';
                    ?>
                            <div class="carousel-item <?php echo $is_first ? 'active' : ''; ?>">
                                <div class="row p-4 py-lg-5 align-items-center">
                                    <div class="col-lg-7 p-3 p-lg-5 pt-lg-3 text-center text-lg-start">
                                        <span
                                            class="badge text-bg-warning d-sm-inline-flex align-items-center gap-1 py-2 px-3 me-2 mb-2 mb-lg-0 rounded-5"><?php echo esc_html($category_name); ?></span>
                                        <h1 class="display-3 fw-bold lh-1 text-emphasis my-4 text-primary text-uppercase"
                                            title="<?php the_title(); ?>"><?php the_title(); ?></h1>
                                        <h2 class="display-6 fw-bold lh-1 text-emphasis my-4"
                                            title="<?php echo get_the_excerpt(); ?>"><?php echo get_the_excerpt(); ?></h2>
                                        
                                        <!-- Micro-social proof badge -->
                                        <div class="d-flex align-items-center gap-2 mb-4 justify-content-center justify-content-lg-start">
                                            <span class="text-warning fw-bold fs-5">★★★★★</span>
                                            <span class="text-muted small"><strong>+<?php echo esc_html(get_theme_mod('tecnoinfor_clients', 125)); ?></strong> <?php _e('clientes satisfeitos em todo o Brasil', 'tecnoinfor'); ?></span>
                                        </div>

                                        <p class="lead text-muted mb-4">
                                            <?php echo esc_html(wp_trim_words(get_the_content(), 15, '...')); ?></p>
                                        <div
                                            class="d-flex flex-column flex-md-row gap-3 justify-content-center justify-content-lg-start mb-4 mb-lg-3">
                                            <?php if ($download_link) : ?>
                                                <a href="<?php echo esc_url($download_link); ?>"
                                                    class="btn btn-success btn-lg shadow-sm py-2 px-4 fw-bold rounded-pill" target="_blank"
                                                    rel="noopener noreferrer">Teste Grátis Agora</a>
                                            <?php endif; ?>
                                            <a href="<?php the_permalink(); ?>"
                                                class="btn btn-outline-primary btn-lg shadow-sm py-2 px-4 rounded-pill">Conheça mais</a>
                                        </div>
                                        <p class="text-body-secondary mb-0">
                                            <?php if ($download_link) : ?>
                                                <small>
                                                    <?php printf(__('Download the trial version and enjoy it for %s days*', 'tecnoinfor'), esc_html($trial_period)); ?>
                                                </small>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="col-lg-4 offset-lg-1 p-0 overflow-hidden">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('large', array('class' => 'rounded-3 img-fluid shadow-lg custom-img', 'loading' => $is_first ? 'eager' : 'lazy')); ?>
                                        <?php else : ?>
                                            <img class="rounded-3 img-fluid shadow-lg custom-img"
                                                src="<?php echo esc_url($template_url); ?>/assets/images/hero-img.png"
                                                alt="<?php the_title(); ?>" loading="<?php echo $is_first ? 'eager' : 'lazy'; ?>">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                            $is_first = false;
                        endwhile;
                        wp_reset_postdata();
                    else : ?>
                        <div class="carousel-item active">
                            <div class="row p-4 py-lg-5 align-items-center">
                                <div class="col-lg-7 p-3 p-lg-5 pt-lg-3 text-center text-lg-start">
                                    <span class="badge text-bg-primary my-2">Nenhum Software</span>
                                    <h1 class="display-3 fw-bold lh-1 text-emphasis mb-3">Nenhum sistema disponível</h1>
                                    <p class="lead text-muted mb-4">Volte em breve para conhecer nossas soluções!</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#softwareCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#softwareCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>
    <!--ABOUT-->
    <section class="about bg-light fadeIn wow py-5">
        <div class="container my-5 px-4 px-lg-5">
            <div class="row g-4 py-5 align-items-center">
                <div class="col-12 col-lg-6 col-xl-7">
                    <div class="about-text">
                        <h3 class="display-6">Gestão de <span class="text-primary fw-bold">Planos e Contratos</span>
                        </h3>
                        <p class="text-secondary mt-3 fs-5">
                            Nossa missão é simplificar a gestão de planos assistenciais e funerários com uma plataforma
                            segura e eficaz. Desde 2009, dedicamo-nos a desenvolver uma solução que otimiza processos,
                            proporcionando tranquilidade e controle total aos nossos clientes.
                        </p>
                        <a href="<?php echo esc_url($site_url); ?>/sobre-nos"
                            class="btn btn-lg btn-primary mt-4 rounded-pill shadow-sm px-4">
                            <small class="fw-bold"><?php echo __('Meet Tecnoinfor', 'tecnoinfor'); ?></small>
                        </a>
                    </div>
                </div>
                <div class="col-12 col-lg-6 col-xl-5 d-flex justify-content-center flex-column ps-lg-5 border-start-desktop">
                    <div class="company-stats row row-cols-1 row-cols-md-3 g-4 text-center">
                        <div class="col">
                            <div class="stat-item p-3 rounded bg-white shadow-sm h-100">
                                <i class="bi bi-people-fill text-primary fs-2 mb-2 d-block"></i>
                                <h3 class="fw-bold text-primary mb-0">
                                    +<span class="countup" data-count="<?php echo esc_attr(get_theme_mod('tecnoinfor_clients', 125)); ?>">0</span>
                                </h3>
                                <p class="text-muted small mb-2"><?php _e('Satisfied Clients', 'tecnoinfor'); ?></p>
                                <div class="stat-progress bg-primary" style="height: 4px; border-radius: 2px; width: 0; transition: width 1.5s ease-out; margin: 0 auto;"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="stat-item p-3 rounded bg-white shadow-sm h-100">
                                <i class="bi bi-award-fill text-success fs-2 mb-2 d-block"></i>
                                <h3 class="fw-bold text-success mb-0">
                                    +<span class="countup" data-count="<?php echo esc_attr(get_theme_mod('tecnoinfor_years', 15)); ?>">0</span>
                                </h3>
                                <p class="text-muted small mb-2"><?php _e('years of Experience', 'tecnoinfor'); ?></p>
                                <div class="stat-progress bg-success" style="height: 4px; border-radius: 2px; width: 0; transition: width 1.5s ease-out; margin: 0 auto;"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="stat-item p-3 rounded bg-white shadow-sm h-100">
                                <i class="bi bi-file-earmark-text-fill text-warning fs-2 mb-2 d-block"></i>
                                <h3 class="fw-bold text-warning mb-0">
                                    +<span class="countup" data-count="<?php echo esc_attr(get_theme_mod('tecnoinfor_contracts', 21562)); ?>">0</span>
                                </h3>
                                <p class="text-muted small mb-2"><?php _e('Managed Contracts', 'tecnoinfor'); ?></p>
                                <div class="stat-progress bg-warning" style="height: 4px; border-radius: 2px; width: 0; transition: width 1.5s ease-out; margin: 0 auto;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- BENEFICIOS -->
    <section class="beneficios wow fadeIn py-5" id="featured-3">
        <div class="container my-5 px-4 px-lg-5">
            <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
                <?php
                $benefits = [
                    [
                        "icon" => "bi-clock", 
                        "title" => get_theme_mod('benefit_title_1', 'Eficiência'), 
                        "desc" => get_theme_mod('benefit_desc_1', 'O Sisplan ajuda a organizar e automatizar as principais rotinas de uma funerária, reduzindo o tempo gasto com tarefas administrativas e melhorando a eficiência do negócio.'),
                        "color" => "#2687e9"
                    ],
                    [
                        "icon" => "bi-lock", 
                        "title" => get_theme_mod('benefit_title_2', 'Confiança'), 
                        "desc" => get_theme_mod('benefit_desc_2', 'Utilizamos um poderoso banco de dados SQL Open Source, garantindo a segurança e integridade dos dados.'),
                        "color" => "#26b547"
                    ],
                    [
                        "icon" => "bi-bar-chart-line", 
                        "title" => get_theme_mod('benefit_title_3', 'Controle'), 
                        "desc" => get_theme_mod('benefit_desc_3', 'O Sisplan fornece relatórios detalhados e em tempo real sobre contratos e aspectos importantes do negócio.'),
                        "color" => "#FFC107"
                    ]
                ];

                foreach ($benefits as $benefit) {
                    echo "
                    <div class='col'>
                        <div class='feature p-4 bg-white rounded shadow-sm h-100 transition-hover d-flex flex-column' style='border-top: 4px solid {$benefit['color']};'>
                            <div class='feature-icon d-inline-flex align-items-center justify-content-center fs-2 p-2 px-3 mb-3 rounded align-self-start' style='background-color: {$benefit['color']}1a; color: {$benefit['color']};'>
                                <i class='bi {$benefit['icon']}'></i>
                            </div>
                            <h3 class='fs-3 text-body-emphasis fw-bold mb-3'>{$benefit['title']}</h3>
                            <p class='text-muted mb-4 fs-6' style='line-height: 1.6;'>{$benefit['desc']}</p>
                            <a href='{$site_url}/sobre-nos' class='text-decoration-none fw-bold mt-auto d-inline-flex align-items-center' style='color: {$benefit['color']};'>
                                Saiba mais <i class='bi bi-arrow-right ms-2 transition-arrow'></i>
                            </a>
                        </div>
                    </div>";
                }
                ?>
            </div>
        </div>
    </section>

    <!-- TERTIMONIAL -->
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
            $depoimentos = wp_cache_get('tecnoinfor_home_depoimentos', 'tecnoinfor');
            if (false === $depoimentos) {
                $args = [
                    'post_type'      => 'depoimentos',
                    'posts_per_page' => get_theme_mod('tecnoinfor_testimonials_count', 3),
                    'post_status'    => 'publish',
                ];
                $depoimentos = new WP_Query($args);
                wp_cache_set('tecnoinfor_home_depoimentos', $depoimentos, 'tecnoinfor', 3600);
            }

            if ($depoimentos->have_posts()) : ?>
                <!-- Mobile Carousel View -->
                <div id="testimonialCarousel" class="carousel slide d-lg-none" data-bs-ride="carousel">
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

                <!-- Desktop Grid View -->
                <div class="d-none d-lg-flex row row-cols-1 row-cols-md-3 g-4 justify-content-center">
                    <?php
                    $depoimentos->rewind_posts();
                    while ($depoimentos->have_posts()) : $depoimentos->the_post();
                        $avaliacao = get_post_meta(get_the_ID(), '_avaliacao', true) ?: 5;
                        $cliente   = get_post_meta(get_the_ID(), '_cliente', true) ?: 'Anônimo';
                        $empresa   = get_post_meta(get_the_ID(), '_empresa', true);
                        $cargo     = get_post_meta(get_the_ID(), '_cargo', true);
                        $imagem    = get_the_post_thumbnail_url(get_the_ID(), 'medium') ?: get_template_directory_uri() . '/assets/images/testimonial-img-default.jpg';
                        $border_top_color = $avaliacao == 5 ? '#FFC107' : '#6c757d'; // Gold for 5 stars, grey otherwise
                    ?>
                        <div class="col">
                            <div class="card border-0 shadow-sm h-100 transition-hover" style="border-top: 4px solid <?php echo $border_top_color; ?> !important;">
                                <div class="card-body text-center p-4">
                                    <img class="img-fluid rounded-circle mb-3 border border-3 border-primary"
                                        loading="lazy" src="<?php echo esc_url($imagem); ?>"
                                        alt="<?php echo esc_attr($cliente); ?>"
                                        style="width: 80px; height: 80px; object-fit: cover;">
                                    <div class="rating mb-3" aria-label="Avaliação: <?php echo esc_attr($avaliacao); ?> de 5 estrelas">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            $checked = $i <= $avaliacao ? 'text-warning' : 'text-muted';
                                            echo '<i class="bi bi-star-fill ' . $checked . '" style="font-size: 1rem;"></i>';
                                        }
                                        ?>
                                    </div>
                                    <blockquote class="blockquote mb-3">
                                        <i class="bi bi-quote text-primary" style="font-size: 1.5rem;"></i>
                                        <p class="text-muted small testimonial-text" data-full-text="<?php echo esc_attr(get_the_content()); ?>">
                                            <?php echo esc_html(wp_trim_words(get_the_content(), 25, '')); ?>
                                            <?php if (str_word_count(get_the_content()) > 25) : ?>
                                                <span class="read-more text-primary fw-bold" role="button" tabindex="0"> <?php _e('Read more', 'tecnoinfor'); ?></span>
                                            <?php endif; ?>
                                        </p>
                                    </blockquote>
                                    <h5 class="fw-bold mb-1 fs-6"><?php echo esc_html($cliente); ?></h5>
                                    <p class="text-secondary small mb-0">
                                        <?php echo esc_html($cargo); ?><?php echo $empresa ? ', ' . esc_html($empresa) : ''; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

            <?php else : ?>
                <div class="text-center">
                    <p class="text-muted"><?php _e('No testimonials found.', 'tecnoinfor'); ?></p>
                </div>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </section>

    <!-- FAQS -->
    <section class="faq wow fadeIn py-5">
        <div class="container my-5 px-4 px-lg-5">
            <div class="text-center mb-5">
                <h3 class="display-6 fw-bold text-primary">Perguntas frequentes</h3>
                <p class="lead text-light-emphasis">Consulte a lista das perguntas mais frequentes ou entre em contato conosco.</p>
            </div>
            
            <?php
            $faq_query = wp_cache_get('tecnoinfor_home_faqs', 'tecnoinfor');
            if (false === $faq_query) {
                $faq_query = new WP_Query([
                    'post_type' => 'faq',
                    'tax_query' => [[
                        'taxonomy' => 'assunto',
                        'field'    => 'slug',
                        'terms'    => 'perguntas-gerais',
                    ]],
                ]);
                wp_cache_set('tecnoinfor_home_faqs', $faq_query, 'tecnoinfor', 3600);
            }

            if ($faq_query->have_posts()) :
                $faqs = $faq_query->posts;
                $count = count($faqs);
                $left_faqs = array_slice($faqs, 0, ceil($count / 2));
                $right_faqs = array_slice($faqs, ceil($count / 2));
            ?>
                <div class="row g-4">
                    <!-- Left Column Accordion -->
                    <div class="col-12 col-lg-6">
                        <div class="accordion accordion-flush" id="accordionFaqsLeft">
                            <?php foreach ($left_faqs as $index => $faq) : 
                                $post_id = $faq->ID;
                                $title = get_the_title($post_id);
                                $content = apply_filters('the_content', $faq->post_content);
                                $show_class = ($index === 0) ? 'show' : '';
                                $button_collapsed = ($index === 0) ? '' : 'collapsed';
                                $aria_expanded = ($index === 0) ? 'true' : 'false';
                            ?>
                                <div class="accordion-item rounded shadow-sm border-0 mb-3 bg-white">
                                    <h2 class="accordion-header" id="heading-left-<?php echo $post_id; ?>">
                                        <button class="accordion-button <?php echo $button_collapsed; ?> fw-bold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-left-<?php echo $post_id; ?>" aria-expanded="<?php echo $aria_expanded; ?>" aria-controls="collapse-left-<?php echo $post_id; ?>">
                                            <?php echo $title; ?>
                                        </button>
                                    </h2>
                                    <div id="collapse-left-<?php echo $post_id; ?>" class="accordion-collapse collapse <?php echo $show_class; ?>" aria-labelledby="heading-left-<?php echo $post_id; ?>" data-bs-parent="#accordionFaqsLeft">
                                        <div class="accordion-body text-muted pb-4"><?php echo $content; ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Right Column Accordion -->
                    <div class="col-12 col-lg-6">
                        <div class="accordion accordion-flush" id="accordionFaqsRight">
                            <?php foreach ($right_faqs as $faq) : 
                                $post_id = $faq->ID;
                                $title = get_the_title($post_id);
                                $content = apply_filters('the_content', $faq->post_content);
                            ?>
                                <div class="accordion-item rounded shadow-sm border-0 mb-3 bg-white">
                                    <h2 class="accordion-header" id="heading-right-<?php echo $post_id; ?>">
                                        <button class="accordion-button collapsed fw-bold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-right-<?php echo $post_id; ?>" aria-expanded="false" aria-controls="collapse-right-<?php echo $post_id; ?>">
                                            <?php echo $title; ?>
                                        </button>
                                    </h2>
                                    <div id="collapse-right-<?php echo $post_id; ?>" class="accordion-collapse collapse" aria-labelledby="heading-right-<?php echo $post_id; ?>" data-bs-parent="#accordionFaqsRight">
                                        <div class="accordion-body text-muted pb-4"><?php echo $content; ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="text-center">
                    <p class="text-muted">Nenhuma FAQ disponível para este assunto.</p>
                </div>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>

            <div class="text-center mt-5">
                <a href="<?php echo esc_url(home_url('/faq')); ?>" class="btn btn-outline-primary rounded-pill px-4 py-2 shadow-sm">
                    <?php _e('Ver todas as perguntas', 'tecnoinfor'); ?> <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta text-center py-5 bg-gradient-cta position-relative overflow-hidden">
        <div class="container my-5 px-4 px-lg-5 position-relative z-index-1">
            <h3 class="display-5 fw-bold text-white mb-3">Pronto para simplificar sua gestão?</h3>
            <p class="lead text-white-50 mb-4"><?php printf(__('+%d clientes já usam. Comece agora gratuitamente, sem cartão de crédito.', 'tecnoinfor'), esc_html(get_theme_mod('tecnoinfor_clients', 125))); ?></p>
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <a href="<?php echo esc_url(home_url('/software')); ?>" class="btn btn-success btn-lg rounded-pill py-3 px-5 fw-bold shadow-sm">
                    <?php _e('Experimente Grátis', 'tecnoinfor'); ?> <i class="bi bi-lightning-charge ms-2"></i>
                </a>
                <a href="https://wa.me/<?php echo esc_attr(preg_replace('/[^0-9]/', '', get_informacao_empresa('whatsapp'))); ?>" class="btn btn-outline-light btn-lg rounded-pill py-3 px-5 shadow-sm" target="_blank" rel="noopener noreferrer">
                    <?php _e('Falar com vendas', 'tecnoinfor'); ?> <i class="bi bi-whatsapp ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- NOTICIAS -->
    <section class="lastnews py-5">
        <div class="container my-5 px-4 px-lg-5">
            <h2 class="text-center mb-5 fw-bold text-primary"><?php echo __('Latest News', 'tecnoinfor'); ?></h2>
            <div class="row g-4">
                <?php
                $noticias = wp_cache_get('tecnoinfor_home_noticias', 'tecnoinfor');
                if (false === $noticias) {
                    $noticias = new WP_Query(['post_type' => 'post', 'posts_per_page' => 5]);
                    wp_cache_set('tecnoinfor_home_noticias', $noticias, 'tecnoinfor', 3600);
                }
                if ($noticias->have_posts()) :
                    while ($noticias->have_posts()) : $noticias->the_post();
                        $current = $noticias->current_post;
                        $is_featured = ($current === 0);
                        $word_count = str_word_count(strip_tags(get_the_content()));
                        $read_time = max(1, ceil($word_count / 200));
                        $categoria = get_the_category_list(', ');

                        if ($is_featured) :
                ?>
                            <!-- Horizontal Featured Card -->
                            <div class="col-12 col-lg-8 wow fadeInUp" data-wow-delay="0.1s">
                                <div class="card h-100 border-0 shadow-sm flex-lg-row overflow-hidden transition-hover">
                                    <div class="col-lg-6 position-relative" style="min-height: 250px;">
                                        <?php 
                                        if (has_post_thumbnail()) {
                                            the_post_thumbnail('large', ['class' => 'w-100 h-100 object-fit-cover absolute-fill']);
                                        } else {
                                            echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/post_thumb_default.jpg') . '" class="w-100 h-100 object-fit-cover absolute-fill" alt="Default Image">';
                                        }
                                        ?>
                                        <div class="position-absolute top-0 end-0 bg-primary text-white py-1 px-3 m-2 rounded-pill shadow-sm small fw-bold">
                                            <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('d M Y'); ?></time>
                                        </div>
                                        <div class="position-absolute top-0 start-0 bg-dark text-white py-1 px-3 m-2 rounded-pill opacity-75 small">
                                            <small class="text-uppercase"><?php echo strip_tags($categoria); ?></small>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 p-4 d-flex flex-column justify-content-between">
                                        <div>
                                            <span class="badge bg-warning text-dark mb-3 rounded-pill px-3"><?php _e('Destaque', 'tecnoinfor'); ?></span>
                                            <h4 class="card-title fw-bold text-dark mb-3"><?php the_title(); ?></h4>
                                            <p class="card-text text-muted mb-4 fs-6">
                                                <?php echo wp_trim_words(get_the_excerpt(), 25, '...'); ?>
                                            </p>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-auto">
                                            <span class="text-muted small"><i class="bi bi-clock me-1"></i> <?php printf(__('%d min de leitura', 'tecnoinfor'), $read_time); ?></span>
                                            <a href="<?php the_permalink(); ?>" class="btn btn-primary rounded-pill px-4"><?php echo __('read more', 'tecnoinfor'); ?> <i class="bi bi-arrow-right ms-1"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else : ?>
                            <!-- Vertical Standard Card -->
                            <div class="col-12 col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.<?php echo esc_attr($current + 1); ?>s">
                                <div class="card h-100 border-0 shadow-sm transition-hover overflow-hidden">
                                    <div class="position-relative" style="height: 200px;">
                                        <?php 
                                        if (has_post_thumbnail()) {
                                            the_post_thumbnail('post_thumb', ['class' => 'w-100 h-100 object-fit-cover']);
                                        } else {
                                            echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/post_thumb_default.jpg') . '" class="w-100 h-100 object-fit-cover" alt="Default Image">';
                                        }
                                        ?>
                                        <div class="position-absolute top-0 end-0 bg-primary text-white py-1 px-3 m-2 rounded-pill shadow-sm small fw-bold">
                                            <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('d M Y'); ?></time>
                                        </div>
                                        <div class="position-absolute top-0 start-0 bg-dark text-white py-1 px-3 m-2 rounded-pill opacity-75 small">
                                            <small class="text-uppercase"><?php echo strip_tags($categoria); ?></small>
                                        </div>
                                    </div>
                                    <div class="card-body d-flex flex-column justify-content-between p-4">
                                        <div>
                                            <h5 class="card-title fw-bold text-dark mb-3"><?php the_title(); ?></h5>
                                            <p class="card-text text-muted mb-4 small">
                                                <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?>
                                            </p>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-auto">
                                            <span class="text-muted small"><i class="bi bi-clock me-1"></i> <?php printf(__('%d min', 'tecnoinfor'), $read_time); ?></span>
                                            <a href="<?php the_permalink(); ?>" class="btn btn-primary rounded-pill px-3 py-1 btn-sm"><?php echo __('read more', 'tecnoinfor'); ?> <i class="bi bi-arrow-right ms-1"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php 
                        endif;
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p class="text-center">Nenhuma notícia encontrada.</p>';
                endif;
                ?>
            </div>

            <div class="container text-center mt-5">
                <a href="<?php echo $site_url; ?>/blog" class="btn btn-outline-primary rounded-pill px-4 py-2"
                    title="<?php echo __('View All Posts', 'tecnoinfor'); ?>"><?php echo __('Ver todas as postagens', 'tecnoinfor'); ?></a>
            </div>
        </div>
    </section>

    <!-- SOCIAL MIDIAS -->
    <section class="comunidade wow fadeIn py-5">
        <div class="container my-5 px-4 px-lg-5">
            <h3 class="display-6 fw-bold text-center mb-4 py-5">
                <?php echo __('Do you already follow our social networks?', 'tecnoinfor'); ?>
            </h3>
            <div class="row g-4 row-cols-3 row-cols-lg-5 justify-content-center">
                <?php
                $redes_sociais = get_redes_sociais();
                foreach ($redes_sociais as $rede => $dados) {
                    $info = get_rede_social($rede);
                    if (!empty($info['link'])) {
                        echo "<a href='{$info['link']}' class='feature p-4 text-center text-decoration-none' target='_blank' rel='noopener noreferrer' aria-label='{$info['nome']}'>
                            <div class='feature-icon d-inline-flex align-items-center justify-content-center bg-primary-subtle fs-2 p-2 px-3 mb-3 rounded'>
                                <i class='{$info['icone']} text-primary'></i>
                            </div>
                          </a>";
                    }
                }
                ?>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>