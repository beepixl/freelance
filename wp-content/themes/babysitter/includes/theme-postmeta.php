<?php

// ==== Post Format meta boxes ====================================== //


// === Define Metabox Fields ====================================== //

$prefix = 'babysitter_';
 
$meta_box_quote = array(
	'id' => 'babysitter-meta-box-quote',
	'title' =>  __('Quote Settings', 'babysitter'),
	'page' => 'post',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array( "name" => __('Author','babysitter'),
				"desc" => __('Put quote author in this field.','babysitter'),
				"id" => $prefix."author_quote",
				"type" => "text",
				'std' => ""
			),
		array( "name" => __('Position','babysitter'),
				"desc" => __('Put author position in this field.','babysitter'),
				"id" => $prefix."author_position",
				"type" => "text",
				'std' => ""
			)
	),
	
	
);

$meta_box_link = array(
	'id' => 'babysitter-meta-box-link',
	'title' =>  __('Link Settings', 'babysitter'),
	'page' => 'post',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array( "name" => __('The URL','babysitter'),
				"desc" => __('Insert the URL you wish to link to.','babysitter'),
				"id" => $prefix."link_url",
				"type" => "text",
				'std' => ""
			),
	),
	
);

add_action('admin_menu', 'babysitter_add_box');


/*-----------------------------------------------------------------------------------*/
/*	Add metabox to edit page
/*-----------------------------------------------------------------------------------*/
 
function babysitter_add_box() {
	global $meta_box_quote, $meta_box_link, $meta_box_image, $meta_box_audio;
 
	add_meta_box($meta_box_quote['id'], $meta_box_quote['title'], 'babysitter_show_box_quote', $meta_box_quote['page'], $meta_box_quote['context'], $meta_box_quote['priority']);

	add_meta_box($meta_box_link['id'], $meta_box_link['title'], 'babysitter_show_box_link', $meta_box_link['page'], $meta_box_link['context'], $meta_box_link['priority']);
}


/*-----------------------------------------------------------------------------------*/
/*	Callback function to show fields in meta box
/*-----------------------------------------------------------------------------------*/

function babysitter_show_box_quote() {
	global $meta_box_quote, $post;

	// Use nonce for verification
	echo '<input type="hidden" name="babysitter_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
 
	echo '<table class="form-table">';
 
	foreach ($meta_box_quote['fields'] as $field) {
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);
		switch ($field['type']) {
 			
 			//If Text		
			case 'text':
			
			echo '<tr>',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><span style=" display:block; color:#999; margin:5px 0 0 0; line-height: 18px;">'. $field['desc'].'</span></label></th>',
				'<td>';
			echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '" size="30" style="width:75%; margin-right: 20px; float:left;" />';
			
			break;
			
			//If textarea		
			case 'textarea':
			
			echo '<tr>',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><span style="line-height:18px; display:block; color:#999; margin:5px 0 0 0;">'. $field['desc'].'</span></label></th>',
				'<td>';
			echo '<textarea name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '" rows="8" cols="5" style="width:75%; margin-right: 20px; float:left;">', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '</textarea>';
			
			break;

		}

	}
 
	echo '</table>';
}

function babysitter_show_box_link() {
	global $meta_box_link, $post;

	// Use nonce for verification
	echo '<input type="hidden" name="babysitter_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
 
	echo '<table class="form-table">';
 
	foreach ($meta_box_link['fields'] as $field) {
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);
		switch ($field['type']) {
 
			
			//If Text		
			case 'text':
			
			echo '<tr>',
				'<th style="width:25%"><label for="', $field['id'], '"><strong>', $field['name'], '</strong><span style=" display:block; color:#999; margin:5px 0 0 0; line-height: 18px;">'. $field['desc'].'</span></label></th>',
				'<td>';
			echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES)), '" size="30" style="width:75%; margin-right: 20px; float:left;" />';
			
			break;

		}

	}
 
	echo '</table>';
}
 
add_action('save_post', 'babysitter_save_data');


/*-----------------------------------------------------------------------------------*/
/*	Save data when post is edited
/*-----------------------------------------------------------------------------------*/
 
function babysitter_save_data($post_id) {
	global $meta_box_quote, $meta_box_link, $meta_box_image, $meta_box_audio;
 
	// verify nonce
	if ( !isset($_POST['babysitter_meta_box_nonce']) || !wp_verify_nonce( $_POST['babysitter_meta_box_nonce'], basename(__FILE__) )) {
		return $post_id;
	}
 
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}
 
	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
 
	foreach ($meta_box_quote['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
 
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], stripslashes(htmlspecialchars($new)));
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
	
	foreach ($meta_box_link['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
 
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], stripslashes(htmlspecialchars($new)));
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}

}