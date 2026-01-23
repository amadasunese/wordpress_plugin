<?php

get_header(); 
$opts = get_option('tno_settings');
$archive_title = $opts['archive_title'] ?? 'The Nigerian Observer ePaper';
$show_today = !empty($opts['show_today']);
?>

<main class="tno-archive">

<!-- <header class="tno-header">
    <h1>The Nigerian Observer – ePaper</h1>
    <p>Official Digital Newspaper Archive</p>
</header> -->

<header class="tno-header">
    <h1><?php echo esc_html($archive_title); ?></h1>
    <p><?php _e('Official Digital Newspaper Archive', 'tno-digital-newspaper'); ?></p>
</header>

<!-- 🔴 TODAY’S EDITION -->
<?php if ($show_today): ?>
<section class="tno-today">
    <h2><?php _e('Today’s Edition', 'tno-digital-newspaper'); ?></h2>

    <?php
    $today = new WP_Query([
        'post_type'      => 'digital_edition',
        'posts_per_page' => 1,
        'date_query'     => [
            [
                'year'  => date('Y'),
                'month' => date('m'),
                'day'   => date('d'),
            ]
        ]
    ]);

    if ($today->have_posts()):
        while ($today->have_posts()): $today->the_post();
            $pdf = get_post_meta(get_the_ID(), '_tno_pdf', true);
    ?>
        <article class="tno-today-card">
            <h3><?php the_title(); ?></h3>
            <a href="<?php the_permalink(); ?>" class="tno-btn">
                <?php _e('View', 'tno-digital-newspaper'); ?>
            </a>
            <?php if ($pdf): ?>
                <a href="<?php echo esc_url($pdf); ?>" download class="tno-btn tno-btn-dark">
                    PDF
                </a>
            <?php endif; ?>
        </article>
    <?php endwhile; wp_reset_postdata(); else: ?>
        <p><?php _e('No edition published today.', 'tno-digital-newspaper'); ?></p>
    <?php endif; ?>
</section>
<?php endif; ?>


<!-- 🔍 SEARCH + FILTER -->
<form class="tno-filter" method="get">
    <input type="search" name="s" placeholder="Search editions…" value="<?php echo esc_attr($_GET['s'] ?? ''); ?>">
    <button>Search</button>
</form>

<!-- 🗂 COLLAPSIBLE ARCHIVE -->
<section class="tno-collapsible">

<?php
global $wpdb;
$dates = $wpdb->get_results("
    SELECT DISTINCT YEAR(post_date) y, MONTH(post_date) m
    FROM $wpdb->posts
    WHERE post_type='digital_edition' AND post_status='publish'
    ORDER BY post_date DESC
");

$current = '';
foreach ($dates as $d):
    $key = $d->y . '-' . $d->m;
    if ($current !== $d->y):
        if ($current !== '') echo '</div>';
        echo "<h3 class='tno-year'>{$d->y}</h3><div class='tno-months'>";
        $current = $d->y;
    endif;
?>
    <a href="?year=<?php echo $d->y; ?>&monthnum=<?php echo $d->m; ?>">
        <?php echo date('F', mktime(0,0,0,$d->m)); ?>
    </a>
<?php endforeach; ?>
</div>

</section>

<!-- 📰 GRID -->
<section class="tno-grid">
<?php if (have_posts()): while (have_posts()): the_post();
    $pdf = get_post_meta(get_the_ID(), '_tno_pdf', true);
?>
<article class="tno-card">
    <?php the_post_thumbnail('medium'); ?>
    <h4><?php the_title(); ?></h4>
    <time><?php echo get_the_date(); ?></time>
    <a href="<?php the_permalink(); ?>">View</a>
    <?php if ($pdf): ?><a href="<?php echo esc_url($pdf); ?>" download>PDF</a><?php endif; ?>
</article>
<?php endwhile; endif; ?>
</section>

<?php the_posts_pagination(); ?>

</main>

<?php get_footer(); ?>
