<?php
get_header();

$opts = get_option('tno_settings');
$archive_title = isset($opts['archive_title']) ? $opts['archive_title'] : 'The Nigerian Observer ePaper';
$show_today = !empty($opts['show_today']);

$current_year  = isset($_GET['year']) ? intval($_GET['year']) : '';
$current_month = isset($_GET['monthnum']) ? intval($_GET['monthnum']) : '';
?>

<div class="container my-5 digital-editions">

    <!-- ================= HEADER ================= -->
    <div class="text-center mb-4">
        <h1 class="edition-title">
            <?php echo esc_html($archive_title); ?>
            <?php if ($current_year): ?>
                — <?php echo esc_html($current_year); ?>
            <?php endif; ?>
        </h1>
        <p class="text-muted">
            <?php _e('Official digital archive of The Nigerian Observer newspaper.', 'tno-digital-newspaper'); ?>
        </p>
    </div>

    
    <!-- ================= CURRENT EDITION ================= -->
<?php if ($show_today): ?>
<div class="tno-today">

    <div class="card shadow-sm mb-4 p-4 text-center">
        <h4 class="mb-4"><?php _e('Current Edition', 'tno-digital-newspaper'); ?></h4>

        <?php
        $latest = new WP_Query([
            'post_type'      => 'digital_edition',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);

        if ($latest->have_posts()):
            while ($latest->have_posts()): $latest->the_post();
                $pdf = get_post_meta(get_the_ID(), '_tno_pdf', true);
        ?>

            <div class="tno-current-edition">

                <!-- Cover -->
                <div class="tno-current-cover mb-3">
                    <?php the_post_thumbnail('large', [
                        'class' => 'img-fluid mx-auto d-block'
                    ]); ?>
                </div>

                <!-- Title -->
                <h5 class="mb-2"><?php the_title(); ?></h5>
                <p class="text-muted mb-3"><?php echo get_the_date(); ?></p>

                <!-- Actions (UNDER IMAGE) -->
                <div class="d-flex justify-content-center gap-2">
                    <a href="<?php the_permalink(); ?>" class="btn btn-outline-dark btn-sm">
                        View
                    </a>
                    <?php if ($pdf): ?>
                        <a href="<?php echo esc_url($pdf); ?>" class="btn btn-dark btn-sm" download>
                            PDF
                        </a>
                    <?php endif; ?>
                </div>

            </div>

        <?php endwhile; wp_reset_postdata(); else: ?>
            <p class="text-muted mb-0">
                <?php _e('No editions have been published yet.', 'tno-digital-newspaper'); ?>
            </p>
        <?php endif; ?>

    </div>

</div>
<?php endif; ?>



    <!-- ================= SEARCH + FILTER ================= -->
    <div class="card shadow-sm mb-4 p-3">
        <form method="get"
              action="<?php echo esc_url(get_post_type_archive_link('digital_edition')); ?>"
              class="row g-3 align-items-end">

            <input type="hidden" name="post_type" value="digital_edition">

            <div class="col-md-4">
                <label class="form-label fw-semibold">Search Editions</label>
                <input type="text"
                       name="s"
                       class="form-control"
                       value="<?php echo esc_attr($_GET['s'] ?? ''); ?>">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Year</label>
                <select name="year" class="form-select">
                    <option value="">All</option>
                    <?php for ($y = date('Y'); $y >= 2000; $y--): ?>
                        <option value="<?php echo $y; ?>" <?php selected($current_year, $y); ?>>
                            <?php echo $y; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Month</label>
                <select name="monthnum" class="form-select">
                    <option value="">All</option>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?php echo $m; ?>" <?php selected($current_month, $m); ?>>
                            <?php echo date('F', mktime(0,0,0,$m)); ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-md-2 d-grid">
                <button class="btn btn-dark">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- ================= GRID ================= -->
    <div class="row g-4">

        <?php if (have_posts()): while (have_posts()): the_post();
            $pdf = get_post_meta(get_the_ID(), '_tno_pdf', true);
        ?>

        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="edition-card text-center h-100">

                <a href="<?php the_permalink(); ?>" class="edition-cover">
                    <?php the_post_thumbnail('medium', ['class'=>'img-fluid']); ?>
                </a>

                <div class="edition-meta mt-2">
                    <strong><?php echo get_the_date('F j, Y'); ?></strong>
                </div>

                <div class="edition-actions mt-2 d-flex justify-content-center gap-2">
                    <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline-dark">
                        View
                    </a>
                    <?php if ($pdf): ?>
                        <a href="<?php echo esc_url($pdf); ?>"
                           class="btn btn-sm btn-dark"
                           download>
                           PDF
                        </a>
                    <?php endif; ?>
                </div>

            </div>
        </div>

        <?php endwhile; else: ?>
            <p class="text-center text-muted">No editions found.</p>
        <?php endif; ?>

    </div>

    <div class="mt-5 text-center">
        <?php the_posts_pagination(); ?>
    </div>

</div>

<?php get_footer(); ?>
