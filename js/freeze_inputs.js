$(document).ready(function () {
    $('.custom-control-input').prop('disabled', true);

    $('.custom-control-input.radio').each(function () {
        let name = $(this).attr('name');
        var radios = document.getElementsByName(name); 
        var val = localStorage.getItem(name); 
        for (var i = 0; i < radios.length; i++) {
            if (radios[i].value == val) {
                radios[i].checked = true; 
            }
        }
    });

    $('.custom-control-input.checkbox').each(function () {
        let id = $(this).attr('id');
        if(localStorage.getItem(id))
        {
            $(this).prop('checked', true);
        }
    });

    localStorage.clear();
});