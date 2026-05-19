<!-- Botão Voltar ao Topo -->
<a href="#goTop" id="goTopBtn" class="btn btn-primary position-fixed py-2 shadow" aria-label="<?php _e('Back to top', 'tecnoinfor'); ?>">
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
                    <h6 class="fw-bold mb-2 small text-uppercase tracking-wider text-white-50"><?php _e('Assine a Newsletter', 'tecnoinfor'); ?></h6>
                    <form id="newsletter-form" class="d-flex gap-2">
                        <input type="email" name="newsletter_email" class="form-control form-control-sm rounded-pill px-3 border-0 shadow-sm" placeholder="<?php esc_attr_e('Seu e-mail', 'tecnoinfor'); ?>" required style="max-width: 180px;">
                        <button type="submit" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark shadow-sm"><?php _e('Assinar', 'tecnoinfor'); ?></button>
                    </form>
                    <div id="newsletter-message" class="small mt-2" style="display:none;"></div>
                </div>
            </div>
            <div class="col-6 col-md-2 mb-4 text-center text-md-start">
                <h5 class="fw-bold"><?php echo __('Tecnoinfor', 'tecnoinfor'); ?></h5>
                <?php wp_nav_menu(array('theme_location' => 'secondary', 'menu_class' => 'nav flex-column', 'container' => false, 'fallback_cb' => false, 'depth' => 1)); ?>
            </div>
            <div class="col-6 col-md-2 mb-4 text-center text-md-start">
                <h5 class="fw-bold"><?php echo __('Solutions', 'tecnoinfor'); ?></h5>
                <?php wp_nav_menu(array('theme_location' => 'fourth', 'menu_class' => 'nav flex-column', 'container' => false, 'fallback_cb' => false, 'depth' => 1)); ?>
            </div>
            <div class="col-6 col-md-2 mb-4 text-center text-md-start">
                <h5 class="fw-bold"><?php echo __('Contact', 'tecnoinfor'); ?></h5>
                <?php wp_nav_menu(array('theme_location' => 'three', 'menu_class' => 'nav flex-column', 'container' => false, 'fallback_cb' => false, 'depth' => 1)); ?>
            </div>
            <div class="col-6 col-md-2 mb-4 text-center text-md-start">
                <h5 class="fw-bold"><?php echo __('Support', 'tecnoinfor'); ?></h5>
                <?php wp_nav_menu(array('theme_location' => 'fifth', 'menu_class' => 'nav flex-column', 'container' => false, 'fallback_cb' => false, 'depth' => 1)); ?>
            </div>
            <div class="col-12 text-center mt-3">
                <p class="small text-warning">
                    <a href="<?php echo esc_url(get_bloginfo('url') . '/termos-de-uso'); ?>" class="px-2 text-warning text-decoration-none"><?php _e('Terms of Use', 'tecnoinfor'); ?></a> |
                    <a href="<?php echo esc_url(get_bloginfo('url') . '/politica-de-privacidade'); ?>" class="px-2 text-warning text-decoration-none"><?php _e('Privacy Policy', 'tecnoinfor'); ?></a> |
                    <a href="<?php echo esc_url(get_bloginfo('url') . '/politica-de-cookies'); ?>" class="px-2 text-warning text-decoration-none"><?php _e('Cookie Privacy', 'tecnoinfor'); ?></a>
                </p>
            </div>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-between delimiter-top text-center text-sm-start">
            <span class="small text-sm text-md-left pt-4"><?php _e('Designed by', 'tecnoinfor'); ?> <a class="link-light" href="https://manuseiro.github.io/" target="_blank" rel="noopener noreferrer">@manuseiro</a></span>
            <ul class="social-midia list-unstyled d-flex justify-content-center justify-content-sm-start pt-4 text-white">
                <?php
                $redes_sociais = get_redes_sociais();
                foreach ($redes_sociais as $rede => $dados) {
                    $info = get_rede_social($rede);
                    if (!empty($info['link'])) {
                        echo "<li class='ms-3'><a href='{$info['link']}' class='link-light btn' target='_blank' rel='noopener noreferrer' aria-label='{$info['nome']}'><i class='{$info['icone']}'></i></a></li>";
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</footer>
<?php if (!isset($_COOKIE['lgpd_consent'])) : ?>
<div id="lgpd-consent-banner" class="d-none">
    <div class="lgpd-banner-card shadow-lg p-3 rounded-4 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 text-white">
        <p class="mb-0 text-start small">
            🍪 <?php _e('Nós usamos cookies para melhorar sua experiência. Ao continuar navegando, você concorda com a nossa Política de Privacidade.', 'tecnoinfor'); ?>
            <a href="<?php echo esc_url(get_bloginfo('url') . '/politica-de-cookies'); ?>" class="text-info text-decoration-none ms-1 fw-bold"><?php _e('Política', 'tecnoinfor'); ?></a>
        </p>
        <div class="d-flex gap-2 align-items-center flex-shrink-0">
            <button id="lgpd-accept" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm"><?php _e('Aceitar', 'tecnoinfor'); ?></button>
            <button id="lgpd-reject" class="btn id-lgpd-reject btn-outline-light btn-sm rounded-pill px-3"><?php _e('Personalizar', 'tecnoinfor'); ?></button>
            <button id="lgpd-close" class="btn btn-link text-white p-0 ms-2 text-decoration-none border-0 fs-5" aria-label="Fechar">✕</button>
        </div>
    </div>
</div>
<?php endif; ?>
<?php wp_footer(); ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const goTopBtn = document.getElementById("goTopBtn");
    window.addEventListener("scroll", function() {
        goTopBtn.style.display = window.scrollY > 300 ? "block" : "none";
    });
    goTopBtn.addEventListener("click", function(event) {
        event.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});
</script>
</body>
</html>