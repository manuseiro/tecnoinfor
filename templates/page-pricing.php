<?php 
/*
Template Name: Pricing Page 2
*/
get_header(); 
?>

<main class="main-content">
    <?php get_template_part('template-parts/content', 'header'); ?>

    <div class="container my-5 p-5">
        <header>
            <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
                <p class="fs-5 text-body-secondary">
                    Escolha o plano ideal para suas necessidades. Alternar entre mensal e anual para melhores ofertas.
                </p>
            </div>
        </header>

        <!-- Toggle Switch Mensal/Anual -->
        <div class="text-center mb-4">
            <label class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="toggle-pricing">
                <span class="ms-2">Cobrança Anual</span>
            </label>
        </div>

        <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
            <?php 
            $args = array(
                'post_type'      => 'planos',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
            );
            $query = new WP_Query($args);

            if ($query->have_posts()) :
                while ($query->have_posts()) : $query->the_post();
                    $preco_mensal = floatval(get_post_meta(get_the_ID(), 'preco', true));
                    if (empty($preco_mensal)) {
                        $preco_mensal = 0; // Definir valor padrão caso o preço mensal esteja ausente
                    }
                    
                    $preco_anual  = $preco_mensal * 12 * 0.9; // 10% de desconto no anual
                    $funcionalidades = get_post_meta(get_the_ID(), 'funcionalidades', true);
$funcionalidades_array = maybe_unserialize($funcionalidades);
                    $classe_botao = get_post_meta(get_the_ID(), 'classe_botao', true);
                    $classe_botao = !empty($classe_botao) ? $classe_botao : 'btn-primary';
                    $texto_botao = get_post_meta(get_the_ID(), 'texto_botao', true);
                    $texto_botao = !empty($texto_botao) ? $texto_botao : 'Assinar';
                    $desconto = get_post_meta(get_the_ID(), 'desconto', true);
                    $preco_anual_com_desconto = !empty($desconto) ? $preco_anual * ((100 - $desconto) / 100) : $preco_anual;
                    ?>
            <div class="col">
                <div class="card mb-4 rounded-3 shadow-sm">
                    <div class="card-header py-3">
                        <h4 class="my-0 fw-normal"><?php the_title(); ?></h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">
                            <div class="preco-mensal">
                                <small>R$</small> <?php echo number_format($preco_mensal, 2, ',', '.'); ?>
                                <small class="text-body-secondary fw-light">/mês</small>
                            </div>
                            <div class="preco-anual" style="display: none;">
                                <div class="d-flex">
                                    <small>R$</small>
                                    <?php echo number_format($preco_anual_com_desconto, 2, ',', '.'); ?>
                                    <small class="text-body-secondary fw-light">/ano</small>
                                </div>
                                <div class="d-flex">
                                    <?php if (!empty($desconto)) : ?>
                                    <div class="text-body-danger fw-bolder text-danger">
                                        <?php echo esc_html($desconto); ?>% Off</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </h1>
                        <?php
                        
                        if (!empty($funcionalidades_array) && is_array($funcionalidades_array)) :
                            echo '<ul class="mt-3 mb-4 text-start list-none">';
                            foreach ($funcionalidades_array as $funcionalidade) :
                                if (!empty($funcionalidade['nome'])) {
                                    echo '<li>' . esc_html($funcionalidade['nome']) . '</li>';
                                }
                            endforeach;
                            echo '</ul>';
                        endif;
                        ?>

                        <a href="#"
                            class="w-100 btn btn-lg <?php echo esc_attr($classe_botao); ?>"><?php echo esc_attr($texto_botao); ?></a>
                    </div>
                </div>
            </div>

            <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>

        <h2 class="display-6 text-center mb-4">Comparar Planos</h2>

        <?php
// Buscar todos os planos
$planos = new WP_Query(array(
    'post_type' => 'planos',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
));

// Inicializa arrays para armazenar dados
$nomes_planos = [];
$dados_funcionalidades = [];
$melhor_plano = 1; // Índice do plano recomendado (pode ser configurado dinamicamente)

// Criar um array com todas as funcionalidades únicas
$funcionalidades_unicas = [];

if ($planos->have_posts()) :
    while ($planos->have_posts()) : $planos->the_post();
        $nomes_planos[] = get_the_title();
        
        // Pegando funcionalidades separadas por vírgulas
        $funcionalidades = get_post_meta(get_the_ID(), 'funcionalidades', true);
        $funcionalidades_array = !empty($funcionalidades) ? explode(',', $funcionalidades) : [];

        // Adiciona funcionalidades ao array de funcionalidades únicas
        foreach ($funcionalidades_array as $funcionalidade) {
            $funcionalidade = trim($funcionalidade);
            if (!in_array($funcionalidade, $funcionalidades_unicas)) {
                $funcionalidades_unicas[] = $funcionalidade;
            }
        }
    endwhile;
    wp_reset_postdata();
endif;

// Inicializa a matriz de comparação
foreach ($funcionalidades_unicas as $funcionalidade) {
    $dados_funcionalidades[$funcionalidade] = array_fill(0, count($nomes_planos), '<i class="bi bi-x text-danger" data-bs-toggle="tooltip" title="Não disponível"></i>');
}

// Preencher os valores com check nos planos corretos
if ($planos->have_posts()) :
    $index = 0;
    while ($planos->have_posts()) : $planos->the_post();
        $funcionalidades = get_post_meta(get_the_ID(), 'funcionalidades', true);
        $funcionalidades_array = !empty($funcionalidades) ? explode(',', $funcionalidades) : [];

        foreach ($funcionalidades_array as $funcionalidade) {
            $funcionalidade = trim($funcionalidade);
            $dados_funcionalidades[$funcionalidade][$index] = '<i class="bi bi-check2 text-success" data-bs-toggle="tooltip" title="Incluído neste plano"></i>';
        }

        $index++;
    endwhile;
    wp_reset_postdata();
endif;
echo '<pre>';
print_r($dados_funcionalidades);
echo '</pre>';
?>

        <div class="table-responsive" style="overflow-x: auto;">
            <table class="table text-center table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th
                            style="width: 34%; position: sticky; left: 0; background: #343a40; color: white; z-index: 2;">
                            Funcionalidade
                        </th>
                        <?php foreach ($nomes_planos as $index => $plano) : ?>
                        <th
                            style="width: <?php echo 66 / count($nomes_planos); ?>%; <?php echo $index == $melhor_plano ? 'background-color: #ffc107; color: #000;' : ''; ?>">
                            <?php echo esc_html($plano); ?>
                        </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dados_funcionalidades as $funcionalidade => $checks) : ?>
                    <tr>
                        <th scope="row" class="text-start"
                            style="position: sticky; left: 0; background: #f8f9fa; z-index: 1;">
                            <?php echo esc_html($funcionalidade); ?>
                        </th>
                        <?php foreach ($checks as $check) : ?>
                        <td><?php echo $check; ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(() => {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll(
                    '[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }, 500);
        });
        </script>


    </div>
</main>

<script>
document.getElementById('toggle-pricing').addEventListener('change', function() {
    const isAnual = this.checked;
    document.querySelectorAll('.preco-mensal').forEach(span => {
        span.style.display = isAnual ? 'none' : 'inline-flex';
    });
    document.querySelectorAll('.preco-anual').forEach(span => {
        span.style.display = isAnual ? 'inline-flex' : 'none';
    });
});
</script>

<?php get_footer(); ?>