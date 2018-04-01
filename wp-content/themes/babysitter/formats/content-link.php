<?php
	$url =  get_post_meta(get_the_ID(), 'babysitter_link_url', true);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('entry entry__link'); ?>>

	<!-- begin post heading -->
	<header class="entry-header clearfix">
		<div class="format-icon">
			<div class="format-icon-inner">
				<i class="icon-link"></i>
			</div>
		</div>
		<div class="entry-header-inner">
			<?php if(!is_singular()) : ?>
			<h3 class="entry-title">
				<a target="_blank" href="<?php echo $url; ?>" title="<?php _e('Permalink to:', 'babysitter');?> <?php echo $url; ?>"><span><?php the_title(); ?></span></a>
			</h3>
			<?php else :?>
			<h1 class="entry-title">
				<a target="_blank" href="<?php echo $url; ?>" title="<?php _e('Permalink to:', 'babysitter');?> <?php echo $url; ?>"><span><?php the_title(); ?></span></a>
			</h1>
			<?php endif; ?>

			<span class="entry-source-link"><a href="<?php echo $url; ?>"><?php echo $url; ?></a></span>
		</div>
	</header>
	<!-- end post heading -->
	
	<!-- begin post content -->
	<div class="entry-content">
		<?php the_content(); ?>
	</div>
	<!-- end post content -->
 
</article>