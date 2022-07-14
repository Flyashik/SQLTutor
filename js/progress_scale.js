$(document).ready(function () {
    $('.progress-bar').each(function () {
        $(this).css('width', function () {
            let res = 100 * $(this).attr('aria-valuenow') / $(this).attr('aria-valuemax');
            return res + '%';
        })
    });
});