<?php

global $wpsf_settings;
	
/**
 * Map styles */

$map_styles_array = array();

if(file_exists(plugin_dir_path( __FILE__ ).'../inc/cspm-map-styles.php')){
	
	$map_styles = include(plugin_dir_path( __FILE__ ).'../inc/cspm-map-styles.php');
	
	array_multisort($map_styles);
	
	foreach($map_styles as $key => $value){
		$value_output  = '';
		$value_output .= empty($value['new']) ? '' : ' <sup class="cspm_new_tag" style="margin:0 5px 0 -2px;">NEW</sup>';		
		$value_output .= $value['title'];				
		$value_output .= empty($value['demo']) ? '' : ' <sup class="cspm_demo_tag"><a href="'.$value['demo'].'" target="_blank"><small>Demo</small></a></sup>';
		$map_styles_array[$key] = $value_output;
	}
	
}

/**
 * Plugin settings */

$post_types = get_post_types(array('_builtin' => false), 'objects');

$post_types_array = array('post' => __('Posts').' (post)', 'page' => __('Pages').' (page)');

foreach($post_types as $post_type)
	$post_types_array[$post_type->name] = $post_type->labels->name.' ('.$post_type->name.')';
			
$wpsf_settings[] = array(
    'section_id' => 'pluginsettings',
    'section_title' => 'Plugin Settings',
    'section_description' => '',
    'section_order' => 1,
    'fields' => array(
		array(
            'id' => 'post_types_section',
            'title' => '<span class="section_sub_title">Post Types Parameters</span>',
            'desc' => '',
            'type' => 'custom',
        ),		

		/**
		 * "Add location" Form fields */
		 					
		array(
            'id' => 'form_fields_section',
            'title' => '<span class="section_sub_title">"Add location" Form fields</span>',
            'desc' => '',
            'type' => 'custom',
        ),
		array(
            'id' => 'form_fields_msg',
            'title' => '<span class="section_sub_title cspacing_info">IMPORTANT NOTE!<br /><br />This feature is dedicated ONLY for users that want to use the plugin with their already created cutsom fields.
			            You don\'t have any interest to change the name of the form fields below if you will use the plugin with a new post type/website. Just leave them as they are!
						<br />The "Add location" form is located in the "Add/Edit post" page of your post type.<br /><br />*After CHANGING the Latitude & Longitude fields names, 
						SAVE your settings, then use the "Regenerate markers" option above to recreate your markers.</span>',
            'desc' => '',
            'type' => 'custom',
        ),
		array(
            'id' => 'latitude_field_name',
            'title' => '"Latitude" field name',
            'desc' => 'Enter the name of your latitude custom field. Empty spaces are not allowed!',
            'type' => 'text',
            'std' => 'codespacing_progress_map_lat',
        ),
		array(
            'id' => 'longitude_field_name',
            'title' => '"Longitude" field name',
            'desc' => 'Enter the name of your longitude custom field. Empty spaces are not allowed!',
            'type' => 'text',
            'std' => 'codespacing_progress_map_lng',
        ),	
		
		/**
		 * Other parameters */
		  					
		array(
            'id' => 'misc_fields_section',
            'title' => '<span class="section_sub_title">Other parameters</span>',
            'desc' => '',
            'type' => 'custom',
        ),
		array(
            'id' => 'outer_links_field_name',
            'title' => '"Outer links" custom field name',
            'desc' => 'By default, the plugin uses the function get_permalink() of wordpress to get the posts links. <strong>In some cases, users wants to use their locations
			           in the map as links to other pages in outer websites.</strong> To use this option, you MUST have your external <strong>links stored in a custom field</strong>. Enter the name of that
					   custom field or leave this field empty if you don\'t need this option.',
            'type' => 'text',
            'std' => '',
        ),		
		array(
            'id' => 'custom_list_columns',
            'title' => 'Display the coordinates column',
            'desc' => 'This will display a custom column for all custom post types used with the plugin indicating the coordinates of each post on the map.',
            'type' => 'radio',
            'std' => 'no',
			'choices' => array(
				'yes' => 'Yes',
				'no' => 'No'
			)
        ),
		array(
            'id' => 'use_with_wpml',
            'title' => 'Use the plugin with WPML <sup>BETA</sup>',
            'desc' => 'If you are using WPML plugin for translation, select "Yes" to enable the WPML compatibility code.<br />
					  <span style="color:red">Note: After duplicating a post to other languages, you must click one more time on the button "Update" of the original 
					  post in order to add the LatLng coordinates for the duplicated posts on the map.</span>',
            'type' => 'radio',
            'std' => 'no',
			'choices' => array(
				'yes' => 'Yes',
				'no' => 'No'
			)
        ),		
			
	)
);       

/**
 * Map Settings section */

$wpsf_settings[] = array(
    'section_id' => 'mapsettings',
    'section_title' => 'Default Map Settings <sup class="cspm_plus_tag">Plus+</sup>',
    'section_description' => 'The maps displayed through the Google Maps API contain UI elements to allow user interaction with the map. These elements are known as controls and you can include and/or customize variations of these controls in your map.',
    'section_order' => 3,
    'fields' => array(
		array(
            'id' => 'api_key',
            'title' => 'API Key <sup class="cspm_new_tag">NEW</sup>',
			/**
			 * https://developers.google.com/maps/signup?csw=1#google-maps-javascript-api */
            'desc' => 'Enter your Google Maps API key.<br /><strong>Note:</strong> The Google Maps JavaScript API does not require an API key to function correctly. However, Google strongly encourage you to load the Maps API using an APIs Console key which allows you to monitor your application\'s Maps API usage.<br />
					  <a href="https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key" target="_blank">Get an API key</a>',
            'type' => 'text',
            'std' => '',
        ),	
		array(
            'id' => 'map_language',
            'title' => 'Map language',
            'desc' => 'Localize your Maps API application by altering default language settings. See also the <a href="https://developers.google.com/maps/faq#languagesupport" target="_blank">supported list of languages</a>.',
            'type' => 'text',
            'std' => 'en'		
        ),
		array(
            'id' => 'map_center',
            'title' => 'Map center',
            'desc' => 'Enter a center point for the map. (Latitude then Longitude separated by comma). Refer to <a href="https://maps.google.com/" target="_blank">https://maps.google.com/</a> to get you center point.',
            'type' => 'text',
            'std' => '51.53096,-0.121064'		
        ),
        array(
            'id' => 'initial_map_style',
            'title' => 'Initial style',
            'desc' => 'Select the initial map style. <span style="color:red;">If you select "Custom style" you must choose one of the available styles in the section "Map style settings".</span>',
            'type' => 'radio',
            'std' => 'ROADMAP',
            'choices' => array(
				'ROADMAP' => 'Map',
				'SATELLITE' => 'Satellite',
				'TERRAIN' => 'Terrain',
				'HYBRID' => 'Hybrid',
				'custom_style' => 'Custom style'
            )
        ),		
		array(
            'id' => 'map_zoom',
            'title' => 'Map zoom',
            'desc' => 'Select the map zoom.',
            'type' => 'select',
            'std' => '12',
            'choices' => array(
				'0' => '0',
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',
				'7' => '7',
				'8' => '8',
				'9' => '9',
				'10' => '10',
				'11' => '11',
				'12' => '12',
				'13' => '13',
				'14' => '14',
				'15' => '15',
				'16' => '16',
				'17' => '17',
				'18' => '18',
				'19' => '19'
            )
        ),
		array(
            'id' => 'max_zoom',
            'title' => 'Max. zoom',
            'desc' => 'Select the max zoom of the map.',
            'type' => 'select',
            'std' => '19',
            'choices' => array(
				'0' => '0',
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',
				'7' => '7',
				'8' => '8',
				'9' => '9',
				'10' => '10',
				'11' => '11',
				'12' => '12',
				'13' => '13',
				'14' => '14',
				'15' => '15',
				'16' => '16',
				'17' => '17',
				'18' => '18',
				'19' => '19'
            )
        ),
		array(
            'id' => 'min_zoom',
            'title' => 'Min. zoom',
            'desc' => 'Select the min. zoom of the map. <span style="color:red;">The Min. zoom should be lower than the Max. zoom!</span>',
            'type' => 'select',
            'std' => '0',
            'choices' => array(
				'0' => '0',
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',
				'7' => '7',
				'8' => '8',
				'9' => '9',
				'10' => '10',
				'11' => '11',
				'12' => '12',
				'13' => '13',
				'14' => '14',
				'15' => '15',
				'16' => '16',
				'17' => '17',
				'18' => '18',
				'19' => '19'
            )
        ),
		array(
            'id' => 'zoom_on_doubleclick',
            'title' => 'Zoom on double click',
            'desc' => 'Enables/disables zoom and center on double click. Disables by default.',
            'type' => 'radio',
            'std' => 'true',
            'choices' => array(
				'false' => 'Enable',
				'true' => 'Disable'
            )
        ),
        array(
            'id' => 'map_draggable',
            'title' => 'Draggable',
            'desc' => 'If Yes, prevents the map from being dragged. Dragging is enabled by default.',
            'type' => 'radio',
            'std' => 'true',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),
		array(
            'id' => 'useClustring',
            'title' => 'Clustering',
            'desc' => 'Clustering simplifies your data visualization by consolidating data that are nearby each other on the map in an aggregate form.',
            'type' => 'radio',
            'std' => 'true',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),
		array(
            'id' => 'gridSize',
            'title' => 'Grid size',
            'desc' => 'Grid size or Grid-based clustering works by dividing the map into squares of a certain size (the size changes at each zoom) and then grouping the markers into each grid square.',
            'type' => 'text',
            'std' => '60'		
        ),
		array(
            'id' => 'autofit',
            'title' => 'Autofit',
            'desc' => 'This option extends map bounds to contain all markers & clusters.',
            'type' => 'radio',
            'std' => 'false',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),
		array(
            'id' => 'traffic_layer',
            'title' => 'Traffic Layer',
            'desc' => 'Display current road traffic.',
            'type' => 'radio',
            'std' => 'false',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),	
		array(
            'id' => 'transit_layer',
            'title' => 'Transit Layer',
            'desc' => 'Display local Transit route information.',
            'type' => 'radio',
            'std' => 'false',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),
		array(
            'id' => 'recenter_map',
            'title' => 'Recenter Map',
            'desc' => 'Show a button on the map to allow recentring the map. Defaults to "Yes".',
            'type' => 'radio',
            'std' => 'true',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),
		array(
            'id' => 'geotarget_section',
            'title' => '<span class="section_sub_title">Geotarget parameters</span>',
            'desc' => '',
            'type' => 'custom',
        ),				
        array(
            'id' => 'geoIpControl',
            'title' => 'Geo targeting',
            'desc' => 'The Geo targeting is the method of determining the geolocation of a website visitor.',
            'type' => 'radio',
            'std' => 'false',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),	
		array(
            'id' => 'show_user',
            'title' => 'Show user location?',
            'desc' => 'Show a marker indicating the location of the user when choosing to share their location.',
            'type' => 'radio',
            'std' => 'false',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),			
		array(
            'id' => 'user_marker_icon',
            'title' => 'User Marker image',
            'desc' => 'Upload a marker image to display as the user location. When empty, the map will display the default marker of Google Map.',
            'type' => 'file',
            'std' => ''
        ),
		array(
            'id' => 'user_map_zoom',
            'title' => 'Geotarget Zoom',
            'desc' => 'Select the zoom of the map after indicating the location of the user.',
            'type' => 'select',
            'std' => '12',
            'choices' => array(
				'0' => '0',
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',
				'7' => '7',
				'8' => '8',
				'9' => '9',
				'10' => '10',
				'11' => '11',
				'12' => '12',
				'13' => '13',
				'14' => '14',
				'15' => '15',
				'16' => '16',
				'17' => '17',
				'18' => '18',
				'19' => '19'
            )
        ),	
		array(
            'id' => 'user_circle',
            'title' => 'Draw a Circle around the user\'s location',
            'desc' => 'Use this field to draw a circle within a certain distance of the user\'s location. Set to 0 to ignore this option.
					  <br /><strong>The value must be a number (e.g. 10).</strong>.
					  <br /><strong>Note: The circle will use the Distance unit & the style defined in "Search Form settings".</strong>',
            'type' => 'text',
            'std' => '0'
        ),	
		array(
			'id' => 'user_circle_fillColor',
			'title' => 'Fill color',
			'desc' => 'The fill color.',
			'type' => 'color',
			'std' => '#189AC9',
		),
		array(
			'id' => 'user_circle_fillOpacity',
			'title' => 'Fill opacity',
			'desc' => 'The fill opacity between 0.0 and 1.0.',
			'type' => 'select',
			'std' => '0.1',
			'choices' => array(
				'0.0' => '0.0',
				'0.1' => '0.1',
				'0.2' => '0.2',
				'0.3' => '0.3',
				'0.4' => '0.4',
				'0.5' => '0.5',
				'0.6' => '0.6',
				'0.7' => '0.7',
				'0.8' => '0.8',
				'0.9' => '0.9',
				'1' => '1',
			)			
		),
		array(
			'id' => 'user_circle_strokeColor',
			'title' => 'Stroke color',
			'desc' => 'The stroke color.',
			'type' => 'color',
			'std' => '#189AC9',
		),
		array(
			'id' => 'user_circle_strokeOpacity',
			'title' => 'Stroke opacity',
			'desc' => 'The stroke opacity between 0.0 and 1.',
			'type' => 'select',
			'std' => '1',
			'choices' => array(
				'0.0' => '0.0',
				'0.1' => '0.1',
				'0.2' => '0.2',
				'0.3' => '0.3',
				'0.4' => '0.4',
				'0.5' => '0.5',
				'0.6' => '0.6',
				'0.7' => '0.7',
				'0.8' => '0.8',
				'0.9' => '0.9',
				'1' => '1',
			)			
		),
		array(
			'id' => 'user_circle_strokeWeight',
			'title' => 'Stroke weight',
			'desc' => 'The stroke width in pixels.',
			'type' => 'text',
			'std' => '1',
			'attributes' => array(
				'type' => 'number',
				'pattern' => '\d*',
				'min' => '0'
			),				
		),							
		array(
            'id' => 'ui_elements_section',
            'title' => '<span class="section_sub_title">UI elements</span>',
            'desc' => '',
            'type' => 'custom',
        ),	
        array(
            'id' => 'mapTypeControl',
            'title' => 'Show map type control',
            'desc' => 'The MapType control lets the user toggle between map types (such as ROADMAP and SATELLITE). This control appears by default in the top right corner of the map.',
            'type' => 'radio',
            'std' => 'true',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),
        array(
            'id' => 'streetViewControl',
            'title' => 'Show street view control',
            'desc' => 'The Street View control contains a Pegman icon which can be dragged onto the map to enable Street View. This control appears by default in the right top corner of the map.',
            'type' => 'radio',
            'std' => 'false',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),
        array(
            'id' => 'scrollwheel',
            'title' => 'Scroll wheel',
            'desc' => 'Allow/Disallow the zoom-in and zoom-out of the map using the scroll wheel.',
            'type' => 'radio',
            'std' => 'false',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),
        array(
            'id' => 'zoomControl',
            'title' => 'Show zoom control',
            'desc' => 'The Zoom control displays a small "+/-" buttons to control the zoom level of the map. This control appears by default in the top left corner of the map on non-touch devices or in the bottom left corner on touch devices.',
            'type' => 'radio',
            'std' => 'true',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),
        array(
            'id' => 'zoomControlType',
            'title' => 'Zoom control Type',
            'desc' => 'Select the zoom control type.',
            'type' => 'radio',
            'std' => 'customize',
            'choices' => array(
				'customize' => 'Customized type',
				'default' => 'Default type'
            )
        ),
		array(
            'id' => 'markers_customizations_section',
            'title' => '<span class="section_sub_title">Markers Customizations</span>',
            'desc' => '',
            'type' => 'custom',
        ),		
        array(
            'id' => 'retinaSupport',
            'title' => 'Retina support',
            'desc' => 'Enable retina support for custom markers & Clusters images. When enabled, make sure the uploaded image is twice the size you want it to be displayed in the map. 
			           For example, if you want the marker/cluster image in the map to be displayed at 20x30 pixels, upload an image with 40x60 pixels.',
            'type' => 'radio',
            'std' => 'false',
            'choices' => array(
				'true' => 'Enable',
				'false' => 'Disable'
            )
        ),																
        array(
            'id' => 'defaultMarker',
            'title' => 'Marker type',
            'desc' => 'Select the marker type.',
            'type' => 'radio',
            'std' => 'customize',
            'choices' => array(
				'customize' => 'Customized type',
				'default' => 'Default type'
            )
        ),	
		array(
            'id' => 'markerAnimation',
            'title' => 'Marker animation',
            'desc' => 'You can animate a marker so that it exhibit a dynamic movement when it\'s been fired. To specify the way a marker is animated, select
					   one of the supported animations above.',
            'type' => 'radio',
            'std' => 'pulsating_circle',
            'choices' => array(
				'pulsating_circle' => 'Pulsating circle',
				'bouncing_marker' => 'Bouncing marker',
				'flushing_infobox' => 'Flushing infobox <sup>Use only when <strong>Show infobox</strong> is set to <strong>Yes</strong></sup>'				
            )
        ),						
        array(
            'id' => 'marker_icon',
            'title' => 'Marker image',
            'desc' => 'Upload a new marker image. You can always find the original marker at the plugin\'s images directory.',
            'type' => 'file',
            'std' => ''
        ),
		array(
            'id' => 'marker_anchor_point_option',
            'title' => 'Set the anchor point',
            'desc' => 'Depending of the shape of the marker, you may not want the middle of the bottom edge to be used as the anchor point. 
					   In this situation, you need to specify the anchor point of the image. A point is defined with an X and Y value (in pixels). 
					   So if X is set to 10, that means the anchor point is 10 pixels to the right of the top left corner of the image. Setting Y to 10 means 
					   that the anchor is 10 pixels down from the top right corner of the image.',
            'type' => 'radio',
            'std' => 'disable',
            'choices' => array(
				'auto' => 'Auto detect <sup>*Detects the center of the image.</sup>',
				'manual' => 'Manualy <sup>*Enter the anchor point in the next two fields.</sup>',
				'disable' => 'Disable'				
            )
        ),
		array(
            'id' => 'marker_anchor_point',
            'title' => 'Marker anchor point',
            'desc' => 'Enter the anchor point of the Marker image. Seperate X and Y by comma. (e.g. 10,15)',
            'type' => 'text',
            'std' => ''
        ),	
		array(
            'id' => 'clusters_customizations_section',
            'title' => '<span class="section_sub_title">Clusters Customizations</span>',
            'desc' => '',
            'type' => 'custom',
        ),				
        array(
            'id' => 'big_cluster_icon',
            'title' => 'Large cluster image',
            'desc' => 'Upload a new large cluster image. You can always find the original marker at the plugin\'s images directory.',
            'type' => 'file',
            'std' => ''
        ),
        array(
            'id' => 'medium_cluster_icon',
            'title' => 'Medium cluster image',
            'desc' => 'Upload a new medium cluster image. You can always find the original marker at the plugin\'s images directory.',
            'type' => 'file',
            'std' => ''
        ),
        array(
            'id' => 'small_cluster_icon',
            'title' => 'Small cluster image',
            'desc' => 'Upload a new small cluster image. You can always find the original marker at the plugin\'s images directory.',
            'type' => 'file',
            'std' => ''
        ),
		array(
            'id' => 'cluster_text_color',
            'title' => 'Clusters text color',
            'desc' => 'Change the text color of all your clusters.',
            'type' => 'color',
            'std' => ''
        ),
		array(
            'id' => 'zoom_customizations_section',
            'title' => '<span class="section_sub_title">Zoom Buttons Customizations</span>',
            'desc' => '',
            'type' => 'custom',
        ),				
        array(
            'id' => 'zoom_in_icon',
            'title' => 'Zoom-in image',
            'desc' => 'Upload a new zoom-in button image. You can always find the original marker at the plugin\'s images directory.',
            'type' => 'file',
            'std' => ''
        ),
		array(
            'id' => 'zoom_in_css',
            'title' => 'Zoom-in CSS',
            'desc' => 'Enter your custom CSS to customize the zoom-in button.<br /><strong>e.g.</strong> border:1px solid; ...',
            'type' => 'textarea',
            'std' => ''
        ),					
        array(
            'id' => 'zoom_out_icon',
            'title' => 'Zoom-out image',
            'desc' => 'Upload a new zoom-out button image. You can always find the original marker at the plugin\'s images directory.',
            'type' => 'file',
            'std' => ''
        ),		
		array(
            'id' => 'zoom_out_css',
            'title' => 'Zoom-out CSS',
            'desc' => 'Enter your custom CSS to customize the zoom-out button.<br /><strong>e.g.</strong> border:1px solid; ...',
            'type' => 'textarea',
            'std' => ''
        ),	
				
    )
);
	
/**
 * map styles section */

$wpsf_settings[] = array(
    'section_id' => 'mapstylesettings',
    'section_title' => 'Default Map Style Settings',
    'section_description' => 'Styled maps allow you to customize the presentation of the standard Google base maps, changing the visual display of such elements as roads, parks, and built-up areas. The lovely styles below are provided by <a href="http://snazzymaps.com" target="_blank">Snazzy Maps</a>',
    'section_order' => 4,
    'fields' => array(
		array(
            'id' => 'map_style_alert_msg',
            'title' => '<span class="section_sub_title cspacing_notice">IMPORTANT!<br /> The Custom Map Style can not be operated without activating the option "Custom style" in "Map Settings => Initial style"</span>',
            'desc' => '',
            'type' => 'custom',
        ),		
        array(
            'id' => 'style_option',
            'title' => 'Style option', 
            'desc' => 'Select the style option of the map. If you select <strong>Progress map styles</strong>, choose on the available styles below.
			           If you select <strong>My custom style</strong>, enter your custom style code in the field <strong>Javascript Style Array</strong>.',
            'type' => 'radio',
            'std' => 'progress-map',
            'choices' => array(
				'progress-map' => 'Progress Map styles',
				'custom-style' => 'My custom style'
            )
        ),			
        array(
            'id' => 'map_style',
            'title' => 'Map style',
            'desc' => 'Select your map style.',
            'type' => 'radio',
            'std' => 'google-map',
            'choices' => $map_styles_array
        ),
		array(
            'id' => 'custom_style_name',
            'title' => 'Custom style name',
            'desc' => 'Enter your custom style name. Only available if your style option is "My custom style". If empty, the name "Custom style" is used.',
            'type' => 'text',
            'std' => 'Custom style',
        ),
		array(
            'id' => 'js_style_array',
            'title' => 'Javascript Style Array',
            'desc' => 'If you don\'t like any of the styles above, fell free to add your own style. Please include just the array definition. No extra variables or code.<br />
					  Make use of the following services to create your style:<br />
					  . <a href="http://gmaps-samples-v3.googlecode.com/svn/trunk/styledmaps/wizard/index.html" target="_blank">Styled Maps Wizard by Google</a><br />
					  . <a href="http://www.evoluted.net/thinktank/web-design/custom-google-maps-style-tool" target="_blank">Custom Google Maps Style Tool by Evoluted</a><br />
					  . <a href="http://software.stadtwerk.org/google_maps_colorizr/" target="_blank">Google Maps Colorizr by stadt werk</a><br />			  					  
			          You may also like to <a href="http://snazzymaps.com/submit" target="_blank">submit</a> your style for the world to see :)',
            'type' => 'textarea',
            'std' => '',
        ),	
	)
);

/**
 * Infowindow settings */

$wpsf_settings[] = array(
    'section_id' => 'infoboxsettings',
    'section_title' => 'Default Infobox Settings',
    'section_description' => 'The infobox, also called infowindow is an overlay that looks like a bubble and is often connected to a marker.',
    'section_order' => 5,
    'fields' => array(
		array(
            'id' => 'show_infobox',
            'title' => 'Show Infobox',
            'desc' => 'Show/Hide the Infobox.',
            'type' => 'radio',
            'std' => 'true',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),	
        array(
            'id' => 'infobox_type',
            'title' => 'Infobox type',
            'desc' => 'Select the Infobox type. <strong>Hover over the options to see an image of the infobox style</strong>.<br />
					  <span style="color:red"><strong style="font-size:14px;"><u>How to use your theme\'s "functions.php" file to override the infobox\'s Title & Content in order to display your custom title & content?</u></strong><br /><br />
					  
					  1) To override the infobox Title, make use of the hook <strong>"cspm_custom_infobox_title"</strong>. Open your theme\'s "functions.php" file and copy the following code:</span>
					  <pre class="cspm_pre">
&lt;?php
function cspm_custom_infobox_title($default_title, $post_id){

	$custom_title = "YOUR CUSTOM TITLE HERE. IT COULD BE A CUSTOM FIELD OR WHATEVER YOU LIKE TO PRINT.";
	
	return (!empty($custom_title)) ? $custom_title : $default_title;

}
add_filter("cspm_custom_infobox_title", "cspm_custom_infobox_title", 10, 2);
?&gt;</pre>
					  <span style="color:red; font-style:italic; font-size:12px;">
					  2) To override the infobox Content, make use of the hooks <strong>"cspm_custom_infobox_description"</strong> and <strong>"cspm_large_infobox_content"</strong>.<br /><br />
					  <strong>[cspm_custom_infobox_description]</strong> is reserved for the <u>"Infobox 1"</u>.<br /><br />
					  <strong>[cspm_large_infobox_content]</strong> is reserved for the <u>Large infobox</u>.<br /><br />
					  Open your theme\'s "functions.php" file and copy the following code:</span>
					  </span>
					  <pre class="cspm_pre">
&lt;?php
function cspm_custom_infobox_description($default_description, $post_id){

	$custom_content = "YOUR CUSTOM CONTENT HERE.";
	
	return (!empty($custom_content)) ? $custom_content : $default_description;

}
add_filter("cspm_custom_infobox_description", "cspm_custom_infobox_description", 10, 2);
add_filter("cspm_large_infobox_content", "cspm_custom_infobox_description", 10, 2);
?&gt;</pre>',
            'type' => 'radio',
            'std' => 'rounded_bubble',
            'choices' => array(				
				'square_bubble' => 'Square bubble',
				'rounded_bubble' => 'Rounded bubble',				
				'cspm_type1' => 'Infobox 1',
				'cspm_type2' => 'Infobox 2',
				'cspm_type3' => 'Infobox 3',
				'cspm_type4' => 'Infobox 4',
				'cspm_type5' => 'Large Infobox',												
            )
        ),		
		array(
            'id' => 'infobox_display_event',
            'title' => 'Display event',
            'desc' => 'Select from the options above when the infoboxes should be displayed in the map.',
            'type' => 'radio',
            'std' => 'onload',
            'choices' => array(
				'onload' => 'On map load <sup>(Load all infoboxes)</sup>',
				'onclick' => 'On marker click',
				'onhover' => 'On marker hover'
            )
        ),
		array(
            'id' => 'remove_infobox_on_mouseout',
            'title' => 'Remove Infobox on mouseout?',
            'desc' => 'Choose whether you want to remove the infobox when the mouse leaves the marker or not. <span style="color:red">This option is operational only when the <strong>Display event</strong> 
					  equals to <strong>On marker click</strong> or <strong>On marker hover</strong></span>',
            'type' => 'radio',
            'std' => 'false',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),
		array(
            'id' => 'infobox_external_link',
            'title' => 'Post URL',
            'desc' => 'Choose an option to open the single post page. You can also disable links in the infoboxes by selecting the option "Disable"',
            'type' => 'radio',
            'std' => 'same_window',
            'choices' => array(
				'new_window' => 'Open in a new window',
				'same_window' => 'Open in the same window',
				'disable' => 'Disable'
            )
        ),		
	)
);
	
/**
 * Troubleshooting & Configs*/

$wpsf_settings[] = array(
    'section_id' => 'troubleshooting',
    'section_title' => 'Troubleshooting & Configs <sup class="cspm_plus_tag">Plus+</sup>',
    'section_description' => '',
    'section_order' => 15,
    'fields' => array(
		array(
            'id' => 'regenerate_markers_link',
            'title' => 'Regenerate markers',
            'desc' => '<span class="cspm_blink_text">Regenerating markers is under process...<br /></span>
					   This option is for regenerating all your markers. In most cases, <strong>you wont need to use this option at all</strong> because markers 
					   are automatically created and each time you add or edit a post, the marker(s) related to this post will be regenerated automatically. 
					   Use this options <strong>only when need to</strong>.
					   <span style="color:red">This may take a while when you have too many markers (500+) to regenerate. Please be patient.</span>',
            'type' => 'link',
			'std' => '#'
        ),
		array(
            'id' => 'loading_scripts_section',
            'title' => '<span class="section_sub_title">Speed Optimization</span>',
            'desc' => '',
            'type' => 'custom',
        ),
		array(
            'id' => 'combine_files',
            'title' => 'Loading scripts & styles',
            'desc' => 'By default, the plugin will load the minified version of all JS & CSS files but you can reduce the number of requests your site has to make to the server in order to 
					   render the map by choosing the option "Combine files".<br />
					   <strong><u>Hint:</u></strong> <strong>Modern browsers are able to download multiple files at a time in parallel. So that means that it might be more efficient and faster for your browser to download several smaller files all at once, then one large file. The results will vary from site to site so you will have to test this for yourself.</strong><br />
					   <a href="http://blog.wp-rocket.me/5-speed-optimization-myths/" target="_blank">A usful artice about Speed Opitimization</a>',
            'type' => 'radio',
            'std' => 'seperate_minify',
			'choices' => array(
				'combine' => 'Combine files',
				'seperate' => 'Seperate files (Debugging versions)',
				'seperate_minify' => 'Seperate files (Minified versions) <sup><strong>Recommended</strong></sup> <sup class="cspm_new_tag">NEW</sup>'
			)
        ),
		
		/**
		 * Disabling Stylesheets & Scripts
		 * @since 2.8.2 */
		 
		array(
            'id' => 'disabling_scripts_section',
            'title' => '<span class="section_sub_title">Disabling Stylesheets & Scripts <sup class="cspm_new_tag" style="margin:0 5px 0 -2px;">NEW</sup></span>',
            'desc' => '',
            'type' => 'custom',
        ),
		array(
            'id' => 'disabling_scripts_msg',
            'title' => '<span class="section_sub_title cspacing_info">Progress Map uses some files that may conflict with your theme. This section will allow you to disable files that are suspected of creating conflicts in your website.</span>',
            'desc' => '',
            'type' => 'custom',
        ),
		array(
            'id' => 'remove_bootstrap',
            'title' => 'Bootstrap (CSS File) <sup class="cspm_new_tag" style="margin:0 5px 0 -2px;">NEW</sup>',
            'desc' => 'If your theme uses bootstrap v3+, you can disable the one used by this plguin.
					  <br /><span style="color:red;">Important Note: This option will not take action if you select the option <strong>"Loading scripts & styles / Combine files"</strong>! Use the option <strong>"Seperate files (Debugging versions)"</strong> or <strong>"Seperate files (Minified versions)"</strong> instead!</span>',
            'type' => 'radio',
            'std' => 'enable',
			'choices' => array(
				'enable' => 'Enable',
				'disable' => 'Disable'
			)
        ),
		array(
            'id' => 'remove_google_fonts',
            'title' => 'Google Fonts "Source Sans Pro" (CSS File) <sup class="cspm_new_tag" style="margin:0 5px 0 -2px;">NEW</sup>',
            'desc' => 'If your theme uses or load this font, you can disable the one used by this plguin.',
            'type' => 'radio',
            'std' => 'enable',
			'choices' => array(
				'enable' => 'Enable',
				'disable' => 'Disable'
			)
        ),
		array(
            'id' => 'remove_gmaps_api',
            'title' => 'Google Maps API (JS File) <sup class="cspm_new_tag" style="margin:0 5px 0 -2px;">NEW</sup><br /> <span style="color:red;">Not recommended unless ... Read the description!</span>',
            'desc' => 'If your theme loads its own GMaps API, you can disbale the one used by this plugin.
					   <br /><span style="color:red;"><strong>Important Notes: <br />
					   1) Your theme MUST load the version 3 (v3) of Google Maps Javascript API!<br /><br />
					   2) Your theme MUST load the following libraries:<br />
					   - Geometry Library.<br />
					   - Places Libary.<br />
					   The list of libraries may change in the future!
					   <br /><br />
					   3) If you remove our GMaps API, you\'ll not be able to use the following features:<br />
					   - Changing the language of the map.<br />
					   - Adding an API Key to your map.<br />
					   - Address search.<br />
					   The list of parameters may change in the future!<br /><br />
					   Unless your theme doesn\'t provide all of this, we highly recommend you to ENABLE our GMaps API!
					   </strong></span>',
            'type' => 'checkboxes',
            'std' => '',
			'choices' => array(
				'disable_frontend' => 'Disable on Frontend',
				'disable_backend' => 'Disable on Backend',
			)
        ),		
		array(
            'id' => 'custom_css_section',
            'title' => '<span class="section_sub_title">Custom CSS</span>',
            'desc' => '',
            'type' => 'custom',
        ),
		array(
            'id' => 'custom_css',
            'title' => 'Custom CSS',
            'desc' => 'If you want to make any customization to the plugin style, you don\'t want to update the style files 
					   directly because you may lose everything you changed when you\'ll upgrade the plugin to a newer release. 
					   Use this field to add your extra custom CSS.<br /> 
					   Example: <strong>div.a_class_name{ border:1px solid #ededed; }</strong>',
            'type' => 'textarea',
            'std' => '',
        ),							
	)
);


/**
 * Hidden Settings */
 
$wpsf_settings[] = array(
    'section_id' => 'hiddensettings',
    'section_title' => '',
    'section_description' => '',
    'section_order' => 100,
    'fields' => array(	
		array(
            'id' => 'json_markers_method',
            'title' => '',
            'desc' => '',
            'type' => 'hidden',
            'std' => 'false',
        ),
	)
);
