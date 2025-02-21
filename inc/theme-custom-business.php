<?php

// ===========================
// Menu no Admin
// ===========================

function menu_informacoes_empresa() {
    add_menu_page(
        'Informações da Empresa',
        'Empresa',
        'manage_options',
        'informacoes-empresa',
        'pagina_informacoes_empresa',
        'dashicons-building',
        20
    );
}
add_action('admin_menu', 'menu_informacoes_empresa');

// ===========================
// Registro de Configurações
// ===========================

function registrar_informacoes_empresa_settings() {
    $campos = [
        'empresa_links_redes_sociais' => 'sanitize_text_field',
        'empresa_endereco'            => 'sanitize_text_field',
        'empresa_logo'                => 'intval',
        'empresa_horarios'            => 'sanitize_textarea_field',
        'empresa_telefone'            => 'sanitize_text_field',
        'empresa_whatsapp'            => 'sanitize_text_field',
    ];

    foreach ($campos as $campo => $sanitize) {
        register_setting('informacoes_empresa_settings', $campo, $sanitize);
    }

    add_settings_section('informacoes_gerais', 'Informações Gerais', '', 'informacoes-empresa');

    add_settings_field('empresa_links_redes_sociais', 'Links das Redes Sociais', 'informacoes_empresa_links_redes_sociais_callback', 'informacoes-empresa', 'informacoes_gerais', ['label_for' => 'empresa_links_redes_sociais']);
    add_settings_field('empresa_endereco', 'Endereço', 'informacoes_empresa_endereco_callback', 'informacoes-empresa', 'informacoes_gerais', ['label_for' => 'empresa_endereco']);
    add_settings_field('empresa_logo', 'Logo da Empresa', 'informacoes_empresa_logo_callback', 'informacoes-empresa', 'informacoes_gerais', ['label_for' => 'empresa_logo']);
    add_settings_field('empresa_horarios', 'Horários de Funcionamento', 'informacoes_empresa_horarios_callback', 'informacoes-empresa', 'informacoes_gerais', ['label_for' => 'empresa_horarios']);
    add_settings_field('empresa_telefone', 'Telefone', 'informacoes_empresa_telefone_callback', 'informacoes-empresa', 'informacoes_gerais', ['label_for' => 'empresa_telefone']);
    add_settings_field('empresa_whatsapp', 'WhatsApp', 'informacoes_empresa_whatsapp_callback', 'informacoes-empresa', 'informacoes_gerais', ['label_for' => 'empresa_whatsapp']);
    foreach ($redes as $rede => $dados) {
      add_settings_field('empresa_rede_' . $rede, $dados['nome'], 'informacoes_empresa_rede_social_callback', 'informacoes-empresa', 'informacoes_gerais', ['label_for' => 'empresa_rede_' . $rede, 'rede' => $rede]);
  }
}

add_action('admin_init', 'registrar_informacoes_empresa_settings');

// ===========================
// Exibição da Página no Admin
// ===========================

function pagina_informacoes_empresa() {
    ?>
    <div class="wrap">
        <h1>Informações da Empresa</h1>
        <?php settings_errors(); ?>
        <form method="post" action="options.php" enctype="multipart/form-data">
            <?php settings_fields('informacoes_empresa_settings'); ?>
            <?php do_settings_sections('informacoes-empresa'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// ===========================
// Callbacks dos Campos
// ===========================

function informacoes_empresa_links_redes_sociais_callback() {
  $links = get_option('empresa_links_redes_sociais', '');
  ?>
  <textarea id="empresa_links_redes_sociais" name="empresa_links_redes_sociais" rows="5" cols="50" placeholder="Cole os links das redes sociais, separados por vírgula."><?php echo esc_textarea($links); ?></textarea>
  <p class="description">Cole os links das redes sociais, separados por vírgula. Exemplo: `https://www.facebook.com/suaempresa, https://www.instagram.com/suaempresa`</p>
  <?php
}

function informacoes_empresa_endereco_callback() {
    $endereco = get_option('empresa_endereco');
    ?>
    <input type="text" id="empresa_endereco" name="empresa_endereco" value="<?php echo esc_attr($endereco); ?>" placeholder="Digite o endereço da empresa">
    <p class="description">Digite o endereço completo da empresa.</p>
    <?php
}

function informacoes_empresa_logo_callback() {
    $logo_id  = get_option('empresa_logo');
    $logo_url = wp_get_attachment_image_url($logo_id, 'full');
    ?>
    <input type="hidden" name="empresa_logo" id="empresa_logo" value="<?php echo esc_attr($logo_id); ?>">
    <div id="logo-preview">
        <?php if ($logo_url) : ?>
          <img src="<?php echo esc_url($logo_url); ?>" alt="Logo da Empresa" style="max-width: 200px; height: auto;">
<?php endif; ?>
</div>
<p>
<button class="button" id="upload-logo">Enviar Logo</button>
<button class="button button-secondary" id="remover-logo">Remover</button>
</p>
    <?php
}

function informacoes_empresa_horarios_callback() {
    $horarios = get_option('empresa_horarios');
    ?>
    <textarea id="empresa_horarios" name="empresa_horarios" rows="5" cols="50" placeholder="Digite os horários de funcionamento da empresa."><?php echo esc_textarea($horarios); ?></textarea>
    <p class="description">Digite os horários de funcionamento da empresa.</p>
    <?php
}

function informacoes_empresa_telefone_callback() {
  $telefone = get_option('empresa_telefone');
  ?>
  <input type="tel" id="empresa_telefone" name="empresa_telefone" value="<?php echo esc_attr($telefone); ?>" placeholder="DDD + Número">
  <p class="description">Informe o número de telefone com DDD.</p>
  <?php
  // Validação do telefone
  if ($telefone && !validar_telefone($telefone)) {
      add_settings_error('empresa_telefone', 'empresa_telefone_error', 'Número de telefone inválido.', 'error');
  }
}

function informacoes_empresa_whatsapp_callback() {
    $whatsapp = get_option('empresa_whatsapp');
    ?>
    <input type="tel" id="empresa_whatsapp" name="empresa_whatsapp" value="<?php echo esc_attr($whatsapp); ?>" placeholder="DDD + Número">
    <p class="description">Informe o número de WhatsApp com DDD.</p>
    <?php
}

// ===========================
// Função para obter informações
// ===========================
function get_informacao_empresa($campo) {
  $valor = get_option("empresa_{$campo}");
  $valor_padrao = '';

  // Definir valores padrão para outros campos
  if ($campo === 'telefone' || $campo === 'whatsapp') {
      $valor_padrao = 'Número não informado';
  }

  if ($campo === 'endereco') {
      $valor_padrao = 'Endereço não informado';
  }

  if ($campo === 'horarios') {
      $valor_padrao = 'Horários não informados';
  }

  if ($campo === 'links_redes_sociais') {
      $valor_padrao = 'Links não informados';
  }

  // Logo - Caso o valor não seja encontrado ou não seja válido
  if ($campo === 'logo' && !empty($valor)) {
    $logo_url = wp_get_attachment_image_url($valor, 'full');
    return $logo_url ? "<img src='" . esc_url($logo_url) . "' alt='Logo da Empresa' style='max-width: 200px; height: auto;'>" : '<img src="URL_PADRAO_DO_LOGO" alt="Logo da Empresa" style="max-width: 200px; height: auto;">';
}

  return !empty($valor) ? esc_html($valor) : $valor_padrao;
}


// ===========================
// Funções para exibir redes sociais
// ===========================

function get_redes_sociais() {
  return [
      'facebook'  => ['nome' => 'Facebook', 'icone' => 'bi bi-facebook'],
      'instagram' => ['nome' => 'Instagram', 'icone' => 'bi bi-instagram'],
      'linkedin'  => ['nome' => 'LinkedIn', 'icone' => 'bi bi-linkedin'],
      'twitter'   => ['nome' => 'Twitter', 'icone' => 'bi bi-twitter'],
      'youtube'   => ['nome' => 'YouTube', 'icone' => 'bi bi-youtube']
  ];
}

function get_rede_social($rede) {
  $redes = get_redes_sociais();
  if (!isset($redes[$rede])) {
      return ['link' => '', 'icone' => ''];
  }

  $link = get_option("empresa_rede_{$rede}", '');
  return [
      'link' => $link,
      'icone' => $redes[$rede]['icone'],
      'nome' => $redes[$rede]['nome']
  ];
}

function exibir_rede_social($rede, $mostrar_icone = true, $mostrar_nome = false) {
  $info = get_rede_social($rede);
  if (empty($info['link'])) return '';

  $output = '<a href="' . esc_url($info['link']) . '" target="_blank" class="me-2">';
  if ($mostrar_icone) {
      $output .= '<i class="' . esc_attr($info['icone']) . '"></i>';
  }
  if ($mostrar_nome) {
      $output .= ' ' . esc_html($info['nome']);
  }
  $output .= '</a>';

  return $output;
}

function exibir_redes_sociais($mostrar_icone = true, $mostrar_nome = false) {
  echo '<ul class="list-inline">';
  foreach (get_redes_sociais() as $rede => $dados) {
      echo '<li class="list-inline-item">' . exibir_rede_social($rede, $mostrar_icone, $mostrar_nome) . '</li>';
  }
  echo '</ul>';
}
// ===========================
// Funções auxiliares
// ===========================

function validar_telefone($telefone) {
  // Remove caracteres não numéricos
  $telefone = preg_replace('/[^0-9]/', '', $telefone);

  // Verifica se tem o número de dígitos corretos
  return strlen($telefone) >= 10 && strlen($telefone) <= 11;
}

// ... (callbacks para outros campos com validação)
