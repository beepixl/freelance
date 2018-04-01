<!-- FlexSlider -->
<section class="slider">
	<div id="flexslider" class="flexslider loading">
		<ul class="slides">
			
			<?php 
			query_posts("post_type=slides&posts_per_page=-1&post_status=publish&order=ASC&orderby=menu_order");
			while ( have_posts() ) : the_post();

			$custom = get_post_custom($post->ID);
			$url = get_post_custom_values("babysitter_slides_url");
			$txt1 = get_post_custom_values("babysitter_slides_txt1");
			$sl_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'slide'); ?>
			
			<li>
				<!-- Featured Thumbnail -->
				<?php if(has_post_thumbnail($post->ID)) { ?>
					<img src="<?php echo $sl_image_url[0] ?>" alt="" />
				<?php } ?>
				<!-- /Featured Thumbnail -->
				
				<?php if(of_get_option('flex_title') != "false") { ?>
				<!-- Caption -->
				<div class="flexslider-desc">
					<h1><?php the_title(); ?></h1>
					
					<?php if($txt1[0]) { ?>
					<a href="<?php echo $url[0]; ?>" class="link"><?php echo $txt1[0]; ?> <i class="icon-double-angle-right"></i></a>
					<?php } ?>
					
				</div>
				<!-- /Caption -->
				<?php } ?>
				
			</li>
			
			<?php endwhile;
		   wp_reset_query(); ?>
		</ul>
	</div>
</section>

<script>
	jQuery(window).load(function(){

		jQuery('#flexslider').flexslider({
		  	animation: "<?php echo of_get_option('flex_effect'); ?>",
			directionNav: <?php echo of_get_option('flex_directionnav', 'true'); ?>,
			prevText: "<i class='icon-chevron-left'></i>",
 			nextText: "<i class='icon-chevron-right'></i>",
			controlNav: <?php echo of_get_option('flex_controls', 'true'); ?>,
			startAt: <?php echo of_get_option('flex_startat', '0'); ?>,
			slideshow: <?php echo of_get_option('flex_autoplay', 'true'); ?>,
			slideshowSpeed: <?php echo of_get_option('flex_slideshowspeed', '7000'); ?>,
			randomize: <?php echo of_get_option('flex_randomize', 'false'); ?>,

			start: function(slider){
				jQuery('#flexslider').removeClass('loading');
			}
		});
	});
</script>
<!-- /FlexSlider -->