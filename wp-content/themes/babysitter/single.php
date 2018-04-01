<?php get_header(); ?>

<?php $blog_sidebar = of_get_option('blog_sidebar', 'right'); ?>

<!-- Content -->
<div id="content" class="grid_8 <?php echo $blog_sidebar; ?>">

	<?php while (have_posts()) : the_post();

	// The following determines what the post format is and shows the correct file accordingly
	$format = get_post_format();
	get_template_part( 'formats/content-'.$format );					
	if($format == '') {
		get_template_part( 'formats/content-standard' );
	}
	wp_link_pages('before=<div class="pagination">&after=</div>');
		
	endwhile; ?>

	<!-- Comments -->
	<?php comments_template(); ?>
	<!-- /Comments -->
	
	<!-- Comment links -->
	<?php paginate_comments_links(); ?>
	<!-- /Comment links --> 

</div>
<!-- /Content -->

<?php if($blog_sidebar != 'fullblog') { ?>
<!-- Sidebar -->
<aside id="sidebar" class="grid_4 <?php echo of_get_option('blog_sidebar', 'right'); ?>">
	<?php if(function_exists('generated_dynamic_sidebar')) { 
		generated_dynamic_sidebar();
	} else {
		get_sidebar();
	}?>
</aside>
<!-- /Sidebar -->
<?php } ?>
		

<?php get_footer(); ?>