<?php

if(!class_exists('CspmLightMap')){
	
	class CspmLightMap{
		
		private static $_this;	
		
		public $plugin_settings = array();

		function __construct(){
			
			if (!class_exists('CspmMainMap'))
				return; 
		
			self::$_this = $this;       

			$CspmMainMap = CspmMainMap::this();
			 
			$this->plugin_settings = $CspmMainMap->plugin_settings;

		}
		
	
		static function this(){
			
			return self::$_this;
			
		}
		
		
		function cspm_hooks(){
			
			if(!is_admin()){
				
				/**
				 * Add map's shortcode */
					
				add_shortcode('cspm_light_map', array(&$this, 'cspm_light_map_shortcode'));
				add_shortcode('codespacing_light_map', array(&$this, 'cspm_light_map_shortcode')); //@deprecated since 3.0
				
			}

		}
		
		
		/**
		 * This will load the styles needed by our shortcodes based on its settings
		 *
		 * @since 3.0
		 */
		function cspm_enqueue_styles(){
			
			do_action('cspm_before_enqueue_light_map_style');
							
			/**
			 * Font Style */
			
			if($this->plugin_settings['remove_google_fonts'] == 'enable')  	
				wp_enqueue_style('cspm_font');
			
			if($this->plugin_settings['combine_files'] == "combine"){
					
				wp_enqueue_style('cspm_combined_styles');
				
			}else{

				/**
				 * Infobox loader */
				 
				wp_enqueue_style('cspm_loading_css');				

				/** 
				 * Progress Map styles */
				
				wp_enqueue_style('cspm_map_css');
				
			}
			
			do_action('cspm_after_enqueue_light_map_style');
		
			/**
			 * Add custom header script */
			
			wp_add_inline_style('cspm_map_css', $this->cspm_custom_map_style());
			
		}

		
		/** 
		 * This will build the custom CSS needed for this map
		 *
		 * @since 3.0
		 */
		function cspm_custom_map_style(){
				
			$custom_map_style = '';
			
			/**
			 * Zoom-In & Zoom-out CSS Style
			 * This uses the default settings in the plugin settings area! */

			if($this->plugin_settings['zoomControl'] == 'true' && $this->plugin_settings['zoomControlType'] == 'customize'){
						
				$custom_map_style .= 'div[class^=codespacing_light_map_zoom_in_]{'.$this->plugin_settings['zoom_in_css'].'}';
				$custom_map_style .= 'div[class^=codespacing_light_map_zoom_out_]{'.$this->plugin_settings['zoom_out_css'].'}';
				
			}
			
			$custom_map_style .= $this->plugin_settings['custom_css'];
			
			return $custom_map_style;
				
		}
		
		
		/**
		 * This will load the scripts needed by our shortcodes based on its settings
		 *
		 * @since 3.0
		 */
		function cspm_enqueue_scripts(){
			
			/**
			 * jQuery */
			 
			wp_enqueue_script('jquery');				 			

			do_action('cspm_before_enqueue_light_map_script');
			
			/**
			 * GMaps API */
			
			if(!in_array('disable_frontend', $this->plugin_settings['remove_gmaps_api']))				 
				wp_enqueue_script('cspm_google_maps_api');
			
			if($this->plugin_settings['combine_files'] == "combine"){
			
				wp_enqueue_script('cspm_combined_scripts');	
			
			}else{
				
				/**
				 * GMap3 jQuery Plugin */
				 
				wp_enqueue_script('cspm_gmap3_js');
					
				/**
				 * Live Query */
				 
				wp_enqueue_script('cspm_livequery_js');

				/**
				 * Progress Map Script */
				 
				wp_enqueue_script('cspm_progress_map_js');
					
			}

			do_action('cspm_after_enqueue_light_map_script');
			
		}
		
			
	    /**
		 * Display a light map that show's one or more locations
		 * Note: No carousel used
		 *
		 * @since 2.0 
		 * @updated 2.8.5
		 * @updated 2.8.6
		 */
		function cspm_light_map_shortcode($atts){

			if (!class_exists('CspmMainMap'))
				return; 
				
			$CspmMainMap = CspmMainMap::this();

			extract( shortcode_atts( array(
			
				'post_ids' => '',
				'center_at' => '',
				'height' => '300px',
				'width' => '100%',
				'zoom' => $this->plugin_settings['map_zoom'],
				'show_overlay' => 'yes',
				'show_secondary' => 'yes',
				'map_style' => '',
				'initial_map_style' => esc_attr($this->plugin_settings['initial_map_style']),
				'infobox_type' => $this->plugin_settings['infobox_type'],
				'hide_empty' => 'yes', //@since 2.7.1
				
				/**
				 * [@window_resize] Whether to resize the map on window resize or not.
				 * @since 2.8.5 */
				
				'window_resize' => 'yes', // Possible values, "yes" & "no"
				
				 /**
				  * [@link_target] Possible value, "same_window", "new_window" & "disable"
				  * @since 2.8.6 */
				  
				'infobox_link_target' => esc_attr($this->plugin_settings['infobox_external_link']), 
			  				
			), $atts, 'cspm_light_map' ) ); 
			
			$post_ids = esc_attr($post_ids);
			
			$post_ids_array = array();
			
			/**
			 * Get the given post id */
			 
			if(!empty($post_ids)){
				
				$post_ids_array = explode(',', $post_ids);			
			
			/**
			 * Get the current post id */
			 
			}else{
			
				global $post;
				
				$post_ids_array[] = $post->ID;
				
			}
			
			$map_id = implode('', $post_ids_array);
			
			/**
			 * Get the center point */
				 
			$default_map_center = explode(',', $this->plugin_settings['map_center']);
 
			if(!empty($center_at)){
				
				$center_point = esc_attr($center_at);
				
				/**
				 * If the center point is Lat&Lng coordinates */
				 
				if(strpos($center_point, ',') !== false){
						
					$center_latlng = explode(',', str_replace(' ', '', $center_point));
					
					/**
					 * Get lat and lng data */
					 
					$centerLat = isset($center_latlng[0]) ? $center_latlng[0] : '';
					$centerLng = isset($center_latlng[1]) ? $center_latlng[1] : '';
				
				/**
				 * If the center point is a post id */
				 
				}else{
						
					/**
					 * Get lat and lng data */
					 
					$post_lat = get_post_meta($center_point, CSPM_LATITUDE_FIELD, true);
					$post_lng = get_post_meta($center_point, CSPM_LONGITUDE_FIELD, true);
					
					$centerLat = (!empty($post_lat)) ? $post_lat : $default_map_center[0];
					$centerLng = (!empty($post_lng)) ? $post_lng : $default_map_center[1];
			
				}
				
			}else{
					
				/**
				 * Get lat and lng data */
				
				$post_lat = get_post_meta($post_ids_array[0], CSPM_LATITUDE_FIELD, true);
				$post_lng = get_post_meta($post_ids_array[0], CSPM_LONGITUDE_FIELD, true);
				
				$centerLat = (!empty($post_lat)) ? $post_lat : $default_map_center[0];
				$centerLng = (!empty($post_lng)) ? $post_lng : $default_map_center[1];
			
			}
			
			$latLng = '"'.$centerLat.','.$centerLng.'"';										
									
			/**
			 * Map Styling */
			 
			$this_map_style = empty($map_style) ? $this->plugin_settings['map_style'] : esc_attr($map_style);
							
			$map_styles = array();

			if($this->plugin_settings['style_option'] == 'progress-map'){
					
				/**
				 * Include the map styles array	*/
		
				if(file_exists($CspmMainMap->map_styles_file))
					$map_styles = include($CspmMainMap->map_styles_file);
						
			}elseif($this->plugin_settings['style_option'] == 'custom-style' && !empty($this->plugin_settings['js_style_array'])){
				
				$this_map_style = 'custom-style';
				$map_styles = array('custom-style' => array('style' => $this->plugin_settings['js_style_array']));
				
			}
			
			?>
			
			<script type="text/javascript">
			
				jQuery(document).ready(function($){ 
					
					/**
					 * init plugin map */
					 
					var plugin_map_placeholder = 'div#codespacing_progress_map_light_<?php echo $map_id; ?>';
					var plugin_map = $(plugin_map_placeholder);
					
					/**
					 * Load Map options */
					 
					var map_options = cspm_load_map_options('initial', true, <?php echo $latLng; ?>, <?php echo esc_attr($zoom); ?>);
					
					/**
					 * Activate the new google map visual */
					 
					google.maps.visualRefresh = true;
					
					/**
					 * The initial map style */
					 
					var initial_map_style = "<?php echo $initial_map_style; ?>";
					
					/**
					 * Enhance the map option with the map types id of the style */
					 
					<?php if(count($map_styles) > 0 && $this_map_style != 'google-map' && isset($map_styles[$this_map_style])){ ?> 
											
						/**
						 * The initial style */
						 
						var map_type_id = cspm_initial_map_style(initial_map_style, true);
						
						/**
						 * Map type control option */
						 
						var mapTypeControlOptions = {mapTypeControlOptions: {
														position: google.maps.ControlPosition.TOP_RIGHT,
														mapTypeIds: [google.maps.MapTypeId.ROADMAP,
																	 google.maps.MapTypeId.SATELLITE,
																	 google.maps.MapTypeId.TERRAIN,
																	 google.maps.MapTypeId.HYBRID,
																	 "custom_style"]				
													}};
													
						var map_options = $.extend({}, map_options, map_type_id, mapTypeControlOptions);
						
					<?php }else{ ?>
											
						/**
						 * The initial style */
						 
						var map_type_id = cspm_initial_map_style(initial_map_style, false);
													
						var map_options = $.extend({}, map_options, map_type_id);
						
					<?php } ?>
					
					<?php $show_infobox = (esc_attr($show_overlay) == 'yes' && $this->plugin_settings['show_infobox'] == 'true') ? 'true' : 'false'; ?>
					
					var json_markers_data = [];
					
					var map_id = 'light_<?php echo $map_id ?>';
					
					var infobox_div = $('div.cspm_infobox_container[data-map-id='+map_id+']');				
					
					var show_infobox = '<?php echo $show_infobox; ?>';
					var infobox_type = '<?php echo esc_attr($infobox_type); ?>';
					var infobox_display_event = '<?php echo $this->plugin_settings['infobox_display_event']; ?>';
					
					_CSPM_MAP_RESIZED[map_id] = 0;
					
					post_ids_and_categories[map_id] = {};
					post_lat_lng_coords[map_id] = {};
					post_ids_and_child_status[map_id] = {};
					
					cspm_bubbles[map_id] = [];
					cspm_child_markers[map_id] = [];
					cspm_requests[map_id] = [];
					
					<?php 
					
					/**
					 * [is_latlng_empty] Whether the post has a LatLng coordinates. Usefull to use with [hide_empty] to hide the map when the user wants to.
					 * @since 2.7.1 */
					 
					$is_latlng_empty = true;
										
					/**
					 * Count items */
					 
					$count_post = count($post_ids_array);
					
					if($count_post > 0){
			
						$i = 1;
						
						$secondary_latlng_array = array();
						
						/**
						 * Loop throught items */
						 
						foreach($post_ids_array as $post_id){
							
							/**
							 * Get lat and lng data */
							 
							$lat = get_post_meta($post_id, CSPM_LATITUDE_FIELD, true);
							$lng = get_post_meta($post_id, CSPM_LONGITUDE_FIELD, true);
						
							/**
							 * Show items only if lat and lng are not empty */
							 
							if(!empty($lat) && !empty($lng)){
								
								/**
								 * Set [is_latlng_empty] to "false" to make sure that the map cannot be empty 
								 * @since 2.7.1 */
								 
								$is_latlng_empty = false;
								
								$marker_img_array = apply_filters('cspm_bubble_img', wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'cspm-marker-thumbnail' ), $post_id);
								$marker_img = isset($marker_img_array[0]) ? $marker_img_array[0] : '';
	
								/**
								 * 1. Get marker category */
									 
								$post_categories = array();
								$implode_post_categories = '';
									
								/**
								 * 2. Get marker image */
								 
								$marker_img_by_cat = $CspmMainMap->cspm_get_marker_img(
									array(
										'post_id' => $post_id,
										'default_marker_icon' => $CspmMainMap->plugin_settings['marker_icon'],
										'is_single' => true,
									)
								);

								/**
								 * 3. Get marker image size for Retina display */
								 
								$marker_img_size = $CspmMainMap->cspm_get_image_size($CspmMainMap->cspm_get_image_path_from_url($marker_img_by_cat));
								
								$secondary_latlng = get_post_meta($post_id, CSPM_SECONDARY_LAT_LNG_FIELD, true);
									
								if(!empty($secondary_latlng) && esc_attr($show_secondary) == "yes")
									$secondary_latlng_array[$post_id] = array('latlng' => $secondary_latlng,
																			  'marker_img' => $marker_img,
																			  'post_categories' => $implode_post_categories,
																			  'map_id' => $map_id,
																			  'marker_img_size' => $marker_img_size
																			 ); ?>
																
								/**
								 * Create the pin object */
								 
								var marker_object = cspm_new_pin_object(<?php echo $i; ?>, '<?php echo $post_id; ?>', <?php echo $lat; ?>, <?php echo $lng; ?>, '<?php echo $implode_post_categories; ?>', map_id, '<?php echo $marker_img_by_cat; ?>', '<?php echo $marker_img_size; ?>', 'no');
								json_markers_data.push(marker_object); <?php 
								
								$i++;			
								
							}
									
						} 
						
						/**
						 * Secondary Lats & longs */						 
						
						if(count($secondary_latlng_array) > 0){
							
							$i = 0;
							
							foreach($secondary_latlng_array as $key => $single_latlng){
								
								$post_id = $key;
								$lats_lngs = explode(']', $single_latlng['latlng']);	
								
								foreach($lats_lngs as $single_coordinate){
								
									$strip_coordinates = str_replace(array('[', ']', ' '), '', $single_coordinate);
									$coordinates = explode(',', $strip_coordinates);
									
									if(isset($coordinates[0]) && isset($coordinates[1]) && !empty($coordinates[0]) && !empty($coordinates[1])){
										
										$lat = $coordinates[0];
										$lng = $coordinates[1]; ?>
																
										/**
										 * Create the child pin object */
										 
										var marker_object = cspm_new_pin_object(<?php echo $i; ?>, '<?php echo $post_id; ?>', <?php echo $lat; ?>, <?php echo $lng; ?>, '<?php echo $single_latlng['post_categories']; ?>', map_id, '<?php echo $marker_img_by_cat; ?>', '<?php echo $single_latlng['marker_img_size']; ?>', 'yes_<?php echo $i; ?>');
										json_markers_data.push(marker_object); <?php 
										
										$lat = $lng = '';
										
										$i++;
									
									} 
									
								}
								
							}
								
						}
						
					}
								
					/**
					 * Execute the map when there's or when there's no LatLng coordinates to display 
					 * & when the user chooses to display the map even when it's empty
					 * @since 2.7.1 */					 
					 
					if(!$is_latlng_empty || $is_latlng_empty && $hide_empty == 'no'){ ?>
					
						/**
						 * Create the map */
						 
						plugin_map.gmap3({	
								  
							map:{
								options: map_options,
								onces: {
									tilesloaded: function(){
										
										plugin_map.gmap3({ 
											marker:{
												values: json_markers_data,																			
											}
										});
										
										<?php

										/**
										 * Show the Zoom control after the map load */
										 
										if($this->plugin_settings['zoomControl'] == 'true' && $this->plugin_settings['zoomControlType'] == 'customize'){ ?>
										
											$('div.codespacing_light_map_zoom_in_<?php echo $map_id ?>, div.codespacing_light_map_zoom_out_<?php echo $map_id ?>').show(); <?php 
										
										}
										
										?>
										
										if(json_markers_data.length > 0 && show_infobox == 'true'){
											setTimeout(function(){
												cspm_draw_multiple_infoboxes(plugin_map, map_id, '<?php echo $CspmMainMap->cspm_infobox(esc_attr($infobox_type), 'multiple', 'light_'.$map_id, 'false', $infobox_link_target); ?>', infobox_type, 'no');
											}, 1000);																
										}
										

									}
									
								},
								events:{
									zoom_changed: function(){
										setTimeout(function(){
											if(json_markers_data.length > 0 && show_infobox == 'true'){								
												cspm_draw_multiple_infoboxes(plugin_map, map_id, '<?php echo $CspmMainMap->cspm_infobox(esc_attr($infobox_type), 'multiple', 'light_'.$map_id, 'false', $infobox_link_target); ?>', infobox_type, 'no');
											}
										}, 1000);
									},
									idle: function(){
										setTimeout(function(){
											if(json_markers_data.length > 0 && show_infobox == 'true' && !cspm_is_panorama_active(plugin_map)){								
												cspm_draw_multiple_infoboxes(plugin_map, map_id, '<?php echo $CspmMainMap->cspm_infobox(esc_attr($infobox_type), 'multiple', 'light_'.$map_id, 'false', $infobox_link_target); ?>', infobox_type, 'no');
											}
										}, 1000);
									},				
									bounds_changed: function(){
										if(json_markers_data.length > 0 && show_infobox == 'true'){
											cspm_draw_multiple_infoboxes(plugin_map, map_id, '<?php echo $CspmMainMap->cspm_infobox(esc_attr($infobox_type), 'multiple', 'light_'.$map_id, 'false', $infobox_link_target); ?>', infobox_type, 'no');														
										}
									},
									drag: function(){
										if(json_markers_data.length > 0 && show_infobox == 'true'){							
											cspm_draw_multiple_infoboxes(plugin_map, map_id, '<?php echo $CspmMainMap->cspm_infobox(esc_attr($infobox_type), 'multiple', 'light_'.$map_id, 'false', $infobox_link_target); ?>', infobox_type, 'no');														
										}
									}
								}						
							},
							
							<?php if(count($map_styles) > 0 && $this_map_style != 'google-map' && isset($map_styles[$this_map_style])){ ?> 
								<?php $style_title = isset($map_styles[$this_map_style]['title']) ? $map_styles[$this_map_style]['title'] : $this->plugin_settings['custom_style_name']; ?>
								styledmaptype:{
									id: "custom_style",
									options:{
										name: "<?php echo $style_title; ?>",
										alt: "Show <?php echo $style_title; ?>"
									},
									styles: <?php echo $map_styles[$this_map_style]['style']; ?>
								}
							<?php } ?>
							
						});								
							
						/**
						 * Call zoom-in function */
						 
						cspm_zoom_in('<?php echo $map_id; ?>', $('div.codespacing_light_map_zoom_in_<?php echo $map_id; ?>'), plugin_map);
					
						/**
						 * Call zoom-out function */
						 
						cspm_zoom_out('<?php echo $map_id; ?>', $('div.codespacing_light_map_zoom_out_<?php echo $map_id; ?>'), plugin_map);
						
						/**
						 * Hide/Show UI Controls depending on the streetview visibility */
						
						var mapObject = plugin_map.gmap3('get');
						
						if(typeof mapObject.getStreetView === 'function'){
							
							var streetView = mapObject.getStreetView();
						
							google.maps.event.addListener(streetView, "visible_changed", function(){
								
								if(this.getVisible()){
									
									/**
									 * Hide the Zoom control before the map load	 */
									 
									<?php if($this->plugin_settings['zoomControl'] == 'true' && $this->plugin_settings['zoomControlType'] == 'customize'){ ?>
										$('div.codespacing_light_map_zoom_in_<?php echo $map_id; ?>, div.codespacing_light_map_zoom_out_<?php echo $map_id; ?>').hide();
									<?php } ?>
									
									$('div.cspm_infobox_container[data-map-id='+map_id+']').hide();
									
								}else{
									
									/**
									 * Show the Zoom cotrol after the map load */
									 
									<?php if($this->plugin_settings['zoomControl'] == 'true' && $this->plugin_settings['zoomControlType'] == 'customize'){ ?>
										$('div.codespacing_light_map_zoom_in_<?php echo $map_id; ?>, div.codespacing_light_map_zoom_out_<?php echo $map_id; ?>').show();
									<?php } ?>
									
									if(json_markers_data.length > 0 && show_infobox == 'true'){
										setTimeout(function(){
											cspm_draw_multiple_infoboxes(plugin_map, map_id, '<?php echo $CspmMainMap->cspm_infobox(esc_attr($infobox_type), 'multiple', 'light_'.$map_id, 'false', $infobox_link_target); ?>', infobox_type, 'no');														
										}, 200);
									}
								}
									
							});
							
						}
						
						<?php
						
						/**
						 * Center the Map on screen resize */
						 
						 if(esc_attr($window_resize) == 'yes' && !empty($centerLat) && !empty($centerLng)){ ?>
						
							/**
							 * Store the window width */
							 
							var windowWidth = $(window).width();
		
							$(window).resize(function(){
								
								/**
								 * Check window width has actually changed and it's not just iOS triggering a resize event on scroll */
								 
								if ($(window).width() != windowWidth) {
						
									/**
									 * Update the window width for next time */
									 
									windowWidth = $(window).width();
			
									setTimeout(function(){
										
										var latLng = new google.maps.LatLng (<?php echo $centerLat; ?>, <?php echo $centerLng; ?>);							
										
										var map = plugin_map.gmap3("get");														
										
										if(typeof map.panTo === 'function')
											map.panTo(latLng);
											
										if(typeof map.setCenter === 'function')
											map.setCenter(latLng);
												
									}, 500);
									
								}
								
							});
							
						<?php } ?>
						
						<?php
						
						/**
						 * Resolve a problem of Google Maps & jQuery Tabs */
						
						 if(!empty($centerLat) && !empty($centerLng)){ ?>
						 
							$(plugin_map_placeholder+':visible').livequery(function(){
								if(_CSPM_MAP_RESIZED[map_id] <= 1){ /* 0 is for the first loading, 1 is when the user clicks the map tab */
									cspm_center_map_at_point(plugin_map, '<?php echo $map_id ?>', <?php echo $centerLat; ?>, <?php echo $centerLng; ?>, 'resize');
									_CSPM_MAP_RESIZED[map_id]++;
								}
								cspm_zoom_in_and_out(plugin_map);							
							});
						
						<?php } ?>
					
					<?php } /* End if($is_latlng_empty ...) */ ?>
					
				});
			
			</script> 
			
			<?php
			
			$this->cspm_enqueue_styles();
			$this->cspm_enqueue_scripts();			
			
			/**
			 * Execute the map when there's or when there's no LatLng coordinates to display 
			 * & when the user chooses to display the map even when it's empty
			 * @since 2.7.1 */		
			 			 
			if(!$is_latlng_empty || $is_latlng_empty && $hide_empty == 'no'){										

				$output = '<div style="width:'.esc_attr($width).'; height:'.esc_attr($height).'; position:relative;">';
				
					/**
					 * Zoom Control */					 
								
					if($this->plugin_settings['zoomControl'] == 'true' && $this->plugin_settings['zoomControlType'] == 'customize'){
					
						$output .= '<div class="codespacing_zoom_container">';
							$output .= '<div class="codespacing_light_map_zoom_in_'.$map_id.' cspm_zoom_in_control cspm_border_shadow cspm_border_top_radius" title="'.__('Zoom in', 'cspm').'">';
								$output .= '<img src="'.$this->plugin_settings['zoom_in_icon'].'" />';
							$output .= '</div>';
							$output .= '<div class="codespacing_light_map_zoom_out_'.$map_id.' cspm_zoom_out_control cspm_border_shadow cspm_border_bottom_radius" title="'.__('Zoom out', 'cspm').'">';
								$output .= '<img src="'.$this->plugin_settings['zoom_out_icon'].'" />';
							$output .= '</div>';
						$output .= '</div>';
			
					}
					
					/**
					 * Map */
								
					$output .= '<div id="codespacing_progress_map_light_'.$map_id.'" style="width:100%; height:100%;"></div>';
				
				$output .= '</div>';
				
				return $output;
			
			}else return '';
			
		}
		
	}
	
}


if(class_exists('CspmLightMap')){
	$CspmLightMap = new CspmLightMap();
	$CspmLightMap->cspm_hooks();
}

