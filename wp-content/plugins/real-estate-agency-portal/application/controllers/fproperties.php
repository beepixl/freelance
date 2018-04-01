<?php

class Fproperties extends Frontuser_Controller
{

	public function __construct ()
	{
		parent::__construct();
        
        $this->load->model('packages_m');
	}
    
    public function index()
    {
        echo 'index';
    }
    
	public function make_featured()
	{
	    $listing_id = $this->uri->segment(4);
        $lang_id = $this->data['lang_id'];
        $user_id = $this->session->userdata('id');
        
        if($this->packages_m->get_available_featured() > 0)
        {
            $data_r = array();
            $data_r['is_featured'] = '1';
            $data_r['featured_paid_date'] = date('Y-m-d H:i:s');
            
            $this->estate_m->save($data_r, $listing_id);
        }
        else
        {
            $this->session->set_flashdata('error_package', lang_check('Featured limitation reached in your package!'));
        }
        
        redirect('frontend/myproperties/'.$this->data['lang_code']);
    }


}