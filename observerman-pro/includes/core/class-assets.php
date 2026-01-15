<?php
if (!defined('ABSPATH')) {
    exit;
}

class ObserverMan_Pro_Assets {

    /**
     * Enqueue frontend (public) assets
     */
    public function enqueue_public() {

        // Get plugin settings
        $options = get_option('observerman_pro_settings', []);

        /**
         * ❌ Do NOT load chat assets if chat is disabled
         */
        // Disable ONLY if explicitly turned off
        if (isset($options['enable_chat']) && !$options['enable_chat']) {
            return;
        }

        // 🔴 FORCE jQuery to be enqueued
        wp_enqueue_script('jquery');
        

        // Load chat styles
        wp_enqueue_style(
            'observerman-pro-css',
            OBSERVERMAN_PRO_URL . 'assets/css/observman-pro.css',
            [],
            OBSERVERMAN_PRO_VERSION
        );

        // Load chat script
        wp_enqueue_script(
            'observerman-pro-js',
            OBSERVERMAN_PRO_URL . 'assets/js/observman-pro.js',
            ['jquery'],
            OBSERVERMAN_PRO_VERSION,
            true
        );

        /**
         * Safely determine current post ID
         */
        $post_id = is_singular() ? get_queried_object_id() : 0;

        /**
         * Localize script for AJAX + security
         */
        wp_localize_script(
            'observerman-pro-js',
            'observermanPro',
            [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('observerman_chat_nonce'),
                'postId'  => $post_id,
            ]
        );
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin() {

        // Admin styles always load (settings, analytics, license)
        wp_enqueue_style(
            'observerman-pro-admin-css',
            OBSERVERMAN_PRO_URL . 'assets/css/observman-pro.css',
            [],
            OBSERVERMAN_PRO_VERSION
        );
    }
}
