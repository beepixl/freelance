<?php

if(!class_exists('CspmContactMap')){
	
	class CspmContactMap{
		
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
					
				add_shortcode('cspm_contact_map', array(&$this, 'cspm_contact_map_shortcode'));
				add_shortcode('cs_static_marker_map', array(&$this, 'cspm_contact_map_shortcode')); //deprecated since 3.0
				
			}

		}
		
		
		/**
		 * This will load the styles needed by our shortcodes based on its settings
		 *
		 * @since 3.0
		 */
		function cspm_enqueue_styles(){
			
			do_action('cspm_before_enqueue_contact_map_style');
			
			/** 
			 * Progress Map styles */
			
			wp_enqueue_style('cspm_map_css');
			
			do_action('cspm_after_enqueue_contact_map_style');
		
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
						
				$custom_map_style .= 'div[class^=codespacing_contact_map_zoom_in_]{'.$this->plugin_settings['zoom_in_css'].'}';
				$custom_map_style .= 'div[class^=codespacing_contact_map_zoom_out_]{'.$this->plugin_settings['zoom_out_css'].'}';
				
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

			do_action('cspm_before_enqueue_contact_map_script');
			
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

			do_action('cspm_after_enqueue_contact_map_script');
			
		}
		
			
	    /**
		 * Display a light map with a static marker (Lat & Lng)
		 * No carousel used
		 *
		 * @since 2.8
		 * @updated 2.8.5				 
		 */
		function cspm_contact_map_shortcode($atts){

			if (!class_exists('CspmMainMap'))
				return; 
				
			$CspmMainMap = CspmMainMap::this();

			extract( shortcode_atts( array(
				
				'map_id' => 'contact_map',
				'latlng' => '',
				'height' => '300px',
				'width' => '100%',
				'zoom' => $this->plugin_settings['map_zoom'],
				'map_style' => '',
				'initial_map_style' => esc_attr($this->plugin_settings['initial_map_style']),
				'marker_img' => $this->plugin_settings['marker_icon'], //@since 2.8.4
				
				/**
				 * [@window_resize] Whether to resize the map on window resize or not.
				 * @since 2.8.5 */
				
				'window_resize' => 'yes', // Possible values, "yes" & "no"
				
				/** 
				 * [@content] Content to display in the InfoWindow. 
				 * @since 3.0 
				 
				'content' => '', */
			  
			), $atts, 'cspm_contact_map' ) ); 						

			$map_id = esc_attr($map_id);
			
			$center_latlng = explode(',', str_replace(' ', '', $latlng));
			
			$lat = $center_latlng[0];
			$lng = $center_latlng[1];
			
			$latLng = '"'.$lat.','.$lng.'"';										
									
			// Map Styling
			$this_map_style = empty($map_style) ? $this->plugin_settings['map_style'] : esc_attr($map_style);
							
			$map_styles = array();
			
			if($this->plugin_settings['style_option'] == 'progress-map'){
					
				// Include the map styles array	
		
				if(file_exists($CspmMainMap->map_styles_file))
					$map_styles = include($CspmMainMap->map_styles_file);
						
			}elseif($this->plugin_settings['style_option'] == 'custom-style' && !empty($this->plugin_settings['js_style_array'])){
				
				$this_map_style = 'custom-style';
				$map_styles = array('custom-style' => array('style' => $this->plugin_settings['js_style_array']));
				
			}

			?>
			
			<script>
			
				jQuery(document).ready(function($) { 
					
					/**
					 * init plugin map */
					 
					var plugin_map_placeholder = 'div#codespacing_progress_map_contact_map_<?php echo $map_id; ?>';
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
					
					var map_id = 'static_marker_<?php echo $map_id ?>';
					
					_CSPM_MAP_RESIZED[map_id] = 0;
					
					/**
					 * Create the map */
					 
					plugin_map.gmap3({	
							  
						map:{
							options: map_options,
							onces: {
								tilesloaded: function(){									
									
									var infowindow = null;
									
									<?php
									
									$marker_img_size = $CspmMainMap->cspm_get_image_size($CspmMainMap->cspm_get_image_path_from_url($marker_img));
									$explode_marker_img_size = explode('x', $marker_img_size);
											
									$marker_img_width = ($this->plugin_settings['retinaSupport'] == 'true') ? intval($explode_marker_img_size[0]/2) : intval($explode_marker_img_size[0]);
									$marker_img_height = ($this->plugin_settings['retinaSupport'] == 'true') ? intval($explode_marker_img_size[1]/2) : intval($explode_marker_img_size[1]);
											
									/**
									 * Display the infobox
									 * @since 3.0 
									 
									 if(!empty($content)){ ?>

										var infowindow = new google.maps.InfoWindow({
											 latLng: [<?php echo $lat; ?>,<?php echo $lng; ?>],
											 options:{
												content: '<?php echo esc_js(wp_json_encode($content)); ?>',
											 }
										});
									
									<?php }*/ ?>
									
									var marker_icon = new google.maps.MarkerImage("<?php echo esc_url($marker_img); ?>", null, null, null, new google.maps.Size(<?php echo $marker_img_width; ?>, <?php echo $marker_img_height; ?>));					

									plugin_map.gmap3({ 
										marker:{
											latLng: [<?php echo $lat; ?>,<?php echo $lng; ?>],
											options:{
												optimized: false,
												icon: marker_icon,
											},
											<?php /*if(!empty($content)){ ?>
											events:{
												click: function(marker, event, context){													
													if(infowindow){
												  		infowindow.open(plugin_map, marker);												  
													}
											  	}
											},callback: function(marker){
												setTimeout(function(){
													if(infowindow){
														infowindow.open(plugin_map, marker);												  
													}
												}, 1000);
											}
											<?php }*/ ?>
										}										
									});		
		
									<?php
									
									/**
									 * Show the Zoom control after the map load	*/
									 
									if($this->plugin_settings['zoomControl'] == 'true' && $this->plugin_settings['zoomControlType'] == 'customize'){ ?>
									
										jQuery('div.codespacing_contact_map_zoom_in_<?php echo $map_id ?>, div.codespacing_contact_map_zoom_out_<?php echo $map_id ?>').show(); <?php 
									
									}
									
									?>									
									
								}
								
							},
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
					 
					cspm_zoom_in('<?php echo $map_id; ?>', $('div.codespacing_contact_map_zoom_in_<?php echo $map_id; ?>'), plugin_map);
				
					/**
					 * Call zoom-out function */
					 
					cspm_zoom_out('<?php echo $map_id; ?>', $('div.codespacing_contact_map_zoom_out_<?php echo $map_id; ?>'), plugin_map);
					
					/**
					 * Center the Map on screen resize */
					 
					<?php if(esc_attr($window_resize) == 'yes' && !empty($lat) && !empty($lng)){ ?>
					
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
									
									var latLng = new google.maps.LatLng (<?php echo $lat; ?>, <?php echo $lng; ?>);							
									
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
					
					if(!empty($lat) && !empty($lng)){ ?>
					 
						jQuery(plugin_map_placeholder+':visible').livequery(function(){
							if(_CSPM_MAP_RESIZED[map_id] <= 1){ /* 0 is for the first loading, 1 is when the user clicks the map tab */
								cspm_center_map_at_point(plugin_map, '<?php echo $map_id ?>', <?php echo $lat; ?>, <?php echo $lng; ?>, 'resize');
								_CSPM_MAP_RESIZED[map_id]++;
							}
							cspm_zoom_in_and_out(plugin_map);							
						});
					
					<?php } ?>
					
				});
			
			</script> 
			
			<?php
			
			$this->cspm_enqueue_styles();
			$this->cspm_enqueue_scripts();			
			
			$output = '<div style="width:'.esc_attr($width).'; height:'.esc_attr($height).'; position:relative;">';
			
				/**
				 * Zoom Control */
							
				if($this->plugin_settings['zoomControl'] == 'true' && $this->plugin_settings['zoomControlType'] == 'customize'){
					
					$output .= '<div class="codespacing_zoom_container">';
						$output .= '<div class="codespacing_contact_map_zoom_in_'.$map_id.' cspm_zoom_in_control cspm_border_shadow cspm_border_top_radius" title="'.__('Zoom in', 'cspm').'">';
							$output .= '<img src="'.$this->plugin_settings['zoom_in_icon'].'" />';
						$output .= '</div>';
						$output .= '<div class="codespacing_contact_map_zoom_out_'.$map_id.' cspm_zoom_out_control cspm_border_shadow cspm_border_bottom_radius" title="'.__('Zoom out', 'cspm').'">';
							$output .= '<img src="'.$this->plugin_settings['zoom_out_icon'].'" />';
						$output .= '</div>';
					$output .= '</div>';
			
				}
				
				/**
				 * Map */
							
				$output .= '<div id="codespacing_progress_map_contact_map_'.$map_id.'" style="width:100%; height:100%;"></div>';
			
			$output .= '</div>';
			
			return $output;
			
		}
		
	}
	
}


if(class_exists('CspmContactMap')){
	$CspmContactMap = new CspmContactMap();
	$CspmContactMap->cspm_hooks();
}

