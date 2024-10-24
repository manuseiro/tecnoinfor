<?php get_header(); ?>
<main class="main-content">
<div class="px-4 py-5 text-center text-white bg-primary">
  
  <h1 class="display-2 pt-5 fw-bold"><?php the_title(); ?></h1>
  <div class="col-lg-8 pb-5 mx-auto">
    <?php if (has_excerpt()) : ?>
      <p class="lead my-5"><?php echo get_the_excerpt(); ?></p>
    <?php endif; ?>
  </div>
</div>

  <!-- Conteúdo da página -->
  <div class="container my-5">
    <div class="row">
      <div class="col col-12">
        <?php if (have_posts()) : ?>
          <h1 class="fw-bold"><?php printf(__('Search results for: %s', 'tecnoinfor'), get_search_query()); ?></h1>

          <?php
          // Loop para exibir os resultados da busca
          while (have_posts()) : the_post(); ?>

            <div class="search-result-item border-bottom py-3">
              <h2 class="fs-4">
                <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark">
                  <?php the_title(); ?>
                </a>
              </h2>
              <p class="search-excerpt">
                <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
              </p>
              <a href="<?php the_permalink(); ?>" class="btn btn-primary"><?php _e('read more', 'tecnoinfor'); ?></a>
            </div>

          <?php endwhile; ?>

          <!-- Paginação -->
          <div class="pagination mt-5">
            <?php
            the_posts_pagination(array(
              'mid_size'  => 2,
              'prev_text' => __('« Previous', 'tecnoinfor'),
              'next_text' => __('Next »', 'tecnoinfor'),
            ));
            ?>
          </div>

        <?php else : ?>
          <h2 class="fw-bold"><?php _e('No results found for:', 'tecnoinfor'); ?> <?php echo get_search_query(); ?></h2>
          <p><?php _e('Try searching for different keywords or see some suggestions below.', 'tecnoinfor'); ?></p>

          <?php
          // Sugestão de posts recentes caso nada seja encontrado
          $recent_posts = new WP_Query(array(
            'posts_per_page' => 5,
          ));

          if ($recent_posts->have_posts()) :
            echo '<ul class="recent-posts-list">';
            while ($recent_posts->have_posts()) : $recent_posts->the_post();
              echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            endwhile;
            echo '</ul>';
            wp_reset_postdata();
          endif;
          ?>

        <?php endif; ?>
      </div>
    </div>
  </div>
</main>
<?php get_footer(); ?>
