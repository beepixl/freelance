<?php

if(!class_exists('CspmFrontendForm')){
	
	class CspmFrontendForm{
		
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
					
				add_shortcode('cspm_frontend_form', array(&$this, 'cspm_frontend_form'));
				add_shortcode('progress_map_add_location_form', array(&$this, 'cspm_frontend_form')); //@deprecated since 3.0
				
			}

		}
		
		
		/**
		 * This will load the styles needed by our shortcodes based on its settings
		 *
		 * @since 3.0
		 */
		function cspm_enqueue_styles(){
			
			do_action('cspm_before_enqueue_frontent_form_style');
							
			/**
			 * Font Style */
			
			if($this->plugin_settings['remove_google_fonts'] == 'enable')  	
				wp_enqueue_style('cspm_font');
				
			/**
			 * Bootstrap */
			
			if($this->plugin_settings['remove_bootstrap'] == 'enable')
				wp_enqueue_style('cspm_bootstrap_css');
			
			do_action('cspm_after_enqueue_frontent_form_style');
			
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

			do_action('cspm_before_enqueue_frontent_form_script');
			
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

			do_action('cspm_after_enqueue_frontent_form_script');
			
		}
		
		
		/**
		 * Save locations from the frontend using the frontend form
		 *
		 * @since 2.6.3
		 */
		function cspm_save_frontend_location($atts = array()){
		
			$defaults = array(
				'post_id' => '',
				'post_type' => '',
				'latitude' => '',
				'longitude' => '',
			);
			
			extract(wp_parse_args($atts, $defaults));
				
			global $wpdb;
			
			$post_id = (isset($post_id) && !empty($post_id)) ? esc_attr($post_id) : $wpdb->insert_id;
			
			if(!empty($post_type) && !empty($post_id) && !empty($latitude) && !empty($longitude)){
				
				$markers_object = get_option('cspm_markers_array');
				$post_markers_object = array();
			
				update_post_meta($post_id, CSPM_LATITUDE_FIELD, $latitude);
				update_post_meta($post_id, CSPM_LONGITUDE_FIELD, $longitude);
					
				$post_taxonomy_terms = array();
				
				$post_taxonomies = get_object_taxonomies($post_type, 'names');	
				
				foreach($post_taxonomies as $taxonomy_name){
					
					$post_taxonomy_terms[$taxonomy_name] = wp_get_post_terms($post_id, $taxonomy_name, array("fields" => "ids"));
				
				}
	
				$post_markers_object = array('lat' => $latitude,
											 'lng' => $longitude,
											 'post_id' => $post_id,
											 'post_tax_terms' => $post_taxonomy_terms,
											 'is_child' => 'no',
											 'child_markers' => array()
											 );																	 
				
				$markers_object[$post_type]['post_id_'.$post_id] = $post_markers_object;
						
				update_option('cspm_markers_array', $markers_object);
							
			}
			
		}
		
			
		/**
		 * A form to add locations from the frontend
		 *
		 * @since 2.6.3 
		 * @updated 2.7 
		 */
		function cspm_frontend_form($atts){

			if (!class_exists('CspmMainMap'))
				return; 
				
			$CspmMainMap = CspmMainMap::this();

			extract( shortcode_atts( array(
			  
				'map_id' => 'frontend_form_map',
				'post_id' => '',
				'post_type' => '',			  
				'embed' => 'no',
				'center_at' => '',
				'height' => '400px',
				'width' => '100%',
				'zoom' => 5,
				'map_style' => '',
				'initial_map_style' => esc_attr($this->plugin_settings['initial_map_style']),

			), $atts, 'cspm_frontend_form' ) ); 
					
			$map_id = esc_attr($map_id);
			
			/**
			 * Default center point */
			 
			$centerLat = 51.53096;
			$centerLng = -0.121064;
			
			/**
			 * Get the center point */
			 
			if(!empty($center_at)){
				
				$center_point = esc_attr($center_at);
			
				if(strpos($center_point, ',') !== false){
						
					$center_latlng = explode(',', str_replace(' ', '', $center_point));
	
					/**
					 * Get lat and lng data */
					 
					$centerLat = isset($center_latlng[0]) ? $center_latlng[0] : 37.09024;
					$centerLng = isset($center_latlng[1]) ? $center_latlng[1] : -95.71289100000001;
					
				}			
				
			}
			
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
					 
					var plugin_map_placeholder = 'div#cspm_frontend_form_<?php echo $map_id; ?>';
					var plugin_map = $(plugin_map_placeholder);
					
					/**
					 * Activate the new google map visual */
					 
					google.maps.visualRefresh = true;
					
					var map_options = { center:[<?php echo $centerLat; ?>, <?php echo $centerLng; ?>],
										zoom: <?php echo esc_attr($zoom); ?>,
										scrollwheel: false,
										zoomControl: true,
										//panControl: true,
										mapTypeControl: true,
										streetViewControl: true,
									  };
					
					/**
					 * The initial map style */
					 
					var initial_map_style = "<?php echo $initial_map_style; ?>";
					
					<?php if(count($map_styles) > 0 && $this_map_style != 'google-map' && isset($map_styles[$this_map_style])){ ?> 
											
						/**
						 * The initial style */
						 
						var map_type_id = cspm_initial_map_style(initial_map_style, true);
													
						var map_options = $.extend({}, map_options, map_type_id);
						
					<?php }else{ ?>
											
						/**
						 * The initial style */
						 
						var map_type_id = cspm_initial_map_style(initial_map_style, false);
													
						var map_options = $.extend({}, map_options, map_type_id);
						
					<?php } ?>
					
					var map_id = 'frontend_form_<?php echo $map_id ?>';
					
					_CSPM_MAP_RESIZED[map_id] = 0;	
					
					/**
					 * Create the map */
					 
					plugin_map.gmap3({	
							  
						map:{
							options: map_options,						
						},
						
						/**
						 * Set the map style */
						 
						<?php if(count($map_styles) > 0 && $this_map_style != 'google-map' && isset($map_styles[$this_map_style])){ ?>
							
							styledmaptype:{
								id: "custom_style",
								styles: <?php echo $map_styles[$this_map_style]['style']; ?>
							}
							
						<?php } ?>
																
					});	
					
					/**
					 * Center the Map on screen resize */
					 
					<?php if(!empty($centerLat) && !empty($centerLng)){ ?>						
						
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
							if(_CSPM_MAP_RESIZED[map_id] <= 1){ // 0 is for the first loading, 1 is when the user clicks the map tab.
								cspm_center_map_at_point(plugin_map, '<?php echo $map_id ?>', <?php echo $centerLat; ?>, <?php echo $centerLng; ?>, 'resize');
								_CSPM_MAP_RESIZED[map_id]++;
							}
						});
					
					<?php } ?>

					/**
					 * Add support for the Autocomplete for the address field
					 * @since 2.8 */
					
					var input = document.getElementById('cspm_search_address');
					var autocomplete = new google.maps.places.Autocomplete(input);
											
				});
			
			</script> 
						
			<?php
			
			$this->cspm_enqueue_styles();
			$this->cspm_enqueue_scripts();			
			
			/**
			 * Save the location */
			 
			if(!empty($post_type) && !empty($post_id) && isset($_POST[CSPM_LATITUDE_FIELD], $_POST[CSPM_LONGITUDE_FIELD]))
				$this->cspm_save_frontend_location(array(
					'post_id' => esc_attr($post_id),
					'latitude' => esc_attr($_POST[CSPM_LATITUDE_FIELD]),
					'longitude' => esc_attr($_POST[CSPM_LONGITUDE_FIELD]),
					'post_type' => $post_type,
				));	
			
			/**
			 * If you want to save the coordinates using PHP, this is the code you need to use to save the location
			 
			if(class_exists('CspmFrontendForm') && isset($_POST[CSPM_LATITUDE_FIELD], $_POST[CSPM_LONGITUDE_FIELD])){			
				$CspmFrontendForm = CspmFrontendForm::this();
				$CspmFrontendForm->cspm_save_frontend_location(array(
					'post_id' => THE_POST_ID,
					'latitude' => esc_attr($_POST[CSPM_LATITUDE_FIELD]),
					'longitude' => esc_attr($_POST[CSPM_LONGITUDE_FIELD]),
					'post_type' => THE_POST_TYPE_NAME,
				));	
			}
			
			*/ 			 

			$output = '';
			
			/**
			 * Use this filter to add some custom code before the frontend form */
			 
			$output .= apply_filters('cspm_before_frontend_form', '');
			
			/**
			 * Use this filter to remove the opening tag */
			 
			if(esc_attr($embed) == 'no')
				$output .= apply_filters('cspm_open_frontend_form_tag', '<form action="" method="post" role="form" class="cspm_frontend_form">');
				
				$output .= '<div class="row">';

					/**
					 * Use this filter to add some custom code before the search field */
					 
					$output .= apply_filters('cspm_frontend_form_before_search_field', '');
					
					$output .= '<div class="form-group '.apply_filters('cspm_frontend_form_search_field_class', 'col-lg-10 col-md-10 col-sm-10 col-xs-12').'">
									<label for="cspm_search_address">'.esc_html__('Search', 'cspm').'</label>
									<input type="text" class="form-control" id="cspm_search_address" name="cspm_search_address" placeholder="'.esc_html__('Enter an address and search', 'cspm').'">
								</div>';
					
					$output .= '<div class="form-group '.apply_filters('cspm_frontend_form_search_btn_class', 'col-lg-2 col-md-2 col-sm-2 col-xs-12').'"> 
									<button type="button" id="cspm_search_btn" class="btn btn-primary" data-map-id="'.$map_id.'">'.esc_html__('Search', 'cspm').'</button>
								</div>';

					/**
					 * Use this filter to add some custom code after the search field */
					 
					$output .= apply_filters('cspm_frontend_form_after_search_field', '');
					
				$output .= '</div>';
				
				$output .= '<div class="row">';

					/**
					 * Use this filter to add some custom code before the map */
					 
					$output .= apply_filters('cspm_frontend_form_before_map', '');
					
					$output .= '<div id="cspm_frontend_form_'.$map_id.'" class="'.apply_filters('cspm_frontend_form_map_class', 'col-lg-12 col-md-12 col-sm-12 col-xs-12').'" style="height:'.$height.'; width:'.$width.'"></div>';

					/**
					 * Use this filter to add some custom code after the map */
					 
					$output .= apply_filters('cspm_frontend_form_after_map', '');
									
				$output .= '</div>';
				
				$output .= '<div class="row">';

					/**
					 * Use this filter to add some custom code before the latlng fields */
					 
					$output .= apply_filters('cspm_frontend_form_before_latlng', '');
									
					$output .= '<div class="form-group '.apply_filters('cspm_frontend_form_lat_field_class', 'col-lg-5 col-md-5 col-sm-12 col-xs-12').'">
									<label for="cspm_latitude">'.esc_html__('Latitude', 'cspm').'</label>
									<input type="text" class="form-control" id="cspm_latitude" name="'.CSPM_LATITUDE_FIELD.'">
								</div>';
				
					$output .= '<div class="form-group '.apply_filters('cspm_frontend_form_lng_field_class', 'col-lg-5 col-md-5 col-sm-12 col-xs-12').'">
									<label for="cspm_longitude">'.esc_html__('Longitude', 'cspm').'</label>
									<input type="text" class="form-control" id="cspm_longitude" name="'.CSPM_LONGITUDE_FIELD.'">
								</div>';
					
					$output .= '<div class="form-group '.apply_filters('cspm_frontend_form_pinpoint_btn_class', 'col-lg-2 col-md-2 col-sm-12 col-xs-12').'"> 
									<button type="button" id="cspm_get_pinpoint" class="btn btn-primary cspm_get_pinpoint" data-map-id="'.$map_id.'">'.esc_html__('Get Pinpoint', 'cspm').'</button>
								</div>';
								
					/**
					 * Use this filter to add some custom code after the latlng fields */
					 
					$output .= apply_filters('cspm_frontend_form_after_latlng', '');
					
				$output .= '</div>';
				
				if($embed == 'no'){
					
					$output .= '<div class="row">';
					
						/**
						 * Use this filter to add some custom code before the submit button */
						 
						$output .= apply_filters('cspm_frontend_form_before_submit_btn', '');
						
						$output .= '<div class="form-group '.apply_filters('cspm_frontend_form_submit_btn_class', 'col-lg-2 col-md-2 col-sm-12 col-xs-12').'"> 
										<button type="submit" id="cspm_add_location" class="btn btn-primary cspm_add_location" data-map-id="'.$map_id.'">'.esc_html__('Add Location', 'cspm').'</button>
									</div>';
						
						/**
						 * Use this filter to add some custom code after the submit button */
						 
						$output .= apply_filters('cspm_frontend_form_after_submit_btn', '');
														
					$output .= '</div>';
				
				}
				
			/**
			 * Use this filter to remove the closing tag of the form */
			 
			if(esc_attr($embed) == 'no')
				$output .= apply_filters('cspm_close_frontend_form_tag', '</form>');
			
			/**
			 * A filter to add some custom code after the frontend form */
			 
			$output .= apply_filters('cspm_after_frontend_form', '');
			
			return $output;
			
		}				
		
	}
	
}


if(class_exists('CspmFrontendForm')){
	$CspmFrontendForm = new CspmFrontendForm();
	$CspmFrontendForm->cspm_hooks();
}

