<?php get_header(); ?>

<div class="grid_12">
	<h1 class="error-404"><?php _e( '404', 'babysitter' ); ?></h1>
	<div class="error-404-desc center">
		<h3>
			<a href="<?php echo home_url(); ?>"><?php _e( 'Return home?', 'babysitter' ); ?></a>
		</h3>
		<?php echo '<p>' . __('Please try using our search box below to look for information on the our site.', 'babysitter') . '</p>'; ?>
   	<div class="clearfix">
   		<div class="grid_4 prefix_4 suffix_4"><?php get_search_form(); /* outputs the default Wordpress search form */ ?></div>
   	</div>
	</div>
</div>

<?php get_footer(); ?>