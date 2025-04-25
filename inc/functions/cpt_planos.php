<?php
/**
 * Registra o Custom Post Type 'Planos'.
 *
 * Cria um tipo de post personalizado chamado 'planos' para gerenciar diferentes planos de assinatura,
 * com suporte a título, editor, imagem destacada e campos personalizados. Exibe um ícone personalizado
 * no menu admin e habilita a API REST.
 *
 * @since 1.0.0
 * @return void
 */
function criar_cpt_planos() {
    $labels = array(
        'name' => 'Planos',
        'singular_name' => 'Plano',
        'menu_name' => 'Planos',
        'name_admin_bar' => 'Plano',
        'add_new' => 'Adicionar Novo',
        'add_new_item' => 'Adicionar Novo Plano',
        'new_item' => 'Novo Plano',
        'edit_item' => 'Editar Plano',
        'view_item' => 'Ver Plano',
        'all_items' => 'Todos os Planos',
        'search_items' => 'Buscar Planos',
        'not_found' => 'Nenhum plano encontrado',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'planos'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 5,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-money-alt',
    );

    register_post_type('planos', $args);
}
add_action('init', 'criar_cpt_planos', 0);

/**
 * Adiciona uma meta box ao Custom Post Type 'Planos'.
 *
 * Registra uma meta box chamada "Detalhes do Plano" no editor do CPT 'planos' para permitir a
 * inserção de informações específicas como preço, funcionalidades e opções de destaque.
 *
 * @since 1.0.0
 * @return void
 */
function criar_meta_box_planos() {
    add_meta_box(
        'informacoes_plano',
        'Detalhes do Plano',
        'exibir_meta_box_planos',
        'planos',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'criar_meta_box_planos');

/**
 * Exibe os campos da meta box 'Detalhes do Plano'.
 *
 * Renderiza os campos HTML para inserção de dados como preço, número máximo de usuários e empresas,
 * funcionalidades (com opção de quantidade ou não), botão, desconto e opções de destaque no editor
 * do CPT 'planos'. Inclui estilos inline e scripts para interatividade.
 *
 * @since 1.0.0
 * @param WP_Post $post O objeto do post atual sendo editado.
 * @return void
 */
function exibir_meta_box_planos($post) {
    wp_nonce_field('informacoes_plano_nonce', 'informacoes_plano_nonce');

    $preco = get_post_meta($post->ID, 'preco', true);
    $funcionalidades = maybe_unserialize(get_post_meta($post->ID, 'funcionalidades', true)) ?: [];
    $texto_botao = get_post_meta($post->ID, 'texto_botao', true);
    $classe_botao = get_post_meta($post->ID, 'classe_botao', true);
    $desconto = get_post_meta($post->ID, 'desconto', true);
    $max_users = get_post_meta($post->ID, 'max_users', true);
    $max_companies = get_post_meta($post->ID, 'max_companies', true);
    $is_recommended = get_post_meta($post->ID, 'is_recommended', true);
    $highlight_color = get_post_meta($post->ID, 'highlight_color', true) ?: 'primary';
    ?>
    <style>
        .meta-box-container { max-width: 600px; }
        .meta-box-container label { display: block; margin: 10px 0 5px; font-weight: bold; }
        .meta-box-container input[type="text"], .meta-box-container input[type="number"], .meta-box-container select { width: 100%; padding: 5px; }
        #funcionalidades-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        #funcionalidades-table th, #funcionalidades-table td { padding: 8px; border: 1px solid #ddd; }
        #funcionalidades-table input { width: 100%; }
        #adicionar-func { background: #0073aa; color: white; padding: 5px 10px; border: none; cursor: pointer; }
        .remover-func { background: #d63638; color: white; border: none; padding: 5px; cursor: pointer; }
    </style>

    <div class="meta-box-container">
        <label for="preco">Preço Mensal (R$):</label>
        <input type="number" id="preco" name="preco" value="<?php echo esc_attr($preco); ?>" step="0.01" min="0" />

        <label for="max_users">Número Máximo de Usuários:</label>
        <input type="number" id="max_users" name="max_users" value="<?php echo esc_attr($max_users); ?>" min="1" />

        <label for="max_companies">Número Máximo de Empresas:</label>
        <input type="number" id="max_companies" name="max_companies" value="<?php echo esc_attr($max_companies); ?>" min="1" />

        <label>Funcionalidades:</label>
        <table id="funcionalidades-table">
            <thead>
                <tr>
                    <th>Quantidade</th>
                    <th>Funcionalidade</th>
                    <th>Sem Quantidade?</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($funcionalidades as $func) : ?>
                    <tr>
                        <td><input type="number" name="funcionalidades_quantidade[]" value="<?php echo esc_attr($func['quantidade']); ?>" min="0" <?php echo !empty($func['no_quantity']) ? 'disabled' : ''; ?>></td>
                        <td><input type="text" name="funcionalidades_nome[]" value="<?php echo esc_attr($func['nome']); ?>"></td>
                        <td><input type="checkbox" name="funcionalidades_no_quantity[]" value="1" <?php checked($func['no_quantity'], '1'); ?> onchange="this.closest('tr').querySelector('input[type=number]').disabled = this.checked;"></td>
                        <td><button type="button" class="remover-func">Remover</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="button" id="adicionar-func">Adicionar Funcionalidade</button>

        <label for="texto_botao">Texto do Botão:</label>
        <input type="text" id="texto_botao" name="texto_botao" value="<?php echo esc_attr($texto_botao); ?>" placeholder="Ex.: Assinar" />

        <label for="classe_botao">Classe do Botão (Bootstrap):</label>
        <input type="text" id="classe_botao" name="classe_botao" value="<?php echo esc_attr($classe_botao); ?>" placeholder="Ex.: btn-primary" />

        <label for="desconto">Desconto Anual (%):</label>
        <input type="number" id="desconto" name="desconto" value="<?php echo esc_attr($desconto); ?>" min="0" max="100" />

        <label for="is_recommended">Plano Recomendado?</label>
        <input type="checkbox" id="is_recommended" name="is_recommended" value="1" <?php checked($is_recommended, '1'); ?> />

        <label for="highlight_color">Cor de Destaque (Plano Recomendado):</label>
        <select id="highlight_color" name="highlight_color">
            <option value="primary" <?php selected($highlight_color, 'primary'); ?>>Azul (Padrão)</option>
            <option value="success" <?php selected($highlight_color, 'success'); ?>>Verde</option>
            <option value="warning" <?php selected($highlight_color, 'warning'); ?>>Amarelo</option>
            <option value="danger" <?php selected($highlight_color, 'danger'); ?>>Vermelho</option>
        </select>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('adicionar-func').addEventListener('click', function() {
                const tbody = document.querySelector('#funcionalidades-table tbody');
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><input type="number" name="funcionalidades_quantidade[]" value="1" min="0"></td>
                    <td><input type="text" name="funcionalidades_nome[]" value=""></td>
                    <td><input type="checkbox" name="funcionalidades_no_quantity[]" value="1" onchange="this.closest('tr').querySelector('input[type=number]').disabled = this.checked;"></td>
                    <td><button type="button" class="remover-func">Remover</button></td>
                `;
                tbody.appendChild(row);
                row.querySelector('.remover-func').addEventListener('click', function() { row.remove(); });
            });

            document.querySelectorAll('.remover-func').forEach(button => {
                button.addEventListener('click', function() { button.closest('tr').remove(); });
            });
        });
    </script>
    <?php
}

/**
 * Salva os valores da meta box 'Detalhes do Plano'.
 *
 * Processa e salva os dados inseridos na meta box do CPT 'planos' ao salvar o post. Inclui validações
 * de segurança como nonce, autosave e permissões. Armazena os campos como metadados serializados
 * quando necessário.
 *
 * @since 1.0.0
 * @param int $post_id O ID do post sendo salvo.
 * @return void
 */
function salvar_meta_box_planos($post_id) {
    if (!isset($_POST['informacoes_plano_nonce']) || !wp_verify_nonce($_POST['informacoes_plano_nonce'], 'informacoes_plano_nonce') || defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || !current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = ['preco', 'max_users', 'max_companies', 'texto_botao', 'classe_botao', 'desconto', 'highlight_color'];
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }

    if (isset($_POST['funcionalidades_nome']) && is_array($_POST['funcionalidades_nome'])) {
        $funcionalidades = [];
        $quantidades = isset($_POST['funcionalidades_quantidade']) ? $_POST['funcionalidades_quantidade'] : [];
        $no_quantities = isset($_POST['funcionalidades_no_quantity']) ? $_POST['funcionalidades_no_quantity'] : [];
        
        foreach ($_POST['funcionalidades_nome'] as $key => $nome) {
            if (!empty($nome)) {
                $no_quantity = isset($no_quantities[$key]) && $no_quantities[$key] === '1';
                $quantidade = $no_quantity ? '' : (isset($quantidades[$key]) ? sanitize_text_field($quantidades[$key]) : '0');
                $funcionalidades[] = [
                    'quantidade' => $quantidade,
                    'nome' => sanitize_text_field($nome),
                    'no_quantity' => $no_quantity ? '1' : '0'
                ];
            }
        }
        update_post_meta($post_id, 'funcionalidades', serialize($funcionalidades));
    }

    update_post_meta($post_id, 'is_recommended', isset($_POST['is_recommended']) ? '1' : '0');
}
add_action('save_post', 'salvar_meta_box_planos');