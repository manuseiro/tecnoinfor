<?php

/**
 * Template Name: Contact Page
 * Description: Página de contato para o tema Tecnoinfor
 *
 * @package Tecnoinfor
 */
get_header();

?>

<main class="main-content" id="main-content">
    <?php
    // Inclui o conteúdo do header puxado do arquivo template-parts/content-header.php
    get_template_part('template-parts/content', 'header');
    ?>

    <!-- Contact Section -->
    <section class="contact py-5 bg-light" role="region" aria-labelledby="contact-heading">
        <div class="container my-5 px-4 px-lg-5">
            <h2 class="visually-hidden" id="contact-heading"><?php esc_html_e('Contact Information and Form', 'tecnoinfor'); ?>
            </h2>
            <div class="row g-5">
                <!-- Contact Form -->
                <div class="col-12 col-lg-7">
                    <div class="card shadow-sm border-0 p-4 bg-white">
                        <h3 class="fs-3 fw-bold text-primary mb-4"><?php esc_html_e('Send Us a Message', 'tecnoinfor'); ?></h3>
                        <?php
                        $cf7_id = get_theme_mod('tecnoinfor_contact_form_id', '');
                        if ($cf7_id && function_exists('wpcf7')) {
                            echo do_shortcode(sprintf('[contact-form-7 id="%s"]', esc_attr($cf7_id)));
                        } else {
                            comment_form(['title_reply' => esc_html__('Envie uma mensagem', 'tecnoinfor')]);
                        }
                        ?>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="col-12 col-lg-5">
                    <div class="card shadow-sm border-0 p-4 bg-white">
                        <h3 class="fs-3 fw-bold text-primary mb-4"><?php esc_html_e('Contact Details', 'tecnoinfor'); ?></h3>
                        <ul class="list-unstyled">
                            <li class="mb-3 d-flex align-items-start">
                                <i class="bi bi-geo-alt text-primary fs-4 me-3"></i>
                                <div>
                                    <strong><?php esc_html_e('Address', 'tecnoinfor'); ?>:</strong><br>
                                    <?php echo esc_html(get_informacao_empresa('endereco')); ?>
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="bi bi-telephone text-primary fs-4 me-3"></i>
                                <div>
                                    <strong><?php esc_html_e('Phone', 'tecnoinfor'); ?>:</strong><br>
                                    <a href="tel:<?php echo esc_attr(get_informacao_empresa('telefone')); ?>"
                                        class="text-decoration-none text-dark">
                                        <?php echo esc_html(get_informacao_empresa('telefone')); ?>
                                    </a>
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="bi bi-whatsapp text-primary fs-4 me-3"></i>
                                <div>
                                    <strong><?php esc_html_e('WhatsApp', 'tecnoinfor'); ?>:</strong><br>
                                    <?php
                                    $whatsapp = get_informacao_empresa('whatsapp');
                                    $whatsapp_clean = preg_replace('/[^0-9]/', '', $whatsapp);
                                    ?>
                                    <a href="https://wa.me/<?php echo esc_attr($whatsapp_clean); ?>"
                                        class="text-decoration-none text-dark" target="_blank"
                                        rel="noopener noreferrer">
                                        <?php echo esc_html($whatsapp); ?>
                                    </a>
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="bi bi-envelope text-primary fs-4 me-3"></i>
                                <div>
                                    <strong><?php esc_html_e('Email', 'tecnoinfor'); ?>:</strong><br>
                                    <?php
                                    $contact_email = get_theme_mod(
                                        'tecnoinfor_contact_email',
                                        'contato@tecnoinfor.com.br'
                                    );
                                    ?>
                                    <a href="mailto:<?php echo esc_attr($contact_email); ?>">
                                        <?php echo esc_html($contact_email); ?>
                                    </a>
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-start">
                                <i class="bi bi-clock text-primary fs-4 me-3"></i>
                                <div>
                                    <strong><?php esc_html_e('Opening Hours', 'tecnoinfor'); ?>:</strong><br>
                                    <?php echo esc_html(get_informacao_empresa('horarios')); ?>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Social Media -->
                    <div class="card shadow-sm border-0 p-4 mt-4 bg-white">
                        <h3 class="fs-3 fw-bold text-primary mb-4"><?php esc_html_e('Follow Us', 'tecnoinfor'); ?></h3>
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

</main>

<?php get_footer(); ?>