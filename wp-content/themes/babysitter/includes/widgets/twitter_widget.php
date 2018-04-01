<?php
add_action('widgets_init', 'twitter_load_widgets');

function twitter_load_widgets()
{
	register_widget('Twitter_Widget');
}

class Twitter_Widget extends WP_Widget {
	
	function Twitter_Widget()
	{
		$widget_ops = array('classname' => 'twitter', 'description' => 'The most recent tweets from your feed.');

		$control_ops = array('id_base' => 'twitter-widget');

		$this->WP_Widget('twitter-widget', 'Babysitter - Twitter Feed', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$notweets = $instance['notweets'];

		echo $before_widget;

		if($title) {
			echo $before_title.$title.$after_title;
		}
		?>
		
		<!-- Tweets List -->
		<?php
			$tweets = getTweets($notweets);//change number up to 20 for number of tweets
			
			if(!isset($tweets['error'])) {
				if(is_array($tweets)){

					// to use with intents
					echo '<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>';

					echo '<ul class="twitter_update_list">';

					foreach($tweets as $tweet){

						echo '<li>';

						if($tweet['text']){
						  $the_tweet = $tweet['text'];

						  /*
						  Twitter Developer Display Requirements
						  https://dev.twitter.com/terms/display-requirements

						  2.b. Tweet Entities within the Tweet text must be properly linked to their appropriate home on Twitter. For example:
						    i. User_mentions must link to the mentioned user's profile.
						   ii. Hashtags must link to a twitter.com search with the hashtag as the query.
						  iii. Links in Tweet text must be displayed using the display_url
						       field in the URL entities API response, and link to the original t.co url field.
						  */

						  // i. User_mentions must link to the mentioned user's profile.
						  if(is_array($tweet['entities']['user_mentions'])){
						      foreach($tweet['entities']['user_mentions'] as $key => $user_mention){
						          $the_tweet = preg_replace(
						              '/@'.$user_mention['screen_name'].'/i',
						              '<a href="http://www.twitter.com/'.$user_mention['screen_name'].'" target="_blank">@'.$user_mention['screen_name'].'</a>',
						              $the_tweet);
						      }
						  }

						  // ii. Hashtags must link to a twitter.com search with the hashtag as the query.
						  if(is_array($tweet['entities']['hashtags'])){
						      foreach($tweet['entities']['hashtags'] as $key => $hashtag){
						          $the_tweet = preg_replace(
						              '/#'.$hashtag['text'].'/i',
						              '<a href="https://twitter.com/search?q=%23'.$hashtag['text'].'&src=hash" target="_blank">#'.$hashtag['text'].'</a>',
						              $the_tweet);
						      }
						  }

						  // iii. Links in Tweet text must be displayed using the display_url
						  //      field in the URL entities API response, and link to the original t.co url field.
						  if(is_array($tweet['entities']['urls'])){
						      foreach($tweet['entities']['urls'] as $key => $link){
						          $the_tweet = preg_replace(
						              '`'.$link['url'].'`',
						              '<a href="'.$link['url'].'" target="_blank">'.$link['url'].'</a>',
						              $the_tweet);
						      }
						  }

						  echo '<div class="twitter-text">';
						  echo $the_tweet;
						  echo '</div>';


						  // 3. Tweet Actions
						  //    Reply, Retweet, and Favorite action icons must always be visible for the user to interact with the Tweet. These actions must be implemented using Web Intents or with the authenticated Twitter API.
						  //    No other social or 3rd party actions similar to Follow, Reply, Retweet and Favorite may be attached to a Tweet.
						  // get the sprite or images from twitter's developers resource and update your stylesheet

						  // echo '
						  // <div class="twitter_intents">
						  //     <a class="reply" href="https://twitter.com/intent/tweet?in_reply_to='.$tweet['id_str'].'">Reply</a>
						  //     <a class="retweet" href="https://twitter.com/intent/retweet?tweet_id='.$tweet['id_str'].'">Retweet</a>
						  //     <a class="favorite" href="https://twitter.com/intent/favorite?tweet_id='.$tweet['id_str'].'">Favorite</a>
						  // </div>';


						  // 4. Tweet Timestamp
						  //    The Tweet timestamp must always be visible and include the time and date. e.g., “3:00 PM - 31 May 12”.
						  // 5. Tweet Permalink
						  //    The Tweet timestamp must always be linked to the Tweet permalink.
						  echo '
					      <a class="timesince" href="https://twitter.com/'.$tweet['user']['screen_name'].'/status/'.$tweet['id_str'].'" target="_blank">
					          '.twitter_time(date('h:i A M d',strtotime($tweet['created_at']. '- 8 hours'))).'
					      </a>';// -8 GMT for Pacific Standard Time
						} else {
						  echo '
						  <br />
						  <a href="http://twitter.com/'.$tweet['user']['screen_name'].'" target="_blank">Click here to read '.$tweet['user']['screen_name'].'\'s Twitter feed</a>';
						}

					   echo '</li>';

					}
				echo '</ul>';
				}
			}
		 ?>
		<!-- //Tweets List -->
		
		<?php
		
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['notweets'] = $new_instance['notweets'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array(
			'title' => 'Twitter Updates',
			'notweets' => 2
		);
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo _e('Title:', 'babysitter') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		</p>
			<label for="<?php echo $this->get_field_id('notweets'); ?>"><?php echo _e('Number of Tweets:', 'babysitter') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('notweets'); ?>" name="<?php echo $this->get_field_name('notweets'); ?>" value="<?php echo $instance['notweets']; ?>" />
		</p>

	<?php
	}
}
?>