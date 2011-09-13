$(document).ready(function(){

  // Drop Down Support
  (function(){
      $("body").bind("click", function (e) {
        $('.dropdown-toggle, .menu').parent("li").removeClass("open");
      });
      $(".dropdown-toggle, .menu").click(function (e) {
        var $li = $(this).parent("li").toggleClass('open');
        return false;
      });
  })();

  // Notice Close
  (function(){
    $('.close').click(function(e){
        $(this).parent().fadeOut('slow', function(){
            $(this).remove();
        });
    });
  })();

});
