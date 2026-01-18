<?php
add_action('save_post', function ($post_id) {
    if (!isset($_POST['dpl_pdf_nonce']) ||
        !wp_verify_nonce($_POST['dpl_pdf_nonce'], 'dpl_save_pdf')) return;

    if (isset($_POST['dpl_pdf'])) {
        update_post_meta($post_id, '_dpl_pdf', esc_url_raw($_POST['dpl_pdf']));
    }
});
