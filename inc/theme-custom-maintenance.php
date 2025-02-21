<?php
function wp_custom_maintenance() {
     // Verifique se o usuário está logado ou possui acesso de administrador
     if (is_user_logged_in() || current_user_can('manage_options')) {
         return;
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
         <div class="container">
            <h1 class="mt-5">Estamos em manutenção!</h1>
             <p class="lead">Estamos trabalhando em melhorias e estaremos de volta em breve.</p>

             <!-- Contador regressivo -->
             <div id="countdown" class="countdown-timer"></div>

             <!-- Formulário de inscrição -->
             <form id="signup-form" class="mt-3" method="POST" action="">
                 <div class="mb-3">
                     <input type="email" class="form-control" name="email" placeholder="Digite seu e-mail" required>
                 </div>
                 <button type="submit" class="btn btn-primary">Inscreva-se</button>
             </form>

             <!-- Ícones de mídia social -->
             <div class="social-icons mt-4">
                 <a href="#"><img src="icone_facebook.png" alt="Facebook"></a>
                 <a href="#"><img src="icone_twitter.png" alt="Twitter"></a>
                 <a href="#"><img src="icone_instagram.png" alt="Instagram"></a>
             </div>
         </div>

         <!-- Scripts -->
         <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
         <script>
             // Contador regressivo
             function countdownTimer() {
                 const countdownDate = new Date("Dec 31, 2024 23:59:59").getTime();
                 const interval = setInterval(function () {
                     const now = new Date().getTime();
                     const distance = countdownDate - now;

                     const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                     const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                     const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                     const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                     document.getElementById("countdown").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

                     if (distance < 0) {
                         clearInterval(interval);
                         document.getElementById("countdown").innerHTML = "Estamos de volta!";
                     }
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