jQuery(document).ready(function() {


/*----------------------------------------------------------------------------------*/
/*	Portfolio Custom Fields Hide/Show
/*----------------------------------------------------------------------------------*/

    var portfolioTypeTrigger = jQuery('#babysitter_portfolio_type'),
        portfolioImage = jQuery('#babysitter-meta-box-portfolio-image'),
        portfolioVideo = jQuery('#babysitter-meta-box-portfolio-video'),
        currentType = portfolioTypeTrigger.val();
        
    babysitterSwitchPortfolio(currentType);

    portfolioTypeTrigger.change( function() {
       currentType = jQuery(this).val();
       
       babysitterSwitchPortfolio(currentType);
    });
    
    function babysitterSwitchPortfolio(currentType) {
       if( currentType === 'Video' ) {
            babysitterHideAllPortfolio(portfolioVideo);
        } else {
            babysitterHideAllPortfolio(portfolioImage);
        }
    }
    
    function babysitterHideAllPortfolio(notThisOne) {
		portfolioImage.css('display', 'none');
		portfolioVideo.css('display', 'none');
		notThisOne.css('display', 'block');
	}


// ---------------------------------------------------------
//  	Quote
// ---------------------------------------------------------
	var quoteOptions = jQuery('#babysitter-meta-box-quote');
	var quoteTrigger = jQuery('#post-format-quote');
	
	quoteOptions.css('display', 'none');

// ---------------------------------------------------------
//  	Link
// ---------------------------------------------------------
	var linkOptions = jQuery('#babysitter-meta-box-link');
	var linkTrigger = jQuery('#post-format-link');
	
	linkOptions.css('display', 'none');
	
// ---------------------------------------------------------
//  	Video
// ---------------------------------------------------------
	var videoOptions = jQuery('#babysitter-meta-box-video');
	var videoTrigger = jQuery('#post-format-video');
	
	videoOptions.css('display', 'none');


// ---------------------------------------------------------
//  	Core
// ---------------------------------------------------------
	var group = jQuery('#post-formats-select input');

	
	group.change( function() {
		
		if(jQuery(this).val() == 'quote') {
			quoteOptions.css('display', 'block');
			babysitterHideAll(quoteOptions);
			
		} else if(jQuery(this).val() == 'link') {
			linkOptions.css('display', 'block');
			babysitterHideAll(linkOptions);
			
		} else if(jQuery(this).val() == 'video') {
			videoOptions.css('display', 'block');
			babysitterHideAll(videoOptions);
			
		} else {
			quoteOptions.css('display', 'none');
			videoOptions.css('display', 'none');
			linkOptions.css('display', 'none');
			audioOptions.css('display', 'none');
			imageOptions.css('display', 'none');
		}
		
	});
	
	if(quoteTrigger.is(':checked'))
		quoteOptions.css('display', 'block');
		
	if(linkTrigger.is(':checked'))
		linkOptions.css('display', 'block');
		
	if(videoTrigger.is(':checked'))
		videoOptions.css('display', 'block');
		
	function babysitterHideAll(notThisOne) {
		videoOptions.css('display', 'none');
		quoteOptions.css('display', 'none');
		linkOptions.css('display', 'none');
		notThisOne.css('display', 'block');
	}
	
	
});