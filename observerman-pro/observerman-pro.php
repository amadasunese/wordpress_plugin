<?php
/**
 * Plugin Name: ObserverMan Pro
 * Description: AI-powered contextual assistant for WordPress (Pro Edition)
 * Version: 1.0.1
 * Author: Ese Amadasun
 * Text Domain: observerman-pro
 */

if (!defined('ABSPATH')) {
    exit;
}

define('OBSERVERMAN_PRO_VERSION', '1.0.0');
define('OBSERVERMAN_PRO_PATH', plugin_dir_path(__FILE__));
define('OBSERVERMAN_PRO_URL', plugin_dir_url(__FILE__));

require_once OBSERVERMAN_PRO_PATH . 'includes/core/class-loader.php';
require_once OBSERVERMAN_PRO_PATH . 'includes/core/class-plugin.php';

register_activation_hook(__FILE__, 'observerman_pro_create_tables');

function observerman_pro_create_tables() {
    global $wpdb;

    $table = $wpdb->prefix . 'observerman_conversations';
    $charset = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        session_id VARCHAR(64),
        post_id BIGINT UNSIGNED,
        user_ip VARCHAR(45),
        role VARCHAR(10),
        message TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY post_id (post_id),
        KEY session_id (session_id)
    ) $charset;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}


function observerman_pro_run() {
    $plugin = new ObserverMan_Pro_Plugin();
    $plugin->run();
}

observerman_pro_run();
