<?php /* Template Name: Contacts Page */ ?>

<?php get_header(); ?>
		
<?php if(of_get_option('gmap_show', 'yes') == 'yes'): ?>
<div class="clearfix">
	<div class="grid_12">
		<!-- BEGIN GOOGLE MAP -->
		<script type="text/javascript">
			jQuery(document).ready(function() {
				//getter
				var zoom= jQuery('#map_canvas').gmap('option', 'zoom');
				
				jQuery('#map_canvas').gmap().bind('init', function(ev, map) {
					jQuery('#map_canvas').gmap('addMarker', {'position': '<?php echo of_get_option('gmap_coord', '57.669645,11.926832'); ?>', 'bounds': true});
					jQuery('#map_canvas').gmap('option', 'zoom', <?php echo of_get_option('gmap_zoom', '14'); ?>);
				});
			});
		</script><!-- Google Map Init-->
	
		<div class="map-wrapper">
			<div id="map_canvas" class="map-canvas"></div>
		</div>
		<!-- END GOOGLE MAP -->
	</div>
</div>
<?php endif; ?>
					
<div class="clearfix">
	<div class="grid_4">
		<!-- Contact Information -->
		<?php if(of_get_option('contact_title')): ?>
		<h2><?php echo of_get_option('contact_title'); ?></h2>
		<?php endif; ?>
		<ul class="contact-info">
			<?php if(of_get_option('contact_address')): ?>
			<li>
				<i class="icon-map-marker"></i> <strong><?php echo of_get_option('contact_address'); ?></strong>
			</li>
			<?php endif; ?>
			<?php if(of_get_option('contact_phone')): ?>
			<li>
				<i class="icon-phone"></i> <?php echo of_get_option('contact_phone'); ?>
				<?php endif; ?>
			</li>
			<?php if(of_get_option('contact_mail')): ?>
			<li>
				<i class="icon-envelope"></i> <a href="mailto:<?php echo of_get_option('contact_mail'); ?>"><?php echo of_get_option('contact_mail'); ?></a>
			</li>
			<?php endif; ?>
			<?php if(of_get_option('contact_site')): ?>
			<li>
				<i class="icon-globe"></i> <a href="http://<?php echo of_get_option('contact_site'); ?>"><?php echo of_get_option('contact_site'); ?></a>
			</li>
			<?php endif; ?>
			<?php if(of_get_option('contact_info')): ?>
			<li>
				<i class="icon-time"></i> <?php echo of_get_option('contact_info'); ?>
			</li>
			<?php endif; ?>
		</ul>
		<!-- Contact Information / End -->
	</div>
	<div class="grid_8">
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('page'); ?>>
			<div id="page-content">
			  	<?php the_content(); ?>							
			</div>
		</div><!-- .post-->
	  	<?php endwhile; ?>
	</div>
</div>

<?php get_footer(); ?>