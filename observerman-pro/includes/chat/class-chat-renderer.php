<?php
if (!defined('ABSPATH')) {
    exit;
}

class ObserverMan_Pro_Chat_Renderer {

    public function render() {

        if (is_admin()) {
            return;
        }
    
        $options = get_option('observerman_pro_settings', []);
    
        // Disable ONLY if explicitly turned off
        if (isset($options['enable_chat']) && !$options['enable_chat']) {
            return;
        }

    
        include OBSERVERMAN_PRO_PATH . 'templates/chat-widget.php';
    }
}