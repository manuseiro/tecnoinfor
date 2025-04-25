<?php get_header(); ?>
<main class="main-content">
    <div class="px-4 py-5 text-center text-white bg-primary">
        <h1 class="display-2 pt-5 fw-bold"><?php printf(esc_html__('Search results for: "%s"', 'tecnoinfor'), get_search_query()); ?></h1>
    </div>

    <!-- Conteúdo da página -->
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <?php
                $search_query = new WP_Query(array(
                    'post_type' => 'post',
                    's' => get_search_query(),
                    'paged' => get_query_var('paged') ? get_query_var('paged') : 1
                ));
                ?>

                <?php if ($search_query->have_posts()) : ?>
                    <div class="row">
                        <?php while ($search_query->have_posts()) : $search_query->the_post(); ?>
                            <div class="col-12 col-md-6 col-lg-4 mb-4">
                                <div class="card border-0">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('post_thumb', array('class' => 'card-img-top rounded', 'loading' => 'lazy')); ?>
                                        </a>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h2 class="fs-5">
                                            <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark">
                                                <?php the_title(); ?>
                                            </a>
                                        </h2>
                                        <p class="card-text">
                                            <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                                        </p>
                                        <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm"><?php _e('Read more', 'tecnoinfor'); ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <!-- Paginação -->
                    <div class="pagination-search py-5">
                        <nav aria-label="<?php esc_attr_e('Pagination', 'tecnoinfor'); ?>">
                            <ul class="pagination justify-content-center">
                                <?php
                                $big = 999999999;
                                $pagination_links = paginate_links(array(
                                    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                                    'format' => '?paged=%#%',
                                    'current' => max(1, get_query_var('paged')),
                                    'total' => $search_query->max_num_pages,
                                    'type' => 'array',
                                ));

                                if ($pagination_links) :
                                    foreach ($pagination_links as $link) :
                                        $class = strpos($link, 'current') !== false ? 'page-item active' : 'page-item';
                                        echo '<li class="' . $class . '"><span class="page-link">' . $link . '</span></li>';
                                    endforeach;
                                endif;
                                ?>
                            </ul>
                        </nav>
                    </div>
                <?php else : ?>
                    <h2 class="fw-bold"><?php _e('No results found for:', 'tecnoinfor'); ?> <?php echo get_search_query(); ?></h2>
                    <p><?php _e('Try searching for different keywords or see some suggestions below.', 'tecnoinfor'); ?></p>
                    <div class="row mt-4">
                        <?php
                        $recent_posts = new WP_Query(array('post_type' => 'post', 'posts_per_page' => 3));
                        if ($recent_posts->have_posts()) :
                            while ($recent_posts->have_posts()) : $recent_posts->the_post();
                        ?>
                                <div class="col-md-4">
                                    <h3 class="fs-6"><a href="<?php the_permalink(); ?>" class="text-dark"><?php the_title(); ?></a></h3>
                                </div>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
</main>
<?php get_footer(); ?>