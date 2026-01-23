<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the Settings Menu
 */
add_action('admin_menu', 'eairap_register_settings_menu');
function eairap_register_settings_menu() {

    add_options_page(
        'EA Inline Related Articles',
        'EA Inline Related Articles',
        'manage_options',
        'eairap-inline-related-articles',
        'eairap_render_settings_page'
    );
}

/**
 * Register Settings and Sanitization
 */
add_action('admin_init', 'eairap_register_settings');
function eairap_register_settings() {

    register_setting(
        'eairap_settings_group',
        'eairap_settings',
        [
            'sanitize_callback' => 'eairap_sanitize_settings'
        ]
    );
}

/**
 * Sanitize Plugin Settings
 */
function eairap_sanitize_settings($input) {

    $new_input = [];

    // Paragraph positions (comma-separated integers)
    if (isset($input['paragraphs'])) {
        $new_input['paragraphs'] = preg_replace('/[^0-9,]/', '', $input['paragraphs']);
    }

    // Section heading
    if (isset($input['heading'])) {
        $new_input['heading'] = sanitize_text_field($input['heading']);
    }

    // Number of articles per block
    if (isset($input['count'])) {
        $count = intval($input['count']);
        $new_input['count'] = ($count >= 1 && $count <= 6) ? $count : 3;
    }

    // AI relevance toggle
    $new_input['ai'] = isset($input['ai']) ? 1 : 0;

    return $new_input;
}

/**
 * Render Settings Page
 */
function eairap_render_settings_page() {

    $defaults = [
        'paragraphs' => '3,7',
        'count'      => 3,
        'ai'         => 0,
        'heading'    => 'Read Next'
    ];

    $settings = wp_parse_args(
        get_option('eairap_settings', []),
        $defaults
    );
    ?>
    <div class="wrap">
        <!-- <h1>EA Inline Related Articles Settings</h1> -->

        <h1><?php esc_html_e(
            'EA Inline Related Articles Settings',
            'ea-inline-related-articles-pro'
        ); ?></h1>

        <form method="post" action="options.php">
            <?php
                settings_fields('eairap_settings_group');
                do_settings_sections('eairap_settings_group');
            ?>

            <table class="form-table">

                <tr>
                    <th scope="row">
                        <label for="eairap_heading">Section Heading</label>
                    </th>
                    <td>
                        <input type="text"
                               id="eairap_heading"
                               name="eairap_settings[heading]"
                               value="<?php echo esc_attr($settings['heading']); ?>"
                               class="regular-text"
                               placeholder="e.g. Recommended for You">

                        <p class="description">
                            The title displayed above the related articles.
                        </p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="eairap_paragraphs">Paragraph Positions</label>
                    </th>
                    <td>
                        <input type="text"
                               id="eairap_paragraphs"
                               name="eairap_settings[paragraphs]"
                               value="<?php echo esc_attr($settings['paragraphs']); ?>"
                               class="regular-text">

                        <p class="description">
                            Where to inject blocks. Example: <code>3,7,10</code>
                        </p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="eairap_count">Articles per Block</label>
                    </th>
                    <td>
                        <input type="number"
                               id="eairap_count"
                               name="eairap_settings[count]"
                               value="<?php echo esc_attr($settings['count']); ?>"
                               min="1"
                               max="6">
                    </td>
                </tr>

                <tr>
                    <th scope="row">AI Relevance</th>
                    <td>
                        <label for="eairap_ai">
                            <input type="checkbox"
                                   id="eairap_ai"
                                   name="eairap_settings[ai]"
                                   value="1" <?php checked(1, $settings['ai']); ?>>
                            Enable AI-powered relevance scoring
                        </label>
                    </td>
                </tr>

            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
