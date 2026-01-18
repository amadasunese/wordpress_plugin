<?php
/**
 * Plugin Name: Trending News Widget
 * Plugin URI: https://wordpress.org/plugins/trending-news-widget/
 * Description: Displays trending news headlines based on recent post views within a configurable time window.
 * Version: 1.0.0
 * Author: Ese Amadasun
 * Author URI: https://amadasunese.pythonanywhere.com
 * Text Domain: trending-news-widget
 * Domain Path: /languages
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ---------------------------------------------------
 * LOAD TEXT DOMAIN
 * --------------------------------------------------- */
function tnw_load_textdomain() {
    load_plugin_textdomain(
        'trending-news-widget',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );
}
add_action( 'plugins_loaded', 'tnw_load_textdomain' );

/* ---------------------------------------------------
 * TRACK POST VIEWS
 * --------------------------------------------------- */
function tnw_track_post_views() {
    if ( ! is_single() ) {
        return;
    }

    global $post;

    if ( empty( $post->ID ) ) {
        return;
    }

    $views = (int) get_post_meta( $post->ID, '_tnw_views', true );
    update_post_meta( $post->ID, '_tnw_views', $views + 1 );
    update_post_meta( $post->ID, '_tnw_last_viewed', current_time( 'timestamp' ) );
}
add_action( 'wp_head', 'tnw_track_post_views' );

/* ---------------------------------------------------
 * ENQUEUE STYLES (ONLY IF WIDGET IS ACTIVE)
 * --------------------------------------------------- */
function tnw_enqueue_styles() {
    if ( is_active_widget( false, false, 'tnw_trending_widget', true ) ) {
        wp_enqueue_style(
            'tnw-trending-widget',
            plugin_dir_url( __FILE__ ) . 'assets/trending-news-widget.css',
            array(),
            '1.0.0'
        );
    }
}
add_action( 'wp_enqueue_scripts', 'tnw_enqueue_styles' );

/* ---------------------------------------------------
 * TRENDING NEWS WIDGET
 * --------------------------------------------------- */
class TNW_Trending_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'tnw_trending_widget',
            __( 'Trending News', 'trending-news-widget' ),
            array(
                'description' => __( 'Displays trending news headlines.', 'trending-news-widget' ),
            )
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Trending News', 'trending-news-widget' );
        echo $args['before_title'] . esc_html( apply_filters( 'widget_title', $title ) ) . $args['after_title'];

        $limit    = ! empty( $instance['limit'] ) ? (int) $instance['limit'] : 5;
        $hours    = ! empty( $instance['hours'] ) ? (int) $instance['hours'] : 24;
        $category = ! empty( $instance['category'] ) ? (int) $instance['category'] : 0;

        $time_limit = current_time( 'timestamp' ) - ( $hours * HOUR_IN_SECONDS );

        $query_args = array(
            'post_type'           => 'post',
            'posts_per_page'      => $limit,
            'meta_key'            => '_tnw_views',
            'orderby'             => 'meta_value_num',
            'order'               => 'DESC',
            'ignore_sticky_posts' => true,
            'meta_query'          => array(
                array(
                    'key'     => '_tnw_last_viewed',
                    'value'   => $time_limit,
                    'compare' => '>=',
                    'type'    => 'NUMERIC',
                ),
            ),
        );

        if ( $category ) {
            $query_args['cat'] = $category;
        }

        $query = new WP_Query( $query_args );

        if ( $query->have_posts() ) {
            echo '<ul class="tnw-trending">';
            while ( $query->have_posts() ) {
                $query->the_post();
                echo '<li><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p>' . esc_html__( 'No trending news yet.', 'trending-news-widget' ) . '</p>';
        }

        wp_reset_postdata();
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title    = $instance['title'] ?? __( 'Trending News', 'trending-news-widget' );
        $limit    = $instance['limit'] ?? 5;
        $hours    = $instance['hours'] ?? 24;
        $category = $instance['category'] ?? 0;
        ?>
        <p>
            <label><?php esc_html_e( 'Title:', 'trending-news-widget' ); ?></label>
            <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>">
        </p>

        <p>
            <label><?php esc_html_e( 'Number of Posts:', 'trending-news-widget' ); ?></label>
            <input class="widefat" type="number" min="1" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" value="<?php echo esc_attr( $limit ); ?>">
        </p>

        <p>
            <label><?php esc_html_e( 'Time Window (hours):', 'trending-news-widget' ); ?></label>
            <input class="widefat" type="number" min="1" name="<?php echo esc_attr( $this->get_field_name( 'hours' ) ); ?>" value="<?php echo esc_attr( $hours ); ?>">
        </p>

        <p>
            <label><?php esc_html_e( 'Category ID (optional):', 'trending-news-widget' ); ?></label>
            <input class="widefat" type="number" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>" value="<?php echo esc_attr( $category ); ?>">
        </p>
        <?php
    }

    public function update( $new, $old ) {
        return array(
            'title'    => sanitize_text_field( $new['title'] ),
            'limit'    => (int) $new['limit'],
            'hours'    => (int) $new['hours'],
            'category' => (int) $new['category'],
        );
    }
}

/* ---------------------------------------------------
 * REGISTER WIDGET
 * --------------------------------------------------- */
function tnw_register_widget() {
    register_widget( 'TNW_Trending_Widget' );
}
add_action( 'widgets_init', 'tnw_register_widget' );
