<?php
get_header();
?>

<main class="main-content">

<div class="container my-5 p-5">
    <article class="changelog">
        <h1><?php the_title(); ?></h1>
        <p class="changelog-version">
            <?php _e('Version:', 'tecnoinfor'); ?> <?php echo get_post_meta(get_the_ID(), '_changelog_version', true); ?>
        </p>
        <p class="changelog-date">
            <?php _e('Released on:', 'tecnoinfor'); ?> <?php echo get_post_meta(get_the_ID(), '_changelog_release_date', true); ?>
        </p>
        <div class="changelog-content">
            <?php the_content(); ?>
        </div>
    </article>
</div>

</main>

<?php get_footer(); ?>