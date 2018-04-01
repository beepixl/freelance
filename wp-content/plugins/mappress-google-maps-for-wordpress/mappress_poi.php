<?php
class Mappress_Poi extends Mappress_Obj {
	var $address,
		$body = '',
		$correctedAddress,
		$iconid,
		$point = array('lat' => 0, 'lng' => 0),
		$poly,
		$kml,
		$title = '',
		$type,
		$viewport;              // array('sw' => array('lat' => 0, 'lng' => 0), 'ne' => array('lat' => 0, 'lng' => 0))

	// Not saved
	var $postid,
		$url;


	function __sleep() {
		return array('address', 'body', 'correctedAddress', 'iconid', 'point', 'poly', 'kml', 'title', 'type', 'viewport');
	}

	function __construct($atts = '') {
		parent::__construct($atts);
	}

	// Work-around for PHP issues with circular references (serialize, print_r, json_encode, etc.)
	function map($map = null) {
		static $_map;
		if ($map)
			$_map = $map;
		else
			return $_map;
	}

	/**
	* Geocode an address using http
	*
	* @param mixed $auto true = automatically update the poi, false = return raw geocoding results
	* @return true if auto=true and success | WP_Error on failure
	*/
	function geocode() {
		if (!Mappress::$pro)
			return new WP_Error('geocode', 'MapPress Pro required for geocoding');

		// If point has a lat/lng then no geocoding
		if (!empty($this->point['lat']) && !empty($this->point['lng'])) {
			$this->correctedAddress = ($this->address) ? $this->address : null;
			$this->viewport = null;
		} else {
			$location = Mappress_Geocoder::geocode($this->address);

			if (is_wp_error($location))
				return $location;

			$this->point = array('lat' => $location->lat, 'lng' => $location->lng);
			$this->correctedAddress = $location->formatted_address;
			$this->viewport = $location->viewport;
		}

		// Guess a default title / body - use address if available or lat, lng if not
		if (empty($this->title) && empty($this->body)) {
			if ($this->correctedAddress) {
				$parsed = Mappress_Geocoder::parse_address($this->correctedAddress);
				$this->title = $parsed[0];
				$this->body = (isset($parsed[1])) ? $parsed[1] : "";
			} else {
				$this->title = $this->point['lat'] . ',' . $this->point['lng'];
			}
		}
	}

	function set_html() {
		global $post;
		$html = Mappress::get_template('map_poi', array('poi' => $this));
			$html = apply_filters('mappress_poi_html', $html, $this);
		$this->html = $html;
	}

	/**
	* Prepare poi for output
	*/
	function prepare() {
		$map = $this->map();

		// Set title
		if (Mappress::$options->mashupTitle == 'post' && $this->postid) {
			$post = get_post($this->postid);
			$this->title = $post->post_title;
		}

		// Set body
		if ($this->postid) {
			if (Mappress::$options->mashupBody == 'post')
				$this->body = $this->get_post_excerpt();
		}

		// Set URL
		if ($this->postid)
			$this->url = get_permalink($this->postid);
	}

	/**
	* Get the poi title
	*
	*/
	function get_title() {
		return $this->title;
	}

	/**
	* Based on style settings, gets either the poi title or a link to the underlying post with poi title as text
	*
	*/
	function get_title_link() {
		$map = $this->map();
		// 2.45
		//$link = ($this->postid && $map->options->mashupLink) ? sprintf("<a href='%s'>%s</a>", $this->url, esc_html($this->title)) : $this->title;
		$link = ($this->postid) ? sprintf("<a href='%s'>%s</a>", $this->url, esc_html($this->title)) : $this->title;
		return $link;
	}

	/**
	* Get the poi body
	*
	*/
	function get_body() {
		return $this->body;
	}

	/**
	* Get a post excerpt for a poi
	* Uses the WP get_the_excerpt(), which requires postdata to be set up.
	*
	* @param mixed $postid
	*/
	function get_post_excerpt() {
		global $post;

		$post = get_post($this->postid);
		if (empty($this->postid) || empty($post))
			return "";

		$old_post = ($post) ? clone $post : null;
		setup_postdata($post);
		$html = get_the_excerpt();

		// wp_reset_postdata() may not work with other plugins so use the cloned copy instead
		if ($old_post) {
			$post = $old_post;
			setup_postdata($post);
		}

		return $html;
	}

	/**
	* Get the formatted address as HTML
	* A <br> tag is inserted between the first line and subsequent lines
	*
	*/
	function get_address() {
		$parsed = Mappress_Geocoder::parse_address($this->correctedAddress);
		if (!$parsed)
			return "";

		return isset($parsed[1]) ? $parsed[0] . "<br/>" . $parsed[1] : $parsed[0];
	}

	/**
	* Get links for poi in infowindow or poi list
	*
	* @param mixed $context - blank or 'poi' | 'poi_list'
	*/
	function get_links($context = '') {
		//2.45 - no links in poi list
		$map = $this->map();
		if ($context == 'poi_list' || $map->options->directions == 'none')
			return '';
		return $this->get_directions_link(array('to' => $this, 'text' => __('Directions', 'mappress-google-maps-for-wordpress')));
	}

	function get_icon() {
		$map = $this->map();
		return sprintf("<img class='mapp-icon' src='%s' />", Mappress_Icons::get($this->iconid));
	}

	/**
	* Get a directions link
	*
	* @param bool $from - 'from' poi object or a string address
	* @param bool $to - 'to' poi object or a string address
	* @param mixed $text
	*/
	function get_directions_link($args = '') {
		$map = $this->map();

		$args = (object) wp_parse_args($args, array(
			'from' => $map->options->from,
			'to' => $map->options->to,
			'text' => __('Directions', 'mappress-google-maps-for-wordpress')
		));

		// Convert objects to indexes, quote strings
		if (is_object($args->from)) {
			$i = array_search($args->from, $map->pois);
			$from = "{$map->name}.getPoi($i)";
		} else {
			$from = "\"{$args->from}\"";
		}

		if (is_object($args->to)) {
			$i = array_search($args->to, $map->pois);
			$to = "{$map->name}.getPoi($i)";
		} else {
			$to = "\"{$args->to}\"";
		}

		// 2.45-
		$link = "<a href='#' onclick = '{$map->name}.openDirections(%s, %s, true); return false;'>{$args->text}</a>";
		return sprintf($link, $from, $to);
	}

	/**
	* Back-compat: get a link to open a poi, currently returns just the title
	*/
	function get_open_link ($args = '') {
		return $this->title;
	}

	/**
	* Get poi thumbnail
	*
	* @param mixed $map
	* @param mixed $args - arguments to pass to WP get_the_post_thumbnail() function
	*/
	function get_thumbnail( $args = '' ) {
		$map = $this->map();

		if (!$this->postid || !Mappress::$options->thumbs)
			return '';

		$args = ($args) ? $args : array();
		$size = (Mappress::$options->thumbSize) ? Mappress::$options->thumbSize : null;

		if (Mappress::$options->thumbWidth && Mappress::$options->thumbHeight)
			$args['style'] = sprintf("width: %spx; height : %spx;", Mappress::$options->thumbWidth, Mappress::$options->thumbHeight);

		$html = get_the_post_thumbnail($this->postid, $size, $args);
		$html = "<a href='" . $this->url . "'>$html</a>";
		return $html;
	}
}
?>