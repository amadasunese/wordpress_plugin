<?php
if (!defined('ABSPATH')) {
    exit;
}

class ObserverMan_Pro_OpenAI {

    private $endpoint = 'https://api.openai.com/v1/chat/completions';
    private $model    = 'gpt-4o-mini';

    /**
     * Generate AI response
     */
    public function generate($prompt) {

        $options = get_option('observerman_pro_settings', []);

        $api_key         = $options['openai_key'] ?? '';
        $tone            = $options['ai_tone'] ?? 'neutral';
        $response_length = absint($options['response_length'] ?? 150);

        if (empty($api_key)) {
            return __('ObserverMan AI is not configured yet.', 'observerman-pro');
        }

        /**
         * Build system prompt dynamically
         */
        $system_prompt = sprintf(
            'You are ObserverMan, an AI assistant for a WordPress website. Respond in a %s tone. Keep responses under %d words.',
            $tone,
            $response_length
        );

        $body = [
            'model' => $this->model,
            'messages' => [
                [
                    'role'    => 'system',
                    'content' => $system_prompt,
                ],
                [
                    'role'    => 'user',
                    'content' => $prompt,
                ],
            ],
            'temperature' => 0.4,
        ];

        $response = wp_remote_post(
            $this->endpoint,
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $api_key,
                    'Content-Type'  => 'application/json',
                ],
                'body'    => wp_json_encode($body),
                'timeout' => 20,
            ]
        );

        if (is_wp_error($response)) {
            return __('AI request failed. Please try again.', 'observerman-pro');
        }

        $code = wp_remote_retrieve_response_code($response);
        $raw  = wp_remote_retrieve_body($response);

        if ($code !== 200) {
            return __('AI service returned an error.', 'observerman-pro');
        }

        $data = json_decode($raw, true);

        if (!isset($data['choices'][0]['message']['content'])) {
            return __('No AI response received.', 'observerman-pro');
        }

        return wp_kses_post($data['choices'][0]['message']['content']);
    }
    
    public function generate_with_messages(array $messages) {

        $options = get_option('observerman_pro_settings', []);
    
        $api_key         = $options['openai_key'] ?? '';
        $tone            = $options['ai_tone'] ?? 'neutral';
        $response_length = absint($options['response_length'] ?? 150);
    
        if (empty($api_key)) {
            return __('ObserverMan AI is not configured yet.', 'observerman-pro');
        }
    
        $system_prompt = sprintf(
            'You are ObserverMan, an AI assistant with a %s tone. Keep replies under %d words.',
            $tone,
            $response_length
        );
    
        array_unshift($messages, [
            'role'    => 'system',
            'content' => $system_prompt,
        ]);
    
        $body = [
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => 0.4,
        ];
    
        $response = wp_remote_post(
            $this->endpoint,
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $api_key,
                    'Content-Type'  => 'application/json',
                ],
                'body'    => wp_json_encode($body),
                'timeout' => 20,
            ]
        );
    
        if (is_wp_error($response)) {
            return __('AI request failed.', 'observerman-pro');
        }
    
        $data = json_decode(wp_remote_retrieve_body($response), true);
    
        return $data['choices'][0]['message']['content'] ?? __('No AI response.', 'observerman-pro');
    }
}    
