(function ($) {

    $(document).ready(function () {
		
		$("ul.tabs-menu a").click(function(event) {
			
			var metabox_id = $(this).attr('data-metabox-id');

			event.preventDefault();
			
			$(this).parent().addClass("current");
			
			$(this).parent().siblings().removeClass("current");
			
			var tab = $(this).attr("href");

			$("div#"+metabox_id+" .tab-content").not(tab).css("display", "none");
			
			$(tab).fadeIn();
			
			$('html, body').animate( { scrollTop: $(tab).offset().top-50 }, 500 ); 
			
		});
		
		$("ul.tabs-menu li:first-child").addClass('current');
	
    });
	
})(jQuery);

jQuery.noConflict();