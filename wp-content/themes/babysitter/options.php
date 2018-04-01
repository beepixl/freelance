<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 */

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet
	$themename = wp_get_theme();
	$themename = preg_replace("/\W/", "_", strtolower($themename) );

	$optionsframework_settings = get_option( 'optionsframework' );
	$optionsframework_settings['id'] = $themename;
	update_option( 'optionsframework', $optionsframework_settings );
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 */

if(!function_exists('optionsframework_options')) {
	function optionsframework_options() {

		// Logo type
		$logo_type = array(
			"image_logo" => __("Image Logo", 'babysitter'),
			"text_logo" => __("Text Logo", 'babysitter')
		);

		// Body Background Defaults
		$bg_body_defaults = array(
			'color' => '#f1f7f9',
			'image' => get_template_directory_uri() . '/images/pattern.gif',
			'repeat' => 'repeat',
			'position' => 'top center',
			'attachment'=>'scroll'
		);

		// Fonts
		$typography_mixed_fonts = array_merge( options_typography_get_os_fonts() , options_typography_get_google_fonts() );
		asort($typography_mixed_fonts);

		// Body Typography
		$typography_body = array(
			'size' => '12px',
			'face' => 'Arial, Helvetica, sans-serif',
			'style' => 'normal',
			'color' => '#919090'
		);
		// Typography Options
		$typography_opt = array(
			'faces' => $typography_mixed_fonts
		);

		// Menu Typography
		$typography_menu = array(
			'size' => '18px',
			'face' => 'Kavoon',
			'style' => 'normal',
			'color' => '#7dbad4'
		);
		// Typography Options
		$typography_menu_opt = array(
			'faces' => $typography_mixed_fonts
		);

		// Headings Typography
		$typography_headings = array(
			'size' => '32px',
			'face' => 'Kavoon',
			'style' => 'normal',
			'color' => '#70b3d0'
		);
		// Headings Typography Options
		$typography_headings_opt = array(
			'faces' => $typography_mixed_fonts
		);

		// Editor Settings
		$wp_editor_settings = array(
			'wpautop' => true, // Default
			'textarea_rows' => 6,
			'tinymce' => array( 'plugins' => 'wordpress' )
		);

		// Pull all the categories into an array
		$options_categories = array();
		$options_categories_obj = get_categories();
		foreach ($options_categories_obj as $category) {
			$options_categories[$category->cat_ID] = $category->cat_name;
		}
		
		// Pull all tags into an array
		$options_tags = array();
		$options_tags_obj = get_tags();
		foreach ( $options_tags_obj as $tag ) {
			$options_tags[$tag->term_id] = $tag->name;
		}


		// Revolution Slider aliases
		if (class_exists('RevSlider')) {
			$theslider     = new RevSlider();
			$arrSliders = $theslider->getArrSliders();
			$arrA     = array();
			$arrT     = array();
			foreach($arrSliders as $slider){
				$arrA[]     = $slider->getAlias();
				$arrT[]     = $slider->getTitle();
			}
			if($arrA && $arrT){
				$all_rev_sliders = array_combine($arrA, $arrT);
			}
			else {
				$all_rev_sliders[0] = 'No Sliders Found';
			}
		}


		// Pull all the pages into an array
		$options_pages = array();
		$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
		$options_pages[''] = 'Select a page:';
		foreach ($options_pages_obj as $page) {
			$options_pages[$page->ID] = $page->post_title;
		}

		// If using image radio buttons, define a directory path
		$imagepath =  get_template_directory_uri() . '/admin/images/';

		$options = array();

		$options[] = array( "name" => "General",
						"type" => "heading");

		$options['logo_type'] = array( 
							"name" => __("What kind of logo?", 'babysitter'),
							"desc" => __("If you select '<em>Image Logo</em>' you can put in the image url in the next option, and if you select '<em>Text Logo</em>' your Site Title will show instead.", 'babysitter'),
							"id" => "logo_type",
							"std" => "image_logo",
							"type" => "radio",
							"options" => $logo_type);

		$options['logo_url'] = array( 
							"name" => __("Logo Image", 'babysitter'),
							"desc" => __("Upload a custom logo for your Site.", 'babysitter'),
							"id" => "logo_url",
							"std" => get_template_directory_uri() . "/images/logo.png",
							"type" => "upload");
							
		$options['favicon'] = array( 
							"name" => __("Favicon", 'babysitter'),
							"desc" => __("Upload a custom favicon for your Site. <i><strong>Format</strong>: ico</i> <i><strong>Size</strong>: 16x16</i>", 'babysitter'),
							"id" => "favicon",
							"std" => get_template_directory_uri() . "/images/favicon.ico",
							"type" => "upload");

		$options['favicon_iphone'] = array( 
							"name" => __("iPhone Favicon", 'babysitter'),
							"desc" => __("Upload a custom iPhone favicon for your Site. <i><strong>Format</strong>: png</i> <i><strong>Size</strong>: 57x57</i> <i>For iPhone 2G/3G/3GS, 2011 iPod Touch and older Android devices </i>", 'babysitter'),
							"id" => "favicon_iphone",
							"std" => get_template_directory_uri() . "/images/apple-touch-icon.png",
							"type" => "upload");

		$options['favicon_ipad'] = array( 
							"name" => __("iPad Favicon", 'babysitter'),
							"desc" => __("Upload a custom iPad favicon for your Site. <i><strong>Format</strong>: png</i> <i><strong>Size</strong>: 72x72</i> <i>For 1st generation iPad, iPad 2 and iPad mini.</i>", 'babysitter'),
							"id" => "favicon_ipad",
							"std" => get_template_directory_uri() . "/images/apple-touch-icon-72x72.png",
							"type" => "upload");

		$options['favicon_iphone_retina'] = array( 
							"name" => __("iPhone Retina Favicon", 'babysitter'),
							"desc" => __("Upload a custom favicon for iPhone/Touch Retina based devices. <i><strong>Format</strong>: png</i> <i><strong>Size</strong>: 114x114</i> <i>For iPhone 4, 4S, 5 and 2012 iPod Touch.</i>", 'babysitter'),
							"id" => "favicon_iphone_retina",
							"std" => get_template_directory_uri() . "/images/apple-touch-icon-114x114.png",
							"type" => "upload");

		$options['favicon_ipad_new'] = array( 
							"name" => __("iPad 3+ Favicon", 'babysitter'),
							"desc" => __("Upload a custom favicon for iPad 3rd and 4th generation. <i><strong>Format</strong>: png</i> <i><strong>Size</strong>: 144x144</i> <i>For iPad 3rd and 4th generation.</i>", 'babysitter'),
							"id" => "favicon_ipad_new",
							"std" => get_template_directory_uri() . "/images/apple-touch-icon-144x144.png",
							"type" => "upload");

		$options['header_info'] = array(
							'name' => __('Header Info', 'babysitter'),
							'desc' => __('You can put some text here (eg phone number) or leave empty.', 'babysitter'),
							'id' => 'header_info',
							'std' => 'Call Us: +1 800 300 4000',
							'class' => 'tiny',
							'type' => 'textarea');

		$options['responsive_design'] = array( 
							"name" => __("Responsive Design", 'babysitter'),
							"desc" => __("Use the responsive design features?", 'babysitter'),
							"id" => "responsive_design",
							"std" => "yes",
							"type" => "select",
							"class" => "mini",
							"options" => array('yes' => 'Yes', 'no' => 'No'));

		$options['layout'] = array( 
							"name" => __("Layout", 'babysitter'),
							"desc" => __("Choose between fullwidth and boxed layouts.", 'babysitter'),
							"id" => "layout",
							"std" => "boxed",
							"type" => "select",
							"class" => "mini",
							"options" => array('full_width' => 'Fullwidth', 'boxed' => 'Boxed'));

		$options['top_bar'] = array( 
							"name" => __("Show Top Bar?", 'babysitter'),
							"desc" => __("Do you want to show Top Bar in the Header?", 'babysitter'),
							"id" => "top_bar",
							"std" => "yes",
							"type" => "select",
							"class" => "mini",
							"options" => array('yes' => 'Yes', 'no' => 'No'));

		$options[] = array( 
							"name" => __("Show footer widgets?", 'babysitter'),
							"desc" => __("Do you want to show footer widgets?", 'babysitter'),
							"id" => "footer_widgets",
							"std" => "yes",
							"type" => "select",
							"class" => "mini",
							"options" => array('yes' => 'Yes', 'no' => 'No'));

		$options['copyright'] = array( 
							"name" => __("Copyright", 'babysitter'),
							"desc" => __("Enter text used in the left side of the footer. HTML tags are allowed.", 'babysitter'),
							"id" => "copyright",
							"std" => "&copy; 2013 babysitter Theme | All rights reserved",
							"type" => "textarea");
		
		$options[] = array( 
							"name" => __("Google Analytics Code", 'babysitter'),
							"desc" => __("You can paste your Google Analytics or other tracking code in this box. This will be automatically added to the footer. <em>Paste your code without &lt;script&gt; tag</em>", 'babysitter'),
							"id" => "ga_code",
							"std" => "",
							"type" => "textarea");






		$options[] = array(
							"name" => __("Styling", 'babysitter'),
							"type" => "heading");

		$options['color_accent'] = array(
							'name' => __('Color 1', 'babysitter'),
							'desc' => __('Primary Color.', 'babysitter'),
							'id' => 'color_accent',
							'std' => '#fc8a58',
							'type' => 'color' );

		$options['color_secondary'] = array(
							'name' => __('Color 2', 'babysitter'),
							'desc' => __('Secondary Color.', 'babysitter'),
							'id' => 'color_secondary',
							'std' => '#c4d208',
							'type' => 'color' );

		$options['color_tertiary'] = array(
							'name' => __('Color 3', 'babysitter'),
							'desc' => __('Tertiary Color.', 'babysitter'),
							'id' => 'color_tertiary',
							'std' => '#7fdbfd',
							'type' => 'color' );

		$options['color_quaternary'] = array(
							'name' => __('Color 4', 'babysitter'),
							'desc' => __('Quaternary Color.', 'babysitter'),
							'id' => 'color_quaternary',
							'std' => '#528cba',
							'type' => 'color' );

		$options['color_quinary'] = array(
							'name' => __('Color 5', 'babysitter'),
							'desc' => __('Quinary Color.', 'babysitter'),
							'id' => 'color_quinary',
							'std' => '#f0f7fa',
							'type' => 'color' );

		$options['color_sextuple'] = array(
							'name' => __('Color 6', 'babysitter'),
							'desc' => __('Sextuple Color.', 'babysitter'),
							'id' => 'color_sextuple',
							'std' => '#70b3d0',
							'type' => 'color' );

		$options['bg_body'] = array(
							'name' =>  __('Body Background', 'babysitter'),
							'desc' => __('Change the body background.', 'babysitter'),
							'id' => 'bg_body',
							'std' => $bg_body_defaults,
							'type' => 'background' );

		$options['bg_content'] = array(
							'name' => __('Content Background Color', 'babysitter'),
							'desc' => __('Background color for content box.', 'babysitter'),
							'id' => 'bg_content',
							'std' => '#ffffff',
							'type' => 'color' );

		$options[] = array( 
							"name" => __("Custom CSS", 'babysitter'),
							"desc" => __("Want to add any custom CSS code? Put in here, and the rest is taken care of. This overrides any other stylesheets. eg: a {color:red}", 'babysitter'),
							"id" => "custom_css",
							"std" => "",
							"type" => "textarea");



		$options[] = array(
							"name" => __("Typography", 'babysitter'),
							"type" => "heading");


		$options['typography_body'] = array( 'name' => __('Body', 'babysitter'),
							'desc' => __('Body typography.', 'babysitter'),
							'id' => "typography_body",
							'std' => $typography_body,
							'type' => 'typography',
							'options' => $typography_opt );

		$options['typography_menu'] = array( 'name' => __('Navigation', 'babysitter'),
							'desc' => __('Customize typography for main navigation.', 'babysitter'),
							'id' => "typography_menu",
							'std' => $typography_menu,
							'type' => 'typography',
							'options' => $typography_menu_opt );

		$options['typography_menu_text_hover'] = array(
							'name' => __('Navigation Text Color on Hover', 'babysitter'),
							'desc' => __('Choose navigation text color on hover.', 'babysitter'),
							'id' => 'typography_menu_text_hover',
							'std' => '#fff',
							'type' => 'color' );

		$options['typography_submenu_text'] = array(
							'name' => __('Sub Navigation Text Color', 'babysitter'),
							'desc' => __('Choose subnavigation text color.', 'babysitter'),
							'id' => 'typography_submenu_text',
							'std' => '#fff',
							'type' => 'color' );

		$options['typography_submenu_text_hover'] = array(
							'name' => __('Sub Navigation Text Color on Hover', 'babysitter'),
							'desc' => __('Choose subnavigation text color on hover.', 'babysitter'),
							'id' => 'typography_submenu_text_hover',
							'std' => '#fff',
							'type' => 'color' );

		$options['typography_submenu_bg_hover'] = array(
							'name' => __('Sub Navigation background on Hover', 'babysitter'),
							'desc' => __('Choose subnavigation background color on hover.', 'babysitter'),
							'id' => 'typography_submenu_bg_hover',
							'std' => '#70b3d0',
							'type' => 'color' );

		$options['typography_h1'] = array( 'name' => __('H1 Heading', 'babysitter'),
							'id' => "typography_h1",
							'std' => array(
								'size' => '32px',
								'face' => 'Kavoon',
								'style' => 'normal',
								'color' => '#70b3d0'
							),
							'type' => 'typography',
							'options' => $typography_opt);

		$options['typography_h2'] = array( 'name' => __('H2 Heading', 'babysitter'),
							'id' => "typography_h2",
							'std' => array(
								'size' => '24px',
								'face' => 'Kavoon',
								'style' => 'normal',
								'color' => '#70b3d0'
							),
							'type' => 'typography',
							'options' => $typography_opt);

		$options['typography_h3'] = array( 'name' => __('H3 Heading', 'babysitter'),
							'id' => "typography_h3",
							'std' => array(
								'size' => '18px',
								'face' => 'Kavoon',
								'style' => 'normal',
								'color' => '#70b3d0'
							),
							'type' => 'typography',
							'options' => $typography_opt);

		$options['typography_h4'] = array( 'name' => __('H4 Heading', 'babysitter'),
							'id' => "typography_h4",
							'std' => array(
								'size' => '16px',
								'face' => 'Kavoon',
								'style' => 'normal',
								'color' => '#70b3d0'
							),
							'type' => 'typography',
							'options' => $typography_opt);

		$options['typography_h5'] = array( 'name' => __('H5 Heading', 'babysitter'),
							'id' => "typography_h5",
							'std' => array(
								'size' => '14px',
								'face' => 'Kavoon',
								'style' => 'normal',
								'color' => '#70b3d0'
							),
							'type' => 'typography',
							'options' => $typography_opt);

		$options['typography_h6'] = array( 'name' => __('H6 Heading', 'babysitter'),
							'id' => "typography_h6",
							'std' => array(
								'size' => '12px',
								'face' => 'Kavoon',
								'style' => 'normal',
								'color' => '#70b3d0'
							),
							'type' => 'typography',
							'options' => $typography_opt);

		$options['typography_heading'] = array( 'name' => __('Page Title', 'babysitter'),
							'desc' => __("Select a custom font to be used for Page Title.", 'babysitter'),
							'id' => "typography_heading",
							'std' => $typography_headings,
							'type' => 'typography',
							'options' => $typography_headings_opt);


		$options[] = array( "name" => "Social",
							"type" => "heading");

		$options['social_twitter'] = array(
							'name' => __('Twitter Link', 'babysitter'),
							'desc' => __('Put link to your Twitter account.', 'babysitter'),
							'id' => 'social_twitter',
							'std' => '#',
							'class' => 'tiny',
							'type' => 'text');

		$options['social_facebook'] = array(
							'name' => __('Facebook Link', 'babysitter'),
							'desc' => __('Put link to your Facebook account.', 'babysitter'),
							'id' => 'social_facebook',
							'std' => '#',
							'class' => 'tiny',
							'type' => 'text');

		$options['social_google'] = array(
							'name' => __('Google+ Link', 'babysitter'),
							'desc' => __('Put link to your Google+ account.', 'babysitter'),
							'id' => 'social_google',
							'std' => '#',
							'class' => 'tiny',
							'type' => 'text');

		$options['social_dribbble'] = array(
							'name' => __('Dribbble Link', 'babysitter'),
							'desc' => __('Put link to your Dribbble account.', 'babysitter'),
							'id' => 'social_dribbble',
							'std' => '#',
							'class' => 'tiny',
							'type' => 'text');

		$options['social_youtube'] = array(
							'name' => __('YouTube Link', 'babysitter'),
							'desc' => __('Put link to your YouTube account.', 'babysitter'),
							'id' => 'social_youtube',
							'std' => '#',
							'class' => 'tiny',
							'type' => 'text');

		$options['social_pinterest'] = array(
							'name' => __('Pinterest Link', 'babysitter'),
							'desc' => __('Put link to your Pinterest account.', 'babysitter'),
							'id' => 'social_pinterest',
							'std' => '#',
							'class' => 'tiny',
							'type' => 'text');

		$options['social_instagram'] = array(
							'name' => __('Instagram Link', 'babysitter'),
							'desc' => __('Put link to your Instagram account.', 'babysitter'),
							'id' => 'social_instagram',
							'std' => '#',
							'class' => 'tiny',
							'type' => 'text');

		$options['social_linkedin'] = array(
							'name' => __('LinkedIn Link', 'babysitter'),
							'desc' => __('Put link to your LinkedIn account.', 'babysitter'),
							'id' => 'social_linkedin',
							'std' => '#',
							'class' => 'tiny',
							'type' => 'text');

		$options['social_yelp'] = array(
							'name' => __('Yelp Link', 'babysitter'),
							'desc' => __('Put link to your Yelp account.', 'babysitter'),
							'id' => 'social_yelp',
							'std' => '',
							'class' => 'tiny',
							'type' => 'text');

		$options['social_rss'] = array(
							'name' => __('RSS Link', 'babysitter'),
							'desc' => __('Put link to your rss feed.', 'babysitter'),
							'id' => 'social_rss',
							'std' => '#',
							'class' => 'tiny',
							'type' => 'text');

		$options['social_email'] = array(
							'name' => __('Email Address', 'babysitter'),
							'desc' => __('Put your email address.', 'babysitter'),
							'id' => 'social_email',
							'std' => '',
							'class' => 'tiny',
							'type' => 'text');



		$options[] = array( 
							"name" => __("Slider", 'babysitter'),
							"type" => "heading");

		$options['flex_title'] = array( 
							"name" => __("Display Box Title?", 'babysitter'), 
							"desc" => __("Do you want to display box title on slide?", 'babysitter'),
							"id" => "flex_title",
							"std" => 'true',
							"type" => "radio",
							"options" => array(
								'true' => 'Yes',
								'false' => 'No'));

		$options['flex_effect'] = array( 
							"name" => __("Slider Effect", 'babysitter'), 
							"desc" => __("Select your animation type.", 'babysitter'),
							"id" => "flex_effect",
							"std" => 'fade',
							"type" => "select",
							"options" => array(
								'fade' => 'fade', 
								'slide' => 'slide'));

		$options['flex_startat'] = array( 
							"name" => __("Start at", 'babysitter'), 
							"desc" => __("The slide that the slider should start on. Array notation (0 = first slide).", 'babysitter'),
							"id" => "flex_startat",
							"std" => "0",
							"type" => "text",
							"class" => "tiny");

		$options['flex_autoplay'] = array( 
							"name" => __("Autoplay", 'babysitter'), 
							"desc" => __("Auto play the slider or not.", 'babysitter'),
							"id" => "flex_autoplay",
							"std" => "true",
							"type" => "radio",
							"options" => array(
								'false' => 'No',
								'true' => 'Yes'));

		$options['flex_slideshowspeed'] = array( 
							"name" => __("Slideshow Speed", 'babysitter'), 
							"desc" => __("Set the speed of the slideshow cycling, in milliseconds", 'babysitter'),
							"id" => "flex_slideshowspeed",
							"std" => "7000",
							"type" => "text",
							"class" => "tiny");

		$options['flex_controls'] = array( 
							"name" => __("Controls", 'babysitter'),
							"desc" => __("Display slide controls (1,2,3 etc.)?", 'babysitter'),
							"id" => "flex_controls",
							"std" => "true",
							"type" => "radio",
							"options" => array(
								'false' => 'No',
								'true' => 'Yes'));

		$options['flex_directionnav'] = array( 
							"name" => __("Previous/next", 'babysitter'),
							"desc" => __("Create navigation for previous/next navigation?", 'babysitter'),
							"id" => "flex_directionnav",
							"std" => "true",
							"type" => "radio",
							"options" => array(
								'false' => 'No',
								'true' => 'Yes'));

		$options['flex_randomize'] = array( 
							"name" => __("Randomize", 'babysitter'), 
							"desc" => __("Randomize slide order.", 'babysitter'),
							"id" => "flex_randomize",
							"std" => "false",
							"type" => "radio",
							"class" => 'slider_flexslider',
							"options" => array(
								'false' => 'No',
								'true' => 'Yes'));





		$options[] = array( 
							"name" => __("Blog", 'babysitter'),
							"type" => "heading");

		$options[] = array( 
							"name" => __("Blog Title", 'babysitter'),
							"desc" => __("Enter Your Blog Title used on Blog page.", 'babysitter'),
							"id" => "blog_text",
							"std" => "Blog",
							"type" => "text");
		
		$options['blog_sidebar'] = array( 
							"name" => __("Sidebar position", 'babysitter'),
							"desc" => __("Choose sidebar position.", 'babysitter'),
							"id" => "blog_sidebar",
							"std" => "right",
							"type" => "images",
							"options" => array(
								'left' => $imagepath . '2cl.png',
								'right' => $imagepath . '2cr.png',
								'fullblog' => $imagepath . '1col.png')
							);

		$options['post_meta'] = array(
							"name" => __("Post Meta Box", 'babysitter'),
							"desc" => __("The metabox is displayed by default. <em>Un-check this box to disable this functionality and hide metaboxes.</em>", 'babysitter'),
							"id" => "post_meta",
							'std' => '1',
							'type' => 'checkbox');




		$options[] = array( 
							"name" => __("Contacts", 'babysitter'),
							"type" => "heading");

		$options[] = array( 
							"name" => __("Show Google map?", 'babysitter'),
							"desc" => __("Do you want to show google map?", 'babysitter'),
							"id" => "gmap_show",
							"std" => "yes",
							"type" => "select",
							"class" => "mini",
							"options" => array('yes' => 'Yes', 'no' => 'No'));

		$options['gmap_coord'] = array( 
							"name" => __("Google Map", 'babysitter'),
							"desc" => __("Google map coordinates.", 'babysitter'),
							"id" => "gmap_coord",
							"std" => "57.669645,11.926832",
							"type" => "text");

		$options['gmap_zoom'] = array( 
							"name" => __("Zoom Level", 'babysitter'),
							"desc" => __("Google map zoom level.", 'babysitter'),
							"id" => "gmap_zoom",
							"std" => "14",
							"type" => "text");

		$options['contact_title'] = array( 
							"name" => __("Contact Title", 'babysitter'),
							"desc" => __("Title for contact info block.", 'babysitter'),
							"id" => "contact_title",
							"std" => "Contact Info",
							"type" => "text");

		$options['contact_map_title'] = array( 
							"name" => __("Google Map Title", 'babysitter'),
							"desc" => __("Title for Google Map (used on Contact v2 Template).", 'babysitter'),
							"id" => "contact_map_title",
							"std" => "Our Location",
							"type" => "text");

		$options['contact_address'] = array( 
							"name" => __("Address", 'babysitter'),
							"desc" => __("Put your address here.", 'babysitter'),
							"id" => "contact_address",
							"std" => "24 Fifth st., Los Angeles, USA",
							"type" => "text");

		$options['contact_phone'] = array( 
							"name" => __("Phone", 'babysitter'),
							"desc" => __("Put your phone here.", 'babysitter'),
							"id" => "contact_phone",
							"std" => "+1 (003) 460-055-274",
							"type" => "text");

		$options['contact_mail'] = array( 
							"name" => __("Email", 'babysitter'),
							"desc" => __("Put your email address here.", 'babysitter'),
							"id" => "contact_mail",
							"std" => "info@dan-fisher.com",
							"type" => "text");

		$options['contact_site'] = array( 
							"name" => __("Site", 'babysitter'),
							"desc" => __("Put your site url here.", 'babysitter'),
							"id" => "contact_site",
							"std" => "www.dan-fisher.com",
							"type" => "text");

		$options['contact_info'] = array( 
							"name" => __("Info", 'babysitter'),
							"desc" => __("Put your additional info here. W P L O C K E R .C O M - ", 'babysitter'),
							"id" => "contact_info",
							"std" => "Monday-Friday: 9:00-17:00<br>Saturday: 10:00-16:00<br>Sunday: Closed",
							"type" => "textarea");

		$options[] = array( 
							"name" => __("Jobs", 'babysitter'),
							"type" => "heading");

		$options[] = array( 
							"name" => __("Currency for Jobs", 'babysitter'),
							"desc" => __("Please select currency shows on job listing pages.", 'babysitter'),
							"id" => "job_currency",
							"std" => "usd",
							"type" => "select",
							"class" => "mini",
							"options" => array(
								'usd' => 'USD', 
								'eur' => 'EUR',
								'gbp' => 'GBP',
								'inr' => 'INR',
								'jpy' => 'JPY',
								'cny' => 'CNY',
								'krw' => 'KRW',
								'btc' => 'BTC'));

		return $options;
	};

}


/**
* Front End Customizer
*
* WordPress 3.4 Required
*/
add_action( 'customize_register', 'babysitter_register' );

if(!function_exists('babysitter_register')) {

	function babysitter_register($wp_customize) {
		/**
		 * This is optional, but if you want to reuse some of the defaults
		 * or values you already have built in the options panel, you
		 * can load them into $options for easy reference
		 */
		$options = optionsframework_options();



		/*-----------------------------------------------------------------------------------*/
		/*	Logo
		/*-----------------------------------------------------------------------------------*/
		
		$wp_customize->add_section( 'babysitter_logo', array(
			'title' => __( 'Logo', 'babysitter' ),
			'priority' => 201
		) );
		
		/* Logo Type */
		$wp_customize->add_setting( 'babysitter[logo_type]', array(
				'default' => $options['logo_type']['std'],
				'type' => 'option'
		) );
		$wp_customize->add_control( 'babysitter_logo_type', array(
				'label' => $options['logo_type']['name'],
				'section' => 'babysitter_logo',
				'settings' => 'babysitter[logo_type]',
				'type' => $options['logo_type']['type'],
				'choices' => $options['logo_type']['options']
		) );
		
		/* Logo Path */
		$wp_customize->add_setting( 'babysitter[logo_url]', array(
			'type' => 'option'
		) );
		
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'logo_url', array(
			'label' => $options['logo_url']['name'],
			'section' => 'babysitter_logo',
			'settings' => 'babysitter[logo_url]'
		) ) );
		
		
		/*-----------------------------------------------------------------------------------*/
		/*	Styling
		/*-----------------------------------------------------------------------------------*/
		$wp_customize->add_section( 'babysitter_header', array(
			'title' => __( 'Styling', 'babysitter' ),
			'priority' => 200
		));

		/* Primary color */
		$wp_customize->add_setting( 'babysitter[color_accent]', array(
			'default' => $options['color_accent']['std'],
			'type' => 'option'
		) );
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_accent', array(
			'label'   => $options['color_accent']['name'],
			'section' => 'babysitter_header',
			'settings'   => 'babysitter[color_accent]',
			'priority' => 10
		) ) );

		/* Secondary color */
		$wp_customize->add_setting( 'babysitter[color_secondary]', array(
			'default' => $options['color_secondary']['std'],
			'type' => 'option'
		) );
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_secondary', array(
			'label'   => $options['color_secondary']['name'],
			'section' => 'babysitter_header',
			'settings'   => 'babysitter[color_secondary]',
			'priority' => 11
		) ) );

		/* Tertiary color */
		$wp_customize->add_setting( 'babysitter[color_tertiary]', array(
			'default' => $options['color_tertiary']['std'],
			'type' => 'option'
		) );
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_tertiary', array(
			'label'   => $options['color_tertiary']['name'],
			'section' => 'babysitter_header',
			'settings'   => 'babysitter[color_tertiary]',
			'priority' => 12
		) ) );


		/* Quaternary color */
		$wp_customize->add_setting( 'babysitter[color_quaternary]', array(
			'default' => $options['color_quaternary']['std'],
			'type' => 'option'
		) );
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_quaternary', array(
			'label'   => $options['color_quaternary']['name'],
			'section' => 'babysitter_header',
			'settings'   => 'babysitter[color_quaternary]',
			'priority' => 13
		) ) );


		/* Quinary color */
		$wp_customize->add_setting( 'babysitter[color_quinary]', array(
			'default' => $options['color_quinary']['std'],
			'type' => 'option'
		) );
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_quinary', array(
			'label'   => $options['color_quinary']['name'],
			'section' => 'babysitter_header',
			'settings'   => 'babysitter[color_quinary]',
			'priority' => 14
		) ) );


		/* Sextuple color */
		$wp_customize->add_setting( 'babysitter[color_sextuple]', array(
			'default' => $options['color_sextuple']['std'],
			'type' => 'option'
		) );
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_sextuple', array(
			'label'   => $options['color_sextuple']['name'],
			'section' => 'babysitter_header',
			'settings'   => 'babysitter[color_sextuple]',
			'priority' => 15
		) ) );


		/* Body Background Image*/
		$wp_customize->add_setting( 'babysitter[bg_body][image]', array(
			'default' => $options['bg_body']['std']['image'],
			'type' => 'option'
		) );
		
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bg_body_image', array(
			'label'   => 'Body background image',
			'section' => 'babysitter_header',
			'settings'   => 'babysitter[bg_body][image]',
			'priority' => 15
		) ) );

		/* Body Background Color*/
		$wp_customize->add_setting( 'babysitter[bg_body][color]', array(
			'default' => $options['bg_body']['std']['color'],
			'type' => 'option'
		) );
		
		$wp_customize->add_control( new WP_Customize_color_Control( $wp_customize, 'bg_body_color', array(
			'label'   => 'Body background color',
			'section' => 'babysitter_header',
			'settings'   => 'babysitter[bg_body][color]',
			'priority' => 16
		) ) );


		/* Content Background Xolor */
		$wp_customize->add_setting( 'babysitter[bg_content]', array(
			'default' => $options['bg_content']['std'],
			'type' => 'option'
		) );
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bg_content', array(
			'label'   => $options['bg_content']['name'],
			'section' => 'babysitter_header',
			'settings'   => 'babysitter[bg_content]',
			'priority' => 17
		) ) );



		/*-----------------------------------------------------------------------------------*/
		/*	Typography
		/*-----------------------------------------------------------------------------------*/

		$wp_customize->add_section( 'babysitter_typo', array(
			'title' => __( 'Typography', 'babysitter' ),
			'priority' => 201
		));
		
		/* Body font face */
		$wp_customize->add_setting( 'babysitter[typography_body][face]', array(
			'default' => $options['typography_body']['std']['face'],
			'type' => 'option'
		) );
		
		$wp_customize->add_control( 'babysitter_typography_body', array(
			'label' => $options['typography_body']['name'],
			'section' => 'babysitter_typo',
			'settings' => 'babysitter[typography_body][face]',
			'type' => 'select',
			'choices' => $options['typography_body']['options']['faces'],
			'priority' => 10
		) );

		/* Primary Heading font face */
		$wp_customize->add_setting( 'babysitter[typography_heading][face]', array(
			'default' => $options['typography_heading']['std']['face'],
			'type' => 'option'
		) );
		
		$wp_customize->add_control( 'babysitter_typography_heading', array(
			'label' => $options['typography_heading']['name'],
			'section' => 'babysitter_typo',
			'settings' => 'babysitter[typography_heading][face]',
			'type' => 'select',
			'choices' => $options['typography_heading']['options']['faces'],
			'priority' => 11
		) );

		/* Navigation */
		$wp_customize->add_setting( 'babysitter[typography_menu][face]', array(
			'default' => $options['typography_menu']['std']['face'],
			'type' => 'option'
		) );
		
		$wp_customize->add_control( 'babysitter_typography_menu', array(
			'label' => $options['typography_menu']['name'],
			'section' => 'babysitter_typo',
			'settings' => 'babysitter[typography_menu][face]',
			'type' => 'select',
			'choices' => $options['typography_menu']['options']['faces'],
			'priority' => 12
		) );


		/* H1 */
		$wp_customize->add_setting( 'babysitter[typography_h1][face]', array(
			'default' => $options['typography_h1']['std']['face'],
			'type' => 'option'
		) );
		
		$wp_customize->add_control( 'babysitter_typography_h1', array(
			'label' => $options['typography_h1']['name'],
			'section' => 'babysitter_typo',
			'settings' => 'babysitter[typography_h1][face]',
			'type' => 'select',
			'choices' => $options['typography_h1']['options']['faces'],
			'priority' => 13
		) );

		/* H2 */
		$wp_customize->add_setting( 'babysitter[typography_h2][face]', array(
			'default' => $options['typography_h2']['std']['face'],
			'type' => 'option'
		) );
		
		$wp_customize->add_control( 'babysitter_typography_h2', array(
			'label' => $options['typography_h2']['name'],
			'section' => 'babysitter_typo',
			'settings' => 'babysitter[typography_h2][face]',
			'type' => 'select',
			'choices' => $options['typography_h2']['options']['faces'],
			'priority' => 14
		) );

		/* H3 */
		$wp_customize->add_setting( 'babysitter[typography_h3][face]', array(
			'default' => $options['typography_h3']['std']['face'],
			'type' => 'option'
		) );
		
		$wp_customize->add_control( 'babysitter_typography_h3', array(
			'label' => $options['typography_h3']['name'],
			'section' => 'babysitter_typo',
			'settings' => 'babysitter[typography_h3][face]',
			'type' => 'select',
			'choices' => $options['typography_h3']['options']['faces'],
			'priority' => 15
		) );

		/* H4 */
		$wp_customize->add_setting( 'babysitter[typography_h4][face]', array(
			'default' => $options['typography_h4']['std']['face'],
			'type' => 'option'
		) );
		
		$wp_customize->add_control( 'babysitter_typography_h4', array(
			'label' => $options['typography_h4']['name'],
			'section' => 'babysitter_typo',
			'settings' => 'babysitter[typography_h4][face]',
			'type' => 'select',
			'choices' => $options['typography_h4']['options']['faces'],
			'priority' => 16
		) );

		/* H5 */
		$wp_customize->add_setting( 'babysitter[typography_h5][face]', array(
			'default' => $options['typography_h5']['std']['face'],
			'type' => 'option'
		) );
		
		$wp_customize->add_control( 'babysitter_typography_h5', array(
			'label' => $options['typography_h5']['name'],
			'section' => 'babysitter_typo',
			'settings' => 'babysitter[typography_h5][face]',
			'type' => 'select',
			'choices' => $options['typography_h5']['options']['faces'],
			'priority' => 17
		) );

		/* H6 */
		$wp_customize->add_setting( 'babysitter[typography_h6][face]', array(
			'default' => $options['typography_h6']['std']['face'],
			'type' => 'option'
		) );
		
		$wp_customize->add_control( 'babysitter_typography_h6', array(
			'label' => $options['typography_h6']['name'],
			'section' => 'babysitter_typo',
			'settings' => 'babysitter[typography_h6][face]',
			'type' => 'select',
			'choices' => $options['typography_h6']['options']['faces'],
			'priority' => 18
		) );


		
	};

}