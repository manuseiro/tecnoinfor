<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mb-4">
    <div class="card h-100 shadow-sm cliente-card">
        <div class="card-img-top text-center position-relative">
            <?php if (has_post_thumbnail()) : ?>
                <a href="<?php the_permalink(); ?>" class="d-block overflow-hidden">
                    <?php the_post_thumbnail('cliente-post', ['class' => 'img-fluid cliente-thumbnail']); ?>
                </a>
            <?php else : ?>
                <a href="<?php the_permalink(); ?>" class="d-block overflow-hidden">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/cliente-default.jpg'); ?>" 
                         class="img-fluid cliente-thumbnail" 
                         alt="<?php the_title_attribute(); ?>" 
                         title="<?php the_title_attribute(); ?>">
                </a>
            <?php endif; ?>
        </div>
        <div class="card-body text-center">
            <h5 class="card-title cliente-title"><?php the_title(); ?></h5>
            <p class="card-text cliente-excerpt"><?php the_excerpt(); ?></p>
            <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm mt-2">Ver mais</a>
        </div>
    </div>
</div>
