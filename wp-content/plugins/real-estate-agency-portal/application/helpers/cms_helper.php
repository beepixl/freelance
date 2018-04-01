<?php

function add_meta_title ($string)
{
	$CI =& get_instance();
	$CI->data['meta_title'] = e($string) . ' - ' . $CI->data['meta_title'];
}


function get_numeric_val($string_val)
{
    $val_numeric = NULL;
    $value_n = trim($string_val);
    $value_n = str_replace("'", '', $value_n);
    $value_n = str_replace("’", '', $value_n);
    $value_n = str_replace(",", '', $value_n);
    if( is_numeric($value_n) )
    {
        $val_numeric = floatval($value_n);
    }
    
    return $val_numeric;
}

function parseTable($html)
{
    
    
  // Iterate each row
  preg_match_all("/<tr.*?>(.*?)<\/[\s]*tr>/s", $html, $matches);

  $table = array();
  foreach($matches[1] as $row_html)
  {
    preg_match_all("/<td.*?>(.*?)<\/[\s]*td>/", $row_html, $td_matches);
    $row = array();
    for($i=0; $i<count($td_matches[1]); $i++)
    {
      $td = strip_tags(html_entity_decode($td_matches[1][$i]));
      $row[$i] = $td;
    }

    if(count($row) > 0)
      $table[] = $row;
  }

  return $table;
}

function parse_tags($content)
{
/*
    $content = $nc_item['description'];
    $needle = '[';
    while (($lastPos = strpos($content, $needle, $lastPos))!== false) {
        $to = strpos($content, ']', $lastPos+1);
        $from = $lastPos+strlen($needle);
        $length = $to-$lastPos-strlen($needle);
        $code = substr($content, $from, $length);
    
        $search = substr($content, $lastPos, $to-$lastPos+1);
        $replace = '<iframe width="420" height="315" src="//www.youtube.com/embed/'.$code.'" frameborder="0" allowfullscreen></iframe>';
        $content = str_replace($search, $replace, $content);
        
        $lastPos = $lastPos + strlen($needle);
    }
*/
}

function _search_form_primary($form_id)
{
    $CI =& get_instance();
    
    $CI->load->model('forms_m');  
    $form = $CI->forms_m->get($form_id);
    
    $CI->load->model('option_m');
    $CI->fields = $CI->option_m->get_field_list($CI->data['lang_id']);
    
    $template_name = $CI->data['settings']['template'];
    
    
    if(!is_object($form))
    {
        echo('<pre>FORM MISSING</pre>');
        return;
    }
    
    $fields_value_json_1 = $form->fields_order_primary;
    $fields_value_json_1 = htmlspecialchars_decode($fields_value_json_1);

    $obj_widgets = json_decode($fields_value_json_1);

    if(is_object($obj_widgets->PRIMARY))
    foreach($obj_widgets->PRIMARY as $key=>$obj)
    {
        $title = '';
        $rel = $obj->type;
        $direction = 'NONE';
        if($obj->id != 'NONE')
        {
            if(isset($CI->fields[$obj->id]))
            {
                $title.='#'.$obj->id.', ';
                $title.=$CI->fields[$obj->id]->option;
                $rel = $CI->fields[$obj->id]->type.'_'.$obj->id;
                
                if($obj->direction != 'NONE')
                {
                    $direction = $obj->direction;
                    $title.=', '.$direction;
                    $rel.='_'.$obj->direction;
                }
            }
        }
        else
        {
            $title.=lang_check($obj->type);
        }
    
        if(!empty($title))
        {
            if($obj->type == 'C_PURPOSE' || $obj->type == 'SMART_SEARCH' || $obj->type == 'DATE_RANGE' || $obj->type == 'BREAKLINE')
            {
                if(file_exists(FCPATH.'templates/'.$template_name.'/form_fields/'.$obj->type.'.php'))
                {
                    echo $CI->load->view($template_name.'/form_fields/'.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj)), true);
                }
            }
            else
            {
                if(file_exists(FCPATH.'templates/'.$template_name.'/form_fields/'.$obj->type.'.php'))
                {
                    echo $CI->load->view($template_name.'/form_fields/'.$obj->type.'.php', array_merge($CI->data, array('field'=>$obj, 'field_data'=>$CI->fields[$obj->id])), true);
                }
                else
                {
                    echo 'MISSING TEMPLATE: '.$obj->type.'<br />';
                }
            }
        }
    }
    
    
}

function price_format($value, $lang_id=NULL)
{
    $CI =& get_instance();
    
    return $value;
}

function custom_number_format($value, $lang_id=NULL)
{
    $CI =& get_instance();
    
    $value = number_format($value, 2, '.', '');
    
    return $value;
}

function format_d($value)
{
    $CI =& get_instance();
    
    $value = date("m/d/y", strtotime($value));
    
    return $value;
}

function print_var($var, $var_name)
{
    if(is_array($var))
    {
        foreach($var as $key=>$value)
        {
            echo '$'.$var_name."['$key']='$value';<br />";
        }
    }
}

function _empty($var)
{
    return empty($var);
}

function search_value($field_id, $custom_return = NULL)
{
    $CI =& get_instance();
    
    if(!empty($CI->g_post_option[$field_id]))
    {
        if($custom_return !== NULL)
            return $custom_return;
        
        return $CI->g_post_option[$field_id];
    }        
    
    return '';
}

function _ch(&$var, $empty = '-')
{
    if(empty($var))
        return $empty;
        
    return $var;
}

function _che(&$var = NULL, $empty = '')
{
    if(empty($var))
        echo $empty;
        
    echo $var;
}

function flashdata_message()
{
    $CI =& get_instance();
    
    if($CI->session->flashdata('message'))
    {
        echo $CI->session->flashdata('message');
    }
}

function _simg($filename, $dim = '640x480')
{
    $filename = basename($filename);
    $filename = str_replace('%20', ' ', $filename);
    
    if(file_exists(FCPATH.'files/strict_cache/'.$dim.$filename))
    {
        return base_url('files/strict_cache/'.$dim.$filename);
    }
    
    if(!file_exists('files/'.$filename))
    {
        $filename = basename('admin-assets/img/no_image.jpg');
    }
    
    return base_url("strict_image.php?d=$dim&f=$filename");
}

function _generate_popup($estate_data, $json_output = false)
{
    $CI =& get_instance();
    
    //Get template settings
    $template_name = $CI->data['settings']['template'];
    
    //Load view
    if(file_exists(FCPATH.'templates/'.$template_name.'/widgets/map_popup.php'))
    {
        $output = $CI->load->view($template_name.'/widgets/map_popup.php', $estate_data, true);
        
        if($json_output)
        {
            $output = str_replace('"', '\"', $output);
            $output = str_replace(array("\n", "\r"), '', $output);
        }
        
        return $output;
    }
    else
    {
        return NULL;
    }
}

function _generate_results_item($estate_data, $json_output = false)
{
    $CI =& get_instance();
    
    //Get template settings
    $template_name = $CI->data['settings']['template'];
    
    //Load view
    if(file_exists(FCPATH.'templates/'.$template_name.'/widgets/results_item.php'))
    {
        $output = $CI->load->view($template_name.'/widgets/results_item.php', $estate_data, true);
        
        if($json_output)
        {
            $output = str_replace('"', '\"', $output);
            $output = str_replace(array("\n", "\r"), '', $output);
            return $output;
        }
        
        echo $output;
    }
    else
    {
        echo 'NOT FOUND: results_item.php';
    }
}


/**
 * _widget()
 * 
 * echo widget if exists or nothing
 * 
 * @param mixed $filename
 * @return
 */
function _widget($filename)
{
    $CI =& get_instance();
    
    if(file_exists(FCPATH.'templates/'.$CI->data['settings_template'].'/widgets/'.$filename.'.php'))
    {
        if(config_item('pseudo_varialbes_disabled') === TRUE)
        {
            $output = $CI->load->view($CI->data['settings_template'].'/widgets/'.$filename.'.php', $CI->data, TRUE);
        }
        else
        {
            $output = $CI->parser->parse($CI->data['settings_template'].'/widgets/'.$filename.'.php', $CI->data, TRUE);
        }
        
        echo $output;
    }
}

/**
 * _widget()
 * 
 * echo widget if exists or nothing
 * 
 * @param mixed $filename
 * @return
 */
function _subtemplate($subfolder = 'headers', $selected_header='empty')
{
    $CI =& get_instance();
    $filename = $selected_header;

    if(file_exists(FCPATH.'templates/'.$CI->data['settings_template'].'/'.$subfolder.'/'.$filename.'.php'))
    {
        $output = $CI->load->view($CI->data['settings_template'].'/'.$subfolder.'/'.$filename.'.php', $CI->data, TRUE);
        echo $output;
    }
}

function btn_view($uri)
{
	return anchor($uri, '<i class=" icon-search"></i> '.lang('view'), array('class'=>'btn btn-primary'));
}

function btn_view_curr($uri)
{
	return anchor($uri, '<i class=" icon-search"></i> '.lang('view_curr'), array('class'=>'btn btn-primary'));
}

function btn_view_sent($uri)
{
	return anchor($uri, '<i class=" icon-th-list"></i> '.lang('view_sent'), array('class'=>'btn btn btn-info'));
}

function btn_edit($uri)
{
	return anchor($uri, '<i class="icon-edit"></i> '.lang('edit'), array('class'=>'btn btn-primary'));
}

function btn_edit_invoice($uri)
{
	return anchor($uri, '<i class="icon-edit"></i> '.lang('edit_invoice'), array('class'=>'btn btn-primary'));
}

function btn_delete($uri)
{
	return anchor($uri, '<i class="icon-remove"></i> '.lang('delete'), array('onclick' => 'return confirm(\''.lang('Are you sure?').'\')', 'class'=>'btn btn-danger'));
}

function btn_delete_debit($uri)
{
	return anchor($uri, '<i class="icon-remove"></i> '.lang('delete_debit'), array('onclick' => 'return confirm(\''.lang('delete_debit?').'\')', 'class'=>'btn btn-danger'));
}

if ( ! function_exists('get_file_extension'))
{
    function get_file_extension($filepath)
    {
        return substr($filepath, strrpos($filepath, '.')+1);
    }
}

if ( ! function_exists('character_hard_limiter'))
{
    function character_hard_limiter($string, $max_len)
    {
        if(strlen($string)>$max_len)
        {
            return substr($string, 0, $max_len-3).'...';
        }
        
        return $string;
    }
}

function article_link($article){
	return 'article/' . intval($article->id) . '/' . e($article->slug);
}

function article_links($articles){
	$string = '<ul>';
	foreach ($articles as $article) {
		$url = article_link($article);
		$string .= '<li>';
		$string .= '<h3>' . anchor($url, e($article->title)) .  ' &rsaquo;</h3>';
		$string .= '<p class="pubdate">' . e($article->pubdate) . '</p>';
		$string .= '</li>';
	}
	$string .= '</ul>';
	return $string;
}

function get_excerpt($article, $numwords = 50){
	$string = '';
	$url = article_link($article);
	$string .= '<h2>' . anchor($url, e($article->title)) .  '</h2>';
	$string .= '<p class="pubdate">' . e($article->pubdate) . '</p>';
	$string .= '<p>' . e(limit_to_numwords(strip_tags($article->body), $numwords)) . '</p>';
	$string .= '<p>' . anchor($url, 'Read more &rsaquo;', array('title' => e($article->title))) . '</p>';
	return $string;
}

function limit_to_numwords($string, $numwords){
	$excerpt = explode(' ', $string, $numwords + 1);
	if (count($excerpt) >= $numwords) {
		array_pop($excerpt);
	}
	$excerpt = implode(' ', $excerpt);
	return $excerpt;
}

function e($string){
	return htmlentities($string);
}

function slug_url($uri, $model_name='')
{
    $slug_extension = '.htm';
    //$slug_extension = '';
    
    if(config_db_item('slug_enabled') === FALSE) return site_url($uri);
    $CI =& get_instance();
    $uri_exp = explode('/', $uri);
    
    $CI->load->model('slug_m');
    
    $def_lang_code = $CI->language_m->get_default();
    
    if($model_name == 'page_m' && count($uri_exp) > 1)
    {
        $model_lang_code = $uri_exp[0];
        $model_id = $uri_exp[1];
        
        $slug_data = $CI->slug_m->get_slug($model_name.'_'.$model_id.'_'.$model_lang_code);
        
        if($slug_data !== FALSE)
            return base_url().$slug_data->slug.$slug_extension;
    }
    else
    {
        // try autodetect $model_name
        $listing_uri = config_item('listing_uri');
        if(empty($listing_uri))$listing_uri = 'property';

        if($uri_exp[0] == $listing_uri)
        {
            //detected, property url
            $model_name = 'estate_m';
            $model_lang_code = $uri_exp[2];
            $model_id = $uri_exp[1];

            $slug_data = $CI->slug_m->get_slug($model_name.'_'.$model_id.'_'.$model_lang_code);
            
            if($slug_data !== FALSE)
                return base_url().$slug_data->slug.$slug_extension;
        }
        else if($uri_exp[0] == 'treefield')
        {
            //detected, property url
            $model_name = 'treefield_m';
            $model_lang_code = $uri_exp[1];
            $model_id = $uri_exp[2];

            $slug_data = $CI->slug_m->get_slug($model_name.'_'.$model_id.'_'.$model_lang_code);

            if($slug_data !== FALSE)
                return base_url().$slug_data->slug.$slug_extension;
        }
        else if($uri_exp[0] == 'profile')
        {
            $model_lang_code = $uri_exp[2];
            $model_id = $uri_exp[1];
            
            // fetch user username
            $user = $CI->user_m->get($model_id);
            
            if(isset($user->username))
            {
                $slug_data = $user->username;
            }
            
            if($def_lang_code != $model_lang_code)
            {
                $slug_data.='.'.$model_lang_code;
            }

            if(!empty($slug_data))
                return base_url().$slug_data.$slug_extension;
        }
    }
    
    return site_url($uri);
}

function get_menu ($array, $child = FALSE, $lang_code)
{
	$CI =& get_instance();
    
    if($CI->config->item('custom_menu') == 'saeedo')
    {
        return get_menu_saeedo($array, $child = FALSE, $lang_code);
    }
    
    if(isset($CI->data['settings_template']))
    {
        if($CI->data['settings_template'] == 'saeedo')
            return get_menu_saeedo($array, $child = FALSE, $lang_code);
    }

	$str = '';
    
    $first_page = $CI->page_m->get_first();
    $default_lang_code = $CI->language_m ->get_default();
    
    $is_logged_user = ($CI->user_m->loggedin() == TRUE);
	
	if (count($array)) {
		$str .= $child == FALSE ? '<ul class="nav navbar-nav nav-collapse collapse navbar-main" id="main-top-menu" role="navigation">' . PHP_EOL : '<ul class="dropdown-menu">' . PHP_EOL;
		$position = 0;
		foreach ($array as $key=>$item) {
		    if($item['is_visible'] == '0')
                continue;
          
			$position++;
            
            $active = $CI->uri->segment(2) == url_title_cro($item['id'], '-', TRUE) ? TRUE : FALSE;
            
            if($position == 1 && $child == FALSE){
                $item['navigation_title'] = '<img src="assets/img/home-icon.png" alt="'.$item['navigation_title'].'" />';
                
                if($CI->uri->segment(2) == '')
                    $active = TRUE;
            }
            
            if($item['is_private'] == '0' || $item['is_private'] == '1' && $is_logged_user)
			if (isset($item['children']) && count($item['children'])) {
			 
                $href = slug_url($lang_code.'/'.$item['id'].'/'.url_title_cro($item['navigation_title'], '-', TRUE), 'page_m');
                
                $target = '';
                
                if(substr($item['keywords'],0,4) == 'http')
                {
                    $href = $item['keywords'];
                    if(substr($item['keywords'],0,10) != substr(site_url(),0,10))
                    {
                        $target=' target="_blank"';
                    }
                }
                    
                if($item['keywords'] == '#')
                    $href = '#';
             
				$str .= $active ? '<li class="menuparent dropdown active">' : '<li class="menuparent dropdown">';
				$str .= '<a class="dropdown-toggle" data-toggle="dropdown" href="' . $href . '" '.$target.'>' . $item['navigation_title'];
				$str .= '<b class="caret"></b></a>' . PHP_EOL;
				$str .= get_menu($item['children'], TRUE, $lang_code);
                
			}
			else {
			 
                $href = slug_url($lang_code.'/'.$item['id'].'/'.url_title_cro($item['navigation_title'], '-', TRUE), 'page_m');
                
                if(is_object($first_page))
                    if($first_page->id == $item['id'] && $default_lang_code == $lang_code)
                    {
                        $href = base_url();
                    }
                    else if($first_page->id == $item['id'] && $default_lang_code != $lang_code)
                    {
                        $href = site_url($lang_code);
                    }

                $target = '';
                
                if(substr($item['keywords'],0,4) == 'http')
                {
                    $href = $item['keywords'];
                    if(substr($item['keywords'],0,10) != substr(site_url(),0,10))
                    {
                        $target=' target="_blank"';
                    }
                }
                    
                if($item['keywords'] == '#')
                    $href = '#';
             
				$str .= $active ? '<li class="active">' : '<li>';
				$str .= '<a href="' . $href . '" '.$target.'>' . $item['navigation_title'] . '</a>';
                
			}
			$str .= '</li>' . PHP_EOL;
		}
		
		$str .= '</ul>' . PHP_EOL;
	}
	
	return $str;
}

function get_menu_saeedo ($array, $child = FALSE, $lang_code)
{
	$CI =& get_instance();
	$str = '';
    $is_logged_user = ($CI->user_m->loggedin() == TRUE);
	
	if (count($array)) {
		$str .= $child == FALSE ? '<ul id="menu" class="menu nav navbar-nav">' . PHP_EOL : '<ul>' . PHP_EOL;
		$position = 0;
		foreach ($array as $key=>$item) {
			$position++;
            
            $active = $CI->uri->segment(2) == url_title_cro($item['id'], '-', TRUE) ? TRUE : FALSE;
            
            if($position == 1 && $child == FALSE){
                $item['navigation_title'] = '<i class="fa fa-home"></i> '.$item['navigation_title'].'';
                
                if($CI->uri->segment(2) == '')
                    $active = TRUE;
            }
            else if($child == FALSE)
            {
                $item['navigation_title'] = '<i class="fa "></i> '.$item['navigation_title'].''; 
            }
            
            if($item['is_visible'] == '1')
            if($item['is_private'] == '0' || $item['is_private'] == '1' && $is_logged_user)
			if (isset($item['children']) && count($item['children'])) {
			 
                $href = slug_url($lang_code.'/'.$item['id'].'/'.url_title_cro($item['navigation_title'], '-', TRUE), 'page_m');
                if(substr($item['keywords'],0,4) == 'http')
                    $href = $item['keywords'];
                    
                if($item['keywords'] == '#')
                    $href = '#';
             
				$str .= $active ? '<li>' : '<li>';
				$str .= '<a href="' . $href . '">' . $item['navigation_title'];
				$str .= '</a>' . PHP_EOL;
				$str .= get_menu_saeedo($item['children'], TRUE, $lang_code);
                
			}
			else {
			 
                $href = slug_url($lang_code.'/'.$item['id'].'/'.url_title_cro($item['navigation_title'], '-', TRUE), 'page_m');
                if(substr($item['keywords'],0,4) == 'http')
                    $href = $item['keywords'];
                    
                if($item['keywords'] == '#')
                    $href = '#';
             
				$str .= $active ? '<li>' : '<li>';
				$str .= '<a href="' . $href . '">' . $item['navigation_title'] . '</a>';
                
			}
			$str .= '</li>' . PHP_EOL;
		}
		
		$str .= '</ul>' . PHP_EOL;
	}
	
	return $str;
}

function get_menu_realia ($array, $child = FALSE, $lang_code)
{
	$CI =& get_instance();
	$str = '';
    
    $is_logged_user = ($CI->user_m->loggedin() == TRUE);
	
	if (count($array)) {
		$str .= $child == FALSE ? '<ul class="nav">' . PHP_EOL : '<ul>' . PHP_EOL;
		$position = 0;
		foreach ($array as $key=>$item) {
			$position++;
            
            $active = $CI->uri->segment(2) == url_title_cro($item['id'], '-', TRUE) ? TRUE : FALSE;
            
            if($position == 1 && $child == FALSE){
                //$item['navigation_title'] = '<img src="assets/img/home-icon.png" alt="'.$item['navigation_title'].'" />';
                
                if($CI->uri->segment(2) == '')
                    $active = TRUE;
            }
            
            if($item['is_visible'] == '1')
            if($item['is_private'] == '0' || $item['is_private'] == '1' && $is_logged_user)
			if (isset($item['children']) && count($item['children'])) {
			 
                $href = slug_url($lang_code.'/'.$item['id'].'/'.url_title_cro($item['navigation_title'], '-', TRUE), 'page_m');
                if(substr($item['keywords'],0,4) == 'http')
                    $href = $item['keywords'];
                    
                if($item['keywords'] == '#')
                    $href = '#';
             
				$str .= $active ? '<li class="menuparent">' : '<li class="menuparent">';
				$str .= '<span class="menuparent nolink">' . $item['navigation_title'];
				$str .= '</span>' . PHP_EOL;
				$str .= get_menu_realia($item['children'], TRUE, $lang_code);
                
			}
			else {
			 
                $href = slug_url($lang_code.'/'.$item['id'].'/'.url_title_cro($item['navigation_title'], '-', TRUE), 'page_m');
                if(substr($item['keywords'],0,4) == 'http')
                    $href = $item['keywords'];
                    
                if($item['keywords'] == '#')
                    $href = '#';
             
				$str .= $active ? '<li class="active">' : '<li>';
				$str .= '<a href="' . $href . '">' . $item['navigation_title'] . '</a>';
                
			}
			$str .= '</li>' . PHP_EOL;
		}
		
		$str .= '</ul>' . PHP_EOL;
	}
	
	return $str;
}

function get_lang_menu ($array, $lang_code, $extra_ul_attributes = '')
{
    $CI =& get_instance();
    
    if(count($array) == 1)
        return '';
    
    if(empty($CI->data['listing_uri']))
    {
        $listing_uri = 'property';
    }
    else
    {
        $listing_uri = $CI->data['listing_uri'];
    }
    
    $default_base_url = config_item('base_url');
    $default_lang_code = $CI->language_m ->get_default();
    $first_page = $CI->page_m->get_first();

    $str = '<ul '.$extra_ul_attributes.'>';
    foreach ($array as $item) {
        $active = $lang_code == $item['code'] ? TRUE : FALSE;
        
        $custom_domain_enabled=false;
        if(config_db_item('multi_domains_enabled') === TRUE)
        {
            if(!empty($item['domain']) && substr_count($item['domain'], 'http') > 0)
            {
                $custom_domain_enabled=true;
                $CI->config->set_item('base_url', $item['domain']);
            }
            else
            {
                $CI->config->set_item('base_url', $default_base_url);
            }
        }
        
        $flag_icon = '';
        
        if(isset($CI->data['settings_template']))
        {
            $template_name = $CI->data['settings_template'];
            if(file_exists(FCPATH.'templates/'.$template_name.'/assets/img/flags/'.$item['code'].'.png'))
            {
                $flag_icon = '&nbsp; <img src="'.'assets/img/flags/'.$item['code'].'.png" alt="" />';
            }
        }

        if($CI->uri->segment(1) == $listing_uri)
        {
            if($active)
            {
                $str.='<li class="'.$item['code'].' active">'.anchor($listing_uri.'/'.$CI->uri->segment(2).'/'.$item['code'], $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $str.='<li class="'.$item['code'].'" >'.anchor($listing_uri.'/'.$CI->uri->segment(2).'/'.$item['code'], $item['code'].$flag_icon).'</li>';
            }
        }
        else if($CI->uri->segment(1) == 'showroom')
        {
            if($active)
            {
                $str.='<li class="'.$item['code'].' active">'.anchor('showroom/'.$CI->uri->segment(2).'/'.$item['code'], $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $str.='<li class="'.$item['code'].'">'.anchor('showroom/'.$CI->uri->segment(2).'/'.$item['code'], $item['code'].$flag_icon).'</li>';
            }
        }
        else if($CI->uri->segment(1) == 'profile')
        {
            if($active)
            {
                $str.='<li class="'.$item['code'].' active">'.anchor(slug_url('profile/'.$CI->uri->segment(2).'/'.$item['code']), $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $str.='<li class="'.$item['code'].'">'.anchor(slug_url('profile/'.$CI->uri->segment(2).'/'.$item['code']), $item['code'].$flag_icon).'</li>';
            }
        }
        else if($CI->uri->segment(1) == 'propertycompare')
        {
            if($active)
            {
                $str.='<li class="'.$item['code'].' active">'.anchor(slug_url('propertycompare/'.$CI->uri->segment(2).'/'.$item['code']), $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $str.='<li class="'.$item['code'].'">'.anchor(slug_url('propertycompare/'.$CI->uri->segment(2).'/'.$item['code']), $item['code'].$flag_icon).'</li>';
            }
        }
        else if($CI->uri->segment(1) == 'treefield')
        {
            if($active)
            {
                $str.='<li class="'.$item['code'].' active">'.anchor(slug_url('treefield/'.$item['code'].'/'.$CI->uri->segment(3).'/'.$CI->uri->segment(4)), $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $str.='<li class="'.$item['code'].'">'.anchor(slug_url('treefield/'.$item['code'].'/'.$CI->uri->segment(3).'/'.$CI->uri->segment(4)), $item['code'].$flag_icon).'</li>';
            }
        }
        else if(is_numeric($CI->uri->segment(2)))
        {
            if($active)
            {
                $str.='<li class="'.$item['code'].' active">'.anchor(slug_url($item['code'].'/'.$CI->uri->segment(2), 'page_m'), $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $str.='<li class="'.$item['code'].'">'.anchor(slug_url($item['code'].'/'.$CI->uri->segment(2), 'page_m'), $item['code'].$flag_icon).'</li>';
            }
        }
        else if($CI->uri->segment(2) != '')
        {
            if($active)
            {
                $str.='<li class="'.$item['code'].' active">'.anchor($CI->uri->segment(1).'/'.$CI->uri->segment(2).'/'.$item['code'].'/'.$CI->uri->segment(4), $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $str.='<li class="'.$item['code'].'">'.anchor($CI->uri->segment(1).'/'.$CI->uri->segment(2).'/'.$item['code'].'/'.$CI->uri->segment(4), $item['code'].$flag_icon).'</li>';
            }
        }
        else
        {
            $url_lang_code = $item['code'];
            if($custom_domain_enabled)
            {
                $url_lang_code='';
            }
            else if($default_lang_code == $item['code'])
            {
                $url_lang_code = base_url();
            }
            
            if($active)
            {
                $str.='<li class="'.$item['code'].' active">'.anchor($url_lang_code, $item['code'].$flag_icon).'</li>';
            }
            else
            {
                $str.='<li class="'.$item['code'].'">'.anchor($url_lang_code, $item['code'].$flag_icon).'</li>';
            }
        }
    }
    $str.='</ul>';
    
    $CI->config->set_item('base_url', $default_base_url);
    
    return $str;
}

function treefield_sitemap($field_id, $lang_id, $view='text')
{
    if(!file_exists(APPPATH.'controllers/admin/treefield.php'))
    {
        return;
    }
    
    $CI =& get_instance();
    $CI->load->model('treefield_m');
    
    $lang_code = 'en';
    if(empty($CI->lang_code))
    {
        $lang_code = $CI->language_m->get_code($lang_id);
    }
    else
    {
        $lang_code = $CI->lang_code;
    }

    if($view == 'text')
    {
        $tree_listings = $CI->treefield_m->get_table_tree($lang_id, $field_id);
        
        foreach($tree_listings as $listing_item)
        {
            if(!empty($listing_item->template) && !empty($listing_item->body))
            {
                echo "<br />$listing_item->visual<a class='link_defined' href='".
                slug_url('treefield/'.$lang_code.'/'.$listing_item->id.'/'.url_title_cro($listing_item->value), 'treefield_m').
                "'>$listing_item->value</a>";
            }
            else
            {
                echo "<br />$listing_item->visual$listing_item->value";
            }
        }
    }
    else
    {
        $tree_listings = $CI->treefield_m->get_table_tree($lang_id, $field_id, NULL, false);
        
        echo_by_parent($tree_listings, 0, $field_id, $lang_code);
    }
}

function website_sitemap($field_id, $lang_id, $view='text')
{
    if(!file_exists(APPPATH.'controllers/admin/treefield.php'))
    {
        return;
    }
    
    $CI =& get_instance();
    $CI->load->model('page_m');
    
    $lang_code = 'en';
    if(empty($CI->lang_code))
    {
        $lang_code = $CI->language_m->get_code($lang_id);
    }
    else
    {
        $lang_code = $CI->lang_code;
    }

    if($view == 'text')
    {
       $tree_listings = $CI->page_m->get_nested($lang_id);
        foreach($tree_listings as $item)
        {
            if($item['is_visible']==1 )
            {
                $href = slug_url($lang_code.'/'.$item['id'].'/'.url_title_cro($item['navigation_title'], '-', TRUE), 'page_m');
                echo '<br /><a class="link_defined" href="'.$href.'">' . $item['title'] .'</a>';
            
                if (isset($item['children']) && count($item['children'])) {
                    foreach($item['children'] as $_item)
                    {
                        if($_item['is_visible']==1 )
                        { 
                            $href = slug_url($lang_code.'/'.$_item['id'].'/'.url_title_cro($_item['navigation_title'], '-', TRUE), 'page_m');
                            echo '<br /><a class="link_defined" href="'.$href.'">' . $_item['title'] .'</a>';
                        }
                    }
                }
            }
        }
    }
    else
    {
     $tree_listings = $CI->page_m->get_nested($lang_id);
     echo  get_menu_tree($tree_listings, $lang_code);
    }
}

function echo_by_parent($tree_listings, $id, $field_id, $lang_code)
{
    if(!isset($tree_listings[$id])) return;
    echo '<ul>';
    foreach($tree_listings[$id] as $key=>$listing_item)
    {
        $print_link = "$listing_item->value";
        if(!empty($listing_item->template) && !empty($listing_item->body))
        {
            $print_link = "<a class='link_defined' href='".
                          slug_url('treefield/'.$lang_code.'/'.$listing_item->id.'/'.url_title_cro($listing_item->value), 'treefield_m').
                          "'>$listing_item->value</a>";
        }
        
        echo '<li>';
        echo $print_link;
        echo_by_parent($tree_listings, $listing_item->id, $field_id, $lang_code);
        echo '</li>';
    }
    echo '</ul>';
}

function get_menu_tree($array,$lang_code='en', $child = FALSE)
{
	$str = '';
    	if (count($array)) {
    		$str .= $child == FALSE ? '<ul>' : '<ul>';
    		
    		foreach ($array as $item) {  
                    if($item['is_visible']!=1) continue;
                    
                      $href = slug_url($lang_code.'/'.$item['id'].'/'.url_title_cro($item['navigation_title'], '-', TRUE), 'page_m');
    			$str .= '<li>';
    			$str .= '<a class="link_defined" href="'.$href.'">' . $item['title'] .'</a>';
    			
                        // Do we have any children?
    			if (isset($item['children']) && count($item['children'])) {
    				$str .= get_menu_tree($item['children'],$lang_code, TRUE);
    			}
    			$str .= '</li>' . PHP_EOL;
    		}
    		$str .= '</ul>' . PHP_EOL;
    	}
    	
    	return $str;
}


function get_admin_menu($array)
{
    $CI =& get_instance();
    
    $str = '<ul class="nav">';
    foreach ($array as $item) {
        $active = $CI->uri->segment(1).'/'.$CI->uri->segment(2) == $item['uri'] ? TRUE : FALSE;
        
        if($active)
        {
            $str.='<li class="active">'.anchor($item['uri'], $item['title']).'</li>';
        }
        else
        {
            $str.='<li>'.anchor($item['uri'], $item['title']).'</li>';
        }
    }
    $str.='</ul>';
    
    return $str;
}

/**
* Dump helper. Functions to dump variables to the screen, in a nicley formatted manner.
* @author Joost van Veen
* @version 1.0
*/
if (!function_exists('dump')) {
    function dump ($var, $label = 'Dump', $echo = TRUE)
    {
        // Store dump in variable
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        
        // Add formatting
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">' . $label . ' => ' . $output . '</pre>';
        
        // Output
        if ($echo == TRUE) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}
 
 
if (!function_exists('dump_exit')) {
    function dump_exit($var, $label = 'Dump', $echo = TRUE)
    {
        dump ($var, $label, $echo);
        exit;
    }
}


if ( ! function_exists('get_ol'))
{
    function get_ol ($array, $child = FALSE)
    {
    	$str = '';
    	
    	if (count($array)) {
    		$str .= $child == FALSE ? '<ol class="sortable" id="option_sortable">' : '<ol>';
    		
    		foreach ($array as $item) {
    		  
                if($child == FALSE){
                    $item_children = null;
                    if(isset($item['children']))$item_children = $item['children'];
                    $item = $item['parent'];
                    if(isset($item_children))$item['children'] = $item_children;
                }
              
                $visible = '';
                if($item['visible'] == 1)
                    $visible = '<i class="icon-th-large"></i>';
                
                $locked='';
                if($item['is_hardlocked'])
                    $locked = '<i class="icon-lock" style="color:red;"></i>';
                else if($item['is_locked'] == 1)
                    $locked = '<i class="icon-lock"></i>';
                    
                $frontend='';
                if($item['is_frontend'] == 0)
                    $frontend = '<i class="icon-eye-close"></i>';
                    
                $required='';
                if($item['is_required'] == 1)
                    $required = '*';
                
                $icon = '';
                $CI =& get_instance();
                $template_name = $CI->data['settings']['template'];
                if(file_exists(FCPATH.'templates/'.$template_name.'/assets/img/icons/option_id/'.$item['id'].'.png'))
                {
                    $icon = '<img class="results-icon" src="'.base_url('templates/'.$template_name.'/assets/img/icons/option_id/'.$item['id'].'.png').'" alt="'.$item['option'].'"/>&nbsp;&nbsp;';
                }
                
    			$str .= '<li id="list_' . $item['id'] .'">';
    			$str .= '<div class="" alt="'.$item['id'].'" >#'.$item['id'].'&nbsp;&nbsp;&nbsp;'.$icon.$required.$item['option'].'&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-'.$item['color'].'">'.$item['type'].'</span>&nbsp;&nbsp;'.$visible.'&nbsp;&nbsp;'.$locked.'&nbsp;&nbsp;'.$frontend.'<span class="pull-right">
                            <div class="btn-group btn-group-xs">
                              <a class="btn btn-xs btn-primary" href="'.site_url('admin/estate/edit_option/'.$item['id']).'"><i class="icon-edit"></i></a>'.
                              ($item['is_locked']||$item['is_hardlocked']?'':'<a onclick="return confirm(\''.lang('Are you sure?').'\')" class="btn btn-xs btn-danger delete" data-loading-text="'.lang('Loading...').'" href="'.site_url('admin/estate/delete_option/'.$item['id']).'"><i class="icon-remove"></i></a>')
                            .'</div></span></div>';
    			
                // Do we have any children?
    			if (isset($item['children']) && count($item['children'])) {
    				$str .= get_ol($item['children'], TRUE);
    			}
    			
    			$str .= '</li>' . PHP_EOL;
    		}
    		
    		$str .= '</ol>' . PHP_EOL;
    	}
    	
    	return $str;
    }
}

if ( ! function_exists('get_ol_pages'))
{
    function get_ol_pages ($array, $child = FALSE)
    {
    	$str = '';
    	
    	if (count($array)) {
    		$str .= $child == FALSE ? '<ol class="sortable" id="page_sortable" rel="2">' : '<ol>';
    		
    		foreach ($array as $item) {  

    			$str .= '<li id="list_' . $item['id'] .'">';
    			$str .= '<div class="" alt="'.$item['id'].'" ><i class="icon-file-alt"></i>&nbsp;&nbsp;' . $item['title'] .'&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-warning">'.$item['template'].'</span>';
                if($item['type'] == 'ARTICLE')
                    $str .= '&nbsp;<span class="label label-info">'.lang_check($item['type']).'</span>';
                $str .= '<span class="pull-right">
                            <div class="btn-group btn-group-xs">
                              <a class="btn btn-xs btn-primary" href="'.site_url('admin/page/edit/'.$item['id']).'"><i class="icon-edit"></i></a>
                              <a onclick="return confirm(\''.lang('Are you sure?').'\')" class="btn btn-xs btn-danger delete" data-loading-text="'.lang('Loading...').'" href="'.site_url('admin/page/delete/'.$item['id']).'"><i class="icon-remove"></i></a>
                            </div></span></div>';
    			
                // Do we have any children?
    			if (isset($item['children']) && count($item['children'])) {
    				$str .= get_ol_pages($item['children'], TRUE);
    			}
    			
    			$str .= '</li>' . PHP_EOL;
    		}
    		
    		$str .= '</ol>' . PHP_EOL;
    	}
    	
    	return $str;
    }
}

if ( ! function_exists('get_ol_pages_tree'))
{
    function get_ol_pages_tree ($array, $parent_id = 0, $custom_templates_names = array())
    {
    	$str = '';
    	
    	if (count($array)) {
    		$str .= $parent_id == 0 ? '<ol class="sortable" id="page_sortable" rel="3">' : '<ol>';

    		foreach ($array[$parent_id] as $k_parent_id => $item) {  
    		  
                if(isset($custom_templates_names[$item['template']]))
                {
                    $item['template'] = $custom_templates_names[$item['template']];
                }
              
    			$str .= '<li id="list_' . $item['id'] .'" rel='.$parent_id.'>';
    			$str .= '<div class="" alt="'.$item['id'].'" ><i class="icon-file-alt"></i>&nbsp;&nbsp;' . $item['title'] .'&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-warning">'.$item['template'].'</span>';
                if($item['type'] == 'ARTICLE')
                    $str .= '&nbsp;<span class="label label-info">'.lang_check($item['type']).'</span>';
                if($item['is_visible'] == '0')
                    $str .= '&nbsp;&nbsp;<i class="icon-eye-close"></i>';
                    
                $str .= '<span class="pull-right">
                            <div class="btn-group btn-group-xs">
                              <a class="btn btn-xs btn-primary" href="'.site_url('admin/page/edit/'.$item['id']).'"><i class="icon-edit"></i></a>
                              <a onclick="return confirm(\''.lang('Are you sure?').'\')" class="btn btn-xs btn-danger delete" data-loading-text="'.lang('Loading...').'" href="'.site_url('admin/page/delete/'.$item['id']).'"><i class="icon-remove"></i></a>
                            </div></span></div>';
    			
                // Do we have any children?
    			if (isset($array[$k_parent_id])) {
    				$str .= get_ol_pages_tree($array, $k_parent_id, $custom_templates_names);
    			}
    			
    			$str .= '</li>' . PHP_EOL;
    		}
    		
    		$str .= '</ol>' . PHP_EOL;
    	}
    	
    	return $str;
    }
}

if ( ! function_exists('get_ol_news'))
{
    function get_ol_news ($array, $child = FALSE)
    {
    	$str = '';
    	
    	if (count($array)) {
    		$str .= $child == FALSE ? '<ol class="sortable" id="page_sortable" rel="2">' : '<ol>';
    		
    		foreach ($array as $item) {                
    			$str .= '<li id="list_' . $item['id'] .'">';
    			$str .= '<div class="" alt="'.$item['id'].'" ><i class="icon-file-alt"></i>&nbsp;&nbsp;' . $item['title'] .'&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-warning">'.$item['template'].'</span><span class="pull-right">
                            <div class="btn-group btn-group-xs">
                              <a class="btn btn-xs btn-success" href="'.site_url('admin/news/index/'.$item['id']).'"><i class="icon-list"></i></a>
                              <a class="btn btn-xs btn-primary" href="'.site_url('admin/news/edit_category/'.$item['id']).'"><i class="icon-edit"></i></a>
                              <a onclick="return confirm(\''.lang('Are you sure?').'\')" class="btn btn-xs btn-danger delete" data-loading-text="'.lang('Loading...').'" href="'.site_url('admin/news/delete/'.$item['id']).'"><i class="icon-remove"></i></a>
                            </div></span></div>';
    			
                // Do we have any children?
    			if (isset($item['children']) && count($item['children'])) {
    				$str .= get_ol_news($item['children'], TRUE);
    			}
    			
    			$str .= '</li>' . PHP_EOL;
    		}
    		
    		$str .= '</ol>' . PHP_EOL;
    	}
    	
    	return $str;
    }
}

if ( ! function_exists('get_ol_showroom_tree'))
{
    function get_ol_showroom_tree ($array, $parent_id=0)
    {        
    	$str = '';
    	
    	if (count($array)) {
    		$str .= $parent_id == 0 ? '<ol class="sortable" id="showroom_sortable" rel="2">' : '<ol>';

    		foreach ($array[$parent_id] as $k_parent_id => $item) {  
    			$str .= '<li id="list_' . $item['id'] .'" rel='.$parent_id.'>';
    			$str .= '<div class="" alt="'.$item['id'].'" ><i class="icon-file-alt"></i>&nbsp;&nbsp;' . $item['title'];
                //if($item['type'] == 'ARTICLE')
                //    $str .= '&nbsp;<span class="label label-info">'.lang_check($item['type']).'</span>';
                $str .= '<span class="pull-right">
                            <div class="btn-group btn-group-xs">
                              <a class="btn btn-xs btn-success" href="'.site_url('admin/showroom/index/'.$item['id']).'"><i class="icon-list"></i></a>
                              <a class="btn btn-xs btn-primary" href="'.site_url('admin/showroom/edit_category/'.$item['id']).'"><i class="icon-edit"></i></a>
                              <a onclick="return confirm(\''.lang('Are you sure?').'\')" class="btn btn-xs btn-danger delete" data-loading-text="'.lang('Loading...').'" href="'.site_url('admin/showroom/delete/'.$item['id']).'"><i class="icon-remove"></i></a>
                            </div></span></div>';
    			
                // Do we have any children?
    			if (isset($array[$k_parent_id])) {
    				$str .= get_ol_showroom_tree($array, $k_parent_id);
    			}
    			
    			$str .= '</li>' . PHP_EOL;
    		}
    		
    		$str .= '</ol>' . PHP_EOL;
    	}
    	
    	return $str;
    }
}

if ( ! function_exists('get_ol_expert_tree'))
{
    function get_ol_expert_tree ($array, $parent_id=0)
    {        
    	$str = '';
    	
    	if (count($array)) {
    		$str .= $parent_id == 0 ? '<ol class="sortable" id="expert_sortable" rel="2">' : '<ol>';

    		foreach ($array[$parent_id] as $k_parent_id => $item) {  
    			$str .= '<li id="list_' . $item['id'] .'" rel='.$parent_id.'>';
    			$str .= '<div class="" alt="'.$item['id'].'" ><i class="icon-file-alt"></i>&nbsp;&nbsp;' . $item['question'];
                //if($item['type'] == 'ARTICLE')
                //    $str .= '&nbsp;<span class="label label-info">'.lang_check($item['type']).'</span>';
                $str .= '<span class="pull-right">
                            <div class="btn-group btn-group-xs">
                              <a class="btn btn-xs btn-success" href="'.site_url('admin/expert/index/'.$item['id']).'"><i class="icon-list"></i></a>
                              <a class="btn btn-xs btn-primary" href="'.site_url('admin/expert/edit_category/'.$item['id']).'"><i class="icon-edit"></i></a>
                              <a onclick="return confirm(\''.lang('Are you sure?').'\')" class="btn btn-xs btn-danger delete" data-loading-text="'.lang('Loading...').'" href="'.site_url('admin/expert/delete/'.$item['id']).'"><i class="icon-remove"></i></a>
                            </div></span></div>';
    			
                // Do we have any children?
    			if (isset($array[$k_parent_id])) {
    				$str .= get_ol_showroom_tree($array, $k_parent_id);
    			}
    			
    			$str .= '</li>' . PHP_EOL;
    		}
    		
    		$str .= '</ol>' . PHP_EOL;
    	}
    	
    	return $str;
    }
}

function calculateCenter($object_locations) 
{
    $minlat = false;
    $minlng = false;
    $maxlat = false;
    $maxlng = false;

    foreach ($object_locations as $estate) {
         $geolocation = array();
         
         $gps_string_explode = array();
         if(is_array($estate))
         {
            $gps_string_explode = explode(', ', $estate['gps']);
         }
         else
         {
            $gps_string_explode = explode(', ', $estate->gps);
         }

         if(!isset($gps_string_explode[1]) && isset($estate->lat))
         {
            $gps_string_explode[0] = $estate->lat;
            $gps_string_explode[1] = $estate->lng;
         }

         if(count($gps_string_explode)>1)
         {
             $geolocation['lat'] = $gps_string_explode[0];
             $geolocation['lon'] = $gps_string_explode[1];
             
             if ($minlat === false) { $minlat = $geolocation['lat']; } else { $minlat = ($geolocation['lat'] < $minlat) ? $geolocation['lat'] : $minlat; }
             if ($maxlat === false) { $maxlat = $geolocation['lat']; } else { $maxlat = ($geolocation['lat'] > $maxlat) ? $geolocation['lat'] : $maxlat; }
             if ($minlng === false) { $minlng = $geolocation['lon']; } else { $minlng = ($geolocation['lon'] < $minlng) ? $geolocation['lon'] : $minlng; }
             if ($maxlng === false) { $maxlng = $geolocation['lon']; } else { $maxlng = ($geolocation['lon'] > $maxlng) ? $geolocation['lon'] : $maxlng; }
         }
    }

    // Calculate the center
    $lat = $maxlat - (($maxlat - $minlat) / 2);
    $lon = $maxlng - (($maxlng - $minlng) / 2);

    return $lat.', '.$lon;
}

function calculateCenterArray($array_locations) 
{
    if(count($array_locations) == 0)
        return array(0,0);
    
    $minlat = false;
    $minlng = false;
    $maxlat = false;
    $maxlng = false;
    
    if(is_object($array_locations[0]))
    foreach ($array_locations as $estate) {
         $geolocation = array();
         $gps_string_explode = explode(', ', $estate->gps);
         
         if(count($gps_string_explode)>1)
         {
             $geolocation['lat'] = $gps_string_explode[0];
             $geolocation['lon'] = $gps_string_explode[1];
             
             if ($minlat === false) { $minlat = $geolocation['lat']; } else { $minlat = ($geolocation['lat'] < $minlat) ? $geolocation['lat'] : $minlat; }
             if ($maxlat === false) { $maxlat = $geolocation['lat']; } else { $maxlat = ($geolocation['lat'] > $maxlat) ? $geolocation['lat'] : $maxlat; }
             if ($minlng === false) { $minlng = $geolocation['lon']; } else { $minlng = ($geolocation['lon'] < $minlng) ? $geolocation['lon'] : $minlng; }
             if ($maxlng === false) { $maxlng = $geolocation['lon']; } else { $maxlng = ($geolocation['lon'] > $maxlng) ? $geolocation['lon'] : $maxlng; }
        
         }
    }
    
    if(is_array($array_locations[0]))
    foreach ($array_locations as $estate) {
         $geolocation = array();
         $gps_string_explode = explode(', ', $estate['gps']);
         
         if(count($gps_string_explode)>1)
         {
             $geolocation['lat'] = $gps_string_explode[0];
             $geolocation['lon'] = $gps_string_explode[1];
             
             if ($minlat === false) { $minlat = $geolocation['lat']; } else { $minlat = ($geolocation['lat'] < $minlat) ? $geolocation['lat'] : $minlat; }
             if ($maxlat === false) { $maxlat = $geolocation['lat']; } else { $maxlat = ($geolocation['lat'] > $maxlat) ? $geolocation['lat'] : $maxlat; }
             if ($minlng === false) { $minlng = $geolocation['lon']; } else { $minlng = ($geolocation['lon'] < $minlng) ? $geolocation['lon'] : $minlng; }
             if ($maxlng === false) { $maxlng = $geolocation['lon']; } else { $maxlng = ($geolocation['lon'] > $maxlng) ? $geolocation['lon'] : $maxlng; }
        
         }
    }

    // Calculate the center
    $lat = $maxlat - (($maxlat - $minlat) / 2);
    $lon = $maxlng - (($maxlng - $minlng) / 2);

    return array($lat, $lon);
}

function lang_check($line, $id = '')
{
	$r_line = lang($line, $id);

    if(empty($r_line))
        $r_line = $line;
    
	return $r_line;
}

function _l($line, $id = '')
{
    echo lang_check($line, $id);
}

function check_set($test, $default)
{
    if(isset($test))
        return $test;
        
    return $default;
}

function check_combine_set($main, $test, $default)
{
    if(count(explode(',', $main)) == count(explode(',', $test)) && 
       count(explode(',', $main)) > 0 && count(explode(',', $test)) > 0)
    {
        return $main;
    }

    return $default;
}

/* Extra simple acl implementation */
function check_acl($uri_for_check = NULL)
{
    $CI =& get_instance();
    $user_type = $CI->session->userdata('type');
    $acl_config = $CI->acl_config;
    //echo $CI->uri->uri_string();
    //echo $user_type;
    
    if($uri_for_check !== NULL)
    {
        if(in_array($uri_for_check, $acl_config[$user_type]))
        {
            return true;
        }
        
        $uri_for_check_explode = explode('/', $uri_for_check);
        if(in_array($uri_for_check_explode[0], $acl_config[$user_type]))
        {
            return true;
        }
        
        return false;
    }
    
    if(in_array($CI->uri->segment(2), $acl_config[$user_type]))
    {
        return true;
    }
    
    if(in_array($CI->uri->segment(2).'/index', $acl_config[$user_type]) && $CI->uri->segment(3) == '')
    {
        return true;
    }
    
    if(in_array($CI->uri->segment(2).'/'.$CI->uri->segment(3), $acl_config[$user_type]))
    {
        return true;
    }
    
    return false;
}

if ( ! function_exists('return_value'))
{
    function return_value($array, $key, $default='')
    {
        if(isset($array[$key]))
        {
            return $array[$key];
        }
        
        return $default;
    }
}

if ( ! function_exists('return_value_nempty'))
{
    function return_value_nempty($array, $key, $default='')
    {
        if(isset($array[$key]) && !empty($array[$key]))
        {
            return $array[$key];
        }
        
        return $default;
    }
}

/**
* Returns the specified config item
*
* @access	public
* @return	mixed
*/
if ( ! function_exists('config_db_item'))
{
	function config_db_item($item)
	{
		static $_config_item = array();
        static $_db_settings = array();

		if ( ! isset($_config_item[$item]))
		{
			$config =& get_config();
            
            // [check-database]
            if(count($_db_settings) == 0)
            {
                $CI =& get_instance();
                $CI->load->model('masking_m');
                $CI->load->model('settings_m');
                $_db_settings = $CI->settings_m->get_fields();
            }

            if(isset($_db_settings[$item]))
            {
                $_config_item[$item] = $_db_settings[$item];
                return $_config_item[$item];
            }
            // [/check-database]
            
			if ( ! isset($config[$item]))
			{
				return FALSE;
			}
			$_config_item[$item] = $config[$item];
		}

		return $_config_item[$item];
	}
}

if ( ! function_exists('map_event'))
{
	function map_event()
	{
		if(config_db_item('map_event') == 'mouseover')
        {
            return 'mouseover';
        }
        
        return 'click';
	}
}

function check_language_writing_permissions($template_folder)
{
    $write_error = '';

    if(!is_writable(FCPATH.'templates/'.$template_folder.'/language/'))
    {
        $write_error.='Folder templates/'.$template_folder.'/language/ is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'application/language/'))
    {
        $write_error.='Folder application/language/ is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'system/language/'))
    {
        $write_error.='Folder system/language/ is not writable<br />';
    }
    
    return $write_error;
}

function check_template_writing_permissions($template_folder)
{
    $write_error = '';

    if(!is_writable(FCPATH.'templates/'.$template_folder))
    {
        $write_error.='Folder templates/'.$template_folder.' is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'templates/'.$template_folder.'/widgets/'))
    {
        $write_error.='Folder templates/'.$template_folder.'/widgets/ is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'templates/'.$template_folder.'/config/'))
    {
        $write_error.='Folder templates/'.$template_folder.'/config/ is not writable<br />';
    }
    
    return $write_error;
}

function check_email_writing_permissions($template_folder)
{
    $write_error = '';
    
    if(!is_writable(APPPATH.'views/email/'))
    {
        $write_error.='File application/views/email is not writable<br />';
    }

    return $write_error;
}

function check_global_writing_permissions()
{
    $write_error = '';
    
    if(!is_writable(APPPATH.'config/cms_config.php'))
    {
        $write_error.='File application/config/cms_config.php is not writable<br />';
    }
    
    if(!is_writable(APPPATH.'config/production/database.php'))
    {
        $write_error.='File application/config/production/database.php is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'files/'))
    {
        $write_error.='Folder files/ is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'backups/'))
    {
        $write_error.='Folder backups/ is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'files/captcha/'))
    {
        $write_error.='Folder files/captcha/ is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'files/strict_cache/'))
    {
        $write_error.='Folder files/strict_cache/ is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'files/thumbnail/'))
    {
        $write_error.='Folder files/thumbnail/ is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'application/language/'))
    {
        $write_error.='Folder application/language/ is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'system/language/'))
    {
        $write_error.='Folder system/language/ is not writable<br />';
    }
    
    if(!is_writable(FCPATH.'sitemap.xml'))
    {
        $write_error.='File sitemap.xml is not writable<br />';
    }
    
    return $write_error;
}


function print_breadcrump ($lang_id=NULL, $delimiter=' > ', $extra_ul_attributes = 'class="breadcrumb pull-left"') {
    $breadcrump_parts=array();
    $page='';
    $str='<ul '.$extra_ul_attributes.'>';
    
    $CI =& get_instance();
    $CI->load->model('page_m');
    
    // define lang id
    if($lang_id==null)
        $lang_id=$CI->data['lang_id'];
    
    $lang_code = 'en';
    if(empty($CI->lang_code))
    {
        $lang_code = $CI->language_m->get_code($lang_id);
    }
    else
    {
        $lang_code = $CI->lang_code;
    }
    
    
        $field_title='navigation_title_'.$lang_id;
        if(!empty($CI->data['page_id']))
        {
            $page_id=$CI->data['page_id'];
            $page=$CI->page_m->get_lang($page_id);
        }
    
    // if not frontend controller
        if($CI->uri->segment(1) == 'property')
        {
            /*
            $page=$CI->page_m->get_lang($page_id);
            $href=slug_url('property/'.$CI->uri->segment(2).'/'.$lang_code.'/'.url_title_cro($CI->data['page_title'], '-', TRUE), 'page_m');
            $_str="<a href='$href'>";
            $_str.=$CI->data['page_title'];
            $_str.='</a>';
            array_unshift($breadcrump_parts,$_str);
            */
            
            //property
            //$href= site_url($lang_code);
            $_str="<span>";
            $_str.=lang_check('Property preview');
            $_str.='</span>';
            array_unshift($breadcrump_parts,$_str);
            
        }
        else if($CI->uri->segment(1) == 'showroom')
        {
                        
            $_str="<span>";
            $_str.=$CI->data['page_title'];
            $_str.='</span>';
            array_unshift($breadcrump_parts,$_str);
            
            // showroom id
            $page_id=160;
        }
        else if($CI->uri->segment(1) == 'profile')
        {
            $_str="<span>";
            $_str.=$CI->data['page_title'];
            $_str.='</span>';
            array_unshift($breadcrump_parts,$_str);
            
            // showroom id
            $page_id=169;
        }
        else if($CI->uri->segment(1) == 'propertycompare')
        {
        }
        else if($CI->uri->segment(1) == 'treefield')
        {
        }
        else if($CI->uri->segment(1) == 'frontend' || $CI->uri->segment(1) == 'fresearch' || $CI->uri->segment(1) == 'fmessages' || $CI->uri->segment(1) == 'ffavorites')
        {
            //property
            $_str="<span>";
            $_str.=lang_check('My properties');
            $_str.='</span>';
            array_unshift($breadcrump_parts,$_str);
        }
        else if(!empty($page)&&$page->type == 'MODULE_NEWS_POST')
        {
            $_str="<span>";
            $_str.=$CI->data['page_title'];
            $_str.='</span>';
            array_unshift($breadcrump_parts,$_str);
            
            // page news id
            $page_id=142;
        }
        else if(!empty($page)&&$page->type == 'ARTICLE')
        {
            $_str="<span>";
            $_str.=$CI->data['page_title'];
            $_str.='</span>';
            array_unshift($breadcrump_parts,$_str);
            
            // page article
            $page_id=157;
        }
        else
        {

        }
        
        
    if(!empty($page)&& $page_id!=1){
       do {
           $page=$CI->page_m->get_lang($page_id);

           $_str="<span>";
           $_str.=$page->$field_title;
           $_str.='</span>';
           array_unshift($breadcrump_parts,$_str);

       } while (!empty($page_id=$page->parent_id));
    }   
        
    // add homepage
    $_page=$CI->page_m->get_first();
    $href= site_url($lang_code);
    $_page=$CI->page_m->get_lang($_page->id);
    
    $_str="<a href='$href'>";
    $_str.=$_page->$field_title;
    $_str.='</a>';
    array_unshift($breadcrump_parts,$_str);
    
    $breadcrump_parts[0] ='<li>'. $breadcrump_parts[0];
    $breadcrump_parts[count($breadcrump_parts)-1].= '</li>';
    
    $str.= implode('<span class="delimiter">'.$delimiter.'</span>'.'</li><li>', $breadcrump_parts);
    $str.= '</ul>';
    return $str;
}
