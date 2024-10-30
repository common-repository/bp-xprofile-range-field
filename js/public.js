(function ($) {
    'use strict';

    $(function () {
        // Slider.
        if ($('input.abp-slider') !== undefined) {
            $('input.abp-slider').on('input', function () {
                $('#output-' + $(this).attr('id')).html($(this).val());
            });
        }
    });
})(jQuery);