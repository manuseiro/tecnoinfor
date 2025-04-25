<?php

/**
 * Template para exibir a página Software.
 *
 * @package Tecnoinfor
 * Template Name: Recomendação de Licença
 */

get_header(); ?>
 <main class="main-content">
   <?php get_template_part('template-parts/content', 'header'); ?>
 
   <div class="container my-5 p-5">
     <div class="row">
       <div class="col-lg-12">
         <div class="editor-wp">
           <?php while (have_posts()) : the_post(); ?>
 
           <h1><?php the_title(); ?></h1>
           <p>Responda às perguntas abaixo para receber uma recomendação de licença personalizada.</p>
 
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
               <h3>Nossa recomendação de licença para você:</h3>
               <p id="licenseRecommendation">Preencha todas as etapas para ver a recomendação.</p>
               <a id="planLink" href="<?php echo get_permalink(get_page_by_path('planos-e-precos')); ?>" class="btn btn-primary mt-3" style="display: none;">Ver detalhes do plano</a>
             </div>
 
             <!-- Navegação -->
             <div class="form-navigation d-flex justify-content-between mt-4">
               <button type="button" class="btn btn-secondary" id="prevBtn" disabled>Anterior</button>
               <button type="button" class="btn btn-primary" id="nextBtn">Próximo</button>
             </div>
           </form>
 
           <?php endwhile; ?>
         </div>
       </div>
     </div>
   </div>
 </main>
 
 <script>
 document.addEventListener('DOMContentLoaded', function() {
   let currentStep = 1;
   const totalSteps = 4;
 
   function showStep(step) {
     document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));
     document.getElementById(`step-${step}`).classList.add('active');
     document.getElementById('prevBtn').disabled = step === 1;
     document.getElementById('nextBtn').textContent = step === totalSteps ? 'Ver Recomendação' : 'Próximo';
   }
 
   function validateForm() {
     const stepElement = document.getElementById(`step-${currentStep}`);
     const radios = stepElement.querySelectorAll('input[type="radio"]');
     if (currentStep <= 2) {
       const isValid = Array.from(radios).some(radio => radio.checked);
       if (!isValid) {
         alert('Selecione uma opção para continuar.');
         return false;
       }
     }
     return true;
   }
 
   function nextStep(direction) {
     if (direction === 1 && !validateForm()) return;
     currentStep += direction;
     if (currentStep < 1) currentStep = 1;
     if (currentStep > totalSteps) {
       showRecommendation();
       return;
     }
     showStep(currentStep);
   }
 
   function showRecommendation() {
     const users = document.querySelector('input[name="users"]:checked')?.value || 'Não informado';
     const companies = document.querySelector('input[name="companies"]:checked')?.value || 'Não informado';
     const features = Array.from(document.querySelectorAll('input[name="features[]"]:checked'))
       .map(f => f.value)
       .filter(f => f !== 'Nenhum dos mencionados') || [];
 
     // Lógica simples de recomendação baseada nas respostas
     let recommendedPlan = 'Plano Básico'; // Padrão
     if (users === 'Usuário Único' && companies === '1' && features.length === 0) {
       recommendedPlan = 'Plano Básico';
     } else if (users === 'Equipe pequena' || companies === '2-5') {
       recommendedPlan = 'Plano Intermediário';
     } else if (users === 'Equipe grande' || companies === '5-10' || features.length > 1) {
       recommendedPlan = 'Plano Avançado';
     } else if (users === 'Necessidades personalizadas') {
       recommendedPlan = 'Plano Personalizado';
     }
 
     const recommendationText = `Baseado nas suas escolhas:\n- Usuários: ${users}\n- Empresas: ${companies}\n- Recursos: ${features.join(', ') || 'Nenhum selecionado'}\n\nRecomendamos: ${recommendedPlan}`;
     document.getElementById('licenseRecommendation').textContent = recommendationText;
     document.getElementById('planLink').style.display = 'inline-block';
     showStep(totalSteps);
   }
 
   showStep(currentStep);
   document.getElementById('prevBtn').addEventListener('click', () => nextStep(-1));
   document.getElementById('nextBtn').addEventListener('click', () => nextStep(1));
 });
 </script>
 
 <?php get_footer(); ?>