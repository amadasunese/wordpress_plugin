<?php
if (!defined('ABSPATH')) exit;

add_action('save_post', function ($post_id) {

    if (!isset($_POST['tno_pdf_nonce']) ||
        !wp_verify_nonce($_POST['tno_pdf_nonce'], 'tno_save_pdf')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['tno_pdf'])) {
        update_post_meta(
            $post_id,
            '_tno_pdf',
            esc_url_raw($_POST['tno_pdf'])
        );
    }
});
