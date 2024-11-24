<?php 
/*
Template Name: Pricing Page
*/
get_header(); 
?>
<main class="main-content">
  <?php get_template_part('template-parts/content', 'header'); ?>
    <!-- Seção de Toggle para Mensal/Anual -->
  <div class="container py-5">
    <div class="col-4 text-center mb-4">
        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
          <label class="form-check-label" for="flexSwitchCheckDefault" id="billingLabel">Mensal</label> / <span>Anual</span>
        </div>
      </div>

    <!-- Seção de Comparação de Planos -->
    <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
      <!-- Plano Gratuito -->
      <div class="col">
        <div class="card mb-4 rounded-3 shadow-sm">
          <div class="card-header py-3">
            <h4 class="my-0 fw-normal">Gratuito</h4>
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title">R$ 0 <small class="text-body-secondary fw-light">/mês</small></h1>
            <p class="card-text">Ideal para pequenos negócios que estão começando.</p>
            <ul class="list-unstyled mt-3 mb-4">
              <li>1 usuário</li>
              <li>1 Empresa</li>
              <li>15 Contratos</li>
              <li>15 dias (teste)</li>
            </ul>
            <a href="#" class="w-100 btn btn-lg btn-outline-primary">Baixar Gratuito</a>
          </div>
        </div>
      </div>

      <!-- Plano Profissional -->
      <div class="col">
        <div class="card mb-4 rounded-3 shadow-sm">
        <div class="card-header py-3">
            <h4 class="my-0 fw-normal">Profissional</h4>
          </div>
          <div class="card-body">
          <h1 class="card-title pricing-card-title monthly">R$ 0 <small class="text-body-secondary fw-light">/mês</small></h1>
          <h1 class="card-title pricing-card-title annually" style="display: none;">R$ 0 <small class="text-body-secondary fw-light">/ano</small></h1>
            <p class="card-text">Para empresas em crescimento que precisam de mais recursos.</p>
            <button class="w-100 btn btn-lg btn-primary">Entrar em Contato</button>
          </div>
        </div>
      </div>

      <!-- Plano Enterprise -->
      <div class="col">
        <div class="card mb-4 rounded-3 shadow-sm">
          <div class="card-header bg-light">
            <h3>Enterprise</h3>
          </div>
          <div class="card-body">
          <h1 class="card-title pricing-card-title monthly">R$ 0 <small class="text-body-secondary fw-light">/mês</small></h1>
          <h1 class="card-title pricing-card-title annually" style="display: none;">R$ 0 <small class="text-body-secondary fw-light">/ano</small></h1>
            <p class="card-text">Para grandes empresas que precisam de soluções completas e personalizadas.</p>
            <button class="w-100 btn btn-lg btn-primary">Entrar em Contato</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabela de Diferenças -->
    <div class="pricing-comparison">
      <h2 class="text-center mb-4">Principais Diferenças entre as Versões</h2>
      <div class="table-responsive">
        <table class="table table-bordered text-center">
          <thead class="bg-light">
            <tr>
              <?php
              // Obter as diferenças e mostrar o cabeçalho
              $plan_differences = get_plan_differences();
              foreach ($plan_differences[0] as $header) {
                  echo "<th>{$header}</th>";
              }
              ?>
            </tr>
          </thead>
          <tbody>
            <?php
            // Exibir cada linha da tabela com base nas diferenças
            for ($i = 1; $i < count($plan_differences); $i++) {
                echo "<tr>";
                foreach ($plan_differences[$i] as $value) {
                    echo "<td>{$value}</td>";
                }
                echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<!-- Script para alternar entre mensal e anual -->
<script>
document.getElementById("flexSwitchCheckDefault").addEventListener("change", function() {
  const isAnnually = this.checked;
  document.getElementById("billingLabel").textContent = isAnnually ? "Anual" : "Mensal";
  
  document.querySelectorAll(".monthly").forEach(function(el) {
    el.style.display = isAnnually ? "none" : "inline";
  });
  document.querySelectorAll(".annually").forEach(function(el) {
    el.style.display = isAnnually ? "inline" : "none";
  });
});
</script>

<style>
/* Estilos para o switch */
.form-check-input {
  width: 60px!important;
  height: 34px;
}

.form-check-input:checked {
  background-color: #2196F3;
}

.form-check-label {
  font-size: 1.1em;
}
</style>

<?php get_footer(); ?>
