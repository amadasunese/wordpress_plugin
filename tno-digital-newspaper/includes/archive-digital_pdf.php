<?php get_header(); ?>

<main class="dpl-archive">

    <header class="dpl-archive-header">
        <h1><?php post_type_archive_title(); ?></h1>
        <p><?php _e('Browse available digital PDF editions.', 'digital-pdf-library'); ?></p>
    </header>

    <?php if (have_posts()) : ?>
        <section class="dpl-grid">
            <?php while (have_posts()) : the_post();
                $pdf = get_post_meta(get_the_ID(), '_dpl_pdf', true);
            ?>
                <article class="dpl-card">

                    <a href="<?php the_permalink(); ?>" class="dpl-thumb">
                        <?php if (has_post_thumbnail()) :
                            the_post_thumbnail('medium');
                        else : ?>
                            <span class="dpl-placeholder">PDF</span>
                        <?php endif; ?>
                    </a>

                    <div class="dpl-card-meta">
                        <time><?php echo esc_html(get_the_date()); ?></time>
                    </div>

                    <div class="dpl-card-actions">
                        <a href="<?php the_permalink(); ?>" class="dpl-btn">
                            <?php _e('View', 'digital-pdf-library'); ?>
                        </a>
                        <?php if ($pdf) : ?>
                            <a href="<?php echo esc_url($pdf); ?>" download class="dpl-btn dpl-btn-dark">
                                <?php _e('Download', 'digital-pdf-library'); ?>
                            </a>
                        <?php endif; ?>
                    </div>

                </article>
            <?php endwhile; ?>
        </section>

        <nav class="dpl-pagination">
            <?php the_posts_pagination(); ?>
        </nav>

    <?php else : ?>
        <p class="dpl-empty"><?php _e('No PDFs found.', 'digital-pdf-library'); ?></p>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
