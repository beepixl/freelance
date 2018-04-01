<?php
/**
 * WordPress Settings Framework
 * 
 * @author Gilbert Pellegrom
 * @link https://github.com/gilbitron/WordPress-Settings-Framework
 * @version 1.4
 * @license MIT
 */

if( !class_exists('CsPm_WordPressSettingsFramework') ){
    /**
     * WordPressSettingsFramework class
     */
    class CsPm_WordPressSettingsFramework {
    
        /**
         * @access private
         * @var string 
         */
        private $option_group;
    	
		private $plugin_url;
		
		private $plugin_path;
		
		private $plugin_get_var = 'cs_progress_map_plugin';
		
		private static $_this;	
       
	    /**
         * Constructor
         * 
         * @param string path to settings file
         * @param string optional "option_group" override
         */
        function __construct( $settings_file, $option_group = '' )
        {
             
			self::$_this = $this;       
			           			
			if( !is_file( $settings_file ) ) return;
            require_once( $settings_file );
            
			$this->plugin_url = plugin_dir_url( __FILE__ );
			$this->plugin_path = plugin_dir_path( __FILE__ );
			
            $this->option_group = preg_replace("/[^a-z0-9]+/i", "", basename( $settings_file, '.php' ));
            if( $option_group ) $this->option_group = $option_group;
             
            add_action('admin_init', array(&$this, 'cspm_admin_init'));
            add_action('admin_notices', array(&$this, 'cspm_admin_notices'));
            add_action('admin_enqueue_scripts', array(&$this, 'cspm_admin_enqueue_scripts'));
			add_action('admin_print_footer_scripts', array(&$this, 'cspm_footer_scripts'));

        }
	
		static function this() 
		{			
			return self::$_this;		
		}
   	
        /**
         * Get the option group for this instance
         * 
         * @return string the "option_group"
         */
        function cspm_get_option_group()
        {
            return $this->option_group;
        }
        
        /**
         * Registers the internal WordPress settings
         */
        function cspm_admin_init()
    	{			
    		register_setting( $this->option_group, $this->option_group .'_settings', array(&$this, 'cspm_settings_validate') );
    		$this->cspm_process_settings();
    	}
        
        /**
         * Displays any errors from the WordPress settings API
         */
        function cspm_admin_notices()
    	{
        	if (is_admin()) {
				
				// Get out if the loaded page is not our plguin settings page
				if (isset($_GET['page']) && $_GET['page'] == $this->plugin_get_var )
					settings_errors();
					
			}
    	}
    	
    	/**
         * Enqueue scripts and styles
         */
    	function cspm_admin_enqueue_scripts()
    	{
            
			if (is_admin()) {
				
				/**
				 * For Meta box script, Livequery must be called first!
				 * This is also used by other extension of "Progress Map" like "List & Filter" */

				wp_register_script('cspacing_pm_livequery_js', $this->plugin_url .'js/min/jquery.livequery.min.js', array( 'jquery' ), false, true);
				 wp_enqueue_script('cspacing_pm_livequery_js');
				
				/**
				 * GMap API */
			
				$plugin_settings = cspm_wpsf_get_settings( $this->plugin_path .'settings/cspm.php' );		
					
				$remove_backend_gmaps_api = isset($plugin_settings['cspm_troubleshooting_remove_gmaps_api_disable_backend'])
													? $plugin_settings['cspm_troubleshooting_remove_gmaps_api_disable_backend'] : ''; //@since 2.8.5
						
				$this->remove_gmaps_api = array(
					$remove_backend_gmaps_api,
				); //@since 2.8.5
				
				if(!in_array('disable_backend', $this->remove_gmaps_api)){ 
					
					$api_key = isset($plugin_settings['cspm_mapsettings_api_key']) ? $plugin_settings['cspm_mapsettings_api_key'] : '';		
				
					$gmaps_api_key = (!empty($api_key)) ? '&key='.$api_key : '';
				 
					wp_register_script('codespacing_pm_maps_google', '//maps.google.com/maps/api/js?v=3.exp'.$gmaps_api_key.'&libraries=places', array( 'jquery' ), false, true);
					 wp_enqueue_script('codespacing_pm_maps_google');
					 
				}
	
				wp_register_script('codespacing_pm_gmap_js', $this->plugin_url .'settings/js/gmaps.min.js', array( 'jquery' ), false, true);
				 wp_enqueue_script('codespacing_pm_gmap_js');
				 			
				// Get out if the loaded page is not our plguin settings page
				if (isset($_GET['page']) && $_GET['page'] == $this->plugin_get_var ){
			
					// CSS
					
					wp_enqueue_style('farbtastic');
					wp_enqueue_style('thickbox');
		
					wp_register_style('cspacing_pm_admin_css', $this->plugin_url .'settings/css/admin_style.css');
					 wp_enqueue_style('cspacing_pm_admin_css');
					
					wp_register_style('cspacing_pm_multi_select_css', $this->plugin_url .'settings/css/multi-select.css');
					 wp_enqueue_style('cspacing_pm_multi_select_css');
					
					// JS
					 
					wp_enqueue_script('jquery');
					wp_enqueue_script('farbtastic');
					wp_enqueue_script('media-upload');
					wp_enqueue_script('thickbox');
					
					wp_register_script('cspacing_pm_jquery_cookie', $this->plugin_url .'settings/js/jquery_cookie.js', array( 'jquery' ), false, true);
					 wp_enqueue_script('cspacing_pm_jquery_cookie');
		
					wp_register_script('cspacing_pm_jquery_validate', $this->plugin_url .'settings/js/jquery.validate.min.js', array( 'jquery' ), false, true);
					 wp_enqueue_script('cspacing_pm_jquery_validate');
					 
					wp_register_script('cspacing_pm_qtip_js', $this->plugin_url .'settings/js/jquery.qtip-1.0.0-rc3.min.js', array( 'jquery' ), false, true);
					 wp_enqueue_script('cspacing_pm_qtip_js');
					
					wp_register_script('cspacing_pm_cufon_font', $this->plugin_url.'settings/js/cufon/cufon-yui.js', array(), false, true);
					 wp_enqueue_script('cspacing_pm_cufon_font');
					 
					wp_register_script('cspacing_pm_cufon_Linux_Biolinum', $this->plugin_url.'settings/js/cufon/Linux_Biolinum_400.font.js', array(), false, true);
					 wp_enqueue_script('cspacing_pm_cufon_Linux_Biolinum');
					
					wp_register_script('cspacing_pm_multi_select_js', $this->plugin_url .'settings/js/jquery.multi-select.js', array( 'jquery' ), false, true);
 					 wp_enqueue_script('cspacing_pm_multi_select_js');
					 
					wp_register_script('cspacing_pm_quicksearch_js', $this->plugin_url .'settings/js/jquery.quicksearch.js', array( 'jquery' ), false, true);
 					 wp_enqueue_script('cspacing_pm_quicksearch_js');
					 
					wp_register_script('cspacing_pm_admin_script', $this->plugin_url .'settings/js/admin_script.js', array( 'jquery' ), false, true);
					 wp_enqueue_script('cspacing_pm_admin_script');
					
					global $wpsf_settings;
					$first_section = '';
					if(!empty($wpsf_settings)){
						usort($wpsf_settings, array(&$this, 'cspm_sort_array'));
						$first_section = $wpsf_settings[0]['section_id'];				
					}
					
					wp_localize_script('cspacing_pm_admin_script', 'cspacing_admin_vars', array(
						'ajax_url' => admin_url('admin-ajax.php'),
						'plugin_url' => $this->plugin_url,
						'first_section' => $first_section,
					));
					
				}
			 
			}
			
    	}
		
		function cspm_footer_scripts(){
			
			// Get out if the loaded page is not our plguin settings page
			if (isset($_GET['page']) && $_GET['page'] == $this->plugin_get_var ){
				
				echo "<script type='text/javascript'>\n";
				echo 'Cufon.now();';
				echo "\n</script>";
			
			}
			
			
		}
		
     	/**
         * Adds a filter for settings validation
         * 
         * @param array the un-validated settings
         * @return array the validated settings
         */
    	function cspm_settings_validate( $input )
    	{	    	
    		return apply_filters( $this->option_group .'_settings_validate', $input );
    	}
    	
    	/**
         * Displays the "section_description" if speicified in $wpsf_settings
         *
         * @param array callback args from add_settings_section()
         */
    	function cspm_section_intro( $args )
    	{
        	global $wpsf_settings;
        	if(!empty($wpsf_settings)){
        		foreach($wpsf_settings as $section){
                    if($section['section_id'] == $args['id']){
                        if(isset($section['section_description']) && $section['section_description']) echo '<p><strong>'. $section['section_description'] .'</strong></p>';
                        break;
                    }
        		}
            }
    	}
		
		/**
		 * Get all registred custom post types 
		 *
		 * @Used only for Progress Map plugin
		 * 
		 * @since 2.7
		 **/
		function cspm_get_registred_cpt($secondary = false){
	
			$post_types = get_post_types(array('_builtin' => false), 'objects');
 			
			$post_types_array = array('post' => __('Posts').' (post)', 'page' => __('Pages').' (page)');
			
			unset($post_types['cspm_post_type']);
			
			foreach($post_types as $post_type)
				$post_types_array[$post_type->name] = $post_type->labels->name.' ('.$post_type->name.')';
			
			$post_type_field = array(
				'id' => 'post_types',
				'title' => 'Post types',
				'desc' => 'Select the post types for which Progress Map should be available during post creation/edition. (Default: '.__('Posts').').',
				'type' => 'multi_select',
				'choices' => $post_types_array,
			);
			
			return $post_type_field;
						 	
		}
		
    	
		/**		 
		 * Get Taxonomies related to the given post or post type
		 *
		 * @Used only for Progress Map plugin
		 * 
		 * Since 2.0
		 **/
		function cspm_get_post_taxonomies(){
			
			$this_settings = cspm_wpsf_get_settings( $this->plugin_path .'settings/cspm.php' );		
			
			$post_type = isset($this_settings['cspm_generalsettings_post_type']) ? $this_settings['cspm_generalsettings_post_type'] : 'post';		
			
			$post_taxonomies = (array) get_object_taxonomies($post_type, 'objects');
			
			return $post_taxonomies;
			
		}
		
		/**		 
		 * Get Taxonomies list related to the given post or post type
		 *
		 * @Used only for Progress Map plugin
		 * 
		 * @since 2.0
		 * @updated 2.8.6
		 *
		 */
		function cspm_get_post_taxonomies_list($post_taxonomies){
			
			$taxonomies_fields = $taxonomy_options = array();
			
			foreach($post_taxonomies as $single_taxonomy){
				
				$tax_name = $single_taxonomy->name;
				$tax_label = $single_taxonomy->labels->name;	
				
				if($tax_name != "post_format")
					$taxonomy_options[$tax_name] = $tax_label;
				
			}
			
			if(count($taxonomy_options) > 0){
				
				reset($taxonomy_options);
				$first_taxonomy = key($taxonomy_options);
	
				$taxonomies_fields =  array( 'id' => 'marker_taxonomies',
											 'title' => 'Taxonomies',
											 'desc' => 'Choose the taxonomy that represent the categories of your posts. 
											 			Once you\'ve made your choice, <u>SAVE</u> your settings in order to update the <u>"Marker categories"</u>.',
											 'type' => 'radio',
											 'std' => $first_taxonomy,
											 'choices' => $taxonomy_options,
											 );			
				
			}
			
			return $taxonomies_fields;
				
		}
		
		/**		 
		 * Get Taxonomy terms related to the given post or post type (for Query settings)
		 *
		 * @Used only for Progress Map plugin
		 * 
		 * Since 2.0
		 **/
		function cspm_get_post_taxonomy_terms($post_taxonomies, $show_operator = true){
			
			$taxonomies_fields = array();
			
			foreach($post_taxonomies as $single_taxonomy){
				
				$tax_name = $single_taxonomy->name;
				$tax_label = $single_taxonomy->labels->name;							
				
				//if($tax_name != "post_tag"){
					
				$terms = (array) get_terms($tax_name, array("orderby" => "name", "hide_empty" => false));
				
				if(count($terms) > 0){			  
					$term_options = array();			  
					foreach($terms as $term){			   
						$term_options[$term->term_id] = $term->name;						
					}
					if($show_operator){
						$taxonomies_fields[] =  array(
													array(
														'id' => 'taxonomie_'.$tax_name.'',
														'title' => $tax_label.' ('.$tax_name.')',
														'desc' => '',
														'type' => 'multi_select',
														'std' => '',
														'choices' => $term_options,
													),
													array(
														'id' => ''.$tax_name.'_operator_param',
														'title' => '"Operator" parameter', 
														'desc' => 'Operator to test "'.$tax_label.'". Possible values are "IN", "NOT IN", "AND".<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Taxonomy_Parameters" target="_blank">More</a>',
														'type' => 'radio',
														'std' => 'IN',
														'choices' => array(
															'AND' => 'AND',
															'IN' => 'IN',
															'NOT IN' => 'NOT IN',
														)
													)
												);		
					}else{
						$taxonomies_fields[] =  array(
													array(
														'id' => 'faceted_search_taxonomy_'.$tax_name.'',
														'title' => $tax_label,
														'desc' => 'Select the terms to use in the faceted search.',
														'type' => 'multi_select',
														'std' => '',
														'choices' => $term_options,
													)
												);		
					}
					
				}	
				
				//}
				
			}
		
			return $taxonomies_fields;
			
		}
		
		/**		 
		 * Get Taxonomy terms related to the given post or post type (For marker categories)
		 *
		 * @Used only for Progress Map plugin
		 * 
		 * Since 2.0
		 **/
		function cspm_get_markers_taxonomy_terms($post_taxonomies, $faceted_search = false){
			
			$this_settings = cspm_wpsf_get_settings( $this->plugin_path .'settings/cspm.php' );		
			
			if(isset($this_settings['cspm_markercategoriessettings_marker_taxonomies'])){
				
				$marker_taxonomy = $this_settings['cspm_markercategoriessettings_marker_taxonomies'];		
				
				$taxonomies_fields = array();
				
				foreach($post_taxonomies as $single_taxonomy){
					
					$tax_name = $single_taxonomy->name;
					$tax_label = $single_taxonomy->labels->name;
												
					if(!empty($marker_taxonomy) && $marker_taxonomy == $tax_name){
						
						$terms = get_terms($tax_name, "hide_empty=0");
						
						if(count($terms) > 0){	
							
							foreach($terms as $term){			   											
								$term_options[$term->term_id] = $term->name;
							}
							
							if(!$faceted_search){
								
								$taxonomies_fields[] = array(
									'id' => 'marker_category_'.$tax_name.'',
									'title' => 'Add marker image', 
									'desc' => 'Add an image to a category of markers.<br />
											   <strong>!Important:</strong> The data are not going to be saved automatically in the Database, you still have to click the "Save" button bellow to 
											   confirm your data. You can add as many marker images before saving.',
									'type' => 'tag',
									'helpers_container_id' => 'marker_img',
									'helpers' => array(	
										array(
											'id' => 'tag_marker_img_label',
											'type' => 'hidden',
										),
										array(
											'id' => 'tag_marker_img_name',											
											'type' => 'hidden',
										),										
										array(
											'id' => 'tag_marker_img_category',
											'title' => $tax_label, 
											'desc' => 'Select the marker category to which you want to add a custom image.',
											'type' => 'select',
											'std' => '0',
											'choices' => array('0'=>'')+$term_options,
											'class' => 'required'
										),										
										array(
											'id' => 'tag_marker_img_path',
											'title' => 'Marker image', 
											'desc' => 'Upload the marker category image.',
											'type' => 'file',
											'std' => '',
											'class' => 'required'
										),
										array(
											'id' => 'add_marker_img',
											'helpers_id' => 'marker_img',
											'type' => 'submit_tag'
										)
									)
								);						
							
							}else{							
							
								$taxonomies_fields[] =  array(
															'id' => 'faceted_search_taxonomy_'.$tax_name.'',
															'title' => $tax_label,
															'desc' => 'Select the terms to use in the faceted search.',
															'type' => 'multi_select',
															'std' => '',
															'choices' => $term_options,
														);
							
							}
							
						}	
						
					}
					
				}

			}else $taxonomies_fields =  array();

			return $taxonomies_fields;
			
		}
		
    	/**
         * Processes $wpsf_settings and adds the sections and fields via the WordPress settings API
         */
    	function cspm_process_settings()
    	{
            global $wpsf_settings;		
			$this->cspm_get_registred_cpt();
        	if(!empty($wpsf_settings)){
        	    usort($wpsf_settings, array(&$this, 'cspm_sort_array'));
        		foreach($wpsf_settings as $section){
            		if(isset($section['section_id']) && $section['section_id'] && isset($section['section_title'])){                		
						add_settings_section( $section['section_id'], $section['section_title'], array(&$this, 'cspm_section_intro'), $this->plugin_get_var );
                		if(isset($section['fields']) && is_array($section['fields']) && !empty($section['fields'])){
                    		foreach($section['fields'] as $field){
                        		if(isset($field['id']) && $field['id'] && isset($field['title'])){									
                        		    add_settings_field( $field['id'], '<strong>'.$field['title'].'</strong>', array(&$this, 'cspm_generate_setting'), $this->plugin_get_var, $section['section_id'], array('section' => $section, 'field' => $field) );

									// Add registred custom post types
									// Just for Progress map
									if($field['id'] == "post_types_section"){
										$post_types_field = $this->cspm_get_registred_cpt();									
										add_settings_field($post_types_field['id'], '<strong>'.$post_types_field['title'].'</strong>', array(&$this, 'cspm_generate_setting'), $this->plugin_get_var, $section['section_id'], array('section' => $section, 'field' => $post_types_field) );												
									
										//$secondary_post_types_field = $this->cspm_get_registred_cpt(true);									
										//add_settings_field($secondary_post_types_field['id'], '<strong>'.$secondary_post_types_field['title'].'</strong>', array(&$this, 'cspm_generate_setting'), $this->plugin_get_var, $section['section_id'], array('section' => $section, 'field' => $secondary_post_types_field) );												
									}
									// End
									
									// Add taxonomy fields for Query settings
									// Just for Progress map
									if($field['id'] == "taxonomies_section"){
										$taxonomies = $this->cspm_get_post_taxonomies();									
										$taxonomy_terms = $this->cspm_get_post_taxonomy_terms($taxonomies);
										if(count($taxonomy_terms) > 0){
											foreach($taxonomy_terms as $single_taxonomy){
												foreach($single_taxonomy as $taxonomy_data){												
													add_settings_field($taxonomy_data['id'], '<strong>'.$taxonomy_data['title'].'</strong>', array(&$this, 'cspm_generate_setting'), $this->plugin_get_var, $section['section_id'], array('section' => $section, 'field' => $taxonomy_data) );
												}
											}
										}
									}
									// End
									
									// Add taxonomy fields for marker categories settings
									// Just for Progress map
									if($field['id'] == "marker_cats_settings"){
										$taxonomies = $this->cspm_get_post_taxonomies();
										$taxonomies_data = $this->cspm_get_post_taxonomies_list($taxonomies);
										if(count($taxonomies_data) > 0)
											add_settings_field($taxonomies_data['id'], '<strong>'.$taxonomies_data['title'].'</strong>', array(&$this, 'cspm_generate_setting'), $this->plugin_get_var, $section['section_id'], array('section' => $section, 'field' => $taxonomies_data) );
									}
									// End
									
									// Add taxonomy (file) fields for Marker categories
									// Just for Progress map
									if($field['id'] == "marker_categories_desc_section"){
										$taxonomies = $this->cspm_get_post_taxonomies();
										$taxonomy_terms = $this->cspm_get_markers_taxonomy_terms($taxonomies);										
										if(count($taxonomy_terms) > 0){
											foreach($taxonomy_terms as $taxonomy_data){
												add_settings_field($taxonomy_data['id'], '<strong>'.$taxonomy_data['title'].'</strong>', array(&$this, 'cspm_generate_setting'), $this->plugin_get_var, $section['section_id'], array('section' => $section, 'field' => $taxonomy_data) );
											}
										}
									}
									// End
									
									// Add taxonomy fields for Faceted search
									// Just for Progress map
									if($field['id'] == "faceted_search_option"){
										$taxonomies = $this->cspm_get_post_taxonomies();
										$taxonomy_terms = $this->cspm_get_markers_taxonomy_terms($taxonomies, true);
										if(count($taxonomy_terms) > 0){
											foreach($taxonomy_terms as $taxonomy_data){
												add_settings_field($taxonomy_data['id'], '<strong>'.$taxonomy_data['title'].'</strong>', array(&$this, 'cspm_generate_setting'), $this->plugin_get_var, $section['section_id'], array('section' => $section, 'field' => $taxonomy_data) );
											}
										}
									}
									// End
									
                        		}
                    		}
                		}
            		}
        		}
    		}
    	}
    	
    	/**
         * Usort callback. Sorts $wpsf_settings by "section_order"
         * 
         * @param mixed section order a
         * @param mixed section order b
         * @return int order
         */
    	function cspm_sort_array( $a, $b )
    	{
        	return $a['section_order'] > $b['section_order'];
    	}
    	
    	/**
         * Generates the HTML output of the settings fields
         *
         * @param array callback args from add_settings_field()
         */
    	function cspm_generate_setting( $args )
    	{
    	    $section = $args['section'];
            $defaults = array(
        		'id'      => 'default_field',
        		'title'   => 'Default Field',
        		'desc'    => '',
        		'std'     => '',
        		'type'    => 'text',
        		'choices' => array(),
        		'class'   => ''
        	);
        	$defaults = apply_filters( 'wpsf_defaults', $defaults );
        	extract( wp_parse_args( $args['field'], $defaults ) );

        	$options = get_option( $this->option_group .'_settings' );
        	$el_id = $this->option_group .'_'. $section['section_id'] .'_'. $id;
        	$val = (isset($options[$el_id])) ? $options[$el_id] : $std;
        	
        	do_action('wpsf_before_field');
        	do_action('wpsf_before_field_'. $el_id);
    		switch( $type ){
    		    case 'text':
    		        $val = esc_attr(stripslashes($val));
    		        echo '<input type="text" name="'. $this->option_group .'_settings['. $el_id .']" id="'. $el_id .'" value="'. $val .'" class="regular-text '. $class .'" />';
    		        if($desc)  echo '<p class="description">'. $desc .'</p>';
    		        break;
				case 'hidden':
    		        $val = esc_attr(stripslashes($val));
    		        echo '<input type="hidden" name="'. $this->option_group .'_settings['. $el_id .']" id="'. $el_id .'" value="'. $val .'" class="regular-text '. $class .'" />';
    		        break;
    		    case 'textarea':
    		        $val = esc_html(stripslashes($val));
    		        echo '<textarea aria-describedby="newcontent-description" name="'. $this->option_group .'_settings['. $el_id .']" id="'. $el_id .'" rows="5" cols="60" class="'. $class .'">'. $val .'</textarea>';
    		        if($desc)  echo '<p class="description">'. $desc .'</p>';
    		        break;
    		    case 'select':
    		        $val = esc_html(esc_attr($val));
    		        echo '<select name="'. $this->option_group .'_settings['. $el_id .']" id="'. $el_id .'" class="'. $class .'" style="width: 25em;">';
    		        foreach($choices as $ckey=>$cval){
        		        echo '<option value="'. $ckey .'"'. (($ckey == $val) ? ' selected="selected"' : '') .'>'. $cval .'</option>';
    		        }
    		        echo '</select>';
    		        if($desc)  echo '<p class="description">'. $desc .'</p>';
    		        break;
				case 'multi_select':
					$val = (is_array($val)) ? $val : array();
    		        echo '<select name="'. $this->option_group .'_settings['. $el_id .'][]" id="'. $el_id .'" class="'. $class .' cspm_multi_select" multiple="multiple" style="width: 25em; height:250px;">';
    		        foreach($choices as $ckey=>$cval){
        		        echo '<option value="'. $ckey .'"'. ((in_array($ckey, $val)) ? ' selected="selected"' : '') .'>'. $cval .'</option>';
    		        }
    		        echo '</select>';
					echo '<a class="cspm_ms_refresh" id="'. $el_id .'" style="font-size:13px; cursor:pointer;">Refresh</a>, ';
					echo '<a class="cspm_ms_select_all" id="'. $el_id .'" style="font-size:13px; cursor:pointer;">Select all</a>, ';
					echo '<a class="cspm_ms_deselect_all" id="'. $el_id .'" style="font-size:13px; cursor:pointer;">Deselect all</a>'; 
    		        if($desc)  echo '<p class="description">'. $desc .'</p>';
    		        break;
    		    case 'radio':
					echo '<div class="'.$id.'">';
						$val = esc_html(esc_attr($val));
						foreach($choices as $ckey=>$cval){
							echo '<input type="radio" name="'. $this->option_group .'_settings['. $el_id .']" id="'. $el_id .'_'. $ckey .'" value="'. $ckey .'" class="'. $class .'"'. (($ckey == $val) ? ' checked="checked"' : '') .' /><label class="custom_wpsf" id="'. $el_id .'_'. $ckey .'" for="'. $el_id .'_'. $ckey .'">'. $cval .'</label><br />';
						}
					echo '</div>';
    		        if($desc)  echo '<p class="description">'. $desc .'</p>';
    		        break;
    		    case 'checkbox':
    		        $val = esc_attr(stripslashes($val));
    		        echo '<input type="hidden" name="'. $this->option_group .'_settings['. $el_id .']" value="0" />';
    		        echo '<input type="checkbox" name="'. $this->option_group .'_settings['. $el_id .']" id="'. $el_id .'" value="1" class="'. $class .'"'. (($val) ? ' checked="checked"' : '') .' /><label class="custom_wpsf" id="'. $ckey .'" for="'. $el_id .'">'. $desc .'</label>';
    		        break;
    		    case 'checkboxes':					
					echo '<div class="'.$id.'">';
						foreach($choices as $ckey=>$cval){
							$val = '';
							if(isset($options[$el_id .'_'. $ckey])) $val = $options[$el_id .'_'. $ckey];
							elseif(is_array($std) && in_array($ckey, $std)) $val = $ckey;
							$val = esc_html(esc_attr($val));
							echo '<input type="hidden" name="'. $this->option_group .'_settings['. $el_id .'_'. $ckey .']" value="0" />';
							echo '<input type="checkbox" name="'. $this->option_group .'_settings['. $el_id .'_'. $ckey .']" id="'. $el_id .'_'. $ckey .'" value="'. $ckey .'" class="'. $class .'"'. (($ckey == $val) ? ' checked="checked"' : '') .' /><label class="custom_wpsf" id="'. $el_id .'_'. $ckey .'" for="'. $el_id .'_'. $ckey .'">'. $cval .'</label><br />';
						}
					echo '</div>';
    		        if($desc)  echo '<p class="description">'. $desc .'</p>';
    		        break;
    		    case 'checkboxes_array':
    		        $val = (is_array($val)) ? $val : array();
    		        foreach($choices as $ckey=>$cval){
						echo '<input type="checkbox" name="'. $this->option_group .'_settings['. $el_id .'][]" id="'. $el_id .'_'. $ckey .'" value="'. $ckey .'" class="'. $class .'"'. ((in_array($ckey, $val)) ? ' checked="checked"' : '') .' /><label class="custom_wpsf" id="'. $el_id .'_'. $ckey .'" for="'. $el_id .'_'. $ckey .'">'. $cval .'</label><br />';
    		        }				
    		        if($desc)  echo '<p class="description">'. $desc .'</p>';
    		        break;									
    		    case 'color':
                    $val = esc_attr(stripslashes($val));
                    echo '<div style="position:relative;">';
    		        echo '<input type="text" name="'. $this->option_group .'_settings['. $el_id .']" id="'. $el_id .'" value="'. $val .'" class="'. $class .'" />';
    		        echo '<div id="'. $el_id .'_cp" style="position:absolute;top:0;left:190px;background:#fff;z-index:9999;"></div>';
    		        if($desc)  echo '<p class="description">'. $desc .'</p>';
    		        echo '<script type="text/javascript">
    		        jQuery(document).ready(function($){ 
                        var colorPicker = $("#'. $el_id .'_cp");
                        colorPicker.farbtastic("#'. $el_id .'");
                        colorPicker.hide();
                        $("#'. $el_id .'").on("focus", function(){
                            colorPicker.show();
                        });
                        $("#'. $el_id .'").on("blur", function(){
                            colorPicker.hide();
                            if($(this).val() == "") $(this).val("#");
                        });
                    });
                    </script></div>';
    		        break;
    		    case 'file':
                    $val = esc_attr($val);
    		        echo '<input type="text" name="'. $this->option_group .'_settings['. $el_id .']" id="'. $el_id .'" value="'. $val .'" class="regular-text '. $class .'" /> ';
                    echo '<input type="button" class="button wpsf-browse" id="'. $el_id .'_button" value="Browse" />';
                    echo '<script type="text/javascript">
                    jQuery(document).ready(function($){
                		$("#'. $el_id .'_button").click(function() {
                			tb_show("", "media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true");
                			window.original_send_to_editor = window.send_to_editor;
                        	window.send_to_editor = function(html) {
                        		var imgurl = $("img",html).attr("src");
                        		$("#'. $el_id .'").val(imgurl);
                        		tb_remove();
                        		window.send_to_editor = window.original_send_to_editor;
                        	};
                			return false;
                		});
                    });
                    </script>';
					if($desc)  echo '<p class="description">'. $desc .'</p>';
                    break;
                case 'editor':

    		        wp_editor( $val, $el_id, array( 'textarea_name' => $this->option_group .'_settings['. $el_id .']' ) );
    		        if($desc)  echo '<p class="description">'. $desc .'</p>';
    		        break;
    		    case 'custom':
    		        echo $std;
    		        break;
				case 'tag':
					echo '<div class="cspm_tags_container '.$helpers_container_id.'" data-helpers-id="'.$helpers_container_id.'">';						
						if(!empty($options[$el_id])){
							$explode_tag_option = json_decode($options[$el_id]);														
							$tag_label = 'tag_'.$helpers_container_id.'_label';
							$tag_name  = 'tag_'.$helpers_container_id.'_name';
							$i=1;
							foreach($explode_tag_option as $tag_option){							
								$tag_option = (array) $tag_option;
								if(count($tag_option) > 0){
									echo '<div class="cspm_tag_container" data-helpers-id="'.$helpers_container_id.'" data-tag-name="'.$tag_option[$tag_name].'">';
										echo '<strong class="cspm_tag_label">'.$tag_option[$tag_label].'</strong>';
										echo '<span class="cspm_remove_tag" data-helpers-id="'.$helpers_container_id.'" data-tag-name="'.$tag_option[$tag_name].'">Remove</span>';
										echo '<span class="cspm_update_tag" data-helpers-id="'.$helpers_container_id.'" data-tag-name="'.$tag_option[$tag_name].'">Update</span>';
									echo '</div>';
									$i++;
								}
							}												
						}
					echo '</div>';
					echo '<textarea name="'. $this->option_group .'_settings['. $el_id .']" id="'.$el_id.'" data-helpers-textarea="'.$helpers_container_id.'" style="display:none;">'. $val .'</textarea>';
					echo '<div class="cspm_tag_warning" data-helpers-container-id="'.$helpers_container_id.'">Data submited/updated. Please don\'t forget to save your data when you finish!</div>';
					echo '<div class="cspm_add_tag" id="'.$el_id.'" data-helpers-container-id="'.$helpers_container_id.'">Add new</div>';
					echo '<div style="clear:both"></div>';					
					echo '<div class="cspm_add_tag_container" id="'.$el_id.'" data-helpers-container-id="'.$helpers_container_id.'">';
						foreach($helpers as $helper){ 
							$this->cspm_generate_tag_helpers($helper);
						}
					echo '</div>';
					if($desc)  echo '<p class="description">'. $desc .'</p>';
					break;					
        		case 'link':
					$val = esc_attr(stripslashes($val));
    		        echo '<a href="'. $val .'" id="'. $el_id .'" class="cspm_troubleshooting_link">'. $title .'</a>';
					if($desc)  echo '<p class="description">'. $desc .'</p>';
    		        break;
				default:
        		    break;
    		}
    		do_action('wpsf_after_field');
        	do_action('wpsf_after_field_'. $el_id);
    	}
		
		/*
		 * Generate the helpers for the tag formulaire
		 * Designed only for the purpose of this plugin
		 */
		function cspm_generate_tag_helpers($helpers){
    	   
            $defaults = array(
        		'id'      => 'default_field',
        		'title'   => 'Default Field',
        		'desc'    => '',
        		'std'     => '',
        		'type'    => 'text',
        		'choices' => array(),
        		'class'   => ''
        	);
        	
        	extract( wp_parse_args( $helpers, $defaults ) );
        	
        	$el_id = $id;
        	$val = $std;
    		
			switch( $type ){
    		    case 'text':
    		        $val = esc_attr(stripslashes($val));
					echo '<label class="label_wpsf" id="'. $el_id .'" for="'. $el_id .'">'. $title .'</label>';
    		        echo '<input type="text" name="'. $el_id .'" id="'. $el_id .'" value="'. $val .'" class="regular-text '. $class .'" onKeyPress="return disableEnterKey(event)" />';
    		        if($desc)  echo '<p class="description">'. $desc .'</p>';
    		        break;
				case 'hidden':
    		        $val = esc_attr(stripslashes($val));
    		        echo '<input type="hidden" name="'. $el_id .'" id="'. $el_id .'" value="'. $val .'" class="regular-text '. $class .'" />';
    		        break;
    		    case 'textarea':
    		        $val = esc_html(stripslashes($val));
					echo '<label class="label_wpsf" id="'. $el_id .'" for="'. $el_id .'">'. $title .'</label>';
    		        echo '<textarea aria-describedby="newcontent-description" name="'. $el_id .'" id="'. $el_id .'" rows="5" cols="60" class="'. $class .'">'. $val .'</textarea>';
    		        if($desc)  echo '<p class="description">'. $desc .'</p>';
    		        break;
    		    case 'select':
    		        $val = esc_html(esc_attr($val));
					echo '<label class="label_wpsf" id="'. $el_id .'" for="'. $el_id .'">'. $title .'</label>';
    		        echo '<select name="'. $el_id .'" id="'. $el_id .'" class="'. $class .'" style="width: 25em;">';
    		        foreach($choices as $ckey=>$cval){
        		        echo '<option value="'. $ckey .'"'. (($ckey == $val) ? ' selected="selected"' : '') .'>'. $cval .'</option>';
    		        }
    		        echo '</select>';
    		        if($desc)  echo '<p class="description">'. $desc .'</p>';
    		        break;
				case 'multi_select':
					$val = (is_array($val)) ? $val : array();
					echo '<label class="label_wpsf" id="'. $el_id .'" for="'. $el_id .'">'. $title .'</label>';
    		        echo '<select name="'. $el_id .'[]" id="'. $el_id .'" class="'. $class .' cspm_multi_select" multiple="multiple" style="width: 25em; height:250px;">';
    		        foreach($choices as $ckey=>$cval){
        		        echo '<option value="'. $ckey .'"'. ((in_array($ckey, $val)) ? ' selected="selected"' : '') .'>'. $cval .'</option>';
    		        }
    		        echo '</select>';
					echo '<a class="cspm_ms_refresh" id="'. $el_id .'" style="font-size:11px; cursor:pointer;">Refresh</a>, ';
					echo '<a class="cspm_ms_select_all" id="'. $el_id .'" style="font-size:11px; cursor:pointer;">Select all</a>, ';
					echo '<a class="cspm_ms_deselect_all" id="'. $el_id .'" style="font-size:11px; cursor:pointer;">Deselect all</a>'; 
    		        if($desc)  echo '<p class="description">'. $desc .'</p>';
    		        break;
    		    case 'radio':
    		        $val = esc_html(esc_attr($val));
					echo '<label class="label_wpsf" id="'. $el_id .'" for="'. $el_id .'">'. $title .'</label><br />';
    		        foreach($choices as $ckey=>$cval){
        		        echo '<input type="radio" name="'. $el_id .'" id="'. $el_id .'_'. $ckey .'" value="'. $ckey .'" class="'. $class .'"'. (($ckey == $val) ? ' checked="checked"' : '') .' onKeyPress="return disableEnterKey(event)" />';
						echo '<label class="custom_wpsf" id="'. $el_id .'_'. $ckey .'" for="'. $el_id .'_'. $ckey .'">'. $cval .'</label><br />';
    		        }
    		        if($desc)  echo '<p class="description">'. $desc .'</p>';
    		        break;
    		    case 'checkbox':
    		        $val = esc_attr(stripslashes($val));
					echo '<label class="label_wpsf" id="'. $el_id .'" for="'. $el_id .'">'. $title .'</label><br />';
    		        echo '<input type="hidden" name="'. $el_id .'" value="0" />';
    		        echo '<input type="checkbox" name="'. $el_id .'" id="'. $el_id .'" value="1" class="'. $class .'"'. (($val) ? ' checked="checked"' : '') .' onKeyPress="return disableEnterKey(event)" />';
					echo '<label class="custom_wpsf" id="'. $ckey .'" for="'. $el_id .'">'. $desc .'</label>';
    		        break;
    		    case 'checkboxes':
					echo '<label class="label_wpsf" id="'. $el_id .'" for="'. $el_id .'">'. $title .'</label><br />';
    		        foreach($choices as $ckey=>$cval){
    		            $val = '';
    		            if(is_array($std) && in_array($ckey, $std)) $val = $ckey;
    		            $val = esc_html(esc_attr($val));
        		        echo '<input type="hidden" name="'. $el_id .'_'. $ckey .'" value="0" />';
        		        echo '<input type="checkbox" name="'. $el_id .'_'. $ckey .'" id="'. $el_id .'_'. $ckey .'" value="'. $ckey .'" class="'. $class .'"'. (($ckey == $val) ? ' checked="checked"' : '') .' onKeyPress="return disableEnterKey(event)" />';
						echo '<label class="custom_wpsf" id="'. $el_id .'_'. $ckey .'" for="'. $el_id .'_'. $ckey .'">'. $cval .'</label><br />';
    		        }
    		        if($desc)  echo '<p class="description">'. $desc .'</p>';
    		        break;
    		    case 'checkboxes_array':
    		        $val = (is_array($val)) ? $val : array();
					echo '<label class="label_wpsf" id="'. $el_id .'" for="'. $el_id .'">'. $title .'</label><br />';
    		        foreach($choices as $ckey=>$cval){
						echo '<input type="checkbox" name="'. $el_id .'[]" id="'. $el_id .'_'. $ckey .'" value="'. $ckey .'" class="'. $class .'"'. ((in_array($ckey, $val)) ? ' checked="checked"' : '') .' onKeyPress="return disableEnterKey(event)" />';
						echo '<label class="custom_wpsf" id="'. $el_id .'_'. $ckey .'" for="'. $el_id .'_'. $ckey .'">'. $cval .'</label><br />';
    		        }				
    		        if($desc)  echo '<p class="description">'. $desc .'</p>';
    		        break;					
    		    case 'color':
                    $val = esc_attr(stripslashes($val));
                    echo '<div style="position:relative;">';
					echo '<label class="label_wpsf">'. $title .'</label>';
    		        echo '<input type="text" name="'. $el_id .'" id="'. $el_id .'" value="'. $val .'" class="'. $class .'" onKeyPress="return disableEnterKey(event)" />';
    		        echo '<div id="'. $el_id .'_cp" style="position:absolute;top:0;left:190px;background:#fff;z-index:9999;"></div>';
    		        if($desc)  echo '<p class="description">'. $desc .'</p>';
    		        echo '<script type="text/javascript">
    		        jQuery(document).ready(function($){ 
                        var colorPicker = $("#'. $el_id .'_cp");
                        colorPicker.farbtastic("#'. $el_id .'");
                        colorPicker.hide();
                        $("#'. $el_id .'").on("focus", function(){
                            colorPicker.show();
                        });
                        $("#'. $el_id .'").on("blur", function(){
                            colorPicker.hide();
                            if($(this).val() == "") $(this).val("#");
                        });
                    });
                    </script></div>';
    		        break;
    		    case 'file':
                    $val = esc_attr($val);
					echo '<label class="label_wpsf" id="'. $el_id .'" for="'. $el_id .'">'. $title .'</label>';
    		        echo '<input type="text" name="'. $el_id .'" id="'. $el_id .'" value="'. $val .'" class="regular-text '. $class .'" onKeyPress="return disableEnterKey(event)" /> ';
                    echo '<input type="button" class="button wpsf-browse" id="'. $el_id .'_button" value="Browse" />';
                    echo '<script type="text/javascript">
                    jQuery(document).ready(function($){
                		$("#'. $el_id .'_button").click(function() {
                			tb_show("", "media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true");
                			window.original_send_to_editor = window.send_to_editor;
                        	window.send_to_editor = function(html) {
                        		var imgurl = $("img",html).attr("src");
                        		$("input#'. $el_id .'").val(imgurl);
                        		tb_remove();
                        		window.send_to_editor = window.original_send_to_editor;
                        	};
                			return false;
                		});
                    });
                    </script>';
					if($desc)  echo '<p class="description">'. $desc .'</p>';
                    break;
                case 'editor':
					echo '<label class="label_wpsf" id="'. $el_id .'" for="'. $el_id .'">'. $title .'</label>';
    		        wp_editor( $val, $el_id, array( 'textarea_name' => $el_id ) );
    		        if($desc)  echo '<p class="description">'. $desc .'</p>';
    		        break;
    		    case 'custom':
    		        echo $std;
    		        break;
				case 'submit_tag':
					echo '<a class="cspm_submit_tag_form" id="'.$el_id.'" data-helpers-id="'.$helpers_id.'">Add</a>';										
					echo '<a class="cspm_update_tag_form" id="'.$el_id.'" data-helpers-id="'.$helpers_id.'" style="display:none">Update</a>';
					echo '<a class="cspm_cancel_tag_form" id="'.$el_id.'" data-helpers-id="'.$helpers_id.'">Cancel</a>';
					echo '<br style="clear:both" />';
					break;
        		default:
        		    break;
    		}		
			
		}
    
    	/**
         * Output the settings form
         */
        function cspm_settings()
        {
            do_action('wpsf_before_settings');
            ?>
            <form action="options.php" method="post" id="cspm_form">
                <?php do_action('wpsf_before_settings_fields'); ?>
                <?php settings_fields( $this->option_group ); ?>
				<?php cspm_custom_do_settings_sections( $this->plugin_get_var ); ?>
        		<p class="submit cspm_submit" style="margin-left:10px; border-top:1px solid #e8ebec"><input type="submit" style="height:40px;" class="custom-button-primary" value="Save" /></p>
				<div style="clear:both"></div>
            </form>
    		<?php
    		do_action('wpsf_after_settings');
        }
    
    }   
}

if( !function_exists('cspm_wpsf_get_option_group') ){
    /**
     * Converts the settings file name to option group id
     * 
     * @param string settings file
     * @return string option group id
     */
    function cspm_wpsf_get_option_group( $settings_file ){
        $option_group = preg_replace("/[^a-z0-9]+/i", "", basename( $settings_file, '.php' ));
        return $option_group;
    }
}

if( !function_exists('cspm_wpsf_get_settings') ){
    /**
     * Get the settings from a settings file/option group
     * 
     * @param string path to settings file
     * @param string optional "option_group" override
     * @return array settings
     */
    function cspm_wpsf_get_settings( $settings_file, $option_group = '' ){
        $opt_group = preg_replace("/[^a-z0-9]+/i", "", basename( $settings_file, '.php' ));
        if( $option_group ) $opt_group = $option_group;
        return get_option( $opt_group .'_settings' );
    }
}

if( !function_exists('cspm_wpsf_get_setting') ){
    /**
     * Get a setting from an option group
     * 
     * @param string option group id
     * @param string section id
     * @param string field id
     * @return mixed setting or false if no setting exists
     */
    function cspm_wpsf_get_setting( $option_group, $section_id, $field_id ){
        $options = get_option( $option_group .'_settings' );
        if(isset($options[$option_group .'_'. $section_id .'_'. $field_id])) return $options[$option_group .'_'. $section_id .'_'. $field_id];
        return false;
    }
}

if( !function_exists('cspm_wpsf_delete_settings') ){
    /**
     * Delete all the saved settings from a settings file/option group
     * 
     * @param string path to settings file
     * @param string optional "option_group" override
     */
    function cspm_wpsf_delete_settings( $settings_file, $option_group = '' ){
        $opt_group = preg_replace("/[^a-z0-9]+/i", "", basename( $settings_file, '.php' ));
        if( $option_group ) $opt_group = $option_group;
        delete_option( $opt_group .'_settings' );
    }
}

if( !function_exists('cspm_custom_do_settings_sections') ){

	function cspm_custom_do_settings_sections( $page ) {
		global $wp_settings_sections, $wp_settings_fields;
	
		if ( ! isset( $wp_settings_sections ) || !isset( $wp_settings_sections[$page] ) )
			return;
	
		foreach ( (array) $wp_settings_sections[$page] as $section ) {
			echo '<div class="custom_section_'.$section["id"].'">';
				if ( $section['title'] )
					echo "<h3>{$section['title']}</h3>\n";
				
				if ( $section['callback'] )
					call_user_func( $section['callback'], $section );
						
				echo '<p style="border-top:1px solid #e8ebec; margin:10px 0 20px 0"></p>';
				
				if ( ! isset( $wp_settings_fields ) || !isset( $wp_settings_fields[$page] ) || !isset( $wp_settings_fields[$page][$section['id']] ) )
					continue;
				echo '<div class="cspm_form_container">';
				cspm_custom_do_settings_fields( $page, $section['id'] );
				echo '</div>';
			echo '</div>';
		}
	}
}

if( !function_exists('cspm_custom_do_settings_fields') ){
	function cspm_custom_do_settings_fields($page, $section) {
		global $wp_settings_fields;
	
		if ( ! isset( $wp_settings_fields[$page][$section] ) )
			return;
	
		foreach ( (array) $wp_settings_fields[$page][$section] as $field ) {
			echo '<div class="cspm_top">';
				if ( !empty($field['args']['label_for']) )
					echo '<div class="cspm_label"><label for="' . esc_attr( $field['args']['label_for'] ) . '">' . $field['title'] . '</label></div>';
				else
					echo '<div class="cspm_label">' . $field['title'] . '</div>';
				echo '<div class="cspm_field">';
				call_user_func($field['callback'], $field['args']);
				echo '</div>';
				echo '<div style="clear:both"></div>';
			echo '</div>';
		}
	}
}


?>