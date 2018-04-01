<article id="post-<?php the_ID(); ?>" <?php post_class('entry entry__standard'); ?>>
	
	<?php if(has_post_thumbnail()) { ?>
	<!-- begin post image -->
	<figure class="thumb">
		<?php if(is_singular()) : ?>
			<?php the_post_thumbnail('large'); ?>
		<?php else :?>
			<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large'); ?></a>
		<?php endif; ?>
	</figure>
	<!-- end post image -->
	<?php } ?>

	<!-- begin post heading -->
	<header class="entry-header clearfix">
		<div class="format-icon">
			<div class="format-icon-inner">
				<i class="icon-file-alt"></i>
			</div>
		</div>
		<div class="entry-header-inner">
			<?php if(!is_singular()) : ?>
			<h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php _e('Permalink to:', 'babysitter');?> <?php the_title(); ?>"><?php the_title(); ?></a></h3>
			<?php else :?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php endif; ?>

			<?php get_template_part('formats/post-meta'); ?>
			
		</div>
	</header>
	<!-- end post heading -->
	
	<!-- begin post content -->
	<div class="entry-content">

		<?php if(is_singular()) { ?>

			<?php the_content(); ?>

		<?php } else { ?>

			<?php if(get_the_excerpt()) {
				the_excerpt();
			} else {
				the_content();
			} ?>

		<?php } ?>
		
	</div>
	<!-- end post content -->
	
	<?php if(!is_singular() && get_the_excerpt()) { ?>
	<!-- begin post footer -->
	<footer class="entry-footer">
		<a href="<?php the_permalink() ?>" class="more-link"><?php _e('Read more', 'babysitter'); ?> <i class="icon-chevron-sign-right"></i></a>
	</footer>
	<!-- end post footer -->
	<?php } ?>
 
</article>