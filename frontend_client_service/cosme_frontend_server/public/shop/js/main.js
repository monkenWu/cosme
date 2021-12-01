(function ($) {
 "use strict";
	
/*---------------------
 jQuery MeanMenu
--------------------- */
	jQuery('nav#dropdown').meanmenu();	
	
/*---------------------
 mixItUp
--------------------- */	
	
   $('.awesome-portfolio-content').mixItUp({
   animation: {
	   effects: 'rotateZ',
	   duration: 1000,
		}
	});
	
   $('.blog-column-content').mixItUp({
   animation: {
	   effects: 'scale',
	   duration: 1000,
		}
	});	
	
   $('.portfolio-column-content').mixItUp({
   animation: {
	   effects: 'fade rotateY(-180deg)',
	   duration: 1000,
		}
	});	
	
/*---------------------
 fancybox
--------------------- */	
	$('.fancybox').fancybox();
	
/*---------------------
 TOP Menu Stick
--------------------- */
    var sticky_menu = $("#sticker");
    var pos = sticky_menu.position();
    if (sticky_menu.length) {
        var windowpos = sticky_menu.offset().top;
        $(window).on('scroll', function() {
            var windowpos = $(window).scrollTop();
            if (windowpos > pos.top) {
                sticky_menu.addClass("stick");
            } else {
                sticky_menu.removeClass("stick");
            }
        });
    }		

/*---------------------
 testimonial-curosel
--------------------- */
	$('.testimonial-curosel').owlCarousel({
		loop:true,
		margin:0,
		nav:false,
		animateOut: 'slideOutDown',
		animateIn: 'zoomInLeft',		
		autoplay:false,
		smartSpeed:3000,
		navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		responsive:{
			0:{
				items:1
			},
			600:{
				items:1
			},
			1000:{
				items:1
			}
		}
	})	

/*---------------------
 device-curosel
--------------------- */
	$('.device-curosel').owlCarousel({
		loop:true,
		margin:0,
		nav:false,		
		autoplay:false,
		smartSpeed:1000,
		navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		responsive:{
			0:{
				items:1
			},
			600:{
				items:1
			},
			1000:{
				items:1
			}
		}
	})
	
/*---------------------
 macbook-list
--------------------- */
	$('.macbook-list').owlCarousel({
		loop:true,
		margin:0,
		nav:false,		
		autoplay:false,
		smartSpeed:1000,
		navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		responsive:{
			0:{
				items:1
			},
			600:{
				items:1
			},
			1000:{
				items:1
			}
		}
	})	
	
/*---------------------
 brand-curosel-3
--------------------- */
	$('.brand-curosel-3').owlCarousel({
		loop:true,
		margin:0,
		nav:false,		
		autoplay:true,
		smartSpeed:1000,
		navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		responsive:{
			0:{
				items:1
			},
			600:{
				items:3
			},
			1000:{
				items:4
			}
		}
	})
	
/*---------------------
 upcoming-product-list
--------------------- */
	$('.upcoming-product-list').owlCarousel({
		loop:true,
		margin:10,
		nav:true,		
		autoplay:false,
		smartSpeed:1000,
		navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		responsive:{
			0:{
				items:1
			},
			600:{
				items:4
			},
			1000:{
				items:6
			}
		}
	})

/*---------------------
 office-banner
--------------------- */
	$('.office-banner').owlCarousel({
		loop:true,
		margin:10,
		nav:true,
		animateIn: 'fadeIn',		
		autoplay:false,
		smartSpeed:3000,
		navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		responsive:{
			0:{
				items:1
			},
			600:{
				items:1
			},
			1000:{
				items:1
			}
		}
	})	
	
/*---------------------
 team-2-curosel
--------------------- */
	$('.team-2-curosel').owlCarousel({
		loop:true,
		margin:10,
		nav:true,
		autoplay:false,
		stagePadding: 50,
		smartSpeed:1000,
		navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		responsive:{
			0:{
				items:1
			},
			770:{
				items:3
			},
			1000:{
				items:5
			}
		}
	})

/*---------------------
 testimonial-list
--------------------- */
	$('.testimonial-list').owlCarousel({
		loop:true,
		margin:10,
		nav:true,
		animateOut: 'slideOutDown',
		animateIn: 'flipInX',		
		autoplay:true,
		smartSpeed:1000,
		navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		responsive:{
			0:{
				items:1
			},
			600:{
				items:1
			},
			1000:{
				items:1
			}
		}
	})	

/*---------------------
 about-counter
--------------------- */	
    $('.counter').counterUp({
        delay: 50,
        time: 3000
    });
	
/*---------------------
 team-counter
--------------------- */	
    $('.team-counter').counterUp({
        delay: 50,
        time: 3000
    });

/*---------------------
 team-3-couter
--------------------- */	
    $('.team-3-couter').counterUp({
        delay: 50,
        time: 3000
    });
		

/*---------------------
   scrollUp
--------------------- */	
	$.scrollUp({
        scrollText: '<i class="fa fa-angle-double-up"></i>',
        easingType: 'linear',
        scrollSpeed: 900,
        animation: 'fade'
    });

/* --------------------------------------------------------
   qa-accordion
* -------------------------------------------------------*/ 
	$(".qa-accordion").collapse({
		accordion:true,
	  open: function() {
		this.slideDown(550);
	  },
	  close: function() {
		this.slideUp(550);
	  }		
	});		

/* --------------------------------------------------------
   contact-accordion
* -------------------------------------------------------*/ 
	$(".contact-accordion").collapse({
		accordion:true,
	  open: function() {
		this.slideDown(550);
	  },
	  close: function() {
		this.slideUp(550);
	  }		
	});	

/* --------------------------------------------------------
   faq-accordion
* -------------------------------------------------------*/ 
	$(".faq-accordion").collapse({
		accordion:true,
	  open: function() {
		this.slideDown(550);
	  },
	  close: function() {
		this.slideUp(550);
	  }		
	});		
	
/*---------------------
   Circular Bars - Knob
--------------------- */	
	  if(typeof($.fn.knob) != 'undefined') {
		$('.knob').each(function () {
		  var $this = $(this),
			  knobVal = $this.attr('data-rel');
	
		  $this.knob({
			'draw' : function () { 
			  $(this.i).val(this.cv + '%')
			}
		  });
		  
		  $this.appear(function() {
			$({
			  value: 0
			}).animate({
			  value: knobVal
			}, {
			  duration : 2000,
			  easing   : 'swing',
			  step     : function () {
				$this.val(Math.ceil(this.value)).trigger('change');
			  }
			});
		  }, {accX: 0, accY: -150});
		});
	  }	

/*---------------------
 countdown
--------------------- */
	$('[data-countdown]').each(function() {
	  var $this = $(this), finalDate = $(this).data('countdown');
	  $this.countdown(finalDate, function(event) {
		$this.html(event.strftime('<span class="cdown days"><span class="time-count">%-D</span> <p>Days</p></span> <span class="cdown hour"><span class="time-count">%-H</span> <p>Hour</p></span> <span class="cdown minutes"><span class="time-count">%M</span> <p>Min</p></span> <span class="cdown second"> <span><span class="time-count">%S</span> <p>Sec</p></span>'));
	  });
	});	



})(jQuery);