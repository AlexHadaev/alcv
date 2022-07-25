(function($) {
  /************************************
  *** show mobile menu
  ************************************/
  $('#menu_button, #menu_button_close').click(function (e){
    e.preventDefault();
    $('nav.menu-mobile').toggleClass('active');
  });

  $('body').click(function (e){
    var eventTarget = $(e.target);
    var checkMainNav = eventTarget.closest('.main-nav').length === 0;
    var checkMenuButton = eventTarget.closest('.menu-button').length === 0;
    var checkClassActive = $('nav.menu-mobile').hasClass('active');

    if (checkMainNav && checkMenuButton && checkClassActive){
      $('#menu_button').trigger('click');
    }
  });


})(jQuery);
