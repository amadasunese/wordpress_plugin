<?php
if (!defined('ABSPATH')) exit;

class ObserverMan_Pro_Analytics {

    public function render() {
        global $wpdb;
        $table = $wpdb->prefix . 'observerman_conversations';

        $total = $wpdb->get_var("SELECT COUNT(*) FROM $table");
        $top_questions = $wpdb->get_results("
            SELECT message, COUNT(*) AS count
            FROM $table
            WHERE role = 'user'
            GROUP BY message
            ORDER BY count DESC
            LIMIT 5
        ");
        ?>
        <h2>Analytics</h2>
        <p><strong>Total Messages:</strong> <?php echo esc_html($total); ?></p>

        <h3>Top User Questions</h3>
        <ul>
            <?php foreach ($top_questions as $q): ?>
                <li><?php echo esc_html($q->message); ?> (<?php echo intval($q->count); ?>)</li>
            <?php endforeach; ?>
        </ul>
        <?php
    }
}
