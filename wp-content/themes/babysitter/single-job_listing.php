<?php get_header(); ?>

<!-- Content -->
<div id="content" class="grid_12 <?php echo of_get_option('blog_sidebar', 'right'); ?>">

	<?php while (have_posts()) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class('single-job_listing'); ?>>

		<?php get_template_part('job_manager/content-single-job_listing'); ?>
	 
	</article>

	<?php wp_link_pages('before=<div class="pagination">&after=</div>'); ?>
		
	<?php endwhile; ?>

</div>
<!-- /Content -->
		

<?php get_footer(); ?>