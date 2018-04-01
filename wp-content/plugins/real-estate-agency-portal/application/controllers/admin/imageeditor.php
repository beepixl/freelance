<?php

class Imageeditor extends Admin_Controller
{
	public function __construct(){
		parent::__construct();
        $this->load->model('page_m');
        $this->load->model('file_m');
        $this->load->model('repository_m');

        // Get language for content id to show in administration
        $this->data['content_language_id'] = $this->language_m->get_content_lang();
        
        $this->data['template_css'] = base_url('templates/'.$this->data['settings']['template']).'/'.config_item('default_template_css');
        
        $this->data['user_id'] = $this->session->userdata('id');
    }
    
    public function edit($filename, $dim = '800x600')
	{
        $input_data = $_POST;
        $this->data['resize'] = $dim;

        $filename_db = urldecode($filename);
        $filename_db = str_replace("&#40;","(",$filename_db);
        $filename_db = str_replace("&#41;",")",$filename_db);
        
        $filename_url = $filename;
        $filename_url = str_replace("&#40;","(",$filename_url);
        $filename_url = str_replace("&#41;",")",$filename_url);
        
        $this->load->model('file_m');
        $this->data['form'] = $this->file_m->get_by(array('filename'=>$filename_db), TRUE);
 
        if(empty($this->data['form']->id))
            exit('Not exists');
        
        // Check if user have permission on this file
        if($this->session->userdata('type') == 'USER')
        if(!$this->user_m->is_related_repository($this->data['user_id'], $this->data['form']->repository_id))
        {
            exit('No permissions');
        }
        
        if(count($input_data) > 0)
        {
            if(isset($input_data['image-data']))
            {
                $data_im = explode(',', $input_data['image-data']);
                $data_im = base64_decode($data_im[1]);
                $fpath = FCPATH.'files/'.$filename_db;
                
                $im = imagecreatefromstring($data_im);
                switch (strtolower(substr(strrchr($filename_db, '.'), 1))) {
                    case 'jpg':
                    case 'jpeg':
                        imagejpeg($im, $fpath);
                        break;
                    case 'gif':
                        imagegif($im, $fpath);
                        break;
                    case 'png':
                        imagepng($im, $fpath);
                        break;
                    default:
                }
                
                // generate image versions
                
                $this->load->library('uploadHandler', array('initialize'=>FALSE));
                $this->uploadhandler->regenerate_versions($filename_db, '');
                
                $this->file_m->delete_cache($filename_db);
            }
            
            $data = $this->user_m->array_from_post(array('alt', 'description', 'title', 'link'));
            $this->file_m->save($data, $this->data['form']->id);
            
            $this->session->set_flashdata('message', 
                    '<p class="alert alert-success validation">'.lang_check('Changes saved').'</p>');

            redirect(uri_string());
        }
       
        if($dim!=='false'):
            $dim_exp = explode('x', $dim);
            $this->data['width'] = $dim_exp[0];
            $this->data['height'] = $dim_exp[1];
            $wanted_ratio = $this->data['width'] / $this->data['height'];

            $this->data['filepath'] = base_url('files/'.$filename_url);

            $dim_real = getimagesize(FCPATH.'files/'.$filename_db);
            $this->data['width_r'] = $dim_real[0];
            $this->data['height_r'] = $dim_real[1];
            $real_ratio = $this->data['width_r'] / $this->data['height_r'];

            //     800x600       700x600
            if($wanted_ratio > $real_ratio)
            {
                $this->data['width'] = $this->data['width_r'];
                $this->data['height'] = $this->data['width_r'] * 1/$wanted_ratio;
            }
            //          800x600       900x600
            else if($wanted_ratio <= $real_ratio)
            {
                $this->data['width'] = $this->data['height_r'] * $wanted_ratio;
                $this->data['height'] = $this->data['height_r'];
            }

            // for larger images
            if($this->data['width'] > $dim_exp[0])
            {
                $this->data['width'] = $dim_exp[0];
                $this->data['height'] = $dim_exp[1];
            }
        endif;
        
        
        $this->load->view('admin/imageeditor/edit', $this->data);
	}

    
}