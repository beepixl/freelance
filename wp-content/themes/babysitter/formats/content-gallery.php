<article id="post-<?php the_ID(); ?>" <?php post_class('entry entry__gallery'); ?>>
	
	<!-- begin post image -->
	<div class="flexslider loading flexslider__nav-on">
		<ul class="slides">
			<?php 
			$args = array(
				'orderby'		 => 'menu_order',
				'order' => 'ASC',
				'post_type'      => 'attachment',
				'post_parent'    => get_the_ID(),
				'post_mime_type' => 'image',
				'post_status'    => null,
				'numberposts'    => -1,
				'exclude' => get_post_thumbnail_id()
			);
			$attachments = get_posts($args); ?>

			<?php if ($attachments) : ?>

			<?php foreach ($attachments as $attachment) : ?>

			<?php $attachment_image = wp_get_attachment_image_src($attachment->ID, 'large'); ?>
			<?php $full_image = wp_get_attachment_image_src($attachment->ID, 'full'); ?>
			<?php $attachment_data = wp_get_attachment_metadata($attachment->ID); ?>
			
			<li>
				<img src="<?php echo $attachment_image[0]; ?>" alt="<?php echo $attachment->post_title; ?>" width="680" height="208" />
			</li>

			<?php endforeach; ?>
			<?php endif; ?>
		</ul>
	</div>
	<!-- end post image -->

	<!-- begin post heading -->
	<header class="entry-header clearfix">
		<div class="format-icon">
			<div class="format-icon-inner">
				<i class="icon-picture"></i>
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
		<?php the_content(); ?>
	</div>
	<!-- end post content -->
	
 
</article>