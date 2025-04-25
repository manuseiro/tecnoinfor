<?php
// Registrar o CPT Software
function registrar_cpt_software() {
    $labels = array(
        'name'               => _x('Softwares', 'Post type general name', 'tecnoinfor'),
        'singular_name'      => _x('Software', 'Post type singular name', 'tecnoinfor'),
        'menu_name'          => _x('Softwares', 'Admin Menu text', 'tecnoinfor'),
        'name_admin_bar'     => _x('Software', 'Add New on Toolbar', 'tecnoinfor'),
        'add_new'            => __('Add New', 'tecnoinfor'),
        'add_new_item'       => __('Add New Software', 'tecnoinfor'),
        'edit_item'          => __('Edit Software', 'tecnoinfor'),
        'new_item'           => __('New Software', 'tecnoinfor'),
        'view_item'          => __('View Software', 'tecnoinfor'),
        'all_items'          => __('All Softwares', 'tecnoinfor'),
        'search_items'       => __('Search Softwares', 'tecnoinfor'),
        'not_found'          => __('No softwares found.', 'tecnoinfor'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'software'),
        'capability_type'    => 'page',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'supports'           => array('title', 'editor', 'excerpt', 'thumbnail'),
        'show_in_rest'       => true,
    );

    register_post_type('software', $args);
}
add_action('init', 'registrar_cpt_software');

// Registrar Taxonomia para Categorias de Software
function registrar_taxonomia_software_category() {
    $labels = array(
        'name'              => _x('Software Categories', 'Taxonomy general name', 'tecnoinfor'),
        'singular_name'     => _x('Software Category', 'Taxonomy singular name', 'tecnoinfor'),
        'search_items'      => __('Search Categories', 'tecnoinfor'),
        'all_items'         => __('All Categories', 'tecnoinfor'),
        'edit_item'         => __('Edit Category', 'tecnoinfor'),
        'update_item'       => __('Update Category', 'tecnoinfor'),
        'add_new_item'      => __('Add New Category', 'tecnoinfor'),
        'new_item_name'     => __('New Category Name', 'tecnoinfor'),
        'menu_name'         => __('Categories', 'tecnoinfor'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'software-category'),
        'show_in_rest'      => true,
    );

    register_taxonomy('software_category', array('software'), $args);
}
add_action('init', 'registrar_taxonomia_software_category');

// Adicionar Meta Boxes
function adicionar_meta_boxes_software() {
    add_meta_box(
        'software_details',
        __('Software Details', 'tecnoinfor'),
        'renderizar_meta_box_software',
        'software',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'adicionar_meta_boxes_software');

// Renderizar Meta Box
function renderizar_meta_box_software($post) {
    wp_nonce_field('salvar_dados_software', 'software_nonce');
    $download_link = get_post_meta($post->ID, '_software_download_link', true);
    $video_url = get_post_meta($post->ID, '_software_video_url', true);
    $trial_period = get_post_meta($post->ID, '_software_trial_period', true);
    $functionalities = get_post_meta($post->ID, '_software_functionalities', true);
    if (!is_array($functionalities)) {
        $functionalities = array();
    }
    ?>
    <p>
        <label for="software_download_link"><?php _e('Download Link', 'tecnoinfor'); ?></label><br>
        <input type="url" id="software_download_link" name="software_download_link" value="<?php echo esc_attr($download_link); ?>" style="width: 100%;">
    </p>
    <p>
        <label for="software_video_url"><?php _e('Video URL (YouTube)', 'tecnoinfor'); ?></label><br>
        <input type="url" id="software_video_url" name="software_video_url" value="<?php echo esc_attr($video_url); ?>" style="width: 100%;">
    </p>
    <p>
        <label for="software_trial_period"><?php _e('Trial Period (e.g. 15 days)', 'tecnoinfor'); ?></label><br>
        <input type="text" id="software_trial_period" name="software_trial_period" value="<?php echo esc_attr($trial_period); ?>" style="width: 100%;">
    </p>
    <h3><?php _e('Features', 'tecnoinfor'); ?></h3>
    <div id="functionalities-container">
        <?php foreach ($functionalities as $index => $func) : ?>
            <div class="functionality-item" style="margin-bottom: 20px; border: 1px solid #ddd; padding: 10px;">
                <p>
                    <label><?php _e('Feature Title', 'tecnoinfor'); ?></label><br>
                    <input type="text" name="software_functionalities[<?php echo $index; ?>][title]" value="<?php echo esc_attr($func['title']); ?>" style="width: 100%;">
                </p>
                <p>
                    <label><?php _e('Description', 'tecnoinfor'); ?></label><br>
                    <textarea name="software_functionalities[<?php echo $index; ?>][description]" style="width: 100%;"><?php echo esc_textarea($func['description']); ?></textarea>
                </p>
                <p>
                    <label><?php _e('Ícone (Bootstrap Icons, ex.: bi-gear)', 'tecnoinfor'); ?></label><br>
                    <input type="text" name="software_functionalities[<?php echo $index; ?>][icon]" value="<?php echo esc_attr($func['icon']); ?>" style="width: 100%;">
                    <small>Ex.: <code>bi-gear</code>, <code>bi-lock</code>. <?php _e('See more at', 'tecnoinfor'); ?> <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a>.</small>
                </p>
                <button type="button" class="remove-functionality button"><?php _e('Remove', 'tecnoinfor'); ?></button>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" id="add-functionality" class="button"><?php _e('Add Feature', 'tecnoinfor'); ?></button>
    <script>
        jQuery(document).ready(function($) {
            let index = <?php echo count($functionalities); ?>;
            $('#add-functionality').click(function() {
                $('#functionalities-container').append(
                    '<div class="functionality-item" style="margin-bottom: 20px; border: 1px solid #ddd; padding: 10px;">' +
                        '<p><label><?php _e('Feature Title', 'tecnoinfor'); ?></label><br>' +
                        '<input type="text" name="software_functionalities[' + index + '][title]" style="width: 100%;"></p>' +
                        '<p><label><?php _e('Description', 'tecnoinfor'); ?></label><br>' +
                        '<textarea name="software_functionalities[' + index + '][description]" style="width: 100%;"></textarea></p>' +
                        '<p><label><?php _e('Ícone (Bootstrap Icons, ex.: bi-gear)', 'tecnoinfor'); ?></label><br>' +
                        '<input type="text" name="software_functionalities[' + index + '][icon]" style="width: 100%;">' +
                        '<small>Ex.: <code>bi-gear</code>, <code>bi-lock</code>. Veja mais em <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a>.</small></p>' +
                        '<button type="button" class="remove-functionality button"><?php _e('Remove', 'tecnoinfor'); ?></button>' +
                    '</div>'
                );
                index++;
            });
            $(document).on('click', '.remove-functionality', function() {
                $(this).closest('.functionality-item').remove();
            });
        });
    </script>
    <?php
}

// Salvar Dados do Meta Box
function salvar_dados_software($post_id) {
    if (!isset($_POST['software_nonce']) || !wp_verify_nonce($_POST['software_nonce'], 'salvar_dados_software')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array('software_download_link', 'software_video_url', 'software_trial_period');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }

    if (isset($_POST['software_functionalities'])) {
        $functionalities = array();
        foreach ($_POST['software_functionalities'] as $func) {
            if (!empty($func['title']) || !empty($func['description'])) {
                $functionalities[] = array(
                    'title' => sanitize_text_field($func['title']),
                    'description' => sanitize_textarea_field($func['description']),
                    'icon' => sanitize_text_field($func['icon']),
                );
            }
        }
        update_post_meta($post_id, '_software_functionalities', $functionalities);
    } else {
        delete_post_meta($post_id, '_software_functionalities');
    }
}
add_action('save_post', 'salvar_dados_software');