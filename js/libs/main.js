
$(window).scroll(function() {    
    var scroll = $(window).scrollTop();

    if (scroll >= 200) {
        $(".round_btn").addClass("animated shake flash");
    } 
});
$(window).scroll(function() {    
    var scroll = $(window).scrollTop();

    if (scroll < 100) {
        $(".round_btn").removeClass("animated shake");
    } 
});
$(window).scroll(function() {    
    var scroll = $(window).scrollTop();

    if (scroll >= 200) {
        $(".content_holder").addClass("bounceInRight animated");
    } 
});
  /*----------------------------------------------------*/
    /*  Scroll To Top Section
    /*----------------------------------------------------*/
      jQuery(document).ready(function () {
      
        jQuery(window).scroll(function () {
          if (jQuery(this).scrollTop() > 100) {
            jQuery('.enigma_scrollup').fadeIn();
          } else {
            jQuery('.enigma_scrollup').fadeOut();
          }
        });
      
        jQuery('.enigma_scrollup').click(function () {
          jQuery("html, body").animate({
            scrollTop: 0
          }, 600);
          return false;
        });
      
      }); 

      
      jQuery.browser = {};
          (function () {
            jQuery.browser.msie = false;
            jQuery.browser.version = 0;
            if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
              jQuery.browser.msie = true;
              jQuery.browser.version = RegExp.$1;
            }
          })();

/*============================================
	Scrolling Animations
	==============================================*/
	// jQuery('.scrollimation').waypoint(function(){
	// 	jQuery('.scrollimation').addClass('in');
	// },{offset:'100%'});

	$(window).scroll(function() {    
    var scroll = $(window).scrollTop();

    if (scroll >= 600) {
        $(".scrollimation").addClass("in");
    } 
});