<?php
/**
 * Plugin Name: Digital PDF Library
 * Plugin URI: https://example.com
 * Description: Create a digital newspaper or PDF document library with responsive viewing.
 * Version: 1.0.0
 * Author: Ese Amadasun
 * Text Domain: digital-pdf-library
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) exit;

define('DPL_PATH', plugin_dir_path(__FILE__));
define('DPL_URL', plugin_dir_url(__FILE__));

require_once DPL_PATH . 'includes/post-type.php';
require_once DPL_PATH . 'includes/meta-boxes.php';
require_once DPL_PATH . 'includes/save-meta.php';

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'dpl-style',
        DPL_URL . 'assets/css/pdf-library.css',
        [],
        '1.0'
    );
});

add_filter('template_include', function ($template) {
    if (is_singular('digital_pdf')) {
        return DPL_PATH . 'templates/single-digital_pdf.php';
    }
    if (is_post_type_archive('digital_pdf')) {
        return DPL_PATH . 'templates/archive-digital_pdf.php';
    }
    return $template;
});


register_activation_hook(__FILE__, 'dpl_flush_rewrites');

function dpl_flush_rewrites() {
    // Register post type first
    require_once plugin_dir_path(__FILE__) . 'includes/post-type.php';
    dpl_register_post_type_for_flush();
    flush_rewrite_rules();
}
