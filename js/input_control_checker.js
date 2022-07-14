$('.custom-control-input').click(function () {
  var checker2 = false;
  $("input:radio").each(function () {
    var val = $('input:radio[name=' + this.name + ']:checked').val();
    if (val === undefined) {
      checker2 = true;
      return false;
    }
  });
  if ($(':checkbox').length > 0) {
    if (!checker2 && $(':checkbox:checked').length > 0) {
      $('#subbutton').removeAttr('disabled');
    }
    else {
      $('#subbutton').attr('disabled', 'disabled');
    }
  }
  else {
    if (!checker2) {
      $('#subbutton').removeAttr('disabled');
    }
    else {
      $('#subbutton').attr('disabled', 'disabled');
    }
  }
});