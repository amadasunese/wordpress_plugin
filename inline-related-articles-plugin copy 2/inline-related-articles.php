<?php
/**
 * Plugin Name: EA Inline Related Articles Pro
 * Description: EA Inline Related Articles Pro is a lightweight but powerful editorial plugin that automatically injects contextually relevant articles inside the body of your posts.
 * Version: 2.0.2
 * Author: Ese Amadasun
 * Author URI: https://amadasunese.pythonanywhere.com
 * Text Domain: ea-inline-related-articles-pro
 * Domain Path: /languages
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Plugin constants (uniquely prefixed)
 */
define('EAIRAP_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('EAIRAP_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Load required files safely
 */
if (file_exists(EAIRAP_PLUGIN_PATH . 'admin-settings.php')) {
    require_once EAIRAP_PLUGIN_PATH . 'admin-settings.php';
}

if (file_exists(EAIRAP_PLUGIN_PATH . 'ai-relevance.php')) {
    require_once EAIRAP_PLUGIN_PATH . 'ai-relevance.php';
}

/**
 * Enqueue frontend assets
 */
add_action('wp_enqueue_scripts', 'eairap_enqueue_assets');
function eairap_enqueue_assets() {

    if (!is_single()) {
        return;
    }

    wp_enqueue_style(
        'eairap-inline-related-style',
        EAIRAP_PLUGIN_URL . 'assets/inline-related.css',
        [],
        '2.0'
    );
}

/**
 * Inject inline related articles into post content
 */
add_filter('the_content', 'eairap_inject_inline_related_articles', 20);
function eairap_inject_inline_related_articles($content) {

    // Basic safety checks
    if (!is_single() || !in_the_loop() || !is_main_query()) {
        return $content;
    }

    $settings = get_option('eairap_settings');
    if (empty($settings['paragraphs'])) {
        return $content;
    }

    // Prevent infinite loops
    static $eairap_is_processing = false;
    if ($eairap_is_processing) {
        return $content;
    }
    $eairap_is_processing = true;

    global $post;

    // Parse paragraph positions
    $raw_positions   = explode(',', $settings['paragraphs']);
    $insert_positions = array_filter(array_map('intval', $raw_positions));

    // Split content safely by paragraph
    $paragraphs = explode('</p>', $content);

    foreach ($insert_positions as $position) {

        $index = $position - 1; // Convert to zero-based index

        if (!isset($paragraphs[$index])) {
            continue;
        }

        if (function_exists('eairap_get_related_posts_html')) {
            $related_html = eairap_get_related_posts_html($post->ID);

            if (!empty($related_html)) {
                $paragraphs[$index] .= $related_html;
            }
        }
    }

    $content = implode('</p>', $paragraphs);

    // Reset guard
    $eairap_is_processing = false;

    return $content;
}
