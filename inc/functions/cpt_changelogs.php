<?php
// Função para registrar o Custom Post Type Changelog
function custom_post_type_changelog() {
    $labels = array(
        'name' => 'Changelogs',
        'singular_name' => 'Changelog',
        'menu_name' => 'Changelogs',
        'name_admin_bar' => 'Changelog',
        'add_new' => 'Adicionar Novo',
        'add_new_item' => 'Adicionar Novo Changelog',
        'new_item' => 'Novo Changelog',
        'edit_item' => 'Editar Changelog',
        'view_item' => 'Ver Changelog',
        'all_items' => 'Todos os Changelogs',
        'search_items' => 'Pesquisar Changelog',
        'not_found' => 'Nenhum Changelog encontrado',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => false,
        'rewrite' => array('slug' => 'changelogs'),
        'supports' => array('title', 'editor'),
        'show_in_rest' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-list-view',
    );

    register_post_type('changelogs', $args);
}
add_action('init', 'custom_post_type_changelog');

function changelog_meta_boxes() {
    add_meta_box('changelog_details', 'Changelog Details', 'changelog_meta_callback', 'changelogs', 'normal', 'high');
    add_meta_box('changelog_software', 'Software Relacionado', 'changelog_software_callback', 'changelogs', 'side', 'default');
}
add_action('add_meta_boxes', 'changelog_meta_boxes');

function changelog_meta_callback($post) {
    $added = get_post_meta($post->ID, '_changelog_added', true);
    $fixed = get_post_meta($post->ID, '_changelog_fixed', true);
    $updated = get_post_meta($post->ID, '_changelog_updated', true);
    $improved = get_post_meta($post->ID, '_changelog_improved', true);
    $removed = get_post_meta($post->ID, '_changelog_removed', true);
    $deprecated = get_post_meta($post->ID, '_changelog_deprecated', true);
    $compatibility = get_post_meta($post->ID, '_changelog_compatibility', true);
    ?>
    <style>
        .changelog-meta-boxes {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        .changelog-meta-boxes label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        .changelog-meta-boxes textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            line-height: 1.5;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>

    <div class="changelog-meta-boxes">
        <div class="changelog-meta-field">
            <label for="changelog_added"><?php echo __('Added','tecnoinfor')?></label>
            <textarea name="changelog_added" id="changelog_added" rows="5" placeholder="<?php echo __('Ex.: New features or improvements','tecnoinfor')?>"><?php echo esc_textarea($added); ?></textarea>
        </div>
        <div class="changelog-meta-field">
            <label for="changelog_fixed"><?php echo __('Fixed','tecnoinfor')?></label>
            <textarea name="changelog_fixed" id="changelog_fixed" rows="5" placeholder="<?php echo __('Ex.: Bug fixes or known errors','tecnoinfor')?>"><?php echo esc_textarea($fixed); ?></textarea>
        </div>
        <div class="changelog-meta-field">
            <label for="changelog_updated"><?php echo __('Update','tecnoinfor')?></label>
            <textarea name="changelog_updated" id="changelog_updated" rows="5" placeholder="<?php echo __('Ex.: General feature updates','tecnoinfor')?>"><?php echo esc_textarea($updated); ?></textarea>
        </div>
        <div class="changelog-meta-field">
            <label for="changelog_improved"><?php echo __('Improved','tecnoinfor')?></label>
            <textarea name="changelog_improved" id="changelog_improved" rows="5" placeholder="<?php echo __('Ex.: Process or performance improvements','tecnoinfor')?>"><?php echo esc_textarea($improved); ?></textarea>
        </div>
        <div class="changelog-meta-field">
            <label for="changelog_removed"><?php echo __('Removed','tecnoinfor')?></label>
            <textarea name="changelog_removed" id="changelog_removed" rows="5" placeholder="<?php echo __('Ex.: Removed features or elements','tecnoinfor')?>"><?php echo esc_textarea($removed); ?></textarea>
        </div>
        <div class="changelog-meta-field">
            <label for="changelog_deprecated"><?php echo __('Deprecated','tecnoinfor')?></label>
            <textarea name="changelog_deprecated" id="changelog_deprecated" rows="5" placeholder="<?php echo __('Ex.: Features or elements Discontinued','tecnoinfor')?>"><?php echo esc_textarea($deprecated); ?></textarea>
        </div>
        <div class="changelog-meta-field">
            <label for="changelog_compatibility"><?php echo __('Compatibility','tecnoinfor')?></label>
            <textarea name="changelog_compatibility" id="changelog_compatibility" rows="5" placeholder="<?php echo __('Ex.: Compatible Features or Elements','tecnoinfor')?>"><?php echo esc_textarea($compatibility); ?></textarea>
        </div>
    </div>
    <?php
}

// Novo Meta Box para Software Relacionado
function changelog_software_callback($post) {
    wp_nonce_field('salvar_dados_changelog', 'changelog_nonce');
    $related_software = get_post_meta($post->ID, '_related_software', true);
    $softwares = get_posts(array('post_type' => 'software', 'posts_per_page' => -1));
    ?>
    <label for="related_software"><?php _e('Select Software', 'tecnoinfor'); ?></label>
    <select name="related_software" id="related_software" class="widefat">
        <option value=""><?php _e('None', 'tecnoinfor'); ?></option>
        <?php foreach ($softwares as $software) : ?>
            <option value="<?php echo $software->ID; ?>" <?php selected($related_software, $software->ID); ?>>
                <?php echo esc_html($software->post_title); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}

function get_changelog_meta($post_id) {
    return array(
        'added'        => get_post_meta($post_id, '_changelog_added', true),
        'fixed'        => get_post_meta($post_id, '_changelog_fixed', true),
        'updated'      => get_post_meta($post_id, '_changelog_updated', true),
        'improved'     => get_post_meta($post_id, '_changelog_improved', true),
        'removed'      => get_post_meta($post_id, '_changelog_removed', true),
        'deprecated'   => get_post_meta($post_id, '_changelog_deprecated', true),
        'compatibility'=> get_post_meta($post_id, '_changelog_compatibility', true)
    );
}

function save_changelog_meta($post_id) {
    if (!isset($_POST['changelog_nonce']) || !wp_verify_nonce($_POST['changelog_nonce'], 'salvar_dados_changelog')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['changelog_added'])) {
        update_post_meta($post_id, '_changelog_added', sanitize_textarea_field($_POST['changelog_added']));
    }
    if (isset($_POST['changelog_fixed'])) {
        update_post_meta($post_id, '_changelog_fixed', sanitize_textarea_field($_POST['changelog_fixed']));
    }
    if (isset($_POST['changelog_updated'])) {
        update_post_meta($post_id, '_changelog_updated', sanitize_textarea_field($_POST['changelog_updated']));
    }
    if (isset($_POST['changelog_improved'])) {
        update_post_meta($post_id, '_changelog_improved', sanitize_textarea_field($_POST['changelog_improved']));
    }
    if (isset($_POST['changelog_removed'])) {
        update_post_meta($post_id, '_changelog_removed', sanitize_textarea_field($_POST['changelog_removed']));
    }
    if (isset($_POST['changelog_deprecated'])) {
        update_post_meta($post_id, '_changelog_deprecated', sanitize_textarea_field($_POST['changelog_deprecated']));
    }
    if (isset($_POST['changelog_compatibility'])) {
        update_post_meta($post_id, '_changelog_compatibility', sanitize_textarea_field($_POST['changelog_compatibility']));
    }
    if (isset($_POST['related_software'])) {
        update_post_meta($post_id, '_related_software', sanitize_text_field($_POST['related_software']));
    }
}
add_action('save_post', 'save_changelog_meta');

// [O restante do código para CPT software permanece o mesmo, não precisa ser alterado]
?>