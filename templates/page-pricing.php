<?php 
/**
 * Template Name: Pricing Page
 *
 * Exibe uma página de preços para os planos registrados no CPT 'planos'. Inclui um banner com imagem
 * destacada ou padrão, seções organizadas por abas para cada software cadastrado com seus respectivos
 * planos (mensais/anuais) e tabela de comparação de funcionalidades correspondente.
 *
 * @package Tecnoinfor
 * @since 1.0.0
 */

get_header(); 

/**
 * Função auxiliar interna para renderizar o painel de planos de um determinado grupo.
 */
function tecnoinfor_render_pricing_plans_pane($pane_id, $software_id = null, $is_active = false) {
    $meta_query = array();
    if ($software_id) {
        $meta_query[] = array(
            'key' => '_plano_software_id',
            'value' => $software_id,
            'compare' => '='
        );
    } else {
        $meta_query[] = array(
            'relation' => 'OR',
            array(
                'key' => '_plano_software_id',
                'compare' => 'NOT EXISTS'
            ),
            array(
                'key' => '_plano_software_id',
                'value' => '',
                'compare' => '='
            )
        );
    }

    $args = array(
        'post_type' => 'planos',
        'posts_per_page' => 20,
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'meta_query' => $meta_query
    );
    $query = new WP_Query($args);
    ?>
    <div class="tab-pane fade <?php echo $is_active ? 'show active' : ''; ?>" id="tab-<?php echo $pane_id; ?>" role="tabpanel" aria-labelledby="tab-<?php echo $pane_id; ?>-btn">
        <?php if ($query->have_posts()) : ?>
            <div class="text-center mb-5">
                <label class="form-check form-switch d-inline-block">
                    <input class="form-check-input toggle-pricing-switch" type="checkbox" data-target="<?php echo $pane_id; ?>">
                    <span class="ms-2 fw-semibold text-muted"><?php esc_html_e('Cobrança Anual (com desconto)', 'tecnoinfor'); ?></span>
                </label>
            </div>

            <div class="row row-cols-1 row-cols-md-3 g-4 mb-5 text-center justify-content-center">
                <?php
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

                    // Dynamic Contact URL with Plan Details
                    $contact_url = add_query_arg(array(
                        'assunto' => sprintf(__('Interesse no plano %s (%s)', 'tecnoinfor'), get_the_title(), $software_id ? get_the_title($software_id) : __('Geral', 'tecnoinfor'))
                    ), home_url('/contato/'));
                ?>
                    <div class="col">
                        <div class="card h-100 rounded-3 shadow-sm border <?php echo $is_recommended ? "border-2 border-$highlight_color" : 'border-light'; ?> transition-hover">
                            <?php if ($is_recommended) : ?>
                                <div class="card-header py-3 text-white bg-<?php echo esc_attr($highlight_color); ?> border-0">
                                    <h4 class="my-0 fw-bold fs-5"><?php esc_html_e('Recomendado', 'tecnoinfor'); ?></h4>
                                </div>
                            <?php else : ?>
                                <div class="card-header py-3 bg-light border-0">
                                    <h4 class="my-0 fw-normal fs-5"><?php the_title(); ?></h4>
                                </div>
                            <?php endif; ?>
                            <div class="card-body p-4 d-flex flex-column">
                                <h3 class="card-title pricing-card-title mb-4">
                                    <?php if ($is_free) : ?>
                                        <span class="fs-2 fw-bold text-success"><?php esc_html_e('Grátis', 'tecnoinfor'); ?></span>
                                    <?php else : ?>
                                        <div class="preco-mensal-<?php echo $pane_id; ?>">
                                            <span class="fs-1 fw-bold">R$ <?php echo number_format($preco_mensal, 2, ',', '.'); ?></span>
                                            <small class="text-muted fw-light">/mês</small>
                                        </div>
                                        <div class="preco-anual-<?php echo $pane_id; ?>" style="display: none;">
                                            <span class="fs-1 fw-bold">R$ <?php echo number_format($preco_anual, 2, ',', '.'); ?></span>
                                            <small class="text-muted fw-light">/ano</small>
                                            <?php if ($desconto) : ?>
                                                <div class="badge bg-danger mt-2"><?php echo esc_html($desconto); ?>% OFF</div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </h3>
                                <ul class="list-unstyled text-start mb-4 flex-grow-1">
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <strong><?php echo $max_users ?: 'Ilimitado'; ?></strong> <?php esc_html_e('usuários', 'tecnoinfor'); ?>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <strong><?php echo $max_companies ?: 'Ilimitado'; ?></strong> <?php esc_html_e('empresas', 'tecnoinfor'); ?>
                                    </li>
                                    <?php foreach ($funcionalidades as $func) : ?>
                                        <?php if (!empty($func['nome'])) : ?>
                                            <li class="mb-2">
                                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                <?php echo !empty($func['no_quantity']) ? esc_html($func['nome']) : esc_html($func['quantidade'] . ' ' . $func['nome']); ?>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                                <a href="<?php echo esc_url($contact_url); ?>" class="w-100 btn btn-lg py-3 rounded-pill <?php echo esc_attr($classe_botao); ?>"><?php echo esc_html($texto_botao); ?></a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>

            <!-- Comparação de Planos -->
            <h3 class="display-7 fw-bold text-primary text-center mb-4 mt-5"><?php esc_html_e('Compare Plans', 'tecnoinfor'); ?></h3>
            <?php
            $query->rewind_posts();
            $nomes_planos = [];
            $dados_funcionalidades = [];
            $funcionalidades_unicas = [];

            while ($query->have_posts()) : $query->the_post();
                $nomes_planos[] = get_the_title();
                $funcionalidades = maybe_unserialize(get_post_meta(get_the_ID(), 'funcionalidades', true)) ?: [];
                foreach ($funcionalidades as $func) {
                    if (!empty($func['nome']) && !in_array($func['nome'], $funcionalidades_unicas)) {
                        $funcionalidades_unicas[] = $func['nome'];
                    }
                }
            endwhile;

            foreach ($funcionalidades_unicas as $func) {
                $dados_funcionalidades[$func] = array_fill(0, count($nomes_planos), '<i class="bi bi-x-lg text-danger"></i>');
            }

            $index = 0;
            $query->rewind_posts();
            while ($query->have_posts()) : $query->the_post();
                $funcionalidades = maybe_unserialize(get_post_meta(get_the_ID(), 'funcionalidades', true)) ?: [];
                foreach ($funcionalidades as $func) {
                    if (!empty($func['nome']) && in_array($func['nome'], $funcionalidades_unicas)) {
                        $dados_funcionalidades[$func['nome']][$index] = !empty($func['no_quantity']) ? '<i class="bi bi-check-lg text-success fs-4"></i>' : esc_html($func['quantidade']);
                    }
                }
                $index++;
            endwhile;
            wp_reset_postdata();
            ?>

            <div class="table-responsive col-lg-10 mx-auto shadow-sm rounded border mb-5">
                <table class="table text-center table-bordered align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-start py-3" style="position: sticky; left: 0; background: #212529; color: white;"><?php esc_html_e('Features', 'tecnoinfor'); ?></th>
                            <?php foreach ($nomes_planos as $plano) : ?>
                                <th style="width: <?php echo 60 / count($nomes_planos); ?>%;"><?php echo esc_html($plano); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dados_funcionalidades as $func => $values) : ?>
                            <tr>
                                <th scope="row" class="text-start py-3" style="position: sticky; left: 0; background: #f8f9fa;"><?php echo esc_html($func); ?></th>
                                <?php foreach ($values as $value) : ?>
                                    <td><?php echo $value; ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <p class="text-center text-muted"><?php esc_html_e('Nenhum plano cadastrado neste grupo.', 'tecnoinfor'); ?></p>
        <?php endif; ?>
    </div>
    <?php
}
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

  <div class="container my-5 px-4 px-lg-5">
    <header class="pricing-header p-3 pb-md-4 mx-auto text-center">
      <p class="fs-5 text-body-secondary">Escolha o plano ideal para você. Filtre os planos por sistema de forma simples e rápida!</p>
    </header>

    <?php
    // Obter softwares ativos que possuem planos cadastrados
    $softwares_args = array(
        'post_type' => 'software',
        'posts_per_page' => 50,
        'post_status' => 'publish'
    );
    $softwares_query = new WP_Query($softwares_args);
    $softwares_list = [];
    if ($softwares_query->have_posts()) {
        while ($softwares_query->have_posts()) {
            $softwares_query->the_post();
            // Verifica se há pelo menos um plano associado a este software
            $check_plans = new WP_Query(array(
                'post_type' => 'planos',
                'posts_per_page' => 1,
                'meta_query' => array(
                    array(
                        'key' => '_plano_software_id',
                        'value' => get_the_ID(),
                        'compare' => '='
                    )
                )
            ));
            if ($check_plans->have_posts()) {
                $softwares_list[] = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title()
                );
            }
        }
        wp_reset_postdata();
    }

    // Verificar se existem planos gerais (sem vinculo com software)
    $general_plans_check = new WP_Query(array(
        'post_type' => 'planos',
        'posts_per_page' => 1,
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => '_plano_software_id',
                'compare' => 'NOT EXISTS'
            ),
            array(
                'key' => '_plano_software_id',
                'value' => '',
                'compare' => '='
            )
        )
    ));
    $has_general_plans = $general_plans_check->have_posts();
    wp_reset_postdata();
    ?>

    <?php if (!empty($softwares_list) || $has_general_plans) : ?>
      <!-- Abas de seleção de Sistemas/Softwares -->
      <ul class="nav nav-pills justify-content-center mb-5 gap-2" id="pricingTabs" role="tablist">
        <?php 
        $active_tab_set = false;
        if ($has_general_plans) : 
          $active_tab_set = true;
        ?>
          <li class="nav-item" role="presentation">
            <button class="nav-link active px-4 py-3 fw-bold rounded-pill shadow-sm" id="tab-general-btn" data-bs-toggle="pill" data-bs-target="#tab-general" type="button" role="tab" aria-controls="tab-general" aria-selected="true">
              <?php esc_html_e('Planos Gerais', 'tecnoinfor'); ?>
            </button>
          </li>
        <?php endif; ?>

        <?php foreach ($softwares_list as $index => $soft) : 
          $is_active = (!$active_tab_set && $index === 0);
          if ($is_active) $active_tab_set = true;
        ?>
          <li class="nav-item" role="presentation">
            <button class="nav-link <?php echo $is_active ? 'active' : ''; ?> px-4 py-3 fw-bold rounded-pill shadow-sm" id="tab-soft-<?php echo $soft['id']; ?>-btn" data-bs-toggle="pill" data-bs-target="#tab-soft-<?php echo $soft['id']; ?>" type="button" role="tab" aria-controls="tab-soft-<?php echo $soft['id']; ?>" aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>">
              <?php echo esc_html($soft['title']); ?>
            </button>
          </li>
        <?php endforeach; ?>
      </ul>

      <!-- Conteúdo de Planos das Abas -->
      <div class="tab-content" id="pricingTabsContent">
        <?php 
        $active_pane_set = false;
        if ($has_general_plans) : 
          $active_pane_set = true;
          tecnoinfor_render_pricing_plans_pane('general', null, true);
        endif; 

        foreach ($softwares_list as $index => $soft) : 
          $is_active = (!$active_pane_set && $index === 0);
          if ($is_active) $active_pane_set = true;
          tecnoinfor_render_pricing_plans_pane('soft-' . $soft['id'], $soft['id'], $is_active);
        endforeach; 
        ?>
      </div>
    <?php else : ?>
      <p class="text-center text-muted py-5"><?php esc_html_e('Nenhum plano cadastrado ainda.', 'tecnoinfor'); ?></p>
    <?php endif; ?>
  </div>
</main>



<?php get_footer(); ?>