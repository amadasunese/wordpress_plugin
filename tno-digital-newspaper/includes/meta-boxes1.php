<?php
if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'tno_pdf_file',
        __('Newspaper PDF', 'tno-digital-newspaper'),
        'tno_pdf_meta_box_callback',
        'digital_edition',
        'normal',
        'high'
    );
});

/*
function tno_pdf_meta_box_callback($post) {
    wp_nonce_field('tno_save_pdf', 'tno_pdf_nonce');
    $pdf = get_post_meta($post->ID, '_tno_pdf', true);
    ?>
    <p>
        <input type="url"
               name="tno_pdf"
               id="tno_pdf"
               value="<?php echo esc_attr($pdf); ?>"
               style="width:100%;"
               placeholder="https://example.com/newspaper.pdf">
    </p>
    <p>
        <button type="button" class="button tno-upload-pdf">
            <?php _e('Upload / Select PDF', 'tno-digital-newspaper'); ?>
        </button>
    </p>
    <?php
    wp_enqueue_media();
}
*/

function tno_pdf_meta_box_callback($post) {
    $pdf = get_post_meta($post->ID, '_tno_pdf', true);
    ?>
    <input type="text" name="tno_pdf" value="<?php echo esc_attr($pdf); ?>" style="width:100%;" />
    <button class="button upload_pdf_button">Upload PDF</button>

    <script>
    jQuery(document).ready(function($){
        $('.upload_pdf_button').click(function(e){
            e.preventDefault();
            var pdfUploader = wp.media({
                title: 'Upload PDF',
                button: { text: 'Use this PDF' },
                multiple: false
            }).on('select', function(){
                var attachment = pdfUploader.state().get('selection').first().toJSON();
                $('input[name="tno_pdf"]').val(attachment.url);
            }).open();
        });
    });
    </script>
    <?php
}