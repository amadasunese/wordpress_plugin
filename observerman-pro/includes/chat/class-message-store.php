<?php
if (!defined('ABSPATH')) exit;

class ObserverMan_Pro_Message_Store {

    private $table;

    public function __construct() {
        global $wpdb;
        $this->table = $wpdb->prefix . 'observerman_conversations';
    }

    public function store($session_id, $post_id, $role, $message) {
        global $wpdb;

        $wpdb->insert(
            $this->table,
            [
                'session_id' => sanitize_text_field($session_id),
                'post_id'    => absint($post_id),
                'user_ip'    => $_SERVER['REMOTE_ADDR'] ?? '',
                'role'       => sanitize_text_field($role),
                'message'    => wp_kses_post($message),
            ]
        );
    }
}
