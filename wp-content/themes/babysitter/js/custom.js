/**
	* @package Babysitter
	* @subpackage Babysitter WordPress Theme
	* @since Babysitter WordPress Theme 1.0
	* 
	* Template Scripts
	* Created by dan_fisher

	Custom JS
	
	1. Main Navigation
	2. Flexslider
	3. Tabs (Tabbed Content)
	4. Accordion
	5. Flickr
	-- Misc
*/

jQuery(function($){

	$('body').hide().show();

	/* ----------------------------------------------------------- */
	/*  1. Main Navigation
	/* ----------------------------------------------------------- */

	$('ul.sf-menu').superfish({
		autoArrows	: true,
		dropShadows : false,
		delay			: 800,
		autoArrows:  false,
		animation	: {opacity:'show', height:'show'},
		speed			: 'fast',
		disableHI   : true
	});

	/* Mobile Menu */
	$('nav.primary .sf-menu').mobileMenu({
		defaultText: 'Navigate to...'
	});


	/* ----------------------------------------------------------- */
	/*  2. Flexslider
	/* ----------------------------------------------------------- */

	$('.flexslider__nav-off').flexslider({
		animation: "fade",
		directionNav: false,
		slideshowSpeed: 6000,
		start: function(slider){
			jQuery('.flexslider__nav-off').removeClass('loading');
		}
	});

	$('.flexslider__nav-on').flexslider({
		animation: "fade",
		directionNav: true,
		prevText: "<i class='icon-chevron-left'></i>",
    	nextText: "<i class='icon-chevron-right'></i>",
		slideshowSpeed: 6000,
		start: function(slider){
			jQuery('.flexslider__nav-on').removeClass('loading');
		}
	});


	// Slider on Single Portfolio page
	$('#carousel').flexslider({
		animation: "slide",
		controlNav: false,
		animationLoop: false,
		slideshow: false,
		itemWidth: 98,
		itemMargin: 5,
		asNavFor: '#slider',
		prevText: "<i class='icon-chevron-left'></i>",
    	nextText: "<i class='icon-chevron-right'></i>",
	});
	$('#slider').flexslider({
		animation: "fade",
		controlNav: false,
		animationLoop: false,
		slideshow: false,
		directionNav: false,
		sync: "#carousel"
	});


	/* ----------------------------------------------------------- */
	/*  3. Tabs (Tabbed Content)
	/* ----------------------------------------------------------- */

	$(".tabs").each(function(){

		$(this).find(".tab").hide();
		$(this).find(".tab-menu li:first a").addClass("active").show();
		$(this).find(".tab:first").show();

	});

	$(".tabs").each(function(){

		$(this).find(".tab-menu a").click(function() {

			$(this).parent().parent().find("a").removeClass("active");
			$(this).addClass("active");
			$(this).parent().parent().parent().parent().find(".tab").hide();
			var activeTab = $(this).attr("href");
			$(activeTab).fadeIn();
			return false;

		});

	});


	/* ----------------------------------------------------------- */
	/*  4. Accordion (Toggle)
	/* ----------------------------------------------------------- */

	(function() {
		var $container = $('.acc-body'),
			 $acc_head  = $('.acc-head');

		$container.hide();

		$acc_head.each(function() {
			if($(this).hasClass('active')) {
				$(this).next().show();
			}
		});
		
		$acc_head.on('click', function(e) {
			if( $(this).next().is(':hidden') ) {
				$acc_head.removeClass('active').next().slideUp(300);
				$(this).toggleClass('active').next().slideDown(300);
			}
			e.preventDefault();
		});

	})();


	/* ----------------------------------------------------------- */
	/*  5. Flickr
	/* ----------------------------------------------------------- */
	
	$('#flickr').jflickrfeed({
		limit: 9,
		qstrings: {
			id: '52617155@N08'
		},
		itemTemplate: '<li class="thumb thumb__hovered"><a class="flickr-widget_thumb_holder" href="{{link}}" target="_blank"><img src="{{image_s}}" alt="{{title}}" width="74" height="75" /></a></li>'
	}, 
	function(data) {
		$("#flickr li:nth-child(3n)").addClass("nomargin");
	});


	/* ----------------------------------------------------------- */
	/*  Misc
	/* ----------------------------------------------------------- */
	$(".list-elements .item:nth-child(5n)").addClass("fifth");

	$(".entry__video iframe, .page iframe[src*='vimeo'], .page iframe[src*='youtube']").each(function(){
		$(this).wrap("<figure class='video-holder'/>");
	});

	$(".video-holder").fitVids();

	// Contact Form 7 Fix Tip
	$(".wpcf7-form-control-wrap").each(function(){
		$(this).hover(function(){
			$(this).find(".wpcf7-not-valid-tip").fadeOut();
		});
	});

	
});