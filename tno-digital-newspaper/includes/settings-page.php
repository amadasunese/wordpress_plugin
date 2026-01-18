<?php
if (!defined('ABSPATH')) exit;

/**
 * Register settings
 */
add_action('admin_init', function () {

    register_setting('tno_settings_group', 'tno_settings');

    add_settings_section(
        'tno_main_section',
        __('Archive Display Settings', 'tno-digital-newspaper'),
        '__return_false',
        'tno_settings'
    );

    add_settings_field(
        'archive_title',
        __('Archive Title', 'tno-digital-newspaper'),
        'tno_archive_title_field',
        'tno_settings',
        'tno_main_section'
    );

    add_settings_field(
        'show_today',
        __('Show Today’s Edition', 'tno-digital-newspaper'),
        'tno_show_today_field',
        'tno_settings',
        'tno_main_section'
    );
});

/**
 * Add menu
 */
add_action('admin_menu', function () {
    add_submenu_page(
        'edit.php?post_type=digital_edition',
        __('Digital Newspaper Settings', 'tno-digital-newspaper'),
        __('Settings', 'tno-digital-newspaper'),
        'manage_options',
        'tno-settings',
        'tno_settings_page'
    );
});

/**
 * Fields
 */
function tno_archive_title_field() {
    $opts = get_option('tno_settings');
    ?>
    <input type="text"
           name="tno_settings[archive_title]"
           value="<?php echo esc_attr($opts['archive_title'] ?? 'The Nigerian Observer ePaper'); ?>"
           class="regular-text">
    <?php
}

function tno_show_today_field() {
    $opts = get_option('tno_settings');
    ?>
    <label>
        <input type="checkbox"
               name="tno_settings[show_today]"
               value="1"
               <?php checked($opts['show_today'] ?? 1, 1); ?>>
        <?php _e('Display Today’s Edition at the top of the archive', 'tno-digital-newspaper'); ?>
    </label>
    <?php
}

/**
 * Page output
 */
function tno_settings_page() {
?>
<div class="wrap">
    <h1><?php _e('Digital Newspaper Settings', 'tno-digital-newspaper'); ?></h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('tno_settings_group');
        do_settings_sections('tno_settings');
        submit_button();
        ?>
    </form>
</div>
<?php
}
