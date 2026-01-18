<?php
add_filter('template_include', function ($template) {

    if (is_singular('pdf_item')) {
        return WP_PDF_LIB_PATH . 'templates/single-pdf_item.php';
    }

    if (is_post_type_archive('pdf_item')) {
        return WP_PDF_LIB_PATH . 'templates/archive-pdf_item.php';
    }

    return $template;
});
