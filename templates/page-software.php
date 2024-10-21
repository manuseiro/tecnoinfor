<?php

/**
 * Template para exibir a página Software.
 *
 * @package Tecnoinfor
 * Template Name: Software Sisplan
 */

get_header();
?>
<div class="px-4 py-5 text-center text-white bg-primary">
  <!--<img class="d-block mx-auto mb-4" src="/docs/5.3/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">-->
  <h1 class="display-2 pt-5 fw-bold"><?php the_title(); ?></h1>
  <div class="col-lg-8 pb-5 mx-auto">
    <?php if (has_excerpt()) : ?>
      <p class="lead my-5"><?php echo get_the_excerpt(); ?>
      </p>
    <?php endif; ?>
    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
      <a href="<?php echo esc_url(home_url()); ?>/mais-informacoes/" class="btn btn-outline-light btn-lg px-4 gap-3">Contato Comercial</a>
    </div>
  </div>
</div>
<main class="main-content">
  <section class="negocio">
    <div class="py-5">
      <div class="p-5 text-center">
        <div class="container py-5">
          <h1 class="text-body-emphasis display-6 fw-bold">Foque no seu Negócio</h1>
          <p class="col-lg-12 mx-auto lead text-light-emphasis">
            Simplifique suas tarefas diárias com praticidade e segurança para cuidar do que realmente importa, com uma solução completa para gestão de planos funerários e serviços assistenciais. Automatize e otimize suas operações, desde a gestão de contratos até a emissão de carner, garantindo maior eficiência e satisfação dos clientes.
          </p>
        </div>
      </div>
    </div>
    <div class="px-4" id="featured-3">
      <div class="container">
        <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
          <div class="feature col p-4 shadow-sm">
            <div class="feature-icon d-inline-flex align-items-center justify-content-center bg-primary-subtle fs-2 p-2 px-3 mb-3 rounded">
              <i class="bi bi-person-bounding-box text-primary"></i>
            </div>
            <h3 class="fs-2 text-body-emphasis fw-bold">Cadastros</h3>
            <p class="text-light-emphasis">Gere contratos personalizados, emita carteiras exclusivas e acompanhe todo o ciclo de vida do contrato, desde a assinatura até a rescisão. O módulo de atendimento ao usuário facilita o processo de comunicação com os clientes, agilizando o atendimento em casos de óbito e outras solicitações.</p>
          </div>
          <div class="feature col p-4 shadow-sm">
            <div class="feature-icon d-inline-flex align-items-center justify-content-center bg-primary-subtle fs-2 p-2 px-3 mb-3 rounded">
              <i class="bi bi-person-vcard text-primary"></i>
            </div>
            <h3 class="fs-2 text-body-emphasis fw-bold">Administração de Contratos</h3>
            <p class="text-light-emphasis">Gere contratos personalizados, emita carteiras exclusivas e acompanhe todo o ciclo de vida do contrato, desde a assinatura até a rescisão. O módulo de atendimento ao usuário facilita o processo de comunicação com os clientes, agilizando o atendimento em casos de óbito e outras solicitações.</p>
          </div>
          <div class="feature col p-4 shadow-sm">
            <div class="feature-icon d-inline-flex align-items-center justify-content-center bg-primary-subtle fs-2 p-2 px-3 mb-3 rounded">
              <i class="bi bi-piggy-bank text-primary"></i>
            </div>
            <h3 class="fs-2 text-body-emphasis fw-bold">Financeiro</h3>
            <p class="text-light-emphasis">Controle suas receitas e despesas de forma eficiente. Emita boletos bancários para diversos bancos, acompanhe o fluxo de caixa e gere relatórios detalhados sobre as suas finanças.</p>
          </div>
          <div class="feature col p-4 shadow-sm">
            <div class="feature-icon d-inline-flex align-items-center justify-content-center bg-primary-subtle fs-2 p-2 px-3 mb-3 rounded">
              <i class="bi bi-journal-plus text-primary"></i>
            </div>
            <h3 class="fs-2 text-body-emphasis fw-bold">Cadastros Básicos</h3>
            <p class="text-light-emphasis">Organize e padronize as informações essenciais para o seu negócio. O módulo de Cadastros Básicos permite a gestão completa de planos de contas, contas bancárias, produtos e fornecedores. Com ele, você garante a precisão dos dados e agiliza a geração de relatórios financeiros.</p>
          </div>
          <div class="feature col p-4 shadow-sm">
            <div class="feature-icon d-inline-flex align-items-center justify-content-center bg-primary-subtle fs-2 p-2 px-3 mb-3 rounded">
              <i class="bi bi-cart text-primary"></i>
            </div>
            <h3 class="fs-2 text-body-emphasis fw-bold">Venda</h3>
            <p class="text-light-emphasis">Obtenha insights valiosos sobre o desempenho do seu negócio. O módulo de relatórios do Sisplan oferece uma variedade de opções para gerar relatórios personalizados, como contratos realizados por período, cobranças, controle de títulos e muito mais. Utilize essas informações para identificar oportunidades de melhoria e tomar decisões mais assertivas.</p>
          </div>
          <div class="feature col p-4 shadow-sm">
            <div class="feature-icon d-inline-flex align-items-center justify-content-center bg-primary-subtle fs-2 p-2 px-3 mb-3 rounded">
              <i class="bi bi-journal-text text-primary"></i>
            </div>
            <h3 class="fs-2 text-body-emphasis fw-bold">Relatórios</h3>
            <p class="text-light-emphasis">Obtenha insights valiosos sobre o desempenho do seu negócio. O módulo de relatórios do Sisplan oferece uma variedade de opções para gerar relatórios personalizados, como contratos realizados por período, cobranças, controle de títulos e muito mais. Utilize essas informações para identificar oportunidades de melhoria e tomar decisões mais assertivas.</p>
          </div>
        </div>
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
        <h1 class="display-5 fw-bold text-body-emphasis lh-1 mb-3">Responsive left-aligned hero with image</h1>
        <p class="lead">Quickly design and customize responsive mobile-first sites with Bootstrap, the world’s most popular front-end open source toolkit, featuring Sass variables and mixins, responsive grid system, extensive prebuilt components, and powerful JavaScript plugins.</p>
        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
          <button type="button" class="btn btn-primary btn-lg px-4 me-md-2">Primary</button>
          <button type="button" class="btn btn-outline-secondary btn-lg px-4">Default</button>
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