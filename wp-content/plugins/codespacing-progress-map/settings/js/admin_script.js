function disableEnterKey(e)
{
     var key;

     if(window.event)
          key = window.event.keyCode;     //IE
     else
          key = e.which;     //firefox

     if(key == 13)
          return false;
     else
          return true;
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
		   
	// Control over the menu

	$("li#"+cspacing_admin_vars.first_section+"").addClass('current');
	
	$("div[class^=custom_section_]").hide();
	
	$("ul.codespacing_menu li").livequery('click', function(){
		$('div.cspm_admin_square_loader').show();
		$('div[class^=custom_section_]').hide();		
		var id = $(this).attr('id');		
		$("li").removeClass('current');
		$(this).addClass('current');
		$(".custom_section_" + id + "").show('fast', function(){
			$.cookie('codespacing_admin_menu', id, { expires: 1 });
			$('div.cspm_admin_square_loader').hide();
		});
	});	

	if($.cookie('codespacing_admin_menu') == null){
		$(".custom_section_"+cspacing_admin_vars.first_section+"").show();
		$("li").removeClass('current');
		$("li#"+cspacing_admin_vars.first_section+"").addClass('current');
	}else{
		$(".custom_section_" + $.cookie('codespacing_admin_menu') + "").show();
		$("li").removeClass('current');
		$("li#" + $.cookie('codespacing_admin_menu') + "").addClass('current');
	}
	
	
	// Show loader on form submit
	
	$(".cspm_submit input[type=submit]").livequery('click', function(){
		$('div.cspm_admin_btm_square_loader').show();
	});
	
	
	// Customize Checkboxes and Radios button
	
	/*$('div[class^=custom_section_] input').iCheck({
	  checkboxClass: 'icheckbox_minimal-blue',
	  radioClass: 'iradio_minimal-blue',
	  increaseArea: '20%',
	});*/
	
	
	// Add CSS class to the to large lists
	
	$("div[class^=custom_section_] div.items_status").addClass('cspm_list_items');
	$("div[class^=custom_section_] div.authors").addClass('cspm_list_items');
		
	
	// Add colspan attr to <th></th> of subtitle section
		
	$('span.section_sub_title, span.section_sub_title_child').parent().parent().next('div.cspm_field').remove();
	$('span.section_sub_title, span.section_sub_title_child').parent().parent().addClass('cspm_colspan_2');
	
	
	// Move the demo link outside the label

    $(".cspm_demo_tag").each(function(){
		$(this).insertAfter($(this).parent());
	});
	
	
	// ToolTip jQuery
	
	$.fn.qtip.styles.mystyle = {
	   width: 400,
	   height: 300,
	   padding: 0,
	   background: 0,
	   border:{
		   width:0
	   }
	}
	
	$('label[id=cspm_layoutsettings_main_layout_mu-cd]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/mu-cd.png" />'
	});		
	
	$('label[id=cspm_layoutsettings_main_layout_md-cu]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/md-cu.png" />'
	});		
	
	$('label[id=cspm_layoutsettings_main_layout_mr-cl]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/mr-cl.png" />'
	});		
	
	$('label[id=cspm_layoutsettings_main_layout_ml-cr]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/ml-cr.png" />'
	});		
	
	$('label[id=cspm_layoutsettings_main_layout_m-con], label[id=cspm_layoutsettings_main_layout_fit-in-map-top-carousel], label[id=cspm_layoutsettings_main_layout_fullscreen-map-top-carousel]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/map-c-top.png" />'
	});		

	$('label[id=cspm_itemssettings_items_view_listview]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/horizontal.jpg" />'
	});		
	
	$('label[id=cspm_itemssettings_items_view_gridview]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/vertical.jpg" />'
	});		
	
	$('label[id=cspm_mapsettings_infowindow_type_content_style]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/customized-infowindow.jpg" />'
	});		
	
	$('label[id=cspm_mapsettings_infowindow_type_bubble_style]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/bubble-overlay.jpg" />'
	});		
	
	$('label[id=cspm_mapsettings_mapTypeControl_true], label[id=cspm_mapsettings_mapTypeControl_false]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/map-type.jpg" />'
	});		
	
	$('label[id=cspm_mapsettings_streetViewControl_true], label[id=cspm_mapsettings_streetViewControl_false]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/streetview.jpg" />'
	});		
	
	$('label[id=cspm_mapsettings_scrollwheel_true], label[id=cspm_mapsettings_scrollwheel_false]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/scroll-wheel.jpg" />'
	});		
	
	$('label[id=cspm_mapsettings_panControl_true], label[id=cspm_mapsettings_panControl_false]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/pancontrol.jpg" />'
	});		
	
	$('label[id=cspm_mapsettings_zoomControl_true], label[id=cspm_mapsettings_zoomControl_false]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/default-zoom.jpg" />'
	});		
	
	$('label[id=cspm_mapsettings_zoomControlType_customize]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/customized-zoom.jpg" />'
	});		
	
	$('label[id=cspm_mapsettings_zoomControlType_default]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/default-zoom.jpg" />'
	});		
	
	$('label[id=cspm_infoboxsettings_infobox_type_square_bubble]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/square_bubble.jpg" />'
	});		
	
	$('label[id=cspm_infoboxsettings_infobox_type_rounded_bubble]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/rounded_bubble.jpg" />'
	});		
	
	$('label[id=cspm_infoboxsettings_infobox_type_cspm_type1]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/infobox_1.jpg" />'
	});		
	
	$('label[id=cspm_infoboxsettings_infobox_type_cspm_type2]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/infobox_2.jpg" />'
	});		
	
	$('label[id=cspm_infoboxsettings_infobox_type_cspm_type3]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/infobox_3.jpg" />'
	});		
	
	$('label[id=cspm_infoboxsettings_infobox_type_cspm_type4]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'settings/img/tooltips/infobox_4.jpg" />'
	});		


	// jQuery Validate
	
	$("#cspm_form").validate({
		wrapper: "em",
		rules: {
			"cspm_settings[cspm_generalsettings_post_type]": "required",
			
			"cspm_settings[cspm_layoutsettings_layout_fixed_width]":{
				number: true
			},						
			"cspm_settings[cspm_layoutsettings_layout_fixed_height]":{
				required: true,
				number: true
			},
			
			"cspm_settings[cspm_mapsettings_map_center]": "required",
			
			"cspm_settings[cspm_carouselsettings_carousel_scroll]":{
				required: true,
				number: true
			},
			"cspm_settings[cspm_carouselsettings_carousel_auto]":{
				required: true,
				number: true
			},

			"cspm_settings[cspm_itemssettings_horizontal_image_size]": "required",
			"cspm_settings[cspm_itemssettings_horizontal_details_size]": "required",
			"cspm_settings[cspm_itemssettings_vertical_image_size]": "required",
			"cspm_settings[cspm_itemssettings_vertical_details_size]": "required",
			
		}
	});
	
	
	// Multi select
		
	$('.cspm_multi_select').multiSelect({
		selectableHeader: "<input type='text' class='cspm-search-input' autocomplete='off' placeholder='Search'>",
		selectionHeader: "<input type='text' class='cspm-search-input' autocomplete='off' placeholder='Search'>",
		selectableFooter: "Selectable items",
		selectionFooter: "Selected items",
		afterInit: function(ms){
			var that = this,
				$selectableSearch = that.$selectableUl.prev(),
				$selectionSearch = that.$selectionUl.prev(),
				selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
				selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';
			
			that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
			.on('keydown', function(e){
			  if (e.which === 40){
				that.$selectableUl.focus();
				return false;
			  }
			});
			
			that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
			.on('keydown', function(e){
			  if (e.which == 40){
				that.$selectionUl.focus();
				return false;
			  }
			});
		},
		afterSelect: function(){
			this.qs1.cache();
			this.qs2.cache();
		},
		afterDeselect: function(){
			this.qs1.cache();
			this.qs2.cache();
		}		
	});
	$('a.cspm_ms_select_all').click(function(){
		var id = $(this).attr('id');
		$('.cspm_multi_select#'+id+'').multiSelect('select_all');
		return false;
	});
	$('a.cspm_ms_deselect_all').click(function(){
		var id = $(this).attr('id');
		$('.cspm_multi_select#'+id+'').multiSelect('deselect_all');
		return false;
	});	
	$('a.cspm_ms_refresh').livequery('click', function(){
		var id = $(this).attr('id');
		$('.cspm_multi_select#'+id+'').multiSelect('refresh');		
	});
	
	
	// Open the tag fields container
	
	$('div.cspm_add_tag').livequery('click', function(){
		
		var id = $(this).attr('id');
		var helpers_id = $(this).attr('data-helpers-container-id');
		
		// Show the submit button
		$('a[class=cspm_submit_tag_form][data-helpers-id='+helpers_id+']').show(function(){
			// Hide the update button
			$('a[class=cspm_update_tag_form][data-helpers-id='+helpers_id+']').hide();		
		});		
		
		$('input[type=text][id^=tag_'+helpers_id+']').each(function(){ $(this).val(''); });
				
		if(!$('div.cspm_add_tag_container#'+id+'').is(":visible"))
		$('div.cspm_add_tag_container#'+id+'').slideToggle(500);
		
	});
	
	// Cancel
	
	$('a.cspm_cancel_tag_form').livequery('click', function(){
		
		var helpers_id = $(this).attr('data-helpers-id');
		
		// Show the submit button
		$('a[class=cspm_submit_tag_form][data-helpers-id='+helpers_id+']').show(function(){
			// Hide the update button
			$('a[class=cspm_update_tag_form][data-helpers-id='+helpers_id+']').hide();		
		});		

		$('input[type=text][id^=tag_'+helpers_id+']').each(function(){ $(this).val(''); });
		
		if($('div[class=cspm_add_tag_container][data-helpers-container-id='+helpers_id+']').is(":visible"))
		$('div[class=cspm_add_tag_container][data-helpers-container-id='+helpers_id+']').slideToggle(500);
		
	});
	
	// Add/Edit tag		
	
	var updated_tag = [];
	
	$('a.cspm_submit_tag_form, a.cspm_update_tag_form').livequery('click', function(){

		// Get the helpers id
		var helpers_id = $(this).attr('data-helpers-id');
		
		var clicked_element = $(this).attr('class');
		
		// Validate the form
		jQuery.validator.setDefaults({
			success: "valid",
			wrapper: "em",
		});
		
		var form = $("#cspm_form");
		
		form.validate();
				
		if(!form.valid()){
			
			jQuery('html, body').animate({
				scrollTop: jQuery("div[class=cspm_add_tag_container][data-helpers-container-id="+helpers_id+"]").offset().top
			});
				
		}else{
			
			// The old value of the textarea containing the tags
			var textarea_val = $('textarea[data-helpers-textarea='+helpers_id+']').val();
			
			var tag_fields_val = {};
			
			// Get the label of the tag
			var tag_label = $('input[id=tag_'+helpers_id+'_label]').val();
			var tag_name = $('input[id=tag_'+helpers_id+'_name]').val();
			
			var multi_input_values = [];
			
			// Loop throught each field in the tag container and collecte the field values
			$('input[type=text][id^=tag_'+helpers_id+'], input[type=hidden][id^=tag_'+helpers_id+'], input[type=radio][id^=tag_'+helpers_id+']:checked, input[type=checkbox][id^=tag_'+helpers_id+']:checked, textarea[id^=tag_'+helpers_id+'], select[id^=tag_'+helpers_id+']').each(function() {
				
				if(!($(this).attr('name') in tag_fields_val)){
				
					// Checkbox array			
					if($(this).is("input[type=checkbox]")){
						
						var name_array = $(this).attr('name').split('[]');
						var name = name_array[0];
						
						// Clear the array
						if(jQuery.inArray($(this).attr('value'), tag_fields_val[name]) > -1)
							multi_input_values = [];	
						
						if(name_array.length == 2){
							
							multi_input_values.push($(this).attr('value'));
							tag_fields_val[name] = multi_input_values;
							
						}else{
							
							tag_fields_val[$(this).attr('name')] = $(this).val();
						
						}
					
					// Multiple select
					}else if($(this).is("select[multiple=multiple]")){
						
						// Clear the array
						multi_input_values = [];
						
						var name_array = $(this).attr('name').split('[]');
						var name = name_array[0];
						var select_id = $(this).attr('id');
						
						if(name_array.length == 2){
							
							// Loop throught all the list
							$('div#ms-'+select_id+' div.ms-selection ul li').each(function(){
								
								// Get the visible items in the list
								if($(this).is(":visible")){
									
									// Get the item caption
									//var caption = $(this).find('span').html();
									var caption = $(this).attr('id').split('-')[0];
									
									var option_value = caption;
							
									multi_input_values.push(option_value);
									tag_fields_val[name] = multi_input_values;
									
								}
								
							});
							
						}
						
					}else{
						
						tag_fields_val[$(this).attr('name')] = $(this).val();
						
					}
					
				}
				
			});
				
			var new_field_obj = {};
			new_field_obj[tag_name] = tag_fields_val;
			
			if(textarea_val != ''){
			
				var old_fields_obj = {};
				old_fields_obj = JSON.parse(textarea_val);
				
				var new_textarea_val = jQuery.extend({}, old_fields_obj, new_field_obj);
				
			}else var new_textarea_val = new_field_obj;

			// Copy the fields in the textarea
			$('textarea[data-helpers-textarea='+helpers_id+']').val(JSON.stringify(new_textarea_val));
			
			if(clicked_element == "cspm_update_tag_form"){
				
				// Update the tag container
				if(tag_label)
					$('div.cspm_tags_container.'+helpers_id+' div[class=cspm_tag_container][data-tag-name='+tag_name+'] strong.cspm_tag_label').empty().html(tag_label);
		
			}else{
			
				// Create the new tag container
				$('div.cspm_tags_container.'+helpers_id+'').append('<div class="cspm_tag_container" data-helpers-id="'+helpers_id+'" data-tag-name="'+tag_name+'"><strong>'+tag_label+'</strong><span class="cspm_remove_tag" data-helpers-id="'+helpers_id+'" data-tag-name="'+tag_name+'">Remove</span><span class="cspm_update_tag" data-helpers-id="'+helpers_id+'" data-tag-name="'+tag_name+'">Update</span></div>');
			
			}
			
			// Close the tag helpers container
			if($('div[class=cspm_add_tag_container][data-helpers-container-id='+helpers_id+']').is(":visible"))
				$('div[class=cspm_add_tag_container][data-helpers-container-id='+helpers_id+']').slideToggle(500, function(){
					jQuery('html, body').animate({
						scrollTop: jQuery('div.cspm_add_tag[data-helpers-container-id='+helpers_id+']').offset().top-150
					},function(){
						$('div.cspm_tag_warning[data-helpers-container-id='+helpers_id+']').fadeIn(500);
						updated_tag.push(1);
					});	
				});
		
		}
		
	});
	
	
	// Sort tags
	
	/*$('div.cspm_tags_container').sortable({
		
		stop: function(event, ui) {
			
			var helpers_id = $(this).attr('data-helpers-id');
			var order = [];
	
			$('div.cspm_tags_container div.cspm_tag_container[data-helpers-id='+helpers_id+']').each(function(i, el){
				var tag_name = $(el).attr('data-tag-name');
				order.push(tag_name);
			});
			
			// The old value of the textarea containing the tags
			var textarea_val = $('textarea[data-helpers-textarea='+helpers_id+']').val();
			
			var new_field_obj = {};			
			
			if(textarea_val != '' && order.length > 1){
			
				var old_fields_obj = {};
				old_fields_obj = JSON.parse(textarea_val);
				
				for (var i=0; i < order.length; i++){
					new_field_obj[order[i]] = old_fields_obj[order[i]];
				}

			}
			
			// Copy the fields in the textarea
			$('textarea[data-helpers-textarea='+helpers_id+']').val(JSON.stringify(new_field_obj));
			
			$('div.cspm_tag_warning[data-helpers-container-id='+helpers_id+']').fadeIn(500);
			
			updated_tag.push(1);

		}
		
	}).disableSelection();*/
	
	
	// Remove tag
	
	$('span.cspm_remove_tag').livequery('click', function(){
		
		var result = confirm("Are you sure?");
		
		if (result == true) {
	
			// Get the helpers id
			var helpers_id = $(this).attr('data-helpers-id');
			
			// Get the name of the tag to be removed
			var tag_name = $(this).attr('data-tag-name');

			// Get the value of the textarea containing the tags
			var textarea_val = JSON.parse($('textarea[data-helpers-textarea='+helpers_id+']').val());
						
			// Remove the tag
			delete textarea_val[tag_name];
			
			// Copy the fields in the textarea
			$('textarea[data-helpers-textarea='+helpers_id+']').val(JSON.stringify(textarea_val));
			
			// Create the new tag container
			$('div[class=cspm_tag_container][data-helpers-id='+helpers_id+'][data-tag-name='+tag_name+']').remove();
			
			$('div.cspm_tag_warning[data-helpers-container-id='+helpers_id+']').fadeIn(500);
			
			updated_tag.push(1);

		}else return;
		
	});
	
	
	// Set tag container with data when Update tag request is sent

	$('span.cspm_update_tag').livequery('click', function(){
	
		// Get the helpers id
		var helpers_id = $(this).attr('data-helpers-id');

		// Get the name of the tag to be updated	
		var tag_name = $(this).attr('data-tag-name');
					
		// Get the value of the textarea containing the tags
		var textarea_val = JSON.parse($('textarea[data-helpers-textarea='+helpers_id+']').val());

		// Loop throught each field in the tag container and add the fields values
		$('input[type=text][id^=tag_'+helpers_id+'], input[type=hidden][id^=tag_'+helpers_id+'], input[type=radio][id^=tag_'+helpers_id+'], input[type=checkbox][id^=tag_'+helpers_id+'], textarea[id^=tag_'+helpers_id+'], select[id^=tag_'+helpers_id+']').each(function(index, value) {

			// Checkbox and radio
			if($(this).is("input[type=radio]")){
		
				var field_name = $(this).attr('name');
				
				var checked_value = textarea_val[tag_name][field_name];

				$('input[name="'+field_name+'"]').each(function(){
					
					var checked = ($(this).attr('value') === checked_value);
					
					// The attribute should be set or unset...
					if(checked){
						//$(this).iCheck('check'); 
						$(this).prop('checked', true);
					}else{
						//$(this).iCheck('uncheck');
						$(this).prop('checked', false);
					}
					
				});
			
			}else if($(this).is("input[type=checkbox]")){
		
				var field_name = $(this).attr('name');
				var field_val = $(this).attr('value');		
				
				var name_array = field_name.split('[]');
				
				// Check from the name if the checkbox is an array
				if(name_array.length == 2){
				
					// If the checbox is an array
					// Get its elements from the data stored in the textarea
					var checkbox_array_elements = textarea_val[tag_name][name_array[0]];
					
					// Loop throught the checkbox elements
					$('input[name="'+field_name+'"]').each(function(){
						
						// Check the cheboxes if its value exists in the elements stored in the textarea
						var checked = jQuery.inArray($(this).attr('value'), checkbox_array_elements);
						
						// The attribute should be set or unset...
						if(checked > -1){
							//$(this).iCheck('check'); 
							$(this).prop('checked', true);
						}else{
							//$(this).iCheck('uncheck');
							$(this).prop('checked', false);
						}
						
					});
					
				}else{
						
					var checked_value = textarea_val[tag_name][field_name];
					var checked = (field_val === checked_value);
						
					if(checked){
						//$(this).iCheck('check'); 
						$(this).prop('checked', true);
					}else{
						//$(this).iCheck('uncheck');
						$(this).prop('checked', false);
					}
				
				}
							
			// Multiple select
			}else if($(this).is("select[multiple=multiple]")){
				
				var name_array = $(this).attr('name').split('[]');
				var field_name = name_array[0];
				var select_id = $(this).attr('id');
				
				if(name_array.length == 2){
					
					// Loop throught all the list // selection
					$('div#ms-'+select_id+' div.ms-selectable ul li').each(function(){
						
						var caption = $(this).attr('id').split('-')[0];
						
						// Hide the list item if it exists in the array
						if(jQuery.inArray(caption, textarea_val[tag_name][field_name]) > -1){
							
							$('.cspm_multi_select#'+field_name+'').multiSelect('deselect', caption);
							
						}
						
					});

					$('div#ms-'+select_id+' div.ms-selection ul li').each(function(){
						
						var caption = $(this).attr('id').split('-')[0];
						
						// show the list item if it exists in the array
						if(jQuery.inArray(caption, textarea_val[tag_name][field_name]) > -1){
							
							$('.cspm_multi_select#'+field_name+'').multiSelect('select', caption);
							
						}
						
					});
					
				}
				
			}else{
		
				var field_name = $(this).attr('name');
				var field_val = textarea_val[tag_name][field_name];	
					
				$(this).val(field_val);
				
			}
			
        });
		
		// Hide the submit button
		$('a[class=cspm_submit_tag_form][data-helpers-id='+helpers_id+']').hide(function(){
			// Show the update button and add to it the number of the tag to be updated
			$('a[class=cspm_update_tag_form][data-helpers-id='+helpers_id+']').attr('data-tag-name', tag_name).show();		
		});		
		
		// Open the tag helpers container
		if(!$('div[class=cspm_add_tag_container][data-helpers-container-id='+helpers_id+']').is(":visible"))
			$('div[class=cspm_add_tag_container][data-helpers-container-id='+helpers_id+']').slideToggle(500);
		
	});
	
	// Check if there's any unsaved tag data
	$(window).on('beforeunload', function(){
		
		if(updated_tag.length > 0)
			return 'You\'ve made changes on your settings which aren\'t saved. If you leave you will lose these changes.';
		
	});
	
	$('form#cspm_form').submit(function() {

   		$(window).unbind('beforeunload');
   
	});
	
	// Exception code for the "marker image" tags with a label field & name field
	$('select#tag_marker_img_category').livequery('change', function(){
		
		$('input#tag_marker_img_label').val($(this).find(":selected").html());
		
		$('input#tag_marker_img_name').val($(this).find(":selected").val());
		
	});
	
	// Regenerate Markers
	$('a#cspm_troubleshooting_regenerate_markers_link').livequery('click', function(){
		
		var result = confirm("Are you sure you want to regenerate your markers?");
		
		if (result == true) {
			
			$('span.cspm_blink_text').fadeIn();
					
			jQuery.post(
				cspacing_admin_vars.ajax_url,
				{
					action: 'cspm_regenerate_markers',
				},
				function(data){	
					$('span.cspm_blink_text').fadeOut();				
					setTimeout(function(){ alert("Done"); }, 1000);
				}
			);
		
		}else return;
		
	});
				
});
	
Cufon.replace("div[class^=custom_section_] > h3, ul.codespacing_menu li, div[class^=custom_section_] > p, input.custom-button-primary, span.section_sub_title");			

