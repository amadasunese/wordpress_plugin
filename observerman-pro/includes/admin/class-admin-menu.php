<?php
if (!defined('ABSPATH')) {
    exit;
}

require_once OBSERVERMAN_PRO_PATH . 'includes/admin/class-analytics.php';

class ObserverMan_Pro_Admin_Menu {

    public function register_menu() {
        add_menu_page(
            'ObserverMan Pro',
            'ObserverMan Pro',
            'manage_options',
            'observerman-pro',
            [$this, 'render_dashboard'],
            'dashicons-format-chat',
            26
        );
    }

    public function render_dashboard() {

        $tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'settings';
        ?>
        <div class="wrap">
            <h1>ObserverMan Pro</h1>

            <nav class="nav-tab-wrapper">
                <a href="?page=observerman-pro&tab=settings"
                   class="nav-tab <?php echo $tab === 'settings' ? 'nav-tab-active' : ''; ?>">
                    Settings
                </a>

                <a href="?page=observerman-pro&tab=license"
                   class="nav-tab <?php echo $tab === 'license' ? 'nav-tab-active' : ''; ?>">
                    License
                </a>

                <a href="?page=observerman-pro&tab=analytics"
                   class="nav-tab <?php echo $tab === 'analytics' ? 'nav-tab-active' : ''; ?>">
                    Analytics
                </a>
            </nav>

            <?php if ($tab === 'license') : ?>

                <form method="post" action="options.php">
                    <?php
                    settings_fields('observerman_pro_license_group');
                    do_settings_sections('observerman-pro-license');
                    submit_button('Activate License');
                    ?>
                </form>

            <?php elseif ($tab === 'analytics') : ?>

                <?php
                $analytics = new ObserverMan_Pro_Analytics();
                $analytics->render();
                ?>

            <?php else : ?>

                <form method="post" action="options.php">
                    <?php
                    settings_fields('observerman_pro_settings_group');
                    do_settings_sections('observerman-pro');
                    submit_button();
                    ?>
                </form>

            <?php endif; ?>
        </div>
        <?php
    }
}
