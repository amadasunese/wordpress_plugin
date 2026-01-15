<?php
if (!defined('ABSPATH')) exit;

require_once OBSERVERMAN_PRO_PATH . 'includes/core/class-assets.php';
require_once OBSERVERMAN_PRO_PATH . 'includes/admin/class-admin-menu.php';
require_once OBSERVERMAN_PRO_PATH . 'includes/chat/class-chat-controller.php';
require_once OBSERVERMAN_PRO_PATH . 'includes/admin/class-settings.php';
require_once OBSERVERMAN_PRO_PATH . 'includes/licensing/class-license.php';
require_once OBSERVERMAN_PRO_PATH . 'includes/chat/class-chat-renderer.php';





class ObserverMan_Pro_Plugin {

    protected $loader;

    public function __construct() {
        $this->loader = new ObserverMan_Pro_Loader();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }


    private function define_admin_hooks() {

        $admin = new ObserverMan_Pro_Admin_Menu();
        $this->loader->add_action('admin_menu', $admin, 'register_menu');
    
        $settings = new ObserverMan_Pro_Settings();
        $settings->register();
    
        $license = new ObserverMan_Pro_License();
        $license->register();
    }
    

    private function define_public_hooks() {
        $assets = new ObserverMan_Pro_Assets();
        $this->loader->add_action('wp_enqueue_scripts', $assets, 'enqueue_public');
        $this->loader->add_action('admin_enqueue_scripts', $assets, 'enqueue_admin');
        $chat = new ObserverMan_Pro_Chat_Controller();
        $chat->register();
        $renderer = new ObserverMan_Pro_Chat_Renderer();
        $this->loader->add_action('wp_footer', $renderer, 'render');


    }

    public function run() {
        $this->loader->run();
    }
}
