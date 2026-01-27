<?php
/**
 * Plugin Name: Simple Article Byline
 * Description: Adds a lightweight custom article author byline below the post title using a post editor input field.
 * Version: 1.0.0
 * Requires at least: 5.5
 * Tested up to: 6.9
 * Requires PHP: 7.2
 * Author: Ese Amadasun
 * Author URI: https://amadasunese.pythonanywhere.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: simple-article-byline
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add meta box to post editor
 */
function sab_add_byline_metabox() {
    add_meta_box(
        'sab_byline_metabox',
        __( 'Article Author Name', 'simple-article-byline' ),
        'sab_render_byline_metabox',
        'post',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'sab_add_byline_metabox' );

/**
 * Render meta box HTML
 */
function sab_render_byline_metabox( $post ) {
    wp_nonce_field( 'sab_save_byline', 'sab_byline_nonce' );

    $byline = get_post_meta( $post->ID, '_sab_article_byline', true );
    ?>
    <input
        type="text"
        name="sab_article_byline"
        value="<?php echo esc_attr( $byline ); ?>"
        style="width:100%;"
        placeholder="<?php esc_attr_e( 'e.g. Mike Osarogbon', 'simple-article-byline' ); ?>"
    />
    <p style="margin-top:6px;font-size:12px;color:#555;">
        <?php esc_html_e( 'This name will appear below the post title.', 'simple-article-byline' ); ?>
    </p>
    <?php
}

/**
 * Save byline meta
 */
/**
 * Save byline meta
 */
function sab_save_byline_meta( $post_id ) {

    // Check if nonce exists and is unslashed/sanitized
    if ( ! isset( $_POST['sab_byline_nonce'] ) ) {
        return;
    }

    // Verify nonce with unslashed and sanitized value
    $nonce = sanitize_text_field( wp_unslash( $_POST['sab_byline_nonce'] ) );
    if ( ! wp_verify_nonce( $nonce, 'sab_save_byline' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check and save the byline field
    if ( isset( $_POST['sab_article_byline'] ) ) {
        // Unslash first, then sanitize
        $sanitized_byline = sanitize_text_field( wp_unslash( $_POST['sab_article_byline'] ) );
        
        update_post_meta(
            $post_id,
            '_sab_article_byline',
            $sanitized_byline
        );
    }
}
add_action( 'save_post', 'sab_save_byline_meta' );


/**
 * Append byline AFTER post title (not inside content)
 */
function sab_add_byline_after_title( $title, $post_id ) {

    // Admin/editor safety
    if ( is_admin() ) {
        return $title;
    }

    // Only single posts
    if ( ! is_singular( 'post' ) ) {
        return $title;
    }

    // Ensure main queried post title only
    if ( get_queried_object_id() !== $post_id ) {
        return $title;
    }

    $byline = get_post_meta( $post_id, '_sab_article_byline', true );

    if ( empty( $byline ) ) {
        return $title;
    }

    $byline_html = '<p class="sab-article-byline">By ' . esc_html( $byline ) . '</p>';

    return $title . $byline_html;
}
add_filter( 'the_title', 'sab_add_byline_after_title', 10, 2 );

/**
 * Frontend styles
 */
function sab_byline_styles() {
    ?>
    <style>
        .sab-article-byline {
            margin: 6px 0 14px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            color: #777;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'sab_byline_styles' );
