<?php
/**
 * SEO dinâmico e schemas JSON-LD para o tema Tecnoinfor.
 *
 * @package Tecnoinfor
 */

// Função para adicionar Meta tags Dinâmicas
function tecnoinfor_dynamic_seo() {
    if (is_page_template('archive-downloads.php')) {
        $category_slug = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';
        $category_term = $category_slug ? get_term_by('slug', $category_slug, 'download_category') : false;
        $category_name_seo = ($category_term && !is_wp_error($category_term)) ? $category_term->name : '';

        $page_title  = $category_name_seo
            ? sprintf(__('Downloads - %s', 'tecnoinfor'), $category_name_seo)
            : __('All Downloads', 'tecnoinfor');

        $description = $category_name_seo
            ? sprintf(__('Browse downloads in the %s category at %s.', 'tecnoinfor'), $category_name_seo, get_bloginfo('name'))
            : sprintf(__('Explore all available downloads at %s.', 'tecnoinfor'), get_bloginfo('name'));

        $canonical_url = get_permalink() . ($category_slug ? '?category=' . $category_slug : '');

        // Meta Tags
        echo '<title>' . esc_html($page_title) . ' | ' . esc_html(get_bloginfo('name')) . '</title>';
        echo '<meta name="description" content="' . esc_attr($description) . '">';
        echo '<meta name="robots" content="index, follow">';
        echo '<link rel="canonical" href="' . esc_url($canonical_url) . '">';

        // Open Graph
        echo '<meta property="og:title" content="' . esc_attr($page_title) . '">';
        echo '<meta property="og:description" content="' . esc_attr($description) . '">';
        echo '<meta property="og:type" content="website">';
        echo '<meta property="og:url" content="' . esc_url($canonical_url) . '">';
        echo '<meta property="og:site_name" content="' . esc_html(get_bloginfo('name')) . '">';
        echo '<meta property="og:image" content="' . esc_url(get_template_directory_uri() . '/assets/images/default-banner.jpg') . '">';

        // Twitter Cards
        echo '<meta name="twitter:card" content="summary_large_image">';
        echo '<meta name="twitter:title" content="' . esc_attr($page_title) . '">';
        echo '<meta name="twitter:description" content="' . esc_attr($description) . '">';
        echo '<meta name="twitter:image" content="' . esc_url(get_template_directory_uri() . '/assets/images/default-banner.jpg') . '">';

        // Schema CollectionPage
        ?>
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "CollectionPage",
            "name": "<?php echo esc_js($page_title); ?>",
            "description": "<?php echo esc_js($description); ?>",
            "url": "<?php echo esc_url($canonical_url); ?>",
            "publisher": {
                "@type": "Organization",
                "name": "<?php bloginfo('name'); ?>",
                "url": "<?php echo esc_url(home_url('/')); ?>"
            }
        }
        </script>
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
                {
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Home",
                    "item": "<?php echo esc_url(home_url('/')); ?>"
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "Downloads",
                    "item": "<?php echo esc_url(get_permalink()); ?>"
                }
                <?php if ($category_name_seo) : ?>
                ,{
                    "@type": "ListItem",
                    "position": 3,
                    "name": "<?php echo esc_js($category_name_seo); ?>",
                    "item": "<?php echo esc_url(add_query_arg('category', sanitize_text_field($_GET['category']), get_permalink())); ?>"
                }
                <?php endif; ?>
            ]
        }
        </script>
        <?php
    }
}
add_action('wp_head', 'tecnoinfor_dynamic_seo');

// Schema AggregateRating dinâmico para depoimentos de clientes
function tecnoinfor_testimonials_schema() {
    $depoimentos = new WP_Query([
        'post_type'      => 'depoimentos',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ]);

    if ($depoimentos->have_posts()) {
        $total_rating = 0;
        $count = 0;
        while ($depoimentos->have_posts()) {
            $depoimentos->the_post();
            $rating = (int) get_post_meta(get_the_ID(), '_avaliacao', true);
            if ($rating > 0) {
                $total_rating += $rating;
                $count++;
            }
        }
        wp_reset_postdata();

        if ($count > 0) {
            $average = round($total_rating / $count, 1);
            $logo_url = has_custom_logo() ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : '';
            ?>
            <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "LocalBusiness",
                "name": "<?php bloginfo('name'); ?>",
                "image": "<?php echo esc_url($logo_url ?: get_template_directory_uri() . '/assets/images/default-banner.jpg'); ?>",
                "telephone": "<?php echo esc_js(get_informacao_empresa('telefone')); ?>",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "<?php echo esc_js(get_informacao_empresa('endereco')); ?>"
                },
                "aggregateRating": {
                    "@type": "AggregateRating",
                    "ratingValue": "<?php echo esc_js($average); ?>",
                    "reviewCount": "<?php echo esc_js($count); ?>",
                    "bestRating": "5",
                    "worstRating": "1"
                }
            }
            </script>
            <?php
        }
    }
}
add_action('wp_head', 'tecnoinfor_testimonials_schema');
