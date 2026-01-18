<?php get_header(); ?>

<main class="tno-single">

<?php if (have_posts()) : while (have_posts()) : the_post();
    $pdf = get_post_meta(get_the_ID(), '_tno_pdf', true);
?>

<header class="tno-single-header">
    <h1><?php the_title(); ?></h1>
    <p class="tno-date"><?php echo get_the_date(); ?></p>

    <?php if ($pdf): ?>
        <div class="tno-actions">
            <a href="<?php echo esc_url($pdf); ?>" target="_blank" class="tno-btn">
                View PDF
            </a>
            <a href="<?php echo esc_url($pdf); ?>" download class="tno-btn tno-btn-dark">
                Download PDF
            </a>
        </div>
    <?php endif; ?>
</header>

<?php if ($pdf): ?>
    <section class="tno-pdf-viewer">
        <iframe src="<?php echo esc_url($pdf); ?>" loading="lazy"></iframe>
        <p class="tno-fallback">
            Your browser does not support embedded PDFs.
            <a href="<?php echo esc_url($pdf); ?>">Download instead</a>.
        </p>
    </section>
<?php endif; ?>

<section class="tno-content">
    <?php the_content(); ?>
</section>

<nav class="tno-back">
    <a href="<?php echo esc_url(get_post_type_archive_link('digital_edition')); ?>">
        ← Back to All Editions
    </a>
</nav>

<?php endwhile; endif; ?>

</main>

<?php get_footer(); ?>
