<?php
get_header();
$site_url = get_bloginfo('url');
$template_url = get_bloginfo('template_url');
?>
<main>
    <!--HERO HOME-->
    <section class="hero-home d-flex align-items-center wow fadeIn py-5 bg-primary-subtle min-vh-75">
        <div class="container">
            <div id="softwareCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $softwares = wp_cache_get('tecnoinfor_home_softwares', 'tecnoinfor');
                    if (false === $softwares) {
                        $softwares = new WP_Query(array(
                            'post_type' => 'software',
                            'posts_per_page' => -1,
                        ));
                        wp_cache_set('tecnoinfor_home_softwares', $softwares, 'tecnoinfor', 3600);
                    }
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
                                        <p class="lead text-muted mb-4">
                                            <?php echo esc_html(wp_trim_words(get_the_content(), 15, '...')); ?></p>
                                        <div
                                            class="d-flex flex-column flex-md-row gap-3 justify-content-center justify-content-lg-start mb-4 mb-lg-3">
                                            <?php if ($download_link) : ?>
                                                <a href="<?php echo esc_url($download_link); ?>"
                                                    class="btn btn-success btn-lg shadow-sm py-2 px-4 fw-bold" target="_blank"
                                                    rel="noopener noreferrer">Teste Grátis Agora</a>
                                            <?php endif; ?>
                                            <a href="<?php the_permalink(); ?>"
                                                class="btn btn-outline-primary btn-lg shadow-sm py-2 px-4">Conheça mais</a>
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
            <div class="row g-4 py-5">
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
                            class="btn btn-lg btn-primary mt-4 rounded-1">
                            <small class="fw-bold"><?php echo __('Meet Tecnoinfor', 'tecnoinfor'); ?></small>
                        </a>
                    </div>
                </div>
                <div class="col-12 col-lg-6 col-xl-5 d-flex justify-content-center flex-column">
                    <div class="company-stats row row-cols-1 row-cols-md-2 g-4">
                        <div class="col col-lg-6">
                            <div class="stat-item text-center">
                                <h3 class="fw-bold text-primary mb-0">
                                    +<span class="countup" data-count="<?php echo esc_attr(get_theme_mod('tecnoinfor_clients', 125)); ?>">0</span>
                                </h3>
                                <p class="text-muted fs-6"><?php _e('Satisfied Clients', 'tecnoinfor'); ?></p>
                            </div>
                        </div>
                        <div class="col col-lg-6">
                            <div class="stat-item text-center">
                                <h3 class="fw-bold text-primary mb-0">
                                    +<span class="countup" data-count="<?php echo esc_attr(get_theme_mod('tecnoinfor_years', 15)); ?>">0</span>
                                    <?php _e('years', 'tecnoinfor'); ?>
                                </h3>
                                <p class="text-muted fs-6"><?php _e('of Experience', 'tecnoinfor'); ?></p>
                            </div>
                        </div>
                        <div class="col col-lg-6">
                            <div class="stat-item text-center">
                                <h3 class="fw-bold text-primary mb-0">
                                    +<span class="countup" data-count="<?php echo esc_attr(get_theme_mod('tecnoinfor_contracts', 21562)); ?>">0</span>
                                </h3>
                                <p class="text-muted fs-6"><?php _e('Managed Contracts', 'tecnoinfor'); ?></p>
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
                    ["icon" => "bi-clock", "title" => "Eficiência", "desc" => "O Sisplan ajuda a organizar e automatizar as principais rotinas de uma funerária, reduzindo o tempo gasto com tarefas administrativas e melhorando a eficiência do negócio."],
                    ["icon" => "bi-lock", "title" => "Confiança", "desc" => "Utilizamos um poderoso banco de dados SQL Open Source, garantindo a segurança e integridade dos dados."],
                    ["icon" => "bi-bar-chart-line", "title" => "Controle", "desc" => "O Sisplan fornece relatórios detalhados e em tempo real sobre contratos e aspectos importantes do negócio."]
                ];

                foreach ($benefits as $benefit) {
                    echo "<div class='feature col p-4'>
                  <div class='feature-icon d-inline-flex align-items-center justify-content-center bg-primary-subtle fs-2 p-2 px-3 mb-3 rounded'>
                    <i class='bi {$benefit['icon']} text-primary'></i>
                  </div>
                  <h3 class='fs-2 text-body-emphasis fw-bold'>{$benefit['title']}</h3>
                  <p class='text-light-emphasis'>{$benefit['desc']}</p>
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

    <!-- LICENCA -->
    <?php
    // Inclui o conteúdo do header puxado do arquivo template-parts/content-licencaconfig.php
    //get_template_part('template-parts/content', 'licencaconfig');
    ?>



    <!-- FAQS -->
    <section class="faq wow fadeIn py-5">
        <div class="container my-5 px-4 px-lg-5">
            <div class="text-center mb-5">
                <h3 class="display-6 fw-bold text-primary">Perguntas frequentes</h3>
                <p class="lead text-light-emphasis">Consulte a lista das perguntas mais frequentes ou entre em contato conosco.</p>
            </div>
            <div class="col-lg-8 mx-auto">
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

                if ($faq_query->have_posts()) {
                    echo '<div class="accordion accordion-flush" id="accordionFaqs">';
                    while ($faq_query->have_posts()) {
                        $faq_query->the_post();
                        $post_id = get_the_ID();
                        echo "<div class='accordion-item'>
                      <h2 class='accordion-header' id='heading-$post_id'>
                        <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapse-$post_id' aria-expanded='false' aria-controls='collapse-$post_id'>" .
                            get_the_title() . "</button>
                      </h2>
                      <div id='collapse-$post_id' class='accordion-collapse collapse' aria-labelledby='heading-$post_id' data-bs-parent='#accordionFaqs'>
                        <div class='accordion-body'>" . get_the_content() . "</div>
                      </div>
                    </div>";
                    }
                    echo '</div>';
                } else {
                    echo '<p>Nenhuma FAQ disponível para este assunto.</p>';
                }
                wp_reset_postdata();
                ?>

            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta text-center py-5 bg-primary-subtle">
        <div class="container my-5 px-4 px-lg-5">
            <h3 class="display-6 fw-bold">Pronto para simplificar sua gestão?</h3>
            <p class="lead py-3">Experimente nossos sistemas e veja como eles podem ajudar no seu dia a dia.</p>
            <a href="<?php echo $site_url; ?>/software" class="btn btn-primary btn-lg me-md-2 py-3 px-5">Experimente
                Agora</a>
        </div>
    </section>

    <!-- NOTICIAS -->
    <section class="lastnews py-5">
        <div class="container my-5 px-4 px-lg-5">
            <h2 class="text-center mb-5 fw-bold text-primary"><?php echo __('Latest News', 'tecnoinfor'); ?></h2>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php
                $noticias = wp_cache_get('tecnoinfor_home_noticias', 'tecnoinfor');
                if (false === $noticias) {
                    $noticias = new WP_Query(['post_type' => 'post', 'posts_per_page' => 6]);
                    wp_cache_set('tecnoinfor_home_noticias', $noticias, 'tecnoinfor', 3600);
                }
                if ($noticias->have_posts()) :
                    while ($noticias->have_posts()) : $noticias->the_post();
                        // Verificar se o post tem thumbnail, caso contrário usa imagem padrão
                        if (has_post_thumbnail()) {
                            $imagem = get_the_post_thumbnail(get_the_ID(), 'post_thumb', ['class' => 'card-img-top img-fluid']);
                        } else {
                            // Caso não tenha, usa a imagem padrão
                            $imagem = '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/post_thumb_default.jpg') . '" class="card-img-top img-fluid" alt="Default Image">';
                        }
                        $data = get_the_date();
                        $categoria = get_the_category_list(', ');
                ?>
                        <div class="col wow fadeInUp" data-wow-delay="0.<?php echo esc_attr($noticias->current_post + 1); ?>s">
                            <div class="card h-100 border-0">
                                <div class="rounded-top position-relative overflow-hidden">
                                    <?php echo $imagem; // Exibe a imagem do post ou a imagem padrão 
                                    ?>
                                    <!-- Badge de Categoria e Data -->
                                    <div class="position-absolute top-0 start-0 bg-dark text-white p-2 m-2 rounded">
                                        <small class="text-uppercase"><?php echo $categoria; ?></small>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title fw-bold text-dark"><?php the_title(); ?></h5>
                                    <p class="card-text mb-4 text-muted">
                                        <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?></p>
                                    <a href="<?php the_permalink(); ?>"
                                        class="btn btn-primary mt-auto align-self-end"><?php echo __('read more', 'tecnoinfor'); ?>
                                        <i class="bi bi-arrow-right ms-2"></i></a>
                                </div>
                            </div>
                        </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p class="text-center">Nenhuma notícia encontrada.</p>';
                endif;
                ?>
            </div>

            <div class="container text-center mt-4">
                <a href="<?php echo $site_url; ?>/blog" class="btn btn-outline-primary"
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