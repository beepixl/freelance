<?php // Template Name: Full width Page ?>
<?php get_header(); ?>
<!-- Content -->
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<div id="post-<?php the_ID(); ?>" <?php post_class('page'); ?>>
	<?php the_content(); ?>							
</div><!--// Page -->
	<?php 
	wp_link_pages('before=<div class="pagination">&after=</div>');
	endwhile; ?>
<!-- /Content -->

<?php get_footer(); ?>