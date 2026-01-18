<?php
add_action('add_meta_boxes', function () {
    add_meta_box(
        'dpl_pdf',
        __('PDF Document', 'digital-pdf-library'),
        'dpl_pdf_box',
        'digital_pdf'
    );
});

function dpl_pdf_box($post) {
    wp_nonce_field('dpl_save_pdf', 'dpl_pdf_nonce');
    $pdf = get_post_meta($post->ID, '_dpl_pdf', true);
    ?>
    <input type="url" name="dpl_pdf" value="<?php echo esc_attr($pdf); ?>" style="width:100%">
    <button class="button dpl-upload"><?php _e('Upload PDF', 'digital-pdf-library'); ?></button>
    <?php
    wp_enqueue_media();
    wp_enqueue_script(
        'dpl-media',
        DPL_URL . 'assets/js/media-upload.js',
        ['jquery'],
        '1.0',
        true
    );
}
