<?php

/*-----------------------------------------------------------------------------------*/
/*	Column Shortcodes
/*-----------------------------------------------------------------------------------*/

if (!function_exists('grid_column')) {
	function grid_column($atts, $content=null, $shortcodename ="")
	{	
		extract(shortcode_atts(array(
			'class' => ''
		), $atts));
		
		//remove wrong nested <p>
		$content = remove_invalid_tags($content, array('p'));

		// add divs to the content
		$return = '<div class="'.$shortcodename.' '.$class.'">';
		$return .= do_shortcode($content);
		$return .= '</div>';

		return $return;
	}
	add_shortcode('grid_1', 'grid_column');
	add_shortcode('grid_2', 'grid_column');
	add_shortcode('grid_3', 'grid_column');
	add_shortcode('grid_4', 'grid_column');
	add_shortcode('grid_5', 'grid_column');
	add_shortcode('grid_6', 'grid_column');
	add_shortcode('grid_7', 'grid_column');
	add_shortcode('grid_8', 'grid_column');
	add_shortcode('grid_9', 'grid_column');
	add_shortcode('grid_10', 'grid_column');
	add_shortcode('grid_11', 'grid_column');
	add_shortcode('grid_12', 'grid_column');
}

// Clear
if (!function_exists('shortcode_clear')) {
	function shortcode_clear() {
		return '<div class="clear"></div>';
	}

	add_shortcode('clear', 'shortcode_clear');
}




/*-----------------------------------------------------------------------------------*/
/*	Buttons
/*-----------------------------------------------------------------------------------*/

if (!function_exists('button')) {
	function button( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'link' => '#',
			'style' => 'primary',
			'target' => '_self',
			'size' => 'normal',
			'text' => 'Button Text',
			'icon' => 'none'
	    ), $atts));

	   $output =  '<a target="'.$target.'" class="button button__'.$size.' button__'.$style.'" href="'.$link.'">';
			if($icon !== 'none') {
				$output .= '<i class="'.$icon.'"></i>';
			}
			$output .= $text;
		$output .= '</a>';

		return $output;
	}
	add_shortcode('button', 'button');
}


/*-----------------------------------------------------------------------------------*/
/*	Alerts
/*-----------------------------------------------------------------------------------*/

if (!function_exists('alert')) {
	function alert( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'style'   => 'error'
	    ), $atts));
		
	   return '<div class="alert alert-'.$style.'">' . do_shortcode($content) . '</div>';
	}
	add_shortcode('alert', 'alert');
}


// Old alerts (for version v1.3.0 and below)

//warning
if (!function_exists('shortcode_warning')) {
	function shortcode_warning($args, $content) {
		return '<div class="alert alert-warning">'.do_shortcode($content).'</div>';
	}
	add_shortcode('warning', 'shortcode_warning');
}

//info
if (!function_exists('shortcode_info')) {
	function shortcode_info($args, $content) {
		return '<div class="alert alert-info">'.do_shortcode($content).'</div>';
	}
	add_shortcode('info', 'shortcode_info');
}

//success
if (!function_exists('shortcode_success')) {
	function shortcode_success($args, $content) {
		return '<div class="alert alert-success">'.do_shortcode($content).'</div>';
	}
	add_shortcode('success', 'shortcode_success');
}



/*-----------------------------------------------------------------------------------*/
/*	Horizontal Rules
/*-----------------------------------------------------------------------------------*/

if (!function_exists('hr_shortcode')) {
	function hr_shortcode( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'style'   => 'default'
	    ), $atts));
		
	   return '<div class="hr hr__'.$style.'"></div>';
	}
	add_shortcode('hr', 'hr_shortcode');
}

// Old rules (for version v1.3.0 and below)
if (!function_exists('hr_doubled_shortcode')) {
	function hr_doubled_shortcode($atts, $content = null) {

		$output = '<div class="hr hr__bold"></div>';

	   return $output;
	}
	add_shortcode('hr_double', 'hr_doubled_shortcode');
}



/*-----------------------------------------------------------------------------------*/
/*	Box
/*-----------------------------------------------------------------------------------*/

if (!function_exists('box_shortcode')) {
	function box_shortcode( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'title' => '',
			'maintext' => '',
			'linktext' => '',
		   'url' => ''
	    ), $atts));

	   $output =  '<div class="box clearfix">';
			$output .= '<h3>';
				$output .= $title;
			$output .= '</h3>';
			$output .= '<p>';
				$output .= $maintext;
			$output .= '</p>';
			if($linktext !== "") {
				$output .= '<a href="'.$url.'" class="button">';
					$output .= $linktext;
				$output .= '</a>';
			}
		$output .= '</div>';

		return $output;
	}
	add_shortcode('box', 'box_shortcode');
}



/*-----------------------------------------------------------------------------------*/
/*	Spacer
/*-----------------------------------------------------------------------------------*/

if (!function_exists('babysitter_spacer_shortcode')) {
	function babysitter_spacer_shortcode( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'size'   => 'default'
	    ), $atts));
		
	   return '<div class="spacer spacer__'.$size.'"></div>';
	}
	add_shortcode('spacer', 'babysitter_spacer_shortcode');
}

// Old small spacer (for version v1.3.0 and below)
if (!function_exists('babysitter_spacer_small_shortcode')) {
	function babysitter_spacer_small_shortcode($atts, $content = null) {
		$output = '<div class="spacer spacer__small"></div>';
	   return $output;
	}

	add_shortcode('spacer_small', 'babysitter_spacer_small_shortcode');
}



/*-----------------------------------------------------------------------------------*/
/*	Posts
/*-----------------------------------------------------------------------------------*/

if (!function_exists('shortcode_posts')) {
	function shortcode_posts($atts, $content = null) {
			
		extract(shortcode_atts(array(
			'type' => 'post',
			'order' => 'post_date',
			'cat_slug' => '',											 
			'num' => '4'
		), $atts));

		$output = '<ul class="thumbs-list">';

		global $post;
		
		$args = array(
			'post_type' => $type,
			'numberposts' => $num,
			'orderby' => $order,
			'category_name' => $cat_slug,
			'order' => 'DESC'
		);

		$latest = get_posts($args);
		
		foreach($latest as $post) {
				setup_postdata($post);
				$format = get_post_format();

				$output .= '<li class="list-item clearfix">';

				if ( has_post_thumbnail($post->ID) ){
					$output .= '<figure class="thumb thumb__hovered"><a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
						$output .= get_the_post_thumbnail($post->ID, 'thumb-sm');
					$output .= '</a></figure>';
				} else {
					if($format!="") {
						$output .= '<figure class="thumb thumb__hovered"><a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
							$output .= '<img src="'.get_template_directory_uri() . '/images/empty-'.$format.'-alt.jpg" alt="" />';
						$output .= '</a></figure>';
					} else {
						$output .= '<figure class="thumb thumb__hovered"><a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
							$output .= '<img src="'.get_template_directory_uri() . '/images/empty-standard-alt.jpg" alt="" />';
						$output .= '</a></figure>';
					}
					
				}
				$output .= '<span class="date">';
					$output .= get_the_time(get_option('date_format'));
				$output .= '</span>';
				$output .= '<h5 class="item-heading"><a href="'.get_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
						$output .= get_the_title($post->ID);
				$output .= '</a></h5>';
				
			$output .= '</li>';
				
		}
				
		$output .= '</ul>';
		return $output;
			
	}

	add_shortcode('posts', 'shortcode_posts');
}




/*-----------------------------------------------------------------------------------*/
/*	Team
/*-----------------------------------------------------------------------------------*/

if (!function_exists('team_shortcode')) {
	function team_shortcode($atts, $content = null) {

			extract(shortcode_atts(array(
				'num' => '3',
				'item_class' => 'grid_4',
				'linkto' => 'post',
				'cat_slug' => '',
				'offset' => ''
			), $atts));

			$team = get_posts('post_type=team&orderby=menu_order&order=ASC&team_category='.$cat_slug.'&offset='.$offset.'&numberposts='.$num);

			$output = '<div class="team-holder clearfix">';
			
			global $post;

			foreach($team as $post){
					setup_postdata($post);
					$position =  get_post_meta(get_the_ID(), 'babysitter_team_role', true);
					$twitter =  get_post_meta(get_the_ID(), 'babysitter_team_twitter', true);
					$facebook =  get_post_meta(get_the_ID(), 'babysitter_team_facebook', true);
					$googleplus =  get_post_meta(get_the_ID(), 'babysitter_team_gplus', true);
					$dribbble =  get_post_meta(get_the_ID(), 'babysitter_team_dribbble', true);
					$linkedin =  get_post_meta(get_the_ID(), 'babysitter_team_linkedin', true);
					$excerpt = get_the_excerpt();

					$output .= '<div class="item-team '.$item_class.'">';

						if ( has_post_thumbnail($post->ID) ){
							if ($linkto == 'yes') {
								$output .= '<figure class="alignnone team-thumb"><a href="'.get_permalink($post->ID).'">';
									$output .= get_the_post_thumbnail($post->ID, 'thumb-single');
								$output .= '</a></figure>';
							} else {
								$output .= '<figure class="alignnone team-thumb">';
									$output .= get_the_post_thumbnail($post->ID, 'thumb-single');
								$output .= '</figure>';
							}
						}

						$output .= '<hgroup class="team-title">';
							if ($linkto == 'post') {
								$output .= '<h4><a href="'.get_permalink($post->ID).'">';
									$output .= get_the_title($post->ID);
								$output .= '</a></h4>';
							} else {
								$output .= '<h4>';
									$output .= get_the_title($post->ID);
								$output .= '</h4>';
							}	
							$output .= '<p>';
								$output .= $position;
							$output .= '</p>';
						$output .= '</hgroup>';
						
						$output .= '<div class="team-excerpt">';
							$output .= $excerpt;
						$output .= '</div>';

						$output .= '<footer class="team-footer">';
							$output .= '<ul class="social-links social-links__small">';
							if($twitter!="") {
								$output .= '<li class="link-twitter"><a href="'.$twitter.'"><i class="icon-twitter"></i></a></li>';
							}
							if($facebook!="") {
								$output .= '<li class="link-facebook"><a href="'.$facebook.'"><i class="icon-facebook"></i></a></li>';
							}
							if($googleplus!="") {
								$output .= '<li class="link-googleplus"><a href="'.$googleplus.'"><i class="icon-google-plus"></i></a></li>';
							}
							if($dribbble!="") {
								$output .= '<li class="link-dribbble"><a href="'.$dribbble.'"><i class="icon-dribbble"></i></a></li>';
							}
							if($linkedin!="") {
								$output .= '<li class="link-linkedin"><a href="'.$linkedin.'"><i class="icon-linkedin"></i></a></li>';
							}
							$output .= '</ul>';
						$output .= '</footer>';
							
					$output .= '</div>';

			}

			$output .= '</div>';

			return $output;

	}

	add_shortcode('team', 'team_shortcode');
}



/*-----------------------------------------------------------------------------------*/
/*	Testimonial
/*-----------------------------------------------------------------------------------*/
if (!function_exists('testimonial_shortcode')) {
	function testimonial_shortcode($atts, $content = null) {

		extract(shortcode_atts(
			array(
				'text' => '',
				'name' => 'Dan Fisher',
				'info' => 'Freelance Web Developer',
				'img_url' => '',
				'gender' => 'female'
		 ), $atts));

		if($gender=="female") {
			$gender = "<i class='icon-female'></i>";
		} else {
			$gender = "<i class='icon-male'></i>";
		}

		$output = '<div class="testimonial clearfix">';
			$output .= '<div class="testi-body">';
				$output .= do_shortcode($content);
			$output .= '</div>';
			$output .= '<div class="testi-meta">';
				$output .= '<span class="testi-author-img">';
					if($img_url != "") {
						$output .= '<img src="'.$img_url.'" height="36" width="36" alt="">';
					} else {
						$output .= $gender;
					}
				$output .= '</span>';
				$output .= '<strong class="testi-author-name">';
					$output .= $name;
				$output .= '</strong><br>';
				$output .= '<span class="testi-author-info">';
					$output .= $info;
				$output .= '</span>';
			$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
	add_shortcode('testimonial', 'testimonial_shortcode');
}



/*-----------------------------------------------------------------------------------*/
/*	Tabs Shortcodes
/*-----------------------------------------------------------------------------------*/

if (!function_exists('tabs_new')) {
	function tabs_new( $atts, $content = null ) {
		extract(shortcode_atts(
			array(
				'type' => 'hor',
		 ), $atts));
		
		STATIC $i = 0;
		$i++;

		// Extract the tab titles for use in the tab widget.
		preg_match_all( '/tab title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );
		
		$tab_titles = array();
		if( isset($matches[1]) ){ $tab_titles = $matches[1]; }
		
		$output = '';
		$tabs_type = '';

		if($type == 'ver') {
			$tabs_type = 'tabs__vertical';
		}
		
		if( count($tab_titles) ){
			$output .= '<div id="tabs-'. $i .'" class="tabs clearfix '.$tabs_type.'"><div class="tab-menu">';
			$output .= '<ul>';
			
			foreach( $tab_titles as $tab ){
				$output .= '<li><a href="#tab-'. sanitize_title( $tab[0] ) .'">' . $tab[0] . '</a></li>';
			}
		    
			$output .= '</ul>';
			$output .= '<div class="clear"></div>';
			$output .= '</div><!-- .tab-menu (end) -->';
			$output .= '<div class="tab-wrapper">';
			$output .= do_shortcode( $content );
			$output .= '</div></div>';
		} else {
			$output .= '<div class="tab-wrapper">';
			$output .= do_shortcode( $content );
			$output .= '</div></div>';
		}
		
		return $output;
	}
	add_shortcode( 'tabs_new', 'tabs_new' );
}

if (!function_exists('tab')) {
	function tab( $atts, $content = null ) {
		$defaults = array( 'title' => 'Tab' );
		extract( shortcode_atts( $defaults, $atts ) );
		
		return '<div id="tab-'. sanitize_title( $title ) .'" class="tab">'. do_shortcode( $content ) .'</div>';
	}
	add_shortcode( 'tab', 'tab' );
}




/*-----------------------------------------------------------------------------------*/
/*	Pricing Tables
/*-----------------------------------------------------------------------------------*/

if (!function_exists('pricing_shortcode')) {
	function pricing_shortcode($atts, $content = null) {

		extract(shortcode_atts(
			array(
				'cols' => '4'
		 ), $atts));

		if($cols=='4') {
			$cols = 'four-cols';
		} elseif($cols=='3'){
			$cols = 'three-cols';
		} else {
			$cols = 'two-cols';
		}

		$output = '<div class="pricing-tables clearfix '.$cols.'">';
		$output .= do_shortcode($content);
		$output .= '</div>';

		return $output;
	}
	add_shortcode('pricing_tables', 'pricing_shortcode');
}


if (!function_exists('pricing_col_shortcode')) {
	function pricing_col_shortcode($atts, $content = null) {

		extract(shortcode_atts(
			array(
				'name' => '',
				'price' => '$ 0',
				'link_txt' => 'Sign Up',
				'link_url' => '#',
				'active' => ''
		), $atts));

		if($active == "yes") {
			$active = "active";
		}

		$output = '<div class="pr-column">';
			$output .= '<div class="single-pricing-table '.$active.'">';
				$output .= '<header class="pr-head">';
					$output .= '<h4>'.$name.'</h4>';
					$output .= '<h3 class="price">'.$price.'</h3>';
				$output .= '</header>';

				$output .= '<div class="pr-features">';
					$output .= do_shortcode($content);
				$output .= '</div>';

				$output .= '<footer class="pr-foot">';
					$output .= '<a href="'.$link_url.'">';
						$output .= $link_txt;
					$output .= '</a>';
				$output .= '</footer>';

			$output .= '</div>';
		$output .= '</div>';

		return $output;

	}
	add_shortcode('pricing_col', 'pricing_col_shortcode');
}




/*-----------------------------------------------------------------------------------*/
/*	Icon Box
/*-----------------------------------------------------------------------------------*/
if (!function_exists('icobox_shortcode')) {
	function icobox_shortcode($atts, $content = null) {
		extract(shortcode_atts(
			array(
				'icon' => 'icon-leaf',
				'title' => '',
			   'text' => '',
			   'linktext' => '',
			   'color' => 'primary',
			   'icon_color' => '#ffffff',
			   'url' => '',
			   'target' => '',
			   'custom_icon_url' => ''
	   ), $atts));


		$output =  '<div class="ico-box ico-box__'.$color.'">';

			$output .= '<div class="ico-holder">';
				if($custom_icon_url != "") {
					$output .= '<img src="'.$custom_icon_url.'" alt="" />';
				} else {
					$output .= '<i class="'.$icon.'" style="color:'.$icon_color.'"></i>';
				}
			$output .= '</div>';
			$output .= '<div class="ico-body inner-wrapper">';
				if($title) {
					$output .= '<h3>';
						$output .= $title;
					$output .= '</h3>';
				}
				$output .= '<p>';
					$output .= $text;
				$output .= '</p>';

				$output .= '<a href="'.$url.'" target="'.$target.'"><strong>';
					$output .= $linktext;
				$output .= '</strong></a>';
			$output .= '</div>';
		$output .= '</div>';

	   return $output;

	}
	add_shortcode('icobox', 'icobox_shortcode');
}



/*-----------------------------------------------------------------------------------*/
/*	Call to Action
/*-----------------------------------------------------------------------------------*/
if (!function_exists('cta_shortcode')) {
	function cta_shortcode($atts, $content = null) {
		extract(shortcode_atts(
			array(
				'title' => 'Title',
				'subtitle' => 'Subtitle goes here',
				'text' => 'Click Here',
			   'link' => ''
	   ), $atts));
	    
		$output =  '<div class="cta clearfix">';
			$output .= '<div class="cta-inner">';
				$output .= '<h2>';
					$output .= $title;
				$output .= '</h2>';
				$output .= $subtitle;
			$output .= '</div>';
			$output .= '<div class="cta-button-holder">';
				$output .= '<a href="'.$link.'" class="button button__large">';
					$output .= $text;
				$output .= '</a>';
			$output .= '</div>';
		$output .= '</div>';

	   return $output;

	}
	add_shortcode('cta', 'cta_shortcode');
}



/*-----------------------------------------------------------------------------------*/
/*	Jumbotron
/*-----------------------------------------------------------------------------------*/
if (!function_exists('jumbotron_shortcode')) {
	function jumbotron_shortcode($atts, $content = null) {
		extract(shortcode_atts(
			array(
				'title' => 'Title',
				'subtitle' => 'Subtitle goes here',
				'btn_text' => 'Click Here',
			   'btn_link' => '#',
			   'align' => 'center',
	   ), $atts));
	    
		$output =  '<div class="hero-unit clearfix txt-'.$align.'">';
			$output .= '<h1>';
				$output .= $title;
			$output .= '</h1>';
			$output .= '<div class="hero-unit-desc suffix_1 prefix_1">';
				$output .= $subtitle;
			$output .= '</div>';
			$output .= '<a href="'.$btn_link.'" class="button button__large">';
				$output .= $btn_text;
			$output .= '</a>';
		$output .= '</div>';

	   return $output;

	}
	add_shortcode('jumbotron', 'jumbotron_shortcode');
}





/*-----------------------------------------------------------------------------------*/
/*	Info Box
/*-----------------------------------------------------------------------------------*/
if (!function_exists('infobox_shortcode')) {
	function infobox_shortcode($atts, $content = null) {
		extract(shortcode_atts(
			array(
				'title' => 'Title',
				'num' => '1',
				'color' => 'primary',
				'background' => 'no',
				'url' => '',
				'arrow' => 'yes'
	   ), $atts));

	   if($background == "no") {
			$background = "info-box__nobg";
		}

		if($arrow == "yes") {
			$arrow = "info-box__arrow";
		}

		if($url != "") {
			$output =  '<a href="'.$url.'" class="info-box info-box__'.$color.' '.$background.' '.$arrow.'">';
		} else {
			$output =  '<div class="info-box info-box__'.$color.' '.$background.' '.$arrow.'">';
		}
	    
			$output .= '<span class="info-box-num">';
				$output .= '<span class="info-box-num-inner">'.$num.'</span>';
			$output .= '</span>';
			$output .= '<div class="inner-wrapper">';
				$output .= '<h2 class="info-box-title">';
					$output .= $title;
				$output .= '</h2>';
				$output .= '<div class="info-box-txt">';
					$output .= do_shortcode($content);
				$output .= '</div>';
			$output .= '</div>';

		if($url != "") {
			$output .=  '</a>';
		} else {
			$output .= '</div>';
		}

	   return $output;

	}
	add_shortcode('infobox', 'infobox_shortcode');
}



/*-----------------------------------------------------------------------------------*/
/*	Link
/*-----------------------------------------------------------------------------------*/
if (!function_exists('link_shortcode')) {
	function link_shortcode($args, $content) {
		return '<span class="link"><span>'.do_shortcode($content).'<span> &nbsp; &rarr;</span>';
	}
	add_shortcode('link', 'link_shortcode');
}



/*-----------------------------------------------------------------------------------*/
/*	List Styles
/*-----------------------------------------------------------------------------------*/

if (!function_exists('list_shortcode')) {
	function list_shortcode( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'style'   => 'checklist',
			'color'   => 'primary'
	    ), $atts));

		if($style == 'none') {
			return '<div class="list__unstyled">' . do_shortcode($content) . '</div>';
		} else {
			return '<div class="list list-style__'.$style.' list-color__'.$color.'">' . do_shortcode($content) . '</div>';
		}
	  
	}
	add_shortcode('list', 'list_shortcode');
}




/*-----------------------------------------------------------------------------------*/
/*	Dropcaps
/*-----------------------------------------------------------------------------------*/

if (!function_exists('dropcap_shortcode')) {
	function dropcap_shortcode($atts, $content = null) {

		extract(shortcode_atts(
			array(
				'style' => 'primary'
		), $atts));

		$output = '<span class="dropcap dropcap__'.$style.'">';
		$output .= do_shortcode($content);
		$output .= '</span>';

		return $output;
	}
	add_shortcode('dropcap', 'dropcap_shortcode');
}




/*-----------------------------------------------------------------------------------*/
/*	Text Lead
/*-----------------------------------------------------------------------------------*/

if (!function_exists('txtlead_shortcode')) {
	function txtlead_shortcode($atts, $content = null) {

		$output = '<p class="lead">';
		$output .= do_shortcode($content);
		$output .= '</p>';

		return $output;
	}
	add_shortcode('txt_lead', 'txtlead_shortcode');
}





/*-----------------------------------------------------------------------------------*/
/*	Accordion
/*-----------------------------------------------------------------------------------*/

if (!function_exists('accordion_shortcode')) {
	function accordion_shortcode($atts, $content = null) {

		$output = '<dl class="accordion-wrapper">';
		$output .= do_shortcode($content);
		$output .= '</dl>';

		return $output;
	}
	add_shortcode('accordion', 'accordion_shortcode');
}

if (!function_exists('panel_shortcode')) {
	function panel_shortcode($atts, $content = null) {

		extract(shortcode_atts(
			array(
				'title' => 'Title goes here',
				'state' => ''
		 ), $atts));

		if($state == 'open') {
			$state = 'active';
		}

		$output = '<dt class="acc-head '.$state.'">';
			$output .= '<a href="#">';
				$output .= $title;
			$output .= '</a>';
		$output .= '</dt>';

		$output .= '<dd class="acc-body">';
			$output .= '<div class="content">';
				$output .= do_shortcode($content);
			$output .= '</div>';
		$output .= '</dd><!-- //.acc-body -->';

		return $output;

	}
	add_shortcode('panel', 'panel_shortcode');
}



/*-----------------------------------------------------------------------------------*/
/*	Definition List
/*-----------------------------------------------------------------------------------*/

if (!function_exists('dl_shortcode')) {
	function dl_shortcode($atts, $content = null) {

		$output = '<dl>';
		$output .= do_shortcode($content);
		$output .= '</dl>';

		return $output;
	}
	add_shortcode('dl', 'dl_shortcode');
}


if (!function_exists('def_item_shortcode')) {
	function def_item_shortcode($atts, $content = null) {

		extract(shortcode_atts(
			array(
				'title' => 'Title goes here'
		 ), $atts));

		$output = '<dt>';
			$output .= $title;
		$output .= '</dt>';

		$output .= '<dd>';
				$output .= do_shortcode($content);
		$output .= '</dd>';

		return $output;

	}
	add_shortcode('def_item', 'def_item_shortcode');
}



/*-----------------------------------------------------------------------------------*/
/*	Carousel
/*-----------------------------------------------------------------------------------*/

if (!function_exists('babysitter_carousel_shortcode')) {
	function babysitter_carousel_shortcode($atts, $content = null) {

		//Create unique ID for this carousel
		$unique_id = rand();

		$output = '<div class="list-carousel carousel__clients">';
			$output .= '<ul id="carousel-'. $unique_id .'">';
				$output .= do_shortcode($content);
			$output .= '</ul>';
			$output .= '<div class="carousel-nav">';
				$output .= '<a id="'.$unique_id.'-prev" class="prev" href="#"><i class="icon-chevron-left"></i></a>';
				$output .= '<a id="'.$unique_id.'-next" class="next" href="#"><i class="icon-chevron-right"></i></a>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<script>
		jQuery(window).load(function() {
			var clientsCarousel = jQuery("#carousel-'.$unique_id.'");
			clientsCarousel.carouFredSel({
				responsive : true,
				width: "100%",
				items : {
					width : 200,
					height: "variable",
					visible : {
						min : 1,
						max : 5
					},
					minimum: 1
				},
				scroll: {
					items: 1,
					fx: "scroll",
					easing: "swing",
					duration: 500,
					queue: true
				},
				auto: false,
				next: "#'.$unique_id.'-next",
				prev: "#'.$unique_id.'-prev",
				swipe:{
					onTouch: false
				}
			});
		});';
		$output .= '</script>';

		return $output;
	}
	add_shortcode('carousel', 'babysitter_carousel_shortcode');
}


if (!function_exists('babysitter_carousel_item_shortcode')) {
	function babysitter_carousel_item_shortcode($atts, $content = null) {

		$output = '<li>';
			$output .= do_shortcode($content);
		$output .= '</li>';

		return $output;
	}
	add_shortcode('item', 'babysitter_carousel_item_shortcode');
}




/*-----------------------------------------------------------------------------------*/
/*	Slider
/*-----------------------------------------------------------------------------------*/

if (!function_exists('babysitter_slider_shortcode')) {
	function babysitter_slider_shortcode($atts, $content = null) {

		extract(shortcode_atts(
			array(
				'animation' => 'fade',
				'nav' => 'true',
				'bullets' => 'true',
			   'speed' => '7000'
	   ), $atts));

		//Create unique ID for this slider
		$unique_id = rand();

		$output = '<div id="flexslider-'. $unique_id .'" class="flexslider loading flexslider__bordered flexslider__content">';
			$output .= '<ul class="slides">';
				$output .= do_shortcode($content);
			$output .= '</ul>';
		$output .= '</div>';

		$output .= '<script>
		jQuery(window).load(function() {
			jQuery("#flexslider-'.$unique_id.'").flexslider({
				animation: "'.$animation.'",
			  	animationLoop: true,
				directionNav: '.$nav.',
				smoothHeight: true,
				prevText: "<i class=\'icon-chevron-left\'></i>",
	 			nextText: "<i class=\'icon-chevron-right\'></i>",
				controlNav: '.$bullets.',
				startAt: 0,
				slideshow: true,
				slideshowSpeed: '.$speed.',

				start: function(slider){
					jQuery("#flexslider-'.$unique_id.'").removeClass("loading");
				}
			});
		});';
		$output .= '</script>';

		return $output;
	}
	add_shortcode('slider', 'babysitter_slider_shortcode');
}


if (!function_exists('babysitter_slide_shortcode')) {
	function babysitter_slide_shortcode($atts, $content = null) {

		$output = '<li>';
			$output .= do_shortcode($content);
		$output .= '</li>';

		return $output;
	}
	add_shortcode('slide', 'babysitter_slide_shortcode');
}





/*-----------------------------------------------------------------------------------*/
/*	Other old shortcodes (leaved here for compatibility with old version of the theme)
/*-----------------------------------------------------------------------------------*/

if (!function_exists('frame_shortcode')) {
	// Frame
	function frame_shortcode($atts, $content = null) {
	    
		$output =  '<div class="box clearfix">';
			$output .= do_shortcode($content);
		$output .= '</div>';

	   return $output;

	}
	add_shortcode('frame', 'frame_shortcode');
}


if (!function_exists('shortcode_tags')) {
	//Tag Cloud
	function shortcode_tags($atts, $content = null) {

		extract(shortcode_atts(array(
			'count' => '5'
		), $atts));

		$output = '<div class="tagcloud clearfix">';

		$tags = wp_tag_cloud('smallest=8&largest=8&format=array&number='.$count);
		foreach($tags as $tag){
				$output .= $tag.' ';
		}
		$output .= '</div>';
		return $output;

	}

	add_shortcode('tags', 'shortcode_tags');
}


if (!function_exists('tabs_shortcode')) {
	// Tabs
	function tabs_shortcode($atts, $content = null) {

		$output = '<div class="tabs">';
		$output .= '<div class="tab-menu">';
		$output .= '<ul>';

		//Create unique ID for this tab set
		$id = rand();

		//Build tab menu
		$numTabs = count($atts);

		for($i = 1; $i <= $numTabs; $i++){
		  $output .= '<li><a href="#tab-'.$id.'-'.$i.'">'.$atts['tab'.$i].'</a></li>';
		}

		$output .= '</ul>';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!-- .tab-menu (end) -->';
		$output .= '<div class="tab-wrapper">';

		//Build content of tabs
		$i = 1;
		$tabContent = do_shortcode($content);
		$find = array();
		$replace = array();
		foreach($atts as $key => $value){
			$find[] = '['.$key.']';
			$find[] = '[/'.$key.']';
			$replace[] = '<div id="tab-'.$id.'-'.$i.'" class="tab">';
			$replace[] = '</div><!-- .tab (end) -->';
			$i++;
		}

		$tabContent = str_replace($find, $replace, $tabContent);

		$output .= $tabContent;

		$output .= '</div><!-- .tab-wrapper (end) -->';
		$output .= '</div><!-- .tabs (end) -->';

		return $output;

	}

	add_shortcode('tabs', 'tabs_shortcode');
}


if (!function_exists('tabs_vertical_shortcode')) {
	// Tabs Vertical
	function tabs_vertical_shortcode($atts, $content = null) {

		$output = '<div class="tabs tabs__vertical">';
		$output .= '<div class="tab-menu">';
		$output .= '<ul>';

			//Create unique ID for this tab set
			$id = rand();

		//Build tab menu
		$numTabs = count($atts);

		for($i = 1; $i <= $numTabs; $i++){
		  $output .= '<li><a href="#tab-'.$id.'-'.$i.'">'.$atts['tab'.$i].'</a></li>';
		}

		$output .= '</ul>';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!-- .tab-menu (end) -->';
		$output .= '<div class="tab-wrapper">';

		//Build content of tabs
		$i = 1;
		$tabContent = do_shortcode($content);
		$find = array();
		$replace = array();
		foreach($atts as $key => $value){
		  $find[] = '['.$key.']';
		  $find[] = '[/'.$key.']';
		  $replace[] = '<div id="tab-'.$id.'-'.$i.'" class="tab">';
		  $replace[] = '</div><!-- .tab (end) -->';
				$i++;
		}

		$tabContent = str_replace($find, $replace, $tabContent);

		$output .= $tabContent;

		$output .= '</div><!-- .tab-wrapper (end) -->';
		$output .= '</div><!-- .tabs (end) -->';

		return $output;

	}

	add_shortcode('tabs_ver', 'tabs_vertical_shortcode');
}


/**
 * List styles
 */

if (!function_exists('unstyled_shortcode')) {
	// Unstyled List
	function unstyled_shortcode($atts, $content = null) {
		$output = '<div class="unstyled">';
		$output .= do_shortcode($content);
		$output .= '</div>';
		return $output;
	}
	add_shortcode('unstyled', 'unstyled_shortcode');
}


if (!function_exists('checklist_shortcode')) {
	// Check List
	function checklist_shortcode($atts, $content = null) {
		extract(shortcode_atts(
			array(
				'marker_color' => 'primary'
	   ), $atts));

		$output = '<div class="list list-style__check list-color__'.$marker_color.'">';
		$output .= do_shortcode($content);
		$output .= '</div>';
		return $output;
	}
	add_shortcode('checklist', 'checklist_shortcode');
}


if (!function_exists('arrow1_list_shortcode')) {
	// Arrow1 List
	function arrow1_list_shortcode($atts, $content = null) {
		extract(shortcode_atts(
			array(
				'marker_color' => 'primary'
	   ), $atts));

		$output = '<div class="list list-style__arrow1 list-color__'.$marker_color.'">';
		$output .= do_shortcode($content);
		$output .= '</div>';
		return $output;
	}
	add_shortcode('list_arrow1', 'arrow1_list_shortcode');
}


if (!function_exists('arrow2_list_shortcode')) {
	// Arrow2 List
	function arrow2_list_shortcode($atts, $content = null) {
		extract(shortcode_atts(
			array(
				'marker_color' => 'primary'
	   ), $atts));

		$output = '<div class="list list-style__arrow2 list-color__'.$marker_color.'">';
		$output .= do_shortcode($content);
		$output .= '</div>';
		return $output;
	}
	add_shortcode('list_arrow2', 'arrow2_list_shortcode');
}


if (!function_exists('arrow3_list_shortcode')) {
	// Arrow1 List
	function arrow3_list_shortcode($atts, $content = null) {
		extract(shortcode_atts(
			array(
				'marker_color' => 'primary'
	   ), $atts));

		$output = '<div class="list list-style__arrow3 list-color__'.$marker_color.'">';
		$output .= do_shortcode($content);
		$output .= '</div>';
		return $output;
	}
	add_shortcode('list_arrow3', 'arrow3_list_shortcode');
}


if (!function_exists('arrow4_list_shortcode')) {
	// Arrow4 List
	function arrow4_list_shortcode($atts, $content = null) {
		extract(shortcode_atts(
			array(
				'marker_color' => 'primary'
	   ), $atts));

		$output = '<div class="list list-style__arrow4 list-color__'.$marker_color.'">';
		$output .= do_shortcode($content);
		$output .= '</div>';
		return $output;
	}
	add_shortcode('list_arrow4', 'arrow4_list_shortcode');
}


if (!function_exists('star_list_shortcode')) {
	// Star List
	function star_list_shortcode($atts, $content = null) {
		extract(shortcode_atts(
			array(
				'marker_color' => 'primary'
	   ), $atts));

		$output = '<div class="list list-style__star list-color__'.$marker_color.'">';
		$output .= do_shortcode($content);
		$output .= '</div>';
		return $output;
	}
	add_shortcode('list_star', 'star_list_shortcode');
}

?>