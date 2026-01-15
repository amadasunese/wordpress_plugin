<?php
if (!defined('ABSPATH')) {
    exit;
}

class ObserverMan_Pro_License {

    private $option_key = 'observerman_pro_license';

    public function register() {
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function register_settings() {

        register_setting(
            'observerman_pro_license_group',
            $this->option_key,
            [$this, 'sanitize']
        );

        add_settings_section(
            'observerman_pro_license_section',
            'License Activation',
            null,
            'observerman-pro-license'
        );

        add_settings_field(
            'license_key',
            'License Key',
            [$this, 'render_field'],
            'observerman-pro-license',
            'observerman_pro_license_section'
        );
    }

    public function sanitize($input) {
        return [
            'key'    => sanitize_text_field($input['key'] ?? ''),
            'status' => 'inactive',
            'checked'=> time(),
        ];
    }

    public function render_field() {
        $license = get_option($this->option_key, []);
        ?>
        <input type="text"
               class="regular-text"
               name="<?php echo esc_attr($this->option_key); ?>[key]"
               value="<?php echo esc_attr($license['key'] ?? ''); ?>">
        <p class="description">
            Enter your ObserverMan Pro license key.
        </p>
        <?php
    }

    /**
     * Check if license is valid
     */
    public function is_valid() {
        $license = get_option($this->option_key, []);
        return ($license['status'] ?? '') === 'valid';
    }
}
