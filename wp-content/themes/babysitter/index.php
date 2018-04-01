<?php get_header(); ?>

<?php $blog_sidebar = of_get_option('blog_sidebar', 'right'); ?>

<!-- Content -->
<div id="content" class="grid_8 <?php echo $blog_sidebar; ?>">
	
	<?php get_template_part('loop'); ?>
	
	<!-- Pagination -->
	<?php babysitter_pagination(); ?>
	<!-- /Pagination -->

</div>
<!-- /Content -->

<?php if($blog_sidebar != 'fullblog') { ?>
<!-- Sidebar -->
<aside id="sidebar" class="grid_4 <?php echo $blog_sidebar; ?>">
	<?php get_sidebar(); ?>
</aside>
<!-- /Sidebar -->
<?php } ?>

<?php get_footer(); ?>