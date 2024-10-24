<?php
get_header();
?>

<main class="main-content">

<div class="container changelog-container">
    <?php while (have_posts()) : the_post(); ?>
        <h2><?php the_title(); ?></h2>
        <div class="content">
            <div class="d-flex gap-2 justify-content-center py-5">

                <!-- Botão Adicionado -->
                <?php if ($adicionado = get_post_meta(get_the_ID(), '_changelog_adicionado', true)) : ?>
                    <button class="btn btn-success rounded-pill px-3" type="button">Adicionado</button>
                    <p><?php echo nl2br(esc_html($adicionado)); ?></p>
                <?php endif; ?>

                <!-- Botão Correção -->
                <?php if ($correcao = get_post_meta(get_the_ID(), '_changelog_correcao', true)) : ?>
                    <button class="btn btn-primary rounded-pill px-3" type="button">Correção</button>
                    <p><?php echo nl2br(esc_html($correcao)); ?></p>
                <?php endif; ?>

                <!-- Botão Atualizado -->
                <?php if ($atualizado = get_post_meta(get_the_ID(), '_changelog_atualizado', true)) : ?>
                    <button class="btn btn-info rounded-pill px-3" type="button">Atualizado</button>
                    <p><?php echo nl2br(esc_html($atualizado)); ?></p>
                <?php endif; ?>

                <!-- Botão Melhorado -->
                <?php if ($melhorado = get_post_meta(get_the_ID(), '_changelog_melhorado', true)) : ?>
                    <button class="btn btn-warning rounded-pill px-3" type="button">Melhorado</button>
                    <p><?php echo nl2br(esc_html($melhorado)); ?></p>
                <?php endif; ?>

                <!-- Botão Removido -->
                <?php if ($removido = get_post_meta(get_the_ID(), '_changelog_removido', true)) : ?>
                    <button class="btn btn-danger rounded-pill px-3" type="button">Removido</button>
                    <p><?php echo nl2br(esc_html($removido)); ?></p>
                <?php endif; ?>

            </div>
        </div>

        <div class="content-footer">
            <p>Versão: <?php echo esc_html(get_the_title()); ?> | Lançado em: <?php the_date(); ?></p>
        </div>

    <?php endwhile; ?>
</div>

</main>

<?php get_footer(); ?>