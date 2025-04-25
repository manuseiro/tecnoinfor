<?php
// Registro do Custom Post Type "Depoimentos"
function tecnoinfor_register_depoimentos_cpt() {
    $labels = [
        'name'                => __('Testimonials', 'Post type general name', 'tecnoinfor'),
        'singular_name'       => __('Testimonial', 'Post type singular name', 'tecnoinfor'),
        'menu_name'           => __('Testimonials', 'Admin Menu text', 'tecnoinfor'),
        'name_admin_bar'      => __('Testimonial', 'Add New on Toolbar', 'tecnoinfor'),
        'add_new'             => __('Add New', 'tecnoinfor'),
        'add_new_item'        => __('Add New Testimonial', 'tecnoinfor'),
        'new_item'            => __('New Testimonial', 'tecnoinfor'),
        'edit_item'           => __('Edit Testimonial', 'tecnoinfor'),
        'view_item'           => __('View Testimonial', 'tecnoinfor'),
        'all_items'           => __('All Testimonials', 'tecnoinfor'),
        'search_items'        => __('Search Testimonials', 'tecnoinfor'),
        'not_found'           => __('No testimonials found.', 'tecnoinfor'),
        'not_found_in_trash'  => __('No testimonials found in Trash.', 'tecnoinfor'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => ['slug' => 'depoimentos'],
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-format-quote',
        'supports'           => ['title', 'editor', 'thumbnail'],
        'show_in_rest'       => true, // Suporte ao REST API (opcional para Gutenberg)
    ];

    register_post_type('depoimentos', $args);
}
add_action('init', 'tecnoinfor_register_depoimentos_cpt');

// Adicionar metabox para campos personalizados
function tecnoinfor_add_depoimentos_metabox() {
    add_meta_box(
        'tecnoinfor_depoimentos_info',
        __('Client Information', 'tecnoinfor'),
        'tecnoinfor_render_depoimentos_metabox',
        'depoimentos',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'tecnoinfor_add_depoimentos_metabox');

// Renderizar a metabox com Bootstrap 5
function tecnoinfor_render_depoimentos_metabox($post) {
    wp_nonce_field('tecnoinfor_save_depoimentos_meta', 'depoimentos_nonce');

    $avaliacao = get_post_meta($post->ID, '_avaliacao', true);
    $cliente   = get_post_meta($post->ID, '_cliente', true);
    $empresa   = get_post_meta($post->ID, '_empresa', true);
    $cargo     = get_post_meta($post->ID, '_cargo', true);

    // Adicionar Bootstrap 5 no admin (pode ser enfileirado separadamente)
    ?>
    <div class="container-fluid">
      <div class="row g-3">
        <div class="col-12">
          <label for="avaliacao" class="form-label fw-bold"><?php _e('Rating (1 to 5)', 'tecnoinfor'); ?></label>
          <select name="avaliacao" id="avaliacao" class="form-select">
            <?php for ($i = 1; $i <= 5; $i++) : ?>
              <option value="<?php echo $i; ?>" <?php selected($avaliacao, $i); ?>><?php echo $i; ?></option>
            <?php endfor; ?>
          </select>
          <small class="form-text text-muted"><?php _e('Select the rating given by the client.', 'tecnoinfor'); ?></small>
        </div>

        <div class="col-12">
          <label for="cliente" class="form-label fw-bold"><?php _e('Client Name', 'tecnoinfor'); ?></label>
          <input type="text" name="cliente" id="cliente" value="<?php echo esc_attr($cliente); ?>" class="form-control" required>
          <small class="form-text text-muted"><?php _e('Enter the name of the client.', 'tecnoinfor'); ?></small>
        </div>

        <div class="col-12">
          <label for="empresa" class="form-label fw-bold"><?php _e('Company', 'tecnoinfor'); ?></label>
          <input type="text" name="empresa" id="empresa" value="<?php echo esc_attr($empresa); ?>" class="form-control">
          <small class="form-text text-muted"><?php _e('Enter the company name (optional).', 'tecnoinfor'); ?></small>
        </div>

        <div class="col-12">
          <label for="cargo" class="form-label fw-bold"><?php _e('Client Position', 'tecnoinfor'); ?></label>
          <input type="text" name="cargo" id="cargo" value="<?php echo esc_attr($cargo); ?>" class="form-control">
          <small class="form-text text-muted"><?php _e('Enter the client’s job title (optional).', 'tecnoinfor'); ?></small>
        </div>
      </div>
    </div>
    <?php
}

// Salvar os campos personalizados
function tecnoinfor_save_depoimentos_meta($post_id) {
    if (!isset($_POST['depoimentos_nonce']) || !wp_verify_nonce($_POST['depoimentos_nonce'], 'tecnoinfor_save_depoimentos_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Validação e salvamento
    if (isset($_POST['avaliacao'])) {
        $avaliacao = intval($_POST['avaliacao']);
        if ($avaliacao >= 1 && $avaliacao <= 5) {
            update_post_meta($post_id, '_avaliacao', $avaliacao);
        }
    }

    if (isset($_POST['cliente'])) {
        $cliente = sanitize_text_field($_POST['cliente']);
        if (!empty($cliente)) {
            update_post_meta($post_id, '_cliente', $cliente);
        } else {
            delete_post_meta($post_id, '_cliente');
        }
    }

    if (isset($_POST['empresa'])) {
        update_post_meta($post_id, '_empresa', sanitize_text_field($_POST['empresa']));
    }

    if (isset($_POST['cargo'])) {
        update_post_meta($post_id, '_cargo', sanitize_text_field($_POST['cargo']));
    }
}
add_action('save_post_depoimentos', 'tecnoinfor_save_depoimentos_meta');

// Enfileirar Bootstrap 5 no admin apenas para a página de depoimentos
function tecnoinfor_enqueue_admin_depoimentos_assets($hook) {
    global $post_type;
    if (($hook === 'post.php' || $hook === 'post-new.php') && $post_type === 'depoimentos') {
        wp_enqueue_style('tecnoinfor_bootstrap-admin', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', [], '5.3.3');
    }
}
add_action('admin_enqueue_scripts', 'tecnoinfor_enqueue_admin_depoimentos_assets');