<?php get_header(); ?>

<?php $blog_sidebar = of_get_option('blog_sidebar', 'right'); ?>
  
<!-- Content -->
<div id="content" class="grid_8 <?php echo $blog_sidebar; ?>">

	<?php if (have_posts()): while (have_posts()) : the_post(); ?>
			
		<article id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>

			<!-- begin post heading -->
			<header class="entry-header entry-header__small clearfix">
				<div class="entry-header-inner">
					<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php _e('Permalink to:', 'babysitter');?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>

					<?php get_template_part('post-formats/post-meta'); ?>
					
				</div>
			</header>
			<!-- end post heading -->
		 
		</article>

	<?php endwhile; else: ?>

		<!-- Article -->
		<article class="post">
			<h2><?php _e( 'Sorry, nothing to display.', 'babysitter' ); ?></h2>
		</article>
		<!-- /Article -->

	<?php endif; ?>

	<!-- Pagination -->
	<?php babysitter_pagination(); ?>
	<!-- /Pagination -->

</div>
<!--/Content -->

<?php if($blog_sidebar != 'fullblog') { ?>
<!-- Sidebar -->
<aside id="sidebar" class="grid_4 <?php echo $blog_sidebar; ?>">
  <?php get_sidebar(); ?>
</aside>
<!-- /Sidebar -->
<?php } ?>

<?php get_footer(); ?>