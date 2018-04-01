<?php get_header(); ?>

<!-- Content -->
<div id="content" class="grid_8 <?php echo of_get_option('blog_sidebar', 'right'); ?>">
	
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class('page'); ?>>
		<div id="page-content">
		  	<?php the_content(); ?>							
		</div><!--#pageContent -->
	</article><!--#post-# .post-->
  	<?php 
  	wp_link_pages('before=<div class="pagination">&after=</div>');
  	endwhile; ?>

</div>
<!-- /Content -->

<!-- Sidebar -->
<aside id="sidebar" class="grid_4 <?php echo of_get_option('blog_sidebar', 'right'); ?>">
	<?php if(function_exists('generated_dynamic_sidebar')) { 
		generated_dynamic_sidebar();
	} else {
		get_sidebar();
	}?>
</aside>
<!-- /Sidebar -->

<?php get_footer(); ?>