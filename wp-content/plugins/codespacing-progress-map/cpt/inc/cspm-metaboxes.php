<?php
 
if(!defined('ABSPATH')){
    exit; // Exit if accessed directly
}

if( !class_exists( 'CspmMetaboxes' ) ){
	
	class CspmMetaboxes{
		
		private $plugin_path;
		private $plugin_url;
		
		private static $_this;	
		
		public $plugin_settings = array();
		
		/**
		 * The name of the post to which we'll add the metaboxes
		 * @since 1.0 */
		 
		public $object_type;
				
        /**
         * Constructor
         * 
         * @plugin_path string path to metaboxes library
         */
        function __construct($atts = array()){
			
			extract( wp_parse_args( $atts, array(
				'plugin_path' => '', 
				'plugin_url' => '',
				'plugin_settings' => array(),
				'metafield_prefix' => '',
				'object_type' => '',
			)));
             
			self::$_this = $this;       
				           
			$this->plugin_path = $plugin_path;
			$this->plugin_url = $plugin_url;
			
			$this->plugin_settings = $plugin_settings;
			
			$this->object_type = $object_type;
			
			$this->metafield_prefix = $metafield_prefix;
			
			/**
			 * Include all required Libraries */
			 
			$libs_path = array(
				'cmb2' => 'cpt/libs/metabox/init.php',
				'cmb2-tabs' => 'cpt/libs/metabox-tabs/cmb2-tabs.class.php',
				'cmb2-conditional' => 'cpt/libs/metabox-conditionals/cmb2-conditionals.php',
				'cmb2-field-select2' => 'cpt/libs/metabox-field-select2/cmb-field-select2.php',
				'cmb2-field-post-search-ajax' => 'cpt/libs/metabox-field-post-search-ajax/cmb-field-post-search-ajax.php',
				'cmb2-radio-image' => 'cpt/libs/metabox-radio-image/metabox-radio-image.php',
				'cmb2-field-order' => 'cpt/libs/metabox-field-order/cmb2-field-order.php', //@since 3.1
			);
				
				foreach($libs_path as $lib_file_path){
					if(file_exists($this->plugin_path . $lib_file_path))
						require_once $this->plugin_path . $lib_file_path;
				}
	
			/**
			 * Include all metaboxes files */
			 
			$metaboxes_path = array(
				'cspm_metabox' => 'cpt/inc/cspm-metabox.php',
			);
				
				foreach($metaboxes_path as $metabox_file_path){
					if(file_exists($this->plugin_path . $metabox_file_path))
						require_once $this->plugin_path . $metabox_file_path;
				}
				
			/**
			 * Load Metaboxes */

			add_action( 'cmb2_admin_init', array(&$this, 'cspm_metaboxes') );
			
			/**
			 * Call .js and .css files */
			 
			add_filter( 'cmb2_enqueue_js', array(&$this, 'cspm_scripts') );
			
			/**
			 * Injects the JS script that will change the group title */
			 
			add_action('admin_footer', array(&$this, 'cspm_change_group_titles_script') );

        }
	
	
		static function this() {
			
			return self::$_this;
		
		}
		
		
		function cspm_scripts(){
			
			global $typenow;
			
			/**
			 * Our custom metaboxes JS & CSS file must be loaded only on our CPT page */

			if($typenow === $this->object_type){
							
				/**
				 * CSS */
				 
				wp_enqueue_style('cspm-cmb2-tabs-css', $this->plugin_url . 'cpt/libs/metabox-tabs/css/cmb2-tabs.css', array(), '1.0.1');
				
				/**
				 * Our custom metaboxes CSS */
				
				wp_enqueue_style('cspm-metabox-css', $this->plugin_url . 'cpt/inc/css/cspm-metabox-style.css');
		
				/**
				 * js */
	
				wp_enqueue_script('cspm-cmb2-tabs-js', $this->plugin_url . 'cpt/libs/metabox-tabs/js/cmb2-tabs.js', array(), '1.0.1', true);
						
			}
			
		}
	
	
		/**
		 * Define the metabox and field configurations.
		 *
		 * @since 1.0
		 */
		function cspm_metaboxes() {
				
			/**
			 * Display "Progress Map" Metabox */

			if(class_exists('CspmMetabox')){
				
				$CspmMetabox = new CspmMetabox(array(
					'plugin_path' => $this->plugin_path, 
					'plugin_url' => $this->plugin_url,
					'object_type' => $this->object_type,
					'plugin_settings' => $this->plugin_settings,
					'metafield_prefix' => $this->metafield_prefix,
				));

				$CspmMetabox->cspm_progress_map_metabox();
				
			}

		}
		
		
		/**
		 * This contains the JS script that will change the group titles to the value of ...
		 * ... the field that has the attribute [data-group-title] 
		 *
		 * @since 1.0 		 
		 */
		function cspm_change_group_titles_script(){ 
						
			global $typenow;

			if($typenow === $this->object_type){ ?>
        
				<script type="text/javascript">
	
                    jQuery(document).ready(function($){ 

                        var metaboxes = ['<?php echo $this->metafield_prefix; ?>_pm_metabox', '<?php echo $this->metafield_prefix; ?>_pmlf_metabox'];
                        
                        for(var i=0; i<metaboxes.length; i++) {
                        
                            var $box = $( document.getElementById( metaboxes[i] ) );
                            
                            var replaceTitles = function() {
                                $box.find( '.cmb-group-title' ).each( function() {
                                    var $this = $( this );
                                    var fieldType = $this.next().find('[data-group-title]').prop('type');
                                    if(fieldType == 'text'){
                                        var txt = $this.next().find( '[data-group-title]' ).val();
                                    }else if(fieldType == 'select-one'){
                                        var selectOptions = $this.next().find( '[data-group-title]' );
                                        var txt = selectOptions.find(':selected').text();								
                                    }
                                    
                                    if ( txt ) {
                                        $this.text( txt );
                                    }
                                });
                            };

                            $box.on( 'cmb2_add_row cmb2_shift_rows_complete', function( evt ) {
                                replaceTitles();
                            });
                            
                            replaceTitles();
                            
                        }
		
						/**
						 * by Codespacing | Custom Fix
						 * 
						 * Fix an issue in the CMB extension "Conditionals" that doesn't display ...
						 * ... the first field in the group "Marker categories settings => Marker Image #" when clicking ...
						 * ... on the button "Add new marker image".
						 * ... This code will trigger the selected taxonomy in "Marker categories settings => Taxonomies" ...
						 * ... which will allow "Conditionals" to research for all group fields to display based on the selected ...
						 * ... taxonomy and which will also show the first field ("{taxonomy_name}") in the group that was mistakenly hidden */
						 
						$('.cmb-add-group-row').on('click', function(evt){
							
							var $checked_taxonomy = $("input[name=<?php echo $this->metafield_prefix; ?>_marker_categories_taxonomy]:checked");
							
							var value = $checked_taxonomy.val();
							
							var id = $checked_taxonomy.attr('id');
							
							setTimeout(function(){
								$('input[id='+id+']').trigger('change');
							}, 50);
							
						});

                    });
                    
                </script><?php
				
				/**
		 		 * CMB2 Auto-scroll to new group */
		 
				$this->cspm_cmb_group_autoscroll_js();
				
			}
			
		}
			
			
		/**
		 * Re-activate CMB2 Auto-scroll to new group
		 * Note: This feature was removed in CMB2 2.0.3
		 * https://github.com/CMB2/CMB2-Snippet-Library/blob/master/javascript/cmb2-auto-scroll-to-new-group.php
		 *
		 * @since 3.0
		 */
		function cspm_cmb_group_autoscroll_js() {
			
			// If not cmb2 scripts on this page, bail
			if ( ! wp_script_is( 'cmb2-scripts', 'enqueued' ) ) {
				return;
			}
			?>
			<script type="text/javascript">
				window.CMB2 = window.CMB2 || {};
				(function(window, document, $, cmb, undefined){
					'use strict';
					// We'll keep it in the CMB2 object namespace
					cmb.initAutoScrollGroup = function(){
						cmb.metabox().find('.cmb-repeatable-group').on( 'cmb2_add_row', cmb.autoScrollGroup );
					};
					cmb.autoScrollGroup = function( evt, row ) {
						var $focus = $(row).find('input:not([type="button"]), textarea, select').first();
						if ( $focus.length ) {
							$( 'html, body' ).animate({
								scrollTop: Math.round( $focus.offset().top - 150 )
							}, 1000);
							$focus.focus();
						}
					};
					$(document).ready( cmb.initAutoScrollGroup );
				})(window, document, jQuery, CMB2);
			</script>
			<?php
			
		}					
				
	}
	
}
		
