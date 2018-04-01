<?php

class Api extends CI_Controller
{
    private $data = array();
    private $settings = array();
    private $api_key='4PcY4Dku0JA5Gd4aT9evfEPMnG9BGBPi';
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('settings_m');
        $this->settings = $this->settings_m->get_fields();
        
        $method = $this->uri->segment(2);
        
        if($method == 'rss')
        {
            header('Content-Type: application/rss+xml; charset=utf-8');
        }
        else
        {
            header('Content-Type: application/json');
        }
    }
   
	public function index()
	{
		echo 'Hello, API here!';
        exit();
	}
    
    /**
     * Api::translate()
     * 
     * @param string $api, mymemory | google
     * @return
     */
    public function translate($api = 'mymemory')
    {
        $this->load->model('language_m');
        
        $this->load->library('gTranslation', array());
        $this->load->library('mymemoryTranslation', array());
        
        $code_from = $this->input->get_post('from');
        $code_to = $this->input->get_post('to');
        $value = $this->input->get_post('value');
        $index = $this->input->get_post('index');
        
        if(is_numeric($code_from))
        {
            $code_from = $this->language_m->get_code($code_from);
        }
        
        if(is_numeric($code_to))
        {
            $code_to = $this->language_m->get_code($code_to);
        }
        
        $translated_value = '';
        $all_translations = array();
        
        // Fix value if HTML errors exists:
        if(function_exists('tidy'))
        {
            $tidy = new tidy();
            $value = $tidy->repairString($value);
        }
        
        if($api == 'google')
        {
            $translated_value = $this->gtranslation->translate($value, $code_from, $code_to);
        }
        else
        {
            $translated_value = $this->mymemorytranslation->translate($value, $code_from, $code_to);
        }
        
        $all_translations['result'] = $translated_value;
        
        echo json_encode($all_translations);
        exit();
    }
    
    public function export_lang_files()
    {
        $this->load->helper('file');
        $zip = new ZipArchive;
        
        $filename_zip = APP_VERSION_REAL_ESTATE.'-languages.zip';
        unlink(FCPATH.$filename_zip);
        
        $zip->open(FCPATH.$filename_zip, ZipArchive::CREATE);
        
        $lang_path = realpath(BASEPATH.'language/');
        $remove_chars = strlen(realpath(BASEPATH.'../'))+1;
        $directory_iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($lang_path));
        foreach($directory_iterator as $filename => $path_object)
        {
            if(is_file($filename))
            {
                $zip_filename = substr($filename, $remove_chars);
                $zip->addFile($filename, $zip_filename);
            }
        }
        
        $lang_path = realpath(BASEPATH.'../'.APPPATH.'language/');
        $remove_chars = strlen(realpath(BASEPATH.'../'))+1;
        $directory_iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($lang_path));
        foreach($directory_iterator as $filename => $path_object)
        {
            if(is_file($filename))
            {
                $zip_filename = substr($filename, $remove_chars);
                $zip->addFile($filename, $zip_filename);
            }
        }
        
        $lang_path = realpath(FCPATH.'templates/'.$this->settings['template'].'/language/');
        $remove_chars = strlen(realpath(FCPATH))+1;
        $directory_iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($lang_path));
        foreach($directory_iterator as $filename => $path_object)
        {
            if(is_file($filename))
            {
                $zip_filename = substr($filename, $remove_chars);
                $zip->addFile($filename, $zip_filename);
            }
        }

        $ret = $zip->close();
        
        if($ret == true)
        {
            $this->load->helper('download');
            $data = file_get_contents(FCPATH.$filename_zip); // Read the file's contents
            force_download($filename_zip, $data);
        }
        else
        {
            echo 'failed';
        }
    }
    
    public function get_level_values_select($lang_id, $field_id, $parent_id=0, $level=0)
    {
        //load language files
        $this->load->model('language_m');
        $lang_name = $this->language_m->get_name($lang_id);
        $this->lang->load('frontend_template', $lang_name, FALSE, TRUE, FCPATH.'templates/'.$this->settings['template'].'/');
        
        $this->data['message'] = lang_check('No message returned!');
        $this->data['parameters'] = $_POST;
        $parameters = json_encode($_POST);
        
        
        $this->load->model('treefield_m');
        
        $values_arr = $this->treefield_m->get_level_values ($lang_id, $field_id, $parent_id, $level);
        
        $generate_select = '';
        foreach($values_arr as $key=>$value)
        {
            $generate_select.= "<option value=\"$key\">$value</option>\n";
        }
        
        $this->data['generate_select'] = $generate_select;
        $this->data['values_arr'] = $values_arr;
        
        echo json_encode($this->data);
        exit();
    }
    
    public function rss($lang_code, $limit_properties=20, $offset_properties=0)
    {
        $this->load->model('language_m');
        $this->load->model('option_m');
        $this->load->model('estate_m');
        $this->load->model('file_m');
        $lang_id = $this->language_m->get_id($lang_code);
        $lang_name = $this->language_m->get_name($lang_id);
        $this->lang->load('frontend_template', $lang_name, FALSE, TRUE, FCPATH.'templates/'.$this->settings['template'].'/');
        
        if(empty($this->settings['websitetitle']))$this->settings['websitetitle'] = 'Title not defined';
        
        $this->data['listing_uri'] = config_item('listing_uri');
        if(empty($this->data['listing_uri']))$this->data['listing_uri'] = 'property';
        
        //Fetch last 20 properties
        //$options = $this->option_m->get_options($lang_id);
        
        $where = array();
        $search_array = array();
        $where['language_id']  = $lang_id;
        $where['is_activated'] = 1;
        
        $estates = $this->estate_m->get_by($where, false, $limit_properties, 'property.id DESC', $offset_properties);
        
        // Fetch all files by repository_id
//        $files = $this->file_m->get();
//        $rep_file_count = array();
//        $this->data['page_images'] = array();
//        foreach($files as $key=>$file)
//        {
//            $file->thumbnail_url = base_url('admin-assets/img/icons/filetype/_blank.png');
//            $file->url = base_url('files/'.$file->filename);
//            if(file_exists(FCPATH.'files/thumbnail/'.$file->filename))
//            {
//                $file->thumbnail_url = base_url('files/thumbnail/'.$file->filename);
//                $this->data['images_'.$file->repository_id][] = $file;
//            }
//        }
        
        // Set website details
        $generated_xml = '<?xml version="1.0" encoding="UTF-8" ?>';
        $generated_xml.= '<rss version="2.0">
                            <channel>
                              <title><![CDATA[ '.strip_tags($this->settings['websitetitle']).' ]]></title>
                              <link>'.site_url().'</link>
                              <description>'.$this->settings['phone'].', '.$this->settings['email'].'</description>';
        
        
        // Add listings to rss feed     
        foreach($estates as $key=>$row){
            $title_slug=$title='';
            $value = $this->estate_m->get_field_from_listing($row, 10);
            if(!empty($value))
            {
                $title = $value;
                $title_slug = url_title_cro($value);
            }
            $url = slug_url($this->data['listing_uri'].'/'.$row->id.'/'.$lang_code.'/'.$title_slug);

            $description = 'Description field removed';
            $value = $this->estate_m->get_field_from_listing($row, 8);
            if(!empty($value))
            {
                $description = $value;
            }
            
            // Thumbnail
            $thumbnail_url = '';
            if(isset($row->image_filename))
            {
                $thumbnail_url = base_url('files/thumbnail/'.$row->image_filename);
                $thumbnail_url = '<img align="left" hspace="5" src="'.$thumbnail_url.'" />';
            }
            
            $generated_xml.=  '<item>
                                <title>'.$title.'</title>
                                <link>'.$url.'</link>
                                <description>
                                    <![CDATA['.$thumbnail_url.$description.']]>
                                </description>
                              </item>';
        }

        // Close rss  
        $generated_xml.= '</channel></rss>';

        echo $generated_xml;
        exit();
    }
    
    /*
        Example call: index.php/api/json/en?
        Supported uri parameters, for pagination:
        $limit_properties=20
        $offset_properties=0
        
        Supported query parameters:
        options_hide
        v_rectangle_ne=46.3905, 16.8329
        v_rectangle_sw=45.9905, 15.999
        search={"search_option_smart":"yellow","v_search_option_2":"Apartment"}
        
        Complete example:
        index.php/api/json/en/20/0?options_hide&search={"search_option_smart":"cestica"}&v_rectangle_ne=46.3905, 16.8329&v_rectangle_sw=45.9905, 15.999
        Example for "from":
        {"v_search_option_36_from":"60000"}
        Example for indeed value:
        {"v_search_option_4":"Sale and Rent"}
    */
    public function json($lang_code=null, $limit_properties=20, $offset_properties=0)
    {
        if($lang_code == NULL)
            exit('Wrong API call!');
        
        $this->data['message'] = lang_check('No message returned!');
        $this->data['parameters'] = $search = $this->input->get_post('search');
        $options_hide = $this->input->get_post('options_hide');
        
        
        $this->load->model('language_m');
        $this->load->model('option_m');
        $this->load->model('estate_m');
        $this->load->model('file_m');
        $lang_id = $this->language_m->get_id($lang_code);
        $lang_name = $this->language_m->get_name($lang_id);
        $this->lang->load('frontend_template', $lang_name, FALSE, TRUE, FCPATH.'templates/'.$this->settings['template'].'/');
        
        if(empty($this->settings['websitetitle']))$this->settings['websitetitle'] = 'Title not defined';
        
        $data_tmp['listing_uri'] = config_item('listing_uri');
        if(empty($data_tmp['listing_uri']))$data_tmp['listing_uri'] = 'property';
        
        $search_array = array();
        if(!empty($search))
        {
            $search_array = json_decode($search);

            if(empty($search_array) && is_string($search))
            {
                $search_array['v_search_option_smart'] = $search;
            }
        }
        
        if(is_object($search_array))
            $search_array = (array) $search_array;
        
        $purpose = "";
        if(is_array($search_array) && isset($search_array['v_search_option_4']))
        {
            $purpose = $search_array['v_search_option_4'];
        }
        
        $order_by = NULL;
        $options_order = $this->input->get_post('order');
        if(!empty($options_order))
        {
            $order_by = $options_order;
            
            if(strpos($purpose, lang_check('Rent')) !== FALSE)
            {
                $order_by = str_replace("price", "field_37_int", $order_by);
            }
            
            $order_by = str_replace("price", "field_36_int", $order_by);
        }
        
        // Rent price support
        if(strpos($purpose, lang_check('Rent')) !== FALSE)
        {
            if(isset($search_array['v_search_option_36_from']))
                $search_array['v_search_option_37_from'] = $search_array['v_search_option_36_from'];
            if(isset($search_array['v_search_option_36_to']))
                $search_array['v_search_option_37_to'] = $search_array['v_search_option_36_to'];
            unset($search_array['v_search_option_36_from'], $search_array['v_search_option_36_to']);
        }

        //Fetch last 20 properties
        //$options = $this->option_m->get_options($lang_id);
        
        $this->data['total_results'] = $this->estate_m->count_get_by(array('is_activated' => 1, 'language_id' => $lang_id), false, NULL, NULL, NULL, $search_array);
        
        $estates = $this->estate_m->get_by(array('is_activated' => 1, 'language_id' => $lang_id), false, $limit_properties, $order_by, $offset_properties, $search_array, NULL, FALSE, TRUE);
        
        $this->data['field_details'] = NULL;
        if(!empty($options_hide))
        {
            $this->data['field_details'] = $this->option_m->get_lang(NULL, FALSE, $lang_id);
        }
        
        // Set website details
        $json_data = array();
        // Add listings to rss feed     
        foreach($estates as $key=>$row){
            $estate_date = array();
            $title = $this->estate_m->get_field_from_listing($row, 10);
            $url = site_url($data_tmp['listing_uri'].'/'.$row->id.'/'.$lang_code.'/'.url_title_cro($title));
            
            $row->json_object = json_decode($row->json_object);
            $row->image_repository = json_decode($row->image_repository);
            $estate_date['url'] = $url;
            $estate_date['listing'] = $row;
            
            $json_data[] = $estate_date;
        }
        
        $this->data['results'] = $json_data;
        
        echo json_encode($this->data);
        exit();
    }
    
    public function google_login($lang_id = NULL)
    {
        if (version_compare(phpversion(), '5.5.0', '>=')) {
        } else {
            exit('PHP version 5.5 is required for google login');
        }
        
        if(!file_exists(APPPATH.'libraries/Glogin.php'))
        {
            exit('Google login modul is not available');
        }

        $this->load->model('language_m');
        
        if(empty($lang_id))
            $lang_id = $this->language_m->get_default_id();
        
        $lang_code = $this->language_m->get_code($lang_id);
        
        $this->load->library('Glogin');

        $provider = $this->glogin->getProvider();
        
        if (!empty($_GET['error'])) {
            // Got an error, probably user denied access
            exit('Got error: ' . $_GET['error']);
        
        } elseif (empty($_GET['code'])) {
        
            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl();
            $this->session->set_flashdata('oauth2state', $provider->getState());
            
            header('Location: ' . $authUrl);
            exit;
        
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $this->session->flashdata('oauth2state'))) {
        
            // State is invalid, possible CSRF attack in progress
            //unset($_SESSION['oauth2state']);
            
            $this->user_m->logout();
            
            exit('Invalid state');
        
        } else {
        
            // Try to get an access token (using the authorization code grant)
            $token = $provider->getAccessToken('authorization_code', array(
                'code' => $_GET['code']
            ));
        
            // Optional: Now you have a token you can look up a users profile data
            try {
                // We got an access token, let's now get the owner details
                $ownerDetails = $provider->getResourceOwner($token);
//array(5) {
//  ["emails"]=>
//  array(1) {
//    [0]=>
//    array(1) {
//      ["value"]=>
//      string(22) ""
//    }
//  }
//  ["id"]=>
//  string(21) ""
//  ["displayName"]=>
//  string(12) ""
//  ["name"]=>
//  array(2) {
//    ["familyName"]=>
//    string(6) ""
//    ["givenName"]=>
//    string(5) ""
//  }
//  ["image"]=>
//  array(1) {
//    ["url"]=>
//    string(98) ""
//  }
//}
                $user_array = $ownerDetails->toArray();
                $user_email = $ownerDetails->getEmail();
                $user_namesurname = $ownerDetails->getFirstName();
                
                // Register / Login
                $user_get = $this->user_m->get_by(array('password'=>$this->user_m->hash($user_array['id']), 
                                                        'username'=>$user_email), true);
                
                if(count($user_get) == 0)
                {
                    // Check if email already exists
                    if($this->user_m->if_exists($user_email) === TRUE)
                    {
                        exit('Email already exists in database, please contact administrator or reset password');
                    }
                    
                    // Register user
                    $data_f = array();
                    $data_f['username'] = $user_email;
                    $data_f['mail'] = $user_email;
                    $data_f['password'] = $this->user_m->hash($user_array['id']);
                    $data_f['facebook_id'] = '';
                    $data_f['type'] = 'USER';
                    $data_f['name_surname'] = $user_namesurname;
                    $data_f['activated'] = '1';
                    $data_f['description'] = '';
                    $data_f['language'] = $lang_id;
                    $data_f['registration_date'] = date('Y-m-d H:i:s');
                    $data_f['mail_verified'] = 0;
                    $data_f['phone_verified'] = 0;               
                    
                    if($this->config->item('def_package') !== FALSE)
                        $data_f['package_id'] = $this->config->item('def_package');
                    
                    $user_id = $this->user_m->save($data_f, NULL);
                }
                
                // Login :: AUTO
                if($this->user_m->login($user_email, $user_array['id']) == TRUE)
                {
                    if(!empty($user_id) && 
                        config_item('registration_interest_enabled') === TRUE && 
                        config_item('tree_field_enabled') === TRUE)
                    {
                        redirect('fresearch/treealerts/'.$lang_code.'/'.$user_id.'/'.md5($user_id.config_item('encryption_key')));
                    }
                    
                    redirect('frontend/myproperties/'.$lang_code);
                    exit();
                }
                else
                {
                    $this->session->set_flashdata('error', 
                            lang_check('That email/password combination does not exists'));
                    redirect('frontend/login/'.$lang_code); 
                    exit();
                }
        
            } catch (Exception $e) {
        
                // Failed to get user details
                exit('Something went wrong: ' . $e->getMessage());
        
            }
        
            // Use this to interact with an API on the users behalf
            // echo $token->accessToken;
        
            // Use this to get a new access token if the old one expires
            //echo $token->refreshToken;
        
            // Number of seconds until the access token will expire, and need refreshing
            //echo $token->expires;
        }
        
        exit();
    }
    
    /* 
     * Use for add property to compare list, use with Controller propertycompare.php
     * 
     */
    public function add_to_compare ($lang_code='') {
        
        /* config */
        $max_properties=4;   
        /* end  config */
        
        $this->load->library('session');
        $this->load->model('option_m');
        $this->load->model('language_m');
        $json_data['success'] = false;
        /*$ses=$this->session->userdata('property_compare');
        // if max property
        if(count($ses)>=$max_properties) {
            $json_data['message'] = lang_check('Added max propery');
            echo json_encode($json_data);
            exit();
        }*/
       
        /* data */
        
        $this->data['post'] = $_POST;
        // lang id and code
        if(empty($lang_code)) $lang_code= $this->language_m->get_default();
        $lang_id = $this->language_m->get_id($lang_code);
        
        $title_option_id=10;
        /* end data */
        
        $json_data=array();
            
        $id_property= trim($this->data['post']['property_id']);
        if(empty($id_property)) {
            $json_data['message'] = lang_check('Parameters not defined!');
            echo json_encode($json_data);
            exit();
        }
        
        // get title
        $title= $this->option_m->get_property_value($lang_id, $id_property, $title_option_id);
        if(empty($title)) {
            $json_data['message'] = lang_check('Parameters not defined!');
            echo json_encode($json_data);
            exit();
        }
        $this->data['listing_uri'] = config_item('listing_uri');
        //get other compare in session
        $data_sess['property_compare'] = $this->session->userdata('property_compare');
        $data_sess['property_compare'][$id_property] = $title;
        
         $json_data['remove_first']=false;
        if(count($data_sess['property_compare'])>$max_properties) {
            reset($data_sess['property_compare']);
            unset($data_sess['property_compare'][key($data_sess['property_compare'])]);
            $json_data['remove_first']=true;
        }
        
        $this->session->set_userdata($data_sess);
        
        // answere
        $json_data['message'] = lang_check('Propery added to compare');
        $json_data['property'] = $id_property.', '.$title;
        $json_data['property_id'] = $id_property;
        $json_data['property_url'] = slug_url($this->data['listing_uri'].'/'.$id_property.'/'.$lang_code.'/'.url_title_cro($title));
        $json_data['success'] = true;
              //  print_r($this->session->userdata('property_compare'));
       echo json_encode($json_data);
    exit();
   } 
    
    /* 
     * Use for remove property from compare list, use with Controller propertycompare.php
     * 
     */
    public function remove_from_compare($lang_code='') {
        /* data */
        $this->load->library('session');
        $this->data['post'] = $_POST;
        /* end data */
        $this->data['success'] = false;
        $json_data=array();
        $this->load->model('option_m');
        $this->load->model('language_m');
            
        $id_property= trim($this->data['post']['property_id']);
        if(empty($id_property)) {
            $json_data['message'] = lang_check('Parameters not defined!');
            $json_data['success'] = true;
            return false;
        }
        
        
        //get other compare in session
        $data_sess['property_compare'] = $this->session->userdata('property_compare');
        unset($data_sess['property_compare'][$id_property]);
        $this->session->set_userdata($data_sess);
        // answere
        $json_data['message'] = lang_check('Propery remove from compare');
        $json_data['property_id'] = $id_property;
        $json_data['success'] = true;
       echo json_encode($json_data);
    exit();
   } 
    
  public function pdf_export($property_id = '', $lang_code = 'en') {
        if(empty($property_id)) {
           exit(lang_check('Listing not found'));
        }
        $this->load->library('pdf');
        $this->pdf->generate_by_property($property_id, $lang_code, $this->api_key);
    }

}
