<?php
if (!defined('ABSPATH')) {
    exit;
}

require_once OBSERVERMAN_PRO_PATH . 'includes/ai/class-ai-engine.php';
require_once OBSERVERMAN_PRO_PATH . 'includes/chat/class-message-store.php';
require_once OBSERVERMAN_PRO_PATH . 'includes/security/class-rate-limiter.php';

class ObserverMan_Pro_Chat_Controller {

    public function register() {
        add_action('wp_ajax_observerman_chat', [$this, 'handle']);
        add_action('wp_ajax_nopriv_observerman_chat', [$this, 'handle']);
    }

    public function handle() {

        // 🔐 Security first
        check_ajax_referer('observerman_chat_nonce', 'nonce');

        $message = sanitize_text_field($_POST['message'] ?? '');
        $post_id = absint($_POST['post_id'] ?? 0);

        if (empty($message)) {
            wp_send_json_error([
                'message' => 'Empty message',
            ]);
        }

        // Session handling
        $session_id = wp_get_session_token();
        if (!$session_id) {
            $session_id = wp_generate_uuid4();
        }

        // 🔒 RATE LIMITING (STEP 7)
        $limiter = new ObserverMan_Pro_Rate_Limiter();

        $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $rate_key   = $session_id ?: $ip_address;

        if (!$limiter->is_allowed($rate_key)) {
            wp_send_json_error([
                'message'     => 'You are sending messages too fast. Please wait a moment.',
                'retry_after' => $limiter->retry_after($rate_key),
            ], 429);
        }

        $store = new ObserverMan_Pro_Message_Store();

        // Store user message
        $store->store($session_id, $post_id, 'user', $message);

        // Build AI context
        $context = $this->build_context($post_id, $message);

        // AI response
        $ai    = new ObserverMan_Pro_AI_Engine();
        $reply = $ai->respond($context, $session_id);

        // Store AI response
        $store->store($session_id, $post_id, 'ai', $reply);

        wp_send_json_success([
            'reply' => wp_kses_post($reply),
        ]);
    }

    private function build_context($post_id, $message) {

        if (!$post_id) {
            return $message;
        }

        $post = get_post($post_id);
        if (!$post) {
            return $message;
        }

        $cats = wp_get_post_categories($post_id, ['fields' => 'names']);

        return sprintf(
            "Page Title: %s\nCategory: %s\nExcerpt: %s\n\nUser Question: %s",
            $post->post_title,
            implode(', ', $cats),
            wp_trim_words(strip_tags($post->post_content), 40),
            $message
        );
    }
}
