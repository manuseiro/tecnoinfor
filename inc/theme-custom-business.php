<?php

function tecnoinfor_enqueue_admin_assets($hook) {
    if ($hook !== 'toplevel_page_informacoes-empresa') {
        return;
    }

    // Carrega a API de mídia do WordPress
    wp_enqueue_media();

    // Scripts
    wp_enqueue_script('jquery-mask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js', array('jquery'), '1.14.16', true);
    wp_enqueue_script(
        'tecnoinfor_empresa-admin',
        get_template_directory_uri() . '/assets/js/empresa-admin.js',
        array('jquery', 'jquery-mask'),
        '0.0.1',
        true
    );

    // Estilos
    wp_enqueue_style(
        'tecnoinfor_empresa-admin',
        get_template_directory_uri() . '/assets/css/empresa-admin.css',
        [],
        '0.0.1'
    );
}
add_action('admin_enqueue_scripts', 'tecnoinfor_enqueue_admin_assets');

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
    $redes_sociais = get_redes_sociais();

    $campos = [
        'empresa_endereco' => 'sanitize_text_field',
        'empresa_logo'     => 'intval',
        'empresa_horarios' => 'sanitize_textarea_field',
        'empresa_telefone' => 'sanitize_text_field',
        'empresa_whatsapp' => 'sanitize_text_field',
    ];

    foreach ($campos as $campo => $sanitize) {
        register_setting('informacoes_empresa_settings', $campo, [
            'sanitize_callback' => $sanitize,
        ]);
    }

    foreach ($redes_sociais as $rede => $dados) {
        register_setting('informacoes_empresa_settings', "empresa_rede_$rede", [
            'sanitize_callback' => function($value) use ($rede) {
                return validar_url_rede_social($value, $rede);
            },
        ]);
    }

    add_settings_section('informacoes_gerais', 'Informações Gerais', '', 'informacoes-empresa');

    add_settings_field('empresa_endereco', 'Endereço', 'informacoes_empresa_endereco_callback', 'informacoes-empresa', 'informacoes_gerais', ['label_for' => 'empresa_endereco']);
    add_settings_field('empresa_logo', 'Logo da Empresa', 'informacoes_empresa_logo_callback', 'informacoes-empresa', 'informacoes_gerais', ['label_for' => 'empresa_logo']);
    add_settings_field('empresa_horarios', 'Horários de Funcionamento', 'informacoes_empresa_horarios_callback', 'informacoes-empresa', 'informacoes_gerais', ['label_for' => 'empresa_horarios']);
    add_settings_field('empresa_telefone', 'Telefone', 'informacoes_empresa_telefone_callback', 'informacoes-empresa', 'informacoes_gerais', ['label_for' => 'empresa_telefone']);
    add_settings_field('empresa_whatsapp', 'WhatsApp', 'informacoes_empresa_whatsapp_callback', 'informacoes-empresa', 'informacoes_gerais', ['label_for' => 'empresa_whatsapp']);

    foreach ($redes_sociais as $rede => $dados) {
        add_settings_field(
            "empresa_rede_$rede",
            $dados['nome'],
            'informacoes_empresa_rede_social_callback',
            'informacoes-empresa',
            'informacoes_gerais',
            ['label_for' => "empresa_rede_$rede", 'rede' => $rede]
        );
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
function informacoes_empresa_endereco_callback() {
    $endereco = get_option('empresa_endereco');
    ?>
    <input type="text" id="empresa_endereco" name="empresa_endereco" value="<?php echo esc_attr($endereco); ?>" placeholder="Digite o endereço da empresa" class="regular-text">
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
    <p class="description">Envie ou selecione o logo da empresa.</p>
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
    <input type="tel" id="empresa_telefone" name="empresa_telefone" value="<?php echo esc_attr($telefone); ?>" placeholder="DDD + Número" class="regular-text">
    <p class="description">Informe o número de telefone com DDD.</p>
    <?php
    if ($telefone && !validar_telefone($telefone)) {
        add_settings_error('empresa_telefone', 'empresa_telefone_error', 'Número de telefone inválido.', 'error');
    }
}

function informacoes_empresa_whatsapp_callback() {
    $whatsapp = get_option('empresa_whatsapp');
    ?>
    <input type="tel" id="empresa_whatsapp" name="empresa_whatsapp" value="<?php echo esc_attr($whatsapp); ?>" placeholder="DDD + Número" class="regular-text">
    <p class="description">Informe o número de WhatsApp com DDD.</p>
    <?php
    if ($whatsapp && !validar_telefone($whatsapp)) {
        add_settings_error('empresa_whatsapp', 'empresa_whatsapp_error', 'Número de WhatsApp inválido.', 'error');
    }
}

function informacoes_empresa_rede_social_callback($args) {
    $rede = $args['rede'];
    $valor = get_option("empresa_rede_$rede", '');
    ?>
    <input type="url" id="<?php echo esc_attr($args['label_for']); ?>" name="<?php echo esc_attr($args['label_for']); ?>" value="<?php echo esc_url($valor); ?>" placeholder="https://www.<?php echo $rede; ?>.com/suaempresa" class="regular-text">
    <p class="description">Digite a URL completa do perfil da empresa no <?php echo esc_html(get_redes_sociais()[$rede]['nome']); ?>.</p>
    <?php
}

// ===========================
// Funções para obter informações
// ===========================
function get_informacao_empresa($campo) {
    static $cache = null;
    if (null === $cache) {
        $cache = [
            'telefone' => get_option('empresa_telefone'),
            'whatsapp' => get_option('empresa_whatsapp'),
            'endereco' => get_option('empresa_endereco'),
            'horarios' => get_option('empresa_horarios'),
            'logo'     => get_option('empresa_logo'),
        ];
    }

    $valor = $cache[$campo] ?? '';
    $valor_padrao = '';

    if ($campo === 'telefone' || $campo === 'whatsapp') {
        $valor_padrao = 'Número não informado';
    } elseif ($campo === 'endereco') {
        $valor_padrao = 'Endereço não informado';
    } elseif ($campo === 'horarios') {
        $valor_padrao = 'Horários não informados';
    } elseif ($campo === 'logo' && !empty($valor)) {
        $logo_url = wp_get_attachment_image_url($valor, 'full');
        return $logo_url ? "<img src='" . esc_url($logo_url) . "' alt='Logo da Empresa' style='max-width: 200px; height: auto;'>" : '';
    }

    return !empty($valor) ? esc_html($valor) : $valor_padrao;
}

// ===========================
// Funções para redes sociais
// ===========================
function get_redes_sociais() {
    return [
        'facebook'  => ['nome' => 'Facebook', 'icone' => 'bi bi-facebook'],
        'instagram' => ['nome' => 'Instagram', 'icone' => 'bi bi-instagram'],
        'linkedin'  => ['nome' => 'LinkedIn', 'icone' => 'bi bi-linkedin'],
        'twitter'   => ['nome' => 'Twitter', 'icone' => 'bi bi-twitter'],
        'youtube'   => ['nome' => 'YouTube', 'icone' => 'bi bi-youtube'],
    ];
}

function get_rede_social($rede) {
    $redes = get_redes_sociais();
    if (!isset($redes[$rede])) {
        return ['link' => '', 'icone' => '', 'nome' => ''];
    }

    $link = get_option("empresa_rede_$rede", '');
    return [
        'link' => $link,
        'icone' => $redes[$rede]['icone'],
        'nome' => $redes[$rede]['nome'],
    ];
}

function exibir_rede_social($rede, $mostrar_icone = true, $mostrar_nome = false) {
    $info = get_rede_social($rede);
    if (empty($info['link'])) return '';

    $output = '<a href="' . esc_url($info['link']) . '" target="_blank" rel="noopener noreferrer" class="me-2">';
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
function enfileirar_scripts_admin($hook) {
    if ($hook !== 'toplevel_page_informacoes-empresa') {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script(
        'empresa-admin-scripts',
        get_template_directory_uri() . '/assets/js/empresa-admin.js',
        ['jquery'],
        '1.0',
        true
    );
    wp_enqueue_style(
        'empresa-admin-styles',
        get_template_directory_uri() . '/assets/css/empresa-admin.css',
        [],
        '1.0'
    );
}
add_action('admin_enqueue_scripts', 'enfileirar_scripts_admin');

function validar_telefone($telefone) {
    $telefone = preg_replace('/[^0-9]/', '', $telefone);
    return strlen($telefone) >= 10 && strlen($telefone) <= 11;
}

function validar_url_rede_social($url, $rede) {
    if (empty($url)) {
        return $url;
    }
    $dominios_permitidos = [
        'facebook'  => ['facebook.com'],
        'instagram' => ['instagram.com'],
        'linkedin'  => ['linkedin.com'],
        'twitter'   => ['twitter.com', 'x.com'],
        'youtube'   => ['youtube.com', 'youtu.be'],
    ];
    $parsed_url = parse_url($url, PHP_URL_HOST);
    if (!$parsed_url || !isset($dominios_permitidos[$rede])) {
        return get_option("empresa_rede_$rede", '');
    }
    foreach ($dominios_permitidos[$rede] as $dominio) {
        if (stripos($parsed_url, $dominio) !== false) {
            return $url;
        }
    }
    add_settings_error(
        "empresa_rede_$rede",
        "empresa_rede_{$rede}_error",
        "O link do {$rede} deve conter '{$dominios_permitidos[$rede][0]}'. O valor anterior foi mantido.",
        'error'
    );
    return get_option("empresa_rede_$rede", '');
}