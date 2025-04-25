<?php
// Função para registrar o Custom Post Type "FAQs"
function registrar_cpt_faqs(){
    $labels = array(
        'name' => _x('FAQs', 'post type general name'),
        'singular_name' => _x('FAQ', 'post type singular name'),
        'add_new' => _x('Add New FAQ', 'FAQ'),
        'add_new_item' => __('Add New FAQ'),
        'edit_item' => __('Edit FAQ'),
        'new_item' => __('New FAQ'),
        'view_item' => __('View FAQ'),
        'search_items' => __('Search FAQs'),
        'not_found' =>  __('No FAQs Found'),
        'not_found_in_trash' => __('No FAQs found in the Trash'),
        'menu_icon' => 'dashicons-info',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'faqs'),
        'capability_type' => 'post', // Alterado para 'post' para permissões mais amplas
        'hierarchical' => false,
        'menu_position' => 5,
        'supports' => array('title', 'editor'), // 'excerpt' pode ser removido
        'has_archive' => true,
    );

    register_post_type('faqs', $args);
}

// Hook para registrar o Custom Post Type
add_action('init', 'registrar_cpt_faqs');

// Função para registrar a Taxonomia "Assunto"
function registrar_taxonomia_assunto() {
    $labels = array(
        'name'              => _x('Subjects', 'taxonomy general name'),
        'singular_name'     => _x('Subject', 'taxonomy singular name'),
        'search_items'      => __('Search Subjects'),
        'all_items'         => __('All Subjects'),
        'parent_item'       => __('Parent Subject'),
        'parent_item_colon' => __('Parent Subject:'),
        'edit_item'         => __('Edit Subject'),
        'update_item'       => __('Update Subject'),
        'add_new_item'      => __('Add New Subject'),
        'new_item_name'     => __('New Subject Name'),
        'menu_name'         => __('Subjects'),
    );

    $args = array(
        'hierarchical'      => true, // Como categorias, use true para hierarquia
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'assunto'),
    );

    register_taxonomy('assunto', array('faqs'), $args);
}

// Hook para registrar a Taxonomia
add_action('init', 'registrar_taxonomia_assunto');

// Adicionar Meta Box para Relacionar Software
function adicionar_meta_box_faqs() {
    add_meta_box(
        'faq_related_software',
        __('Related Software', 'tecnoinfor'),
        'renderizar_meta_box_faqs',
        'faqs',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'adicionar_meta_box_faqs');

function renderizar_meta_box_faqs($post) {
    wp_nonce_field('salvar_dados_faqs', 'faqs_nonce');
    $related_software = get_post_meta($post->ID, '_related_software', true);
    $softwares = get_posts(array('post_type' => 'software', 'posts_per_page' => -1));
    ?>
    <label for="related_software"><?php _e('Select a Software', 'tecnoinfor'); ?></label>
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

function salvar_dados_faqs($post_id) {
    if (!isset($_POST['faqs_nonce']) || !wp_verify_nonce($_POST['faqs_nonce'], 'salvar_dados_faqs')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['related_software'])) {
        update_post_meta($post_id, '_related_software', sanitize_text_field($_POST['related_software']));
    }
}
add_action('save_post_faqs', 'salvar_dados_faqs');
