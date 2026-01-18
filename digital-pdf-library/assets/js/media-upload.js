jQuery(document).ready(function ($) {
    let file_frame;

    $('.dpl-upload').on('click', function (e) {
        e.preventDefault();

        if (file_frame) {
            file_frame.open();
            return;
        }

        file_frame = wp.media({
            title: 'Select PDF',
            button: { text: 'Use this PDF' },
            library: { type: 'application/pdf' },
            multiple: false
        });

        file_frame.on('select', function () {
            const attachment = file_frame.state().get('selection').first().toJSON();
            $('input[name="dpl_pdf"]').val(attachment.url);
        });

        file_frame.open();
    });
});
