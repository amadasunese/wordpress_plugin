<?php
if (!defined('ABSPATH')) {
    exit;
}

class ObserverMan_Pro_Session {

    /**
     * Get last N messages for session
     */
    public static function get_recent_messages($session_id, $limit = 6) {
        global $wpdb;

        $table = $wpdb->prefix . 'observerman_conversations';

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT role, message
                 FROM $table
                 WHERE session_id = %s
                 ORDER BY id DESC
                 LIMIT %d",
                $session_id,
                $limit
            ),
            ARRAY_A
        );
    }
}
