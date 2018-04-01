<?php

class Templatefiles extends Admin_Controller
{

	public function __construct(){
		parent::__construct();
        $this->load->model('customtemplates_m');

        // Get language for content id to show in administration
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
        
        $this->data['template_css'] = base_url('templates/'.$this->data['settings']['template']).'/'.config_item('default_template_css');
	}
    
    public function index()
	{
	    $this->data['writing_permissions'] = check_template_writing_permissions($this->data['settings']['template']);
       
	    $this->load->library('pagination');

        // Fetch all listings
        $this->data['listings'] = array();
        
        $path = FCPATH.'templates/'.$this->data['settings']['template'];
        $templatesDirectory = opendir($path);
        // get each template
        while($tempFile = readdir($templatesDirectory)) {
            if ($tempFile != "." && $tempFile != "..") {
                if(substr($tempFile,-4) == '.php')
                {
                    $this->data['listings']['root'][] = $tempFile;
                }
                else if(is_dir($path.'/'.$tempFile))
                {
                    $path1 = $path.'/'.$tempFile;
                    $templatesDirectory1 = opendir($path1);
                    while($tempFile1 = readdir($templatesDirectory1)) {
                        if ($tempFile1 != "." && $tempFile1 != "..") {
                            if(substr($tempFile1,-4) == '.css' || substr($tempFile1,-4) == '.php' || substr($tempFile1,-3) == '.js')
                            {
                                $this->data['listings'][$tempFile][] = $tempFile1;
                            }
                            else if(is_dir($path1.'/'.$tempFile1))
                            {
                                $templatesDirectory2 = opendir($path1.'/'.$tempFile1);
                                while($tempFile2 = readdir($templatesDirectory2)) {
                                    if ($tempFile2 != "." && $tempFile2 != "..") {
                                        if(substr($tempFile2,-4) == '.css' || substr($tempFile2,-4) == '.php' || substr($tempFile2,-3) == '.js')
                                        {
                                            $this->data['listings'][$tempFile][$tempFile1][] = $tempFile2;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // Load view
		$this->data['subview'] = 'admin/templatefiles/index';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
    public function edit($filename = NULL, $foldername = NULL, $subfoldername = NULL)
	{
	    // Fetch a page or set a new one
	    if(empty($filename))
        {
            redirect('admin/templatefiles/index/');
        }
        
        $this->data['writing_permissions'] = check_template_writing_permissions($this->data['settings']['template']);
        if($this->data['writing_permissions'] != '')
        {
            $this->session->set_flashdata('error', 
                    lang_check('Data editing disabled because of chmod permissions'));
            redirect('admin/templatefiles/index/');
            exit();
        }
        
        $file_path = FCPATH.'templates/'.$this->data['settings']['template'].'/';
        if(!empty($foldername) && $foldername != 'root')$file_path.=$foldername.'/';
        if(!empty($subfoldername))$file_path.=$subfoldername.'/';
        $file_path.=$filename;
        
        if(!file_exists($file_path))
        {
            exit('File not exists');
        }
        
        if(!is_writable($file_path))
        {
            exit(lang_check('File is not writable').': '.$file_path);
        }
        
        $this->data['filename'] = $filename;
        
        $this->data['file_content'] = file_get_contents($file_path);

        
        // Set up the form
        $rules = array();
        $rules['file_content'] = array('field'=>'file_content', 'label'=>'lang:File content', 'rules'=>'trim|required');
        $this->form_validation->set_rules($rules);

        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            if($this->config->item('app_type') == 'demo')
            {
                $this->session->set_flashdata('error', 
                        lang('Data editing disabled in demo'));
                redirect('admin/templatefiles/');
                exit();
            }
            
            $data = $_POST['file_content'];
            
            if(!empty($data))
                file_put_contents($file_path, $data);
            
            $this->session->set_flashdata('message', 
                    '<p class="label label-success validation">'.lang_check('Changes saved').'</p>');
            
            redirect('admin/templatefiles');
        }
        
        // Load the view
		$this->data['subview'] = 'admin/templatefiles/edit';
        $this->load->view('admin/_layout_main', $this->data);
	}
    
}