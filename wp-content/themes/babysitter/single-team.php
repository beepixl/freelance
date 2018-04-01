<?php get_header(); ?>

<?php
	// get the meta information and display if supplied
	$position = get_post_meta($post->ID, 'babysitter_team_role', true);

	// get the social links information and display if supplied
	$twitter = get_post_meta($post->ID, 'babysitter_team_twitter', true);
	$facebook = get_post_meta($post->ID, 'babysitter_team_facebook', true);
	$googleplus = get_post_meta($post->ID, 'babysitter_team_gplus', true);
	$dribbble = get_post_meta($post->ID, 'babysitter_team_dribbble', true);
	$linkedin = get_post_meta($post->ID, 'babysitter_team_linkedin', true);
?>

	<div class="container clearfix">
		<!-- Content -->
		<div id="content" class="grid_12">
			<?php while (have_posts()) : the_post(); ?>
			
			<!-- Single Employee -->
			<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix item-team item-team__single'); ?>>

				<div class="clearfix">
					<div class="grid_3 alpha">
						<!-- Post Thumbnail -->
						<?php if ( has_post_thumbnail()) : // Check if Thumbnail exists ?>
							<figure class="alignnone single-team-thumb">
								<?php the_post_thumbnail(); // Fullsize image for the single post ?>
							</figure>
						<?php endif; ?>
						<!-- /Post Thumbnail -->
						<div class="clear"></div>
						<br>
						<!-- Post Heading -->
						<hgroup class="single-team-title">
							<h4><?php the_title(); ?></h4>
							<p><?php echo $position; ?></p>
						</hgroup>
						<!-- /Post Heading -->
						<!-- Social Links -->
						<ul class="social-links unstyled">

							<?php if($twitter) { ?>
							<li class="link-twitter"><a href="<?php echo $twitter; ?>"><i class="icon-twitter"></i></a></li>
							<?php } ?>

							<?php if($facebook) { ?>
							<li class="link-facebook"><a href="<?php echo $facebook; ?>"><i class="icon-facebook"></i></a></li>
							<?php } ?>
							
							<?php if($googleplus) { ?>
							<li class="link-googleplus"><a href="<?php echo $googleplus; ?>"><i class="icon-google-plus"></i></a></li>
							<?php } ?>
							
							<?php if($dribbble) { ?>
							<li class="link-dribbble"><a href="<?php echo $dribbble; ?>"><i class="icon-dribbble"></i></a></li>
							<?php } ?>

							<?php if($linkedin) { ?>
							<li class="link-linkedin"><a href="<?php echo $linkedin; ?>"><i class="icon-linkedin"></i></a></li>
							<?php } ?>
							
						</ul>
						<!-- /Social Links -->
					</div>
					<!-- Post Content -->
					<div class="grid_9 omega">
						<?php the_content(''); ?>
					</div>
					<!-- /Post Content -->
				</div>
				
			</article>
			<!-- /Single Employee -->
			
		<?php endwhile; ?>
		</div>
		<!-- Content / End -->
	</div>

<?php get_footer(); ?>