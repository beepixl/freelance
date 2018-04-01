<?php

class Admin_Controller extends MY_Controller 
{
    var $modules_acl_config = array();
    
	public function __construct(){
		parent::__construct();
        $this->data['meta_title'] = 'Real estate script';
        $this->load->helper('form');
        $this->load->helper('text');
        $this->load->library('form_validation');
        $this->load->library('session');
        
        //Change language if user have defined
        $user_lang = $this->session->userdata('lang');
        if(!empty($user_lang))
        {
            $this->config->set_item('language', $user_lang);
        }
        else
        {
            // When user is not logged in
            $this->load->model('language_m');
            $lang_def_id = $this->language_m->get_default_id();
            $lang_def_name = $this->language_m->get_name($lang_def_id);
            $this->config->set_item('language', $lang_def_name);
        }
            
        $this->lang->load('calendar');
        $this->lang->load('backend_base');
        
        $this->load->model('user_m');
        $this->load->model('enquire_m');
        $this->load->model('language_m');
        $this->load->model('file_m');
        $this->load->model('repository_m');
        
        $this->form_validation->set_error_delimiters('<p class="label label-important validation">', '</p>');
        
        // Fetch settings
        $this->load->model('settings_m');
        $this->data['settings'] = $this->settings_m->get_fields();
        
        if(file_exists(FCPATH.'templates/'.$this->data['settings']['template'].'/config/'))
        {
            $this->config->add_config_path(FCPATH.'templates/'.$this->data['settings']['template'].'/');
            $this->config->load('template_config');
        }
        
        $this->data['admin_template'] = '';
        if(config_db_item('admin_template') !== FALSE)
            $this->data['admin_template'] = config_db_item('admin_template');
        
	    // Fetch 3 users
		$this->data['users_3'] = $this->user_m->get_by(NULL, FALSE, 3, 'id DESC');
        
	    // Fetch 3 enquire
		$this->data['enquire_3'] = $this->enquire_m->get_by(NULL, FALSE, 3, 'id DESC');
        
        $CI =& get_instance();
        $CI->form_languages = $this->language_m->get_form_dropdown('language', FALSE, FALSE);
        $CI->app_settings = $this->data['settings'];
        
        $CI->acl_config = array();
        $CI->acl_config['ADMIN'] = array('imageeditor', 'enquire', 'dashboard', 'estate', 'page', 'settings', 'slideshow', 'user', 'upload_field_icons',
                                         'upload', 'order', 'upload_slideshow', 'upload_estate', 'upload_user', 'upload_settings', 'news', 
                                         'ads', 'expert', 'companies', 'upload_ads', 'upload_field', 'showroom', 'upload_showroom', 'expert', 
                                         'booking', 'packages', 'tools', 'monetize', 'backup', 'reviews', 'emailfiles', 'forms', 'tcalendar',
                                         'savesearch', 'treefield', 'favorites', 'mapreport', 'benchmarktool', 'templates', 'templatefiles','addons', 'reports');
                                         
        $CI->acl_config['AGENT'] = array('imageeditor', 'enquire', 'enquire/edit', 'dashboard', 'estate/index', 'estate/edit', 'estate/delete', 'user/edit', 
                                         'upload', 'order', 'upload_estate', 'upload_field', 'upload_user', 'tcalendar',
                                         'packages/mypackage', 'packages/do_purchase_package', 'packages/cancel_payment');

        $CI->acl_config['AGENT_ADMIN'] = 
                                   array('imageeditor', 'enquire', 'dashboard', 'estate/index', 'estate/edit', 'estate/delete', 'user', 
                                         'upload', 'order', 'upload_estate', 'upload_field', 'upload_user', 
                                         'packages/mypackage', 'packages/do_purchase_package', 'packages/cancel_payment');
                                         
        $CI->acl_config['AGENT_COUNTY_AFFILIATE'] = 
                                   array('imageeditor', 'enquire', 'dashboard', 'estate/index', 'estate/edit', 'estate/delete', 'user/edit', 
                                         'upload', 'order', 'upload_estate', 'upload_field', 'upload_user', 'estate/contracted',
                                         'packages/affilatepackage', 'packages/do_purchase_affilate', 'packages/cancel_payment', 'estate/status', 'estate/statuses');
                             
        $CI->acl_config['AGENT_LIMITED'] = 
                                   array('imageeditor', 'enquire', 'enquire/edit', 'dashboard', 'estate/index', 'estate/edit', /*'estate/delete',*/ 'user/edit', 
                                         'upload', 'order', 'upload_estate', 'upload_field', 'upload_user', 
                                         'packages/mypackage', 'packages/do_purchase_package', 'packages/cancel_payment');
                             
                             
        $CI->acl_config['USER'] = array('imageeditor', 'order', 'upload_estate', 'upload', 'upload_field', 'upload_user');
        
        if(config_db_item('agent_reservation_rates_enabled') === TRUE)
        {
            $CI->acl_config['AGENT'][] = 'booking';
        }
        
        if(config_db_item('report_property_enabled') === TRUE)
        {
            $CI->acl_config['ADMIN'][] = 'reports';
        }
        
        //if(isset($this->modules_acl_config['ADMIN']))
        //$CI->acl_config['ADMIN'] = array_merge($CI->acl_config['ADMIN'], $this->modules_acl_config['ADMIN']);
        
        // Login check
        $exception_uris = array(
            'admin/user/login',
            //'admin/user/login_secret',
            'admin/user/logout',
            'admin/user/register',
            'admin/user/login',
            'admin/user/forgetpassword',
            'admin/user/resetpassword',
            'admin/user/verifyphone',
            'admin/user/verifyemail',
            'admin/user/logout',
            'admin/user/register'
        );
        
        $uri_string = uri_string();
        if(substr($uri_string, 0, 1) == '/')$uri_string = substr($uri_string, 1);
        
        $uri_string_3seg = $uri_string;
        $segs = $this->uri->segment_array();
        if(!empty($segs[1]))
            $uri_string_3seg = $segs[1];
        if(!empty($segs[2]))
            $uri_string_3seg.= '/'.$segs[2];
        if(!empty($segs[3]))
            $uri_string_3seg.= '/'.$segs[3];
        
        
        if(strpos($uri_string, 'admin/user/resetpassword') === FALSE)
        if(in_array($uri_string_3seg, $exception_uris) == FALSE)
        {
            if($this->user_m->loggedin() == FALSE)
            {
                redirect('admin/user/login', 'refresh');
            }
            else
            {
                // Check acl
                if(check_acl())
                {
                    
                }
                else
                {
                    $this->session->set_flashdata('error', 
                        lang('You have no permissions').': '.$this->uri->uri_string());
                    //redirect('admin/user/login_secret', 'refresh');
                    redirect('admin/user/login', 'refresh');
                }

            }
        }

	}
}