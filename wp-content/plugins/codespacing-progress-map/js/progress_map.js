/* @Version 3.2 */

//=======================//
//==== Map functions ====//
//=======================//

	window.cspm_global_object = {};
	
	var map_layout = {};
	
	/**
	 * Load map options
	 *
	 * @light_map, Declare the light map in order to use the apropriate options for this type of map.
	 * @latLng, The center point of the map.
	 * @zoom, The default zoom of the map.
	 *
	 */
	function cspm_load_map_options(map_id, light_map, latLng, zoom){
        
		/**
		 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
		 * @since 3.0 */
		var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';

		var latlng = (latLng != null) ? latLng.split(',') : progress_map_vars.map_script_args[map_script_id]['center'].split(',');
		
		var zoom_value = (zoom != null) ? parseInt(zoom) : parseInt(progress_map_vars.map_script_args[map_script_id]['zoom']);
		var max_zoom_value = parseInt(progress_map_vars.map_script_args[map_script_id]['max_zoom']);
		var min_zoom_value = parseInt(progress_map_vars.map_script_args[map_script_id]['min_zoom']);
		
		var map_draggable = (progress_map_vars.map_script_args[map_script_id]['map_draggable'] == 'true') ? true : false;
		var zoom_on_doubleclick = (progress_map_vars.map_script_args[map_script_id]['zoom_on_doubleclick'] == 'true') ? true : false;

		var default_options = {
			center:[latlng[0], latlng[1]],
			zoom: zoom_value,			
			maxZoom: max_zoom_value,
			minZoom: min_zoom_value,								
			scrollwheel: eval(progress_map_vars.map_script_args[map_script_id]['scrollwheel']),
			mapTypeControl: eval(progress_map_vars.map_script_args[map_script_id]['mapTypeControl']),
			mapTypeControlOptions: {
				position: google.maps.ControlPosition.TOP_RIGHT,
				mapTypeIds: [google.maps.MapTypeId.ROADMAP,
							 google.maps.MapTypeId.SATELLITE,
							 google.maps.MapTypeId.TERRAIN,
							 google.maps.MapTypeId.HYBRID]				
			},
			streetViewControl: eval(progress_map_vars.map_script_args[map_script_id]['streetViewControl']),	
			streetViewControlOptions: {
				position: google.maps.ControlPosition.RIGHT_TOP  
			},
			draggable: map_draggable,
			disableDoubleClickZoom: zoom_on_doubleclick,
		};
		
		if(progress_map_vars.map_script_args[map_script_id]['zoomControl'] == 'true' && progress_map_vars.map_script_args[map_script_id]['zoomControlType'] == 'default'){
			
			var zoom_options = {
				zoomControl: true,
				zoomControlOptions:{
					//style: google.maps.ZoomControlStyle.SMALL 
				},
			};
		
		}else{
			var zoom_options = {
				zoomControl: false,
			};
		}
		
		var map_options = jQuery.extend({}, default_options, zoom_options);

		return map_options;
		
	}					
	
	/**
	 * Set the initial map style
	 * @since 2.4
	 */
	function cspm_initial_map_style(map_style, custom_style_status){
			
		if(map_style == 'custom_style' && custom_style_status == false)
			var map_type_id = {mapTypeId: google.maps.MapTypeId.ROADMAP};
		
		else if(map_style == 'custom_style')
			var map_type_id = {mapTypeId: "custom_style"};
			
		else if(map_style == 'ROADMAP')
			var map_type_id = {mapTypeId: google.maps.MapTypeId.ROADMAP};
			
		else if(map_style == 'SATELLITE')
			var map_type_id = {mapTypeId: google.maps.MapTypeId.SATELLITE};
			
		else if(map_style == 'TERRAIN')				
			var map_type_id = {mapTypeId: google.maps.MapTypeId.TERRAIN};
			
		else if(map_style == 'HYBRID')				
			var map_type_id = {mapTypeId: google.maps.MapTypeId.HYBRID};
		
		return map_type_id;
		
	}
	
	var post_ids_and_categories = {};
	var post_lat_lng_coords = {};
	var post_ids_and_child_status = {}

	/**
	 * Create Markers
	 *
	 * @Since 2.5 
	 */
	function cspm_new_pin_object(i, post_id, lat, lng, post_categories, map_id, marker_img, marker_size, is_child){
		        
		/**
		 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
		 * @since 3.0 */
		var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';

		post_lat_lng_coords[map_id][post_id] = lat+'_'+lng;
	
		// Create an object of that post_id and its categories/status for the faceted search
		post_lat_lng_coords[map_id]['post_id_'+post_id] = {};
		post_ids_and_child_status[map_id][lat+'_'+lng] = is_child;
		
		// Get the current post categories	
		var post_category_ids = (post_categories != '') ? post_categories.split(',') : '';
		
		// Collect an object of all posts in the map and their categories
		// Useful for the faceted search & the search form
		post_lat_lng_coords[map_id]['post_id_'+post_id][0] = post_category_ids;
		
		// By default the marker image is the default Google map red marker
		var marker_icon = '';
		
		// If the selected marker is the customized type
		if(progress_map_vars.map_script_args[map_script_id]['defaultMarker'] == 'customize'){
			
			// Get the custom marker image
			// If the marker categories option is set to TRUE, the marker image will be the uploaded marker category image
			// If the marker categories option is set to FALSE, the marker image will be the default custom image provided by the plugin
			var marker_cat_img = marker_img;

			// Marker image size
			var marker_img_width = (progress_map_vars.map_script_args[map_script_id]['retinaSupport'] == "true") ? parseInt(marker_size.split('x')[0])/2 : parseInt(marker_size.split('x')[0]);
			var marker_img_height = (progress_map_vars.map_script_args[map_script_id]['retinaSupport'] == "true") ? parseInt(marker_size.split('x')[1])/2 : parseInt(marker_size.split('x')[1]);

			// Marker image anchor point
			var anchor_y = marker_img_height/2;
			var anchor_x = marker_img_width/2;	
			var anchor_point = null;
			
			if(progress_map_vars.map_script_args[map_script_id]['marker_anchor_point_option'] == 'auto')				
				anchor_point = new google.maps.Point(anchor_x, anchor_y);
			else if(progress_map_vars.map_script_args[map_script_id]['marker_anchor_point_option'] == 'manual'){
				if(progress_map_vars.map_script_args[map_script_id]['retinaSupport'] == "true"){
					anchor_point = new google.maps.Point(
						progress_map_vars.map_script_args[map_script_id]['marker_anchor_point'].split(',')[0]/2, 
						progress_map_vars.map_script_args[map_script_id]['marker_anchor_point'].split(',')[1]/2
					);
				}else anchor_point = new google.maps.Point(progress_map_vars.map_script_args[map_script_id]['marker_anchor_point'].split(',')[0], progress_map_vars.map_script_args[map_script_id]['marker_anchor_point'].split(',')[1]);
			}

			// Add retina support
			marker_icon = new google.maps.MarkerImage(marker_cat_img, null, null, anchor_point, new google.maps.Size(marker_img_width,marker_img_height));					
			
		}		

		return pin_object = {latLng: [lat, lng], tag: 'post_id__'+post_id, id: post_id+'_'+is_child, options:{ optimized: false, icon: marker_icon, id: post_id, post_id: post_id, is_child: is_child }};										
	
	}
	
	/**
	 * Clustering markers 
	 */
	function cspm_clustering(plugin_map, map_id, light_map){
        
		/**
		 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
		 * @since 3.0 */
		var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';

		var markerCluster;
		
		var mapObject = plugin_map.gmap3('get');
	
		small_cluster_size = progress_map_vars.map_script_args[map_script_id]['small_cluster_size'];
		medium_cluster_size = progress_map_vars.map_script_args[map_script_id]['medium_cluster_size']
		big_cluster_size = progress_map_vars.map_script_args[map_script_id]['big_cluster_size'];
		
		var max_zoom = parseInt(progress_map_vars.map_script_args[map_script_id]['max_zoom']);

		plugin_map.gmap3({
			get: {
				name: 'marker',
				all: true,
				callback: function(objs){
					
					if(objs && typeof MarkerClusterer !== 'undefined'){
						
						markerCluster = new MarkerClusterer(mapObject, objs, {
							gridSize: parseInt(progress_map_vars.map_script_args[map_script_id]['grid_size']),
							styles: [{
										url: progress_map_vars.map_script_args[map_script_id]['small_cluster_icon'],
										height: parseInt(small_cluster_size.split('x')[0]),
										width: parseInt(small_cluster_size.split('x')[1]),
										textColor: progress_map_vars.map_script_args[map_script_id]['cluster_text_color'],
										textSize: 11,
										fontWeight: 'normal',
										fontFamily: 'sans-serif'
									}, {
										url: progress_map_vars.map_script_args[map_script_id]['medium_cluster_icon'],
										height: parseInt(medium_cluster_size.split('x')[0]),
										width: parseInt(medium_cluster_size.split('x')[1]),
										textColor: progress_map_vars.map_script_args[map_script_id]['cluster_text_color'],
										textSize: 13,	
										fontWeight: 'normal',								
										fontFamily: 'sans-serif'
									}, {
										url: progress_map_vars.map_script_args[map_script_id]['big_cluster_icon'],
										height: parseInt(big_cluster_size.split('x')[0]),
										width: parseInt(big_cluster_size.split('x')[1]),
										textColor: progress_map_vars.map_script_args[map_script_id]['cluster_text_color'],
										textSize: 15,		
										fontWeight: 'normal',							
										fontFamily: 'sans-serif'
									}],
							zoomOnClick: true, //(progress_map_vars.map_script_args[map_script_id]['initial_map_style'] == 'TERRAIN') ? true : false,	
							maxZoom: max_zoom,
							minimumClusterSize: 2,
							averageCenter: true,
							ignoreHidden: true,	
							title: progress_map_vars.cluster_text, //@since 2.8
							enableRetinaIcons: (progress_map_vars.map_script_args[map_script_id]['retinaSupport'] == 'true') ? true : false,	
						});					
						
						var cluster_xhr;
						
						// On cluster click, Hide and show overlays depending on markers positions	
						
						google.maps.event.addListener(markerCluster, 'click', function(cluster){

							var current_zoom = mapObject.getZoom();	
							
							// Get cluster position and convert it to XY
							var scale = Math.pow(2, current_zoom);
							var nw = new google.maps.LatLng(mapObject.getBounds().getNorthEast().lat(), mapObject.getBounds().getSouthWest().lng());
							var worldCoordinateNW = mapObject.getProjection().fromLatLngToPoint(nw);
							var worldCoordinate = mapObject.getProjection().fromLatLngToPoint(cluster.center_);
							var pixelOffset = new google.maps.Point(Math.floor((worldCoordinate.x - worldCoordinateNW.x) * scale), Math.floor((worldCoordinate.y - worldCoordinateNW.y) * scale));
							var mapposition = plugin_map.position();
	
							var count_li = 0;						
							
							if(current_zoom >= max_zoom || (progress_map_vars.map_script_args[map_script_id]['initial_map_style'] == 'TERRAIN' && current_zoom >= 15)) {
								
								if(typeof cluster.markers_ !== 'undefined')
									var cluster_markers = cluster.markers_;
								else var cluster_markers = cluster.getMarkers();									

								// @since 2.5 ====							
								var clustred_post_ids = [];
								// ===============
								
								if(typeof cluster_markers !== 'undefined'){
									
									for (var i = 0; i < cluster_markers.length; i++ ){
										
										if(cluster_markers[i].visible == true){
											
											count_li++;
											
											// @since 2.5 ====
											clustred_post_ids.push(cluster_markers[i].id);										
											// ===============
											
										}
										
									}
									
									jQuery('div.cluster_posts_widget_'+map_id).html('<div class="blue_cloud"></div>');
										
									if(count_li > 0){
										
										// @since 2.5 ====
										jQuery('div.cluster_posts_widget_'+map_id).removeClass('flipOutX');
										jQuery('div.cluster_posts_widget_'+map_id).addClass('cspm_animated flipInX').css('display', 'block');
										jQuery('div.cluster_posts_widget_'+map_id).css({left: (pixelOffset.x + mapposition.left + 40 + 'px'), top: (pixelOffset.y + mapposition.top - 32 + 'px')});	
					
										if(cluster_xhr && cluster_xhr.readystate != 4){
											cluster_xhr.abort();
										}
										
										cluster_xhr = jQuery.post(
											progress_map_vars.ajax_url,
											{
												action: 'cspm_load_clustred_markers_list',
												post_ids: clustred_post_ids,
												map_object_id: progress_map_vars.map_script_args[map_script_id]['map_object_id'],
												map_settings: progress_map_vars.map_script_args[map_script_id]['map_settings'],																	
												light_map: light_map
											},
											function(data){	
												
												jQuery('div.cluster_posts_widget_'+map_id).html(data);
												
												/**
												 * Call custom scroll bar for infowindow */
												
												if(typeof jQuery('div.cluster_posts_widget_'+map_id).mCustomScrollbar === 'function'){
																											
													jQuery('div.cluster_posts_widget_'+map_id).mCustomScrollbar({
														autoHideScrollbar:true,
														mouseWheel:{
															enable: true,
															preventDefault: true,
														},
														theme:"dark-thin"
													});		
												
												}
												
											}
										);
										
										jQuery('div.cluster_posts_widget_'+map_id+' ul li').livequery('click', function(){
											
											var id = jQuery(this).attr('id');
											var i = jQuery('li#'+map_id+'_list_items_'+id).attr('value');
											cspm_call_carousel_item(jQuery('ul#codespacing_progress_map_carousel_'+map_id).data('jcarousel'), i);
											cspm_carousel_item_hover_style('li.carousel_item_'+i+'_'+map_id, map_id);
											
										});
										
									}else jQuery('div.cluster_posts_widget_'+map_id).css({'display':'none'});
																			
								}														
								
								cspm_zoom_in_and_out(plugin_map);
								
							}
							
							
							/**
							 * Fix an issue where the cluster disappers when reaching the max_zoom
							 *
							 * @since 2.6.5
							 */
							setTimeout(function(){						
								current_zoom = mapObject.getZoom();	
								if(progress_map_vars.map_script_args[map_script_id]['initial_map_style'] != 'TERRAIN' && current_zoom > max_zoom){
									mapObject.setZoom(max_zoom);
								}
							}, 100);

						});
					
					}
					
				}
			}
			
		});
				
		return markerCluster;
	
	}
	
	function cspm_zoom_in_and_out(plugin_map){
		
		var mapObject = plugin_map.gmap3('get');
		
		mapObject.setZoom(mapObject.getZoom() - 1);
		mapObject.setZoom(mapObject.getZoom() + 1);
		
	}
	
	function cspm_simple_clustering(plugin_map, map_id){
		
		var mapObject = plugin_map.gmap3('get');
		
		if(typeof MarkerClusterer !== 'undefined')
			var markerCluster = new MarkerClusterer(mapObject);
		
    	cspm_zoom_in_and_out(plugin_map);
							
	}
	
	/**
	 * This will load the carousel item details
	 */
	function cspm_ajax_item_details(post_id, map_id){
        
		/**
		 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
		 * @since 3.0 */
		var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';

		jQuery.post(
			progress_map_vars.ajax_url,
			{
				action: 'cspm_load_carousel_item',
				post_id: post_id,
				map_object_id: progress_map_vars.map_script_args[map_script_id]['map_object_id'],
				map_settings: progress_map_vars.map_script_args[map_script_id]['map_settings'],
				items_view: progress_map_vars.map_script_args[map_script_id]['items_view'],
			},
			function(data){
				jQuery("li#"+map_id+"_list_items_"+post_id).addClass('cspm_animated fadeIn').html(data);															
			}
		);
	
	}
	
	/**
	 * Animate the selected marker 
	 */
	function cspm_animate_marker(plugin_map, map_id, post_id){
        
		/**
		 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
		 * @since 3.0 */
		var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';

		plugin_map.gmap3({
			get: {
				name: 'marker',
				tag: 'post_id__'+post_id,
				callback: function(marker){
					
					if(typeof marker !== 'undefined' && marker.visible == true){
						
						var is_child = marker.is_child;	
						var marker_infobox = 'div.cspm_infobox_container.infobox_'+post_id+'[data-map-id='+map_id+'][data-is-child='+is_child+']';
						jQuery('div.cspm_infobox_container[data-map-id='+map_id+']').removeClass('cspm_current_bubble');

						if(progress_map_vars.map_script_args[map_script_id]['markerAnimation'] == 'pulsating_circle'){
								
							var mapObject = plugin_map.gmap3('get');
	
							// Get marker position and convert it to XY
							var scale = Math.pow(2, mapObject.getZoom());
							var nw = new google.maps.LatLng(mapObject.getBounds().getNorthEast().lat(), mapObject.getBounds().getSouthWest().lng());
							var worldCoordinateNW = mapObject.getProjection().fromLatLngToPoint(nw);
							var worldCoordinate = mapObject.getProjection().fromLatLngToPoint(marker.position);
							var pixelOffset = new google.maps.Point(Math.floor((worldCoordinate.x - worldCoordinateNW.x) * scale), Math.floor((worldCoordinate.y - worldCoordinateNW.y) * scale));
							var mapposition = plugin_map.position();
		
							jQuery('div#pulsating_holder.'+map_id+'_pulsating').css({'display':'block', 'left':(pixelOffset.x + mapposition.left - 15 + 'px'), 'top':(pixelOffset.y + mapposition.top - 18 + 'px')});
							setTimeout(function(){
								jQuery('div#pulsating_holder.'+map_id+'_pulsating').css('display', 'none');
								jQuery(marker_infobox).addClass('cspm_current_bubble');
							},1500);
							
						}else if(progress_map_vars.map_script_args[map_script_id]['markerAnimation'] == 'bouncing_marker'){
						 								
							marker.setAnimation(google.maps.Animation.BOUNCE);
							setTimeout(function(){
								marker.setAnimation(null);
								jQuery(marker_infobox).addClass('cspm_current_bubble');
							},1500);
							
						}else if(progress_map_vars.map_script_args[map_script_id]['markerAnimation'] == 'flushing_infobox'){						
							
							jQuery('div.cspm_infobox_container[data-map-id='+map_id+']').removeClass('cspm_animated flash');
							setTimeout(function(){								
								jQuery(marker_infobox).addClass('cspm_animated flash');
								jQuery(marker_infobox).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){jQuery(marker_infobox).removeClass('flash');});
							}, 600);
							
						}

					}
					
				}
			}
		});
	
	}

	// Zoom-in function
	function cspm_zoom_in(map_id, selector, plugin_map){
        
		/**
		 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
		 * @since 3.0 */
		var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';

		selector.livequery('click', function(){
			
			var map = plugin_map.gmap3("get");
			var current_zoom = map.getZoom();
			
			if(current_zoom < progress_map_vars.map_script_args[map_script_id]['max_zoom'])
	    		map.setZoom(current_zoom + 1);

			jQuery('div[class^=cluster_posts_widget]').removeClass('flipInX');
			jQuery('div[class^=cluster_posts_widget]').addClass('cspm_animated flipOutX');

		});
		
	}

	// Zoom-out function
	function cspm_zoom_out(map_id, selector, plugin_map){
        
		/**
		 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
		 * @since 3.0 */
		var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';

		selector.livequery('click', function(){
					
			var map = plugin_map.gmap3("get");
			var current_zoom = map.getZoom();
			
			if(current_zoom > progress_map_vars.map_script_args[map_script_id]['min_zoom'])
				map.setZoom(current_zoom - 1);

			jQuery('div[class^=cluster_posts_widget]').removeClass('flipInX');
			jQuery('div[class^=cluster_posts_widget]').addClass('cspm_animated flipOutX');
			
		});
		
	}
	
//============================//
//==== Carousel functions ====//
//============================//

	/**
	 * Initialize carousel
	 *
	 * @updated 3.0
	 */
	function cspm_init_carousel(carousel_size, map_id){
        
		/**
		 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
		 * @since 3.0 */
		var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';

		var carousel_id = map_id;

		if(progress_map_vars.map_script_args[map_script_id]['show_carousel'] == 'true' && map_layout[map_id] != 'fullscreen-map' && map_layout[map_id] != 'fit-in-map'){
			
			var vertical_value = false;	
			var dimension = (progress_map_vars.map_script_args[map_script_id]['items_view'] == 'listview') ? progress_map_vars.map_script_args[map_script_id]['horizontal_item_width'] : progress_map_vars.map_script_args[map_script_id]['vertical_item_width'];
			
			if(map_layout[map_id] == "mr-cl" || map_layout[map_id] == "ml-cr"  || map_layout[map_id] == "map-tglc-right"  || map_layout[map_id] == "map-tglc-left"){
				var vertical_value = true;
				var dimension = (progress_map_vars.map_script_args[map_script_id]['items_view'] == 'listview') ? progress_map_vars.map_script_args[map_script_id]['horizontal_item_height'] : progress_map_vars.map_script_args[map_script_id]['vertical_item_height'];
			}
			
			var size = {}; 
			var auto_scroll_option = {}; 
			
			if(progress_map_vars.map_script_args[map_script_id]['number_of_items'] != '')
				var size = { size: parseInt(progress_map_vars.map_script_args[map_script_id]['number_of_items']) };
			else if(carousel_size != null)
				var size = { size: parseInt(carousel_size) };
				
			var default_options = {
				
				scroll: eval(progress_map_vars.map_script_args[map_script_id]['carousel_scroll']),
				wrap: progress_map_vars.map_script_args[map_script_id]['carousel_wrap'],
				auto: eval(progress_map_vars.map_script_args[map_script_id]['carousel_auto']),		
				initCallback: cspm_carousel_init_callback,
				itemFallbackDimension: parseInt(dimension),
				itemLoadCallback: cspm_carousel_itemLoadCallback,
				rtl: eval(progress_map_vars.map_script_args[map_script_id]['carousel_mode']),
				animation: progress_map_vars.map_script_args[map_script_id]['carousel_animation'],
				easing: progress_map_vars.map_script_args[map_script_id]['carousel_easing'],
				vertical: vertical_value
			
			};
			
			if(eval(progress_map_vars.map_script_args[map_script_id]['carousel_auto']) > 0)
				var auto_scroll_option = { itemFirstInCallback: { onAfterAnimation:  cspm_carousel_item_request } }
			else var auto_scroll_option = { itemFirstInCallback: cspm_carousel_itemFirstInCallback, }
		
			var carousel_options = jQuery.extend({}, default_options, size, auto_scroll_option);
			
			// Init jcarousel
			jQuery('ul#codespacing_progress_map_carousel_'+carousel_id).jcarousel(carousel_options);	

		}else return false;		
		
	}
	
	function cspm_carousel_itemFirstInCallback(carousel, item, idx, state) {
		
		var map_id = carousel.container.context.id.split('codespacing_progress_map_carousel_')[1];
		
		if(state == "prev" || state == "next"){
			
			var item_value = item.value;

			cspm_carousel_item_hover_style('li.carousel_item_'+item_value+'_'+map_id, map_id);
				
		}
		
		return false;
		
	}

	/**
	 * Load Items in the screenview
	 *
	 * @Updated 2.7.1 */
	 
	function cspm_carousel_itemLoadCallback(carousel){
	
		var map_id = carousel.container.context.id.split('codespacing_progress_map_carousel_')[1];
		
		for(var i = parseInt(carousel.first); i <= parseInt(carousel.last); i++){
			
			var post_id = jQuery('li.jcarousel-item-'+ i +'[class*=_'+map_id+']').attr('data-post-id');
			
			/**
			 * Check if the requested items already exist */
			 
			if ( jQuery('li#'+map_id+'_list_items_'+post_id).has('div.cspm_spinner').length ){
			
				/**
				 * Get items details */
				 
				cspm_ajax_item_details(post_id, map_id);	
				
			}
			
		}
		
	}

	// Carousel callback function
	function cspm_carousel_init_callback(carousel){
		
		var carousel_id = carousel.container.context.id.split('codespacing_progress_map_carousel_')[1];
		
		var map_id = carousel_id;
        
		/**
		 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
		 * @since 3.0 */
		var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';
		
		// Move the carousel with scroll wheel
		if(progress_map_vars.map_script_args[map_script_id]['scrollwheel_carousel'] == 'true'){

			jQuery('ul#codespacing_progress_map_carousel_'+carousel_id).mousewheel(function(event, delta) {
					
				if (delta > 0){
					carousel.prev();
					setTimeout(function(){ cspm_carousel_item_request(carousel, 0); }, 600);
					return false;
				}else if (delta < 0){
					carousel.next();
					setTimeout(function(){ cspm_carousel_item_request(carousel, 0); }, 600);
					return false;
				}
					
			});
			
		}
		
		// Touch swipe option
		if(progress_map_vars.map_script_args[map_script_id]['touchswipe_carousel'] == 'true' && typeof jQuery('ul#codespacing_progress_map_carousel_'+carousel_id).swipe === 'function'){

			jQuery('ul#codespacing_progress_map_carousel_'+carousel_id).swipe({ 
				
				//Generic swipe handler for all directions
				swipe:function(event, direction, distance, duration, fingerCount) {

					if(map_layout[map_id] == 'mu-cd' || map_layout[map_id] == 'md-cu' || map_layout[map_id] == 'm-con' || map_layout[map_id] == 'fullscreen-map-top-carousel' || map_layout[map_id] == 'fit-in-map-top-carousel'){
						
						if(direction == 'left'){
							carousel.next();
							setTimeout(function(){ cspm_carousel_item_request(carousel, 0); }, 600);
							return false;
						}else if(direction == 'right'){
							carousel.prev();
							setTimeout(function(){ cspm_carousel_item_request(carousel, 0); }, 600);
							return false;
						}
						
					}else if(map_layout[map_id] == 'ml-cr' || map_layout[map_id] == 'mr-cl'){
						
						if(direction == 'up'){
							carousel.next();
							setTimeout(function(){ cspm_carousel_item_request(carousel, 0); }, 600);
							return false;
						}else if(direction == 'down'){
							carousel.prev();
							setTimeout(function(){ cspm_carousel_item_request(carousel, 0); }, 600);
							return false;
						}
						
					}															
					
				},
				threshold:0				
			});
			
		}
		
		// Pause autoscrolling if the user moves with the cursor over the carousel
		carousel.clip.hover(function() {
			carousel.stopAuto();
		}, function() {
			carousel.startAuto();
		});
		
		// Next button 
		carousel.buttonNext.bind('click', function() {
			setTimeout(function(){ cspm_carousel_item_request(carousel, 0); }, 600);
		});
		
		// Previous button
		carousel.buttonPrev.bind('click', function() {		
			setTimeout(function(){ cspm_carousel_item_request(carousel, 0); }, 600);
		});
		
	}					
	
	function cspm_carousel_item_request(carousel, item_value){

		var map_id = carousel.container.context.id.split('codespacing_progress_map_carousel_')[1];
        
		/**
		 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
		 * @since 3.0 */
		var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';

		var plugin_map = jQuery('div#codespacing_progress_map_div_'+map_id);
		
		var firstItem = parseInt(carousel.first);
		
		var carouselItemValue = (eval(progress_map_vars.map_script_args[map_script_id]['carousel_auto']) > 0) ? 0 : parseInt(item_value);

		if(carouselItemValue != 0 && carouselItemValue != firstItem) 
			firstItem = carouselItemValue;
		 
		var overlay_id = jQuery('.jcarousel-item-'+ firstItem ).attr('class').split(' ')[0];

		if(overlay_id){
			
			var item_latlng = jQuery('li#'+map_id+'_list_items_'+overlay_id).attr('name');

			if(item_latlng && typeof item_latlng !== 'undefined'){
					
				var split_item_latlng = item_latlng.split('_');
				var this_lat = split_item_latlng[0].replace(/\"/g, '');
				var this_lng = split_item_latlng[1].replace(/\"/g, '');
					
				cspm_carousel_item_hover_style('li#'+map_id+'_list_items_'+overlay_id, map_id);
				
				cspm_center_map_at_point(plugin_map, map_id, this_lat, this_lng, 'zoom');
				
				setTimeout(function(){cspm_animate_marker(plugin_map, map_id, overlay_id);},200);
			
			}
			
		}
				
	}

	/**
	 * Call carousel items								
	 */

	function cspm_call_carousel_item(carousel, id){
		
		carousel.scroll(jQuery.jcarousel.intval(id));
		
		return false;
		
	}
	
	/**
	 * Add different style for the first and/or the selected item in the carousel
	 */
	 
	function cspm_carousel_item_hover_style(item_selector, map_id){								
        
		/**
		 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
		 * @since 3.0 */
		var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';

		jQuery('li[id^='+map_id+'_list_items_]').removeClass('cspm_carousel_first_item').css({'background-color':progress_map_vars.map_script_args[map_script_id]['items_background']});
		
		jQuery(item_selector).addClass('cspm_carousel_first_item').css({'background-color':progress_map_vars.map_script_args[map_script_id]['items_hover_background']});	
		
	}
	
	function cspm_rewrite_carousel(map_id, show_carousel, posts_to_retrieve){
        
		/**
		 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
		 * @since 3.0 */
		var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';

		if(show_carousel == "yes" && progress_map_vars.map_script_args[map_script_id]['show_carousel'] == "true" && map_layout[map_id] != 'fullscreen-map' && map_layout[map_id] != 'fit-in-map'){
	
			var carousel = jQuery('ul#codespacing_progress_map_carousel_'+map_id).data('jcarousel');
	
			if(typeof carousel !== 'undefined'){
				
				carousel.reset();
				
				var carousel_length = cspm_object_size(posts_to_retrieve);
		
				if(progress_map_vars.map_script_args[map_script_id]['items_view'] == "listview"){ 
				
					item_width = parseInt(progress_map_vars.map_script_args[map_script_id]['horizontal_item_width']);										
					item_height = parseInt(progress_map_vars.map_script_args[map_script_id]['horizontal_item_height']);
					item_css = progress_map_vars.map_script_args[map_script_id]['horizontal_item_css'];
					items_background  = progress_map_vars.map_script_args[map_script_id]['items_background'];
				
				}else if(progress_map_vars.map_script_args[map_script_id]['items_view'] == "gridview"){ 
				
					item_width = parseInt(progress_map_vars.map_script_args[map_script_id]['vertical_item_width']);
					item_height = parseInt(progress_map_vars.map_script_args[map_script_id]['vertical_item_height']);
					item_css = progress_map_vars.map_script_args[map_script_id]['vertical_item_css'];
					items_background  = progress_map_vars.map_script_args[map_script_id]['items_background'];
					
				}
	
				var count_loop = 0;
							
				for(var c = 0; c < carousel_length; c++){
					
					var post_id = posts_to_retrieve[c];
					var is_child = post_ids_and_child_status[map_id][post_lat_lng_coords[map_id][post_id]];
					
					var carousel_item = '';							

					carousel_item = '<li id="'+map_id+'_list_items_'+post_id+'" class="'+post_id+' carousel_item_'+(c+1)+'_'+map_id+' cspm_border_radius cspm_border_shadow" data-map-id="'+map_id+'" data-is-child="'+is_child+'" name="'+post_lat_lng_coords[map_id][post_id]+'" value="'+(c+1)+'" data-post-id="'+post_id+'" style="width:'+item_width+'px; height:'+item_height+'px; background-color:'+items_background+'; '+item_css+'">';
						carousel_item += '<div class="cspm_spinner"></div>';
					carousel_item += '</li>';

					carousel.add(c+1, carousel_item);
					
					count_loop++;
					
				}					
	
				cspm_init_carousel(carousel_length, map_id);
				
				return count_loop++;

			}
			
		}else return posts_to_retrieve.length;

	}

	function cspm_fullscreen_map(map_id){
		
		var screenWidth = window.innerWidth;
		var screenHeight = window.innerHeight;

		jQuery('div.codespacing_progress_map_area[data-map-id='+map_id+']').css({height : screenHeight, width : screenWidth});
	
	}
		
	function cspm_carousel_width(map_id){
		
		//var carouselWidth = jQuery('div.codespacing_progress_map_area[data-map-id='+map_id+']').width();
		var carouselWidth = jQuery('div#codespacing_progress_map_div_'+map_id).width();
		
		carouselWidth = parseInt(carouselWidth - 40);
		
		var carouselHalf = parseInt((-0) - (carouselWidth/ 2));

		jQuery('div.codespacing_progress_map_carousel_on_top[data-map-id='+map_id+']').css({width : carouselWidth, 'margin-left' : carouselHalf+'px'});
	
	}

	function cspm_fitIn_map(map_id){
        
		/**
		 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
		 * @since 3.0 */
		var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';

		var parentHeight = jQuery('div.codespacing_progress_map_area[data-map-id='+map_id+']').parent().height();

		if(parentHeight == 0) parentHeight = progress_map_vars.map_script_args[map_script_id]['layout_fixed_height'];

		jQuery('div.codespacing_progress_map_area[data-map-id='+map_id+']').css({height : parentHeight});
	
	}
	
	function cspm_set_markers_visibility(plugin_map, map_id, value, j, post_ids_and_categories, posts_to_retrieve, retrieve_posts){

		if(retrieve_posts == true){
			
			// @value: Refers to the category ID
			if(value != null){
				// Show markers comparing with the category ID (faceted search case)
				plugin_map.gmap3({
					get: {
						name: "marker",
						all: true,
						callback: function(objs){
							
							if(objs){
								
								jQuery.each(objs, function(i, obj){

									if(typeof obj.post_id !== 'undefined' && obj.post_id != 'user_location' && jQuery.inArray(value, post_ids_and_categories['post_id_'+obj.post_id][0]) > -1){
										
										if(typeof obj.setVisible === 'function')
											obj.setVisible(true);
											
										if(jQuery.inArray(parseInt(obj.post_id), posts_to_retrieve) === -1){
											posts_to_retrieve[j] = parseInt(obj.post_id);
											j++;
										}	
											
									}
									
									/**
									 * Show the user's marker for GeoTargeting
									 * @since 2.7.4 */
									 
									if(obj.post_id == 'user_location' && typeof obj.setVisible === 'function')
										obj.setVisible(true);
									
								});
								
							}
				
						}
					}
				});
				
			}else{
				
				/**
				 * Show markers within the search area/radius (Search form case) */
				 
				plugin_map.gmap3({
					get: {
						name: "marker",
						all: true,
						callback: function(objs){
							
							if(objs){
								
								jQuery.each(objs, function(i, obj){
									
									if(typeof obj.setVisible === 'function' && (jQuery.inArray(parseInt(obj.post_id), posts_to_retrieve) > -1))
										obj.setVisible(true);	
										
								});
								
							}
							
						}
					}
				});
			
			}
		
		/**
		 * Show all markers */
		 
		}else{
			
			plugin_map.gmap3({
				get: {
					name: "marker",
					all: true,
					callback: function(objs){
						
						if(objs){
							
							jQuery.each(objs, function(i, obj){
								
								if(typeof obj.setVisible === 'function') 
									obj.setVisible(true);
																
								if(typeof obj.post_id !== 'undefined' && obj.post_id != 'user_location')										
									posts_to_retrieve[j] = parseInt(obj.post_id);
									
								j++;
								
							});
							
						}
						
					}
				}
			});

		}
		
		return posts_to_retrieve;
		
	}

	// Get Two Address's And Return Distance In Between
	// @distance_unit = imperial / metric 
	function cspm_get_distance(origin_lat, origin_lng, destination_lat, destination_lng, distance_unit){
		
		var earth_radius = (distance_unit == "metric") ? 6380 : (6380*0.621371192);
		
		return distance = Math.acos(Math.sin(cspm_deg2rad(destination_lat))*Math.sin(cspm_deg2rad(origin_lat))+Math.cos(cspm_deg2rad(destination_lat))*Math.cos(cspm_deg2rad(origin_lat))*Math.cos(cspm_deg2rad(destination_lng)-cspm_deg2rad(origin_lng)))*earth_radius;
		
	}
	
	/**
	 * Display user position on the map
	 *
	 * @since 2.8
	 * @updated 3.2 [added retina support for user marker icon]
	 */
	function cspm_geolocate(plugin_map, map_id, show_user, user_marker_icon, marker_size, user_circle, zoom, show_error){
        
		/**
		 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
		 * @since 3.0 */
		var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';

		var mapObject = plugin_map.gmap3("get");
		var current_zoom = mapObject.getZoom();		

		// Marker image size
		var marker_img_width = (progress_map_vars.map_script_args[map_script_id]['retinaSupport'] == "true") ? parseInt(marker_size.split('x')[0])/2 : parseInt(marker_size.split('x')[0]);
		var marker_img_height = (progress_map_vars.map_script_args[map_script_id]['retinaSupport'] == "true") ? parseInt(marker_size.split('x')[1])/2 : parseInt(marker_size.split('x')[1]);

		// Marker image anchor point
		var anchor_y = marker_img_height/2;
		var anchor_x = marker_img_width/2;	
		var anchor_point = null;

		anchor_point = new google.maps.Point(anchor_x, anchor_y);
		
		// Add retina support
		marker_icon = new google.maps.MarkerImage(user_marker_icon, null, null, anchor_point, new google.maps.Size(marker_img_width,marker_img_height));					
		
		plugin_map.gmap3({
			getgeoloc:{
				callback: function(latLng){
				  
				  if(latLng){
					  						
					plugin_map.gmap3({						  
					  map:{
						options:{
							center: latLng,
							zoom: parseInt(zoom)
						},
						onces: {
							tilesloaded: function(map){
								
								/**
								 * Add a marker indicating the user location */
								
								if(show_user){

									setTimeout(function(){

										plugin_map.gmap3({
											marker:{
												latLng: latLng,
												tag: 'cspm_user_marker_'+map_id,
												options:{
													icon: marker_icon,
													post_id: 'user_location',
												},
												events:{
													click: function(marker, event, elements){
														
														/**
														 * Nearby points of interets.
														 * Add the coordinates of this marker to the list of Proximities ...
														 * ... in order to use them (latLng) to display nearby points of interest ...
														 * ... of that marker 
														 * @since 3.2 */
														 
														if(progress_map_vars.map_script_args[map_script_id]['nearby_places_option'] == 'true'){
														
															jQuery('li.cspm_proximity_name[data-map-id='+map_id+']').attr('data-marker-latlng', marker.position).attr('data-marker-post-id', 'user_location').removeClass('selected');	
																														
															var map_message_widget = jQuery('div.cspm_map_green_msg_widget[data-map-id='+map_id+']');
															
															map_message_widget.text(progress_map_vars.new_marker_selected_msg).removeClass('fadeOut').addClass('cspm_animated fadeIn').css({'display':'block'});														
															setTimeout(function(){ 
																map_message_widget.removeClass('fadeIn').addClass('fadeOut');
																setTimeout(function(){
																	map_message_widget.css({'display':'none'});
																}, 500);
															}, 3000);
																														
														}
													}
												},																																																		
											},											
											circle:{
												options:{
													center: latLng,
													radius : parseInt(user_circle*1000),
													fillColor : progress_map_vars.map_script_args[map_script_id]['user_circle_fillColor'],
													fillOpacity: progress_map_vars.map_script_args[map_script_id]['user_circle_fillOpacity'],
													strokeColor : progress_map_vars.map_script_args[map_script_id]['user_circle_strokeColor'],
													strokeOpacity: progress_map_vars.map_script_args[map_script_id]['user_circle_strokeOpacity'],
													strokeWeight: parseInt(progress_map_vars.map_script_args[map_script_id]['user_circle_strokeWeight']),
													editable: false,
												},
											}
										});
										
									}, 500);
								
								}	
								
							}
						}
					  }
					});																		
				  
				  }else if(show_error){
					
					/**
					 * More info at https://support.google.com/maps/answer/152197
					 * Deprecate Geolocation API: https://developers.google.com/web/updates/2016/04/geolocation-on-secure-contexts-only */
					
					alert(progress_map_vars.geoErrorTitle + '\n\n' + progress_map_vars.geoErrorMsg);
					console.log('PROGRESS MAP: ' + progress_map_vars.geoDeprecateMsg);
					  
				  }
				  
				}
			},							
		});
		
	}
	
	/**
	 * Get User marker on the map
	 *
	 * @since 2.8
	 */
	function cspm_get_user_marker(plugin_map, map_id){

		plugin_map.gmap3({
		  get: {
			name:"marker",
			tag: 'cspm_user_marker_'+map_id,
			callback: function(objs){
				if(objs)
				  return objs;
			}
		  }
		});

	}
	
	/**
	 * This will detect if the Street View panorama is activated
	 * @since 2.7
	 */
	function cspm_is_panorama_active(plugin_map){
		
		var mapObject = plugin_map.gmap3("get");
		
		if(typeof mapObject.getStreetView === "function"){
			
			var streetView = mapObject.getStreetView();

			return streetView.getVisible();
			
		}else return false;
								
	}

	function cspm_center_map_at_point(plugin_map, map_id, latitude, longitude, effect){
        
		/**
		 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
		 * @since 3.0 */
		var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';

		var mapObject = plugin_map.gmap3("get");
		var current_zoom = mapObject.getZoom();
		var latLng = new google.maps.LatLng(latitude, longitude);
		
		if(typeof progress_map_vars.map_script_args[map_script_id]['carousel_map_zoom'] !== 'undefined'){
			var carousel_map_zoom = progress_map_vars.map_script_args[map_script_id]['carousel_map_zoom'];
		}else var carousel_map_zoom = current_zoom;
		
		if(effect == 'zoom' && current_zoom != parseInt(carousel_map_zoom))
			mapObject.setZoom(parseInt(carousel_map_zoom));
		else if(effect == 'resize')
			google.maps.event.trigger(mapObject, 'resize');
			
		mapObject.panTo(latLng);
		mapObject.setCenter(latLng);
		
	}
	
	/**
	 * Pan the map X pixels to fit infobox
	 *
	 * @since 2.8
	 */
	 
	function cspm_pan_map_to_fit_infobox(plugin_map, map_id, infobox){

		var mapObject = plugin_map.gmap3('get');
		
		var map_selector = 'div#codespacing_progress_map_div_'+map_id;
		var map_container_width = jQuery(map_selector).width();
		var map_container_height = jQuery(map_selector).height();	
		var map_container_top = jQuery(map_selector).position().top;	
		var map_container_left = jQuery(map_selector).position().left;	

		var infobox_container_width = jQuery(infobox.selector).width();
		var infobox_container_height = jQuery(infobox.selector).height();		
		var infobox_container_top = jQuery(infobox.selector).position().top;	
		var infobox_container_left = jQuery(infobox.selector).position().left;	
		  
		if (infobox_container_top > map_container_top) {

			var left = 0;
			var top = -(infobox_container_height/2);
			
			mapObject.panBy(left, top);
			
		}
		
	}

	function cspm_is_bounds_contains_marker(plugin_map, latitude, longitude){
		
		var mapObject = plugin_map.gmap3('get');
		var myLatlng = new google.maps.LatLng(latitude, longitude);
		return mapObject.getBounds().contains(myLatlng);		
							
	}
		
	var cspm_requests = {};
	var cspm_bubbles = {};
	var cspm_child_markers = {};
	
	function cspm_draw_multiple_infoboxes(plugin_map, map_id, infobox_html_content, infobox_type, carousel){
        
		/**
		 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
		 * @since 3.0 */
		var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';

		plugin_map.gmap3({
			get: {
				name: 'marker',
				all:  true,
				callback: function(objs) {				
									
					for(var i = 0; i < objs.length; i++){	
						
						var this_marker_object = objs[i];														
						var post_id = parseInt(objs[i].post_id);
						
						if(post_id != '' && post_id != 'user_location' && !isNaN(post_id)){
													
							var latLng = objs[i].position;
							var icon_height = (typeof objs[i].icon === 'undefined' || typeof objs[i].icon.size === 'undefined' || typeof objs[i].icon.size.height === 'undefined') ? 38 : objs[i].icon.size.height;
							var is_child = objs[i].is_child;
							
							// Convert the LatLng object to array
							var lat = latLng.lat();
							var lng = latLng.lng();
							
							// if the marker is within the viewport of the map
							if(cspm_is_bounds_contains_marker(plugin_map, lat, lng) && objs[i].getMap() != null && objs[i].visible == true){
								
								var this_infobox_div = jQuery('div.infobox_'+post_id+'.cspm_infobox_'+map_id+'[data-is-child='+is_child+']');
	
								// If the infobox was already created ...
								if(jQuery.contains(document.body, this_infobox_div[0])){
									
									// ... Set its position to the top of the marker
									cspm_infobox_set_position(plugin_map, this_infobox_div, latLng, icon_height, this_marker_object);
									
									var infobox_link_target = this_infobox_div.attr('data-infobox-link-target'); //@since 2.8.6
									
								// If the infobox not created ...
								}else{
									
									// 1. Create the marker infobox
									var this_infobox_div = infobox_html_content;
									this_infobox_div = this_infobox_div.split('<div class="cspm_infobox_container cspm_border_shadow cspm_infobox_multiple cspm_infobox_'+map_id+' '+infobox_type);
									this_infobox_div = jQuery('<div data-is-child="'+is_child+'" class="cspm_infobox_container cspm_border_shadow cspm_infobox_multiple cspm_infobox_'+map_id+' '+infobox_type+' infobox_'+post_id+''+this_infobox_div[1]);

									// 2. Append the infobox to the map
									jQuery(plugin_map.selector).parent().append(this_infobox_div);
									
									var infobox_link_target = this_infobox_div.attr('data-infobox-link-target'); //@since 2.8.6
									
									// 3. Set the position of the infobox on to of the marker
									cspm_infobox_set_position(plugin_map, this_infobox_div, latLng, icon_height, this_marker_object);
									
									// 4. Save the ajax requests in an array
									cspm_bubbles[map_id].push(post_id);
									cspm_child_markers[map_id].push(is_child);
									cspm_requests[map_id].push(jQuery.post(
										progress_map_vars.ajax_url,
										{
											action: 'cspm_infobox_content',
											post_id: post_id,
											map_object_id: progress_map_vars.map_script_args[map_script_id]['map_object_id'],
											map_settings: progress_map_vars.map_script_args[map_script_id]['map_settings'],											
											infobox_type: infobox_type,
											map_id: map_id,
											status: 'cspm_infobox_multiple',
											carousel: carousel,
											infobox_link_target: infobox_link_target											
										}
									));
									
								}															
							
							// Hide the infobox when the marker is outside the viewport of the map	
							}else jQuery('div.infobox_'+post_id+'.cspm_infobox_'+map_id+'[data-is-child='+is_child+']').fadeOut();	

						}
						
						// Detect the end of the loop
						if(i == (objs.length-1)){
							// If there was any new infoboxes created
							if(cspm_bubbles[map_id].length > 0){
								// Load their content just after ajax requests were finished
								var cspm_defer = jQuery.when.apply(jQuery, cspm_requests[map_id]);
								cspm_defer.done(function(){
									if(cspm_requests[map_id].length == 1){
										if(arguments[1] == 'success')
											jQuery('div.infobox_'+cspm_bubbles[map_id][0]+'.cspm_infobox_'+map_id+'[data-is-child='+cspm_child_markers[map_id][0]+']').html(arguments[0]);		
									}else if(cspm_requests[map_id].length > 1){
										jQuery.each(arguments, function(index, responseData){ 
											if(responseData.length > 0 && responseData[1] == 'success')
												jQuery('div.infobox_'+cspm_bubbles[map_id][index]+'.cspm_infobox_'+map_id+'[data-is-child='+cspm_child_markers[map_id][index]+']').html(responseData[0]);		
										});
									}
								});
							}	
						}
						
					}
																												
				}
			}
		});
		
	}

	function cspm_draw_single_infobox(plugin_map, map_id, infobox_div, infobox_type, marker_obj, infobox_xhr, carousel){
        
		/**
		 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
		 * @since 3.0 */
		var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';

		var post_id = parseInt(marker_obj.post_id);
		var icon_height = (typeof marker_obj.icon === 'undefined' || typeof marker_obj.icon.size === 'undefined' || typeof marker_obj.icon.size.height === 'undefined') ? 38 : marker_obj.icon.size.height;
		
		// 1. Get the post_id from the infobox
		var infobox_post_id = infobox_div.attr('data-post-id');
			
		var infobox_link_target = infobox_div.attr('data-infobox-link-target'); //@since 2.8.6
								
		// 2. Compare the infobox post_id with the clicked marker post_id ...
		// ... to make sure not loading the content again
		if(infobox_post_id != post_id){
			
			var infobox_html = '<div class="blue_cloud"></div><div class="cspm_arrow_down '+infobox_type+'"></div>';
			infobox_div.html(infobox_html);															
			
			if(infobox_xhr && infobox_xhr.readystate != 4){
				infobox_xhr.abort();
			}
			
			infobox_xhr = jQuery.post(
				progress_map_vars.ajax_url,
				{
					action: 'cspm_infobox_content',
					post_id: post_id,
					map_object_id: progress_map_vars.map_script_args[map_script_id]['map_object_id'],
					map_settings: progress_map_vars.map_script_args[map_script_id]['map_settings'],					
					infobox_type: infobox_type,
					map_id: map_id,
					status: 'cspm_infobox_single',
					carousel: carousel,
					infobox_link_target: infobox_link_target //@since 2.8.6					
				},
				function(data){
					infobox_div.html(data);															
				}
			);
			
		}
		
		// 3. Update the infobox post_id attribute
		infobox_div.attr('data-post-id', post_id);
		
		// 4. Set the position on the infobox on top of the marker
		cspm_infobox_set_position(plugin_map, infobox_div, marker_obj.position, icon_height, marker_obj);
		
		return infobox_xhr;
	
	}
	
	function cspm_set_single_infobox_position(plugin_map, infobox_div){
		
		if(infobox_div.is(':visible')){
			
			var post_id = infobox_div.attr('data-post-id');
			
			plugin_map.gmap3({
			  get: {
				name: 'marker',
				tag: 'post_id__'+post_id,
				callback: function(obj){
					var icon_height = (typeof obj.icon === 'undefined' || typeof obj.icon.size === 'undefined' || typeof obj.icon.size.height === 'undefined') ? 38 : obj.icon.size.height;
					cspm_infobox_set_position(plugin_map, infobox_div, obj.position, icon_height, obj);
					// Hide the infobox when the marker was clustred or no more visible
					setTimeout(function(){ 
						if(obj.getMap() == null || obj.visible == false){
							infobox_div.addClass('cspm_animated fadeOutUp');					
							infobox_div.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
								infobox_div.hide().removeClass('cspm_animated fadeOutUp');
							});		
						}
					}, 400);
				}
			  }
			});									
		}	
			
	}
	
	function cspm_infobox_set_position(plugin_map, infobox_div, marker_position, marker_icon_height, marker_object){

		var mapObject = plugin_map.gmap3('get');
					
		var scale = Math.pow(2, mapObject.getZoom());
		
		var nw = new google.maps.LatLng(
			mapObject.getBounds().getNorthEast().lat(),
			mapObject.getBounds().getSouthWest().lng()
		);
		
		var worldCoordinateNW = mapObject.getProjection().fromLatLngToPoint(nw);
	
		/**
		 * [@marker_position] - The position (LatLng) of the marker */
	
		var worldCoordinate = mapObject.getProjection().fromLatLngToPoint(marker_position);
		
		var pixelOffset = new google.maps.Point(
			Math.floor((worldCoordinate.x - worldCoordinateNW.x) * scale),
			Math.floor((worldCoordinate.y - worldCoordinateNW.y) * scale)
		);

		/**
		 * [@mapPosition] - This will get the position of the map on the screen */
		 			   
		var mapPosition = plugin_map.position();
		
		var infobox_half_width = infobox_div.width() / 2;
		var margin_top = marker_icon_height + infobox_div.height();		
		
		/**
		 * Set the position of the infobox on the map.
		 * [@left] - The position of the marker (Horizontaly) ACCORING to the map's left position on the screen.
		 * [@top] - The position of the marker (Verticaly) ACCORING to the map's top position on the screen. 
		 * [@margin-left] - Move the infobox 50% of its size to the left. 
		 * [@margin-top] - Move the map to the top of the marker (According to the marker image size). */
		 	
		infobox_div.css({'left':(pixelOffset.x + mapPosition.left + 'px'),
  						 'top':(pixelOffset.y + mapPosition.top + 'px'), 
						 'margin-left':('-' + infobox_half_width + 'px'),
						 'margin-top':('-'+margin_top+'px')
					   }).fadeIn('slow');								   
		
	}
	
	// Count the number of visible markers in the map
	// @since 2.5
	function cspm_nbr_of_visible_markers(plugin_map){
		
		var count_posts = 0;
		
		plugin_map.gmap3({
			get: {
				name: 'marker',
				all:  true,
				callback: function(objs) {				
					for(var i = 0; i < objs.length; i++){	
						if(objs[i].visible == true){
							count_posts++;
						}
					}
				}
			}
		});		
		
		return count_posts;
		
	}
	
	// Hide all visible markers in the map
	// @since 2.5
	function cspm_hide_all_markers(plugin_map){
		
		var r = jQuery.Deferred();
		
		plugin_map.gmap3({
			get: {
				name: "marker",				
				all: true,	
				callback: function(objs){
					jQuery.each(objs, function(i, obj){
						if(typeof obj.setVisible === 'function' && obj.post_id != 'user_location')
							obj.setVisible(false);
					});
					r.resolve();
				}
			}
		});
				
		return r;
		
	}
	
	/**
	 * Get the bounds of a polygon, polyline ...
	 * [@latLngs] Array of latLng coordinates [[1,2],[1,2],[1,2],...]
	 *
	 * @since 3.0
	 */
	function cspm_get_latLngs_bounds(latLngs){

		var bounds = new google.maps.LatLngBounds();
		var i;
		
		// The Bermuda Triangle
		var LatLngsCoords = [];
		
		jQuery.each(latLngs, function(i, latLngs){
			var lat = latLngs[0];
			var lng = latLngs[1];
			LatLngsCoords.push(new google.maps.LatLng(lat, lng));
		});

		for (i = 0; i < LatLngsCoords.length; i++) {
		  bounds.extend(LatLngsCoords[i]);
		}
		
		return bounds;		
		
	}
	
	/**
	 * Get the center of a polygon, polyline ...
	 * [@latLngs] Array of latLng coordinates [[1,2],[1,2],[1,2],...]
	 *
	 * @since 3.0
	 */
	function cspm_get_latLngs_center(latLngs){

		var bounds = cspm_get_latLngs_bounds(latLngs)
		
		return bounds.getCenter();		
		
	}
	
	/**
	 * Check if bounds (polygon, polyline) are empty ...
	 * [@latLngs] Array of latLng coordinates [[1,2],[1,2],[1,2],...]
	 *
	 * @since 3.0
	 */
	function cspm_are_bounds_empty(latLngs){

		var bounds = cspm_get_latLngs_bounds(latLngs)
		
		return bounds.isEmpty();		
		
	}
	
	
	/**
	 * Zoom to address
	 *
	 * @since 3.0
	 */
	function cspm_zoom_to_address(map_id, plugin_map, address, autofit, map_zoom){

		plugin_map.gmap3({
			getlatlng:{
				address: address,
				callback: function(results, status){

					if (status == 'OK') {
						
						if(!results || typeof results[0].geometry.location === 'undefined')
							return;
					
						var mapObject = plugin_map.gmap3("get");
						
						if(!autofit){
							
							mapObject.setZoom(parseInt(map_zoom));
							mapObject.panTo(results[0].geometry.location);
							mapObject.setCenter(results[0].geometry.location);
							
						}else{
							
							if(typeof results[0].geometry.bounds !== 'undefined'){
								mapObject.fitBounds(results[0].geometry.bounds);
							}else{
								mapObject.panTo(results[0].geometry.location);
								mapObject.setCenter(results[0].geometry.location);
							}
							
						}
					
					}else if(status == 'ERROR'){
						
						alert('There was a problem contacting the Google servers. Try again.');
							
					}else if(status == 'INVALID_REQUEST'){
						
						console.log('PROGRESS MAP: This GeocoderRequest was invalid');
						
					}else if(status == 'OVER_QUERY_LIMIT'){
						
						console.log('PROGRESS MAP: The webpage has gone over the requests limit in too short a period of time. Try again later.');
						
					}else if(status == 'REQUEST_DENIED'){
						
						console.log('PROGRESS MAP: The webpage is not allowed to use the geocoder.');
						
					}else if(status == 'UNKNOWN_ERROR'){
						
						console.log('PROGRESS MAP: A geocoding request could not be processed due to a server error. The request may succeed if you try again.');
						
					}else if(status == 'ZERO_RESULTS'){
						
						alert('No result was found for "'+address+'"');
						
					}
					
				}
			}
		});	
		
	}
	
	
	function cspm_recenter_map(plugin_map, map_id){
        
		/**
		 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
		 * @since 3.0 */
		var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';
		
		var map_center = progress_map_vars.map_script_args[map_script_id]['center'].split(',');
		
		if(typeof map_center !== 'undefined'){
			
			var zoom = parseInt(progress_map_vars.map_script_args[map_script_id]['zoom']);
	
			var mapObject = plugin_map.gmap3("get");
					
			var latLng = new google.maps.LatLng(map_center[0], map_center[1]);
			
			mapObject.setZoom(zoom);
			mapObject.setCenter(latLng);
			
		}else console.log('PROGRESS MAP: There was a problem centring the map. Check your initial map center coordinates in "Progress Map => Map settings => Map center", then, try again.');
		
	}
		
//=========================//
//==== Other functions ====//
//=========================//
	
	// Remove duplicated emlements from an array
	// @since 2.5
	function cspm_remove_array_duplicates(array){
		var new_array = [];
		var i = 0;
		jQuery.each(array, function(index, element){
			if(jQuery.inArray(element, new_array) === -1){
				new_array[i] = element;	
				i++;
			}
		});
		return new_array;
	}

	function cspm_object_size(obj){
			
		var size = 0, key;
		for (key in obj) {
			if (obj.hasOwnProperty(key)) size++;
		}
		return size;
					
	}

	function cspm_strpos(haystack, needle, offset) {
		
	  // From: http://phpjs.org/functions
	  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	  // +   improved by: Onno Marsman
	  // +   bugfixed by: Daniel Esteban
	  // +   improved by: Brett Zamir (http://brett-zamir.me)
	  // *     example 1: strpos('Kevin van Zonneveld', 'e', 5);
	  // *     returns 1: 14
	  var i = (haystack + '').indexOf(needle, (offset || 0));
	  return i === -1 ? false : i;
	  
	}

	function cspm_deg2rad(angle) {
		
	  // From: http://phpjs.org/functions
	  // +   original by: Enrique Gonzalez
	  // +     improved by: Thomas Grainger (http://graingert.co.uk)
	  // *     example 1: deg2rad(45);
	  // *     returns 1: 0.7853981633974483
	  return angle * .017453292519943295; // (angle / 180) * Math.PI;
	
	}	
	
	function alerte(obj) {
		
		if (typeof obj == 'object') {
			var foo = '';
			for (var i in obj) {
				if (obj.hasOwnProperty(i)) {
					foo += '[' + i + '] => ' + obj[i] + '\n';
				}
			}
			alert(foo);
		}else {
			alert(obj);
		}
		
	}
	
	jQuery(document).ready(function($){				
		
		var posts_to_retrieve = {};
		
		/**
		 * Save the post_ids in the global object */
		 
		cspm_global_object.posts_to_retrieve = {};
		
		/**
		 * Customize Checkbox and Radio buttons */
		
		$.each($('form.faceted_search_form'), function(i, obj){
			
			var map_id = $(this).attr('data-map-id');
					
			/**
			 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
			 * @since 3.0 */
			var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';

			if(typeof $('form.faceted_search_form[data-map-id='+map_id+'] input').iCheck === 'function'){

				if(progress_map_vars.map_script_args[map_script_id]['faceted_search_input_skin'] == 'line'){
					
					var skin_color = (progress_map_vars.map_script_args[map_script_id]['faceted_search_input_color'] != 'black') ? '-' + progress_map_vars.map_script_args[map_script_id]['faceted_search_input_color'] : '';
					
					$('form.faceted_search_form[data-map-id='+map_id+'] input').each(function(){
						
						var self = $(this),
						  label = self.next(),
						  label_text = label.text();
						
						label.remove();
						self.iCheck({
							checkboxClass: 'icheckbox_line' + skin_color,
							radioClass: 'iradio_line' + skin_color,
							insert: '<div class="icheck_line-icon"></div>' + label_text,
							inheritClass: true
						});
					
					});
					
				}else{
					
					if(progress_map_vars.map_script_args[map_script_id]['faceted_search_input_skin'] != 'polaris' && progress_map_vars.map_script_args[map_script_id]['faceted_search_input_skin'] != 'futurico')
						var skin_color = (progress_map_vars.map_script_args[map_script_id]['faceted_search_input_color'] != 'black') ? '-' + progress_map_vars.map_script_args[map_script_id]['faceted_search_input_color'] : '';
					else var skin_color = '';

					$('form.faceted_search_form[data-map-id='+map_id+'] input').iCheck({
						checkboxClass: 'icheckbox_' + progress_map_vars.map_script_args[map_script_id]['faceted_search_input_skin'] + skin_color,
						radioClass: 'iradio_' + progress_map_vars.map_script_args[map_script_id]['faceted_search_input_skin'] + skin_color,
						increaseArea: '20%',
						inheritClass: true
					});
				
				}
		
			}
		
		});
	
		/**
		 * Faceted search */
	
		$('div.faceted_search_btn').livequery('click', function(){

			var map_id = $(this).attr('id');
			
			if($('div.faceted_search_container_'+map_id).is(':visible')){
				
				$('div.faceted_search_container_'+map_id).removeClass('slideInLeft').addClass('cspm_animated slideOutLeft');
				
				setTimeout(function(){$('div.faceted_search_container_'+map_id).css({'display':'none'});},200);
			
			}else $('div.faceted_search_container_'+map_id).removeClass('slideOutLeft').addClass('cspm_animated fadeInRight').css({'display':'block'});
			
			/**
			 * Call custom scroll bar for faceted search list */
										
			if(typeof jQuery('div.faceted_search_container_'+map_id).mCustomScrollbar === 'function'){
												
				$("div[class^=faceted_search_container] form.faceted_search_form ul").mCustomScrollbar("destroy");
				$("div[class^=faceted_search_container] form.faceted_search_form ul").mCustomScrollbar({
					autoHideScrollbar:false,
					mouseWheel:{
						enable: true,
						preventDefault: true,
					},
					theme:"dark-thin",
				});
				
			}
			
		});

		$('div[class^=reset_map_list]').livequery('click', function(){
			
			var map_id = $(this).attr('id');

			var icheck_selector = $('form#faceted_search_form_'+map_id+' input');
			
			if(typeof icheck_selector.iCheck === 'function')
				icheck_selector.iCheck('uncheck');
			
			$(this).hide();
			
			/**
			 * Reset nearby points of interest */
			
			$('div.cspm_reset_proximities[data-map-id='+map_id+']').trigger('click');
			
		});
		
		var change_event = (typeof $('form.faceted_search_form input').iCheck === 'function') ? 'ifChanged' : 'change';
		
		$('form.faceted_search_form input').livequery(change_event, function(){
			
			var map_id = $(this).attr('data-map-id');
			var show_carousel = $(this).attr('data-show-carousel');
			var plugin_map = $('div#codespacing_progress_map_div_'+map_id);
					
			/**
			 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
			 * @since 3.0 */
			var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';

			if(typeof NProgress !== 'undefined'){
				
				NProgress.configure({
				  parent: 'div#codespacing_progress_map_div_'+map_id,
				  showSpinner: true
				});				
				
				NProgress.start();
				
			}
			
			/**
			 * Hide all markers */
			 
			cspm_hide_all_markers(plugin_map).done(function(){
				
				if(typeof NProgress !== 'undefined')
					NProgress.set(0.5);
				
				if(progress_map_vars.map_script_args[map_script_id]['faceted_search_multi_taxonomy_option'] == "false")
					$('div.reset_map_list_'+map_id).show();
				
				posts_to_retrieve[map_id] = [];
				var retrieved_posts = [];
				var i = 0;
				var j = 0;
				var num_checked = 0;
				var count_posts = 0;					
				
				/**
				 * Loop throught checkboxes/radios and get the one(s) selected, then, display related markers */
						
				$('div.faceted_search_container_'+map_id+' form.faceted_search_form input').each(function(){
	
					if($(this).prop('checked') == true){ 
						
						num_checked++;

						var value = $(this).val();

						/**
						 * Loop throught post_ids and check its relation with the current category
						 * Then show markers within the selected category */
						 
						retrieved_posts = cspm_remove_array_duplicates(retrieved_posts.concat(cspm_set_markers_visibility(plugin_map, map_id, value, j, post_lat_lng_coords[map_id], posts_to_retrieve[map_id], true)));

						cspm_simple_clustering(plugin_map, map_id);
						
						i++;
	
					}
					
				});
				
				posts_to_retrieve[map_id] = retrieved_posts;
				
				/**
				 * Show all markers when there is no checked category */
				 
				if(num_checked == 0){
					
					var j = 0;
					
					cspm_set_markers_visibility(plugin_map, map_id, null, j, post_lat_lng_coords[map_id], posts_to_retrieve[map_id], false);
					
					cspm_simple_clustering(plugin_map, map_id);
						
				}
									
				/**
				 * Center the map on the first post in the array 
				 * @since 2.8
				 * @updated 2.8.2 */
											
				var marker_objs_length = count_posts = cspm_object_size(posts_to_retrieve[map_id]);

				if(progress_map_vars.map_script_args[map_script_id]['faceted_search_drag_map'] == 'autofit'){
					
					setTimeout(function(){
						
						var markers_bounds = new google.maps.LatLngBounds();

						plugin_map.gmap3({
							get: {
								name:"marker",
								all: true,
								callback: function(markers){
									
									if(!markers)
										return;
										
									$.each(markers, function(i, marker){
										if(typeof marker !== 'undefined' && marker.visible == true){
											markers_bounds.extend(marker.getPosition());
								  		}								  
									});
									
							  		setTimeout(function(){
										plugin_map.gmap3('get').fitBounds(markers_bounds);
										cspm_zoom_in_and_out(plugin_map);
									}, 100);
									
								}
						  	}
						});
						
					}, 100);
				
				}else if(progress_map_vars.map_script_args[map_script_id]['faceted_search_drag_map'] == 'drag'){
					
					if(marker_objs_length > 0){
						
						var first_post_latlng = post_lat_lng_coords[map_id][posts_to_retrieve[map_id][0]].split('_');
						
						cspm_center_map_at_point(plugin_map, map_id, first_post_latlng[0], first_post_latlng[1], 'zoom');
					
					}
					
				}
				
				/**
				 * Save the post_ids in the global object.
				 * This is usefull when using the plugin with an extension */
				 
				cspm_global_object.posts_to_retrieve[map_id] = posts_to_retrieve[map_id];																											
	
				if(progress_map_vars.map_script_args[map_script_id]['show_posts_count'] == "yes")
					$('span.the_count_'+map_id).empty().html(count_posts);

				/**
				 * Rewrite Carousel */
				
				setTimeout(function(){
					cspm_rewrite_carousel(map_id, show_carousel, posts_to_retrieve[map_id]);
				}, 500);
			
				/**
				 * Hide the Countries list & Remove hover style on Countries list just in case a user ...
				 * ... already choosed a country then decided they want to filter the map */
				
				if($('div.countries_container_'+map_id).is(':visible')){
					
					$('div.countries_container_'+map_id).removeClass('slideInLeft').addClass('cspm_animated slideOutLeft');
					
					setTimeout(function(){$('div.countries_container_'+map_id).css({'display':'none'});},200);
				
					$('li.cspm_country_name[data-map-id='+map_id+']').removeClass('selected');
				
				}
			
				/**
				 * Reset nearby points of interest */
				
				$('div.cspm_reset_proximities[data-map-id='+map_id+']').trigger('click');
			
				/**
				 * End the Progress Bar Loader */
					
				if(typeof NProgress !== 'undefined')
					NProgress.done();
				
			});
				
		});
	
		// @Facetd search ====
		
		
		// Move the carousel on Infowindow hover
		$('div.cspm_infobox_container[data-move-carousel=true] div.cspm_infobox_content_container').livequery('mouseenter', function(){
	
			var map_id = $(this).attr('data-map-id');
			var post_id = $(this).attr('data-post-id');
			var carousel = $(this).attr('data-show-carousel');
			var item_value = $('ul[id^=codespacing_progress_map_carousel_] li[id='+map_id+'_list_items_'+post_id+']').attr('value');
			
			if(carousel == 'yes')
				cspm_call_carousel_item($('ul#codespacing_progress_map_carousel_'+map_id).data('jcarousel'), item_value);			
			
		});
				
			
		// The event handler of the carousel items
		
		$('ul[id^=codespacing_progress_map_carousel_] li').livequery('click', function(){
			
			var map_id = $(this).attr('data-map-id');
			
			if(map_layout[map_id] != 'fullscreen-map' && map_layout[map_id] != 'fit-in-map'){
				
				var item_value = $(this).attr('value');
						
				// Move the clicked carousel item to the first position
				cspm_call_carousel_item($('ul#codespacing_progress_map_carousel_'+map_id).data('jcarousel'), item_value);
				
				var carousel = $('ul#codespacing_progress_map_carousel_'+map_id).data('jcarousel');
				
				cspm_carousel_item_request(carousel, parseInt(item_value));
				
			}
			
		}).css('cursor','pointer');
		
		// @Event handler
		
		
		// Search form request
	
		/**
		 * Customize the text box "radius" to slider */
		
		if(typeof $("input.cspm_sf_slider_range").ionRangeSlider === 'function'){
				
			$("input.cspm_sf_slider_range").ionRangeSlider({
				type: 'single',	
				grid: true	
			});
		
		}
		
		// Load the search form to the screen
		$('div.search_form_btn').livequery('click', function(){

			var map_id = $(this).attr('id');
			
			if($('div.search_form_container_'+map_id).is(':visible')){
				$('div.search_form_container_'+map_id).removeClass('fadeInUp').addClass('cspm_animated slideOutLeft');
				setTimeout(function(){
					$('div.search_form_container_'+map_id).css({'display':'none'});							
				},200);
			}else{										
				$('div.search_form_container_'+map_id).removeClass('slideOutLeft').addClass('cspm_animated fadeInUp').css({'display':'block'});
				setTimeout(function(){
					$('form#search_form_'+map_id+' input[name=cspm_address]').focus();
				},400);
			}
			
		});
		
		/**
		 * Submit the search form data */
		 
		$('div[class^=cspm_submit_search_form_]').livequery('click', function(){
			
			var map_id = $(this).attr('data-map-id');
					
			/**
			 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
			 * @since 3.0 */
			var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';

			if(typeof NProgress !== 'undefined'){
				
				NProgress.configure({
				  parent: 'div#codespacing_progress_map_div_'+map_id,
				  showSpinner: true
				});				
			
				NProgress.start();
			
			}				
			
			var show_carousel = $(this).attr('data-show-carousel');
			var address = progress_map_vars.map_script_args[map_script_id]['before_search_address'] + $('form#search_form_'+map_id+' input[name=cspm_address]').val() + progress_map_vars.map_script_args[map_script_id]['after_search_address'];
			
			var draw_circle = progress_map_vars.map_script_args[map_script_id]['sf_circle_option'] == 'true' ? true : false;
			var edit_circle = progress_map_vars.map_script_args[map_script_id]['sf_edit_circle'] == 'true' ? true : false;
			
			/**
			 * Distance in KM or Miles */
			
			var decimals_distance = $('form#search_form_'+map_id+' input[name=cspm_decimals_distance]').val();
			
			if(decimals_distance != ''){
				var distance = decimals_distance;
			}else var distance = $('form#search_form_'+map_id+' input[name=cspm_distance]').val();
			 
			var distance_unit = $('form#search_form_'+map_id+' input[name=cspm_distance_unit]').val();
			
				/**
				 * [@distance_in_meters] Distance converted to meters */
				 
				if(distance_unit == "metric")
					var distance_in_meters = distance * 1000; // 1KM = 1000
				else var distance_in_meters = (distance * 1.60934) * 1000; // 1Mile = 1.60934
			
			/**
			 * Min & Max Distances in KM or Miles */
			 
			var min_distance = $('form#search_form_'+map_id+' input[name=cspm_distance]').attr('data-min');
			var max_distance = $('form#search_form_'+map_id+' input[name=cspm_distance]').attr('data-max');
			
				/**
				 * [@min_distance_in_meters] Min Distance converted to meters
				 * [@max_distance_in_meters] Max Distance converted to meters */
				 
				if(distance_unit == "metric"){
					var min_distance_in_meters = min_distance * 1000; // 1KM = 1000
					var max_distance_in_meters = max_distance * 1000; // 1KM = 1000
				}else{
					var min_distance_in_meters = (min_distance * 1.60934) * 1000; // 1Mile = 1.60934
					var max_distance_in_meters = (max_distance * 1.60934) * 1000; // 1Mile = 1.60934
				}
			
			var infobox_div = $('div.cspm_infobox_container.cspm_infobox_'+map_id);
			var show_infobox = $('div.codespacing_progress_map_area').attr('data-show-infobox');
			var infobox_display_event = $('div.codespacing_progress_map_area').attr('data-infobox-display-event');

			var plugin_map = $('div#codespacing_progress_map_div_'+map_id);
			
			var posts_in_search = {};				
			posts_in_search[map_id] = [];

			var geocoder = new google.maps.Geocoder();
			
			/**
			 * Convert our address to Lat & Long */
			 
			geocoder.geocode({ 'address': address }, function (results, status) {
				
				/**
				 * If the address is found */
				 
				if (status == google.maps.GeocoderStatus.OK) {

					var latitude = results[0].geometry.location.lat();
					var longitude = results[0].geometry.location.lng();
									
					plugin_map.gmap3({
						get: {
							name: 'marker',
							all:  true,
							callback: function(objs) {
								
								var j = 0;
								
								/**
								 * Get all markers inside the radius of our address */
								 
								$.each(objs, function(i, obj) {									
									
									var marker_id = obj.id;
									
									/**
									 * Convert the LatLng object to array */
									 
									var post_latlng = obj.position;
									
									/**
									 * Calculate the distance and save the post_id */
									 
									if(cspm_get_distance(latitude, longitude, post_latlng.lat(), post_latlng.lng(), distance_unit) < parseInt(distance)){
										posts_in_search[map_id][j] = parseInt(marker_id);													
										j++;
									}
									
								});
								
								/**
								 * If one or more posts are found within the radius area */
								 
								if(cspm_object_size(posts_in_search[map_id]) > 0){
									
									/**
									 * Hide all markers */
									 
									cspm_hide_all_markers(plugin_map).done(function(){
				
										/**
										 * Center the map in our address position */
										 
										cspm_center_map_at_point(plugin_map, map_id, latitude, longitude, 'zoom');

										/**
										 * Loop throught post_ids and check the post relation with the current category
										 * Then show markers within the selected category */
										 
										cspm_set_markers_visibility(plugin_map, map_id, null, 0, post_lat_lng_coords[map_id], posts_in_search[map_id], true);
										cspm_simple_clustering(plugin_map, map_id);

										/**
										 * Save the post_ids in the global object
										 * This is usefull when using the plugin with an extension */
										 
										cspm_global_object.posts_to_retrieve[map_id] = posts_in_search[map_id];																																						
										
										if(draw_circle){
										
											var circle_radius = distance_in_meters;										
											
											plugin_map.gmap3({
												clear: {
													//name:"circle",
													tag: "search_circle",
													all: true
												},
												circle:{
													tag: 'search_circle',
													options:{
														center: [latitude, longitude],
														radius : circle_radius, // The radius in meters on the Earth's surface
														fillColor : progress_map_vars.map_script_args[map_script_id]['fillColor'],
														fillOpacity: progress_map_vars.map_script_args[map_script_id]['fillOpacity'],
														strokeColor : progress_map_vars.map_script_args[map_script_id]['strokeColor'],
														strokeOpacity: progress_map_vars.map_script_args[map_script_id]['strokeOpacity'],
														strokeWeight: parseInt(progress_map_vars.map_script_args[map_script_id]['strokeWeight']),
														editable: edit_circle,
													},
													callback: function(circle){
														plugin_map.gmap3('get').fitBounds(circle.getBounds());
													},
													events:{
														click: function(circle){
									
															/**
															 * Remove single infobox on circle click */
									 
															if(show_infobox == 'true' && infobox_display_event != 'onload'){																										
																infobox_div.addClass('cspm_animated fadeOutUp');					
																infobox_div.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
																	infobox_div.hide().removeClass('cspm_animated fadeOutUp');
																});
															}
														},
														radius_changed: function(circle){
															
															if(edit_circle){
																
																/**
																 * Get circle radius */
																 
																var radius_in_meters = circle.getRadius();
																
																/**
																 * Compare circle radius to min and max distances and redraw circle if needs to */
																
																var readable_distance_unit = (distance_unit == 'metric') ? 'Km' : 'Miles';
																
																var map_message_widget = $('div.cspm_map_red_msg_widget[data-map-id='+map_id+']');
																
																if(radius_in_meters > max_distance_in_meters){
																	
																	circle.setRadius(max_distance_in_meters);
																	
																	map_message_widget.text(progress_map_vars.circle_reached_max_msg+' ('+max_distance+readable_distance_unit+')').removeClass('fadeOut').addClass('cspm_animated fadeIn').css({'display':'block'});
																	setTimeout(function(){ 
																		map_message_widget.removeClass('fadeIn').addClass('fadeOut');
																		setTimeout(function(){
																			map_message_widget.css({'display':'none'});
																		}, 500);
																	}, 1500);
																	
																}else if(radius_in_meters < min_distance_in_meters){
																	
																	circle.setRadius(min_distance_in_meters);
																	
																	map_message_widget.text(progress_map_vars.circle_reached_min_msg+' ('+min_distance+readable_distance_unit+')').removeClass('fadeOut').addClass('cspm_animated fadeIn').css({'display':'block'});
																	setTimeout(function(){ 
																		map_message_widget.removeClass('fadeIn').addClass('fadeOut');
																		setTimeout(function(){
																			map_message_widget.css({'display':'none'});
																		}, 500);
																	}, 1500);
																
																}
															
																setTimeout(function(){
																
																	var radius_in_meters = circle.getRadius();
																
																	if(distance_unit == "metric")
																		var radius_in_distance_unit = (radius_in_meters / 1000);
																	else var radius_in_distance_unit = (radius_in_meters / 1.60934);
																																
																	$('form#search_form_'+map_id+' input[name=cspm_decimals_distance]').attr('value', radius_in_distance_unit);
																				
																	var distance_slider = $('input.cspm_sf_slider_range[data-map-id='+map_id+']').data('ionRangeSlider');
		
																	distance_slider.update({
																		from: radius_in_distance_unit
																	});

																	cspm_hide_all_markers(plugin_map).done(function(){
																		$('div.cspm_submit_search_form_'+map_id).trigger('click');
																		cspm_zoom_in_and_out(plugin_map);
																	});
																	
																}, 500);
																
															}
																												
														}
													}
												}											
											});
											
										}
										
										/**
										 * Show the reset button */
										 
										$('div.cspm_reset_search_form_'+map_id).show();
				
										/**
										 * Reload post count value */
										 
										if(progress_map_vars.map_script_args[map_script_id]['show_posts_count'] == "yes")
											$('span.the_count_'+map_id).empty().html(posts_in_search[map_id].length);
				
										/**
										 * Rewrite Carousel */
										
										setTimeout(function(){
											cspm_rewrite_carousel(map_id, show_carousel, posts_in_search[map_id]);
										}, 500);
										
									});
									
								}else{
									
									$('div.search_form_container_'+map_id+' div.cspm_search_form_notice').removeClass('fadeOut').addClass('cspm_animated fadeInLeft').css({'display':'block'});	
									setTimeout(function(){
										$('div.search_form_container_'+map_id+' div.cspm_search_form_notice').removeClass('fadeInLeft').addClass('cspm_animated fadeOut');
										setTimeout(function(){
											$('div.search_form_container_'+map_id+' div.cspm_search_form_notice').css({'display':'none'});
										},700);
									},5000);		
									
									/**
									 * If circle resized but no locations found */
									 
									if(decimals_distance != '' && draw_circle && edit_circle){
										
										$('form#search_form_'+map_id+' input[name=cspm_decimals_distance]').attr('value', '');							
				
										/**
										 * Reload post count value */
										 
										if(progress_map_vars.map_script_args[map_script_id]['show_posts_count'] == "yes")
											$('span.the_count_'+map_id).empty().html(posts_in_search[map_id].length);
				
										/**
										 * Rewrite Carousel */
										
										setTimeout(function(){
											cspm_rewrite_carousel(map_id, show_carousel, posts_in_search[map_id]);
										}, 500);
										
									}
		
								}

							}
						}
					});
				
				// The address is not found		
				}else{

					$('div.search_form_container_'+map_id+' div.cspm_search_form_error').removeClass('fadeOut').addClass('cspm_animated fadeInLeft').css({'display':'block'});	
					setTimeout(function(){
						$('div.search_form_container_'+map_id+' div.cspm_search_form_error').removeClass('fadeInLeft').addClass('cspm_animated fadeOut');
						setTimeout(function(){
							$('div.search_form_container_'+map_id+' div.cspm_search_form_error').css({'display':'none'});
						},700);
					},5000);									
		
				}
			
				/**
				 * Hide the Countries list & Remove hover style on Countries list just in case a user ...
				 * ... already choosed a country then decided they want to search the map */
				
				if($('div.countries_container_'+map_id).is(':visible')){
					
					$('div.countries_container_'+map_id).removeClass('slideInLeft').addClass('cspm_animated slideOutLeft');
					
					setTimeout(function(){$('div.countries_container_'+map_id).css({'display':'none'});},200);
				
					$('li.cspm_country_name[data-map-id='+map_id+']').removeClass('selected');
				
				}
			
				/**
				 * Reset nearby points of interest */
				
				$('div.cspm_reset_proximities[data-map-id='+map_id+']').trigger('click');
			
				if(typeof NProgress !== 'undefined')
					NProgress.done();
				
			});
			
		});
		
		/**
		 * Reset the search form & the map */
		 
		$('div[class^=cspm_reset_search_form]').livequery('click', function(){
			
			var map_id = $(this).attr('data-map-id');
					
			/**
			 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
			 * @since 3.0 */
			var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';

			if(typeof NProgress !== 'undefined'){

				NProgress.configure({
				  parent: 'div#codespacing_progress_map_div_'+map_id,
				  showSpinner: true
				});				
				
				NProgress.start();
				
			}
							
			var show_carousel = $(this).attr('data-show-carousel');
			var plugin_map = $('div#codespacing_progress_map_div_'+map_id);
			
			posts_to_retrieve[map_id] = [];
			
			cspm_set_markers_visibility(plugin_map, map_id, null, 0, post_lat_lng_coords[map_id], posts_to_retrieve[map_id], false);
			cspm_simple_clustering(plugin_map, map_id);
										
			/**
			 * Save the post_ids in the global object
			 * This is usefull when using the plugin with an extension */
			 
			cspm_global_object.posts_to_retrieve[map_id] = posts_to_retrieve[map_id];																											
										
			if(progress_map_vars.map_script_args[map_script_id]['show_posts_count'] == "yes")
				$('span.the_count_'+map_id).empty().html(posts_to_retrieve[map_id].length);
			
			if(typeof NProgress !== 'undefined')
				NProgress.set(0.5);
								
			plugin_map.gmap3({
				clear: {
					tag: "search_circle",
					all: true
				},
			});
        	
			cspm_recenter_map(plugin_map, map_id);						
			cspm_zoom_in_and_out(plugin_map); // Fix issue whith clusters when current map center equals initial map center
			
			$('form#search_form_'+map_id+' input#cspm_address_'+map_id).attr('value', '').focus();
				
			/**
			 * Rewrite Carousel */
			
			setTimeout(function(){
				cspm_rewrite_carousel(map_id, show_carousel, posts_to_retrieve[map_id]);
			}, 500);
			
			/**
			 * Hide the Countries list & Remove hover style on Countries list just in case a user ...
			 * ... already choosed a country then decided they want to search the map */
			
			if($('div.countries_container_'+map_id).is(':visible')){
				
				$('div.countries_container_'+map_id).removeClass('slideInLeft').addClass('cspm_animated slideOutLeft');
				
				setTimeout(function(){$('div.countries_container_'+map_id).css({'display':'none'});},200);
			
				$('li.cspm_country_name[data-map-id='+map_id+']').removeClass('selected');
			
			}
			
			/**
			 * Reset nearby points of interest */
			
			$('div.cspm_reset_proximities[data-map-id='+map_id+']').trigger('click');
			
			$(this).removeClass('fadeIn').hide('fast', function(){
				if(typeof NProgress !== 'undefined')
					NProgress.done();	
			});
					
		});
		
		// @Search form request
		
		
		// Toogle the carousel
				
		$('div.toggle-carousel-bottom, div.toggle-carousel-top').livequery('click', function(){
			
			var map_id = $(this).attr('data-map-id');
			
			$('div#cspm_carousel_container[data-map-id='+map_id+']').slideToggle("slow", function(){
			
				cspm_init_carousel(null, map_id);
				
			});
			
		});						
		
		// Show & Hide the search distance radius in the search form
		
		$('span.cspm_distance').livequery('click', function(){
			
			var map_id = $(this).attr('data-map-id');
							
			if($('form#search_form_'+map_id+' div.cspm_search_distances ul').is(':visible')){
				$('form#search_form_'+map_id+' div.cspm_search_distances ul').removeClass('fadeInDown').addClass('cspm_animated fadeOutUp');
				setTimeout(function(){$('form#search_form_'+map_id+' div.cspm_search_distances ul').css({'display':'none'});},200);
			}else{					
				$('form#search_form_'+map_id+' div.cspm_search_distances ul').removeClass('fadeOutUp').addClass('cspm_animated fadeInDown').css({'display':'block'});
			}
						
		});
		
		$('div.cspm_search_distances ul li').livequery('click', function(){
			
			var map_id = $(this).parent().prev().attr('data-map-id');
			
			$('form#search_form_'+map_id+' div.cspm_search_distances span.cspm_distance').text($(this).text());
			
			$('form#search_form_'+map_id+' div.cspm_search_distances ul').removeClass('fadeInDown').addClass('cspm_animated fadeOutUp');
			
			setTimeout(function(){$('form#search_form_'+map_id+' div.cspm_search_distances ul').css({'display':'none'});},200);
			
		});
		
		// @Search distance
		
		
		// Frontend Form @since 2.6.3
		
		$('button#cspm_search_btn').livequery('click', function(){
			
			var map_id = $(this).attr('data-map-id');
			var address = $('input[name=cspm_search_address]').val();

			var plugin_map = $('div#cspm_frontend_form_'+map_id);
			
			plugin_map.gmap3({
				clear: {
					name:"marker",
					all: true
				},
			});

			var geocoder = new google.maps.Geocoder();
			
			// Convert our address to Lat & Lng
			geocoder.geocode({ 'address': address }, function (results, status) {
				
				// If the address was found
				if (status == google.maps.GeocoderStatus.OK) {

					var latitude = results[0].geometry.location.lat();
					var longitude = results[0].geometry.location.lng();
					
					setTimeout(function(){
						// Center the map in our address position
						cspm_center_map_at_point(plugin_map, map_id, latitude, longitude, 'zoom');
						
						plugin_map.gmap3({							
							marker:{
							  latLng:results[0].geometry.location,
							  options:{
							  	draggable: true,
							  }
							}
						});
					}, 500);
					
				}
				
			});
			
		});
		
		$('input#cspm_search_address').keypress(function(e){
			if (e.keyCode == 13) {
				e.preventDefault();
			}
		});
				  
		$('button#cspm_get_pinpoint').livequery('click', function(){
			
			var map_id = $(this).attr('data-map-id');
							
			var plugin_map = $('div#cspm_frontend_form_'+map_id);
			
			plugin_map.gmap3({
			  get: {
				name:"marker",
				callback: function(marker){
					if(marker){
						$("input#cspm_latitude").val(marker.getPosition().lat());
						$("input#cspm_longitude").val(marker.getPosition().lng());
					}
				}
			  }
			});
			
		});
				
		// @Frontend Form		
					
		/**
		 * Geolocalization
		 *
		 * @since 2.8 */
		
		$('div[class^=codespacing_map_geotarget]').livequery('click', function(){
			
			var map_id = $(this).attr('data-map-id');
				        
			/**
			 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
			 * @since 3.0 */
			var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';

			var plugin_map = $('div#codespacing_progress_map_div_'+map_id);

			cspm_geolocate(plugin_map, map_id, false, '', '', 0, progress_map_vars.map_script_args[map_script_id]['user_map_zoom'], true);
			
		});
		
		
		/**
		 * Zoom to country
		 *
		 * @since 3.0 
		 */
		
		$('div.countries_btn').livequery('click', function(){

			var map_id = $(this).attr('data-map-id');
		
			var plugin_map = $('div#codespacing_progress_map_div_'+map_id);
			
			if($('div.countries_container_'+map_id).is(':visible')){
				
				$('div.countries_container_'+map_id).removeClass('slideInLeft').addClass('cspm_animated slideOutLeft');
				
				setTimeout(function(){$('div.countries_container_'+map_id).css({'display':'none'});},200);
			
			}else{
				
				$('div.countries_container_'+map_id).removeClass('slideOutLeft').addClass('cspm_animated fadeInRight').css({'display':'block'});
				
				var selected_country = $('li.cspm_country_name.selected');
				
				if(selected_country.length){

					if(typeof $('div.countries_container_'+map_id).mCustomScrollbar === 'function')
						$('div.countries_container_'+map_id+' ul').mCustomScrollbar("scrollTo", selected_country, {timeout: 600});
														
				}
							
			}
			
			/**
			 * Call custom scroll bar for countries list */
										
			if(typeof $('div.countries_container_'+map_id).mCustomScrollbar === 'function'){
												
				$("div[class^=countries_container] ul").mCustomScrollbar("destroy");
				$("div[class^=countries_container] ul").mCustomScrollbar({
					autoHideScrollbar:false,
					mouseWheel:{
						enable: true,
						preventDefault: true,
					},
					theme:"dark-thin"
				});
				
			}
			
		});
		 
		$('li.cspm_country_name').livequery('click', function(){
			
			var country_name = $(this).attr('data-country-name');

			var map_id = $(this).attr('data-map-id');
			
			$('li.cspm_country_name[data-map-id='+map_id+']').removeClass('selected');
			
			$(this).addClass('selected');
			
			if(typeof NProgress !== 'undefined'){

				NProgress.configure({
				  parent: 'div#codespacing_progress_map_div_'+map_id,
				  showSpinner: true
				});				
				
				NProgress.start();
				
			}								
        
			/**
			 * [@map_script_id] | Used only to get map options from the PHP option wp_localize_script()
			 * @since 3.0 */
			var map_script_id = (typeof progress_map_vars.map_script_args[map_id] !== 'undefined') ? map_id : 'initial';
			
			var zoom_or_autofit = progress_map_vars.map_script_args[map_script_id]['country_zoom_or_autofit'];
			
			var autofit = (zoom_or_autofit == 'autofit') ? true : false;
			
			var map_zoom = progress_map_vars.map_script_args[map_script_id]['country_zoom_level'];
		
			var plugin_map = $('div#codespacing_progress_map_div_'+map_id);
							
			if(typeof NProgress !== 'undefined')
				NProgress.set(0.5);

			cspm_zoom_to_address(map_id, plugin_map, country_name, autofit, map_zoom);

			if(typeof NProgress !== 'undefined')
				NProgress.done();	

		});
		
		
		/**
		 * Recenter the map
		 *
		 * @since 3.0 
		 */
		
		$('div[class^=cspm_recenter_map_btn]').livequery('click', function(){

			var map_id = $(this).attr('data-map-id');
		
			var plugin_map = $('div#codespacing_progress_map_div_'+map_id);
			
			if(typeof NProgress !== 'undefined'){

				NProgress.configure({
				  parent: 'div#codespacing_progress_map_div_'+map_id,
				  showSpinner: true
				});				
				
				NProgress.start();
				
			}								
        	
			cspm_recenter_map(plugin_map, map_id);						
			cspm_zoom_in_and_out(plugin_map); // Fix issue whith clusters when current map center equals initial map center
			
			/**
			 * Hide the Countries list & Remove hover style on Countries list just in case a user ...
			 * ... already choosed a country then decided they want to recenter the map */
			
			if($('div.countries_container_'+map_id).is(':visible')){
				
				$('div.countries_container_'+map_id).removeClass('slideInLeft').addClass('cspm_animated slideOutLeft');
				
				setTimeout(function(){$('div.countries_container_'+map_id).css({'display':'none'});},200);
			
				$('li.cspm_country_name[data-map-id='+map_id+']').removeClass('selected');
			
			}
			
			if(typeof NProgress !== 'undefined')
				NProgress.done();	

		});
		
		
		/**
		 * Nearby points of interest
		 *
		 * @since 3.2 
		 */
		
		$('div.cspm_proximities_btn').livequery('click', function(){

			var map_id = $(this).attr('data-map-id');
		
			var plugin_map = $('div#codespacing_progress_map_div_'+map_id);
			
			/**
			 * Call custom scroll bar for proximities list */
										
			if(typeof $('div.cspm_proximities_container_'+map_id).mCustomScrollbar === 'function'){
												
				$("div[class^=cspm_proximities_container] ul").mCustomScrollbar("destroy");
				$("div[class^=cspm_proximities_container] ul").mCustomScrollbar({
					autoHideScrollbar:false,
					mouseWheel:{
						enable: true,
						preventDefault: true,
					},
					theme:"dark-thin"
				});
				
			}
			
			if($('div.cspm_proximities_container_'+map_id).is(':visible')){
				
				$('div.cspm_proximities_container_'+map_id).removeClass('slideInLeft').addClass('cspm_animated slideOutLeft');
				
				setTimeout(function(){$('div.cspm_proximities_container_'+map_id).css({'display':'none'});},200);
			
			}else{
				
				$('div.cspm_proximities_container_'+map_id).removeClass('slideOutLeft').addClass('cspm_animated fadeInRight').css({'display':'block'});
				
				var selected_proximity = $('li.cspm_proximity_name.selected');
				
				if(selected_proximity.length){

					if(typeof $('div.cspm_proximities_container_'+map_id).mCustomScrollbar === 'function')
						$('div.cspm_proximities_container_'+map_id+' ul').mCustomScrollbar("scrollTo", selected_proximity, {timeout: 600});
														
				}
							
			}
			
		});		
		
		/**
		 * Init Directions Display */
		 
		var DirectionsDisplay = {};
		
		/**
		 * Display nearby points of interest */
		 
		$('li.cspm_proximity_name').livequery('click', function(){
				
			var map_id = $(this).attr('data-map-id');

			var plugin_map = jQuery('div#codespacing_progress_map_div_'+map_id);
			var mapObject = plugin_map.gmap3('get');
			
			/**
			 * Get all data needed to get proximities */
			
			var proximity_id = $(this).attr('data-proximity-id');
			var proximity_name = $(this).attr('data-proximity-name');	
			var distance_unit = $(this).attr('data-distance-unit');	
			var radius = $(this).attr('data-radius');
			var initial_radius = $(this).attr('data-initial-radius');
			var travel_mode = $(this).attr('data-travel-mode');	
			var draw_circle = $(this).attr('data-draw-circle');
				draw_circle = (draw_circle == 'true') ? true : false;
			var edit_circle = $(this).attr('data-edit-circle');
				edit_circle = (edit_circle == 'true') ? true : false;
			var marker_type = $(this).attr('data-marker-type');
			var target_marker_latlng = $(this).attr('data-marker-latlng');			        		
			
			/**
			 * Min & Max Radius in meters */
			
			var min_radius = $(this).attr('data-min-radius');
			var max_radius = $(this).attr('data-max-radius');	
			
				/**
				 * [@radius_in_distance_unit] Radius converted to Km or Miles
				 * [@min_radius_in_distance_unit] Min Radius converted to Km or Miles
				 * [@max_radius_in_distance_unit] Max Radius converted to Km or Miles */
													
				if(distance_unit == "METRIC"){
					var radius_in_distance_unit = (radius / 1000); 					
					var min_radius_in_distance_unit = (min_radius / 1000);
					var max_radius_in_distance_unit = (max_radius / 1000);
				}else{
					var radius_in_distance_unit = (radius / 1.60934);
						radius_in_distance_unit = radius_in_distance_unit / 1000;					
					var min_radius_in_distance_unit = (min_radius / 1.60934);
						min_radius_in_distance_unit = min_radius_in_distance_unit / 1000;
					var max_radius_in_distance_unit = (max_radius / 1.60934);
						max_radius_in_distance_unit = max_radius_in_distance_unit / 1000;
				}
				
			var min_radius_attempt = $(this).attr('data-min-radius-attempt');
			var max_radius_attempt = $(this).attr('data-max-radius-attempt');
			
			var map_message_widget = $('div.cspm_map_red_msg_widget[data-map-id='+map_id+']');
			
			/**
			 * No marker coordinates has been found */
			 
			if(target_marker_latlng == '' || typeof target_marker_latlng === 'undefined'){
				
				map_message_widget.text(progress_map_vars.no_marker_selected_msg).removeClass('fadeOut').addClass('cspm_animated fadeIn').css({'display':'block'});
				setTimeout(function(){ 
					map_message_widget.removeClass('fadeIn').addClass('fadeOut');
					setTimeout(function(){
						map_message_widget.css({'display':'none'});
					}, 500);
				}, 3000);
			
			/**
			 * Proceed when marker coordinates are available */
			 	
			}else{
			
				if($(this).hasClass('selected'))
					return;
			
				$('li.cspm_proximity_name[data-map-id='+map_id+']').removeClass('selected');
				
				$(this).addClass('selected');
					
				if(typeof NProgress !== 'undefined'){
	
					NProgress.configure({
					  parent: 'div#codespacing_progress_map_div_'+map_id,
					  showSpinner: true
					});				
					
					NProgress.start();
					
				}								
				
				/**
				 * Hide the proximities list */
				 
				$('div.cspm_proximities_container_'+map_id).removeClass('slideInLeft').addClass('cspm_animated slideOutLeft');
				
				setTimeout(function(){$('div.cspm_proximities_container_'+map_id).css({'display':'none'});},200);
				
				/**
				 * Hide the green message */
				 
				$('div.cspm_map_green_msg_widget[data-map-id='+map_id+']').removeClass('fadeIn').addClass('fadeOut');
				setTimeout(function(){
					$('div.cspm_map_green_msg_widget[data-map-id='+map_id+']').css({'display':'none'});
				}, 500);
			
				/**
				 * Display the reset button */
				
				setTimeout(function(){$('div.cspm_reset_proximities[data-map-id='+map_id+']').show();},1000);
				
				/**
				 * Convert marker latLng string to array */
				 
				var location_latlng = target_marker_latlng.replace('(', '').replace(')', '').split(',');
				
				if(typeof location_latlng[0] === 'undefined' && typeof location_latlng[1] === 'undefined'){
					
					if(typeof NProgress !== 'undefined')
							NProgress.done();
					
					return;
					
				}
			
				/**
				 * Clear already displayed proximity markers */
				 
				plugin_map.gmap3({
					clear: {
						tag: ['cspm_nearby_point_'+map_id, 'cspm_nearby_circle_'+map_id]
					}
				});	
				
				/**
				 * Prepare the map to display new markers and routes */
				
				var DirectionsRendererOptions = {
					suppressMarkers: true,
					suppressInfoWindows: true,
					draggable: false,
					preserveViewport: false,
					suppressBicyclingLayer: true,
				};
				
				if(DirectionsDisplay[map_id] != null) {
					DirectionsDisplay[map_id].setMap(null);
					DirectionsDisplay[map_id] = null;
				}
			
				DirectionsDisplay[map_id] = new google.maps.DirectionsRenderer(DirectionsRendererOptions);
				DirectionsDisplay[map_id].setMap(mapObject);
				
				/**
				 * New request for places */
				 	
				var request = {
				  location: new google.maps.LatLng(location_latlng[0], location_latlng[1]),
				  radius: radius,	  
				  types: [proximity_id], /* https://developers.google.com/places/documentation/supported_types?hl=fr */
				};
	
				var service = new google.maps.places.PlacesService(mapObject);
				
				var cspm_existing_places = {};
					cspm_existing_places[map_id] = new Array();
							
				if(typeof NProgress !== 'undefined')
					NProgress.set(0.5);
				
				var markers_bounds = new google.maps.LatLngBounds();
				
				var no_route_found_msg = progress_map_vars.no_route_found_msg.replace('{travel_mode}', travel_mode.toLowerCase());

				service.nearbySearch(request, function(results, status, pagination){
			
					if(status === google.maps.places.PlacesServiceStatus.OK){

						if(results.length > 0){

							/**
							 * List all places */
							
							for (var i = 0; i < results.length; i++){
									
								var this_place_id = results[i].place_id;
								var this_place_location = results[i].geometry.location;
								var this_place_name = results[i].name;
										
								if(jQuery.inArray( this_place_id, cspm_existing_places[map_id] ) == -1 && jQuery.inArray( proximity_id, results[i].types ) != -1){
									
									cspm_existing_places[map_id].push(this_place_id);
									
									/**
									 * Add this nearby point of interest as new marker on the map */
									
									var marker_icon = (marker_type == 'default') ? '' : new google.maps.MarkerImage(progress_map_vars.plugin_url+'img/nearby/pointers/'+proximity_id+'-pin.png');
									
									plugin_map.gmap3({ 
									
										marker:{
											latLng: this_place_location,
											title: this_place_name,
											tag: 'cspm_nearby_point_'+map_id,
											options:{
												icon: marker_icon,
												animation: google.maps.Animation.DROP,
												opacity: 1,
												placeID: this_place_id,
												placeName: this_place_name, 
											},
											callback: function(marker){	
												markers_bounds.extend(marker.getPosition());
											},
											events:{
												click: function(marker, event, context){
													
													var origin = new google.maps.LatLng(location_latlng[0], location_latlng[1]);
													var destination = marker.position;
													var travel_mode = $('li.cspm_proximity_name[data-map-id='+map_id+']').attr('data-travel-mode');	
													
													$('div.cspm_switch_np_travel_mode[data-map-id='+map_id+']').attr('data-origin', origin).attr('data-destination', destination);
													
													/**
													 * Get route */
													 
													plugin_map.gmap3({ 
														getroute:{
															options:{
																origin: origin,
																destination: destination,
																travelMode: google.maps.DirectionsTravelMode[travel_mode],
																unitSystem: google.maps.UnitSystem[distance_unit],
															},
															callback: function(response){
																
																if(response){
																	
																	if(response.status === 'OK'){
																		
																		/**
																		 *  Render the route on the map */
																		 
																		DirectionsDisplay[map_id].setDirections(response);						
																		 
																		var route = response.routes[0];
																				
																		var distance = route.legs[0].distance.text;
																		var duration = route.legs[0].duration.text;		
			
																		var place_name = marker.placeName;
																		
																		/**
																		 * Get place details */
																		 
																		service.getDetails({
																			
																			placeId: marker.placeID,
																			
																		}, function(place, status){

																			if(status === google.maps.places.PlacesServiceStatus.OK){
																				
																				var origin_latLng = origin.toString().replace('(', '').replace(')', '');
																				var destination_latLng = destination.toString().replace('(', '').replace(')', '');
																				
																				var infowindow_content = 
																					'<div style="padding:10px 5px; margin-bottom:5px; border-bottom:1px solid #eee; text-align:center;">'+
																						'<strong><a href="'+place.url+'" target="_blank">'+place_name+'</a></strong>'+
																					'</div>'+
																					'<div style="padding:5px; text-align:center;">'+
																						'<span style="text-align:center; padding:5px; margin-right:15px;"><img src="'+progress_map_vars.plugin_url+'img/route_distance.png" style="width:15px; margin-right:5px" /><span class="cspm_infowindow_distance">'+distance+'</span></span>'+
																						'<span style="text-align:center; padding:5px;"><img src="'+progress_map_vars.plugin_url+'img/clock.png" style="width:15px; margin-right:5px" /><span class="cspm_infowindow_duration">'+duration+'</span></span>'+
																						'<span style="text-align:center; padding:5px;"><img src="'+progress_map_vars.plugin_url+'img/directions.png" style="width:15px; margin-right:5px" /><span class="cspm_infowindow_directions">'+
																							'<a href="https://www.google.com/maps/dir/?api=1&origin='+origin_latLng+'&destination='+destination_latLng+'&destination_place_id='+place.place_id+'&travelmode='+travel_mode.toLowerCase()+'" target="_blank">Directions</a>'+
																						'</span></span>'+
																					'</div>';
																				
																				infowindow = plugin_map.gmap3({get:{name:"infowindow"}});
																				
																				if(infowindow){
																				  infowindow.open(mapObject, marker);
																				  infowindow.setContent(infowindow_content);
																				}else{
																					plugin_map.gmap3({
																						infowindow:{
																							anchor:marker, 
																							options:{
																								content: infowindow_content
																							}
																						}
																					});
																				}
																				
																			}
																			
																		});
																	
																	}else{
					
																		map_message_widget.text(no_route_found_msg).removeClass('fadeOut').addClass('cspm_animated fadeIn').css({'display':'block'});
																		setTimeout(function(){ 
																			map_message_widget.removeClass('fadeIn').addClass('fadeOut');
																			setTimeout(function(){
																				map_message_widget.css({'display':'none'});
																			}, 500);
																		}, 3000);

																		if(typeof NProgress !== 'undefined')
																			NProgress.done();
								
																		return;
						
																	}
															
																}else{
					
																	map_message_widget.text(no_route_found_msg).removeClass('fadeOut').addClass('cspm_animated fadeIn').css({'display':'block'});
																	setTimeout(function(){ 
																		map_message_widget.removeClass('fadeIn').addClass('fadeOut');
																		setTimeout(function(){
																			map_message_widget.css({'display':'none'});
																		}, 500);
																	}, 3000);

																	if(typeof NProgress !== 'undefined')
																		NProgress.done();
								
																	return;
						
																}
													
															}
															
														}
														
													});
	
												}
											}
											
										}
										
									});
				
								}
								
							}
							
							if(pagination.hasNextPage){

								pagination.nextPage();
								
							}else{
								
								/**
								 * Draw a circle around the nearby points of interest */

								if(!draw_circle){
									
									plugin_map.gmap3('get').fitBounds(markers_bounds);
									 											
									if(typeof NProgress !== 'undefined')
										NProgress.done();
										
								}else{
									
									setTimeout(function(){
										
										plugin_map.gmap3({
											circle:{
												tag: 'cspm_nearby_circle_'+map_id,
												options:{
													center: [location_latlng[0], location_latlng[1]],
													radius: eval(radius),
													fillColor: "#0085dc",
													fillOpacity: 0.05,
													strokeColor: "#0085dc",
													strokeOpacity: 0.5,
													strokeWeight: 5,
													editable: edit_circle,
												},										
												callback: function(circle){
	
													plugin_map.gmap3('get').fitBounds(markers_bounds);
													
													if(typeof NProgress !== 'undefined')
														NProgress.done();
												
												},										
												events:{
													radius_changed: function(circle){
														
														if(edit_circle){
															
															/**
															 * Get circle radius */
															 
															var circle_radius_in_meters = circle.getRadius();
																	
															/**
															 * Compare circle radius to min and max distances and redraw circle if needs to */
															
															var readable_distance_unit = (distance_unit == 'METRIC') ? 'Km' : 'Miles';
															
															var map_message_widget = $('div.cspm_map_red_msg_widget[data-map-id='+map_id+']');
															
															/**
															 * Circle should not be more than max radius */
															 
															if(circle_radius_in_meters > max_radius){
																
																circle.setRadius(eval(max_radius));
																
																map_message_widget.text(progress_map_vars.max_search_radius_msg+' ('+Math.floor(max_radius_in_distance_unit)+readable_distance_unit+')').removeClass('fadeOut').addClass('cspm_animated fadeIn').css({'display':'block'});
																setTimeout(function(){ 
																	map_message_widget.removeClass('fadeIn').addClass('fadeOut');
																	setTimeout(function(){
																		map_message_widget.css({'display':'none'});
																	}, 500);
																}, 3000);
																
																$('li.cspm_proximity_name[data-map-id='+map_id+'][data-proximity-id='+proximity_id+']').attr('data-radius', max_radius).attr('data-max-radius-attempt', eval(max_radius_attempt)+1);
															
																var reached_edge = 'max';
																
															/**
															 * Circle should not be less than max radius */
															 
															}else if(circle_radius_in_meters < min_radius){
																
																circle.setRadius(eval(min_radius));
																
																map_message_widget.text(progress_map_vars.min_search_radius_msg+' ('+Math.floor(min_radius_in_distance_unit)+readable_distance_unit+')').removeClass('fadeOut').addClass('cspm_animated fadeIn').css({'display':'block'});
																setTimeout(function(){ 
																	map_message_widget.removeClass('fadeIn').addClass('fadeOut');
																	setTimeout(function(){
																		map_message_widget.css({'display':'none'});
																	}, 500);
																}, 3000);
																
																$('li.cspm_proximity_name[data-map-id='+map_id+'][data-proximity-id='+proximity_id+']').attr('data-radius', min_radius).attr('data-min-radius-attempt', eval(min_radius_attempt)+1);
															
																var reached_edge = 'min';
															
															/**
															 * Circle is between min radius & max radius */
															 
															}else{
																
																var reached_edge = '';
																	
																$('li.cspm_proximity_name[data-map-id='+map_id+'][data-proximity-id='+proximity_id+']').attr('data-radius', circle_radius_in_meters).attr('data-min-radius-attempt', eval(0)).attr('data-max-radius-attempt', eval(0));
																
															}
															
															/**
															 * Research for nearby points of interest */
								 
															setTimeout(function(){
																
																var min_radius_attempt = $('li.cspm_proximity_name[data-map-id='+map_id+'][data-proximity-id='+proximity_id+']').attr('data-min-radius-attempt');
																var max_radius_attempt = $('li.cspm_proximity_name[data-map-id='+map_id+'][data-proximity-id='+proximity_id+']').attr('data-max-radius-attempt');
																
																if((min_radius_attempt == 0 && max_radius_attempt == 0) || (reached_edge == 'min' && min_radius_attempt <= 1) || (reached_edge == 'max' && max_radius_attempt <= 1))
																	$('li.cspm_proximity_name[data-map-id='+map_id+'][data-proximity-id='+proximity_id+']').removeClass('selected').trigger('click');
															
															}, 500);
															
														}
																											
													}
												}
											}
											
										});
									
									}, 500);
									
								}
									
							}
					
						}
								
					}else if(status === google.maps.places.PlacesServiceStatus.ZERO_RESULTS){
					
						map_message_widget.text(progress_map_vars.no_nearby_point_found).removeClass('fadeOut').addClass('cspm_animated fadeIn').css({'display':'block'});
						setTimeout(function(){ 
							map_message_widget.removeClass('fadeIn').addClass('fadeOut');
							setTimeout(function(){
								map_message_widget.css({'display':'none'});
							}, 500);
						}, 3000);
						
						/**
						 * Back to the initial situation */
						 
						$('li.cspm_proximity_name[data-map-id='+map_id+']').removeClass('selected').attr('data-radius', initial_radius).attr('data-min-radius-attempt', 0).attr('data-max-radius-attempt', 0);
						
						if(typeof NProgress !== 'undefined')						
							NProgress.done();
									
						return;
						
					}

				});
				
			}

		});
		
		/**
		 * Switch the travel mode */
		
		$('div.cspm_switch_np_travel_mode').livequery('click', function(){
			
			var map_id = $(this).attr('data-map-id');
			var travel_mode = $(this).attr('data-travel-mode');
			var origin = $(this).attr('data-origin');
			var destination = $(this).attr('data-destination');
			var distance_unit = $(this).attr('data-distance-unit');
			
			if($(this).hasClass('active'))
				return;
		
			jQuery.each($('div.cspm_switch_np_travel_mode[data-map-id='+map_id+'] img'), function(index, element){
				var inactive_icon_src = $(this).attr('data-inactive-src');
				$(this).attr('src', inactive_icon_src);			
			});
			
			$('div.cspm_switch_np_travel_mode[data-map-id='+map_id+']').removeClass('active');
			$(this).addClass('active');			
			
			var this_icon = $('div.cspm_switch_np_travel_mode[data-map-id='+map_id+'][data-travel-mode='+travel_mode+'] img');
			var active_icon_src = this_icon.attr('data-active-src');
			this_icon.attr('src', active_icon_src);			
			
			var plugin_map = jQuery('div#codespacing_progress_map_div_'+map_id);
			var mapObject = plugin_map.gmap3('get');
				
			$('li.cspm_proximity_name[data-map-id='+map_id+']').attr('data-travel-mode', travel_mode);
						
			if(origin == '' && destination == '')
				return;
				
			/**
			 * Convert marker latLng string to array */
			 
			var origin_latlng = origin.replace('(', '').replace(')', '').split(',');
			
			if(typeof origin_latlng[0] === 'undefined' && typeof origin_latlng[1] === 'undefined'){
				
				if(typeof NProgress !== 'undefined')
						NProgress.done();
				
				return;
				
			}
				
			/**
			 * Convert destination latLng string to array */
			 
			var destination_latlng = destination.replace('(', '').replace(')', '').split(',');
			
			if(typeof destination_latlng[0] === 'undefined' && typeof destination_latlng[1] === 'undefined'){
				
				if(typeof NProgress !== 'undefined')
						NProgress.done();
				
				return;
				
			}
						
			if(typeof NProgress !== 'undefined'){

				NProgress.configure({
				  parent: 'div#codespacing_progress_map_div_'+map_id,
				  showSpinner: true
				});				
				
				NProgress.start();
				
			}								
		
			/**
			 * Get route */
														
			var map_message_widget = $('div.cspm_map_red_msg_widget[data-map-id='+map_id+']');
				
			var no_route_found_msg = progress_map_vars.no_route_found_msg.replace('{travel_mode}', travel_mode.toLowerCase());
														
			plugin_map.gmap3({ 
				getroute:{
					options:{
						origin: new google.maps.LatLng(origin_latlng[0], origin_latlng[1]),
						destination: new google.maps.LatLng(destination_latlng[0], destination_latlng[1]),
						travelMode: google.maps.DirectionsTravelMode[travel_mode],
						unitSystem: google.maps.UnitSystem[distance_unit],
					},
					callback: function(response){
						
						if(response){
							
							if(response.status === 'OK'){
								
								/**
								 *  Render the route on the map */
								 
								DirectionsDisplay[map_id].setDirections(response);						
								 
								var route = response.routes[0];
										
								var distance = route.legs[0].distance.text;
								var duration = route.legs[0].duration.text;		

								$('span.cspm_infowindow_distance').text(distance);
								$('span.cspm_infowindow_duration').text(duration);
								
								var directions_url = $('span.cspm_infowindow_directions a').attr('href');
								
								if(typeof directions_url !== 'undefined'){
									
									var directions_url_array = directions_url.split('&');								
								
									var new_directions_url = directions_url_array[0]+'&'+directions_url_array[1]+'&'+directions_url_array[2]+'&'+directions_url_array[3]+'&travelmode='+travel_mode.toLowerCase();
								
									$('span.cspm_infowindow_directions a').attr('href', new_directions_url);
								
								}
								
								if(typeof NProgress !== 'undefined')
									NProgress.done();
							
							}else{

								map_message_widget.text(no_route_found_msg).removeClass('fadeOut').addClass('cspm_animated fadeIn').css({'display':'block'});
								setTimeout(function(){ 
									map_message_widget.removeClass('fadeIn').addClass('fadeOut');
									setTimeout(function(){
										map_message_widget.css({'display':'none'});
									}, 500);
								}, 3000);

								if(typeof NProgress !== 'undefined')
									NProgress.done();

								return;

							}
					
						}else{

							map_message_widget.text(no_route_found_msg).removeClass('fadeOut').addClass('cspm_animated fadeIn').css({'display':'block'});
							setTimeout(function(){ 
								map_message_widget.removeClass('fadeIn').addClass('fadeOut');
								setTimeout(function(){
									map_message_widget.css({'display':'none'});
								}, 500);
							}, 3000);

							if(typeof NProgress !== 'undefined')
								NProgress.done();

							return;

						}
			
					}
					
				}
				
			});
	
		});
		
		/**
		 * Reset proximities list */
		
		$('div.cspm_reset_proximities').livequery('click', function(){
			
			var map_id = $(this).attr('data-map-id');

			if(typeof NProgress !== 'undefined'){

				NProgress.configure({
				  parent: 'div#codespacing_progress_map_div_'+map_id,
				  showSpinner: true
				});				
				
				NProgress.start();
				
			}		
			
			var plugin_map = jQuery('div#codespacing_progress_map_div_'+map_id);
			var mapObject = plugin_map.gmap3('get');
			
			$(this).hide();
		
			/**
			 * Remove selected class name and previous marker latLng from the list items */
			 
			$('li.cspm_proximity_name[data-map-id='+map_id+']').removeClass('selected').attr('data-marker-latlng', '');
			
			/**
			 * Hide the green message */
			 
			$('div.cspm_map_green_msg_widget[data-map-id='+map_id+']').removeClass('fadeIn').addClass('fadeOut');
			setTimeout(function(){
				$('div.cspm_map_green_msg_widget[data-map-id='+map_id+']').css({'display':'none'});
			}, 500);
			
			/**
			 * Scroll to top of the list */
			 
			if(typeof $('div.cspm_proximities_container_'+map_id).mCustomScrollbar === 'function')
				$('div.cspm_proximities_container_'+map_id+' ul').mCustomScrollbar("scrollTo", ['top',null], {timeout: 600});
			
			/**
			 * Clear already displayed proximity markers */
			 
			plugin_map.gmap3({
				clear: {
					tag: ['cspm_nearby_point_'+map_id, 'cspm_nearby_circle_'+map_id]
				}
			});	
				
			/**
			 * Clear already displayed routes */
			
			if(typeof DirectionsDisplay[map_id] !== 'undefined')
				DirectionsDisplay[map_id].setMap(null);
				
			if(typeof NProgress !== 'undefined')						
				NProgress.done();
																		
		});
		
	});
