$('.questions-form').submit(function (){
    $('.custom-control-input.radio').each(function (){
        if($(this).is(':checked'))
        {
            localStorage.setItem($(this).attr('name'), $(this).attr('value'));
        }
    });
    $('.custom-control-input.checkbox').each(function () {
        if($(this).is(':checked'))
        {
            localStorage.setItem($(this).attr('id'), $(this).attr('value'));
        }
    });
});