<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

/**
 * Delete all byline metadata across all posts.
 * This function handles database deletion and cache clearing automatically.
 */
delete_post_meta_by_key( '_sab_article_byline' );