<?php if(config_item('enable_search_details_on_top') == TRUE): ?>

<script language="javascript">
$(document).ready(function(){
	if($('.top_content').length == 0 && $(window).width() > 767)
    {
    	var content = $('.wrap-search');
    	var pos = content.offset();
    	
    	$(window).scroll(function(){
    		if($(this).scrollTop() > pos.top && $(window).width() > 767){
              content.addClass('search_on_top');
    		} else if($(this).scrollTop() <= pos.top){
              content.removeClass('search_on_top');
    		}
    	});
    }
});
</script>
<?php endif; ?>

<!-- The Gallery as lightbox dialog, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>

{settings_tracking}