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
  <!--<img class="d-block mx-auto mb-4" src="/docs/5.3/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">-->
  <h2 class="display-2 pt-5 fw-bold"><?php the_title(); ?></h2>
  <div class="col-lg-8 mx-auto">
    <?php if (has_excerpt()) : ?>
      <p class="lead my-5"><?php echo get_the_excerpt(); ?>
      </p>
    <?php endif; ?>
    <div class="input-group mb-3 py-5">
      <input type="text" class="form-control p-3" placeholder="Search documentation..." aria-label="Search documentation..." aria-describedby="button-addon2">
      <button class="btn btn-outline-light px-4" type="button" id="button-addon2"><i class="bi bi-search"></i></button>
    </div>

  </div>
</div>
  <div class="container my-5 p-5">
    <div class="row">
      <div class="col col-8">
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
              echo '<p>Nenhuma FAQ disponível para este assunto.</p>';
            }

            // Reseta o loop do WP_Query
            wp_reset_postdata();
          }
        } else {
          // Se não houver termos na taxonomia "Assunto"
          echo '<p>Nenhum assunto encontrado.</p>';
        }
        ?>
      </div>

      <div class="col col-4 px-5">
        <h3 class="fw-bold py-3">Nesta página</h3>
        <?php
        // Recupera os termos da taxonomia "assunto"
        $assuntos = get_terms(array(
          'taxonomy' => 'assunto',
          'hide_empty' => true, // Mostra apenas assuntos com FAQs associadas
        ));

        if (!empty($assuntos) && !is_wp_error($assuntos)) {
          echo '<nav class="nav flex-column">'; // Inicia uma lista com Bootstrap (opcional)

          // Loop pelos termos (assuntos)
          foreach ($assuntos as $assunto) {
            // Link para o ID interno da página
            echo '<a class="nav-link border" href="#' . esc_attr($assunto->slug) . '">' . esc_html($assunto->name) . '</a>';
          }

          echo '</nav>'; // Fecha a lista
        } else {
          // Se não houver assuntos disponíveis
          echo '<p>Nenhum assunto disponível no momento.</p>';
        }
        ?>
      </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>