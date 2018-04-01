<?php

/*-----------------------------------------------------------------------------------*/
/*	Button Config
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['button'] = array(
	'no_preview' => true,
	'params' => array(
		'link' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Button URL', 'babysitter'),
			'desc' => __('Add the button\'s url eg http://example.com', 'babysitter')
		),
		'style' => array(
			'type' => 'select',
			'label' => __('Button Style', 'babysitter'),
			'desc' => __('Select the button\'s style, ie the button\'s colour', 'babysitter'),
			'options' => array(
				'primary' => 'Primary Color',
				'secondary' => 'Secondary Color',
				'tertiary' => 'Tertiary Color',
				'quaternary' => 'Quaternary Color',
				'quinary' => 'Quinary Color',
				'senary' => 'Senary Color'
			)
		),
		'size' => array(
			'type' => 'select',
			'label' => __('Button Size', 'babysitter'),
			'desc' => __('Select the button\'s size', 'babysitter'),
			'options' => array(
				'small' => 'Small',
				'normal' => 'Normal',
				'large' => 'Large'
			)
		),
		'target' => array(
			'type' => 'select',
			'label' => __('Button Target', 'babysitter'),
			'desc' => __('_self = open in same window. _blank = open in new window', 'babysitter'),
			'options' => array(
				'_self' => '_self',
				'_blank' => '_blank'
			)
		),
		'text' => array(
			'std' => 'Button Text',
			'type' => 'text',
			'label' => __('Button\'s Text', 'babysitter'),
			'desc' => __('Add the button\'s text', 'babysitter'),
		),
		'icon' => array(
			'type' => 'select',
			'label' => __('Button Icon', 'babysitter'),
			'desc' => __('Select the button\'s icon', 'babysitter'),
			'options' => array(
				'none' => 'none',
				'icon-leaf' => 'icon-leaf',
				'icon-glass' => 'icon-glass',
				'icon-envelope-alt' => 'icon-envelope-alt',
				'icon-star-empty' => 'icon-star-empty',
				'icon-th-large' => 'icon-th-large',
				'icon-ok' => 'icon-ok',
				'icon-zoom-out' => 'icon-zoom-out',
				'icon-cog' => 'icon-cog',
				'icon-file-alt' => 'icon-file-alt',
				'icon-download-alt' => 'icon-download-alt', 
				'icon-inbox' => 'icon-inbox', 
				'icon-refresh' => 'icon-refresh',
				'icon-flag' => 'icon-flag',
				'icon-volume-down' => 'icon-volume-down',
				'icon-barcode' => 'icon-barcode',
				'icon-book' => 'icon-book',
				'icon-camera' => 'icon-camera',
				'icon-italic' => 'icon-italic',
				'icon-align-left' => 'icon-align-left',
				'icon-align-justify' => 'icon-align-justify',
				'icon-indent-right' => 'icon-indent-right',
				'icon-pencil' => 'icon-pencil',
				'icon-tint' => 'icon-tint',
				'icon-check' => 'icon-check',
				'icon-fast-backward' => 'icon-fast-backward',
				'icon-pause' => 'icon-pause',
				'icon-fast-forward' => 'icon-fast-forward',
				'icon-chevron-left' => 'icon-chevron-left',
				'icon-minus-sign' => 'icon-minus-sign',
				'icon-question-sign' => 'icon-question-sign',
				'icon-remove-circle' => 'icon-remove-circle',
				'icon-arrow-left' => 'icon-arrow-left',
				'icon-arrow-down' => 'icon-arrow-down',
				'icon-resize-small' => 'icon-resize-small',
				'icon-asterisk' => 'icon-asterisk',
				'icon-leaf' => 'icon-leaf',
				'icon-eye-close' => 'icon-eye-close',
				'icon-calendar' => 'icon-calendar',
				'icon-magnet' => 'icon-magnet',
				'icon-retweet' => 'icon-retweet',
				'icon-folder-open' => 'icon-folder-open',
				'icon-bar-chart' => 'icon-bar-chart',
				'icon-camera-retro' => 'icon-camera-retro',
				'icon-comments' => 'icon-comments',
				'icon-star-half' => 'icon-star-half',
				'icon-linkedin-sign' => 'icon-linkedin-sign',
				'icon-signin' => 'icon-signin',
				'icon-upload-alt' => 'icon-upload-alt',
				'icon-check-empty' => 'icon-check-empty',
				'icon-twitter' => 'icon-twitter',
				'icon-unlock' => 'icon-unlock',
				'icon-hdd' => 'icon-hdd',
				'icon-certificate' => 'icon-certificate',
				'icon-hand-up' => 'icon-hand-up',
				'icon-circle-arrow-right' => 'icon-circle-arrow-right',
				'icon-globe' => 'icon-globe',
				'icon-filter' => 'icon-filter',
				'icon-group' => 'icon-group',
				'icon-beaker' => 'icon-beaker',
				'icon-paper-clip' => 'icon-paper-clip',
				'icon-reorder' => 'icon-reorder',
				'icon-strikethrough' => 'icon-strikethrough',
				'icon-magic' => 'icon-magic',
				'icon-pinterest-sign' => 'icon-pinterest-sign',
				'icon-money' => 'icon-money',
				'icon-caret-left' => 'icon-caret-left',
				'icon-sort' => 'icon-sort',
				'icon-envelope' => 'icon-envelope',
				'icon-legal' => 'icon-legal',
				'icon-comments-alt' => 'icon-comments-alt',
				'icon-umbrella' => 'icon-umbrella',
				'icon-exchange' => 'icon-exchange',
				'icon-user-md' => 'icon-user-md',
				'icon-bell-alt' => 'icon-bell-alt',
				'icon-file-text-alt' => 'icon-file-text-alt',
				'icon-ambulance' => 'icon-ambulance',
				'icon-beer' => 'icon-beer',
				'icon-double-angle-left' => 'icon-double-angle-left', 
				'icon-double-angle-down' => 'icon-double-angle-down',
				'icon-angle-up' => 'icon-angle-up',
				'icon-laptop' => 'icon-laptop',
				'icon-circle-blank' => 'icon-circle-blank',
				'icon-spinner' => 'icon-spinner',
				'icon-github-alt' => 'icon-github-alt',
				'icon-expand-alt' => 'icon-expand-alt',
				'icon-frown' => 'icon-frown',
				'icon-keyboard' => 'icon-keyboard',
				'icon-terminal' => 'icon-terminal',
				'icon-mail-reply-all' => 'icon-mail-reply-all',
				'icon-crop' => 'icon-crop',
				'icon-question' => 'icon-question',
				'icon-superscript' => 'icon-superscript',
				'icon-puzzle-piece' => 'icon-puzzle-piece',
				'icon-shield' => 'icon-shield',
				'icon-rocket' => 'icon-rocket',
				'icon-chevron-sign-right' => 'icon-chevron-sign-right',
				'icon-html5' => 'icon-html5',
				'icon-unlock-alt' => 'icon-unlock-alt',
				'icon-ellipsis-vertical' => 'icon-ellipsis-vertical',
				'icon-ticket' => 'icon-ticket',
				'icon-level-up' => 'icon-level-up',
				'icon-edit-sign' => 'icon-edit-sign',
				'icon-compass' => 'icon-compass',
				'icon-expand' => 'icon-expand',
				'icon-usd' => 'icon-usd',
				'icon-cny' => 'icon-cny',
				'icon-file' => 'icon-file',
				'icon-sort-by-alphabet-alt' => 'icon-sort-by-alphabet-alt', 
				'icon-sort-by-order' => 'icon-sort-by-order',
				'icon-thumbs-down' => 'icon-thumbs-down',
				'icon-xing' => 'icon-xing',
				'icon-dropbox' => 'icon-dropbox',
				'icon-flickr' => 'icon-flickr',
				'icon-bitbucket-sign' => 'icon-bitbucket-sign',
				'icon-long-arrow-down' => 'icon-long-arrow-down',
				'icon-long-arrow-right' => 'icon-long-arrow-right',
				'icon-android' => 'icon-android',
				'icon-skype' => 'icon-skype',
				'icon-female' => 'icon-female',
				'icon-sun' => 'icon-sun',
				'icon-bug' => 'icon-bug',
				'icon-renren' => 'icon-renren',
				'icon-music' => 'icon-music',
				'icon-heart' => 'icon-heart',
				'icon-user' => 'icon-user',
				'icon-th' => 'icon-th',
				'icon-remove' => 'icon-remove',
				'icon-off' => 'icon-off',
				'icon-trash' => 'icon-trash',
				'icon-time' => 'icon-time',
				'icon-download' => 'icon-download',
				'icon-play-circle' => 'icon-play-circle',
				'icon-list-alt' => 'icon-list-alt',
				'icon-headphones' => 'icon-headphones',
				'icon-volume-up' => 'icon-volume-up',
				'icon-tag' => 'icon-tag',
				'icon-bookmark' => 'icon-bookmark',
				'icon-font' => 'icon-font',
				'icon-text-height' => 'icon-text-height',
				'icon-align-center' => 'icon-align-center',
				'icon-list' => 'icon-list',
				'icon-facetime-video' => 'icon-facetime-video',
				'icon-map-marker' => 'icon-map-marker',
				'icon-edit' => 'icon-edit',
				'icon-move' => 'icon-move',
				'icon-backward' => 'icon-backward',
				'icon-stop' => 'icon-stop',
				'icon-step-forward' => 'icon-step-forward',
				'icon-chevron-right' => 'icon-chevron-right',
				'icon-remove-sign' => 'icon-remove-sign',
				'icon-info-sign' => 'icon-info-sign',
				'icon-ok-circle' => 'icon-ok-circle',
				'icon-arrow-right' => 'icon-arrow-right',
				'icon-share-alt' => 'icon-share-alt',
				'icon-plus' => 'icon-plus',
				'icon-exclamation-sign' => 'icon-exclamation-sign',
				'icon-fire' => 'icon-fire',
				'icon-warning-sign' => 'icon-warning-sign',
				'icon-random' => 'icon-random',
				'icon-chevron-up' => 'icon-chevron-up',
				'icon-shopping-cart' => 'icon-shopping-cart',
				'icon-resize-vertical' => 'icon-resize-vertical',
				'icon-twitter-sign' => 'icon-twitter-sign',
				'icon-key' => 'icon-key',
				'icon-thumbs-up-alt' => 'icon-thumbs-up-alt',
				'icon-heart-empty' => 'icon-heart-empty',
				'icon-pushpin' => 'icon-pushpin',
				'icon-trophy' => 'icon-trophy',
				'icon-lemon' => 'icon-lemon',
				'icon-bookmark-empty' => 'icon-bookmark-empty',
				'icon-facebook' => 'icon-facebook',
				'icon-credit-card' => 'icon-credit-card',
				'icon-bullhorn' => 'icon-bullhorn',
				'icon-hand-right' => 'icon-hand-right',
				'icon-hand-down' => 'icon-hand-down',
				'icon-circle-arrow-up' => 'icon-circle-arrow-up',
				'icon-wrench' => 'icon-wrench',
				'icon-briefcase' => 'icon-briefcase',
				'icon-link' => 'icon-link',
				'icon-cut' => 'icon-cut',
				'icon-save' => 'icon-save',
				'icon-list-ul' => 'icon-list-ul',
				'icon-underline' => 'icon-underline',
				'icon-truck' => 'icon-truck',
				'icon-google-plus-sign' => 'icon-google-plus-sign',
				'icon-caret-down' => 'icon-caret-down',
				'icon-caret-right' => 'icon-caret-right',
				'icon-sort-down' => 'icon-sort-down',
				'icon-linkedin' => 'icon-linkedin',
				'icon-dashboard' => 'icon-dashboard',
				'icon-bolt' => 'icon-bolt',
				'icon-paste' => 'icon-paste',
				'icon-cloud-download' => 'icon-cloud-download',
				'icon-stethoscope' => 'icon-stethoscope',
				'icon-coffee' => 'icon-coffee',
				'icon-building' => 'icon-building',
				'icon-medkit' => 'icon-medkit',
				'icon-h-sign' => 'icon-h-sign',
				'icon-double-angle-right' => 'icon-double-angle-right',
				'icon-angle-left' => 'icon-angle-left',
				'icon-angle-down' => 'icon-angle-down',
				'icon-tablet' => 'icon-tablet',
				'icon-quote-left' => 'icon-quote-left',
				'icon-circle' => 'icon-circle',
				'icon-folder-close-alt' => 'icon-folder-close-alt',
				'icon-collapse-alt' => 'icon-collapse-alt',
				'icon-meh' => 'icon-meh',
				'icon-flag-alt' => 'icon-flag-alt',
				'icon-code' => 'icon-code',
				'icon-star-half-empty' => 'icon-star-half-empty',
				'icon-code-fork' => 'icon-code-fork',
				'icon-info' => 'icon-info',
				'icon-subscript' => 'icon-subscript',
				'icon-microphone' => 'icon-microphone',
				'icon-calendar-empty' => 'icon-calendar-empty',
				'icon-maxcdn' => 'icon-maxcdn',
				'icon-chevron-sign-up' => 'icon-chevron-sign-up',
				'icon-css3' => 'icon-css3',
				'icon-bullseye' => 'icon-bullseye',
				'icon-rss-sign' => 'icon-rss-sign',
				'icon-minus-sign-alt' => 'icon-minus-sign-alt',
				'icon-level-down' => 'icon-level-down',
				'icon-external-link-sign' => 'icon-external-link-sign',
				'icon-collapse' => 'icon-collapse',
				'icon-eur' => 'icon-eur',
				'icon-inr' => 'icon-inr',
				'icon-krw' => 'icon-krw',
				'icon-file-text' => 'icon-file-text',
				'icon-sort-by-attributes' => 'icon-sort-by-attributes',
				'icon-sort-by-order-alt' => 'icon-sort-by-order-alt',
				'icon-youtube-sign' => 'icon-youtube-sign',
				'icon-xing-sign' => 'icon-xing-sign',
				'icon-stackexchange' => 'icon-stackexchange',
				'icon-adn' => 'icon-adn',
				'icon-tumblr' => 'icon-tumblr',
				'icon-long-arrow-up' => 'icon-long-arrow-up',
				'icon-apple' => 'icon-apple',
				'icon-linux' => 'icon-linux',
				'icon-foursquare' => 'icon-foursquare',
				'icon-male' => 'icon-male',
				'icon-moon' => 'icon-moon',
				'icon-vk' => 'icon-vk',
				'icon-search' => 'icon-search',
				'icon-star' => 'icon-star',
				'icon-film' => 'icon-film',
				'icon-th-list' => 'icon-th-list',
				'icon-zoom-in' => 'icon-zoom-in',
				'icon-signal' => 'icon-signal',
				'icon-home' => 'icon-home',
				'icon-road' => 'icon-road',
				'icon-upload' => 'icon-upload',
				'icon-repeat' => 'icon-repeat',
				'icon-lock' => 'icon-lock',
				'icon-volume-off' => 'icon-volume-off',
				'icon-qrcode' => 'icon-qrcode',
				'icon-tags' => 'icon-tags',
				'icon-print' => 'icon-print',
				'icon-bold' => 'icon-print',
				'icon-text-width' => 'icon-text-width',
				'icon-align-right' => 'icon-align-right',
				'icon-indent-left' => 'icon-indent-left',
				'icon-picture' => 'icon-picture',
				'icon-adjust' => 'icon-adjust',
				'icon-share' => 'icon-share',
				'icon-step-backward' => 'icon-step-backward',
				'icon-play' => 'icon-play',
				'icon-forward' => 'icon-forward',
				'icon-eject' => 'icon-eject',
				'icon-plus-sign' => 'icon-plus-sign',
				'icon-ok-sign' => 'icon-ok-sign',
				'icon-screenshot' => 'icon-screenshot',
				'icon-ban-circle' => 'icon-ban-circle',
				'icon-arrow-up' => 'icon-arrow-up',
				'icon-resize-full' => 'icon-resize-full',
				'icon-minus' => 'icon-minus',
				'icon-gift' => 'icon-gift',
				'icon-eye-open' => 'icon-eye-open',
				'icon-plane' => 'icon-plane',
				'icon-comment' => 'icon-comment',
				'icon-chevron-down' => 'icon-chevron-down',
				'icon-folder-close' => 'icon-folder-close',
				'icon-resize-horizontal' => 'icon-resize-horizontal',
				'icon-facebook-sign' => 'icon-facebook-sign',
				'icon-cogs' => 'icon-cogs',
				'icon-thumbs-down-alt' => 'icon-thumbs-down-alt',
				'icon-signout' => 'icon-signout',
				'icon-external-link' => 'icon-external-link',
				'icon-github-sign' => 'icon-github-sign',
				'icon-phone' => 'icon-phone',
				'icon-phone-sign' => 'icon-phone-sign',
				'icon-github' => 'icon-github',
				'icon-rss' => 'icon-rss',
				'icon-bell' => 'icon-bell',
				'icon-hand-left' => 'icon-hand-left',
				'icon-circle-arrow-left' => 'icon-circle-arrow-left',
				'icon-circle-arrow-down' => 'icon-circle-arrow-down',
				'icon-tasks' => 'icon-tasks',
				'icon-fullscreen' => 'icon-fullscreen',
				'icon-cloud' => 'icon-cloud',
				'icon-copy' => 'icon-copy',
				'icon-sign-blank' => 'icon-sign-blank',
				'icon-list-ol' => 'icon-list-ol',
				'icon-table' => 'icon-table',
				'icon-pinterest' => 'icon-pinterest',
				'icon-google-plus' => 'icon-google-plus',
				'icon-caret-up' => 'icon-caret-up',
				'icon-columns' => 'icon-columns',
				'icon-sort-up' => 'icon-sort-up',
				'icon-undo' => 'icon-undo',
				'icon-comment-alt' => 'icon-comment-alt',
				'icon-sitemap' => 'icon-sitemap',
				'icon-lightbulb' => 'icon-lightbulb',
				'icon-cloud-upload' => 'icon-cloud-upload',
				'icon-suitcase' => 'icon-suitcase',
				'icon-food' => 'icon-food',
				'icon-hospital' => 'icon-hospital',
				'icon-fighter-jet' => 'icon-fighter-jet',
				'icon-plus-sign-alt' => 'icon-plus-sign-alt',
				'icon-double-angle-up' => 'icon-double-angle-up',
				'icon-angle-right' => 'icon-angle-right',
				'icon-desktop' => 'icon-desktop',
				'icon-mobile-phone' => 'icon-mobile-phone',
				'icon-quote-right' => 'icon-quote-right',
				'icon-reply' => 'icon-reply',
				'icon-folder-open-alt' => 'icon-folder-open-alt',
				'icon-smile' => 'icon-smile',
				'icon-gamepad' => 'icon-gamepad',
				'icon-flag-checkered' => 'icon-flag-checkered',
				'icon-reply-all' => 'icon-reply-all',
				'icon-location-arrow' => 'icon-location-arrow',
				'icon-unlink' => 'icon-unlink',
				'icon-exclamation' => 'icon-exclamation',
				'icon-eraser' => 'icon-eraser',
				'icon-microphone-off' => 'icon-microphone-off',
				'icon-fire-extinguisher' => 'icon-fire-extinguisher',
				'icon-chevron-sign-left' => 'icon-chevron-sign-left',
				'icon-chevron-sign-down' => 'icon-chevron-sign-down',
				'icon-anchor' => 'icon-anchor',
				'icon-ellipsis-horizontal' => 'icon-ellipsis-horizontal',
				'icon-play-sign' => 'icon-play-sign',
				'icon-check-minus' => 'icon-check-minus',
				'icon-check-sign' => 'icon-check-sign',
				'icon-share-sign' => 'icon-share-sign',
				'icon-collapse-top' => 'icon-collapse-top',
				'icon-gbp' => 'icon-gbp',
				'icon-jpy' => 'icon-jpy',
				'icon-btc' => 'icon-btc',
				'icon-sort-by-alphabet' => 'icon-sort-by-alphabet',
				'icon-sort-by-attributes-alt' => 'icon-sort-by-attributes-alt',
				'icon-thumbs-up' => 'icon-thumbs-up',
				'icon-youtube' => 'icon-youtube',
				'icon-youtube-play' => 'icon-youtube-play',
				'icon-instagram' => 'icon-instagram',
				'icon-bitbucket' => 'icon-bitbucket',
				'icon-tumblr-sign' => 'icon-tumblr-sign',
				'icon-long-arrow-left' => 'icon-long-arrow-left',
				'icon-windows' => 'icon-windows',
				'icon-dribble' => 'icon-dribble',
				'icon-trello' => 'icon-trello',
				'icon-gittip' => 'icon-gittip',
				'icon-archive' => 'icon-archive',
				'icon-weibo' => 'icon-weibo'
			)
		),
	),
	'shortcode' => '[button text="{{text}}" link="{{link}}" style="{{style}}" size="{{size}}" target="{{target}}" icon="{{icon}}"][/button]',
	'popup_title' => __('Insert Button Shortcode', 'babysitter')
);

/*-----------------------------------------------------------------------------------*/
/*	Alert Config
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['alert'] = array(
	'no_preview' => true,
	'params' => array(
		'style' => array(
			'type' => 'select',
			'label' => __('Alert Style', 'babysitter'),
			'desc' => __('Select the alert\'s style, ie the alert colour', 'babysitter'),
			'options' => array(
				'error' => 'Error',
				'warning' => 'Warning',
				'info' => 'Info',
				'success' => 'Success'
			)
		),
		'content' => array(
			'std' => 'Your Alert!',
			'type' => 'textarea',
			'label' => __('Alert Text', 'babysitter'),
			'desc' => __('Add the alert\'s text', 'babysitter'),
		)
		
	),
	'shortcode' => '[alert style="{{style}}"] {{content}} [/alert]',
	'popup_title' => __('Insert Alert Shortcode', 'babysitter')
);

/*-----------------------------------------------------------------------------------*/
/*	Columns Config
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['columns'] = array(
	'params' => array(),
	'shortcode' => ' {{child_shortcode}} [clear]', // as there is no wrapper shortcode
	'popup_title' => __('Insert Column Shortcode', 'babysitter'),
	'no_preview' => true,
	
	// child shortcode is clonable & sortable
	'child_shortcode' => array(
		'params' => array(
			'grid' => array(
				'type' => 'select',
				'label' => __('Column Type', 'babysitter'),
				'desc' => __('Select the type, ie width of the column.', 'babysitter'),
				'options' => array(
					'grid_1' => 'Grid 1',
					'grid_2' => 'Grid 2',
					'grid_3' => 'Grid 3',
					'grid_4' => 'Grid 4',
					'grid_5' => 'Grid 5',
					'grid_6' => 'Grid 6',
					'grid_7' => 'Grid 7',
					'grid_8' => 'Grid 8',
					'grid_9' => 'Grid 9',
					'grid_10' => 'Grid 10',
					'grid_11' => 'Grid 11',
					'grid_12' => 'Grid 12'
				)
			),
			'content' => array(
				'std' => '',
				'type' => 'textarea',
				'label' => __('Column Content', 'babysitter'),
				'desc' => __('Add the grid content.', 'babysitter'),
			)
		),
		'shortcode' => '[{{grid}}] {{content}} [/{{grid}}] ',
		'clone_button' => __('Add Column', 'babysitter')
	)
);


/*-----------------------------------------------------------------------------------*/
/*	Horizontal Rules
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['hr'] = array(
	'no_preview' => true,
	'params' => array(
		'style' => array(
			'type' => 'select',
			'label' => __('Rule Style', 'babysitter'),
			'desc' => __('Select the rule\'s style', 'babysitter'),
			'options' => array(
				'default' => 'Default',
				'bold' => 'Bold'
			)
		)
		
	),
	'shortcode' => '[hr style="{{style}}"]',
	'popup_title' => __('Insert Rule Shortcode', 'babysitter')
);


/*-----------------------------------------------------------------------------------*/
/*	Box Config
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['box'] = array(
	'no_preview' => true,
	'params' => array(
		'title' => array(
			'std' => 'Your Titlte',
			'type' => 'text',
			'label' => __('Title', 'babysitter'),
			'desc' => __('Add the boxe\'s title', 'babysitter'),
		),
		'maintext' => array(
			'std' => 'Your Content',
			'type' => 'textarea',
			'label' => __('Content', 'babysitter'),
			'desc' => __('Add the boxe\'s content', 'babysitter'),
		),
		'url' => array(
			'std' => '#',
			'type' => 'text',
			'label' => __('Button\'s URL', 'babysitter'),
			'desc' => __('Add the button\'s url eg http://example.com', 'babysitter')
		),
		'linktext' => array(
			'std' => 'Button Text',
			'type' => 'text',
			'label' => __('Button\'s Text', 'babysitter'),
			'desc' => __('Add the button\'s text', 'babysitter'),
		)
		
	),
	'shortcode' => '[box title="{{title}}" maintext="{{maintext}}" linktext="{{linktext}}" url="{{url}}"]',
	'popup_title' => __('Insert Box Shortcode', 'babysitter')
);




/*-----------------------------------------------------------------------------------*/
/*	Spacers
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['spacer'] = array(
	'no_preview' => true,
	'params' => array(
		'size' => array(
			'type' => 'select',
			'label' => __('Spacer Size', 'babysitter'),
			'desc' => __('Select the spacer\'s size', 'babysitter'),
			'options' => array(
				'default' => 'Default',
				'small' => 'small'
			)
		)
		
	),
	'shortcode' => '[spacer size="{{size}}"]',
	'popup_title' => __('Insert Spacer Shortcode', 'babysitter')
);



/*-----------------------------------------------------------------------------------*/
/*	Posts
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['posts'] = array(
	'no_preview' => true,
	'params' => array(
		'num' => array(
			'std' => '4',
			'type' => 'text',
			'label' => __('Number of Posts', 'babysitter'),
			'desc' => __('Change the number of displayed posts', 'babysitter'),
		),
		'order' => array(
			'type' => 'select',
			'label' => __('Order', 'babysitter'),
			'desc' => __('Select the post\'s order', 'babysitter'),
			'options' => array(
				'date' => 'date',
				'name' => 'name',
				'title' => 'title',
				'author' => 'author',
				'modified' => 'modified',
				'parent' => 'parent',
				'rand' => 'rand',
				'comment_count' => 'comment_count'
			)
		),
		'slug' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Category Slug', 'babysitter'),
			'desc' => __('Add category slug to filter your posts', 'babysitter'),
		),
		'type' => array(
			'type' => 'select',
			'label' => __('Post Type', 'babysitter'),
			'desc' => __('Select the post\'s type', 'babysitter'),
			'options' => array(
				'post' => 'post',
				'slides' => 'slides'
			)
		)
	),
	'shortcode' => '[posts num="{{num}}" order="{{order}}" cat_slug="{{slug}}" type="{{type}}"]',
	'popup_title' => __('Insert Posts Shortcode', 'babysitter')
);


/*-----------------------------------------------------------------------------------*/
/*	Team
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['team'] = array(
	'no_preview' => true,
	'params' => array(
		'num' => array(
			'std' => '4',
			'type' => 'text',
			'label' => __('Number of Posts', 'babysitter'),
			'desc' => __('Change the number of displayed posts', 'babysitter'),
		),
		'item_class' => array(
			'type' => 'select',
			'label' => __('Layout', 'babysitter'),
			'desc' => __('Select team\'s layout', 'babysitter'),
			'options' => array(
				'grid_3' => '4 columns',
				'grid_4' => '3 columns',
				'grid_6' => '2 columns'
			)
		),
		'linkto' => array(
			'type' => 'select',
			'label' => __('Link to Post?', 'babysitter'),
			'desc' => __('Do you want to link thumbnail to single post?', 'babysitter'),
			'options' => array(
				'yes' => 'Yes',
				'no' => 'No'
			)
		),
		'order' => array(
			'type' => 'select',
			'label' => __('Order', 'babysitter'),
			'desc' => __('Select the post\'s order', 'babysitter'),
			'options' => array(
				'date' => 'date',
				'name' => 'name',
				'title' => 'title',
				'author' => 'author',
				'modified' => 'modified',
				'parent' => 'parent',
				'rand' => 'rand',
				'comment_count' => 'comment_count'
			)
		),
		'offset' => array(
			'std' => '0',
			'type' => 'text',
			'label' => __('Offset', 'babysitter'),
			'desc' => __('Add from which post to start (from 0)', 'babysitter'),
		),
		'cat_slug' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Category Slug', 'babysitter'),
			'desc' => __('Add category slug to filter your posts', 'babysitter'),
		)
	),
	'shortcode' => '[team num="{{num}}" item_class="{{item_class}}" linkto="{{linkto}}" offset="{{offset}}" cat_slug="{{cat_slug}}"]',
	'popup_title' => __('Insert Team Posts Shortcode', 'babysitter')
);



/*-----------------------------------------------------------------------------------*/
/*	Testimonial
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['testimonial'] = array(
	'no_preview' => true,
	'params' => array(
		'name' => array(
			'std' => 'Your Name',
			'type' => 'text',
			'label' => __('Author\'s Name', 'babysitter'),
			'desc' => __('Add the author of testimonial', 'babysitter'),
		),
		'info' => array(
			'std' => 'Info',
			'type' => 'text',
			'label' => __('Author\'s Info', 'babysitter'),
			'desc' => __('Add short info about author, ie position, company etc.', 'babysitter'),
		),
		'img_url' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Author\'s Image', 'babysitter'),
			'desc' => __('Add url to author\'s image (leave it blank if you want to use icons). Recommended size is 36x36', 'babysitter'),
		),
		'gender' => array(
			'type' => 'select',
			'label' => __('Gender', 'babysitter'),
			'desc' => __('Choose gender of testimonial author', 'babysitter'),
			'options' => array(
				'male' => 'Male',
				'female' => 'Female'
			)
		),
		'content' => array(
			'std' => 'Your Content',
			'type' => 'textarea',
			'label' => __('Testimonial Text', 'babysitter'),
			'desc' => __('Add the testimonial\'s text', 'babysitter'),
		)
	),
	'shortcode' => '[testimonial name="{{name}}" info="{{info}}" img_url="{{img_url}}" gender="{{gender}}"] {{content}} [/testimonial]',
	'popup_title' => __('Insert Testimonial Shortcode', 'babysitter')
);



/*-----------------------------------------------------------------------------------*/
/*	Tabs Config
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['tabs_new'] = array(
	'params' => array(
		'type' => array(
			'type' => 'select',
			'label' => __('Orientation', 'babysitter'),
			'desc' => __('Select the tab\'s orientation', 'babysitter'),
			'options' => array(
				'hor' => 'Horizontal',
				'ver' => 'Vertical'
			)
		)
	),
	'no_preview' => true,
	'shortcode' => '[tabs_new type="{{type}}"] {{child_shortcode}} [/tabs_new]',
	'popup_title' => __('Insert Tab Shortcode', 'babysitter'),
    
	'child_shortcode' => array(
		'params' => array(
	   	'title' => array(
				'std' => 'Title',
				'type' => 'text',
				'label' => __('Tab Title', 'babysitter'),
				'desc' => __('Title of the tab', 'babysitter'),
	      ),
	      'content' => array(
				'std' => 'Tab Content',
				'type' => 'textarea',
				'label' => __('Tab Content', 'babysitter'),
				'desc' => __('Add the tabs content', 'babysitter')
	      )
	  ),
		'shortcode' => '[tab title="{{title}}"] {{content}} [/tab]',
		'clone_button' => __('Add Tab', 'babysitter')
	)
);



/*-----------------------------------------------------------------------------------*/
/* Pricing Tables
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['pricing_tables'] = array(
	'params' => array(
		'cols' => array(
			'type' => 'select',
			'label' => __('Number of Columns', 'babysitter'),
			'desc' => __('Select number of columns', 'babysitter'),
			'options' => array(
				'2' => '2',
				'3' => '3',
				'4' => '4'
			)
		)
	),
	'no_preview' => true,
	'shortcode' => '[pricing_tables cols="{{cols}}"] {{child_shortcode}} [/pricing_tables]',
	'popup_title' => __('Insert Tab Shortcode', 'babysitter'),
    
	'child_shortcode' => array(
		'params' => array(
	   	'name' => array(
				'std' => 'Title',
				'type' => 'text',
				'label' => __('Plan Title', 'babysitter'),
				'desc' => __('Title of the plan', 'babysitter'),
			),
	      'price' => array(
				'std' => '$0.00',
				'type' => 'text',
				'label' => __('Plan Price', 'babysitter'),
				'desc' => __('Price of the plan', 'babysitter'),
			),
			'link_txt' => array(
				'std' => 'Sign Up',
				'type' => 'text',
				'label' => __('Plan Button Text', 'babysitter'),
				'desc' => __('Button\'s text of the plan', 'babysitter'),
			),
			'link_url' => array(
				'std' => '',
				'type' => 'text',
				'label' => __('Plan Button URL', 'babysitter'),
				'desc' => __('Button\'s url of the plan', 'babysitter'),
			),
			'active' => array(
				'type' => 'select',
				'label' => __('Featured Plan?', 'babysitter'),
				'desc' => __('If you want to highlight this plan please choose "Yes"', 'babysitter'),
				'options' => array(
					'no' => 'no',
					'yes' => 'yes'
				)
			),
	      'content' => array(
				'std' => 'Plan Content',
				'type' => 'textarea',
				'label' => __('Plan Content', 'babysitter'),
				'desc' => __('Add the plans content (unordered list)', 'babysitter')
	      )
	  ),
		'shortcode' => '[pricing_col name="{{name}}" price="{{price}}" link_txt="{{link_txt}}" link_url="{{link_url}}" active="{{active}}"] {{content}} [/pricing_col]',
		'clone_button' => __('Add Plan Column', 'babysitter')
	)
);



/*-----------------------------------------------------------------------------------*/
/*	Icon Box
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['icobox'] = array(
	'no_preview' => true,
	'params' => array(
		'icon' => array(
			'type' => 'select',
			'label' => __('Icon', 'babysitter'),
			'desc' => __('Select the icon', 'babysitter'),
			'options' => array(
				'none' => 'none',
				'icon-leaf' => 'icon-leaf',
				'icon-glass' => 'icon-glass',
				'icon-envelope-alt' => 'icon-envelope-alt',
				'icon-star-empty' => 'icon-star-empty',
				'icon-th-large' => 'icon-th-large',
				'icon-ok' => 'icon-ok',
				'icon-zoom-out' => 'icon-zoom-out',
				'icon-cog' => 'icon-cog',
				'icon-file-alt' => 'icon-file-alt',
				'icon-download-alt' => 'icon-download-alt', 
				'icon-inbox' => 'icon-inbox', 
				'icon-refresh' => 'icon-refresh',
				'icon-flag' => 'icon-flag',
				'icon-volume-down' => 'icon-volume-down',
				'icon-barcode' => 'icon-barcode',
				'icon-book' => 'icon-book',
				'icon-camera' => 'icon-camera',
				'icon-italic' => 'icon-italic',
				'icon-align-left' => 'icon-align-left',
				'icon-align-justify' => 'icon-align-justify',
				'icon-indent-right' => 'icon-indent-right',
				'icon-pencil' => 'icon-pencil',
				'icon-tint' => 'icon-tint',
				'icon-check' => 'icon-check',
				'icon-fast-backward' => 'icon-fast-backward',
				'icon-pause' => 'icon-pause',
				'icon-fast-forward' => 'icon-fast-forward',
				'icon-chevron-left' => 'icon-chevron-left',
				'icon-minus-sign' => 'icon-minus-sign',
				'icon-question-sign' => 'icon-question-sign',
				'icon-remove-circle' => 'icon-remove-circle',
				'icon-arrow-left' => 'icon-arrow-left',
				'icon-arrow-down' => 'icon-arrow-down',
				'icon-resize-small' => 'icon-resize-small',
				'icon-asterisk' => 'icon-asterisk',
				'icon-leaf' => 'icon-leaf',
				'icon-eye-close' => 'icon-eye-close',
				'icon-calendar' => 'icon-calendar',
				'icon-magnet' => 'icon-magnet',
				'icon-retweet' => 'icon-retweet',
				'icon-folder-open' => 'icon-folder-open',
				'icon-bar-chart' => 'icon-bar-chart',
				'icon-camera-retro' => 'icon-camera-retro',
				'icon-comments' => 'icon-comments',
				'icon-star-half' => 'icon-star-half',
				'icon-linkedin-sign' => 'icon-linkedin-sign',
				'icon-signin' => 'icon-signin',
				'icon-upload-alt' => 'icon-upload-alt',
				'icon-check-empty' => 'icon-check-empty',
				'icon-twitter' => 'icon-twitter',
				'icon-unlock' => 'icon-unlock',
				'icon-hdd' => 'icon-hdd',
				'icon-certificate' => 'icon-certificate',
				'icon-hand-up' => 'icon-hand-up',
				'icon-circle-arrow-right' => 'icon-circle-arrow-right',
				'icon-globe' => 'icon-globe',
				'icon-filter' => 'icon-filter',
				'icon-group' => 'icon-group',
				'icon-beaker' => 'icon-beaker',
				'icon-paper-clip' => 'icon-paper-clip',
				'icon-reorder' => 'icon-reorder',
				'icon-strikethrough' => 'icon-strikethrough',
				'icon-magic' => 'icon-magic',
				'icon-pinterest-sign' => 'icon-pinterest-sign',
				'icon-money' => 'icon-money',
				'icon-caret-left' => 'icon-caret-left',
				'icon-sort' => 'icon-sort',
				'icon-envelope' => 'icon-envelope',
				'icon-legal' => 'icon-legal',
				'icon-comments-alt' => 'icon-comments-alt',
				'icon-umbrella' => 'icon-umbrella',
				'icon-exchange' => 'icon-exchange',
				'icon-user-md' => 'icon-user-md',
				'icon-bell-alt' => 'icon-bell-alt',
				'icon-file-text-alt' => 'icon-file-text-alt',
				'icon-ambulance' => 'icon-ambulance',
				'icon-beer' => 'icon-beer',
				'icon-double-angle-left' => 'icon-double-angle-left', 
				'icon-double-angle-down' => 'icon-double-angle-down',
				'icon-angle-up' => 'icon-angle-up',
				'icon-laptop' => 'icon-laptop',
				'icon-circle-blank' => 'icon-circle-blank',
				'icon-spinner' => 'icon-spinner',
				'icon-github-alt' => 'icon-github-alt',
				'icon-expand-alt' => 'icon-expand-alt',
				'icon-frown' => 'icon-frown',
				'icon-keyboard' => 'icon-keyboard',
				'icon-terminal' => 'icon-terminal',
				'icon-mail-reply-all' => 'icon-mail-reply-all',
				'icon-crop' => 'icon-crop',
				'icon-question' => 'icon-question',
				'icon-superscript' => 'icon-superscript',
				'icon-puzzle-piece' => 'icon-puzzle-piece',
				'icon-shield' => 'icon-shield',
				'icon-rocket' => 'icon-rocket',
				'icon-chevron-sign-right' => 'icon-chevron-sign-right',
				'icon-html5' => 'icon-html5',
				'icon-unlock-alt' => 'icon-unlock-alt',
				'icon-ellipsis-vertical' => 'icon-ellipsis-vertical',
				'icon-ticket' => 'icon-ticket',
				'icon-level-up' => 'icon-level-up',
				'icon-edit-sign' => 'icon-edit-sign',
				'icon-compass' => 'icon-compass',
				'icon-expand' => 'icon-expand',
				'icon-usd' => 'icon-usd',
				'icon-cny' => 'icon-cny',
				'icon-file' => 'icon-file',
				'icon-sort-by-alphabet-alt' => 'icon-sort-by-alphabet-alt', 
				'icon-sort-by-order' => 'icon-sort-by-order',
				'icon-thumbs-down' => 'icon-thumbs-down',
				'icon-xing' => 'icon-xing',
				'icon-dropbox' => 'icon-dropbox',
				'icon-flickr' => 'icon-flickr',
				'icon-bitbucket-sign' => 'icon-bitbucket-sign',
				'icon-long-arrow-down' => 'icon-long-arrow-down',
				'icon-long-arrow-right' => 'icon-long-arrow-right',
				'icon-android' => 'icon-android',
				'icon-skype' => 'icon-skype',
				'icon-female' => 'icon-female',
				'icon-sun' => 'icon-sun',
				'icon-bug' => 'icon-bug',
				'icon-renren' => 'icon-renren',
				'icon-music' => 'icon-music',
				'icon-heart' => 'icon-heart',
				'icon-user' => 'icon-user',
				'icon-th' => 'icon-th',
				'icon-remove' => 'icon-remove',
				'icon-off' => 'icon-off',
				'icon-trash' => 'icon-trash',
				'icon-time' => 'icon-time',
				'icon-download' => 'icon-download',
				'icon-play-circle' => 'icon-play-circle',
				'icon-list-alt' => 'icon-list-alt',
				'icon-headphones' => 'icon-headphones',
				'icon-volume-up' => 'icon-volume-up',
				'icon-tag' => 'icon-tag',
				'icon-bookmark' => 'icon-bookmark',
				'icon-font' => 'icon-font',
				'icon-text-height' => 'icon-text-height',
				'icon-align-center' => 'icon-align-center',
				'icon-list' => 'icon-list',
				'icon-facetime-video' => 'icon-facetime-video',
				'icon-map-marker' => 'icon-map-marker',
				'icon-edit' => 'icon-edit',
				'icon-move' => 'icon-move',
				'icon-backward' => 'icon-backward',
				'icon-stop' => 'icon-stop',
				'icon-step-forward' => 'icon-step-forward',
				'icon-chevron-right' => 'icon-chevron-right',
				'icon-remove-sign' => 'icon-remove-sign',
				'icon-info-sign' => 'icon-info-sign',
				'icon-ok-circle' => 'icon-ok-circle',
				'icon-arrow-right' => 'icon-arrow-right',
				'icon-share-alt' => 'icon-share-alt',
				'icon-plus' => 'icon-plus',
				'icon-exclamation-sign' => 'icon-exclamation-sign',
				'icon-fire' => 'icon-fire',
				'icon-warning-sign' => 'icon-warning-sign',
				'icon-random' => 'icon-random',
				'icon-chevron-up' => 'icon-chevron-up',
				'icon-shopping-cart' => 'icon-shopping-cart',
				'icon-resize-vertical' => 'icon-resize-vertical',
				'icon-twitter-sign' => 'icon-twitter-sign',
				'icon-key' => 'icon-key',
				'icon-thumbs-up-alt' => 'icon-thumbs-up-alt',
				'icon-heart-empty' => 'icon-heart-empty',
				'icon-pushpin' => 'icon-pushpin',
				'icon-trophy' => 'icon-trophy',
				'icon-lemon' => 'icon-lemon',
				'icon-bookmark-empty' => 'icon-bookmark-empty',
				'icon-facebook' => 'icon-facebook',
				'icon-credit-card' => 'icon-credit-card',
				'icon-bullhorn' => 'icon-bullhorn',
				'icon-hand-right' => 'icon-hand-right',
				'icon-hand-down' => 'icon-hand-down',
				'icon-circle-arrow-up' => 'icon-circle-arrow-up',
				'icon-wrench' => 'icon-wrench',
				'icon-briefcase' => 'icon-briefcase',
				'icon-link' => 'icon-link',
				'icon-cut' => 'icon-cut',
				'icon-save' => 'icon-save',
				'icon-list-ul' => 'icon-list-ul',
				'icon-underline' => 'icon-underline',
				'icon-truck' => 'icon-truck',
				'icon-google-plus-sign' => 'icon-google-plus-sign',
				'icon-caret-down' => 'icon-caret-down',
				'icon-caret-right' => 'icon-caret-right',
				'icon-sort-down' => 'icon-sort-down',
				'icon-linkedin' => 'icon-linkedin',
				'icon-dashboard' => 'icon-dashboard',
				'icon-bolt' => 'icon-bolt',
				'icon-paste' => 'icon-paste',
				'icon-cloud-download' => 'icon-cloud-download',
				'icon-stethoscope' => 'icon-stethoscope',
				'icon-coffee' => 'icon-coffee',
				'icon-building' => 'icon-building',
				'icon-medkit' => 'icon-medkit',
				'icon-h-sign' => 'icon-h-sign',
				'icon-double-angle-right' => 'icon-double-angle-right',
				'icon-angle-left' => 'icon-angle-left',
				'icon-angle-down' => 'icon-angle-down',
				'icon-tablet' => 'icon-tablet',
				'icon-quote-left' => 'icon-quote-left',
				'icon-circle' => 'icon-circle',
				'icon-folder-close-alt' => 'icon-folder-close-alt',
				'icon-collapse-alt' => 'icon-collapse-alt',
				'icon-meh' => 'icon-meh',
				'icon-flag-alt' => 'icon-flag-alt',
				'icon-code' => 'icon-code',
				'icon-star-half-empty' => 'icon-star-half-empty',
				'icon-code-fork' => 'icon-code-fork',
				'icon-info' => 'icon-info',
				'icon-subscript' => 'icon-subscript',
				'icon-microphone' => 'icon-microphone',
				'icon-calendar-empty' => 'icon-calendar-empty',
				'icon-maxcdn' => 'icon-maxcdn',
				'icon-chevron-sign-up' => 'icon-chevron-sign-up',
				'icon-css3' => 'icon-css3',
				'icon-bullseye' => 'icon-bullseye',
				'icon-rss-sign' => 'icon-rss-sign',
				'icon-minus-sign-alt' => 'icon-minus-sign-alt',
				'icon-level-down' => 'icon-level-down',
				'icon-external-link-sign' => 'icon-external-link-sign',
				'icon-collapse' => 'icon-collapse',
				'icon-eur' => 'icon-eur',
				'icon-inr' => 'icon-inr',
				'icon-krw' => 'icon-krw',
				'icon-file-text' => 'icon-file-text',
				'icon-sort-by-attributes' => 'icon-sort-by-attributes',
				'icon-sort-by-order-alt' => 'icon-sort-by-order-alt',
				'icon-youtube-sign' => 'icon-youtube-sign',
				'icon-xing-sign' => 'icon-xing-sign',
				'icon-stackexchange' => 'icon-stackexchange',
				'icon-adn' => 'icon-adn',
				'icon-tumblr' => 'icon-tumblr',
				'icon-long-arrow-up' => 'icon-long-arrow-up',
				'icon-apple' => 'icon-apple',
				'icon-linux' => 'icon-linux',
				'icon-foursquare' => 'icon-foursquare',
				'icon-male' => 'icon-male',
				'icon-moon' => 'icon-moon',
				'icon-vk' => 'icon-vk',
				'icon-search' => 'icon-search',
				'icon-star' => 'icon-star',
				'icon-film' => 'icon-film',
				'icon-th-list' => 'icon-th-list',
				'icon-zoom-in' => 'icon-zoom-in',
				'icon-signal' => 'icon-signal',
				'icon-home' => 'icon-home',
				'icon-road' => 'icon-road',
				'icon-upload' => 'icon-upload',
				'icon-repeat' => 'icon-repeat',
				'icon-lock' => 'icon-lock',
				'icon-volume-off' => 'icon-volume-off',
				'icon-qrcode' => 'icon-qrcode',
				'icon-tags' => 'icon-tags',
				'icon-print' => 'icon-print',
				'icon-bold' => 'icon-print',
				'icon-text-width' => 'icon-text-width',
				'icon-align-right' => 'icon-align-right',
				'icon-indent-left' => 'icon-indent-left',
				'icon-picture' => 'icon-picture',
				'icon-adjust' => 'icon-adjust',
				'icon-share' => 'icon-share',
				'icon-step-backward' => 'icon-step-backward',
				'icon-play' => 'icon-play',
				'icon-forward' => 'icon-forward',
				'icon-eject' => 'icon-eject',
				'icon-plus-sign' => 'icon-plus-sign',
				'icon-ok-sign' => 'icon-ok-sign',
				'icon-screenshot' => 'icon-screenshot',
				'icon-ban-circle' => 'icon-ban-circle',
				'icon-arrow-up' => 'icon-arrow-up',
				'icon-resize-full' => 'icon-resize-full',
				'icon-minus' => 'icon-minus',
				'icon-gift' => 'icon-gift',
				'icon-eye-open' => 'icon-eye-open',
				'icon-plane' => 'icon-plane',
				'icon-comment' => 'icon-comment',
				'icon-chevron-down' => 'icon-chevron-down',
				'icon-folder-close' => 'icon-folder-close',
				'icon-resize-horizontal' => 'icon-resize-horizontal',
				'icon-facebook-sign' => 'icon-facebook-sign',
				'icon-cogs' => 'icon-cogs',
				'icon-thumbs-down-alt' => 'icon-thumbs-down-alt',
				'icon-signout' => 'icon-signout',
				'icon-external-link' => 'icon-external-link',
				'icon-github-sign' => 'icon-github-sign',
				'icon-phone' => 'icon-phone',
				'icon-phone-sign' => 'icon-phone-sign',
				'icon-github' => 'icon-github',
				'icon-rss' => 'icon-rss',
				'icon-bell' => 'icon-bell',
				'icon-hand-left' => 'icon-hand-left',
				'icon-circle-arrow-left' => 'icon-circle-arrow-left',
				'icon-circle-arrow-down' => 'icon-circle-arrow-down',
				'icon-tasks' => 'icon-tasks',
				'icon-fullscreen' => 'icon-fullscreen',
				'icon-cloud' => 'icon-cloud',
				'icon-copy' => 'icon-copy',
				'icon-sign-blank' => 'icon-sign-blank',
				'icon-list-ol' => 'icon-list-ol',
				'icon-table' => 'icon-table',
				'icon-pinterest' => 'icon-pinterest',
				'icon-google-plus' => 'icon-google-plus',
				'icon-caret-up' => 'icon-caret-up',
				'icon-columns' => 'icon-columns',
				'icon-sort-up' => 'icon-sort-up',
				'icon-undo' => 'icon-undo',
				'icon-comment-alt' => 'icon-comment-alt',
				'icon-sitemap' => 'icon-sitemap',
				'icon-lightbulb' => 'icon-lightbulb',
				'icon-cloud-upload' => 'icon-cloud-upload',
				'icon-suitcase' => 'icon-suitcase',
				'icon-food' => 'icon-food',
				'icon-hospital' => 'icon-hospital',
				'icon-fighter-jet' => 'icon-fighter-jet',
				'icon-plus-sign-alt' => 'icon-plus-sign-alt',
				'icon-double-angle-up' => 'icon-double-angle-up',
				'icon-angle-right' => 'icon-angle-right',
				'icon-desktop' => 'icon-desktop',
				'icon-mobile-phone' => 'icon-mobile-phone',
				'icon-quote-right' => 'icon-quote-right',
				'icon-reply' => 'icon-reply',
				'icon-folder-open-alt' => 'icon-folder-open-alt',
				'icon-smile' => 'icon-smile',
				'icon-gamepad' => 'icon-gamepad',
				'icon-flag-checkered' => 'icon-flag-checkered',
				'icon-reply-all' => 'icon-reply-all',
				'icon-location-arrow' => 'icon-location-arrow',
				'icon-unlink' => 'icon-unlink',
				'icon-exclamation' => 'icon-exclamation',
				'icon-eraser' => 'icon-eraser',
				'icon-microphone-off' => 'icon-microphone-off',
				'icon-fire-extinguisher' => 'icon-fire-extinguisher',
				'icon-chevron-sign-left' => 'icon-chevron-sign-left',
				'icon-chevron-sign-down' => 'icon-chevron-sign-down',
				'icon-anchor' => 'icon-anchor',
				'icon-ellipsis-horizontal' => 'icon-ellipsis-horizontal',
				'icon-play-sign' => 'icon-play-sign',
				'icon-check-minus' => 'icon-check-minus',
				'icon-check-sign' => 'icon-check-sign',
				'icon-share-sign' => 'icon-share-sign',
				'icon-collapse-top' => 'icon-collapse-top',
				'icon-gbp' => 'icon-gbp',
				'icon-jpy' => 'icon-jpy',
				'icon-btc' => 'icon-btc',
				'icon-sort-by-alphabet' => 'icon-sort-by-alphabet',
				'icon-sort-by-attributes-alt' => 'icon-sort-by-attributes-alt',
				'icon-thumbs-up' => 'icon-thumbs-up',
				'icon-youtube' => 'icon-youtube',
				'icon-youtube-play' => 'icon-youtube-play',
				'icon-instagram' => 'icon-instagram',
				'icon-bitbucket' => 'icon-bitbucket',
				'icon-tumblr-sign' => 'icon-tumblr-sign',
				'icon-long-arrow-left' => 'icon-long-arrow-left',
				'icon-windows' => 'icon-windows',
				'icon-dribble' => 'icon-dribble',
				'icon-trello' => 'icon-trello',
				'icon-gittip' => 'icon-gittip',
				'icon-archive' => 'icon-archive',
				'icon-weibo' => 'icon-weibo'
			)
		),
		'color' => array(
			'type' => 'select',
			'label' => __('Background Color', 'babysitter'),
			'desc' => __('Select the icon\'s background color', 'babysitter'),
			'options' => array(
				'primary' => 'Primary Color',
				'secondary' => 'Secondary Color',
				'tertiary' => 'Tertiary Color',
				'quaternary' => 'Quaternary Color'
			)
		),
		'icon_color' => array(
			'std' => '#ffffff',
			'type' => 'text',
			'label' => __('Icon Color', 'babysitter'),
			'desc' => __('Select the icon\'s color in hex format, i.e. #ffffff', 'babysitter')
		),
		'title' => array(
			'std' => 'Title',
			'type' => 'text',
			'label' => __('Boxe\'s Text', 'babysitter'),
			'desc' => __('Add the icoboxe\'s text', 'babysitter'),
		),
		'text' => array(
			'std' => 'Main Text',
			'type' => 'text',
			'label' => __('Main Text', 'babysitter'),
			'desc' => __('Add some text to describe service', 'babysitter'),
		),
		'size' => array(
			'type' => 'select',
			'label' => __('Button Size', 'babysitter'),
			'desc' => __('Select the button\'s size', 'babysitter'),
			'options' => array(
				'small' => 'Small',
				'normal' => 'Normal',
				'large' => 'Large'
			)
		),
		'linktext' => array(
			'std' => 'Button Text',
			'type' => 'text',
			'label' => __('Link\'s Text', 'babysitter'),
			'desc' => __('Add the link\'s text', 'babysitter'),
		),
		'url' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Link\'s URL', 'babysitter'),
			'desc' => __('Add the link\'s url eg http://example.com', 'babysitter')
		),
		'target' => array(
			'type' => 'select',
			'label' => __('Link Target', 'babysitter'),
			'desc' => __('_self = open in same window. _blank = open in new window', 'babysitter'),
			'options' => array(
				'_self' => '_self',
				'_blank' => '_blank'
			)
		),
		'custom_icon_url' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Custom Icon URL', 'babysitter'),
			'desc' => __('If you don\'t want to use standart icon, put url to your custom icon here', 'babysitter')
		),
	),
	'shortcode' => '[icobox icon="{{icon}}" color="{{color}}" icon_color="{{icon_color}}" title="{{title}}" text="{{text}}" linktext="{{linktext}}" url="{{url}}" target="{{target}}" custom_icon_url="{{custom_icon_url}}"]',
	'popup_title' => __('Insert Button Shortcode', 'babysitter')
);



/*-----------------------------------------------------------------------------------*/
/*	Call to Action
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['cta'] = array(
	'no_preview' => true,
	'params' => array(
		'title' => array(
			'std' => 'Title',
			'type' => 'text',
			'label' => __('Title', 'babysitter'),
			'desc' => __('Add the title of this box', 'babysitter'),
		),
		'subtitle' => array(
			'std' => 'Subtitle',
			'type' => 'text',
			'label' => __('Subtitle', 'babysitter'),
			'desc' => __('Add the subtitle of this box', 'babysitter'),
		),
		'link' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Button URL', 'babysitter'),
			'desc' => __('Add the button\'s url eg http://example.com', 'babysitter')
		),
		'text' => array(
			'std' => 'Button Text',
			'type' => 'text',
			'label' => __('Button\'s Text', 'babysitter'),
			'desc' => __('Add the button\'s text', 'babysitter'),
		)
	),
	'shortcode' => '[cta title="{{title}}" subtitle="{{subtitle}}" text="{{text}}" link="{{link}}"]',
	'popup_title' => __('Insert Call to Action Shortcode', 'babysitter')
);



/*-----------------------------------------------------------------------------------*/
/*	Jumbotron
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['jumbotron'] = array(
	'no_preview' => true,
	'params' => array(
		'title' => array(
			'std' => 'Title',
			'type' => 'text',
			'label' => __('Title', 'babysitter'),
			'desc' => __('Add the title of the jumbotron', 'babysitter'),
		),
		'subtitle' => array(
			'std' => 'Subtitle',
			'type' => 'text',
			'label' => __('Subtitle', 'babysitter'),
			'desc' => __('Add the subtitle of this jumbotron', 'babysitter'),
		),
		'btn_link' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Button URL', 'babysitter'),
			'desc' => __('Add the button\'s url eg http://example.com', 'babysitter')
		),
		'btn_text' => array(
			'std' => 'Button Text',
			'type' => 'text',
			'label' => __('Button\'s Text', 'babysitter'),
			'desc' => __('Add the button\'s text', 'babysitter'),
		),
		'align' => array(
			'type' => 'select',
			'label' => __('Link Target', 'babysitter'),
			'desc' => __('_self = open in same window. _blank = open in new window', 'babysitter'),
			'options' => array(
				'center' => 'Center',
				'left' => 'Left',
				'right' => 'Right'
			)
		)
	),
	'shortcode' => '[jumbotron title="{{title}}" subtitle="{{subtitle}}" btn_text="{{btn_text}}" btn_link="{{btn_link}}" align="{{align}}"]',
	'popup_title' => __('Insert Jumbotron Shortcode', 'babysitter')
);



/*-----------------------------------------------------------------------------------*/
/*	InfoBox
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['infobox'] = array(
	'no_preview' => true,
	'params' => array(
		'title' => array(
			'std' => 'Title',
			'type' => 'text',
			'label' => __('Title', 'babysitter'),
			'desc' => __('Add the title of this box', 'babysitter'),
		),
		'num' => array(
			'std' => '1',
			'type' => 'text',
			'label' => __('Digit', 'babysitter'),
			'desc' => __('Add the digit of this box, ie 1, 2, 3 ...', 'babysitter'),
		),
		'color' => array(
			'type' => 'select',
			'label' => __('Color', 'babysitter'),
			'desc' => __('Choose color of this box', 'babysitter'),
			'options' => array(
				'primary' => 'Primary',
				'secondary' => 'Secondary',
				'tertiary' => 'Tertiary'
			)
		),
		'background' => array(
			'type' => 'select',
			'label' => __('Background', 'babysitter'),
			'desc' => __('Should it be filled with background?', 'babysitter'),
			'options' => array(
				'yes' => 'Yes',
				'no' => 'No'
			)
		),
		'arrow' => array(
			'type' => 'select',
			'label' => __('Add Arrow?', 'babysitter'),
			'desc' => __('Should it be with arrow?', 'babysitter'),
			'options' => array(
				'no' => 'No',
				'yes' => 'Yes'
			)
		),
		'content' => array(
			'std' => 'Content goes here',
			'type' => 'textarea',
			'label' => __('Infobox Content', 'babysitter'),
			'desc' => __('Add the box content', 'babysitter')
		)
	),
	'shortcode' => '[infobox num="{{num}}" title="{{title}}" color="{{color}}" background="{{background}}" arrow="{{arrow}}"] {{content}} [/infobox] ',
	'popup_title' => __('Insert Infobox Shortcode', 'babysitter')
);



/*-----------------------------------------------------------------------------------*/
/*	Link
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['link'] = array(
	'no_preview' => true,
	'params' => array(
		'content' => array(
			'std' => 'Link Text',
			'type' => 'text',
			'label' => __('Link Text', 'babysitter'),
			'desc' => __('Add the link\'s text', 'babysitter'),
		)
	),
	'shortcode' => '[link] {{content}} [/link]',
	'popup_title' => __('Insert Link Shortcode', 'babysitter')
);



/*-----------------------------------------------------------------------------------*/
/*	List Styles
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['list'] = array(
	'no_preview' => true,
	'params' => array(
		'style' => array(
			'type' => 'select',
			'label' => __('List Style', 'babysitter'),
			'desc' => __('Select the list\'s style, ie the marker type', 'babysitter'),
			'options' => array(
				'none' => 'None',
				'checklist' => 'Check list',
				'arrow1' => 'Arrow1',
				'arrow2' => 'Arrow2',
				'arrow3' => 'Arrow3',
				'arrow4' => 'Arrow4',
				'star' => 'Star List'
			)
		),
		'color' => array(
			'type' => 'select',
			'label' => __('List Color', 'babysitter'),
			'desc' => __('Select the list\'s color, ie the marker color', 'babysitter'),
			'options' => array(
				'primary' => 'Primary',
				'secondary' => 'Secondary',
				'tertiary' => 'Tertiary',
				'quaternary' => 'Quaternary',
				'quinary' => 'Quinary'
			)
		)
		
	),
	'shortcode' => '[list style="{{style}}" color="{{color}}"] [/list]',
	'popup_title' => __('Insert List Shortcode', 'babysitter')
);



/*-----------------------------------------------------------------------------------*/
/*	Dropcaps
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['dropcap'] = array(
	'no_preview' => true,
	'params' => array(
		'style' => array(
			'type' => 'select',
			'label' => __('Style', 'babysitter'),
			'desc' => __('Select the dropcap\'s style, ie the color', 'babysitter'),
			'options' => array(
				'primary' => 'Primary',
				'secondary' => 'Secondary',
				'tertiary' => 'Tertiary',
				'quaternary' => 'Quaternary'
			)
		),
		'content' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Dropcap Letter', 'babysitter'),
			'desc' => __('Add the single letter, ie A, B, C etc', 'babysitter'),
		)
	),
	'shortcode' => '[dropcap style="{{style}}"] {{content}} [/dropcap]',
	'popup_title' => __('Insert Dropcap Shortcode', 'babysitter')
);



/*-----------------------------------------------------------------------------------*/
/*	Link
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['txt_lead'] = array(
	'no_preview' => true,
	'params' => array(
		'content' => array(
			'std' => 'Title',
			'type' => 'text',
			'label' => __('Title', 'babysitter'),
			'desc' => __('Add your title here', 'babysitter'),
		)
	),
	'shortcode' => '[txt_lead] {{content}} [/txt_lead]',
	'popup_title' => __('Insert Text Lead Shortcode', 'babysitter')
);



/*-----------------------------------------------------------------------------------*/
/*	Accordion
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['accordion'] = array(
	'params' => array(),
	'no_preview' => true,
	'shortcode' => '[accordion] {{child_shortcode}} [/accordion]',
	'popup_title' => __('Insert Accordion Shortcode', 'babysitter'),
    
	'child_shortcode' => array(
		'params' => array(
	   	'title' => array(
				'std' => 'Title',
				'type' => 'text',
				'label' => __('Panel Title', 'babysitter'),
				'desc' => __('Title of the panel', 'babysitter'),
	      ),
	      'content' => array(
				'std' => 'Content',
				'type' => 'textarea',
				'label' => __('Panel Content', 'babysitter'),
				'desc' => __('Add the panel\'s content', 'babysitter')
	      ),
	      'state' => array(
				'type' => 'select',
				'label' => __('Panel State', 'babysitter'),
				'desc' => __('Select the state of the toggle on page load', 'babysitter'),
				'options' => array(
					'open' => 'Open',
					'closed' => 'Closed'
				)
			)
	  ),
		'shortcode' => '[panel title="{{title}}" state="{{state}}"] {{content}} [/panel]',
		'clone_button' => __('Add Panel', 'babysitter')
	)
);



/*-----------------------------------------------------------------------------------*/
/*	Definition List
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['dl'] = array(
	'params' => array(),
	'no_preview' => true,
	'shortcode' => '[dl] {{child_shortcode}} [/dl]',
	'popup_title' => __('Insert List Shortcode', 'babysitter'),
    
	'child_shortcode' => array(
		'params' => array(
	   	'title' => array(
				'std' => 'Title',
				'type' => 'text',
				'label' => __('Item Title', 'babysitter'),
				'desc' => __('Title of the item', 'babysitter'),
	      ),
	      'content' => array(
				'std' => 'Content',
				'type' => 'textarea',
				'label' => __('Item Content', 'babysitter'),
				'desc' => __('Add the item\'s content', 'babysitter')
	      )
	  ),
		'shortcode' => '[def_item title="{{title}}"] {{content}} [/def_item]',
		'clone_button' => __('Add Item', 'babysitter')
	)
);


/*-----------------------------------------------------------------------------------*/
/*	Carousel
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['carousel'] = array(
	'params' => array(),
	'no_preview' => true,
	'shortcode' => '[carousel] {{child_shortcode}} [/carousel]',
	'popup_title' => __('Insert Carousel Shortcode', 'babysitter'),
    
	'child_shortcode' => array(
		'params' => array(
	      'content' => array(
				'std' => 'Content',
				'type' => 'textarea',
				'label' => __('Item Content', 'babysitter'),
				'desc' => __('Add the item\'s content', 'babysitter')
	      )
	  ),
		'shortcode' => '[item] {{content}} [/item]',
		'clone_button' => __('Add Item', 'babysitter')
	)
);



/*-----------------------------------------------------------------------------------*/
/*	Slider
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['slider'] = array(
	'params' => array(
		'animation' => array(
			'type' => 'select',
			'label' => __('Animation', 'babysitter'),
			'desc' => __('Select animation', 'babysitter'),
			'options' => array(
				'fade' => 'Fade',
				'slide' => 'Slide'
			)
		),
		'nav' => array(
			'type' => 'select',
			'label' => __('Show navigation?', 'babysitter'),
			'desc' => __('Prev/next buttons', 'babysitter'),
			'options' => array(
				'true' => 'Yes',
				'false' => 'No'
			)
		),
		'bullets' => array(
			'type' => 'select',
			'label' => __('Show pagination?', 'babysitter'),
			'desc' => __('Bullets', 'babysitter'),
			'options' => array(
				'true' => 'Yes',
				'false' => 'No'
			)
		),
		'speed' => array(
			'std' => '7000',
			'type' => 'text',
			'label' => __('Animation Speed', 'babysitter'),
			'desc' => __('Add the animation speed', 'babysitter')
      )
	),
	'no_preview' => true,
	'shortcode' => '[slider animation="{{animation}}" nav="{{nav}}" bullets="{{bullets}}" speed="{{speed}}"] {{child_shortcode}} [/slider]',
	'popup_title' => __('Insert Slider Shortcode', 'babysitter'),
    
	'child_shortcode' => array(
		'params' => array(
			'content' => array(
				'std' => 'Content',
				'type' => 'textarea',
				'label' => __('Item Content', 'babysitter'),
				'desc' => __('Add the item\'s content', 'babysitter')
	      )
		),
		'shortcode' => '[slide] {{content}} [/slide]',
		'clone_button' => __('Add Panel', 'babysitter')
	)
);



/*-----------------------------------------------------------------------------------*/
/*	Jobs List
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['jobs'] = array(
	'no_preview' => true,
	'params' => array(
		'per_page' => array(
			'std' => '10',
			'type' => 'text',
			'label' => __('Number of Jobs', 'babysitter'),
			'desc' => __('This controls how many jobs get listed per page', 'babysitter'),
		),
		'orderby' => array(
			'type' => 'select',
			'label' => __('Order', 'babysitter'),
			'desc' => __('Select the job\'s order', 'babysitter'),
			'options' => array(
				'date' => 'date',
				'name' => 'name',
				'title' => 'title',
				'modified' => 'modified',
				'parent' => 'parent',
				'rand' => 'rand'
			)
		),
		'order' => array(
			'type' => 'select',
			'label' => __('Sorting directio', 'babysitter'),
			'desc' => __('Choose the sorting direction', 'babysitter'),
			'options' => array(
				'desc' => 'desc',
				'asc' => 'asc'
			)
		),
		'show_filters' => array(
			'type' => 'select',
			'label' => __('Show Filters', 'babysitter'),
			'desc' => __('Shows filters above the job list letting the user narrow the list by keyword, location, and job type. Once a filter is chosen, active filters are listed above the jobs, as is an "RSS" link for the current search', 'babysitter'),
			'options' => array(
				'true' => 'Yes',
				'false' => 'No'
			)
		),
		'show_categories' => array(
			'type' => 'select',
			'label' => __('Show Categories', 'babysitter'),
			'desc' => __('f enabled, the filters will also show a dropdown letting the user choose a job category to filter by', 'babysitter'),
			'options' => array(
				'true' => 'Yes',
				'false' => 'No'
			)
		),
		'categories' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Categories', 'babysitter'),
			'desc' => __('Comma separate slugs to limit the jobs to certain categories. This option overrides "show_categories" if both are set.', 'babysitter'),
		)
	),
	'shortcode' => '[jobs per_page="{{per_page}}" orderby="{{orderby}}" order="{{order}}" show_filters="{{show_filters}}" show_categories="{{show_categories}}" categories="{{categories}}"]',
	'popup_title' => __('Insert Jobs Shortcode', 'babysitter')
);




/*-----------------------------------------------------------------------------------*/
/*	Single Job
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['job'] = array(
	'no_preview' => true,
	'params' => array(
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Job ID', 'babysitter'),
			'desc' => __('Outputs a single job by ID. You can find the id by viewing the list of jobs in admin', 'babysitter'),
		)
	),
	'shortcode' => '[job id="{{id}}"]',
	'popup_title' => __('Insert Job Shortcode', 'babysitter')
);



/*-----------------------------------------------------------------------------------*/
/*	Job Summary
/*-----------------------------------------------------------------------------------*/

$zilla_shortcodes['job_summary'] = array(
	'no_preview' => true,
	'params' => array(
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __('Job ID', 'babysitter'),
			'desc' => __('Outputs a single job\'s summary by ID. You can find the id by viewing the list of jobs in admin', 'babysitter'),
		),
		'width' => array(
			'std' => '100%',
			'type' => 'text',
			'label' => __('Width', 'babysitter'),
			'desc' => __('Set block size in percents, eg 100%', 'babysitter'),
		)
	),
	'shortcode' => '[job_summary id="{{id}}" width="{{width}}"]',
	'popup_title' => __('Insert Job Summary Shortcode', 'babysitter')
);


?>