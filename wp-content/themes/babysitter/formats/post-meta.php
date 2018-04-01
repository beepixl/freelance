<?php
	$metabox = of_get_option('post_meta');
?>
<?php if($metabox == "1") { ?>
	<p class="post-meta">
		<span class="post-meta-date"><i class="icon-calendar"></i><?php the_time(get_option('date_format')); ?></span>
		<span class="post-meta-cats"><i class="icon-tag"></i><?php the_category(' , '); ?></span>
		<span class="post-meta-author"><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')) ?>"><i class="icon-user"></i><?php the_author();?></a></span>
		<span class="post-meta-comments"><a href="<?php echo get_comments_link(); ?>"><i class="icon-comments-alt"></i><?php comments_number('0', '1', '%'); ?></a></span>
	</p>
<?php }?>