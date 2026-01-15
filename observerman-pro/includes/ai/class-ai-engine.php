<?php
if (!defined('ABSPATH')) {
    exit;
}

require_once OBSERVERMAN_PRO_PATH . 'includes/ai/class-openai.php';
require_once OBSERVERMAN_PRO_PATH . 'includes/licensing/class-license.php';
require_once OBSERVERMAN_PRO_PATH . 'includes/chat/class-session.php';

class ObserverMan_Pro_AI_Engine {

    public function respond($prompt, $session_id = null) {

        // 🔐 License check
        $license = new ObserverMan_Pro_License();
        if (!$license->is_valid()) {
            return __('ObserverMan Pro license is not active.', 'observerman-pro');
        }

        // Load settings
        $options = get_option('observerman_pro_settings', []);
        $memory_depth = absint($options['memory_depth'] ?? 6);

        $messages = [];

        // 🧠 Load memory
        if ($session_id) {
            $history = ObserverMan_Pro_Session::get_recent_messages(
                $session_id,
                $memory_depth
            );

            // Reverse to chronological order
            $history = array_reverse($history);

            foreach ($history as $row) {
                $messages[] = [
                    'role'    => $row['role'] === 'ai' ? 'assistant' : 'user',
                    'content' => wp_strip_all_tags($row['message']),
                ];
            }
        }

        // Append current prompt
        $messages[] = [
            'role'    => 'user',
            'content' => $prompt,
        ];

        $provider = new ObserverMan_Pro_OpenAI();

        return $provider->generate_with_messages($messages);
    }
}
