<?php get_header(); ?>

<main class="dpl-single">
    <h1><?php the_title(); ?></h1>

    <?php $pdf = get_post_meta(get_the_ID(), '_dpl_pdf', true); ?>

    <?php if ($pdf): ?>
        <div class="dpl-actions">
            <a href="<?php echo esc_url($pdf); ?>" target="_blank">View</a>
            <a href="<?php echo esc_url($pdf); ?>" download>Download</a>
        </div>

        <div class="dpl-viewer">
            <iframe src="<?php echo esc_url($pdf); ?>" loading="lazy"></iframe>
        </div>
    <?php endif; ?>

    <article><?php the_content(); ?></article>
</main>

<?php get_footer(); ?>
