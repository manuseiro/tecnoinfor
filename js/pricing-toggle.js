document.addEventListener('DOMContentLoaded', function() {
    // Logic for single-software.php
    const toggle = document.getElementById('toggle-software-pricing');
    const mensalPrices = document.querySelectorAll('.preco-mensal-soft');
    const anualPrices = document.querySelectorAll('.preco-anual-soft');
    if (toggle) {
        toggle.addEventListener('change', function() {
            const isAnual = this.checked;
            mensalPrices.forEach(price => price.style.display = isAnual ? 'none' : 'block');
            anualPrices.forEach(price => price.style.display = isAnual ? 'block' : 'none');
        });
    }

    // Logic for templates/page-pricing.php
    const switches = document.querySelectorAll('.toggle-pricing-switch');
    if (switches.length > 0) {
        switches.forEach(sw => {
            sw.addEventListener('change', function() {
                const paneId = this.getAttribute('data-target');
                const isAnual = this.checked;
                const mensalPricesPane = document.querySelectorAll('.preco-mensal-' + paneId);
                const anualPricesPane = document.querySelectorAll('.preco-anual-' + paneId);
                
                mensalPricesPane.forEach(price => price.style.display = isAnual ? 'none' : 'block');
                anualPricesPane.forEach(price => price.style.display = isAnual ? 'block' : 'none');
            });
        });
    }
});
