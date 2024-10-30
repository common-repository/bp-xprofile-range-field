function abp_save_range($, e) {
    var min = $('#number_minmax_option1').val();
    var max = $('#number_minmax_option2').val();
    if (min === '' && max === '') {
        alert(error_msg_number_minmax_empty);
        e.preventDefault();
        return false;
    } else if (parseInt(min) >= parseInt(max)) {
        alert(error_msg_number_minmax);
        e.preventDefault();
        return false;
    }

    if (min !== '') {
        $('#number_minmax_option1').parent().hide();
        $('#number_minmax_option1').val('min_' + min);
    }
    if (max !== '') {
        $('#number_minmax_option2').parent().hide();
        $('#number_minmax_option2').val('max_' + max);
    }
}

function abp_save_range_slider($, e) {
    var min = $('#slider_option1').val();
    var max = $('#slider_option2').val();
    if (min === '' || max === '') {
        alert(error_msg_slider_empty);
        e.preventDefault();
        return false;
    } else if (parseInt(min) >= parseInt(max)) {
        alert(error_msg_slider);
        e.preventDefault();
        return false;
    }

    if (min !== '') {
        $('#slider_option1').parent().hide();
        $('#slider_option1').val('min_' + min);
    }
    if (max !== '') {
        $('#slider_option2').parent().hide();
        $('#slider_option2').val('max_' + max);
    }
}

jQuery(document).ready(function ($) {
    $('#bp-xprofile-add-field').on('submit', function (e) {
        if ($('select#fieldtype').val() == 'slider') {
            abp_save_range_slider($, e);
        }
    });
    // Slider.
    if ($('input.abp-slider') !== undefined) {
        $('input.abp-slider').on('input', function () {
            $('#output-' + $(this).attr('id')).html($(this).val());
        });
    }
});
