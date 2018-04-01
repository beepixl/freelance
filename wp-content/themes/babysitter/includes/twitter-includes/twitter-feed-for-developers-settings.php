<?php

//Add an option page
if (is_admin()) {
  add_action('admin_menu', 'tdf_menu');
  add_action('admin_init', 'tdf_register_settings');
}

function tdf_menu() {
	add_options_page('Twitter Settings','Twitter Settings','manage_options','tdf_settings','tdf_settings_output');
}

function tdf_settings() {
	$tdf = array();
	$tdf[] = array('name'=>'tdf_consumer_key','label'=>'Consumer Key');
	$tdf[] = array('name'=>'tdf_consumer_secret','label'=>'Consumer Secret');
	$tdf[] = array('name'=>'tdf_access_token','label'=>'Access Token');
	$tdf[] = array('name'=>'tdf_access_token_secret','label'=>'Access Token Secret');
	$tdf[] = array('name'=>'tdf_cache_expire','label'=>'Cache Duration (in seconds <em>Default 3600s</em>)');
	$tdf[] = array('name'=>'tdf_user_timeline','label'=>'Twitter Username');
	return $tdf;
}

function tdf_register_settings() {
	$settings = tdf_settings();
	foreach($settings as $setting) {
		register_setting('tdf_settings',$setting['name']);
	}
}


function tdf_settings_output() {
	$settings = tdf_settings();
	
	echo '<div class="wrap">';
	
		echo '<h2>Twitter Settings</h2>';
		
		echo '<p>Most of this configuration can found on the application overview page on the <a href="http://dev.twitter.com/apps">http://dev.twitter.com</a> website. <br />';
		echo 'When creating an application, you don\'t need to set a callback location and you only need read access.<br />';
		echo 'You will need to generate an oAuth token once you\'ve created the application. The button for that is on the bottom of the application overview page.</p>';
		
		echo '<div style="display:block; overflow:hidden; height:1px; background:#DFDFDF; margin:20px 0;"></div>';
		
		echo '<form method="post" action="options.php">';
		
    settings_fields('tdf_settings');
		
		echo '<table>';
			foreach($settings as $setting) {
				echo '<tr>';
					echo '<td>'.$setting['label'].'</td>';
					echo '<td><input type="text" style="width: 400px" name="'.$setting['name'].'" value="'.get_option($setting['name']).'" /></td>';
				echo '</tr>';
			}
		echo '</table>';
		
		submit_button();
		
		echo '</form>';
		
		// echo '<div style="display:block; overflow:hidden; height:1px; background:#DFDFDF; margin:20px 0;"></div>';
		
		// echo '<h3>Debug Information</h3>';
		// $last_error = get_option('tdf_last_error');
		// if (empty($last_error)) $last_error = "None";
		// echo 'Last Error: '.$last_error.'</p>';
	
	echo '</div>';
	
}