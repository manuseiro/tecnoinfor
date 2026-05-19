<?php
function wp_custom_maintenance() {
     // Verifique se o usuário está logado ou possui acesso de administrador
     if (is_user_logged_in() || current_user_can('manage_options')) {
         return;
     }

     // Processamento do formulário de e-mail se enviado
     $message = '';
     if (isset($_POST['email'])) {
         $email = sanitize_email($_POST['email']);
         if (is_email($email)) {
             $to = get_option('admin_email');
             $subject = 'Nova inscricao na pagina de manutencao - Tecnoinfor';
             $body = 'Um novo usuario se inscreveu na pagina de manutencao para receber atualizacoes.<br><br><strong>E-mail:</strong> ' . esc_html($email);
             $headers = array('Content-Type: text/html; charset=UTF-8');
             wp_mail($to, $subject, $body, $headers);
             $message = '<div class="alert alert-success mt-3" role="alert">Obrigado! Seu e-mail foi cadastrado com sucesso.</div>';
         } else {
             $message = '<div class="alert alert-danger mt-3" role="alert">E-mail inválido. Por favor, tente novamente.</div>';
         }
     }

     // Defina os estilos e scripts necessários
     echo '
     <!DOCTYPE html>
     <html lang="pt-br">
    <head>
         <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <title>Estamos em Manutenção - Em breve de volta!</title>
         <!-- Bootstrap CSS -->
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
         <!-- Bootstrap Icons -->
         <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
         <style>
             body {
                 background-color: #f4f4f9;
                 color: #333;
                 display: flex;
                 align-items: center;
                 justify-content: center;
                 min-height: 100vh;
                 text-align: center;
             }
             .countdown-timer {
                 font-size: 1.5rem;
                 margin: 20px 0;
             }
         </style>
     </head>
     <body>
         <div class="container" style="max-width: 500px;">
            <h1 class="mt-5">Estamos em manutenção!</h1>
             <p class="lead">Estamos trabalhando em melhorias e estaremos de volta em breve.</p>

             <!-- Contador regressivo -->
             <div id="countdown" class="countdown-timer"></div>

             ' . $message . '

             <!-- Formulário de inscrição -->
             <form id="signup-form" class="mt-3" method="POST" action="">
                 <div class="mb-3">
                     <input type="email" class="form-control" name="email" placeholder="Digite seu e-mail" required>
                 </div>
                 <button type="submit" class="btn btn-primary w-100">Inscreva-se</button>
             </form>

             <!-- Ícones de mídia social -->
             <div class="social-icons mt-4 d-flex justify-content-center gap-3">
                 <a href="#" aria-label="Facebook"><i class="bi bi-facebook fs-4"></i></a>
                 <a href="#" aria-label="Instagram"><i class="bi bi-instagram fs-4"></i></a>
                 <a href="#" aria-label="YouTube"><i class="bi bi-youtube fs-4"></i></a>
             </div>
         </div>

         <!-- Scripts -->
         <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
         <script>
             // Contador regressivo
             function countdownTimer() {
                 // Data configurável — ajuste conforme necessário
                 const countdownDate = new Date("Dec 31, 2025 23:59:59").getTime();
                 const el = document.getElementById("countdown");
                 if (!el) return;
                 const interval = setInterval(function () {
                     const now = new Date().getTime();
                     const distance = countdownDate - now;

                     if (distance < 0) {
                         clearInterval(interval);
                         el.innerHTML = "Estamos de volta!";
                         return;
                     }

                     const days    = Math.floor(distance / (1000 * 60 * 60 * 24));
                     const hours   = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                     const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                     const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                     el.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
                 }, 1000);
             }
            countdownTimer();

         </script>
    </body>
     </html>
     ';
     exit();
 }
add_action('template_redirect', 'wp_custom_maintenance');