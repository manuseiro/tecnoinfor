<?php

/**
 * Template para exibir a página Faqs.
 *
 * @package Tecnoinfor
 * Template Name: Faqs
 */

get_header(); ?>
<main class="main-content">
    <div class="px-4 py-5 text-center text-white bg-primary">
        <h2 class="display-2 pt-5 fw-bold"><?php the_title(); ?></h2>
        <div class="col-lg-8 mx-auto">
            <?php if (has_excerpt()) : ?>
                <p class="lead my-5"><?php echo get_the_excerpt(); ?></p>
            <?php endif; ?>
            <div class="input-group mb-3 py-5">
                <input type="text" id="faq-search" class="form-control p-3" placeholder="<?php _e('Search documentation...', 'tecnoinfor'); ?>" aria-label="<?php _e('Search documentation...', 'tecnoinfor'); ?>" aria-describedby="button-addon2">
                <button class="btn btn-outline-light px-4" type="button" id="button-addon2"><i class="bi bi-search"></i></button>
            </div>
            <script>
                document.getElementById("faq-search").addEventListener("input", function() {
                    var input = this.value.toLowerCase();
                    document.querySelectorAll(".accordion-item").forEach(function(item) {
                        var question = item.querySelector(".accordion-button").textContent.toLowerCase();
                        item.style.display = question.includes(input) ? "" : "none";
                    });
                });
            </script>
        </div>
    </div>
    <div class="container my-5 p-3">
        <div class="row">
            <div class="col-md-8 col-12">
                <?php
                // Recupera todos os termos da taxonomia "Assunto"
                $assuntos = get_terms(array(
                    'taxonomy' => 'assunto',
                    'hide_empty' => true, // Mostra apenas assuntos com FAQs associadas
                ));

                // Verifica se há termos na taxonomia "Assunto"
                if (!empty($assuntos) && !is_wp_error($assuntos)) {
                    foreach ($assuntos as $assunto) {
                        // Exibe o título do Assunto com um ID baseado no slug para navegação interna
                        echo '<h3 id="' . esc_attr($assunto->slug) . '" class="fw-bold py-3">' . esc_html($assunto->name) . '</h3>';

                        // Recupera os posts do tipo 'faq' associados a este termo de assunto
                        $faq_query = new WP_Query(array(
                            'post_type' => 'faq',
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'assunto',
                                    'field' => 'slug',
                                    'terms' => $assunto->slug,
                                ),
                            ),
                        ));

                        // Verifica se há FAQs para este Assunto
                        if ($faq_query->have_posts()) {
                            echo '<div class="accordion accordion-flush mb-5 border" id="accordionFaqs-' . esc_attr($assunto->slug) . '">';

                            // Loop pelos FAQs encontrados
                            while ($faq_query->have_posts()) {
                                $faq_query->the_post();

                                // Usando o ID do post para gerar os IDs únicos para o accordion
                                $post_id = get_the_ID();
                ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading-<?php echo $post_id; ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $post_id; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $post_id; ?>">
                                            <?php the_title(); ?>
                                        </button>
                                    </h2>
                                    <div id="collapse-<?php echo $post_id; ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?php echo $post_id; ?>" data-bs-parent="#accordionFaqs-<?php echo esc_attr($assunto->slug); ?>">
                                        <div class="accordion-body">
                                            <?php the_content(); ?>
                                        </div>
                                    </div>
                                </div>
                <?php
                            }

                            echo '</div>'; // Fecha o accordion
                        } else {
                            // Se não houver FAQs para o assunto
                            echo '<p>' . __('No FAQ available for this topic.', 'tecnoinfor') . '</p>';
                        }

                        // Reseta o loop do WP_Query
                        wp_reset_postdata();
                    }
                } else {
                    // Se não houver termos na taxonomia "Assunto"
                    echo '<p>' . __('Nenhum assunto encontrado.', 'tecnoinfor') . '</p>';
                }
                ?>
            </div>
            <div class="col-md-4 col-12 px-3 logs-sidebar" style="position: sticky; top: 20px;">
                <h3 class="fw-bold py-3"><?php _e('By Topics', 'tecnoinfor'); ?></h3>
                <nav class="sticky-top">
    <ul class="nav flex-column">
        <?php
        // Recupera os termos da taxonomia "assunto"
        $assuntos = get_terms(array(
            'taxonomy' => 'assunto',
            'hide_empty' => true, // Mostra apenas assuntos com FAQs associadas
        ));

        if (!empty($assuntos) && !is_wp_error($assuntos)) {
            foreach ($assuntos as $assunto) {
                echo '<li class="nav-item">';
                echo '<a class="nav-link custom-link py-3" href="#' . esc_attr($assunto->slug) . '">' . esc_html($assunto->name) . '</a>';
                echo '</li>';
            }
        } else {
            echo '<p>' . __('No topics available at this time.', 'tecnoinfor') . '</p>';
        }
        ?>
    </ul>
</nav>

<script>
    // JavaScript para adicionar a classe 'active' ao link correspondente ao hash atual
    document.addEventListener('DOMContentLoaded', function () {
        const links = document.querySelectorAll('.nav-link');

        // Função para remover a classe 'active' de todos os links
        function removeActiveClass() {
            links.forEach(link => {
                link.classList.remove('active');
            });
        }

        // Atualiza a classe 'active' com base no hash atual
        function updateActiveLink() {
            const currentHash = window.location.hash;

            removeActiveClass(); // Remove a classe 'active' de todos os links

            links.forEach(link => {
                if (link.getAttribute('href') === currentHash) {
                    link.classList.add('active'); // Adiciona a classe 'active' ao link correspondente
                }
            });
        }

        // Adiciona evento de clique a cada link
        links.forEach(link => {
            link.addEventListener('click', function () {
                // Aguarda um pequeno tempo antes de atualizar a classe 'active'
                setTimeout(updateActiveLink, 15);
            });
        });

        // Chama a função na carga inicial para definir o link ativo corretamente
        updateActiveLink();
    });
</script>





            </div>
        </div>
    </div>
</main>


<?php get_footer(); ?>