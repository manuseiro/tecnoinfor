<?php
/**
 * Administração de Traduções em painel interno do WordPress.
 *
 * @package Tecnoinfor
 */

// Função para adicionar a página de administração para traduções
function tecnoinfor_menu() {
    add_menu_page(
        'Translation Settings',
        'Translation',
        'manage_options',
        'tecnoinfor-traducao',
        'tecnoinfor_traducao_page',
        'dashicons-translation',
        20
    );
}
add_action('admin_menu', 'tecnoinfor_menu');

// Função para a página de administração de traduções
function tecnoinfor_traducao_page() {
    // Caminho para o arquivo .pot
    $pot_file = get_template_directory() . '/languages/tecnoinfor.pot';

    // Verifica se o arquivo .pot existe
    if (!file_exists($pot_file)) {
        echo '<div class="notice notice-error"><p>' . __('POT file not found.', 'tecnoinfor') . '</p></div>';
        return;
    }

    // Selecionar o idioma para traduções
    $selected_language = isset($_POST['language']) ? sanitize_text_field($_POST['language']) : 'pt_BR';
    $po_file = get_template_directory() . "/languages/{$selected_language}.po";

    // Criar o arquivo .po se não existir
    if (!file_exists($po_file)) {
        file_put_contents($po_file, '');
        echo '<div class="notice notice-warning"><p>' . __('PO file was created. Please add your translations now.', 'tecnoinfor') . '</p></div>';
    }

    // Processar o envio do formulário para adicionar/atualizar tradução
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['original_string'], $_POST['translated_string'])) {
        check_admin_referer('tecnoinfor_translation_action', 'tecnoinfor_translation_nonce');
        $original_string = sanitize_text_field($_POST['original_string']);
        $translated_string = sanitize_text_field($_POST['translated_string']);

        // Adicionar ou atualizar a tradução no arquivo .po
        $current_content = file_get_contents($po_file);
        $new_translation = "msgid \"$original_string\"\nmsgstr \"$translated_string\"\n\n";

        // Atualiza ou adiciona a tradução
        if (strpos($current_content, $original_string) !== false) {
            // Atualiza a tradução existente
            $current_content = preg_replace("/msgid \"$original_string\"\nmsgstr \".*?\"/", $new_translation, $current_content);
        } else {
            // Adiciona nova tradução
            $current_content .= $new_translation;
        }

        file_put_contents($po_file, $current_content);
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Translation saved successfully!', 'tecnoinfor') . '</p></div>';
    }

    // Processar exclusão de tradução
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_original_string'])) {
        check_admin_referer('tecnoinfor_translation_action', 'tecnoinfor_translation_nonce');
        $original_string_to_delete = sanitize_text_field($_POST['delete_original_string']);

        // Verifica se o arquivo .po existe antes de tentar ler
        if (!file_exists($po_file)) {
            echo '<div class="notice notice-error"><p>' . __('PO file not found. Cannot delete translation.', 'tecnoinfor') . '</p></div>';
            return;
        }

        // Ler conteúdo atual do arquivo .po
        $current_content = file_get_contents($po_file);

        // Remover a tradução
        $new_content = preg_replace("/msgid \"$original_string_to_delete\"\nmsgstr \".*?\"\n\n/", '', $current_content);

        // Verifica se a tradução foi removida com sucesso
        if ($new_content !== $current_content) {
            // Salvar o conteúdo atualizado de volta ao arquivo .po
            file_put_contents($po_file, $new_content);
            echo '<div class="notice notice-success is-dismissible"><p>' . __('Translation deleted successfully!', 'tecnoinfor') . '</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>' . __('Translation not found for deletion.', 'tecnoinfor') . '</p></div>';
        }
    }

    // Ler traduções do arquivo .po
    $translations = [];
    $content = file_get_contents($po_file);

    // Usar regex para extrair strings
    preg_match_all('/msgid "(.*?)"\s*msgstr "(.*?)"/', $content, $matches);
    $translations = array_combine($matches[1], $matches[2]);

?>
<div class="wrap">
    <h1><?php _e('Translation Settings', 'tecnoinfor'); ?></h1>

    <form method="post" action="">
        <h2><?php _e('Select Language', 'tecnoinfor'); ?></h2>
        <select name="language" onchange="this.form.submit()">
            <option value="pt_BR" <?php selected($selected_language, 'pt_BR'); ?>>Português do Brasil</option>
            <option value="en_US" <?php selected($selected_language, 'en_US'); ?>>English (US)</option>
            <option value="es_ES" <?php selected($selected_language, 'es_ES'); ?>>Español</option>
            <!-- Adicione mais opções de idioma aqui -->
        </select>
    </form>

    <form method="post" action="">
        <h2><?php _e('Add or Update Translation', 'tecnoinfor'); ?></h2>
        <?php wp_nonce_field('tecnoinfor_translation_action', 'tecnoinfor_translation_nonce'); ?>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="original_string"><?php _e('Original String', 'tecnoinfor'); ?></label></th>
                <td><input name="original_string" type="text" id="original_string" class="regular-text" required></td>
            </tr>
            <tr>
                <th scope="row"><label for="translated_string"><?php _e('Translated String', 'tecnoinfor'); ?></label>
                </th>
                <td><input name="translated_string" type="text" id="translated_string" class="regular-text" required>
                </td>
            </tr>
        </table>
        <?php submit_button(__('Save Translation', 'tecnoinfor')); ?>
    </form>

    <hr>

    <h2><?php _e('Existing Translations', 'tecnoinfor'); ?></h2>
    <table class="widefat">
        <thead>
            <tr>
                <th><?php _e('Original String', 'tecnoinfor'); ?></th>
                <th><?php _e('Translated String', 'tecnoinfor'); ?></th>
                <th><?php _e('Actions', 'tecnoinfor'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
                // Exibir as traduções existentes
                if ($translations) {
                    foreach ($translations as $original => $translated) {
                        echo '<tr>';
                        echo '<td>' . esc_html($original) . '</td>';
                        echo '<td>';
                        echo '<form method="post" action="" style="display:inline;">';
                        echo '<input type="hidden" name="original_string" value="' . esc_attr($original) . '">';
                        echo '<input type="text" name="translated_string" value="' . esc_attr($translated) . '" class="regular-text" required>';
                        echo '</td>';
                        echo '<td>';
                        echo '<input type="submit" value="' . __('Update', 'tecnoinfor') . '" class="button button-secondary">';
                        echo '</form>';
                        echo '<form method="post" action="" style="display:inline;">';
                        echo '<input type="hidden" name="delete_original_string" value="' . esc_attr($original) . '">';
                        echo '<input type="submit" value="' . __('Delete', 'tecnoinfor') . '" class="button button-secondary" onclick="return confirm(\'Are you sure you want to delete this translation?\');">';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="3">' . __('No translations found.', 'tecnoinfor') . '</td></tr>';
                }
                ?>
        </tbody>
    </table>
</div>
<?php
}
