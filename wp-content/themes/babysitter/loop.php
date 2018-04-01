<?php if (have_posts()): while (have_posts()) : the_post(); 
	// The following determines what the post format is and shows the correct file accordingly
	$format = get_post_format();
	get_template_part( 'formats/content-'.$format );					
	if($format == '') {
		get_template_part( 'formats/content-standard' );
	}
	
endwhile; else: ?>

	<!-- Article -->
	<article class="entry">
		<h2><?php _e( 'Sorry, nothing to display.', 'babysitter' ); ?></h2>
	</article>
	<!-- /Article -->

<?php endif; ?>