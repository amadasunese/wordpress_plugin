<?php
if (!defined('ABSPATH')) {
    exit;
}

class ObserverMan_Pro_Settings {

    public function register() {
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function register_settings() {

        /**
         * Register settings
         */
        register_setting(
            'observerman_pro_settings_group',
            'observerman_pro_settings',
            [$this, 'sanitize']
        );

        /**
         * Settings section
         */
        add_settings_section(
            'observerman_pro_main_section',
            'AI Configuration',
            null,
            'observerman-pro'
        );

        /**
         * API Key field
         */
        add_settings_field(
            'openai_key',
            'OpenAI API Key',
            [$this, 'render_openai_key'],
            'observerman-pro',
            'observerman_pro_main_section'
        );

        /**
         * Tone field
         */
        add_settings_field(
            'ai_tone',
            'AI Tone',
            [$this, 'render_tone'],
            'observerman-pro',
            'observerman_pro_main_section'
        );

        /**
         * Response length field
         */
        add_settings_field(
            'response_length',
            'Response Length',
            [$this, 'render_length'],
            'observerman-pro',
            'observerman_pro_main_section'
        );

        /**
         * 🧠 Conversation memory depth
         */
        add_settings_field(
            'memory_depth',
            'Conversation Memory Depth',
            [$this, 'render_memory'],
            'observerman-pro',
            'observerman_pro_main_section'
        );

        add_settings_field(
            'enable_chat',
            'Enable Chat Widget',
            [$this, 'render_enable_chat'],
            'observerman-pro',
            'observerman_pro_main_section'
        );
        
    }

    /**
     * Sanitize settings
     */
    // public function sanitize($input) {

    //     return [
    //         'openai_key'       => sanitize_text_field($input['openai_key'] ?? ''),
    //         'ai_tone'          => sanitize_text_field($input['ai_tone'] ?? 'neutral'),
    //         'response_length' => absint($input['response_length'] ?? 150),
    //         'memory_depth'    => absint($input['memory_depth'] ?? 6),
    //     ];
    // }

    public function sanitize($input) {

        return [
            'openai_key'       => sanitize_text_field($input['openai_key'] ?? ''),
            'ai_tone'          => sanitize_text_field($input['ai_tone'] ?? 'neutral'),
            'response_length' => absint($input['response_length'] ?? 150),
            'memory_depth'    => absint($input['memory_depth'] ?? 6),
            'enable_chat'     => isset($input['enable_chat']) ? 1 : 0,
        ];
    }

    public function render_enable_chat() {
        $options = get_option('observerman_pro_settings', []);
        ?>
        <label>
            <input type="checkbox"
                   name="observerman_pro_settings[enable_chat]"
                   value="1"
                   <?php checked($options['enable_chat'] ?? 1, 1); ?>>
            Enable the ObserverMan chat widget on the frontend
        </label>
        <?php
    }
    

    

    public function render_openai_key() {
        $options = get_option('observerman_pro_settings', []);
        ?>
        <input type="password"
               name="observerman_pro_settings[openai_key]"
               value="<?php echo esc_attr($options['openai_key'] ?? ''); ?>"
               class="regular-text">
        <p class="description">Your OpenAI API key (stored securely).</p>
        <?php
    }

    public function render_tone() {
        $options = get_option('observerman_pro_settings', []);
        $tone = $options['ai_tone'] ?? 'neutral';
        ?>
        <select name="observerman_pro_settings[ai_tone]">
            <option value="neutral" <?php selected($tone, 'neutral'); ?>>Neutral</option>
            <option value="formal" <?php selected($tone, 'formal'); ?>>Formal</option>
            <option value="friendly" <?php selected($tone, 'friendly'); ?>>Friendly</option>
            <option value="newsroom" <?php selected($tone, 'newsroom'); ?>>Newsroom</option>
            <option value="support" <?php selected($tone, 'support'); ?>>Support</option>
        </select>
        <?php
    }

    public function render_length() {
        $options = get_option('observerman_pro_settings', []);
        ?>
        <input type="number"
               name="observerman_pro_settings[response_length]"
               value="<?php echo esc_attr($options['response_length'] ?? 150); ?>"
               min="50" max="500">
        <p class="description">Maximum words per response.</p>
        <?php
    }

    public function render_memory() {
        $options = get_option('observerman_pro_settings', []);
        ?>
        <input type="number"
               name="observerman_pro_settings[memory_depth]"
               value="<?php echo esc_attr($options['memory_depth'] ?? 6); ?>"
               min="2" max="20">
        <p class="description">
            Number of previous messages the AI should remember per session.
        </p>
        <?php
    }
}
