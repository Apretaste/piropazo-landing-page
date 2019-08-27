(function($) {

  'use strict';


  /**
   * =====================================
   * Function for windows height and width
   * =====================================
   */
  function windowSize( el ) {
	var result = 0;
	if("height" == el)
		result = window.innerHeight ? window.innerHeight : $(window).height();
	if("width" == el)
	  result = window.innerWidth ? window.innerWidth : $(window).width();

	return result;
  }


  /**
   * =====================================
   * Function for email address validation
   * =====================================
   */
  function isValidEmail(emailAddress) {
	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	return pattern.test(emailAddress);
  };


  /**
   * =====================================
   * Function for windows height and width
   * =====================================
   */
  function deviceControll() {
	if( windowSize( 'width' ) < 768 ) {
	  $('body').removeClass('desktop').removeClass('tablet').addClass('mobile');
	}
	else if( windowSize( 'width' ) < 992 ){
	  $('body').removeClass('mobile').removeClass('desktop').addClass('tablet');
	}
	else {
	  $('body').removeClass('mobile').removeClass('tablet').addClass('desktop');
	}
  }


  $(window).on('resize', function() {

	deviceControll();

  });



  $(document).on('ready', function() {

	deviceControll();



	/**
	 * =======================================
	 * Top Navigaion Init
	 * =======================================
	 */
	var navigation = $('#js-navbar-menu').okayNav({
	  toggle_icon_class: "okayNav__menu-toggle",
	  toggle_icon_content: "<span /><span /><span /><span /><span />"
	});



	/**
	 * =======================================
	 * Top Fixed Navbar
	 * =======================================
	 */
	$(document).on('scroll', function() {
	  var activeClass = 'navbar-home',
		  ActiveID		= '.main-navbar-top',
		  scrollPos	   = $(this).scrollTop();

	  if( scrollPos > 10 ) {
		$( ActiveID ).addClass( activeClass );
	  } else {
		$( ActiveID ).removeClass( activeClass );
	  }
	});




	/**
	 * ====================================
	 * LOCAL NEWSLETTER SUBSCRIPTION
	 * ====================================
	 */
	$("#local-subscribe").on('submit', function(e) {
		e.preventDefault();
		var data = {
			email: $("#subscriber-email").val()
		};

		if ( isValidEmail(data['email']) ) {
			$.ajax({
				type: "POST",
				url: "../../php/subscribe.php",
				data: data,
				success: function() {
					$('.subscription-success').fadeIn(1000);
					$('.subscription-failed').fadeOut(500);
				}
			});
		} else {
			$('.subscription-failed').fadeIn(1000);
			$('.subscription-success').fadeOut(500);
		}

		return false;
	});




	/**
	 * =======================================
	 * TESTIMONIAL SYNC WITH CLIENTS
	 * =======================================
	 */

	// Images Screenshot Gallery
	var swiper = new Swiper('.app-swiper-slide .swiper-container', {
	  pagination: '.swiper-pagination',
	  paginationClickable: true,
	  spaceBetween: 15,
	  slidesPerView: 2,
	  autoplay: 3000,
	  loop: true,
	  breakpoints: {
		767: {
		  slidesPerView: 1
		}
	  }
	});


	// Images Screenshot Gallery
	var swiper = new Swiper('.testimonials-1 .swiper-container', {
	  slidesPerView: 1,
	  autoplay: 3000,
	  loop: true
	});


  });


} (jQuery) );
