jQuery(document).ready(function ($) {
    $('.tno-year').on('click', function () {
        $(this).next('.tno-months').slideToggle();
    });
});
