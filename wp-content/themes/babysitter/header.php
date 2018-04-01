<!DOCTYPE html>
<!--[if IE 7]>                  <html <?php language_attributes(); ?> class="ie7 no-js"><![endif]-->
<!--[if lte IE 8]>              <html <?php language_attributes(); ?> class="ie8 no-js"><![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?> class="not-ie no-js">  <!--<![endif]-->
<head>

	<!-- Basic Page Needs
	================================================== -->
	<meta charset="<?php bloginfo('charset'); ?>">
	<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>
	
	<?php if(of_get_option('responsive_design', 'yes') != 'no') { ?>
	<!-- Mobile Specific Metas
	================================================== -->
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0">
	<?php } ?>

	<!-- Metas
	================================================== -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="description" content="<?php bloginfo('description'); ?>">

	<!-- dns prefetch -->
	<link href="//www.google-analytics.com" rel="dns-prefetch">

	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="<?php echo of_get_option('favicon', '' ); ?>">
	<link rel="apple-touch-icon" href="<?php echo of_get_option('favicon_iphone', '' ); ?>">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo of_get_option('favicon_ipad', '' ); ?>">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo of_get_option('favicon_iphone_retina', '' ); ?>">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo of_get_option('favicon_ipad_new', '' ); ?>">
	
	<!--[if lt IE 9]>
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ie/ie8.css" media="screen" />
	<![endif]-->
	
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- CSS + JavaScript + jQuery  -->
	<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>

	<?php $layout = of_get_option('layout'); ?>

	<!-- BEGIN WRAPPER -->
	<div id="wrapper" class="<?php echo $layout; ?>">
		
		<!-- Top Bar -->
		<div id="top-bar" class="top-bar">
			<?php if(of_get_option('top_bar', 'yes') == 'yes'): ?>
			<div class="container clearfix">
				<div class="grid_12 mobile-nomargin">
					<?php if(!is_user_logged_in()) { ?>
						<?php _e('Have an account?', 'babysitter'); ?> <a href="<?php echo wp_login_url(); ?>"><?php _e('Log in', 'babysitter'); ?></a> <?php _e('or', 'babysitter'); ?> <?php wp_register('', ''); ?>
					<?php } else {
						wp_loginout();
					} ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<!-- Top Bar / End -->

		<div class="main-box">

			<!-- Header -->
			<header id="header" class="header" role="banner">
				<div class="container clearfix">
					<div class="grid_7 mobile-nomargin">
						<!-- Logo -->
						<div id="logo" class="logo">
							
							<?php if(of_get_option('logo_type') == 'text_logo'){ ?>
								<!-- Text based Logo -->
								<?php if( is_front_page() || is_home() || is_404() ) { ?>
								<h1><a href="<?php echo home_url(); ?>" title="<?php bloginfo('description'); ?>"><?php bloginfo('name'); ?></a></h1>
								<?php } else { ?>
								<h2><a href="<?php echo home_url(); ?>" title="<?php bloginfo('description'); ?>"><?php bloginfo('name'); ?></a></h2>
								<!-- /Text based Logo -->
								<?php } ?>
							
							<?php } else { ?>
								<!-- Image based Logo -->
								<a href="<?php echo home_url(); ?>"><img src="<?php echo of_get_option('logo_url', "" ); ?>" alt="<?php bloginfo('name'); ?>" title="<?php bloginfo('description'); ?>"></a>
								<!-- /Image based Logo -->
							<?php }?>

							<?php if ( get_bloginfo( 'description' ) ) { ?>
								<!-- Tagline -->
								<p class="tagline"><?php bloginfo('description'); ?></p>
								<!-- /Tagline -->
							<?php } ?>
							
						</div>
						<!-- /Logo -->
					</div>

					<div class="grid_5 mobile-nomargin">
						<div class="prefix_1">
							<!-- Header Info -->
							<div class="header-info">
								<?php if(of_get_option('header_info')) { ?>
								<!-- Phone Number -->
								<div class="phone-num">
									<?php echo of_get_option('header_info'); ?>
								</div>
								<!-- Phone Number / End -->
								<?php } ?>

								<!-- Social Links -->
								<ul class="social-links">
									<?php if(of_get_option('social_twitter')): ?>
									<li class="link-twitter"><a href="<?php echo of_get_option('social_twitter'); ?>"><i class="icon-twitter"></i></a>
									</li>
									<?php endif; ?>
									<?php if(of_get_option('social_facebook')): ?>
									<li class="link-facebook"><a href="<?php echo of_get_option('social_facebook'); ?>"><i class="icon-facebook"></i></a>
									</li>
									<?php endif; ?>
									<?php if(of_get_option('social_google')): ?>
									<li class="link-googleplus"><a href="<?php echo of_get_option('social_google'); ?>"><i class="icon-google-plus"></i></a>
									</li>
									<?php endif; ?>
									<?php if(of_get_option('social_dribbble')): ?>
									<li class="link-dribbble"><a href="<?php echo of_get_option('social_dribbble'); ?>"><i class="icon-dribbble"></i></a>
									</li>
									<?php endif; ?>
									<?php if(of_get_option('social_youtube')): ?>
									<li class="link-youtube"><a href="<?php echo of_get_option('social_youtube'); ?>"><i class="icon-youtube"></i></a>
									</li>
									<?php endif; ?>
									<?php if(of_get_option('social_pinterest')): ?>
									<li class="link-pinterest"><a href="<?php echo of_get_option('social_pinterest'); ?>"><i class="icon-pinterest"></i></a>
									</li>
									<?php endif; ?>
									<?php if(of_get_option('social_instagram')): ?>
									<li class="link-instagram"><a href="<?php echo of_get_option('social_instagram'); ?>"><i class="icon-instagram"></i></a>
									</li>
									<?php endif; ?>
									<?php if(of_get_option('social_linkedin')): ?>
									<li class="link-linkedin"><a href="<?php echo of_get_option('social_linkedin'); ?>"><i class="icon-linkedin"></i></a>
									</li>
									<?php endif; ?>
									<?php if(of_get_option('social_yelp')): ?>
									<li class="link-yelp"><a href="<?php echo of_get_option('social_yelp'); ?>"></a>
									</li>
									<?php endif; ?>
									<?php if(of_get_option('social_rss')): ?>
									<li class="link-rss"><a href="<?php echo of_get_option('social_rss'); ?>"><i class="icon-rss"></i></a>
									</li>
									<?php endif; ?>
									<?php if(of_get_option('social_email')): ?>
									<li class="link-email"><a href="mailto:<?php echo of_get_option('social_email'); ?>"><i class="icon-envelope"></i></a>
									</li>
									<?php endif; ?>
								</ul>
								<!-- /Social Links -->

							</div>
							<!-- Header Info / End -->
						</div>
					</div>
				</div>

				<!-- Navigation -->
				<?php if($layout == "full_width") { ?>
				<nav class="primary clearfix" role="navigation">
					<div class="container clearfix">
						<div class="grid_12 mobile-nomargin">
							<?php babysitter_nav(); ?>
						</div>
					</div>
				</nav>
				<?php } else { ?>
				<div class="container clearfix">
					<div class="grid_12 mobile-nomargin">
						<nav class="primary clearfix" role="navigation">
							<?php babysitter_nav(); ?>
						</nav>
					</div>
				</div>
				<?php } ?>
				<!-- /Navigation -->

			</header>
			<!-- /Header -->
			
			<?php if(!is_search() && !is_404()) { // search and 404 pages excluded to avoid errors
				$title = get_post_meta(get_the_ID(), 'babysitter_page_title', true);
				$slider = get_post_meta(get_the_ID(), 'babysitter_page_slider', true);

				// Page Heading
				if($title != "Hide") {
					get_template_part('title');
				}

				// Slider
				if($slider == "Show") {
					get_template_part('slider');
				}

			} elseif(is_search() || is_404()) {
				get_template_part('title');
			} ?>

			<!-- Content Wrapper -->
			<div id="content-wrapper" class="content-wrapper">
				<div class="container">