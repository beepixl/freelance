<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Astoundify_Job_Manager_Fields {

	/**
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Make sure only one instance is only running.
	 *
	 * @since Custom fields for WP Job Manager 1.0
	 *
	 * @param void
	 * @return object $instance The one true class instance.
	 */
	public static function instance() {
		if ( ! isset ( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Start things up.
	 *
	 * @since Custom fields for WP Job Manager 1.0
	 *
	 * @param void
	 * @return void
	 */
	public function __construct() {
		$this->setup_globals();
		$this->setup_actions();
	}

	/**
	 * Set some smart defaults to class variables.
	 *
	 * @since Custom fields for WP Job Manager 1.0
	 *
	 * @param void
	 * @return void
	 */
	private function setup_globals() {
		$this->file         = __FILE__;
		
		$this->basename     = plugin_basename( $this->file );
		$this->plugin_dir   = plugin_dir_path( $this->file );
		$this->plugin_url   = plugin_dir_url ( $this->file ); 
	}

	/**
	 * Hooks and filters.
	 *
	 * We need to hook into a couple of things:
	 * 1. Output fields on frontend, and save.
	 * 2. Output fields on backend, and save (done automatically).
	 *
	 * @since Custom fields for WP Job Manager 1.0
	 *
	 * @param void
	 * @return void
	 */
	private function setup_actions() {
		/**
		 * Filter the default fields that ship with WP Job Manager.
		 * The `form_fields` method is what we use to add our own custom fields.
		 */
		add_filter( 'submit_job_form_fields', array( $this, 'form_fields' ) );

		/**
		 * When WP Job Manager is saving all of the default field data, we need to also
		 * save our custom fields. The `update_job_data` callback is what does this.
		 */
		add_action( 'job_manager_update_job_data', array( $this, 'update_job_data' ), 10, 2 );

		/**
		 * Filter the default fields that are output in the WP admin when viewing a job listing.
		 * The `job_listing_data_fields` adds the same fields to the backend that we added to the front.
		 *
		 * We do not need to add an additional callback for saving the data, as this is done automatically.
		 */
		add_filter( 'job_manager_job_listing_data_fields', array( $this, 'job_listing_data_fields' ) );
	}

	/**
	 * Add fields to the submission form.
	 *
	 * Currently the fields must fall between two sections: "job" or "company". Until
	 * WP Job Manager filters the data that passes to the registration template, these are the
	 * only two sections we can manipulate.
	 *
	 * You may use a custom field type, but you will then need to filter the `job_manager_locate_template`
	 * to search in `/templates/form-fields/$type-field.php` in your theme or plugin.
	 *
	 * @since Custom fields for WP Job Manager 1.0
	 *
	 * @param array $fields The existing fields
	 * @return array $fields The modified fields
	 */
	function form_fields( $fields ) {

		$currency = of_get_option('job_currency');

		if($currency == "eur") {
			$currency = "<i class='icon-eur'></i>";
		}elseif($currency =="gbp") {
			$currency = "<i class='icon-gbp'></i>";
		}elseif($currency =="inr") {
			$currency = "<i class='icon-inr'></i>";
		}elseif($currency =="jpy") {
			$currency = "<i class='icon-jpy'></i>";
		}elseif($currency =="cny") {
			$currency = "<i class='icon-cny'></i>";
		}elseif($currency =="krw") {
			$currency = "<i class='icon-krw'></i>";
		}elseif($currency =="btc") {
			$currency = "<i class='icon-btc'></i>";
		}else{
			$currency = "<i class='icon-usd'></i>";
		}

		$fields[ 'company' ][ 'company_rate' ] = array(
			'label'       => __('Hourly Rate', 'babysitter') . ' <small>(in '. $currency . ' )</small>',    // The label for the field
			'type'        => 'text',           // file, job-description (tinymce), select, text
			'placeholder' => __('10-15 etc', 'babysitter'),     // Placeholder value
			'required'    => true,             // If the field is required to submit the form
			'priority'    => 2                 // Where should the field appear based on the others
		);

		$fields[ 'company' ][ 'company_gender' ] = array(
			'label'       => __('Gender', 'babysitter'),    	  // The label for the field
			'type'        => 'select',         // file, job-description (tinymce), select, text
			'placeholder' => '',     			  // Placeholder value
			'required'    => true,             // If the field is required to submit the form
			'priority'    => 2,                // Where should the field appear based on the others
			'options' => array(
				'female' => __('Female', 'babysitter'),
				'male' => __('Male', 'babysitter'))
		);

		$fields[ 'company' ][ 'company_age' ] = array(
			'label'       => __('Age', 'babysitter'),    	  	  // The label for the field
			'type'        => 'text',           // file, job-description (tinymce), select, text
			'placeholder' => '',     			  // Placeholder value
			'required'    => true,             // If the field is required to submit the form
			'priority'    => 2                 // Where should the field appear based on the others
		);

		$fields[ 'company' ][ 'company_exp' ] = array(
			'label'       => __('Experience', 'babysitter'),    	  	  // The label for the field
			'type'        => 'text',           // file, job-description (tinymce), select, text
			'placeholder' => '',     			  // Placeholder value
			'required'    => false,             // If the field is required to submit the form
			'priority'    => 2                 // Where should the field appear based on the others
		);

		$fields[ 'company' ][ 'company_smokes' ] = array(
			'label'       => __('Smokes', 'babysitter'),    	  	  // The label for the field
			'type'        => 'select',           // file, job-description (tinymce), select, text
			'placeholder' => '',     			  // Placeholder value
			'required'    => false,             // If the field is required to submit the form
			'priority'    => 3,                 // Where should the field appear based on the others
			'options' => array(
				'no' => __('No', 'babysitter'),
				'yes' => __('Yes', 'babysitter')				
			)
		);

		$fields[ 'company' ][ 'company_edu' ] = array(
			'label'       => __('Education', 'babysitter'),    	  	  // The label for the field
			'type'        => 'text',           // file, job-description (tinymce), select, text
			'placeholder' => '',     			  // Placeholder value
			'required'    => false,             // If the field is required to submit the form
			'priority'    => 3                 // Where should the field appear based on the others
		);

		$fields[ 'company' ][ 'company_lang' ] = array(
			'label'       => __('Languages', 'babysitter'),    	  	  // The label for the field
			'type'        => 'text',           // file, job-description (tinymce), select, text
			'placeholder' => __('English, German etc', 'babysitter'),     			  // Placeholder value
			'required'    => false,             // If the field is required to submit the form
			'priority'    => 3                 // Where should the field appear based on the others
		);

		// First Aid Training
		$fields[ 'company' ][ 'company_info_fat' ] = array(
			'label'       => __('First Aid Training', 'babysitter'),    	  	  // The label for the field
			'type'        => 'select',           // file, job-description (tinymce), select, text
			'placeholder' => '',     			  // Placeholder value
			'required'    => true,             // If the field is required to submit the form
			'priority'    => 4,                 // Where should the field appear based on the others
			'options' => array(
				'no'  => __('No', 'babysitter'),
				'yes' => __('Yes', 'babysitter')
			)
		);

		// CPR Certified
		$fields[ 'company' ][ 'company_info_cpr' ] = array(
			'label'       => 'CPR Certified',    	  	  // The label for the field
			'type'        => 'select',           // file, job-description (tinymce), select, text
			'placeholder' => '',     			  // Placeholder value
			'required'    => true,             // If the field is required to submit the form
			'priority'    => 4,                 // Where should the field appear based on the others
			'options' => array(
				'no'  => __('No', 'babysitter'),
				'yes' => __('Yes', 'babysitter')
			)
		);

		// TrustLine Certified
		$fields[ 'company' ][ 'company_info_trust' ] = array(
			'label'       => __('TrustLine Certified', 'babysitter'),    	  	  // The label for the field
			'type'        => 'select',           // file, job-description (tinymce), select, text
			'placeholder' => '',     			  // Placeholder value
			'required'    => true,             // If the field is required to submit the form
			'priority'    => 4,                 // Where should the field appear based on the others
			'options' => array(
				'no'  => __('No', 'babysitter'),
				'yes' => __('Yes', 'babysitter')
			)
		);

		// Doula
		$fields[ 'company' ][ 'company_info_doula' ] = array(
			'label'       => __('Doula', 'babysitter'),    	  	  // The label for the field
			'type'        => 'select',           // file, job-description (tinymce), select, text
			'placeholder' => '',     			  // Placeholder value
			'required'    => true,             // If the field is required to submit the form
			'priority'    => 4,                 // Where should the field appear based on the others
			'options' => array(
				'no'  => __('No', 'babysitter'),
				'yes' => __('Yes', 'babysitter')
			)
		);

		// Has Transportation
		$fields[ 'company' ][ 'company_info_trans' ] = array(
			'label'       => __('Has Transportation', 'babysitter'),    	  	  // The label for the field
			'type'        => 'select',           // file, job-description (tinymce), select, text
			'placeholder' => '',     			  // Placeholder value
			'required'    => true,             // If the field is required to submit the form
			'priority'    => 4,                 // Where should the field appear based on the others
			'options' => array(
				'no'  => __('No', 'babysitter'),
				'yes' => __('Yes', 'babysitter')
			)
		);

		// Will travel 50 mi
		$fields[ 'company' ][ 'company_info_travel' ] = array(
			'label'       => __('Will travel 50 mi', 'babysitter'),    	  	  // The label for the field
			'type'        => 'select',           // file, job-description (tinymce), select, text
			'placeholder' => '',     			  // Placeholder value
			'required'    => true,             // If the field is required to submit the form
			'priority'    => 4,                 // Where should the field appear based on the others
			'options' => array(
				'no'  => __('No', 'babysitter'),
				'yes' => __('Yes', 'babysitter')
			)
		);

		// Special Needs Care
		$fields[ 'company' ][ 'company_info_care' ] = array(
			'label'       => __('Special Needs Care', 'babysitter'),    	  	  // The label for the field
			'type'        => 'select',           // file, job-description (tinymce), select, text
			'placeholder' => '',     			  // Placeholder value
			'required'    => true,             // If the field is required to submit the form
			'priority'    => 4,                 // Where should the field appear based on the others
			'options' => array(
				'no'  => __('No', 'babysitter'),
				'yes' => __('Yes', 'babysitter')
			)
		);

		// Comfortable with pets
		$fields[ 'company' ][ 'company_info_pets' ] = array(
			'label'       => __('Comfortable with pets', 'babysitter'),    	  	  // The label for the field
			'type'        => 'select',           // file, job-description (tinymce), select, text
			'placeholder' => '',     			  // Placeholder value
			'required'    => true,             // If the field is required to submit the form
			'priority'    => 4,                 // Where should the field appear based on the others
			'options' => array(
				'no'  => __('No', 'babysitter'),
				'yes' => __('Yes', 'babysitter')
			)
		);

		// Certified Nursing Assistant
		$fields[ 'company' ][ 'company_info_nurse' ] = array(
			'label'       => __('Certified Nursing Assistant', 'babysitter'),    	  	  // The label for the field
			'type'        => 'select',           // file, job-description (tinymce), select, text
			'placeholder' => '',     			  // Placeholder value
			'required'    => true,             // If the field is required to submit the form
			'priority'    => 4,                 // Where should the field appear based on the others
			'options' => array(
				'no'  => __('No', 'babysitter'),
				'yes' => __('Yes', 'babysitter')
			)
		);

		// Live-in nanny
		$fields[ 'company' ][ 'company_info_live' ] = array(
			'label'       => __('Live-in nanny', 'babysitter'),    	  	  // The label for the field
			'type'        => 'select',           // file, job-description (tinymce), select, text
			'placeholder' => '',     			  // Placeholder value
			'required'    => true,             // If the field is required to submit the form
			'priority'    => 4,                 // Where should the field appear based on the others
			'options' => array(
				'no'  => __('No', 'babysitter'),
				'yes' => __('Yes', 'babysitter')
			)
		);

		/**
		 * Repeat this for any additional fields.
		 */

		return $fields;
	}

	/**
	 * When the form is submitted, update the data.
	 *
	 * All data is stored in the $values variable that is in the same
	 * format as the fields array.
	 *
	 * @since Custom fields for WP Job Manager 1.0
	 *
	 * @param int $job_id The ID of the job being submitted.
	 * @param array $values The values of each field.
	 * @return void
	 */
	function update_job_data( $job_id, $values ) {
		/** Get the values of our fields. */

		$rate = isset ( $values[ 'company' ][ 'company_rate' ] ) ? sanitize_text_field( $values[ 'company' ][ 'company_rate' ] ) : null;

		$gender = isset ( $values[ 'company' ][ 'company_gender' ] ) ? sanitize_text_field( $values[ 'company' ][ 'company_gender' ] ) : null;

		$age = isset ( $values[ 'company' ][ 'company_age' ] ) ? sanitize_text_field( $values[ 'company' ][ 'company_age' ] ) : null;

		$experience = isset ( $values[ 'company' ][ 'company_exp' ] ) ? sanitize_text_field( $values[ 'company' ][ 'company_exp' ] ) : null;

		$smokes = isset ( $values[ 'company' ][ 'company_smokes' ] ) ? sanitize_text_field( $values[ 'company' ][ 'company_smokes' ] ) : null;

		$education = isset ( $values[ 'company' ][ 'company_edu' ] ) ? sanitize_text_field( $values[ 'company' ][ 'company_edu' ] ) : null;

		$languages = isset ( $values[ 'company' ][ 'company_lang' ] ) ? sanitize_text_field( $values[ 'company' ][ 'company_lang' ] ) : null;

		$info_fat = isset ( $values[ 'company' ][ 'company_info_fat' ] ) ? sanitize_text_field( $values[ 'company' ][ 'company_info_fat' ] ) : null;

		$info_cpr = isset ( $values[ 'company' ][ 'company_info_cpr' ] ) ? sanitize_text_field( $values[ 'company' ][ 'company_info_cpr' ] ) : null;

		$info_trust = isset ( $values[ 'company' ][ 'company_info_trust' ] ) ? sanitize_text_field( $values[ 'company' ][ 'company_info_trust' ] ) : null;

		$info_doula = isset ( $values[ 'company' ][ 'company_info_doula' ] ) ? sanitize_text_field( $values[ 'company' ][ 'company_info_doula' ] ) : null;

		$info_trans = isset ( $values[ 'company' ][ 'company_info_trans' ] ) ? sanitize_text_field( $values[ 'company' ][ 'company_info_trans' ] ) : null;

		$info_travel = isset ( $values[ 'company' ][ 'company_info_travel' ] ) ? sanitize_text_field( $values[ 'company' ][ 'company_info_travel' ] ) : null;

		$info_care = isset ( $values[ 'company' ][ 'company_info_care' ] ) ? sanitize_text_field( $values[ 'company' ][ 'company_info_care' ] ) : null;

		$info_pets = isset ( $values[ 'company' ][ 'company_info_pets' ] ) ? sanitize_text_field( $values[ 'company' ][ 'company_info_pets' ] ) : null;

		$info_nurse = isset ( $values[ 'company' ][ 'company_info_nurse' ] ) ? sanitize_text_field( $values[ 'company' ][ 'company_info_nurse' ] ) : null;

		$info_live = isset ( $values[ 'company' ][ 'company_info_live' ] ) ? sanitize_text_field( $values[ 'company' ][ 'company_info_live' ] ) : null;

		/** By using an underscore in the meta key name, we can prevent this from being shown in the Custom Fields metabox. */

		if ( $rate ) {
			update_post_meta( $job_id, '_company_rate', $rate );
		}

		if ( $gender ) {
			update_post_meta( $job_id, '_company_gender', $gender );
		}

		if ( $age ) {
			update_post_meta( $job_id, '_company_age', $age );
		}

		if ( $experience ) {
			update_post_meta( $job_id, '_company_exp', $experience );
		}

		if ( $smokes ) {
			update_post_meta( $job_id, '_company_smokes', $smokes );
		}

		if ( $education ) {
			update_post_meta( $job_id, '_company_edu', $education );
		}

		if ( $languages ) {
			update_post_meta( $job_id, '_company_lang', $languages );
		}

		if ( $info_fat ) {
			update_post_meta( $job_id, '_company_info_fat', $info_fat );
		}

		if ( $info_cpr ) {
			update_post_meta( $job_id, '_company_info_cpr', $info_cpr );
		}

		if ( $info_trust ) {
			update_post_meta( $job_id, '_company_info_trust', $info_trust );
		}

		if ( $info_doula ) {
			update_post_meta( $job_id, '_company_info_doula', $info_doula );
		}

		if ( $info_trans ) {
			update_post_meta( $job_id, '_company_info_trans', $info_trans );
		}

		if ( $info_travel ) {
			update_post_meta( $job_id, '_company_info_travel', $info_travel );
		}

		if ( $info_care ) {
			update_post_meta( $job_id, '_company_info_care', $info_care );
		}

		if ( $info_pets ) {
			update_post_meta( $job_id, '_company_info_pets', $info_pets );
		}

		if ( $info_nurse ) {
			update_post_meta( $job_id, '_company_info_nurse', $info_nurse );
		}

		if ( $info_live ) {
			update_post_meta( $job_id, '_company_info_live', $info_live );
		}

		/**
		 * Repeat this process for any additional fields. Always escape your data.
		 */
	}

	/**
	 * Add fields to the admin write panel.
	 *
	 * There is a slight disconnect between the frontend and backend at the moment.
	 * The frontend allows for select boxes, but there is no way to output those in
	 * the admin panel at the moment.
	 *
	 * @since Custom fields for WP Job Manager 1.0
	 *
	 * @param array $fields The existing fields
	 * @return array $fields The modified fields
	 */
	function job_listing_data_fields( $fields ) {
		/**
		 * Add the field we added to the frontend, using the meta key as the name of the
		 * field. We do not need to separate these fields into "job" or "company" as they
		 * are all output in the same spot.
		 */

		$fields[ '_company_rate' ] = array(
			'label'       => __('Hourly Rate', 'babysitter'),    // The field label
			'placeholder' => '10-15 etc',     // The default value when adding via backend.
			'type'        => 'text'            // text, textarea, checkbox, file
		);

		$fields[ '_company_gender' ] = array(
			'label'       => __('Gender', 'babysitter'),    // The field label
			'placeholder' => __('Female', 'babysitter'),     // The default value when adding via backend.
			'type'        => 'text'            // text, textarea, checkbox, file
		);

		$fields[ '_company_age' ] = array(
			'label'       => __('Age', 'babysitter'),    // The field label
			'placeholder' => '',     // The default value when adding via backend.
			'type'        => 'text'            // text, textarea, checkbox, file
		);

		$fields[ '_company_exp' ] = array(
			'label'       => __('Experience', 'babysitter'),    // The field label
			'placeholder' => '',     // The default value when adding via backend.
			'type'        => 'text'            // text, textarea, checkbox, file
		);

		$fields[ '_company_smokes' ] = array(
			'label'       => __('Smokes', 'babysitter'),    // The field label
			'placeholder' => '',     // The default value when adding via backend.
			'type'        => 'checkbox'            // text, textarea, checkbox, file
		);

		$fields[ '_company_edu' ] = array(
			'label'       => __('Education', 'babysitter'),    // The field label
			'placeholder' => __('College etc', 'babysitter'),     // The default value when adding via backend.
			'type'        => 'text'            // text, textarea, checkbox, file
		);

		$fields[ '_company_lang' ] = array(
			'label'       => __('Languages', 'babysitter'),    // The field label
			'placeholder' => __('English, German etc', 'babysitter'),     // The default value when adding via backend.
			'type'        => 'text'            // text, textarea, checkbox, file
		);

		$fields[ '_company_info_fat' ] = array(
			'label'       => __('First Aid Training', 'babysitter'),    // The field label
			'placeholder' => '',     // The default value when adding via backend.
			'type'        => 'select',
			'options' => array(
				'yes' => __('Yes', 'babysitter'),
				'no'  => __('No', 'babysitter')
			)
		);

		$fields[ '_company_info_cpr' ] = array(
			'label'       => __('CPR Certified', 'babysitter'),    // The field label
			'placeholder' => '',     // The default value when adding via backend.
			'type'        => 'select',
			'options' => array(
				'yes' => __('Yes', 'babysitter'),
				'no'  => __('No', 'babysitter')
			)
		);

		$fields[ '_company_info_trust' ] = array(
			'label'       => __('TrustLine Certified', 'babysitter'),    // The field label
			'placeholder' => '',     // The default value when adding via backend.
			'type'        => 'select',
			'options' => array(
				'yes' => __('Yes', 'babysitter'),
				'no'  => __('No', 'babysitter')
			)
		);

		$fields[ '_company_info_doula' ] = array(
			'label'       => __('Doula', 'babysitter'),    // The field label
			'placeholder' => '',     // The default value when adding via backend.
			'type'        => 'select',
			'options' => array(
				'yes' => __('Yes', 'babysitter'),
				'no'  => __('No', 'babysitter')
			)
		);

		$fields[ '_company_info_trans' ] = array(
			'label'       => __('Has Transportation', 'babysitter'),    // The field label
			'placeholder' => '',     // The default value when adding via backend.
			'type'        => 'select',
			'options' => array(
				'yes' => __('Yes', 'babysitter'),
				'no'  => __('No', 'babysitter')
			)
		);

		$fields[ '_company_info_travel' ] = array(
			'label'       => __('Will travel 50 mi', 'babysitter'),    // The field label
			'placeholder' => '',     // The default value when adding via backend.
			'type'        => 'select',
			'options' => array(
				'yes' => __('Yes', 'babysitter'),
				'no'  => __('No', 'babysitter')
			)
		);

		$fields[ '_company_info_care' ] = array(
			'label'       => __('Special Needs Care', 'babysitter'),    // The field label
			'placeholder' => '',     // The default value when adding via backend.
			'type'        => 'select',
			'options' => array(
				'yes' => __('Yes', 'babysitter'),
				'no'  => __('No', 'babysitter')
			)
		);

		$fields[ '_company_info_pets' ] = array(
			'label'       => __('Comfortable with pets', 'babysitter'),    // The field label
			'placeholder' => '',     // The default value when adding via backend.
			'type'        => 'select',
			'options' => array(
				'yes' => __('Yes', 'babysitter'),
				'no'  => __('No', 'babysitter')
			)
		);

		$fields[ '_company_info_nurse' ] = array(
			'label'       => __('Certified Nursing Assistant', 'babysitter'),    // The field label
			'placeholder' => '',     // The default value when adding via backend.
			'type'        => 'select',
			'options' => array(
				'yes' => __('Yes', 'babysitter'),
				'no'  => __('No', 'babysitter')
			)
		);

		$fields[ '_company_info_live' ] = array(
			'label'       => __('Live-in nanny', 'babysitter'),    // The field label
			'placeholder' => '',     // The default value when adding via backend.
			'type'        => 'select',
			'options' => array(
				'yes' => __('Yes', 'babysitter'),
				'no'  => __('No', 'babysitter')
			)
		);

		/**
		 * Repeat this for any additional fields.
		 */

		return $fields;
	}
}
add_action( 'init', array( 'Astoundify_Job_Manager_Fields', 'instance' ) );