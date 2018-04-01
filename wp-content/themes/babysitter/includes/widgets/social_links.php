<?php
add_action('widgets_init', 'social_links_load_widgets');

function social_links_load_widgets()
{
	register_widget('Social_Links_Widget');
}

class Social_Links_Widget extends WP_Widget {
	
	function Social_Links_Widget()
	{
		$widget_ops = array('classname' => 'social_links', 'description' => 'Widget displays your social links.');

		$control_ops = array('id_base' => 'social_links-widget');

		$this->WP_Widget('social_links-widget', 'Babysitter - Social Networks', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);

		echo $before_widget;

		if($title) {
			echo $before_title.$title.$after_title;
		}
		?>
		<ul class="social-links">
			<?php if($instance['twitter_link']): ?>
			<li class="link-twitter">
				<a href="<?php echo $instance['twitter_link']; ?>"><i class="icon-twitter"></i></a>
			</li>
			<?php endif; ?>

			<?php if($instance['facebook_link']): ?>
			<li class="link-facebook">
				<a href="<?php echo $instance['facebook_link']; ?>"><i class="icon-facebook"></i></a>
			</li>
			<?php endif; ?>
			
			<?php if($instance['google_link']): ?>
			<li class="link-googleplus">
				<a href="<?php echo $instance['google_link']; ?>"><i class="icon-google-plus"></i></a>
			</li>
			<?php endif; ?>

			<?php if($instance['dribbble_link']): ?>
			<li class="link-dribbble">
				<a href="<?php echo $instance['dribbble_link']; ?>"><i class="icon-dribbble"></i></a>
			</li>
			<?php endif; ?>

			<?php if($instance['pinterest_link']): ?>
			<li class="link-pinterest">
				<a href="<?php echo $instance['pinterest_link']; ?>"><i class="icon-pinterest"></i></a>
			</li>
			<?php endif; ?>
			
			<?php if($instance['linkedin_link']): ?>
			<li class="link-linkedin">
				<a href="<?php echo $instance['linkedin_link']; ?>"><i class="icon-linkedin"></i></a>
			</li>
			<?php endif; ?>

			<?php if($instance['instagram_link']): ?>
			<li class="link-instagram">
				<a href="<?php echo $instance['instagram_link']; ?>"><i class="icon-instagram"></i></a>
			</li>
			<?php endif; ?>

			<?php if($instance['youtube_link']): ?>
			<li class="link-youtube">
				<a href="<?php echo $instance['youtube_link']; ?>"><i class="icon-youtube"></i></a>
			</li>
			<?php endif; ?>
			
			<?php if($instance['rss_link']): ?>
			<li class="link-rss">
				<a href="<?php echo $instance['rss_link']; ?>"><i class="icon-rss"></i></a>
			</li>
			<?php endif; ?>
		</ul>
		<?php
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = $new_instance['title'];
		$instance['twitter_link'] = $new_instance['twitter_link'];
		$instance['facebook_link'] = $new_instance['facebook_link'];
		$instance['google_link'] = $new_instance['google_link'];
		$instance['dribbble_link'] = $new_instance['dribbble_link'];
		$instance['pinterest_link'] = $new_instance['pinterest_link'];
		$instance['linkedin_link'] = $new_instance['linkedin_link'];
		$instance['instagram_link'] = $new_instance['instagram_link'];
		$instance['youtube_link'] = $new_instance['youtube_link'];
		$instance['rss_link'] = $new_instance['rss_link'];

		return $instance;
	}

	function form($instance)
	{
		/* Set up some default widget settings. */
		$defaults = array( 
			'title' => 'Social Networks',
			'twitter_link' => '',
			'facebook_link' => '',
			'google_link' => '',
			'dribbble_link' => '',
			'pinterest_link' => '',
			'linkedin_link' => '',
			'instagram_link' => '',
			'youtube_link' => '',
			'rss_link' => ''
		);
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo _e('Title:', 'babysitter') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('twitter_link'); ?>"><?php echo _e('Twitter Link:', 'babysitter') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('twitter_link'); ?>" name="<?php echo $this->get_field_name('twitter_link'); ?>" value="<?php echo $instance['twitter_link']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('facebook_link'); ?>"><?php echo _e('Facebook Link:', 'babysitter') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('facebook_link'); ?>" name="<?php echo $this->get_field_name('facebook_link'); ?>" value="<?php echo $instance['facebook_link']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('google_link'); ?>"><?php echo _e('Google+ Link:', 'babysitter') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('google_link'); ?>" name="<?php echo $this->get_field_name('google_link'); ?>" value="<?php echo $instance['google_link']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('dribbble_link'); ?>"><?php echo _e('Dribbble Link:', 'babysitter') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('dribbble_link'); ?>" name="<?php echo $this->get_field_name('dribbble_link'); ?>" value="<?php echo $instance['dribbble_link']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('pinterest_link'); ?>"><?php echo _e('Pinterest Link:', 'babysitter') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('pinterest_link'); ?>" name="<?php echo $this->get_field_name('pinterest_link'); ?>" value="<?php echo $instance['pinterest_link']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('linkedin_link'); ?>"><?php echo _e('Linkedin Link:', 'babysitter') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('linkedin_link'); ?>" name="<?php echo $this->get_field_name('linkedin_link'); ?>" value="<?php echo $instance['linkedin_link']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('instagram_link'); ?>"><?php echo _e('Instagram Link:', 'babysitter') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('instagram_link'); ?>" name="<?php echo $this->get_field_name('instagram_link'); ?>" value="<?php echo $instance['instagram_link']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('youtube_link'); ?>"><?php echo _e('YouTube Channel:', 'babysitter') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('youtube_link'); ?>" name="<?php echo $this->get_field_name('youtube_link'); ?>" value="<?php echo $instance['youtube_link']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('rss_link'); ?>"><?php echo _e('RSS Link:', 'babysitter') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('rss_link'); ?>" name="<?php echo $this->get_field_name('rss_link'); ?>" value="<?php echo $instance['rss_link']; ?>" />
		</p>
	<?php
	}
}
?>