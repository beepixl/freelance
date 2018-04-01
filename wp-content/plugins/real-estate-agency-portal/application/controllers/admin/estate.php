<?php

class Estate extends Admin_Controller
{
    
	public function __construct()
    {
		parent::__construct();
        $this->load->model('estate_m');
        $this->load->model('option_m');
        $this->load->model('file_m');
        
        // Get language for content id to show in administration
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
	}
    
    public function index($pagination_offset=0)
	{
	    $this->load->library('pagination');
        $where = array();
        $search_array = array();
        
        $where['language_id']  = $this->data['content_language_id'];
        
        // [AGENT_COUNTY_AFFILIATE]
        if($this->session->userdata('type') == 'AGENT_COUNTY_AFFILIATE')
        {
            $this->load->model('affilatepackages_m');
            $user_id = $this->session->userdata('id');

            $related_tree_paths = $this->affilatepackages_m->get_user_packages($user_id, $this->data['content_language_id']);
            
            $gen_where = array();
            if(count($related_tree_paths) > 0)
            {
                foreach($related_tree_paths as $row_t)
                {
                    $gen_where[] = 'json_object LIKE \'%'.$row_t->value_path.'%\'';
                }
                
//                $where = 'language_id = '.$this->data['content_language_id'].' AND status != "HOLD_ADMIN" AND ('.
//                         implode(' OR ', $gen_where).
//                         ')';
                
                $where['(status IS NULL OR status != "CONTRACT")'] = NULL;
                $where['(status IS NULL OR status != "HOLD_ADMIN")'] = NULL;
                $where['('.implode(' OR ', $gen_where).')'] = NULL;
            }
            else
            {
                $where['user_id'] = $this->session->userdata('id');
                $where['(status IS NULL OR status != "CONTRACT")'] = NULL;
                $where['(status IS NULL OR status != "HOLD_ADMIN")'] = NULL;
            }
        }
        // [/AGENT_COUNTY_AFFILIATE]

        prepare_search_query_GET(array('field_2', 'field_4'), array('property.id', 'address', 'search_values'));
        
        // Fetch all estates
        $config['total_rows'] = $this->estate_m->count_get_by($where, false, NULL, 'property.id DESC', 
                                                              NULL, $search_array, NULL, TRUE);
        
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['available_agent'] = $this->user_m->get_form_dropdown('name_surname'/*, array('type'=>'AGENT')*/);

        $config['base_url'] = site_url('admin/estate/index');
        $config['uri_segment'] = 4;
        //$config['total_rows'] = count($this->data['estates']);
        $config['per_page'] = 20;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['additional_query_string'] = regenerate_query_string();
        
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
        
        prepare_search_query_GET(array('field_2', 'field_4'), array('property.id', 'address', 'search_values'));
        
        $this->data['estates'] = $this->estate_m->get_by($where, false, $config['per_page'], 'property.id DESC', 
                                               $pagination_offset, $search_array, NULL, TRUE);
        
        // Load view
		$this->data['subview'] = 'admin/estate/index';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function contracted($pagination_offset=0)
	{
	    $this->load->library('pagination');
        $where = array();
        $search_array = array();
        
        $where['language_id']  = $this->data['content_language_id'];
        
        // [AGENT_COUNTY_AFFILIATE]
        if($this->session->userdata('type') == 'AGENT_COUNTY_AFFILIATE')
        {
            $this->load->model('affilatepackages_m');
            $user_id = $this->session->userdata('id');

            $related_tree_paths = $this->affilatepackages_m->get_user_packages($user_id, $this->data['content_language_id']);
            
            $gen_where = array();
            if(count($related_tree_paths) > 0)
            {
                foreach($related_tree_paths as $row_t)
                {
                    $gen_where[] = 'json_object LIKE \'%'.$row_t->value_path.'%\'';
                }
                
//                $where = 'language_id = '.$this->data['content_language_id'].' AND status != "HOLD_ADMIN" AND ('.
//                         implode(' OR ', $gen_where).
//                         ')';
                
                $where['(status = "CONTRACT")'] = NULL;
                $where['('.implode(' OR ', $gen_where).')'] = NULL;
            }
            else
            {
                $where['user_id'] = $this->session->userdata('id');
                $where['(status = "CONTRACT")'] = NULL;
            }
        }
        // [/AGENT_COUNTY_AFFILIATE]

        prepare_search_query_GET(array('field_2', 'field_4'), array('property.id', 'address', 'search_values'));
        
        // Fetch all estates
        $config['total_rows'] = $this->estate_m->count_get_by($where, false, NULL, 'property.id DESC', 
                                                              NULL, $search_array, NULL, TRUE);
        
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['available_agent'] = $this->user_m->get_form_dropdown('name_surname'/*, array('type'=>'AGENT')*/);

        $config['base_url'] = site_url('admin/estate/contracted');
        $config['uri_segment'] = 4;
        //$config['total_rows'] = count($this->data['estates']);
        $config['per_page'] = 20;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['additional_query_string'] = regenerate_query_string();
        
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
        
        prepare_search_query_GET(array('field_2', 'field_4'), array('property.id', 'address', 'search_values'));
        
        $this->data['estates'] = $this->estate_m->get_by($where, false, $config['per_page'], 'property.id DESC', 
                                               $pagination_offset, $search_array, NULL, TRUE);

        // Load view
		$this->data['subview'] = 'admin/estate/contracted';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function statuses($pagination_offset=0)
	{
	    $this->load->library('pagination');
        $where = array();
        $search_array = array();
        
        $where['language_id']  = $this->data['content_language_id'];
        
        // [AGENT_COUNTY_AFFILIATE]
        if($this->session->userdata('type') == 'AGENT_COUNTY_AFFILIATE')
        {
            $this->load->model('affilatepackages_m');
            $user_id = $this->session->userdata('id');

            $related_tree_paths = $this->affilatepackages_m->get_user_packages($user_id, $this->data['content_language_id']);
            
            $gen_where = array();
            if(count($related_tree_paths) > 0)
            {
                foreach($related_tree_paths as $row_t)
                {
                    $gen_where[] = 'json_object LIKE \'%'.$row_t->value_path.'%\'';
                }
                
//                $where = 'language_id = '.$this->data['content_language_id'].' AND status != "HOLD_ADMIN" AND ('.
//                         implode(' OR ', $gen_where).
//                         ')';
                
                $where['('.implode(' OR ', $gen_where).')'] = NULL;
            }
            else
            {
                $where['user_id'] = $this->session->userdata('id');
            }
        }
        // [/AGENT_COUNTY_AFFILIATE]

        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['available_agent'] = $this->user_m->get_form_dropdown('name_surname'/*, array('type'=>'AGENT')*/);
        
        
        
        $where['(status = "" OR status is NULL)'] = NULL;
        $this->data['estates_pending'] = $this->estate_m->get_by($where, false, NULL, 'property.id DESC', 
                                               $pagination_offset, $search_array, NULL, TRUE);
        unset($where['(status = "" OR status is NULL)']);
        
        
        $where['(status = "HOLD")'] = NULL;
        $this->data['estates_hold'] = $this->estate_m->get_by($where, false, NULL, 'property.id DESC', 
                                               $pagination_offset, $search_array, NULL, TRUE);
        unset($where['(status = "HOLD")']);
                                               
        $where['(status = "CONTRACT")'] = NULL;
        $this->data['estates_contracted'] = $this->estate_m->get_by($where, false, NULL, 'property.id DESC', 
                                               $pagination_offset, $search_array, NULL, TRUE);
        
        
        
        // Load view
		$this->data['subview'] = 'admin/estate/statuses';
        $this->load->view('admin/_layout_main', $this->data);
	}
       
    public function edit($id = NULL)
	{
        // If limit reached, error/warning!
        $this->load->model('packages_m');
        $this->load->model('treefield_m');
        
        $user = $this->user_m->get($this->session->userdata('id'));
        
        if(file_exists(APPPATH.'controllers/admin/packages.php'))
        if($user->package_id > 0 && $this->session->userdata('type') == 'AGENT')
        {
            $package = $this->packages_m->get($user->package_id);
            $listing_num = $this->packages_m->get_curr_listings(array('user_id'=>$user->id));
            
            if(config_item('enable_num_amenities_listing') == true)
                $this->data['package_num_amenities_limit'] = $package->num_amenities_limit;
            
            if(isset($listing_num[$user->id]))
            {
                if($listing_num[$user->id] >= $package->num_listing_limit && !$id)
                {
                    $this->session->set_flashdata('error', 
                            lang_check('Num listings max. reached for your package'));
                    redirect('admin/estate');
                    exit();
                }
                else if($package->package_days > 0 && strtotime($user->package_last_payment)<=time())
                {
                    $this->session->set_flashdata('error', 
                            lang_check('Date for your package expired, please extend'));
                    redirect('admin/estate');
                    exit();
                }
            }
        }
       
	    // Fetch a page or set a new one
	    if($id)
        {
            $this->data['estate'] = $this->estate_m->get_dynamic($id);
            
            if(count($this->data['estate']) == 0)
            {
                $this->data['errors'][] = 'Estate could not be found';
                redirect('admin/estate');
            }
            
            //Check if user have permissions
            if($this->session->userdata('type') != 'ADMIN' && $this->session->userdata('type') != 'AGENT_ADMIN')
            {
                if($this->data['estate']->agent == $this->session->userdata('id'))
                {
                    
                }
                else if($this->session->userdata('type') == 'AGENT_COUNTY_AFFILIATE')
                {
                    // [AGENT_COUNTY_AFFILIATE]
                    
                    $user = $this->user_m->get($this->session->userdata('id'));
                    if(strpos($this->data['estate']->search_values, $user->county_affiliate_values) == FALSE)
                    {
                        redirect('admin/estate');
                    }
            
                    // [/AGENT_COUNTY_AFFILIATE]
                }
                else
                {
                    redirect('admin/estate');
                }
            }
            
            // Fetch file repository
            $repository_id = $this->data['estate']->repository_id;
            if(empty($repository_id))
            {
                // Create repository
                $repository_id = $this->repository_m->save(array('name'=>'estate_m'));
                
                // Update page with new repository_id
                $this->estate_m->save(array('repository_id'=>$repository_id), $this->data['estate']->id);
            }
        }
        else
        {
            $this->data['estate'] = $this->estate_m->get_new();
        }
        
		// Pages for dropdown
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        
        // Get available agents
        $this->data['available_agent'] = $this->user_m->get_form_dropdown('name_surname', array('type'=>'AGENT'));
        
        $this->data['available_agent'][''] = lang_check('Current user');
        
        // Get all options
        foreach($this->option_m->languages as $key=>$val){
            $this->data['options_lang'][$key] = $this->option_m->get_lang(NULL, FALSE, $key);
        }
        $this->data['options'] = $this->option_m->get_lang(NULL, FALSE, $this->data['content_language_id']);
        
        // Id's for key adjustments 
        // TODO: better solution needed, this is just hotfix
        $options = $this->data['options'];
        $this->data['options'] = array();
        foreach($options as $option_key=>$option_row)
        {
            $this->data['options'][$option_row->option_id] = $option_row;
        }
        
        // For other langs
        foreach($this->option_m->languages as $key=>$val){
            $options_key = $this->data['options_lang'][$key];
            $this->data['options_lang'][$key] = array();
            foreach($options_key as $option_key=>$option_row)
            {
                $this->data['options_lang'][$key][$option_row->option_id] = $option_row;
            }
        }
        // End id's for key adjustments
        
        
        $options_data = array();
        foreach($this->option_m->get() as $key=>$val)
        {
            $options_data[$val->id][$val->type] = 'true';
        }
        
        // Add rules for dynamic options
        $rules_dynamic = array();
        foreach($this->option_m->languages as $key_lang=>$val_lang){
            foreach($this->data['options'] as $key_option=>$val_option){
                $rules_dynamic['option'.$val_option->id.'_'.$key_lang] = 
                    array('field'=>'option'.$val_option->id.'_'.$key_lang, 'label'=>$val_option->option, 'rules'=>'trim');
                //if($id == NULL)$this->data['estate']->{'option'.$val_option->id.'_'.$key_lang} = '';
                if(!isset($this->data['estate']))$this->data['estate']->{'option'.$val_option->id.'_'.$key_lang} = '';
            }
            
            if(config_db_item('slug_enabled') === TRUE)
            {
                $rules_dynamic['slug_'.$key_lang] = 
                    array('field'=>'slug_'.$key_lang, 'label'=>'lang:URI slug', 'rules'=>'trim');
            }
        }
        
        // Fetch all files by repository_id
        $files = $this->file_m->get();
        foreach($files as $key=>$file)
        {
            $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/_blank.png');
            $file->zoom_enabled = false;
            $file->download_url = base_url('files/'.$file->filename);
            $file->delete_url = site_url_q('files/upload/rep_'.$file->repository_id, '_method=DELETE&amp;file='.rawurlencode($file->filename));

            if(file_exists(FCPATH.'/files/thumbnail/'.$file->filename))
            {
                $file->thumbnail_url = base_url('files/thumbnail/'.$file->filename);
                $file->zoom_enabled = true;
            }
            else if(file_exists(FCPATH.'admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png'))
            {
                $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png');
            }
            
            $this->data['files'][$file->repository_id][] = $file;
        }
        
        // Set up the form
        $rules = $this->estate_m->rules;
        $this->form_validation->set_rules(array_merge($rules, $rules_dynamic));

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Data editing disabled in demo'));
                redirect('admin/estate/edit/'.$id);
                exit();
            }
            
            $data = $this->estate_m->array_from_post(array('gps', 'date', 'address', 'is_featured', 'is_activated', 'id_transitions'));
            $dynamic_data = $this->estate_m->array_from_post(array_keys($rules_dynamic));
            
            // AGENT_LIMITED don't have permission to change this fields...
            if($this->session->userdata('type') == 'AGENT_LIMITED')
            {
                unset($data['is_activated'],
                      $data['is_featured']
                );
                
                $data['is_activated'] = 0;
            }
            
            if(empty($data['id_transitions']))
            {
                $data['id_transitions'] = NULL;
            }
            
            $data['search_values'] = $data['address'];
            foreach($dynamic_data as $key=>$val)
            {
                $pos = strpos($key, '_');
                $option_id = substr($key, 6, $pos-6);
                $language_id = substr($key, $pos+1);
                
                if(!isset($options_data[$option_id]['TEXTAREA']) && !isset($options_data[$option_id]['CHECKBOX'])){
                    $data['search_values'].=' '.$val;
                }
                
                // TODO: test check, values for each language for selected checkbox
                if(isset($options_data[$option_id]['CHECKBOX'])){
                    if($val == 'true')
                    {
                        foreach($this->option_m->languages as $key_lang=>$val_lang){
                            foreach($this->data['options_lang'][$key_lang] as $key_option=>$val_option){
                                if($val_option->id == $option_id && $language_id == $key_lang)
                                {
                                    $data['search_values'].=' true'.$val_option->option;
                                }
                            }
                        }
                    }
                }
            }
            
            if($this->data['estate']->agent != $this->session->userdata('id'))
            {
                // If admin/agent/... modify property from other user, date_modified is not changed
            }
            else
            {
                $data['date_modified'] = date('Y-m-d H:i:s');
            }
            
            /* [Auto move gps coordinates few meters away if same exists in database] */
            $estate_same_coordinates = $this->estate_m->get_by(array('gps'=>$data['gps']), TRUE);

            if(is_object($estate_same_coordinates) && !empty($estate_same_coordinates))
            {
                $same_gps = explode(', ', $estate_same_coordinates->gps);
                // $same_gps[0] && $same_gps[1] available
                $rand_lat = rand(1, 9);
                $rand_lan = rand(1, 9);
                
                $data['gps'] = ($same_gps[0]+0.00001*$rand_lat).', '.($same_gps[1]+0.00001*$rand_lan);
            }
            /* [/Auto move gps coordinates few meters away if same exists in database] */
            
            $insert_id = $this->estate_m->save($data, $id);
            
            // add insert to search_values
            if(is_numeric($insert_id))
            {
                $update_data = array();
                $update_data['search_values'] = 'id: '.$id.$data['search_values'];
                
                $this->estate_m->save($update_data, $insert_id);
            }
            
            if( $this->session->userdata('type') != 'ADMIN' && 
                $this->session->userdata('type') != 'AGENT_ADMIN' && 
                $this->session->userdata('type') != 'AGENT_COUNTY_AFFILIATE')
            {
                $data['agent'] = $this->session->userdata('id');
            }
            else
            {
                $data['agent'] = $this->input->post('agent');
            }           
            
            // Save dynamic options
            $dynamic_data['agent'] = $data['agent'];
            $this->estate_m->save_dynamic($dynamic_data, $insert_id);

            $this->load->library('sitemap');
            $this->sitemap->generate_sitemap();
            
            /* [Email sending] */
            
            if(ENVIRONMENT != 'development')
            if( $this->session->userdata('type') == 'AGENT_LIMITED' && 
                $this->data['settings']['email_alert'] == 1 )
            {
                // Send email alert to contact address
                $this->load->library('email');
                $config_mail['mailtype'] = 'html';
                $this->email->initialize($config_mail);
                
                $this->email->from($this->data['settings']['noreply'], lang_check('Web page not-activated property'));
                $this->email->to($this->data['settings']['email']);
                $this->email->subject(lang_check('Web page not-activated property'));
                
                $data_m = array();
                $data_m['subject'] = lang_check('New not-activated property from user');
                $data_m['name_surname'] = $this->session->userdata('username');
                $data_m['link'] = '<a href="'.site_url('admin/estate/edit/'.$insert_id).'">'.lang_check('Property edit link').'</a>';
                $message = $this->load->view('email/waiting_for_activation', array('data'=>$data_m), TRUE);
                
                $this->email->message($message);
                $this->email->send();
            }

            if(ENVIRONMENT != 'development' && count($this->data['estate']))
            if(isset($data['is_activated']) && $data['is_activated'] == 1 && $this->data['estate']->is_activated == 0)
            {
                // get user details
                $user = $this->user_m->get($this->data['estate']->agent);
                
                if($user->type == 'USER')
                {
                    // Send email alert to contact address
                    $this->load->library('email');
                    
                    $config_mail['mailtype'] = 'html';
                    $this->email->initialize($config_mail);
                    
                    $this->email->from($this->data['settings_noreply'], lang_check('Web page activated property'));
                    $this->email->to($user->mail);
                    $this->email->subject(lang_check('Web page activated property'));
                    
                    $data_m = array();
                    $data_m['subject'] = lang_check('New activated property');
                    $data_m['link'] = '<a href="'.site_url('property/'.$insert_id.'/').'">'.lang_check('Property preview link').'</a>';
                    $message = $this->load->view('email/waiting_for_activation_user', array('data'=>$data_m), TRUE);
                    
                    $this->email->message($message);
                    $this->email->send();
                }
            }
            
            /* [/Email sending] */
            
            $this->session->set_flashdata('message', 
                    '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');
            
            redirect('admin/estate/edit/'.$insert_id);
        }
        
        // Load the view
		$this->data['subview'] = 'admin/estate/edit';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function status($id, $status, $redirect_uri = '')
    {
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/estate/');
            exit();
        }
        
        $this->data['estate'] = $this->estate_m->get_dynamic($id);
        
        if(count($this->data['estate']) == 0)
        {
            $this->data['errors'][] = 'Estate could not be found';
            redirect('admin/estate/');
        }
        
        if($this->session->userdata('type') == 'AGENT_COUNTY_AFFILIATE')
        {
            // [AGENT_COUNTY_AFFILIATE]
            $this->load->model('affilatepackages_m');
            $user_id = $this->session->userdata('id');
            $language_id = $this->data['content_language_id'];
            
            //get_related_affilate($default_lang_id, $county_affiliate_values)
            if(!$this->affilatepackages_m->check_user_affilate($user_id, $id, $language_id))
            {
                redirect('admin/estate');
            }
    
            // [/AGENT_COUNTY_AFFILIATE]
        }
        else if($this->session->userdata('type') != 'ADMIN')
        {
            redirect('admin/estate');
        }
        
        $update_data = array();
        $update_data['status']      = $status;
        $update_data['date_status'] = date('Y-m-d H:i:s');
        
        if($status == 'APPROVE')
        {
            $update_data['is_activated']    = 1;
            $update_data['date_repost']     = date('Y-m-d H:i:s');
            $update_data['date_modified']   = date('Y-m-d H:i:s');
            $update_data['date_alert']      = date('Y-m-d H:i:s');
        }
        
        $this->estate_m->save($update_data, $id);
        
        $insert_id = $id;
        
        if($status == 'DECLINE')
        {
            /*
                Declined
                
                *User receives generic email with the reasons why most postings get declined.
            */
            $lang_code = $this->language_m->get_default();
            
            // Send message to user
            $this->load->library('email');
            
            $config_mail['mailtype'] = 'html';
            $this->email->initialize($config_mail);
            
            $this->email->from($this->data['settings_noreply'], lang_check('Your property declined!'));
            $this->email->to($user->mail);
            $this->email->subject(lang_check('Your property declined!'));
            
            $data_m = array();
            $data_m['subject'] = lang_check('Your property declined!');
            $data_m['link'] = '<a href="'.site_url('frontend/editproperty/'.$lang_code.'/'.$insert_id).'">'.lang_check('Property edit link').'</a>';
            $message = $this->load->view('email/declined_property', array('data'=>$data_m), TRUE);
            
            $this->email->message($message);
            $this->email->send();
        }
        elseif($status == 'HOLD')
        {
            /*
                Hold
                
                *User receives notification that someone from our team has interest in his property.
            */
            
            $lang_code = $this->language_m->get_default();
            
            // Send message to user
            $this->load->library('email');
            
            $config_mail['mailtype'] = 'html';
            $this->email->initialize($config_mail);
            
            $this->email->from($this->data['settings_noreply'], lang_check('Your property on hold!'));
            $this->email->to($user->mail);
            $this->email->subject(lang_check('Your property on hold!'));
            
            $data_m = array();
            $data_m['subject'] = lang_check('Your property on hold!');
            $message = $this->load->view('email/hold_property', array('data'=>$data_m), TRUE);

            $this->email->message($message);
            $this->email->send();
        }
        elseif($status == 'CONTRACT')
        {
            /*
                Contract
                
                *User: Receives notification letting him know that our team member has decided to contract his property.
            */

            $lang_code = $this->language_m->get_default();
            
            // Send message to user
            $this->load->library('email');
            
            $config_mail['mailtype'] = 'html';
            $this->email->initialize($config_mail);
            
            $this->email->from($this->data['settings_noreply'], lang_check('Your property purchased!'));
            $this->email->to($user->mail);
            $this->email->subject(lang_check('Your property purchased!'));
            
            $data_m = array();
            $data_m['subject'] = lang_check('Your property purchased!');
            $message = $this->load->view('email/contract_property', array('data'=>$data_m), TRUE);

            $this->email->message($message);
            $this->email->send();
        }
        elseif($status == 'APPROVE')
        {
            /*
                Property approved
                
                *User receives notification letting him know his property has been posted on the website.
            */

            // get user details
            $user = $this->user_m->get($this->data['estate']->agent);
            
            if($user->type == 'USER')
            {
                // Send email alert to contact address
                $this->load->library('email');
                
                $config_mail['mailtype'] = 'html';
                $this->email->initialize($config_mail);
                
                $this->email->from($this->data['settings_noreply'], lang_check('Web page activated property'));
                $this->email->to($user->mail);
                $this->email->subject(lang_check('Web page activated property'));
                
                $data_m = array();
                $data_m['subject'] = lang_check('New activated property');
                $data_m['link'] = '<a href="'.site_url('property/'.$insert_id.'/').'">'.lang_check('Property preview link').'</a>';
                $message = $this->load->view('email/waiting_for_activation_user', array('data'=>$data_m), TRUE);
                
                $this->email->message($message);
                $this->email->send();
            }
        }
        
        redirect('admin/estate/'.$redirect_uri);
    }
    
    public function delete($id, $redirect = TRUE)
	{
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/estate');
            exit();
        }
        
        //Check if user have permissions
        if($this->session->userdata('type') != 'ADMIN' && $this->session->userdata('type') != 'AGENT_ADMIN')
        {
            $this->data['estate'] = $this->estate_m->get_dynamic($id);
            
            if(count($this->data['estate']) > 0)
            {
                if($this->data['estate']->agent == $this->session->userdata('id'))
                {
                    
                }
                else if($this->session->userdata('type') == 'AGENT_COUNTY_AFFILIATE')
                {
                    // [AGENT_COUNTY_AFFILIATE]
                    $this->load->model('affilatepackages_m');
                    $user_id = $this->session->userdata('id');
                    $language_id = $this->data['content_language_id'];
                    
                    //get_related_affilate($default_lang_id, $county_affiliate_values)
                    if(!$this->affilatepackages_m->check_user_affilate($user_id, $id, $language_id))
                    {
                        redirect('admin/estate');
                    }
            
                    // [/AGENT_COUNTY_AFFILIATE]
                }
                else
                {
                    if(!$redirect)return;
                    redirect('admin/estate');
                }
            }
        }
       
		$this->estate_m->delete($id);
        
        if(!$redirect)return;
        redirect('admin/estate');
	}
    
    public function delete_multiple()
    {
        // var_dump($_POST);
        // array(1) { ["delete_multiple"]=> array(3) { [0]=> string(2) "31" [1]=> string(2) "30" [2]=> string(2) "29" } } 
        
        foreach($_POST["delete_multiple"] as $property_id)
        {
            if(is_numeric($property_id))
                $this->delete($property_id, FALSE);
        }
        
        redirect('admin/estate');
    }
    
    public function options()
	{
        // Fetch all estates
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');
        $this->data['options_no_parents'] = $this->option_m->get_no_parents($this->data['content_language_id']);
        $this->data['options'] = $this->option_m->get_lang(NULL, FALSE, $this->data['content_language_id']);
        $this->data['options_nested'] = $this->option_m->get_nested($this->data['content_language_id']);
        
        //var_dump($this->data['options_nested']);
        
        // Load view
		$this->data['subview'] = 'admin/estate/options';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function numeric_field_range($id)
    {
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/estate/edit_option/'.$id);
            exit();
        }

        if($this->option_m->numeric_field_range($id) === TRUE)
        {
            $this->session->set_flashdata('message', 
                    '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');
        }
        else
        {
            $this->session->set_flashdata('message', 
                    '<p class="label label-danger validation">'.lang_check('Query failed, probably permissions on db missing').'</p>');
        }

        redirect('admin/estate/edit_option/'.$id);
    }
    
    public function dependent_fields()
	{
        
        $this->load->model('dependentfield_m');
        
        $this->data['listings'] = $this->dependentfield_m->get_detailed($this->data['content_language_id']);
        
        // Load view
		$this->data['subview'] = 'admin/estate/dependent_fields';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
    public function edit_dependent_field($id = NULL)
    {
        $this->load->model('dependentfield_m');

        // Fetch a user or set a new one
	    if($id)
        {
            $this->data['item'] = $this->dependentfield_m->get($id);

            if(count($this->data['item']) == 0)
            {
                $this->data['errors'][] = lang_check('Could not be found');
                redirect('admin/estate/dependent_fields');
            }
            
            //Check if user have permissions
            if($this->session->userdata('type') != 'ADMIN')
            {
                redirect('admin/estate/dependent_fields');
            }

        }
        else
        {
            $this->data['item'] = $this->dependentfield_m->get_new();
        }
        
        if(!empty($this->data['item']->field_id))
        {
            $field_data = $this->option_m->get($this->data['item']->field_id);
        }
        
        // Load additional resources
        $this->data['available_fields'] = $this->dependentfield_m->get_available_fields($this->data['content_language_id']);
        $this->data['available_indexes'] = $this->dependentfield_m->get_field_values($this->data['content_language_id'], $this->data['item']->field_id, lang_check('Not selected'));
        $this->data['fields_under_selected'] = $this->dependentfield_m->get_fields_under($field_data->order, $this->data['content_language_id']);
        
        // Fetch hidden fields
        if(!empty($this->data['item']->hidden_fields_list))
        {
            foreach(explode(',', $this->data['item']->hidden_fields_list) as $f_id)
            {
                $this->data['item']->{'field_'.$f_id} = '1';
            }
        }
        
        // Form configuration
        $rules = $this->dependentfield_m->rules;
        $this->form_validation->set_rules($rules);

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Data editing disabled in demo'));
                redirect('admin/estate/edit_dependent_field/'.$id);
                exit();
            }
            
            $data = $this->dependentfield_m->array_from_rules($rules);
            
            // [Hidden fields]
            $hidden_fields_list = array();
            foreach($_POST as $key=>$val)
            {
                $exp = explode('_', $key);
                if(count($exp) == 2)
                {
                    if($exp[0] == 'field' && is_numeric($exp[1]))
                    {
                        $hidden_fields_list[] = $exp[1];
                    }
                }
                
            }
            $data['hidden_fields_list'] = implode(',', $hidden_fields_list);
            
            // [/Hidden fields]
            
            $id = $this->dependentfield_m->save($data, $id);
            
            $this->session->set_flashdata('message', 
                    '<p class="label label-success validation">'.lang_check('Changes saved').$message_mail.'</p>');
            
            if(empty($id))
                $this->output->enable_profiler(TRUE);

            redirect('admin/estate/edit_dependent_field/'.$id);
        }
        
        // Load view
		$this->data['subview'] = 'admin/estate/edit_dependent_field';
        $this->load->view('admin/_layout_main', $this->data);
    }
    
    public function delete_dependent_field($id)
    {
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/estate/dependent_fields');
            exit();
        }
        
        $this->load->model('dependentfield_m');
        $this->dependentfield_m->delete($id);
		
        redirect('admin/estate/dependent_fields');
    }
    
    public function edit_option($id = NULL)
	{
	    // Fetch a record or set a new one
	    if($id)
        {
            $this->data['option'] = $this->option_m->get_lang($id, FALSE, $this->data['content_language_id']);
            count($this->data['option']) || $this->data['errors'][] = 'Could not be found';
            
            // Fetch file repository
            $repository_id = $this->data['option']->repository_id;
            if(empty($repository_id))
            {
                // Create repository
                $repository_id = $this->repository_m->save(array('name'=>'option_m'));
                
                // Update page with new repository_id
                $this->option_m->save(array('repository_id'=>$repository_id), $this->data['option']->id);
            }
        }
        else
        {
            $this->data['option'] = $this->option_m->get_new();
        }
        
        // Fetch all files by repository_id
        $this->data['files'] = array();
        if(!empty($repository_id))
        {
            $files = $this->file_m->get_where_in(array($repository_id));
            foreach($files as $key=>$file)
            {
                $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/_blank.png');
                $file->zoom_enabled = false;
                $file->download_url = base_url('files/'.$file->filename);
                $file->delete_url = site_url_q('files/upload/rep_'.$file->repository_id, '_method=DELETE&amp;file='.rawurlencode($file->filename));
    
                if(file_exists(FCPATH.'/files/thumbnail/'.$file->filename))
                {
                    $file->thumbnail_url = base_url('files/thumbnail/'.$file->filename);
                    $file->zoom_enabled = true;
                }
                else if(file_exists(FCPATH.'admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png'))
                {
                    $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/'.get_file_extension($file->filename).'.png');
                }
                
                $this->data['files'][$file->repository_id][] = $file;
            }
        }

        
		// Options for dropdown
        $this->data['options_no_parents'] = $this->option_m->get_no_parents($this->data['content_language_id']);
        $this->data['languages'] = $this->language_m->get_form_dropdown('language');

        // Set up the form
        $rules = $this->option_m->get_all_rules();
        $this->form_validation->set_rules($rules);

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Data editing disabled in demo'));
                redirect('admin/estate/edit_option/'.$id);
                exit();
            }
            
            $data = $this->option_m->array_from_post($this->option_m->get_post_fields());
            
            if(empty($data['max_length']))
                $data['max_length'] = NULL;
            
            if($id == NULL)
            {
                //get max order in parent id and set
                $parent_id = $this->input->post('parent_id');
                $data['order'] = $this->option_m->max_order($parent_id);
            }
            
            $data_lang = $this->option_m->array_from_post($this->option_m->get_lang_post_fields());
            $id = $this->option_m->save_with_lang($data, $data_lang, $id);
            
            //$this->output->enable_profiler(TRUE);
            //redirect('admin/estate/options');
            $this->session->set_flashdata('message', 
                    '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');
            
            redirect('admin/estate/edit_option/'.$id);
        }
        
        // Load the view
		$this->data['subview'] = 'admin/estate/edit_option';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function update_ajax($filename = NULL)
    {
        // Save order from ajax call
        if(isset($_POST['sortable']) && $this->config->item('app_type') != 'demo')
        {
            $this->option_m->save_order($_POST['sortable']);
        }
        
        $data = array();
        $length = strlen(json_encode($data));
        header('Content-Type: application/json; charset=utf8');
        header('Content-Length: '.$length);
        echo json_encode($data);
        
        exit();
    }
    
    public function delete_option($id)
	{
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/estate/options');
            exit();
        }
        
        if($this->option_m->check_deletable($id))
        {
            $this->option_m->delete($id);
        }
        else
        {
            $this->session->set_flashdata('error', 
                    lang_check('Delete disabled, child or element locked/hardlocked! But you can change or unlock it.'));
        }
		
        redirect('admin/estate/options');
	}
    
    public function forms($pagination_offset=0)
	{
	    $this->load->model('forms_m');
	    $this->load->library('pagination');
        
        $listing_selected = array();
        $listing_selected['theme'] = $this->data['settings']['template'];

        if(config_db_item('loaded_template_config') !== FALSE)
            $listing_selected['theme'] = config_db_item('loaded_template_config');
        
        // Fetch all listings
        $this->data['listings'] = $this->forms_m->get_by($listing_selected);

        $config['base_url'] = site_url('admin/savedsearch/index/'.$user_id.'/');
        $config['uri_segment'] = 4;
        $config['total_rows'] = count($this->data['listings']);
        $config['per_page'] = 20;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
        
        $this->data['listings'] = $this->forms_m->get_by($listing_selected, FALSE, $config['per_page'], NULL, $pagination_offset);

        // Load view
		$this->data['subview'] = 'admin/forms/index';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function values_correction(&$str)
    {
        $str = str_replace(', ', ',', $str);
        
        return TRUE;
    }
    
    public function values_dropdown_check($str)
    {
        static $already_set = false;
        $comma_count = -1;
        
        if($already_set == true)
            return TRUE;
        
        foreach($this->option_m->languages as $key=>$value)
        {
            $values_post = $this->input->post("values_$key");
            
            $comma_cur_count = substr_count($values_post, ',');
            
            if($comma_count == -1)$comma_count = $comma_cur_count;
            
            if($comma_count != $comma_cur_count)
            {
                $this->form_validation->set_message('values_dropdown_check', lang_check('Values number must be same in all languages'));
                $already_set = true;
                return FALSE;
            }
        }
        
        return TRUE;
    }
    
	public function gps_check($str)
	{
        $gps_coor = explode(', ', $str);
        
        if(count($gps_coor) != 2)
        {
        	$this->form_validation->set_message('gps_check', lang_check('Please check GPS coordinates'));
        	return FALSE;
        }
        
        if(!is_numeric($gps_coor[0]) || !is_numeric($gps_coor[1]))
        {
        	$this->form_validation->set_message('gps_check', lang_check('Please check GPS coordinates'));
        	return FALSE;
        }
        
        if($gps_coor[0] < -90 || $gps_coor[0] > 90 || $gps_coor[1] < -180 || $gps_coor[1] > 180)
        {
        	$this->form_validation->set_message('gps_check', lang_check('Please check GPS coordinates'));
        	return FALSE;
        }
        
        return TRUE;
	}
    
    public function json2field($field_id)
    {
        $query = $this->db->get('property_lang');

        if (!$this->db->field_exists("field_$field_id", 'property_lang'))
        {
            exit("field_$field_id doesn't exists");
        } 

        $data = array();

        foreach ($query->result() as $row)
        {
            $json_obj = json_decode($row->json_object);
            
            if(isset($json_obj->{"field_$field_id"}))
            {
                $data[] = array(
                  'l_id' => (int) $row->l_id,
                  "field_$field_id" => (string) $json_obj->{"field_$field_id"}
                );
            }
            
            //if(count($data) > 3)break;
        }

        $this->db->update_batch('property_lang', $data, 'l_id'); 
        
        exit('update successfuly: '.count($data));
    }
    
	public function featured_limitation_check($str)
	{
        if($str=='1')
        {
            if($this->session->userdata('type') != 'ADMIN' && $this->session->userdata('type') != 'AGENT_ADMIN')
            {
                // Get exception property_id
                $exception_property_id = NULL;
                if(isset($this->data['estate']))
                    $exception_property_id = $this->data['estate']->id;
                
                $this->load->model('user_m');
                $user = $this->user_m->get($this->session->userdata('id'));
                if(empty($user->package_id)) return TRUE;
                
                if($this->packages_m->get_available_featured(NULL, $exception_property_id) == 0)
                {
                	$this->form_validation->set_message('featured_limitation_check', 
                                                        lang_check('Featured limitation reached in your package!'));
                	return FALSE;
                }
            }
        }
        
        return TRUE;
	}
    
        public function import_csv() {
            
            $this->load->library('Importcsv');
            /* feature */
            $xml='file.csv';
            $config['allowed_types'] = 'csv|xml|txt|text';
            $config['allowed_types'] = '*'; // test
            $config['upload_path'] = './files/';
            $config['overwrite'] = TRUE;
            $this->load->library('upload', $config);
            
            if($this->input->post()) :
                    if($this->input->post('csv_url')) {
                        if(preg_match('/\.(csv|xml|txt|text)$/i', $this->input->post('csv_url'))){
                        // Load csv file for import
                        $xmlurl = trim($this->input->post('csv_url'));
                        $imports=$this->importcsv->start_import($xmlurl);
                        $this->data['imports']= $imports;
                        } else {
                            $this->data['error'] = 'Set current xml link';
                        }
                    } else if($this->upload->do_upload('userfile_csv')){
                        $this->upload->do_upload('userfile_xml');
                        $upload_data = $this->upload->data();
                        $file_path = $upload_data['full_path'];

                        // Load csv file for import
                        $xmlurl = $file_path;
                        $imports=$this->importcsv->start_import($xmlurl);
                        
                        $this->data['imports']= $imports;
                                
                    } else {
                        /* error */
                        $this->data['error'] = $this->upload->display_errors('', '');
                    }
                endif;
                
        // Load view
        $this->data['subview'] = 'admin/estate/import_csv';
        $this->load->view('admin/_layout_main', $this->data);
        }
        
        function export_csv () {
            $this->load->library('importcsv');
            $this->load->helper('download');
            $this->importcsv->startExport();
            
            $date = date('Y-m-d H:i:s');
            force_download('export_'.$date.'.csv', $this->importcsv->get_csv());
            
        }
        
}