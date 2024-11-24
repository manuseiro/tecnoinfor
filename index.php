<?php get_header(); ?>
<main>
  <!-- Seção Hero -->
  <section class="hero-home d-flex wow fadeIn py-5 bg-primary-subtle">
    <div class="container-fluid">
      <div class="row p-4 pb-0 pe-lg-0 py-lg-5 align-items-center">
        <div class="col-lg-7 p-3 p-lg-5 pt-lg-3">
          <span class="badge text-bg-primary my-2">Sistema para Funerarias</span>
          <h1 class="display-4 fw-bold lh-1 text-emphasis">Gestão de Contratos para Planos Assistenciais e Funerários</h1>
          <p class="lead text-bg-text-secondary-emphasis">Agilize suas tarefas diárias com praticidade e segurança para você cuidar do que realmente importa.</p>
          <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-4 mb-lg-3">
            <a href="<?php echo get_bloginfo('url'); ?>/sisplan" class="btn btn-success btn-lg me-md-2 py-3 px-5">Teste Grátis Agora</a>
            <a href="<?php echo get_bloginfo('url'); ?>/planos-e-precos" class="btn btn-outline-primary btn-lg py-3 px-5">Veja planos e preços</a>
          </div>
          <p class="text-body-secondary mb-1">
            <small>Baixe a versão de teste do nosso software e aproveite as funcionalidades por 15 dias*, <a href="#" class="text-primary" title="saiba mais"><?php echo __('read more','tecnoinfor');?></a></small>
          </p>
        </div>
        <div class="col-lg-4 offset-lg-1 p-0 overflow-hidden shadow-lg">
          <img class="rounded-lg-3 img-fluid custom-img" src="<?php echo get_bloginfo('template_url'); ?>/assets/images/hero-img.png" alt="Sisplan - Gestão de Contratos de Planos Assistenciais e Funerários" loading="lazy">
        </div>
      </div>
    </div>
  </section>

  <section class="about bg-light fadeIn wow py-5">
    <div class="container">
      <div class="row">

        <div class="col-lg-6 m-auto p-5">
          <img class="img-fluid rounded" src="<?php echo get_bloginfo('template_url'); ?>/assets/images/about-home.svg" alt="Sobre a Empresa - Sisplan" loading="lazy">
        </div>
        <div class="col-lg-6 m-auto py-5">
          <h4 class="display-6 fw-bold ">Somos a </br><span class="text-primary"> Tecnoinfor</span></h4>

          <p class="text-secondary mt-3">
            Nossa missão é simplificar a gestão de planos assistenciais e funerários com uma plataforma segura e eficaz.
          </p>

          <p class="text-secondary">
            Desde 2009, dedicamo-nos a desenvolver soluções que otimizam processos, proporcionando tranquilidade e controle total aos nossos clientes.
          </p>

          <div class="row text-center mt-4">
            <div class="col">
              <h4 class="text-primary fw-bold">+125</h4>
              <p>Clientes Satisfeitos</p>
            </div>
            <div class="col">
              <h4 class="text-primary fw-bold">+15 anos</h4>
              <p>De Experiência</p>
            </div>
            <div class="col">
              <h4 class="text-primary fw-bold">+21.562</h4>
              <p>Contratos Gerenciados</p>
            </div>
          </div>
          <a href="<?php echo get_bloginfo('url'); ?>/sobre-nos" class="btn btn-lg btn-primary mt-4 rounded-1"><?php echo __('Learn more','tecnoinfor');?></a>
        </div>
      </div>
    </div>
  </section>
  <!-- Seção Benefícios -->
  <section class="beneficios wow fadeIn py-5" id="featured-3">
    <div class="container">
      <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
        <?php
        $benefits = [
          ["icon" => "bi-clock", "title" => "Eficiência", "desc" => "O Sisplan ajuda a organizar e automatizar as principais rotinas de uma funerária, reduzindo o tempo gasto com tarefas administrativas e melhorando a eficiência do negócio."],
          ["icon" => "bi-lock", "title" => "Confiança", "desc" => "Utilizamos um poderoso banco de dados SQL Open Source, garantindo a segurança e integridade dos dados."],
          ["icon" => "bi-bar-chart-line", "title" => "Controle", "desc" => "O Sisplan fornece relatórios detalhados e em tempo real sobre contratos e aspectos importantes do negócio."]
        ];

        foreach ($benefits as $benefit) {
          echo "<div class='feature col p-4'>
                  <div class='feature-icon d-inline-flex align-items-center justify-content-center bg-primary-subtle fs-2 p-2 px-3 mb-3 rounded'>
                    <i class='bi {$benefit['icon']} text-primary'></i>
                  </div>
                  <h3 class='fs-2 text-body-emphasis fw-bold'>{$benefit['title']}</h3>
                  <p class='text-light-emphasis'>{$benefit['desc']}</p>
                </div>";
        }
        ?>
      </div>
    </div>
  </section>
  <!-- Seção Adicional: Depoimentos de Clientes -->
  <!-- Testimonial 3 - Bootstrap Brain Component -->
  <section class="testimonial bg-light py-5 py-xl-8">
    <div class="container py-5">
      <div class="row justify-content-md-center text-center">
        <div class="col-12 col-md-10 col-lg-8 col-xl-7 col-xxl-6">
          <h3 class="display-6 fw-bold"><?php __('We help entrepreneurs simplify their businesses','tecnoinfor');?></h3>
          <p class="lead text-light-emphasis"><?php __('See what they say about our solution!','tecnoinfor');?></p>
        </div>
      </div>
    </div>

    <div class="container pb-5 overflow-hidden">
      <div class="row gy-4 gy-md-0 gx-xxl-5">

        <?php
        // Argumentos para obter os depoimentos
        $args = [
          'post_type'      => 'depoimentos', // O Custom Post Type que você registrou
          'posts_per_page' => -1,             // Obter todos os depoimentos
        ];
        $depoimentos = new WP_Query($args);

        // Loop através dos depoimentos
        if ($depoimentos->have_posts()) :
          while ($depoimentos->have_posts()) : $depoimentos->the_post();
            // Obtendo os campos personalizados
            $avaliacao = get_post_meta(get_the_ID(), '_avaliacao', true);
            $cliente = get_post_meta(get_the_ID(), '_cliente', true);
            $empresa = get_post_meta(get_the_ID(), '_empresa', true);
            $cargo = get_post_meta(get_the_ID(), '_cargo', true);
        ?>

            <div class="col-12 col-md-4 mx-auto">
              <div class="card border-0 border-bottom border-primary rounded-0 shadow-sm">
                <div class="card-body p-4 p-xxl-5">
                  <figure>
                    <?php
                    // Recupera a URL da imagem destacada, com um fallback caso não haja imagem
                    $imagem_cliente = get_the_post_thumbnail_url(get_the_ID(), 'full') ? get_the_post_thumbnail_url(get_the_ID(), 'full') : get_bloginfo('template_url') . '/assets/images/testimonial-img-default.jpg';
                    ?>
                    <img class="img-fluid rounded rounded-circle mb-4 border border-5"
                      loading="lazy"
                      src="<?php echo esc_url($imagem_cliente); ?>"
                      alt="<?php echo esc_attr($cliente); ?>">
                    <figcaption>
                      <div class="bsb-ratings text-warning mb-3" data-bsb-star="<?php echo esc_attr($avaliacao); ?>" data-bsb-star-off="0"></div>
                      <blockquote class="bsb-blockquote-icon mb-4">
                        <i class="bi bi-quote gy-4" style="font-size: 2.5rem;"></i>
                        <?php the_content(); ?>
                      </blockquote>
                      <h4 class="mb-2"><?php echo esc_html($cliente); ?></h4>
                      <h5 class="fs-6 text-secondary mb-0"><?php echo esc_html($cargo); ?>, <?php echo esc_html($empresa); ?></h5>
                    </figcaption>
                  </figure>
                </div>
              </div>
            </div>
        <?php
          endwhile;
          wp_reset_postdata(); // Reseta os dados da query
        else :
          echo '<p>' . __('No testimonials found.', 'tecnoinfor') . '</p>';
        endif;
        ?>

      </div>
    </div>
  </section>

  <!---Seção Configurar Licença--->
  <?php
  // Inclui o conteúdo do header puxado do arquivo template-parts/content-licencaconfig.php
  get_template_part('template-parts/content', 'licencaconfig');
  ?>


  <!-- Seção Perguntas Frequentes -->
  <?php
  // Inclui o conteúdo do header puxado do arquivo template-parts/content-faqs.php
  get_template_part('template-parts/content', 'faqs');
  ?>

  <!-- Seção CTA -->
  <section class="cta text-center py-5 bg-primary-subtle">
    <div class="container pb-3">
      <h3 class="display-6 fw-bold">Pronto para simplificar sua gestão?</h3>
      <p class="lead py-3">Experimente nosso sistema por 15 dias e veja como ele pode ajudar no seu dia a dia.</p>
      <a href="<?php echo get_bloginfo('url'); ?>/sisplan" class="btn btn-primary btn-lg me-md-2 py-3 px-5">Experimente grátis</a>
    </div>
  </section>
  <!-- Seção de Notícias -->
  <section class="news-section py-5">
    <div class="container">
      <h2 class="text-center mb-5 fw-bold text-primary"><?php __('Latest News','tecnoinfor');?></h2>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php
        $noticias = new WP_Query(['post_type' => 'post', 'posts_per_page' => 6]);
        if ($noticias->have_posts()) :
          while ($noticias->have_posts()) : $noticias->the_post();
            // Verificar se o post tem thumbnail, caso contrário usa imagem padrão
            if (has_post_thumbnail()) {
              $imagem = get_the_post_thumbnail(get_the_ID(), 'post_thumb', ['class' => 'card-img-top img-fluid']);
            } else {
              // Caso não tenha, usa a imagem padrão
              $imagem = '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/post_thumb_default.jpg') . '" class="card-img-top img-fluid" alt="Default Image">';
            }
            $data = get_the_date();
            $categoria = get_the_category_list(', ');
        ?>
            <div class="col">
              <div class="card h-100 border-0">
                <div class="rounded position-relative overflow-hidden">
                  <?php echo $imagem; // Exibe a imagem do post ou a imagem padrão 
                  ?>
                  <!-- Badge de Categoria e Data -->
                  <div class="position-absolute top-0 start-0 bg-dark text-white p-2 m-2 rounded">
                    <small class="text-uppercase"><?php echo $categoria; ?></small>
                  </div>
                </div>
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title fw-bold text-dark"><?php the_title(); ?></h5>
                  <p class="card-text mb-4 text-muted"><?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?></p>
                  <a href="<?php the_permalink(); ?>" class="btn btn-primary mt-auto align-self-start"><?php echo __('read more','tecnoinfor');?></a>
                </div>
              </div>
            </div>
        <?php
          endwhile;
          wp_reset_postdata();
        else :
          echo '<p class="text-center">Nenhuma notícia encontrada.</p>';
        endif;
        ?>
      </div>

      <div class="container text-center mt-4">
        <a href="<?php echo get_bloginfo('url'); ?>/blog" class="btn btn-outline-primary" title="<?php echo __('View All Posts','tecnoinfor');?>"><?php echo __('Ver todas as postagens','tecnoinfor');?></a>
      </div>
    </div>
  </section>


  <!-- Seção Comunidade -->
  <section class="comunidade wow fadeIn py-5">
    <div class="container">
      <h3 class="display-6 fw-bold text-center mb-4 py-5">Junte-se à nossa comunidade</h3>
      <div class="row g-4 row-cols-3 row-cols-lg-5 justify-content-center">
        <?php
        $social_links = [
          ["icon" => "bi-instagram", "url" => "#", "label" => "Instagram"],
          ["icon" => "bi-twitter", "url" => "#", "label" => "Twitter"],
          ["icon" => "bi-facebook", "url" => "#", "label" => "Facebook"],
          ["icon" => "bi-youtube", "url" => "#", "label" => "Youtube"],
          ["icon" => "bi-linkedin", "url" => "#", "label" => "Linkedin"]
        ];

        foreach ($social_links as $social) {
          echo "<a href='{$social['url']}' class='feature p-4 text-center text-decoration-none' target='_blank' rel='noopener noreferrer' aria-label='{$social['label']}'>
                <div class='feature-icon d-inline-flex align-items-center justify-content-center bg-primary-subtle fs-2 p-2 px-3 mb-3 rounded'>
                  <i class='{$social['icon']} text-primary'></i>
                </div>
              </a>";
        }
        ?>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>