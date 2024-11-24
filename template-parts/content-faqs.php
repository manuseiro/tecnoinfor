<section class="faq wow fadeIn py-5">
    <div class="container py-5">
      <div class="text-center mb-5">
        <h3 class="display-6 fw-bold text-primary">Perguntas frequentes</h3>
        <p class="lead text-light-emphasis">Consulte a lista das perguntas mais frequentes ou entre em contato conosco.</p>
      </div>
      <div class="col-lg-8 mx-auto">
        <?php
        $faq_query = new WP_Query([
          'post_type' => 'faq',
          'tax_query' => [[
            'taxonomy' => 'assunto',
            'field'    => 'slug',
            'terms'    => 'perguntas-gerais',
          ]],
        ]);

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