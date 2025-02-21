<?php
// Verifica se estamos em um arquivo (como arquivos de tipo de post)
if (is_archive()) {
    // Título do arquivo (ex.: "Clientes")
    $banner_title = post_type_archive_title('', false);

    // Descrição do arquivo (opcional, se configurada no WordPress)
    $banner_description = get_the_archive_description();

    // Imagem padrão ou personalizada para o arquivo
    $banner_image = get_template_directory_uri() . '/assets/images/default-banner.jpg'; // Imagem padrão para arquivos

    // Permitir personalização com ACF ou outro campo
    if (function_exists('get_field') && get_field('header_image', 'option')) {
        $banner_image = get_field('header_image', 'option');
    }
} else {
    // Título e imagem padrão para páginas normais
    $banner_title = get_the_title();
    $banner_description = has_excerpt() ? get_the_excerpt() : '';
    $banner_image = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'full') : get_template_directory_uri() . '/assets/images/default-banner.jpg';
}
?>

<section id="banner-header" class="banner-breadcrumb bg-delp" style="
    background: linear-gradient(to bottom, rgba(11, 94, 215, 0.3) 0%, rgba(11, 94, 215, 0.4) 50%, rgba(11, 94, 215, 0.8) 100%), url('<?php echo esc_url($banner_image); ?>');
    background-size: cover; 
    background-position: center;">
    <div class="container">
        <div class="row p-5 pb-0 pe-lg-0 py-lg-5 align-items-center" style="position: relative; z-index: 2;">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb fw-bolder text-white text-shadow">
                    <li class="breadcrumb-item"><a href="<?php echo home_url(); ?>">Home</a></li>
                    <?php if (is_archive()) : ?>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo esc_html($banner_title); ?></li>
                    <?php else : ?>
                        <li class="breadcrumb-item active" aria-current="page"><?php the_title(); ?></li>
                    <?php endif; ?>
                </ol>
            </nav>

            <!-- Título e Descrição -->
            <div class="d-flex flex-column align-items-start text-left">
                <h1 class="display-4 text-white fw-bolder">
                    <?php echo esc_html($banner_title); ?>
                </h1>
                <div style="max-width:400px" class="text-white">
                    <!-- Descrição opcional -->
                    <div class="page-summary">
                        <?php if (!empty($banner_description)) : ?>
                            <p><?php echo esc_html($banner_description); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>