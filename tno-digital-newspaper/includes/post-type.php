<?php
add_action('init', function () {
    register_post_type('digital_edition', [
        'labels' => [
            'name' => __('Digital Editions', 'tno-digital-newspaper'),
            'singular_name' => __('Digital Edition', 'tno-digital-newspaper'),
        ],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'digital-editions'],
        'menu_icon' => 'dashicons-media-document',
        'supports' => ['title', 'editor', 'thumbnail'],
    ]);
});
