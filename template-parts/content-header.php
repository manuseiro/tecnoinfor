<?php
    // Verifica se a página tem uma imagem destacada
    $banner_image = has_post_thumbnail() 
        ? get_the_post_thumbnail_url(get_the_ID(), 'full') 
        : get_template_directory_uri() . '/assets/images/default-banner.jpg';
    ?>

    <section class="banner-breadcrumb bg-delp"
        style="background: linear-gradient(to bottom, rgba(11, 94, 215, 0.3) 0%, rgba(11, 94, 215, 0.4) 50%, rgba(11, 94, 215, 0.8) 100%), url('<?php echo esc_url($banner_image); ?>'); background-size: cover; background-position: center;">
        <div class="container">
            <div class="row p-5 pb-0 pe-lg-0 py-lg-5 align-items-center" style="position: relative; z-index: 2;">
                <!-- Breadcrumb -->
                <?php echo tecnoinfor_get_breadcrumb(); ?>

                <!-- Título e Descrição -->
                <div class="d-flex flex-column align-items-start text-left">
                    <h1 class="display-4 text-white fw-bolder">
                        <?php echo esc_html(get_the_title()); ?>
                    </h1>
                    <div class="text-white col-lg-8 page-summary">
                        <?php if (has_excerpt()) : ?>
                        <p><?php echo wp_kses_post(get_the_excerpt()); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>