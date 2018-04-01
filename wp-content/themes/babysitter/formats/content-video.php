<?php
/**
 * The template for displaying posts in the Video post format.
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('entry entry__video'); ?>>

	<!-- begin post heading -->
	<header class="entry-header clearfix">
		<div class="format-icon">
			<div class="format-icon-inner">
				<i class="icon-film"></i>
			</div>
		</div>
		<div class="entry-header-inner">
			<?php if(!is_singular()) : ?>
			<h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php _e('Permalink to:', 'babysitter');?> <?php the_title(); ?>"><?php the_title(); ?></a></h3>
			<?php else :?>
			<h1 class="entry-title" rel="bookmark"><?php the_title(); ?></h1>
			<?php endif; ?>

			<?php get_template_part('formats/post-meta'); ?>
			
		</div>
	</header>
	<!-- end post heading -->
	
	<!-- begin post content -->
	<div class="entry-content">
		<?php the_content(); ?>
	</div>
	<!-- end post content -->
 
</article>