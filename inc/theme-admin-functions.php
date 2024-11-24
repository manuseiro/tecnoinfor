<?php
// Adicione o menu admin
add_action('admin_menu', 'theme_admin_menu');
function theme_admin_menu()
{
    // Crie o menu principal para o painel do tema
    add_menu_page(
        __('Theme Admin', 'tecnoinfor'),
        __('Theme Admin', 'tecnoinfor'),
        'manage_options',
        'theme-admin',
        'theme_admin_page',
        '',
        2
    );
}

// Função principal para renderizar a página com abas Bootstrap
function theme_admin_page()
{
?>
    <div class="wrap">
        <h1 class="mb-3">Administração do Tema</h1>
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" id="config-tab" data-bs-toggle="tab" href="#config" role="tab">Configurações</a></li>
            <li class="nav-item"><a class="nav-link" id="social-tab" data-bs-toggle="tab" href="#social" role="tab">Redes Sociais</a></li>
            <li class="nav-item"><a class="nav-link" id="address-tab" data-bs-toggle="tab" href="#address" role="tab">Endereço</a></li>
            <li class="nav-item"><a class="nav-link" id="license-tab" data-bs-toggle="tab" href="#license" role="tab">Licença</a></li>
            <li class="nav-item"><a class="nav-link" id="colors-tab" data-bs-toggle="tab" href="#colors" role="tab">Configurações de Cores</a></li>
            <li class="nav-item"><a class="nav-link" id="import-export-tab" data-bs-toggle="tab" href="#import-export" role="tab">Importar/Exportar</a></li>
        </ul>
        <div class="tab-content pt-3">
            <!-- Configurações do Tema -->
            <div class="tab-pane fade show active" id="config" role="tabpanel">
                <form id="config-form">
                    <h2>Configurações</h2>
                    <!-- Campos de formulário -->
                    <button type="submit" class="btn btn-primary mt-3">Salvar Configurações</button>
                </form>
            </div>

            <!-- Redes Sociais -->
            <div class="tab-pane fade" id="social" role="tabpanel">
                <form id="social-form">
                    <h2>Redes Sociais</h2>
                    <!-- Campos de Redes Sociais -->
                    <button type="submit" class="btn btn-primary mt-3">Salvar Redes Sociais</button>
                </form>
            </div>

            <!-- Endereço -->
            <div class="tab-pane fade" id="address" role="tabpanel">
                <form id="address-form">
                    <h2>Contato</h2>
                    <button type="submit" class="btn btn-primary mt-3">Salvar Endereço</button>
                </form>
            </div>

            <!-- Licença -->
            <div class="tab-pane fade" id="license" role="tabpanel">
                <form id="license-form">
                    <h2>Chave & Segurança</h2>
                    <button type="submit" class="btn btn-primary mt-3">Salvar Licença</button>
                </form>
            </div>

            <!-- Configurações de Cores -->
            <div class="tab-pane fade" id="colors" role="tabpanel">
                <form id="color-form">
                    <h2>Cores</h2>
                    <div class="form-control">
                    <label for="theme_primary_color">Cor Primária:</label>
                    <input type="color" id="theme_primary_color" name="theme_primary_color" class="col-2" value="<?php echo esc_attr(get_option('theme_primary_color')); ?>">
                    </div>
                    <div class="form-control">
                    <label for="theme_secondary_color">Cor Secundária:</label>
                    <input type="color" id="theme_secondary_color" name="theme_secondary_color" class="col-2" value="<?php echo esc_attr(get_option('theme_secondary_color')); ?>">
                    </div>
                    
                    <button type="submit" class="btn btn-primary mt-3">Salvar Cores</button>
                </form>
            </div>

            <!-- Importar/Exportar -->
            <div class="tab-pane fade" id="import-export" role="tabpanel">
                <form id="import-export-form">
                    <h2>Importar/Exportar Configurações</h2>
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary mt-3" onclick="exportSettings()">Exportar Configurações</button>
                    </div>

                    <input class="form-control" type="file" id="import_file" name="import_file" accept=".json">
                    <button type="button" class="btn btn-secondary mt-3" onclick="importSettings()">Importar Configurações</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function exportSettings() {
            const settings = {
                theme_description: '<?php echo esc_js(get_option('theme_description')); ?>',
                theme_layout: '<?php echo esc_js(get_option('theme_layout')); ?>',
                body_font: '<?php echo esc_js(get_option('body_font')); ?>',
                header_font: '<?php echo esc_js(get_option('header_font')); ?>',
                background_image: '<?php echo esc_js(get_option('background_image')); ?>',
                theme_primary_color: '<?php echo esc_js(get_option('theme_primary_color')); ?>',
                theme_secondary_color: '<?php echo esc_js(get_option('theme_secondary_color')); ?>',
                // Adicione outros campos conforme necessário
            };

            const blob = new Blob([JSON.stringify(settings, null, 2)], {
                type: 'application/json'
            });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'theme-settings.json';
            link.click();
        }

        function importSettings() {
            const fileInput = document.getElementById('import_file');
            const file = fileInput.files[0];

            if (!file) {
                alert('Por favor, escolha um arquivo para importar.');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const settings = JSON.parse(e.target.result);
                    for (const key in settings) {
                        if (settings.hasOwnProperty(key)) {
                            // Atualize as opções do tema com os dados importados
                            $.post(ajaxurl, {
                                action: 'theme_admin_save_all',
                                [key]: settings[key],
                            });
                        }
                    }
                    alert('Configurações importadas com sucesso!');
                } catch (err) {
                    alert('Erro ao importar configurações: ' + err.message);
                }
            };
            reader.readAsText(file);
        }
    </script>
<?php
}

// Função AJAX consolidada para salvar dados
add_action('wp_ajax_theme_admin_save_all', 'theme_admin_save_all');
function theme_admin_save_all() {
    // Atualiza opções com validações adequadas
    if (isset($_POST['theme_description'])) update_option('theme_description', sanitize_text_field($_POST['theme_description']));
    if (isset($_POST['theme_layout'])) update_option('theme_layout', sanitize_text_field($_POST['theme_layout']));
    if (isset($_POST['body_font'])) update_option('body_font', sanitize_text_field($_POST['body_font']));
    if (isset($_POST['header_font'])) update_option('header_font', sanitize_text_field($_POST['header_font']));
    if (isset($_POST['background_image'])) update_option('background_image', esc_url($_POST['background_image']));
    if (isset($_POST['theme_primary_color'])) update_option('theme_primary_color', esc_attr($_POST['theme_primary_color']));
    if (isset($_POST['theme_secondary_color'])) update_option('theme_secondary_color', esc_attr($_POST['theme_secondary_color']));
    // Adicione outros campos conforme necessário
    wp_die();
}

// Scripts e estilos
add_action('admin_enqueue_scripts', 'theme_admin_scripts_styles');
function theme_admin_scripts_styles()
{
    wp_enqueue_script('jquery');
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
?>
    <style>
        .tab-pane {
            transition: opacity 0.3s ease;
        }

        .tab-pane.fade {
            opacity: 0;
        }

        .tab-pane.fade.show {
            opacity: 1;
        }
    </style>
    <script>
        jQuery(document).ready(function($) {
            $('#config-form, #social-form, #address-form, #license-form, #color-form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize() + '&action=theme_admin_save_all';
                $.post(ajaxurl, formData, function(response) {
                    alert('Configurações salvas com sucesso!');
                });
            });
        });
    </script>
<?php
}
