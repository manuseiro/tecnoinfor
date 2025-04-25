
<?php
// Metabox para subtítulo (já implementado anteriormente)
function tecnoinfor_add_subtitle_metabox() {
    add_meta_box(
        'tecnoinfor_subtitle_metabox',
        'Subtítulo da Página',
        'tecnoinfor_subtitle_metabox_callback',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'tecnoinfor_add_subtitle_metabox');

function tecnoinfor_subtitle_metabox_callback($post) {
    wp_nonce_field('tecnoinfor_save_subtitle_metabox', 'tecnoinfor_subtitle_nonce');
    $subtitle = get_post_meta($post->ID, '_tecnoinfor_subtitle', true);
    ?>
    <label for="tecnoinfor_subtitle">Subtítulo (exibido no banner):</label><br>
    <input type="text" id="tecnoinfor_subtitle" name="tecnoinfor_subtitle" value="<?php echo esc_attr($subtitle); ?>" style="width: 100%; max-width: 500px;" />
    <p class="description">Insira um subtítulo opcional para a página "Sobre".</p>
    <?php
}

function tecnoinfor_save_subtitle_metabox($post_id) {
    if (!isset($_POST['tecnoinfor_subtitle_nonce']) || !wp_verify_nonce($_POST['tecnoinfor_subtitle_nonce'], 'tecnoinfor_save_subtitle_metabox')) {
        return;
    }
    if (!current_user_can('edit_page', $post_id)) {
        return;
    }
    if (isset($_POST['tecnoinfor_subtitle'])) {
        update_post_meta($post_id, '_tecnoinfor_subtitle', sanitize_text_field($_POST['tecnoinfor_subtitle']));
    }
}
add_action('save_post', 'tecnoinfor_save_subtitle_metabox');
?>