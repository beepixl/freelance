<?php

class Frontend_Controller extends MY_Controller 
{
	public function __construct(){
		parent::__construct();
        
        if(config_item('installed') == false)
        {
            redirect('configurator');
            exit();
        }

        if(ENVIRONMENT == 'development' || 
          md5($this->input->get('profiler'))=='b78ee15cb3ca6531667d47af5cdc61a1' ||
          config_item('enable_benchmark_tools') == TRUE)
        {
            error_reporting(E_ALL | E_STRICT);
            $this->output->enable_profiler(TRUE);
        }
        
        $this->data['listing_uri'] = config_item('listing_uri');
        if(empty($this->data['listing_uri']))$this->data['listing_uri'] = 'property';
        
        /* Load Helpers */
        $this->load->helper('text');    
        
        /* Load libraries */
        $this->load->library('parser');
        
        $this->load->library('form_validation');
        
        $this->load->library('session');
        $this->load->library('pagination');
        
        /* Load models */
        $this->load->model('language_m');
        $this->load->model('page_m');
        $this->load->model('file_m');
        $this->load->model('user_m');
        $this->load->model('repository_m');
        $this->load->model('estate_m');
        $this->load->model('option_m');
        $this->load->model('settings_m');
        $this->load->model('slideshow_m');
        
        $this->form_validation->set_error_delimiters('<p class="alert alert-error">', '</p>');
        
        $CI =& get_instance();
        $CI->form_languages = $this->language_m->get_form_dropdown('language', FALSE, FALSE);
        
        // Fetch settings
        $this->load->model('settings_m');
        $this->data['settings'] = $this->settings_m->get_fields();
        
        foreach($this->data['settings'] as $key=>$value)
        {
            if($key == 'address')
            {
                $value = str_replace('"', '\\"', $value);
            }
            
            $this->data['settings_'.$key] = $value;
            
            $this->data['has_settings_'.$key] = array();
            if(!empty($value))
            {
                $this->data['has_settings_'.$key][] = array('count'=>'1');
            }
        }
        
        if(!isset($this->data['settings_template']))
        {
            $error = 'Parameter not defined: settings_template<br />';
            $error.= 'Posssible issue: <strong>Wrong database configuration</strong>'.'<br />';
            show_error($error);
        }
        
        if(file_exists(FCPATH.'templates/'.$this->data['settings_template'].'/config/'))
        {
            $this->config->add_config_path(FCPATH.'templates/'.$this->data['settings_template'].'/');
            $this->config->load('template_config');
        }
        
        if(config_db_item('frontend_disabled') === TRUE)
        {
            redirect('admin/user/login');
        }
        
        /* [START] Fetch logo URL */
        $this->data['website_logo_url'] = 'assets/img/logo.png';
        if(isset($this->data['settings']['website_logo']))
        {
            if(is_numeric($this->data['settings']['website_logo']))
            {
                $files_logo = $this->file_m->get_by(array('repository_id' => $this->data['settings']['website_logo']), TRUE);
                if( is_object($files_logo) && file_exists(FCPATH.'files/thumbnail/'.$files_logo->filename))
                {
                    $this->data['website_logo_url'] = base_url('files/'.$files_logo->filename);
                }
            }
        }
        /* [END] Fetch logo URL */
         
        /* [START] Fetch favicon URL */
        $this->data['website_favicon_url'] = 'assets/img/favicon.png';
        if(isset($this->data['settings']['website_favicon']))
        {
            if(is_numeric($this->data['settings']['website_favicon']))
            {
                $files_logo = $this->file_m->get_by(array('repository_id' => $this->data['settings']['website_favicon']), TRUE);
                if( is_object($files_logo) && file_exists(FCPATH.'files/thumbnail/'.$files_logo->filename))
                {
                    $this->data['website_favicon_url'] = base_url('files/'.$files_logo->filename);
                }
            }
        }
        /* [END] Fetch favicon URL */
        
       // Extra JS features enabled
        $this->data['has_extra_js'] = array();
        if($this->uri->segment(2) == 'editproperty' ||
           $this->uri->segment(2) == 'myprofile' )
            $this->data['has_extra_js'][] = array('count'=>'1');
        
        if(config_item('enable_lang_autodetection') == TRUE)
        if($this->uri->uri_string() == '' && count($this->language_m->db_languages_code) > 0)
        {
            $lang_autodetect = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            
            if(strlen($lang_autodetect)>0 && isset($this->language_m->db_languages_code[$lang_autodetect]))
            {
                if($this->language_m->db_languages_code_obj[$lang_autodetect]->is_frontend)
                    redirect($lang_autodetect);
            }
        }
        
        // Get page data
        $this->data['lang_code'] = (string) $this->uri->segment(1);
        $this->data['page_id'] = (string) $this->uri->segment(2);
        $this->data['page_slug'] = (string) $this->uri->segment(3);
        $this->data['pagination_offset'] = 0;

        if(config_db_item('multi_domains_enabled') === TRUE && empty($this->data['lang_code']))
        {
            foreach($this->language_m->db_languages_code_obj as $lang_obj)
            {
                if(!empty($lang_obj->domain) && substr_count($lang_obj->domain, $_SERVER['HTTP_HOST']) > 0)
                {
                    $this->data['lang_code'] = $lang_obj->code;
                    break;
                }
            }
        }
        
        // If frontend
        if($this->data['page_id'] == 'typeahead')
        {
            $this->data['page_slug'] = '';
            $this->data['lang_code'] = (string) $this->uri->segment(3);
            $this->data['page_id'] = (string) $this->uri->segment(4);
            $this->data['pagination_offset'] = (string) $this->uri->segment(5);
        }
        else if($this->data['page_id'] == 'ajax')
        {
            $this->data['page_slug'] = '';
            $this->data['lang_code'] = (string) $this->uri->segment(3);
            $this->data['page_id'] = (string) $this->uri->segment(4);
            $this->data['pagination_offset'] = (string) $this->uri->segment(5);
            
            if($this->uri->segment(1) == 'profile')
            {
                $this->data['page_id'] = '';
            }
        }
        else if($this->data['page_id'] == 'showroom' || $this->data['lang_code'] == 'showroom')
        {
            $this->data['page_slug'] = '';
            $this->data['lang_code'] = (string) $this->uri->segment(3);
            $this->data['page_id'] = '';
        }
        else if($this->data['page_id'] == 'expert' || $this->data['lang_code'] == 'expert')
        {
            $this->data['page_slug'] = '';
            $this->data['lang_code'] = (string) $this->uri->segment(3);
            $this->data['page_id'] = '';
        }
        else if($this->data['page_id'] == $this->data['listing_uri'] || 
                $this->data['lang_code'] == $this->data['listing_uri'] ||
                $this->data['page_id'] == 'profile' || 
                $this->data['lang_code'] == 'profile')
        {
            $this->data['page_slug'] = '';
            $this->data['lang_code'] = (string) $this->uri->segment(3);
            $this->data['page_id'] = '';
        }
        else if($this->data['page_id'] == 'login' || 
                $this->data['page_id'] == 'myproperties' ||
                $this->data['page_id'] == 'myprofile' ||
                $this->data['page_id'] == 'myrates' ||
                $this->data['page_id'] == 'editrate' ||
                $this->data['page_id'] == 'deleterate' ||
                $this->data['page_id'] == 'editproperty' ||
                $this->data['page_id'] == 'deleteproperty' ||
                $this->data['page_id'] == 'report_deleteproperty' ||
                $this->data['page_id'] == 'logout' ||
                $this->data['page_id'] == 'listproperty' ||
                $this->data['page_id'] == 'myreservations' ||
                $this->data['page_id'] == 'deletereservation' ||
                $this->data['page_id'] == 'viewreservation' ||
                $this->data['page_id'] == 'login_book'||
                $this->data['page_id'] == 'do_purchase' ||
                $this->data['page_id'] == 'notify_payment' || 
                $this->data['page_id'] == 'cancel_payment' ||
                $this->data['page_id'] == 'do_purchase_package' ||
                $this->data['page_id'] == 'do_purchase_featured' ||
                //$this->data['page_id'] == 'loginfacebook' ||
                $this->data['page_id'] == 'do_purchase_activation')
        {
            $this->data['page_slug'] = '';
            $this->data['lang_code'] = (string) $this->uri->segment(3);
            $this->data['page_id'] = '';
        }
        else if($this->data['page_id'] == 'maskingsubmit')
        {
            $this->data['page_slug'] = '';
            $this->data['lang_code'] = (string) $this->uri->segment(3);
            $this->data['page_id'] = '';
        } else if($this->data['page_id'] == 'reportsubmit')
        {
            $this->data['page_slug'] = '';
            $this->data['lang_code'] = (string) $this->uri->segment(3);
            $this->data['page_id'] = '';
        }
        else if($this->data['lang_code'] == 'treefield')
        {
            $this->data['page_slug'] = '';
            $this->data['lang_code'] = (string) $this->uri->segment(2);
            $this->data['page_id'] = '';
        }
        else if($this->data['page_id'] == 'propertycompare' || 
                $this->data['lang_code'] == 'propertycompare')
        {
            $this->data['page_slug'] = '';
            $this->data['lang_code'] = (string) $this->uri->segment(3);
            $this->data['page_id'] = '';
        }
        
        if(empty($this->data['page_id']))
        {
            // Get first menu item page
            $first_page = $this->page_m->get_first();
            
            if(!empty($first_page))
                $this->data['page_id'] = $first_page->id;
        }
        else if(!is_numeric($this->data['page_id']))
        {
            $this->data['page_id'] = $this->page_m->get_id_by_name ($this->data['page_id']);
        }
        
        if(empty($this->data['lang_code']))
        {
            $this->data['lang_code'] = $this->language_m->get_default();
        }
        
        $this->data['lang_id'] = $this->language_m->get_id($this->data['lang_code']);
        
        if(empty($this->data['lang_id']))
            show_404(current_url());

        $this->data['page_current_url'] = site_url($this->uri->uri_string());
        
        // Check if is it RTL
        $this->data['is_rtl'] = array();
        $lang_data = $this->language_m->get($this->data['lang_id']);
        $rtl_test = $this->input->get('test', TRUE);
        if($lang_data->is_rtl == 1 || $rtl_test == 'rtl')
        {
            $this->data['is_rtl'][]= array('count'=>'1');
        }
        
        // Facebook lang code add
        $this->data['lang_facebook_code'] = $lang_data->facebook_lang_code;
        
        // Fetch menu
        $this->temp_data['menu'] = $this->page_m->get_nested($this->data['lang_id']);

        // Fetch current page
        $this->temp_data['page'] = $this->page_m->get_lang($this->data['page_id']);
//            echo '<pre>';
//            var_dump($this->data['page_id'], $this->temp_data['page']);
//            echo '</pre>';
//            exit();
        if(!empty($this->temp_data['page']) && !empty($this->data['page_id'])){
            $this->data['page_navigation_title'] = $this->temp_data['page']->{'navigation_title_'.$this->data['lang_id']};
            $this->data['page_title'] = $this->temp_data['page']->{'title_'.$this->data['lang_id']};
            $this->data['page_body']  = $this->temp_data['page']->{'body_'.$this->data['lang_id']};
            $this->data['page_description']  = character_limiter(strip_tags($this->temp_data['page']->{'description_'.$this->data['lang_id']}), 160);
            $this->data['page_keywords']  = $this->temp_data['page']->{'keywords_'.$this->data['lang_id']};
            $this->data['subtemplate_header'] = $this->temp_data['page']->template_header;
            $this->data['subtemplate_footer'] = $this->temp_data['page']->template_footer;
            

        }
        else
        {  
            if(empty($this->data['page_id']))
                show_error(lang_check('No pages found, please add pages via administration'));
            
            show_404(current_url());
        }
                
        // URL-s
        $this->data['ajax_load_url'] = site_url('frontend/ajax/'.$this->data['lang_code'].'/'.$this->data['page_id']);
        $this->data['ajax_showroom_load_url'] = site_url('showroom/ajax/'.$this->data['lang_code'].'/'.$this->data['page_id'].'/'.$this->input->get('cat', TRUE));
        $this->data['ajax_expert_load_url'] = site_url('expert/ajax/'.$this->data['lang_code'].'/'.$this->data['page_id'].'/'.$this->input->get('cat', TRUE));
        $this->data['ajax_news_load_url'] = site_url('news/ajax/'.$this->data['lang_code'].'/'.$this->data['page_id'].'/'.$this->input->get('cat', TRUE));
        
        $this->data['typeahead_url'] = site_url('frontend/typeahead/'.$this->data['lang_code'].'/'.$this->data['page_id']);
        
        // Load custom translations
        $this->config->set_item('language', $this->language_m->get_name($this->data['lang_code']));
        //$this->lang->load('frontend_base');
        
        if(file_exists(FCPATH.'templates/'.$this->data['settings_template'].'/language/'.$this->language_m->get_name($this->data['lang_code'])))
        {
            $this->lang->load('frontend_template', '', FALSE, TRUE, FCPATH.'templates/'.$this->data['settings_template'].'/');
        }
        else
        {
            $this->config->set_item('language', 'english');
            $this->lang->load('frontend_template', '', FALSE, TRUE, FCPATH.'templates/'.$this->data['settings_template'].'/');
            //$this->config->set_item('language', $this->language_m->get_name($this->data['lang_code']));
        }
        
        if(!file_exists(APPPATH.'language/'.$this->language_m->get_name($this->data['lang_code']).'/form_validation_lang.php'))
        {
            $this->config->set_item('language', 'english');
        }
        
        // Define language for template
        $lang = $this->lang->get_array();
        foreach($lang as $key=>$row)
        {
            $this->data['lang_'.$key] = $row;
        }
        
        // Color definition for demo purposes
        $this->data['color'] = '';
        $this->data['color_path'] = '';
        $this->data['has_color'] = array();
        $this->data['has_color_picker'] = array();
        
        $color = $this->input->get_post('color', TRUE);
        $color_set_session = false;
        
        if(empty($color))
        {
            $color = $this->session->userdata('color');
        }
        else
        {
            $color_set_session = true;
        }

        if(config_db_item('color') !== FALSE && empty($color))
        {
            $color = config_db_item('color');
        }
        
        if($this->config->item('color_picker') !== FALSE)
        {
            if($this->config->item('color_picker') == TRUE)
            {
                $this->data['has_color_picker'][] = array('selected_color'=>$color);
            }
        }
        
        if( file_exists(FCPATH.'templates/'.$this->data['settings_template'].'/assets/css/styles_'.$color.'.css') &&
            file_exists(FCPATH.'templates/'.$this->data['settings_template'].'/assets/img/markers/'.$color))
        {
            $this->data['color'] = $color;
            $this->data['color_path'] = $color.'/';
            $this->data['has_color'][] = array('color'=>$color);
            
            if($color_set_session)
                $this->session->set_userdata('color', $color);
        }

        // homepage_url
        $this->data['homepage_url'] = base_url('');
        $this->data['homepage_url_lang'] = site_url($this->data['lang_code']);
        
        if($this->data['lang_code'] == $this->language_m->get_default())
        {
            $this->data['homepage_url_lang'] = base_url('');
        }
        
        /* Check login */
        $this->data['is_logged_user'] = array();
        $this->data['is_logged_other'] = array();
        $this->data['not_logged'][] = array('count'=>'1');
        if($this->user_m->loggedin() == TRUE)
        {
            if($this->session->userdata('type') == 'USER')
            {
                $this->data['is_logged_user'][] = array('count'=>'1');
                $this->data['not_logged'] = array();
            }
            else
            {
                $this->data['is_logged_other'][] = array('count'=>'1');
                $this->data['not_logged'] = array();
            }
            
            $this->data['loged_user'] = $this->session->userdata('name_surname');
        }
        
        $this->data['logout_url'] = site_url('frontend/logout/'.$this->data['lang_code']);
        $this->data['login_url'] = site_url('admin/dashboard');

        $this->data['front_login_url'] = site_url('frontend/login/'.$this->data['lang_code']);
        $this->data['myproperties_url'] = site_url('frontend/myproperties/'.$this->data['lang_code']);
        $this->data['myprofile_url'] = site_url('frontend/myprofile/'.$this->data['lang_code']);
        $this->data['myreservations_url'] = site_url('frontend/myreservations/'.$this->data['lang_code']);
        $this->data['myresearch_url'] = site_url('fresearch/myresearch/'.$this->data['lang_code']);
        $this->data['api_private_url'] = site_url('privateapi');
        $this->data['myrates_url'] = site_url('frontend/myrates/'.$this->data['lang_code']);
        $this->data['myfavorites_url'] = site_url('ffavorites/myfavorites/'.$this->data['lang_code']);
        $this->data['mymessages_url'] = site_url('fmessages/mymessages/'.$this->data['lang_code']);
        
        if(config_item('enable_restricted_mode') === TRUE)
        {
            if(count($this->data['not_logged']) > 0 && $this->uri->segment(2) != 'login')
            {
                redirect($this->data['front_login_url']);
            }
        }
        
        // [agent_direct feature]
        if(config_db_item('agent_profile_direct') === TRUE)
        {
            $agent_direct = $this->session->userdata('agent_direct');
            $last_activity = $this->session->userdata('last_activity');

            if(empty($agent_direct) && (string) $this->uri->segment(1) != 'profile')
            {
                $this->session->set_userdata('agent_direct', 'not_direct');
            }
            
        }
        // [/agent_direct feature]
                
        $this->data['search_query'] = $this->input->get('search');

        if(empty($this->data['search_query']))
            $this->data['search_query'] = '';
        
        // Get slideshow
        $rep_slideshow_images = $this->slideshow_m->get_repository_images();
        
        $this->data['slideshow_images'] = array();
        foreach($rep_slideshow_images as $key=>$file)
        {
            $slideshow_image = array();
            $slideshow_image['num'] = $key;
            $slideshow_image['url'] = base_url('files/'.$file->filename);
            $slideshow_image['thumb_url'] = base_url('files/thumbnail/'.$file->filename);
            $slideshow_image['filename'] = url_title_cro($file->filename, ' ');
            $slideshow_image['title'] = $file->title;
            $slideshow_image['link'] = $file->link;
            $slideshow_image['first_active'] = '';
            if($key==0)$slideshow_image['first_active'] = 'active';
            
            $this->data['slideshow_images'][] = $slideshow_image;
        }
        // End Get slideshow
        
        /* [CAPTCHA Helper] */
        if(config_item('captcha_disabled') === FALSE)
        {
            $this->load->helper('captcha');
            $captcha_hash = substr(md5(rand(0, 999).time()), 0, 5);
            $captcha_hash_old = $this->session->userdata('captcha_hash');
            if(isset($_POST['captcha_hash']))
                $captcha_hash_old = $_POST['captcha_hash'];
            
            $this->data['captcha_hash_old'] = $captcha_hash_old;
            $this->session->set_userdata('captcha_hash', $captcha_hash);

            $vals = array(
                'word' => substr(md5($captcha_hash.config_item('encryption_key')), 0, 5),
                'img_path' => FCPATH.'files/captcha/',
                'img_url' => base_url('files/captcha').'/',
                'font_path' => FCPATH.'admin-assets/font/verdana.ttf',
                'img_width' => 120,
                'img_height' => 35,
                'expiration' => 7200
                );

            $this->data['captcha'] = create_captcha($vals);
            $this->data['captcha_hash'] = $captcha_hash;
        }
        /* [/CAPTCHA Helper] */

	}
    
    public function generate_results_array(&$results_obj, &$results_array, &$options_name)
    {
        foreach($results_obj as $key=>$estate_arr)
        {
            $estate = array();
            $estate['id'] = $estate_arr->id;
            $estate['gps'] = $estate_arr->gps;
            $estate['address'] = $estate_arr->address;
            $estate['date'] = $estate_arr->date;
            $estate['repository_id'] = $estate_arr->repository_id;
            $estate['is_featured'] = $estate_arr->is_featured;
            $estate['counter_views'] = $estate_arr->counter_views;
            $estate['estate_data_id'] = $estate_arr->id;
            $estate['icons'] = array();
            
            $json_obj = json_decode($estate_arr->json_object);
            
            foreach($options_name as $key2=>$row2)
            {
                $key1 = $row2->option_id;
                $estate['has_option_'.$key1] = array();
                if(isset($json_obj->{"field_$key1"}))
                {
                    $row1 = $json_obj->{"field_$key1"};
                    if(substr($row1, -2) == ' -')$row1=substr($row1, 0, -2);
                    $estate['option_'.$key1] = $row1;
                    $estate['option_chlimit_'.$key1] = character_limiter(strip_tags($row1), 80);
                    $estate['option_icon_'.$key1] = '';
                    
                    if(!empty($row1))
                    {
                        $estate['has_option_'.$key1][] = array('count'=>count($row1));
                        
                        if(file_exists(FCPATH.'templates/'.$this->data['settings_template'].
                            '/assets/img/icons/option_id/'.$key1.'.png'))
                        {
                            $estate['option_icon_'.$key1] = '<img class="results-icon" src="assets/img/icons/option_id/'.$key1.'.png" alt="'.$row1.'"/>';;
                            $estate['icons'][]['icon']= $estate['option_icon_'.$key1];
                        }
                    }
                }
            }
            
            // [START] custom price field
            $estate['custom_price'] = '';
            if(!empty($estate['option_36']))
                $estate['custom_price'].=$this->data['options_prefix_36'].$estate['option_36'].$this->data['options_suffix_36'];
            if(!empty($estate['option_37']))
            {
                if(!empty($estate['custom_price']))
                    $estate['custom_price'].=' / ';
                $estate['custom_price'].=$this->data['options_prefix_37'].$estate['option_37'].$this->data['options_suffix_37'];
            }
                
            if(empty($estate['option_37']) && !empty($estate['option_56']))
            {
                if(!empty($estate['custom_price']))
                    $estate['custom_price'].=' / ';
                $estate['custom_price'].=$this->data['options_prefix_56'].$estate['option_56'].$this->data['options_suffix_56'];
            }
            // [END] custom price field
            $estate['icon'] = 'assets/img/markers/'.$this->data['color_path'].'marker_blue.png';
            if(isset($estate['option_6']))
            {
                if($estate['option_6'] != '' && $estate['option_6'] != 'empty')
                {
                    if(file_exists(FCPATH.'templates/'.$this->data['settings_template'].
                                   '/assets/img/markers/'.$this->data['color_path'].$estate['option_6'].'.png'))
                    $estate['icon'] = 'assets/img/markers/'.$this->data['color_path'].$estate['option_6'].'.png';
                    elseif (file_exists(FCPATH.'templates/'.$this->data['settings_template'].
                                   '/assets/img/markers/'.$estate['option_6'].'.png'))
                    $estate['icon'] = 'assets/img/markers/'.$estate['option_6'].'.png';
                }
            }
            
            // [fetch marker by type uploaded image]
            
            // Check if images are uploaded
            if(config_db_item('field_file_upload_enabled') === TRUE && !empty($this->data['options_obj_2']->image_gallery))
            {
                // Get selected type index
                $image_index = array_search($estate['option_2'], $this->data['options_values_arr_2']);
                
                // Explode images
                $images = explode(',', $this->data['options_obj_2']->image_gallery);

                // set if image exists
                if(!empty($images[$image_index]))
                {
                    $image_filename = 'files/'.$images[$image_index];
                    
                    if(file_exists(FCPATH.$image_filename))
                        $estate['icon'] = base_url($image_filename);
                }
            }

            // [/fetch marker by type uploaded image]
            
            // Url to preview
            if(isset($json_obj->field_10))
            {
                $estate['url'] = slug_url($this->data['listing_uri'].'/'.$estate_arr->id.'/'.$this->data['lang_code'].'/'.url_title_cro($json_obj->field_10));
            }
            else
            {
                $estate['url'] = slug_url($this->data['listing_uri'].'/'.$estate_arr->id.'/'.$this->data['lang_code']);
            }
            
            // Thumbnail
            if(!empty($estate_arr->image_filename) and file_exists(FCPATH.'files/thumbnail/'.$estate_arr->image_filename))
            {
                $estate['thumbnail_url'] = base_url('files/thumbnail/'.$estate_arr->image_filename);
            }
            else
            {
                $estate['thumbnail_url'] = 'assets/img/no_image.jpg';
            }
            
            // [agent second image]
            if(isset($estate_arr->agent_rep_id))
            if(isset($this->data['images_'.$estate_arr->agent_rep_id]))
            {
                if(isset($this->data['images_'.$estate_arr['agent_rep_id']][1]))
                $estate['agent_sec_img_url'] = $this->data['images_'.$estate_arr['agent_rep_id']][1]->thumbnail_url;
            }
            
            $estate['has_agent_sec_img'] = array();
            if(isset($estate['agent_sec_img_url']))
                $estate['has_agent_sec_img'][] = array('count'=>'1');
            // [/agent second image]
            
            $results_array[] = $estate;
        }
    }
    
}