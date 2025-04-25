<?php 
/**
 * Template Name: Pricing Page
 *
 * Exibe uma página de preços para os planos registrados no CPT 'planos'. Inclui um banner com imagem
 * destacada ou padrão, uma seção de cards com preços mensais e anuais, e uma tabela de comparação
 * de funcionalidades. Permite alternar entre preços mensais e anuais via toggle.
 *
 * @package Tecnoinfor
 * @since 1.0.0
 */

get_header(); 
?>

<main class="main-content">
  <?php
  $banner_image = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'full') : get_template_directory_uri() . '/assets/images/default-banner.jpg';
  ?>

  <section class="banner-breadcrumb" style="background: linear-gradient(to bottom, rgba(11, 94, 215, 0.3) 0%, rgba(11, 94, 215, 0.4) 50%, rgba(11, 94, 215, 0.8) 100%), url('<?php echo esc_url($banner_image); ?>'); background-size: cover; background-position: center;">
    <div class="container">
      <div class="row p-5 pb-0 pe-lg-0 py-lg-5 align-items-center">
        <?php echo tecnoinfor_get_breadcrumb(); ?>
        <div class="d-flex flex-column align-items-start text-left">
          <h1 class="display-4 text-white fw-bolder"><?php echo esc_html(get_the_title()); ?></h1>
          <?php if (has_excerpt()) : ?>
            <div class="text-white col-lg-8 page-summary"><?php echo wp_kses_post(get_the_excerpt()); ?></div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <div class="container my-5 p-5">
    <header class="pricing-header p-3 pb-md-4 mx-auto text-center">
      <p class="fs-5 text-body-secondary">Escolha o plano ideal para você. Veja as opções mensais ou anuais com descontos exclusivos!</p>
    </header>

    <div class="text-center mb-4">
      <label class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="toggle-pricing">
        <span class="ms-2">Cobrança Anual (economize até <span id="max-discount"></span>%)</span>
      </label>
    </div>

    <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
      <?php 
      $args = array('post_type' => 'planos', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC');
      $query = new WP_Query($args);
      $max_discount = 0;

      if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
          $preco_mensal = floatval(get_post_meta(get_the_ID(), 'preco', true)) ?: 0;
          $desconto = floatval(get_post_meta(get_the_ID(), 'desconto', true)) ?: 0;
          $preco_anual = $preco_mensal ? $preco_mensal * 12 * (1 - $desconto / 100) : 0;
          $funcionalidades = maybe_unserialize(get_post_meta(get_the_ID(), 'funcionalidades', true)) ?: [];
          $classe_botao = get_post_meta(get_the_ID(), 'classe_botao', true) ?: 'btn-primary';
          $texto_botao = get_post_meta(get_the_ID(), 'texto_botao', true) ?: 'Assinar';
          $is_recommended = get_post_meta(get_the_ID(), 'is_recommended', true);
          $highlight_color = get_post_meta(get_the_ID(), 'highlight_color', true) ?: 'primary';
          $max_users = get_post_meta(get_the_ID(), 'max_users', true);
          $max_companies = get_post_meta(get_the_ID(), 'max_companies', true);
          $is_free = $preco_mensal == 0;
          $max_discount = $is_free ? $max_discount : max($max_discount, $desconto);
          ?>
          <div class="col">
            <div class="card mb-4 rounded-3 shadow-sm <?php echo $is_recommended ? "border-$highlight_color" : ''; ?>">
              <?php if ($is_recommended) : ?>
                <div class="card-header py-3 text-white bg-<?php echo esc_attr($highlight_color); ?>">
                  <h4 class="my-0 fw-normal">Recomendado</h4>
              </div>
              <?php else : ?>
                <div class="card-header py-3"><h4 class="my-0 fw-normal"><?php the_title(); ?></h4></div>
              <?php endif; ?>
              <div class="card-body">
                <h1 class="card-title pricing-card-title">
                  <?php if ($is_free) : ?>
                    Grátis
                  <?php else : ?>
                    <div class="preco-mensal">
                      R$ <?php echo number_format($preco_mensal, 2, ',', '.'); ?> <small class="text-body-secondary fw-light">/mês</small>
                    </div>
                    <div class="preco-anual" style="display: none;">
                      R$ <?php echo number_format($preco_anual, 2, ',', '.'); ?> <small class="text-body-secondary fw-light">/ano</small>
                      <?php if ($desconto) : ?>
                        <div class="text-danger fw-bold"><?php echo esc_html($desconto); ?>% Off</div>
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>
                </h1>
                <ul class="mt-3 mb-4 text-start list-unstyled">
                  <li><strong><?php echo $max_users ?: 'Ilimitado'; ?></strong> usuários</li>
                  <li><strong><?php echo $max_companies ?: 'Ilimitado'; ?></strong> empresas</li>
                  <?php foreach ($funcionalidades as $func) : ?>
                    <?php if (!empty($func['nome'])) : ?>
                      <li><?php echo !empty($func['no_quantity']) ? esc_html($func['nome']) : esc_html($func['quantidade'] . ' ' . $func['nome']); ?></li>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </ul>
                <a href="#" class="w-100 btn btn-lg <?php echo esc_attr($classe_botao); ?>"><?php echo esc_html($texto_botao); ?></a>
              </div>
            </div>
          </div>
          <?php
        endwhile;
        wp_reset_postdata();
      else :
        echo '<p class="text-center">Nenhum plano encontrado.</p>';
      endif;
      ?>
    </div>

    <h2 class="display-6 text-center mb-4">Comparar Planos</h2>
    <?php
    $planos = new WP_Query($args);
    $nomes_planos = [];
    $dados_funcionalidades = [];
    $funcionalidades_unicas = [];

    if ($planos->have_posts()) :
      while ($planos->have_posts()) : $planos->the_post();
        $nomes_planos[] = get_the_title();
        $funcionalidades = maybe_unserialize(get_post_meta(get_the_ID(), 'funcionalidades', true)) ?: [];
        foreach ($funcionalidades as $func) {
          if (!empty($func['nome']) && !in_array($func['nome'], $funcionalidades_unicas)) {
            $funcionalidades_unicas[] = $func['nome'];
          }
        }
      endwhile;
      wp_reset_postdata();

      foreach ($funcionalidades_unicas as $func) {
        $dados_funcionalidades[$func] = array_fill(0, count($nomes_planos), '<i class="bi bi-x text-danger" data-bs-toggle="tooltip" title="Não disponível"></i>');
      }

      $index = 0;
      $planos->rewind_posts();
      while ($planos->have_posts()) : $planos->the_post();
        $funcionalidades = maybe_unserialize(get_post_meta(get_the_ID(), 'funcionalidades', true)) ?: [];
        foreach ($funcionalidades as $func) {
          if (!empty($func['nome']) && in_array($func['nome'], $funcionalidades_unicas)) {
            $dados_funcionalidades[$func['nome']][$index] = !empty($func['no_quantity']) ? '<i class="bi bi-check2 text-success" data-bs-toggle="tooltip" title="Incluído"></i>' : esc_html($func['quantidade']);
          }
        }
        $index++;
      endwhile;
      wp_reset_postdata();
    endif;
    ?>

    <div class="table-responsive" style="overflow-x: auto;">
      <table class="table text-center table-bordered align-middle">
        <thead class="table-dark">
          <tr>
            <th style="position: sticky; left: 0; background: #343a40; color: white; z-index: 2;">Funcionalidade</th>
            <?php foreach ($nomes_planos as $index => $plano) : ?>
              <th style="width: <?php echo 66 / count($nomes_planos); ?>%;"><?php echo esc_html($plano); ?></th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($dados_funcionalidades as $func => $values) : ?>
            <tr>
              <th scope="row" class="text-start" style="position: sticky; left: 0; background: #f8f9fa; z-index: 1;"><?php echo esc_html($func); ?></th>
              <?php foreach ($values as $value) : ?>
                <td><?php echo $value; ?></td>
              <?php endforeach; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const toggle = document.getElementById('toggle-pricing');
  const mensalPrices = document.querySelectorAll('.preco-mensal');
  const anualPrices = document.querySelectorAll('.preco-anual');
  document.getElementById('max-discount').textContent = <?php echo json_encode($max_discount); ?>;
  toggle.disabled = <?php echo json_encode($query->have_posts() && !$max_discount); ?>;

  toggle.addEventListener('change', function() {
    const isAnual = this.checked;
    mensalPrices.forEach(price => price.style.display = isAnual ? 'none' : 'block');
    anualPrices.forEach(price => price.style.display = isAnual ? 'block' : 'none');
  });

  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
  tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
});
</script>

<?php get_footer(); ?>