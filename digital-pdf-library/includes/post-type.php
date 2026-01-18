<?php
function dpl_register_post_type_for_flush() {
    register_post_type('digital_pdf', [
        'labels' => [
            'name' => __('Digital PDFs', 'digital-pdf-library'),
            'singular_name' => __('Digital PDF', 'digital-pdf-library'),
        ],
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-media-document',
        'supports' => ['title', 'editor', 'thumbnail'],
        'rewrite' => ['slug' => 'pdf-library'],
        'show_in_rest' => true,
    ]);
}

add_action('init', 'dpl_register_post_type_for_flush');
