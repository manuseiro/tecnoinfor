<?php get_header(); ?>
<main class="main-content d-flex flex-column justify-content-center align-items-center text-center" style="min-height: 100vh;">
    <meta name="robots" content="noindex">
    <div class="container py-5">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/404.svg" 
             alt="<?php _e('Page not found', 'tecnoinfor'); ?>" 
             class="img-fluid mb-4" 
             style="max-width: 40%; height: 30%;">
        <h6 class="display-4 mb-3 fw-bold"><?php _e('Error 404. Page not found', 'tecnoinfor'); ?></h6>
        <p class="lead mb-4"><?php _e("The post or page that you are looking for either has just moved or doesn't exist in this server.", 'tecnoinfor'); ?></p>
        <div class="d-grid gap-2 col-6 mx-auto mb-4 mb-lg-3">
            <a class="btn btn-primary btn-lg" href="<?php echo home_url(); ?>"><?php _e('Back to Home', 'tecnoinfor'); ?></a>
            <form role="search" method="get" action="<?php echo home_url('/'); ?>" class="d-flex gap-2 mt-3">
                <input type="search" class="form-control" placeholder="<?php _e('Search...', 'tecnoinfor'); ?>" value="<?php echo get_search_query(); ?>" name="s">
                <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
            </form>
        </div>
    </div>
</main>
<?php get_footer(); ?>