<?php
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Orders
 */
class WP_Job_Manager_WCPL_Forms {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
		add_filter( 'submit_job_steps', array( $this, 'submit_job_steps' ), 10 );
		add_filter( 'submit_resume_steps', array( $this, 'submit_resume_steps' ), 10 );
	}

	/**
	 * Add form styles
	 */
	public function styles() {
		wp_enqueue_style( 'wc-paid-listings-packages', JOB_MANAGER_WCPL_PLUGIN_URL . '/assets/css/packages.css' );
	}

	/**
	 * Change submit button text
	 * @return string
	 */
	public function submit_button_text() {
		return __( 'Choose a package &rarr;', 'wp-job-manager-wc-paid-listings' );
	}

	/**
	 * Return packages
	 * @return array
	 */
	public static function get_job_packages() {
		return get_posts( array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => array( 'job_package', 'job_package_subscription' )
				)
			),
			'meta_query'     => array(
				array(
					'key'     => '_visibility',
					'value'   => array( 'visible', 'catalog' ),
					'compare' => 'IN'
				)
			)
		) );
	}

	/**
	 * Return packages
	 * @return array
	 */
	public static function get_resume_packages() {
		return get_posts( array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => array( 'resume_package', 'resume_package_subscription' )
				)
			),
			'meta_query'     => array(
				array(
					'key'     => '_visibility',
					'value'   => array( 'visible', 'catalog' ),
					'compare' => 'IN'
				)
			)
		) );
	}

	/**
	 * Change the steps during the submission process
	 *
	 * @param  array $steps
	 * @return array
	 */
	public function submit_job_steps( $steps ) {
		if ( self::get_job_packages() && apply_filters( 'wcpl_enable_paid_job_listing_submission', true, $this ) ) {
			// We need to hijack the preview submission so we can take a payment
			$steps['preview']['handler'] = array( $this, 'preview_handler' );

			// Add the payment step
			$steps['wc-pay'] = array(
				'name'     => __( 'Choose a package', 'wp-job-manager-wc-paid-listings' ),
				'view'     => array( __CLASS__, 'choose_package' ),
				'handler'  => array( __CLASS__, 'choose_package_handler' ),
				'priority' => 25
			);

			add_filter( 'submit_job_step_preview_submit_text', array( $this, 'submit_button_text' ), 10 );
		}
		return $steps;
	}

	/**
	 * Change the steps during the submission process
	 *
	 * @param  array $steps
	 * @return array
	 */
	public function submit_resume_steps( $steps ) {
		if ( self::get_resume_packages() && apply_filters( 'wcpl_enable_paid_resume_submission', true, $this ) ) {
			// We need to hijack the preview submission so we can take a payment
			$steps['preview']['handler'] = array( $this, 'preview_resume_handler' );

			// Add the payment step
			$steps['wc-pay'] = array(
				'name'     => __( 'Choose a package', 'wp-job-manager-wc-paid-listings' ),
				'view'     => array( __CLASS__, 'choose_resume_package' ),
				'handler'  => array( __CLASS__, 'choose_resume_package_handler' ),
				'priority' => 25
			);

			add_filter( 'submit_resume_step_preview_submit_text', array( $this, 'submit_button_text' ), 10 );
		}
		return $steps;
	}

	/**
	 * [choose_package description]
	 * @return [type]
	 */
	public static function choose_package() {
		$packages      = self::get_job_packages();
		$user_packages = wc_paid_listings_get_user_packages( get_current_user_id(), 'job_listing' );
		?>
		<form method="post" id="job_package_selection">
			<div class="job_listing_packages_title">
				<input type="submit" name="continue" class="button" value="<?php echo apply_filters( 'submit_job_step_choose_package_submit_text', __( 'Submit &rarr;', 'wp-job-manager-wc-paid-listings' ) ); ?>" />
				<input type="hidden" name="job_id" value="<?php echo esc_attr( WP_Job_Manager_Form_Submit_Job::get_job_id() ); ?>" />
				<input type="hidden" name="step" value="<?php echo esc_attr( WP_Job_Manager_Form_Submit_Job::get_step() ); ?>" />
				<input type="hidden" name="job_manager_form" value="<?php echo WP_Job_Manager_Form_Submit_Job::$form_name; ?>" />
				<h2><?php _e( 'Choose a package', 'wp-job-manager-wc-paid-listings' ); ?></h2>
			</div>
			<div class="job_listing_packages">
				<?php get_job_manager_template( 'package-selection.php', array( 'packages' => $packages, 'user_packages' => $user_packages ), 'wc-paid-listings', JOB_MANAGER_WCPL_PLUGIN_DIR . '/templates/' ); ?>
			</div>
		</form>
		<?php
	}

	/**
	 * [choose_package_handler description]
	 * @return [type]
	 */
	public static function choose_package_handler() {
		global $woocommerce;

		// Get and validate package
		if ( is_numeric( $_POST['job_package'] ) )
			$package_id = absint( $_POST['job_package'] );
		else
			$user_job_package_id = absint( substr( $_POST['job_package'], 5 ) );

		// Get job ID
		$job_id = WP_Job_Manager_Form_Submit_Job::get_job_id();

		if ( ! empty( $package_id ) ) {
			$package = get_product( $package_id );

			if ( ! $package->is_type( 'job_package' ) && ! $package->is_type( 'job_package_subscription' ) ) {
				WP_Job_Manager_Form_Submit_Job::add_error( __( 'Invalid Package', 'wp-job-manager-wc-paid-listings' ) );
				return;
			}

			// Don't let them buy twice
			if ( class_exists( 'WC_Subscriptions' ) && is_user_logged_in() ) {
				$user_subscriptions = WC_Subscriptions_Manager::get_users_subscriptions( get_current_user_id() );
				foreach ( $user_subscriptions as $user_subscription ) {
					if ( $user_subscription['product_id'] == $package_id ) {
						WP_Job_Manager_Form_Submit_Job::add_error( __( 'You already have this subscription.', 'wp-job-manager-wc-paid-listings' ) );
						return;
					}
				}
			}

			// Give job the package attributes
			update_post_meta( $job_id, '_job_duration', $package->get_duration() );
			update_post_meta( $job_id, '_featured', $package->is_featured() ? 1 : 0 );
			update_post_meta( $job_id, '_package_id', $package_id );

			// Add package to the cart
			$woocommerce->cart->add_to_cart( $package_id, 1, '', '', array(
				'job_id' => $job_id
			) );

			woocommerce_add_to_cart_message( $package_id );

			// Redirect to checkout page
			wp_redirect( get_permalink( woocommerce_get_page_id( 'checkout' ) ) );
			exit;
		} elseif ( $user_job_package_id && wc_paid_listings_package_is_valid( get_current_user_id(), $user_job_package_id ) ) {
			$job     = get_post( $job_id );

			// Give job the package attributes
			$package = wc_paid_listings_get_user_package( $user_job_package_id );
			update_post_meta( $job_id, '_job_duration', $package->get_duration() );
			update_post_meta( $job_id, '_featured', $package->is_featured() ? 1 : 0 );
			update_post_meta( $job_id, '_package_id', $package->get_product_id() );

			// Approve the job
			if ( in_array( $job->post_status, array( 'pending_payment', 'expired' ) ) ) {
				wc_paid_listings_approve_job_listing_with_package( $job->ID, get_current_user_id(), $user_job_package_id );
			}

			WP_Job_Manager_Form_Submit_Job::next_step();
		} else {
			WP_Job_Manager_Form_Submit_Job::add_error( __( 'Invalid Package', 'wp-job-manager-wc-paid-listings' ) );
			return;
		}
	}

	/**
	 * Handle the form when the preview page is submitted
	 */
	public function preview_handler() {
		if ( ! $_POST ) {
			return;
		}

		// Edit = show submit form again
		if ( ! empty( $_POST['edit_job'] ) ) {
			WP_Job_Manager_Form_Submit_Job::previous_step();
		}

		// Continue = Take Payment
		if ( ! empty( $_POST['continue'] ) ) {
			$job = get_post( WP_Job_Manager_Form_Submit_Job::get_job_id() );

			if ( $job->post_status == 'preview' ) {
				$update_job                = array();
				$update_job['ID']          = $job->ID;
				$update_job['post_status'] = 'pending_payment';
				wp_update_post( $update_job );
			}
			WP_Job_Manager_Form_Submit_Job::next_step();
		}
	}

	/**
	 * Handle the form when the preview page is submitted
	 */
	public function preview_resume_handler() {
		if ( ! $_POST ) {
			return;
		}

		// Edit = show submit form again
		if ( ! empty( $_POST['edit_resume'] ) ) {
			WP_Resume_Manager_Form_Submit_Resume::previous_step();
		}

		// Continue = Take Payment
		if ( ! empty( $_POST['continue'] ) ) {
			$resume = get_post( WP_Resume_Manager_Form_Submit_Resume::get_resume_id() );

			if ( $resume->post_status == 'preview' ) {
				$update_resume                = array();
				$update_resume['ID']          = $resume->ID;
				$update_resume['post_status'] = 'pending_payment';
				wp_update_post( $update_resume );
			}
			WP_Resume_Manager_Form_Submit_Resume::next_step();
		}
	}

	/**
	 * Choose a resume package
	 */
	public static function choose_resume_package() {
		$packages      = self::get_resume_packages();
		$user_packages = wc_paid_listings_get_user_packages( get_current_user_id(), 'resume' );
		?>
		<form method="post" id="job_package_selection">
			<div class="job_listing_packages_title">
				<input type="submit" name="continue" class="button" value="<?php echo apply_filters( 'submit_job_step_choose_package_submit_text', __( 'Submit &rarr;', 'wp-job-manager-wc-paid-listings' ) ); ?>" />
				<input type="hidden" name="resume_id" value="<?php echo esc_attr( WP_Resume_Manager_Form_Submit_Resume::get_resume_id() ); ?>" />
				<input type="hidden" name="job_id" value="<?php echo esc_attr( WP_Resume_Manager_Form_Submit_Resume::get_job_id() ); ?>" />
				<input type="hidden" name="step" value="<?php echo esc_attr( WP_Resume_Manager_Form_Submit_Resume::get_step() ); ?>" />
				<input type="hidden" name="resume_manager_form" value="<?php echo WP_Resume_Manager_Form_Submit_Resume::$form_name; ?>" />
				<h2><?php _e( 'Choose a package', 'wp-job-manager-wc-paid-listings' ); ?></h2>
			</div>
			<div class="job_listing_packages">
				<?php get_job_manager_template( 'resume-package-selection.php', array( 'packages' => $packages, 'user_packages' => $user_packages ), 'wc-paid-listings', JOB_MANAGER_WCPL_PLUGIN_DIR . '/templates/' ); ?>
			</div>
		</form>
		<?php
	}

	/**
	 * choose_resume_package_handler
	 */
	public static function choose_resume_package_handler() {
		global $woocommerce;

		// Get and validate package
		if ( is_numeric( $_POST['resume_package'] ) ) {
			$package_id = absint( $_POST['resume_package'] );
		} else {
			$user_resume_package_id = absint( substr( $_POST['resume_package'], 5 ) );
		}

		// Get job ID
		$resume_id = WP_Resume_Manager_Form_Submit_Resume::get_resume_id();

		if ( ! empty( $package_id ) ) {
			$package = get_product( $package_id );

			if ( ! $package->is_type( 'resume_package' ) && ! $package->is_type( 'resume_package_subscription' ) ) {
				WP_Resume_Manager_Form_Submit_Resume::add_error( __( 'Invalid Package', 'wp-job-manager-wc-paid-listings' ) );
				return;
			}

			// Give job the package attributes
			update_post_meta( $resume_id, '_featured', $package->is_featured() ? 1 : 0 );
			update_post_meta( $resume_id, '_resume_duration', $package->get_duration() );
			update_post_meta( $resume_id, '_package_id', $package->get_product_id() );

			// Add package to the cart
			$woocommerce->cart->add_to_cart( $package_id, 1, '', '', array(
				'resume_id' => $resume_id
			) );

			woocommerce_add_to_cart_message( $package_id );

			// Redirect to checkout page
			wp_redirect( get_permalink( woocommerce_get_page_id( 'checkout' ) ) );
			exit;
		} elseif ( $user_resume_package_id && wc_paid_listings_package_is_valid( get_current_user_id(), $user_resume_package_id ) ) {
			$resume = get_post( $resume_id );

			// Give job the package attributes
			$package = wc_paid_listings_get_user_package( $user_resume_package_id );
			update_post_meta( $resume_id, '_featured', $package->is_featured() ? 1 : 0 );
			update_post_meta( $resume_id, '_resume_duration', $package->get_duration() );
			update_post_meta( $resume_id, '_package_id', $package->get_product_id() );

			// Approve the job
			if ( in_array( $resume->post_status, array( 'pending_payment', 'expired' ) ) ) {
				wc_paid_listings_approve_resume_with_package( $resume->ID, get_current_user_id(), $user_resume_package_id );
			}

			WP_Resume_Manager_Form_Submit_Resume::next_step();
		} else {
			WP_Resume_Manager_Form_Submit_Resume::add_error( __( 'Invalid Package', 'wp-job-manager-wc-paid-listings' ) );
			return;
		}
	}
}

new WP_Job_Manager_WCPL_Forms();