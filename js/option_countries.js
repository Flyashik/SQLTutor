$(function () {
    var dataList = new Map();
    $.getJSON('../data/countries_cities.json', function (data) {
        $.each(data, function(key, value) {
            $('datalist#countries').append(`<option value="${key}"></option>`);
            dataList.set(key, value);
        });
    });
    var set = new Set();
    $('input#country').blur(function() {
        $('input#city').val('');
        var check = $('input#country').val();
        if(dataList.has(check))
        {
            $('datalist#cities').empty();
            $.each(dataList.get(check), function(key, value) {
                $('datalist#cities').append(`<option value="${value}"></option>`);
                set.add(value);
            });
        }
        else{
            $('input#country').val('');
        }
    });
    
});

