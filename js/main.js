document.addEventListener('DOMContentLoaded', function() {
    console.log('My Theme scripts loaded.');
});

// Biblioteca WOW.js
new WOW({
    offset: 100, // Distância até o topo da página
    duration: 1000, // Duração da animação
    delay: 500, // Atraso antes de iniciar a animação
    mobile: true, // Habilitar em dispositivos móveis
  }).init();
  
  jQuery(document).ready(function($) {
    $('.read-more').on('click keypress', function(e) {
      if (e.type === 'click' || e.which === 13) { // Clique ou tecla Enter
        var $text = $(this).prev('.testimonial-text');
        var fullText = $text.data('full-text');
        var shortText = $text.text();
  
        if ($text.hasClass('expanded')) {
          $text.text(shortText).removeClass('expanded');
          $(this).text('<?php _e('Read more', 'tecnoinfor'); ?>');
        } else {
          $text.text(fullText).addClass('expanded');
          $(this).text('<?php _e('Read less', 'tecnoinfor'); ?>');
        }
      }
    });
  });