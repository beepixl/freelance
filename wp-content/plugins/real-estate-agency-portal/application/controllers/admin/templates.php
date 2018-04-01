<?php

class Templates extends Admin_Controller
{

	public function __construct(){
		parent::__construct();
        $this->load->model('customtemplates_m');

        // Get language for content id to show in administration
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
        
        $this->data['template_css'] = base_url('templates/'.$this->data['settings']['template']).'/'.config_item('default_template_css');
	}
    
    public function index($pagination_offset=0)
	{
	    $this->load->library('pagination');
        
        $listing_selected = array();
        $listing_selected['theme'] = $this->data['settings']['template'];
        
        // Fetch all listings
        $this->data['listings'] = $this->customtemplates_m->get_by($listing_selected);

        $config['base_url'] = site_url('admin/savedsearch/index/'.$user_id.'/');
        $config['uri_segment'] = 4;
        $config['total_rows'] = count($this->data['listings']);
        $config['per_page'] = 20;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
        
        $this->data['listings'] = $this->customtemplates_m->get_by($listing_selected, FALSE, $config['per_page'], NULL, $pagination_offset);

        // Load view
		$this->data['subview'] = 'admin/templates/index';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function edit($id = NULL)
	{
	    // Fetch a page or set a new one
	    if($id)
        {
            $this->data['listing'] = $this->customtemplates_m->get($id);
            count($this->data['listing']) || $this->data['errors'][] = 'Could not be found';
        }
        else
        {
            $this->data['listing'] = $this->customtemplates_m->get_new();
        }
        
        // Set up the form
        $rules = $this->customtemplates_m->rules_admin;
        $this->form_validation->set_rules($rules);
        
        $this->load->model('page_m');
        $this->widgets = $this->page_m->get_subtemplates('widgets');

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Data editing disabled in demo'));
                redirect('admin/templates/edit/'.$id);
                exit();
            }
            
            $data = $this->customtemplates_m->array_from_rules($rules);
            
            $id = $this->customtemplates_m->save($data, $id, TRUE);
            
            $this->session->set_flashdata('message', 
                    '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');
            
            if(!empty($id))
            {
                redirect('admin/templates/edit/'.$id);
            }
            else
            {
                $this->output->enable_profiler(TRUE);
            }
        }
        
        // Load the view
		$this->data['subview'] = 'admin/templates/edit';
        $this->load->view('admin/_layout_main', $this->data);
	}

    public function delete($id)
	{
        if($this->config->item('app_type') == 'demo')
        {
            $this->session->set_flashdata('error', 
                    lang('Data editing disabled in demo'));
            redirect('admin/templates');
            exit();
        }
       
		$this->customtemplates_m->delete($id);
        redirect('admin/templates');
	}
    
}