<?php
/*
Plugin Name: WP Reset
Plugin URI: https://plugins.svn.wordpress.org/wp-reset/trunk/WP-Reset/
Description: It's Resets the your database to the default installation and does not modify files. Deletes all customizations and content of wordpress.
Version: 1.0.1
Author: WP Dev
Author URI: https://github.com
Text Domain: WP-Reset
*/

if ( ! defined( 'ABSPATH' ) ){
	exit; // Exit if accessed this file directly
} 

if ( is_admin() ) {

define( 'REACTIVATE_THE_WP_RESET', true );

class WPReset {
	
	function WPReset() {
		add_action( 'admin_menu', array( &$this, 'add_set_page' ) );
		add_action( 'admin_init', array( &$this, 'admin_init' ) );
		add_filter( 'favorite_actions', array( &$this, 'action_favorite' ), 100 );
		add_action( 'wp_before_admin_bar_render', array( &$this, 'link_admin_bar' ) );
	}
	
	// Checks wp_reset post value and performs an installation.
	function admin_init() {
		global $current_user;

		$wp_reset = ( isset( $_POST['wp_reset'] ) && $_POST['wp_reset'] == 'true' ) ? true : false;
		$wp_reset_confirm = ( isset( $_POST['wp_reset_confirm'] ) && $_POST['wp_reset_confirm'] == 'wp-reset' ) ? true : false;
		$valid_nonce = ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'wp_reset' ) ) ? true : false;

		if ( $wp_reset && $wp_reset_confirm && $valid_nonce ) {
			require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );

			$blogname = get_option( 'blogname' );
			$admin_email = get_option( 'admin_email' );
			$blog_public = get_option( 'blog_public' );

			if ( $current_user->user_login != 'admin' )
				$user = get_user_by( 'login', 'admin' );

			if ( empty( $user->user_level ) || $user->user_level < 10 )
				$user = $current_user;

			global $wpdb;

			$prefix = str_replace( '_', '\_', $wpdb->prefix );
			$tables = $wpdb->get_col( "SHOW TABLES LIKE '{$prefix}%'" );
			foreach ( $tables as $table ) {
				$wpdb->query( "DROP TABLE $table" );
			}

			$result = wp_install( $blogname, $user->user_login, $user->user_email, $blog_public );
			extract( $result, EXTR_SKIP );

			$query = $wpdb->prepare( "UPDATE $wpdb->users SET user_pass = '".$user->user_pass."', user_activation_key = '' WHERE ID =  '".$user_id."' ");
			$wpdb->query( $query );

			$get_user_meta = function_exists( 'get_user_meta' ) ? 'get_user_meta' : 'get_usermeta';
			$update_user_meta = function_exists( 'update_user_meta' ) ? 'update_user_meta' : 'update_usermeta';

			if ( $get_user_meta( $user_id, 'default_password_nag' ) )
				$update_user_meta( $user_id, 'default_password_nag', false );

			if ( $get_user_meta( $user_id, $wpdb->prefix . 'default_password_nag' ) )
				$update_user_meta( $user_id, $wpdb->prefix . 'default_password_nag', false );

			
			if ( defined( 'REACTIVATE_THE_WP_RESET' ) && REACTIVATE_THE_WP_RESET === true )
				@activate_plugin( plugin_basename( __FILE__ ) );
			

			wp_clear_auth_cookie();
			
			wp_set_auth_cookie( $user_id );

			wp_redirect( admin_url()."?wp-reset=wp-reset" );
			exit();
		}

		if ( array_key_exists( 'wp-reset', $_GET ) && stristr( $_SERVER['HTTP_REFERER'], 'wp-reset' ) )
			add_action( 'admin_notices', array( &$this, 'my_wordpress_successfully_reset' ) );
	}
	
	// admin_menu action hook operations & Add the settings page
	function add_set_page() {
		if ( current_user_can( 'level_10' ) && function_exists( 'add_management_page' ) )
			$hook = add_management_page( 'WP Reset', 'WP Reset', 'level_10', 'wp-reset', array( &$this, 'admin_page' ) );
			add_action( "admin_print_scripts-{$hook}", array( &$this, 'admin_script' ) );
			add_action( "admin_footer-{$hook}", array( &$this, 'footer_script' ) );
	}
	
	function action_favorite( $actions ) {
		$reset['tools.php?page=wp-reset'] = array( 'WP Reset', 'level_10' );
		return array_merge( $reset, $actions );
	}

	function link_admin_bar() {
		global $wp_admin_bar;
		$wp_admin_bar->add_menu(
			array(
				'parent' => 'site-name',
				'id'     => 'wp-reset',
				'title'  => 'WP Reset',
				'href'   => admin_url( 'tools.php?page=wp-reset' )
			)
		);
	}
	
	// Inform the user that WordPress has been successfully reset
	function my_wordpress_successfully_reset() {
		$user = get_user_by( 'id', 1 );
		echo '<div id="message" class="updated fade"><p><strong>WordPress has been reset back to defaults. The user "' . $user->user_login . '" was recreated with its previous password.</strong></p></div>';
		do_action( 'wordpress_reset_post', $user );
	}
	
	function admin_script() {
		wp_enqueue_script( 'jquery' );
	}

	function footer_script() {
	?>
	<script type="text/javascript">
		jQuery('#wp_reset_submit').click(function(){
			if ( jQuery('#wp_reset_confirm').val() == 'wp-reset' ) {
				var message = 'This action is not reversable.\n\nClicking "OK" will reset your database back to it\'s defaults. Click "Cancel" to abort.'
				var reset = confirm(message);
				if ( reset ) {
					jQuery('#wp_reset_form').submit();
				} else {
					jQuery('#wp_reset').val('false');
					return false;
				}
			} else {
				alert('Invalid confirmation. Please type \'wp-reset\' in the confirmation field.');
				return false;
			}
		});
	</script>	
	<?php
	}

	// add_option_page callback operations
	function admin_page() {
		global $current_user;
		if ( isset( $_POST['wp_reset_confirm'] ) && $_POST['wp_reset_confirm'] != 'wp-reset' )
			echo '<div class="error fade"><p><strong>Invalid confirmation. Please type \'wp-reset\' in the confirmation field.</strong></p></div>';
		elseif ( isset( $_POST['_wpnonce'] ) )
			echo '<div class="error fade"><p><strong>Invalid wpnonce. Please try again.</strong></p></div>';
			
	?>
	<div class="wrap">
		<div id="icon-tools" class="icon32"><br /></div>
		<h2>WP Reset</h2>
		<p><strong>After completing this reset you will be taken to the dashboard.</strong></p>
		
		<?php $admin = get_user_by( 'login', 'admin' ); ?>
		<?php if ( ! isset( $admin->user_login ) || $admin->user_level < 10 ) : $user = $current_user; ?>
		<p>The 'admin' user does not exist. The user '<strong><?php echo esc_html( $user->user_login ); ?></strong>' will be recreated with its <strong>current password</strong> with user level 10.</p>
		<?php else : ?>
		
		<p>The '<strong>admin</strong>' user exists and will be recreated with its <strong>current password</strong>.</p>
		<?php endif; ?>
		<p>This plugin <strong>will be automatically reactivated</strong> after the reset operation.</p>
		
		<hr/>
		<h3>Reset</h3>
		<p>Type <strong>wp-reset</strong> in the confirmation field to confirm the reset and then click the Reset button:</p>
		<form id="wp_reset_form" action="" method="post" autocomplete="off">
			<?php wp_nonce_field( 'wp_reset' ); ?>
			<input id="wp_reset" type="hidden" name="wp_reset" value="true" />
			<input id="wp_reset_confirm" type="text" name="wp_reset_confirm" value="" maxlength="8" />
			<input id="wp_reset_submit" style="width: 80px;" type="submit" name="Submit" class="button-primary" value="Reset" />
			
		</form>
	</div>
	<?php
	}
}

$WPReset = new WPReset();

}
