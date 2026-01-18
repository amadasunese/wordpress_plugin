<?php

/**
 * Plugin Name: EA Digital Newspaper Library
 * Description: Create a professional digital newspaper and PDF archive with a featured current edition.
 * Version: 1.0.0
 * Author: Ese Amadasun
 * Author URI: https://amadasunese.pythonanywhere.com
 * Text Domain: ea-digital-newspaper
 * Domain Path: /languages
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */


if (!defined('ABSPATH')) exit;

define('TNO_PATH', plugin_dir_path(__FILE__));
define('TNO_URL', plugin_dir_url(__FILE__));

require_once TNO_PATH . 'includes/post-type.php';
require_once TNO_PATH . 'includes/meta-boxes.php';
require_once TNO_PATH . 'includes/save-meta.php';
require_once TNO_PATH . 'includes/settings-page.php';
require_once TNO_PATH . 'includes/archive-query.php';

add_action('wp_enqueue_scripts', function () {

    if (
        is_post_type_archive('digital_edition') ||
        is_singular('digital_edition')
    ) {
        wp_enqueue_style(
            'tno-newspaper',
            TNO_URL . 'assets/css/newspaper.css',
            [],
            '1.2'
        );

        wp_enqueue_script(
            'tno-archive',
            TNO_URL . 'assets/js/archive-toggle.js',
            ['jquery'],
            '1.2',
            true
        );
    }

}, 20);


add_filter('template_include', function ($template) {
    if (is_singular('digital_edition')) {
        return TNO_PATH . 'templates/single-digital_edition.php';
    }
    if (is_post_type_archive('digital_edition')) {
        return TNO_PATH . 'templates/archive-digital_edition.php';
    }
    return $template;
});
