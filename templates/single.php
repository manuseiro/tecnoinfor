<?php get_header(); ?>
<main class="main-content">
    <?php 
    // Verifica se a página tem uma imagem destacada
    if (has_post_thumbnail()) {
        $banner_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
    } else {
        // Adiciona uma imagem padrão se não houver imagem destacada
        $banner_image = get_template_directory_uri() . '/assets/images/default-banner.jpg';
    }
    ?>

    <section class="banner-breadcrumb bg-delp" style="
    background: linear-gradient(to bottom, rgb(11, 94, 215, 0.3) 0%, rgba(11, 94, 215, 0.4) 50%, rgba(11, 94, 215, 0.8) 100%), url('<?php echo esc_url($banner_image); ?>');
    background-size: cover; 
    background-position: center;">
        <div class="row p-5 pb-0 pe-lg-0 py-lg-5 align-items-center" style="position: relative; z-index: 2;">
            <!-- Breadcrumb -->
            <?php echo tecnoinfor_get_breadcrumb(); ?>

            <!-- Título e Descrição -->
            <div class="d-flex flex-column align-items-start text-left">
                <h1 class="display-4 text-white fw-bolder">
                    <?php the_title(); ?>
                </h1>
                <div class="text-white col col-lg-6">
                    <!-- Descrição opcional, pode ser a introdução ou campo personalizado -->
                    <div class="page-summary">
                        <?php if (has_excerpt()) : ?>
                            <p><?php echo get_the_excerpt(); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Conteúdo da página -->
    <div class="container my-5 p-5">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="editor-wp pe-sm-3">
                    <?php while (have_posts()) : the_post(); the_content(); endwhile; ?>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="sidebar-related-posts">
                    <h3 class="fw-bold text-primary mb-4"><?php _e('Recent Posts', 'tecnoinfor'); ?></h3>
                    <?php
                    // Obtém as categorias do post atual
                    $categories = get_the_category();
                    $category_ids = !empty($categories) ? wp_list_pluck($categories, 'term_id') : array();

                    if (!empty($category_ids)) :
                        $related_args = array(
                            'post_type' => 'post',
                            'posts_per_page' => 5, // Limite de posts exibidos
                            'post__not_in' => array(get_the_ID()), // Exclui o post atual
                            'category__in' => $category_ids, // Filtra por categorias do post atual
                            'orderby' => 'date',
                            'order' => 'DESC',
                        );
                        $related_query = new WP_Query($related_args);

                        if ($related_query->have_posts()) :
                            while ($related_query->have_posts()) : $related_query->the_post();
                    ?>
                            <div class="related-post mb-4 d-flex align-items-center sticky-top">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>" class="me-3">
                                        <?php the_post_thumbnail('thumbnail', array(
                                            'class' => 'img-fluid rounded',
                                            'loading' => 'lazy',
                                            'style' => 'width: 100px; height: auto;'
                                        )); ?>
                                    </a>
                                <?php endif; ?>
                                <div class="flex-grow-1">
                                    <h3 class="fs-6 fw-bold mb-0">
                                        <a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                </div>
                            </div>
                    <?php
                            endwhile;
                        else :
                    ?>
                            <p class="text-muted"><?php _e('No related posts found.', 'tecnoinfor'); ?></p>
                    <?php
                        endif;
                        wp_reset_postdata();
                    else :
                    ?>
                        <p class="text-muted"><?php _e('No categories associated with this post.', 'tecnoinfor'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>
<?php get_footer(); ?>