$(function () {
  $('.nav-link').on('click', function (e) {
    $('html,body').stop().animate({ scrollTop: $(this.hash).offset().top }, 1000);
    e.preventDefault();
  });
});