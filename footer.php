
<!-- Botão Voltar ao Topo -->
<a href="#goTop" id="goTopBtn" class="btn btn-primary position-fixed py-2 shadow">
  <i class="bi bi-arrow-up"></i>
</a>
<footer class="bg-primary text-white pt-5">
    <div class="container ">
        <div class="row py-1">
            <!-- Logo e descrição (ocupa toda a largura em telas pequenas e 3 colunas em telas maiores) -->
            <div class="col-12 col-md-3 mb-4 text-center text-md-start">
                <div class="footer-logo c-white">
                    <?php echo get_custom_logo('img-fluid'); ?>
                </div>
                <p class="small"><?php echo comicpress_copyright(); ?></p>

            </div>

            <!-- Menus (Cada um ocupa toda a largura em telas pequenas e 2 colunas em telas médias ou maiores) -->
            <div class="col-6 col-md-2 mb-4 text-center text-md-start">
                <h5 class="fw-bold"><?php echo __('Tecnoinfor', 'tecnoinfor'); ?></h5>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'secondary',
                    'menu_class' => 'nav flex-column',
                    'container' => false,
                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'fallback_cb' => false,
                    'depth' => 1,
                ));
                ?>
            </div>

            <div class="col-6 col-md-2 mb-4 text-center text-md-start">
                <h5 class="fw-bold"><?php echo __('Solutions', 'tecnoinfor'); ?></h5>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'fourth',
                    'menu_class' => 'nav flex-column',
                    'container' => false,
                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'fallback_cb' => false,
                    'depth' => 1,
                ));
                ?>
            </div>

            <div class="col-6 col-md-2 mb-4 text-center text-md-start">
                <h5 class="fw-bold"><?php echo __('Contact', 'tecnoinfor'); ?></h5>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'three',
                    'menu_class' => 'nav flex-column',
                    'container' => false,
                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'fallback_cb' => false,
                    'depth' => 1,
                ));
                ?>
            </div>

            <div class="col-6 col-md-2 mb-4 text-center text-md-start">
                <h5 class="fw-bold"><?php echo __('Support', 'tecnoinfor'); ?></h5>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'Fifth',
                    'menu_class' => 'nav flex-column',
                    'container' => false,
                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'fallback_cb' => false,
                    'depth' => 1,
                ));
                ?>
            </div>
            <!-- Links no centro (ocupa toda a largura em todas as telas, centralizado) -->
            <div class="col-12 text-center ">
                <p class="small text-body-warning">
                    <a href="<?php echo get_bloginfo('url'); ?>/termos-de-uso" class="px-2"><?php _e('Terms of Use', 'tecnoinfor'); ?></a> |
                    <a href="<?php echo get_bloginfo('url'); ?>/politica-de-privacidade" class="px-2"><?php _e('Privacy Policy', 'tecnoinfor'); ?></a> |
                    <a href="<?php echo get_bloginfo('url'); ?>/politica-de-cookies" class="px-2"><?php _e('Cookie Privacy', 'tecnoinfor'); ?></a>
                </p>
            </div>
        </div>

        <!-- Rodapé inferior com redes sociais e direitos (Responsivo, centraliza em telas pequenas) -->
        <div class="d-flex flex-column flex-sm-row justify-content-between delimiter-top text-center text-sm-start">
            <span class="small text-sm text-md-left pt-4 "><?php _e('Designed by', 'tecnoinfor'); ?> <a class="link-light" href="https://manuseiro.github.io/" target="_blank"> @manuseiro</a></span>
            <ul class="social-midia list-unstyled d-flex justify-content-center justify-content-sm-start pt-4 text-white">
                <li class="ms-3"><a class="link-light btn" href="#"><i class="bi bi-instagram"></i></a></li>
                <li class="ms-3"><a class="link-light btn" href="#"><i class="bi bi-twitter-x"></i></a></li>
                <li class="ms-3"><a class="link-light btn" href="#"><i class="bi bi-facebook"></i></a></li>
                <li class="ms-3"><a class="link-light btn" href="#"><i class="bi bi-youtube"></i></a></li>
                <li class="ms-3"><a class="link-light btn" href="#"><i class="bi bi-linkedin"></i></a></li>
            </ul>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const goTopBtn = document.getElementById("goTopBtn");

    // Mostrar o botão ao rolar para baixo
    window.addEventListener("scroll", function() {
        if (window.scrollY > 300) {
            goTopBtn.style.display = "block";
        } else {
            goTopBtn.style.display = "none";
        }
    });

    // Rolagem suave ao clicar no botão
    goTopBtn.addEventListener("click", function(event) {
        event.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});
</script>
</body>

</html>