<?php get_header(); ?>
<main class="main-content">
<?php 
// Inclui o conteúdo do header puxado do arquivo template-parts/content-header.php
get_template_part('template-parts/content', 'header'); 
?>

  <!-- Conteúdo da página -->
  <div class="container my-5 p-5">
    <div class="row">
      <div class="col-lg-12">
        <div class="editor-wp">
          <?php
            while ( have_posts() ) : the_post();
          ?>

          <!-- Início do formulário de múltiplas etapas -->
          <form id="licenseForm">
              <!-- Etapa 1: Quantos usuários -->
              <div class="step active" id="step-1">
                  <div class="mb-3">
                      <label class="form-label">Quantos usuários devem ter uma conta Tecnoinfor?</label>
                      <div class="form-check">
                          <input type="radio" class="form-check-input" name="users" value="Usuário Único" id="user-1" required>
                          <label class="form-check-label" for="user-1">Usuário Único (Apenas para um usuário acessar o sistema para minha empresa)</label>
                      </div>
                      <div class="form-check">
                          <input type="radio" class="form-check-input" name="users" value="Equipe pequena" id="user-2">
                          <label class="form-check-label" for="user-2">Equipe pequena (Para uma equipe de até 5 membros)</label>
                      </div>
                      <div class="form-check">
                          <input type="radio" class="form-check-input" name="users" value="Equipe grande" id="user-3">
                          <label class="form-check-label" for="user-3">Equipe grande (Para uma equipe de até 10 membros)</label>
                      </div>
                      <div class="form-check">
                          <input type="radio" class="form-check-input" name="users" value="Necessidades personalizadas" id="user-4">
                          <label class="form-check-label" for="user-4">Necessidades personalizadas (Para uma equipe com mais de 10 membros)</label>
                      </div>
                  </div>
              </div>

              <!-- Etapa 2: Quantas empresas -->
              <div class="step" id="step-2">
                  <div class="mb-3">
                      <label class="form-label">Quantas empresas deseja cadastrar?</label>
                      <div class="form-check">
                          <input type="radio" class="form-check-input" name="companies" value="1" id="company-1" required>
                          <label class="form-check-label" for="company-1">1 (Uma empresa)</label>
                      </div>
                      <div class="form-check">
                          <input type="radio" class="form-check-input" name="companies" value="2-5" id="company-2">
                          <label class="form-check-label" for="company-2">2 - 5 (Entre duas e cinco empresas)</label>
                      </div>
                      <div class="form-check">
                          <input type="radio" class="form-check-input" name="companies" value="5-10" id="company-3">
                          <label class="form-check-label" for="company-3">5 - 10 (Entre cinco e dez empresas)</label>
                      </div>
                  </div>
              </div>

              <!-- Etapa 3: Recursos extras -->
              <div class="step" id="step-3">
                  <div class="mb-3">
                      <label class="form-label">Você precisa de algum dos recursos abaixo?</label>
                      <div class="form-check">
                          <input type="checkbox" class="form-check-input" name="features[]" value="Suporte por Telefone" id="feature-1">
                          <label class="form-check-label" for="feature-1">Suporte por Telefone</label>
                      </div>
                      <div class="form-check">
                          <input type="checkbox" class="form-check-input" name="features[]" value="Treinamento de Equipe" id="feature-2">
                          <label class="form-check-label" for="feature-2">Treinamento de Equipe</label>
                      </div>
                      <div class="form-check">
                          <input type="checkbox" class="form-check-input" name="features[]" value="Atualizações Mensais" id="feature-3">
                          <label class="form-check-label" for="feature-3">Atualizações Mensais</label>
                      </div>
                      <div class="form-check">
                          <input type="checkbox" class="form-check-input" name="features[]" value="Migração de sistema" id="feature-4">
                          <label class="form-check-label" for="feature-4">Migração de sistema</label>
                      </div>
                      <div class="form-check">
                          <input type="checkbox" class="form-check-input" name="features[]" value="Nenhum dos mencionados" id="feature-5">
                          <label class="form-check-label" for="feature-5">Nenhum dos mencionados</label>
                      </div>
                  </div>
              </div>

              <!-- Etapa 4: Resultado -->
              <div class="step" id="step-4">
                  <h3>Nossa principal recomendação de licença para você:</h3>
                  <p id="licenseRecommendation">Preencha todas as etapas para ver a recomendação.</p>
              </div>

              <!-- Navegação -->
              <div class="form-navigation d-flex justify-content-between mt-4">
                  <button type="button" class="btn btn-secondary" id="prevBtn" onclick="nextStep(-1)" disabled>Anterior</button>
                  <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextStep(1)">Próximo</button>
              </div>
          </form>

          <?php endwhile; ?>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Script JavaScript para navegação e validação do formulário -->
<script>
    let currentStep = 1;

    // Exibe a etapa correspondente
    function showStep(step) {
        document.querySelectorAll('.step').forEach((el, index) => {
            el.classList.remove('active');
            if (index === step - 1) el.classList.add('active');
        });

        document.getElementById("prevBtn").disabled = step === 1;
        document.getElementById("nextBtn").textContent = step === 4 ? "Ver Recomendação" : "Próximo";
    }

    // Controla as etapas do formulário e chama a recomendação
    function nextStep(n) {
        if (n === 1 && !validateForm()) return false;

        // Atualiza a etapa atual
        currentStep += n;
        if (currentStep > 4) {
            showRecommendation();
            return;
        }
        showStep(currentStep);
    }

    // Valida se os campos obrigatórios foram preenchidos
    function validateForm() {
        let fields = document.querySelectorAll(`#step-${currentStep} input`);
        let valid = Array.from(fields).some(input => input.checked || input.value);

        if (!valid) alert("Selecione uma opção para prosseguir.");
        return valid;
    }

    // Mostra a recomendação baseada nas seleções
    function showRecommendation() {
        let users = document.querySelector('input[name="users"]:checked').value;
        let companies = document.querySelector('input[name="companies"]:checked').value;
        let features = Array.from(document.querySelectorAll('input[name="features[]"]:checked'))
                            .map(f => f.value)
                            .join(", ");

        let recommendation = `Licença recomendada: \nUsuários: ${users}, Empresas: ${companies}, Recursos: ${features}`;
        document.getElementById("licenseRecommendation").textContent = recommendation;
    }

    // Inicializa a primeira etapa ao carregar a página
    document.addEventListener("DOMContentLoaded", function() {
        showStep(currentStep);
    });
</script>


<?php get_footer(); ?>
