<a href="#goTop" id="goTopBtn" class="btn btn-primary position-fixed py-2 shadow"
    aria-label="<?php esc_attr_e('Back to top', 'tecnoinfor'); ?>">
    <i class="bi bi-arrow-up"></i>
</a>
<footer class="bg-primary text-white pt-5">
    <div class="container">
        <div class="row py-1">
            <div class="col-12 col-md-3 mb-4 text-center text-md-start">
                <div class="footer-logo c-white mb-3">
                    <?php echo get_custom_logo(); ?>
                </div>
                <p class="small mb-3"><?php echo esc_html(tecnoinfor_copyright()); ?></p>
                <!-- Newsletter Form -->
                <div class="footer-newsletter mt-4">
                    <h6 class="fw-bold mb-2 small text-uppercase tracking-wider text-white-50">
                        <?php esc_html_e('Assine a Newsletter', 'tecnoinfor'); ?></h6>
                    <form id="newsletter-form" class="d-flex gap-2">
                        <input type="email" name="newsletter_email"
                            class="form-control form-control-sm rounded-pill px-3 border-0 shadow-sm"
                            placeholder="<?php esc_attr_e('Seu e-mail', 'tecnoinfor'); ?>" required
                            style="max-width: 180px;">
                        <button type="submit"
                            class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark shadow-sm"><?php esc_html_e('Assinar', 'tecnoinfor'); ?></button>
                    </form>
                    <div id="newsletter-message" class="small mt-2" style="display:none;"></div>
                </div>
            </div>
            <div class="col-6 col-md-2 mb-4 text-center text-md-start">
                <h5 class="fw-bold"><?php esc_html_e('Tecnoinfor', 'tecnoinfor'); ?></h5>
                <?php wp_nav_menu(array('theme_location' => 'secondary', 'menu_class' => 'nav flex-column', 'container' => false, 'fallback_cb' => false, 'depth' => 1)); ?>
            </div>
            <div class="col-6 col-md-2 mb-4 text-center text-md-start">
                <h5 class="fw-bold"><?php esc_html_e('Solutions', 'tecnoinfor'); ?></h5>
                <?php wp_nav_menu(array('theme_location' => 'fourth', 'menu_class' => 'nav flex-column', 'container' => false, 'fallback_cb' => false, 'depth' => 1)); ?>
            </div>
            <div class="col-6 col-md-2 mb-4 text-center text-md-start">
                <h5 class="fw-bold"><?php esc_html_e('Contact', 'tecnoinfor'); ?></h5>
                <?php wp_nav_menu(array('theme_location' => 'three', 'menu_class' => 'nav flex-column', 'container' => false, 'fallback_cb' => false, 'depth' => 1)); ?>
            </div>
            <div class="col-6 col-md-2 mb-4 text-center text-md-start">
                <h5 class="fw-bold"><?php esc_html_e('Support', 'tecnoinfor'); ?></h5>
                <?php wp_nav_menu(array('theme_location' => 'fifth', 'menu_class' => 'nav flex-column', 'container' => false, 'fallback_cb' => false, 'depth' => 1)); ?>
            </div>
            <div class="col-12 text-center mt-3">
                <p class="small text-warning">
                    <a href="<?php echo esc_url(get_bloginfo('url') . '/termos-de-uso'); ?>"
                        class="px-2 text-warning text-decoration-none"><?php esc_html_e('Terms of Use', 'tecnoinfor'); ?></a> |
                    <a href="<?php echo esc_url(get_bloginfo('url') . '/politica-de-privacidade'); ?>"
                        class="px-2 text-warning text-decoration-none"><?php esc_html_e('Privacy Policy', 'tecnoinfor'); ?></a>
                    |
                    <a href="<?php echo esc_url(get_bloginfo('url') . '/politica-de-cookies'); ?>"
                        class="px-2 text-warning text-decoration-none"><?php esc_html_e('Cookie Privacy', 'tecnoinfor'); ?></a>
                </p>
            </div>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-between delimiter-top text-center text-sm-start">
            <span class="small text-sm text-md-left pt-4"><?php esc_html_e('Designed by', 'tecnoinfor'); ?> <a
                    class="link-light" href="https://manuseiro.github.io/" target="_blank"
                    rel="noopener noreferrer">@manuseiro</a></span>
            <ul
                class="social-midia list-unstyled d-flex justify-content-center justify-content-sm-start pt-4 text-white">
                <?php
                $redes_sociais = get_redes_sociais();
                foreach ($redes_sociais as $rede => $dados) {
                    $info = get_rede_social($rede);
                    if (!empty($info['link'])) {
                        printf(
                            '<li class="ms-3"><a href="%s" class="link-light btn" target="_blank" rel="noopener noreferrer" aria-label="%s"><i class="%s" aria-hidden="true"></i></a></li>',
                            esc_url($info['link']),
                            esc_attr($info['nome']),
                            esc_attr($info['icone'])
                        );
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</footer>
<?php if (!isset($_COOKIE['lgpd_consent'])): ?>
    <div id="lgpd-consent-banner" class="d-none">
        <div
            class="lgpd-banner-card shadow-lg p-3 rounded-4 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 text-white">
            <p class="mb-0 text-start small">
                🍪
                <?php esc_html_e('Nós usamos cookies para melhorar sua experiência. Ao continuar navegando, você concorda com a nossa Política de Privacidade.', 'tecnoinfor'); ?>
                <a href="<?php echo esc_url(get_bloginfo('url') . '/politica-de-cookies'); ?>"
                    class="text-info text-decoration-none ms-1 fw-bold"><?php esc_html_e('Política', 'tecnoinfor'); ?></a>
            </p>
            <div class="d-flex gap-2 align-items-center flex-shrink-0">
                <button id="lgpd-accept"
                    class="btn btn-success btn-sm rounded-pill px-3 shadow-sm"><?php esc_html_e('Aceitar', 'tecnoinfor'); ?></button>
                <button id="lgpd-reject"
                    class="btn id-lgpd-reject btn-outline-light btn-sm rounded-pill px-3"><?php esc_html_e('Personalizar', 'tecnoinfor'); ?></button>
                <button id="lgpd-close" class="btn btn-link text-white p-0 ms-2 text-decoration-none border-0 fs-5"
                    aria-label="Fechar">✕</button>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php wp_footer(); ?>
</body>

</html>