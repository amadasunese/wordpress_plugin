<?php
/**
 * Plugin Name: Smart Initial Caps Titles
 * Description: Converts post titles to Initial Caps while preserving ALL CAPS and numeric acronyms like FG, INEC, 5G, 2FA, 50BN. Includes category-based rules and performance optimizations.
 * Version:     1.0.0
 * Author:      Ese Amadasun
 * Author URI:  https://amadasunese.pythonanywhere.com
 * Text Domain: smart-initial-caps-titles
 * License:     GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SICT_OPTION', 'sict_settings' );
define( 'SICT_CACHE_GROUP', 'sict_titles' );

/**
 * Default settings
 */
function sict_default_settings() {
	return array(
		'home'       => 1,
		'single'     => 1,
		'archive'    => 0,
		'min_length' => 4,
		'categories' => array(),
	);
}

/**
 * Get sanitized settings
 */
function sict_get_settings() {
	return wp_parse_args( get_option( SICT_OPTION, array() ), sict_default_settings() );
}

/**
 * Smart formatter (ALL CAPS + numeric acronyms)
 */
function sict_format_title( $title ) {
	$settings = sict_get_settings();

	// Skip very short titles.
	if ( mb_strlen( $title ) < intval( $settings['min_length'] ) ) {
		return $title;
	}

	// Cache.
	$cache_key = md5( $title );
	$cached    = wp_cache_get( $cache_key, SICT_CACHE_GROUP );
	if ( false !== $cached ) {
		return $cached;
	}

	/**
	 * Match:
	 * FG, INEC, USA
	 * 5G, 2FA, 50BN, 2027PVC
	 * U.S., A.B.C
	 */
	preg_match_all(
		'/(?<![A-Za-z0-9])(?:[A-Z]{2,}|\d+[A-Z]+|(?:[A-Z]\.){2,})(?![A-Za-z0-9])/',
		$title,
		$matches
	);
	$acronyms = array_unique( $matches[0] );

	// Convert to Initial Caps.
	$formatted = ucwords( strtolower( $title ) );

	// Restore original acronyms (using /i flag for case-insensitive matching).
	foreach ( $acronyms as $acro ) {
		$formatted = preg_replace(
			'/(?<![A-Za-z0-9])' . preg_quote( $acro, '/' ) . '(?![A-Za-z0-9])/i',
			$acro,
			$formatted
		);
	}

	wp_cache_set( $cache_key, $formatted, SICT_CACHE_GROUP );

	return $formatted;
}

/**
 * Conditional filter
 */
function sict_filter_the_title( $title, $post_id = null ) {
	// Basic safety checks.
	if ( is_admin() || empty( $title ) ) {
		return $title;
	}

	// Skip Nav Menu items.
	$post = get_post( $post_id );
	if ( $post && 'nav_menu_item' === $post->post_type ) {
		return $title;
	}

	$settings = sict_get_settings();

	// Category filtering.
	if ( ! empty( $settings['categories'] ) && $post_id ) {
		$post_categories = wp_get_post_categories( $post_id );
		if ( ! array_intersect( $settings['categories'], $post_categories ) ) {
			return $title;
		}
	}

	// Define context variables.
	$is_home    = ( is_home() || is_front_page() );
	$is_single  = is_singular();
	$is_archive = is_archive();

	// Apply logic.
	if (
		( $settings['home'] && $is_home ) ||
		( $settings['single'] && $is_single ) ||
		( $settings['archive'] && $is_archive ) ||
		( ! $is_single && ! $is_archive && ! $is_home )
	) {
		return sict_format_title( $title );
	}

	return $title;
}
add_filter( 'the_title', 'sict_filter_the_title', 10, 2 );

/**
 * Sanitization callback for settings
 */
function sict_sanitize_settings( $input ) {
	$output               = array();
	$output['home']       = isset( $input['home'] ) ? 1 : 0;
	$output['single']     = isset( $input['single'] ) ? 1 : 0;
	$output['archive']    = isset( $input['archive'] ) ? 1 : 0;
	$output['min_length'] = isset( $input['min_length'] ) ? absint( $input['min_length'] ) : 4;
	$output['categories'] = ( isset( $input['categories'] ) && is_array( $input['categories'] ) ) ? array_map( 'absint', $input['categories'] ) : array();

	return $output;
}

/**
 * Admin Menu
 */
add_action( 'admin_menu', function () {
	add_options_page(
		__( 'Smart Initial Caps Titles', 'smart-initial-caps-titles' ),
		__( 'Initial Caps Titles', 'smart-initial-caps-titles' ),
		'manage_options',
		'sict-settings',
		'sict_render_settings_page'
	);
} );

add_action( 'admin_init', function () {
	register_setting( 'sict_settings_group', SICT_OPTION, 'sict_sanitize_settings' );
} );

/**
 * Settings UI
 */
function sict_render_settings_page() {
	$settings   = sict_get_settings();
	$categories = get_categories( array( 'hide_empty' => false ) );
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<form method="post" action="options.php">
			<?php settings_fields( 'sict_settings_group' ); ?>

			<table class="form-table">
				<tr>
					<th><?php esc_html_e( 'Apply On', 'smart-initial-caps-titles' ); ?></th>
					<td>
						<label><input type="checkbox" name="<?php echo esc_attr( SICT_OPTION ); ?>[home]" value="1" <?php checked( $settings['home'] ); ?>> Homepage</label><br>
						<label><input type="checkbox" name="<?php echo esc_attr( SICT_OPTION ); ?>[single]" value="1" <?php checked( $settings['single'] ); ?>> Single Posts</label><br>
						<label><input type="checkbox" name="<?php echo esc_attr( SICT_OPTION ); ?>[archive]" value="1" <?php checked( $settings['archive'] ); ?>> Archives</label>
					</td>
				</tr>

				<tr>
					<th><?php esc_html_e( 'Minimum Title Length', 'smart-initial-caps-titles' ); ?></th>
					<td>
						<input type="number" name="<?php echo esc_attr( SICT_OPTION ); ?>[min_length]" value="<?php echo esc_attr( $settings['min_length'] ); ?>" min="1">
						<p class="description"><?php esc_html_e( 'Titles shorter than this will be skipped.', 'smart-initial-caps-titles' ); ?></p>
					</td>
				</tr>

				<tr>
					<th><?php esc_html_e( 'Limit to Categories (optional)', 'smart-initial-caps-titles' ); ?></th>
					<td>
						<?php foreach ( $categories as $cat ) : ?>
							<label style="display:block;">
								<input type="checkbox"
									name="<?php echo esc_attr( SICT_OPTION ); ?>[categories][]"
									value="<?php echo esc_attr( $cat->term_id ); ?>"
									<?php checked( in_array( $cat->term_id, $settings['categories'], true ) ); ?>>
								<?php echo esc_html( $cat->name ); ?>
							</label>
						<?php endforeach; ?>
						<p class="description"><?php esc_html_e( 'If none selected, applies to all categories.', 'smart-initial-caps-titles' ); ?></p>
					</td>
				</tr>
			</table>

			<?php submit_button(); ?>
		</form>

		<p style="color:#555;margin-top:20px;">
			<?php
			printf(
				/* translators: 1-5: Example acronyms */
				esc_html__( 'Preserves ALL CAPS & numeric acronyms like %1$s, %2$s, %3$s, %4$s, %5$s.', 'smart-initial-caps-titles' ),
				'<strong>FG</strong>',
				'<strong>INEC</strong>',
				'<strong>5G</strong>',
				'<strong>2FA</strong>',
				'<strong>50BN</strong>'
			);
			?>
		</p>
	</div>
	<?php
}