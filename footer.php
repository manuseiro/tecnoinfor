<!-- Botão Voltar ao Topo -->
<a href="#goTop" id="goTopBtn" class="btn btn-primary position-fixed py-2 shadow" aria-label="<?php _e('Back to top', 'tecnoinfor'); ?>">
    <i class="bi bi-arrow-up"></i>
</a>
<footer class="bg-primary text-white pt-5">
    <div class="container">
        <div class="row py-1">
            <div class="col-12 col-md-3 mb-4 text-center text-md-start">
                <div class="footer-logo c-white">
                    <?php echo get_custom_logo('img-fluid'); ?>
                </div>
                <p class="small"><?php echo esc_html(tecnoinfor_copyright()); ?></p>
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
                <?php wp_nav_menu(array('theme_location' => 'Fifth', 'menu_class' => 'nav flex-column', 'container' => false, 'fallback_cb' => false, 'depth' => 1)); ?>
            </div>
            <div class="col-12 text-center">
                <p class="small text-body-warning">
                    <a href="<?php echo esc_url(get_bloginfo('url') . '/termos-de-uso'); ?>" class="px-2"><?php _e('Terms of Use', 'tecnoinfor'); ?></a> |
                    <a href="<?php echo esc_url(get_bloginfo('url') . '/politica-de-privacidade'); ?>" class="px-2"><?php _e('Privacy Policy', 'tecnoinfor'); ?></a> |
                    <a href="<?php echo esc_url(get_bloginfo('url') . '/politica-de-cookies'); ?>" class="px-2"><?php _e('Cookie Privacy', 'tecnoinfor'); ?></a>
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