<?php

/**
 * Template para exibir a página Software.
 *
 * @package Tecnoinfor
 * Template Name: Software Sisplan
 */

get_header();
?>
<div id="banner-header" class="px-4 py-5 text-center text-white bg-primary">
  <h1 class="display-2 pt-5 fw-bold"><?php the_title(); ?></h1>
  <div class="col-lg-8 pb-5 mx-auto">
    <?php if (has_excerpt()) : ?>
      <p class="lead my-5"><?php echo get_the_excerpt(); ?>
      </p>
    <?php endif; ?>
    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
      <a href="https://www.tecnoinfor.com.br/uploads/downloads/Demo_Sisplan.exe" class="btn btn-success btn-lg me-md-2 py-3 px-5 gap-3" target='_blank' rel='noopener noreferrer' >Baixar Software<i class="bi bi-download ms-3"></i></a>
      <a href="<?php echo esc_url(home_url()); ?>/contato/" class="btn btn-outline-light btn-lg me-md-2 py-3 px-5 gap-3">Contato Comercial</a>
    </div>
    </br><small>Baixe a versão de teste do nosso software e aproveite as funcionalidades por 15 dias*</small>
  </div>
</div>
<main class="main-content">
  <section class="negocio">
    <div class="pt-5">
      <div class="p-5 text-center">
        <div class="container py-5">
          <h1 class="text-body-emphasis display-6 fw-bold">Foque no seu Negócio</h1>
          <p class="col-lg-12 mx-auto lead text-light-emphasis">
            Simplifique suas tarefas diárias com praticidade e segurança para cuidar do que realmente importa, com uma solução completa para gestão de planos funerários e serviços assistenciais. Automatize e otimize suas operações, desde a gestão de contratos até a emissão de carner, garantindo maior eficiência e satisfação dos clientes.
          </p>

        </div>
      </div>
    </div>
    <div class="pb-3" id="featured-3">
      <div class="container">
      <!-- Vídeo centralizado usando wp_oembed_get -->
      <div class="text-center my-5">
      <?php echo wp_oembed_get("https://www.youtube.com/watch?v=rXo9pKBt8Uk"); ?>
      </div>
        <div class="col-12 px-5 pb-3 text-center">
          <div class="container py-5">
            <h1 class="display-6 fw-bold text-primary">Principais Módulos</h1>
            <p class="col-lg-12 mx-auto lead text-light-emphasis">
            Nosso sistema é composto de módulos estratégicos, que organizam e simplificam as operações da sua funerária
            </p>
          </div>
        </div>
        <?php 
        // Inclui o conteúdo do header puxado do arquivo template-parts/content-features.php
        get_template_part('template-parts/content', 'features'); 
        ?>

      </div>
    </div>
  </section>
  <div class="container col-xl-10 col-xxl-8 px-4 py-5">
    <div class="row align-items-center g-lg-5 py-5">
      <div class="col-md-10 mx-auto col-lg-5">
        <form class="p-4 p-md-5 border rounded-3 bg-body-tertiary">
          <div class="form-floating mb-3">
            <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Email address</label>
          </div>
          <div class="form-floating mb-3">
            <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
            <label for="floatingPassword">Password</label>
          </div>
          <div class="checkbox mb-3">
            <label>
              <input type="checkbox" value="remember-me"> Remember me
            </label>
          </div>
          <button class="w-100 btn btn-lg btn-primary" type="submit">Sign up</button>
          <hr class="my-4">
          <small class="text-body-secondary">By clicking Sign up, you agree to the terms of use.</small>
        </form>
      </div>
      <div class="col-lg-7 text-center text-lg-start">
        <h1 class="display-4 fw-bold lh-1 text-body-emphasis mb-3">Vertically centered hero sign-up form</h1>
        <p class="col-lg-10 fs-4">Below is an example form built entirely with Bootstrap’s form controls. Each required form group has a validation state that can be triggered by attempting to submit the form without completing it.</p>
      </div>
    </div>
  </div>
  <div class="container col-xxl-8 px-4 py-5">
    <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
      <div class="col-10 col-sm-8 col-lg-6">
        <img src="<?php bloginfo('template_url'); ?>/assets/images/bootstrap-themes.png" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy">
      </div>
      <div class="col-lg-6">
        <h1 class="display-5 fw-bold text-body-emphasis lh-1 mb-3">Por que a
          Tecnoinfor?</h1>
        <p class="lead">
        Optar pela Tecnoinfor significa escolher uma parceira que entende os desafios do seu negócio e oferece soluções que realmente fazem a diferença. Com mais de uma década de experiência no mercado, sabemos que tecnologia só é valiosa quando simplifica, agiliza e transforma o dia a dia da sua empresa. Nosso compromisso é ser um suporte ativo e dedicado ao seu sucesso.
        </br></br>
        Solicite uma demonstração gratuita hoje mesmo e descubra como podemos te ajudar a alcançar seus objetivos!
        </p>
        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
          <a class="btn btn-primary btn-lg px-4 me-md-2" href="#">Entrar em contato</a>
        </div>
      </div>
    </div>
  </div>
  <section class="faq py-5">
    <div class="container">
      <div class="row justify-content-md-center">
        <div class="px-5 pb-3 text-center">
          <div class="container py-5">
            <h1 class="display-6 fw-bold text-primary">Perguntas frequentes</h1>
            <p class="col-lg-12 mx-auto lead text-light-emphasis">
              Consulte a lista das perguntas mais frequentes solicitadas à Tecnoinfor. Caso sua dúvida não esteja listada, entre em contato conosco.
            </p>
          </div>
        </div>
        <div class="col-lg-8 col-md-auto">
          <?php
          // Defina o slug do assunto que você deseja exibir
          $assunto_especifico = 'perguntas-sobre-suporte'; // Troque pelo slug do assunto que você quer

          // Faz uma consulta para recuperar as FAQs associadas ao termo específico
          $faq_query = new WP_Query(array(
            'post_type' => 'faq',
            'tax_query' => array(
              array(
                'taxonomy' => 'assunto',
                'field' => 'slug',
                'terms' => $assunto_especifico, // Usando o slug do termo específico
              ),
            ),
          ));

          // Verifica se há FAQs para o assunto específico
          if ($faq_query->have_posts()) {
            echo '<div class="accordion accordion-flush mb-5 border" id="accordionFaqs-' . esc_attr($assunto_especifico) . '">';

            // Loop pelos FAQs encontrados
            while ($faq_query->have_posts()) {
              $faq_query->the_post();

              // Usando o ID do post para gerar IDs únicos para o accordion
              $post_id = get_the_ID();
          ?>
              <div class="accordion-item">
                <h2 class="accordion-header" id="heading-<?php echo $post_id; ?>">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $post_id; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $post_id; ?>">
                    <?php the_title(); ?>
                  </button>
                </h2>
                <div id="collapse-<?php echo $post_id; ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?php echo $post_id; ?>" data-bs-parent="#accordionFaqs-<?php echo esc_attr($assunto_especifico); ?>">
                  <div class="accordion-body">
                    <?php the_content(); ?>
                  </div>
                </div>
              </div>
          <?php
            }

            echo '</div>'; // Fecha o accordion
          } else {
            // Se não houver FAQs para este assunto
            echo '<p>Nenhuma FAQ disponível para este assunto.</p>';
          }

          // Reseta o loop do WP_Query
          wp_reset_postdata();
          ?>
        </div>



      </div>
    </div>
  </section>
</main>

<?php get_footer(); ?>