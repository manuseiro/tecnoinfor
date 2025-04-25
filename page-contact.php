<?php

/**
 * Template Name: Contact Page
 * Description: Página de contato para o tema Tecnoinfor
 *
 * @package Tecnoinfor
 */
get_header();

$site_url = get_bloginfo('url');
$template_url = get_bloginfo('template_url');
?>

<main class="main-content" id="main-content">
    <?php
    // Inclui o conteúdo do header puxado do arquivo template-parts/content-header.php
    get_template_part('template-parts/content', 'header');
    ?>

    <!-- Contact Section -->
    <section class="contact py-5 bg-light" role="region" aria-labelledby="contact-heading">
        <div class="container my-5 px-4 px-lg-5">
            <h2 class="visually-hidden" id="contact-heading"><?php _e('Contact Information and Form', 'tecnoinfor'); ?></h2>
            <div class="row g-5">
                <!-- Contact Form -->
                <div class="col-12 col-lg-7">
                    <div class="card shadow-sm border-0 p-4 bg-white">
                        <h3 class="fs-3 fw-bold text-primary mb-4"><?php _e('Send Us a Message', 'tecnoinfor'); ?></h3>
                        <?php
                        // Formulário Contact Form 7
                        echo do_shortcode('[contact-form-7 id="2c6c7f4" title="Formulário de contato 1"]');
                        ?>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="col-12 col-lg-5">
                    <div class="card shadow-sm border-0 p-4 bg-white">
                        <h3 class="fs-3 fw-bold text-primary mb-4"><?php _e('Contact Details', 'tecnoinfor'); ?></h3>
                        <ul class="list-unstyled">
                            <li class="mb-3 d-flex align-items-start">
                                <i class="bi bi-geo-alt text-primary fs-4 me-3"></i>
                                <div>
                                    <strong><?php _e('Address', 'tecnoinfor'); ?>:</strong><br>
                                    <?php echo esc_html(get_informacao_empresa('endereco')); ?>
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="bi bi-telephone text-primary fs-4 me-3"></i>
                                <div>
                                    <strong><?php _e('Phone', 'tecnoinfor'); ?>:</strong><br>
                                    <a href="tel:<?php echo esc_attr(get_informacao_empresa('telefone')); ?>" class="text-decoration-none text-dark">
                                        <?php echo esc_html(get_informacao_empresa('telefone')); ?>
                                    </a>
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="bi bi-whatsapp text-primary fs-4 me-3"></i>
                                <div>
                                    <strong><?php _e('WhatsApp', 'tecnoinfor'); ?>:</strong><br>
                                    <?php
                                    $whatsapp = get_informacao_empresa('whatsapp');
                                    $whatsapp_clean = preg_replace('/[^0-9]/', '', $whatsapp);
                                    ?>
                                    <a href="https://wa.me/<?php echo esc_attr($whatsapp_clean); ?>" class="text-decoration-none text-dark" target="_blank" rel="noopener noreferrer">
                                        <?php echo esc_html($whatsapp); ?>
                                    </a>
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="bi bi-envelope text-primary fs-4 me-3"></i>
                                <div>
                                    <strong><?php _e('Email', 'tecnoinfor'); ?>:</strong><br>
                                    <a href="mailto:<?php echo esc_attr(get_option('admin_email', 'contato@tecnoinfor.com')); ?>" class="text-decoration-none text-dark">
                                        <?php echo esc_html(get_option('admin_email', 'contato@tecnoinfor.com')); ?>
                                    </a>
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="bi bi-clock text-primary fs-4 me-3"></i>
                                <div>
                                    <strong><?php _e('Opening Hours', 'tecnoinfor'); ?>:</strong><br>
                                    <?php echo esc_html(get_informacao_empresa('horarios')); ?>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Social Media -->
                    <div class="card shadow-sm border-0 p-4 mt-4 bg-white">
                        <h3 class="fs-3 fw-bold text-primary mb-4"><?php _e('Follow Us', 'tecnoinfor'); ?></h3>
                        <div class="d-flex gap-3 justify-content-start flex-wrap">
                            <?php
                            foreach (get_redes_sociais() as $rede => $dados) {
                                echo exibir_rede_social($rede, true, false);
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map (Opcional) -->
    <section class="map py-5 bg-light" role="region" aria-labelledby="map-heading">
        <div class="container my-5 px-4 px-lg-5">
            <h2 class="visually-hidden" id="map-heading"><?php _e('Our Location', 'tecnoinfor'); ?></h2>
            <!-- Substitua pelo iframe do Google Maps ou outro serviço -->
            <!--
            <div class="ratio ratio-16x9 shadow-lg rounded">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3657.123456789!2d-46.654321!3d-23.565432!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjPCsDU2JzU1LjYiUyA0NsKwMzknMTUuNSJX!5e0!3m2!1spt-BR!2sbr!4v1631234567890!5m2!1spt-BR!2sbr" allowfullscreen="" loading="lazy" aria-label="<?php _e('Map showing our location', 'tecnoinfor'); ?>"></iframe>
            </div>
            -->
        </div>
    </section>
</main>

<?php get_footer(); ?>