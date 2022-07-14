$(document).ready(function () {
    $('.form-control').addClass('empty_field');
    $('.form-control').on('keyup', function () {
        $('.form-control').each(function () {
            if ($(this).val() != '') {
                $(this).removeClass('empty_field');
            } else {
                $(this).addClass('empty_field');
            }
        });
        var sizeEmpty = $('.empty_field').length;
        if (sizeEmpty > 0) {
            $('#accept-button').attr('disabled', 'disabled');
        }
        else {
            $('#accept-button').removeAttr('disabled');
        }
    });
});