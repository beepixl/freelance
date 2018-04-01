<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// ------------------------------------------------------------------------

/**
 * Text Input Field
 *
 * @access	public
 * @param	mixed
 * @param	string
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_number'))
{
	function form_number($data = '', $value = '', $extra = '')
	{
		$defaults = array('type' => 'number', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

		return "<input "._parse_form_attributes($data, $defaults).$extra." />";
	}
}

// ------------------------------------------------------------------------

/**
 * Form Value
 *
 * Grabs a value from the POST array for the specified field so you can
 * re-populate an input field or textarea.  If Form Validation
 * is active it retrieves the info from the validation class
 *
 * @access	public
 * @param	string
 * @return	mixed
 */
if ( ! function_exists('set_value_GET'))
{
	function set_value_GET($field = '', $default = '', $skip_valdation = FALSE)
	{
		if (FALSE === ($OBJ =& _get_validation_object()))
		{
			if ( ! isset($_GET[$field]))
			{
				return $default;
			}

			return form_prep($_GET[$field], $field);
		}
        
        if($skip_valdation)
        {
			if (!empty($_GET[$field]))
			{
			    $CI =& get_instance(); 
				return $CI->input->get($field);
			}
        }
        
		return form_prep($OBJ->set_value($field, $default), $field);
	}
}

if ( ! function_exists('regenerate_query_string'))
{
	function regenerate_query_string($enable_fields = array())
	{
		$CI =& get_instance();
        $check_fields = (count($enable_fields) > 0);
        $_GET_clone = $_GET;

        $gen_text = '';
        if(count($_GET_clone) > 0) foreach($_GET_clone as $field=>$value)
        {
            if($check_fields && !in_array($field, $enabled_fields))
            {
                continue;
            }
            
            $s_value = $CI->input->get($field);
            
            if(!empty($s_value))
            {
                $gen_text.=$field.'='.$s_value.'&amp;';
            }
        }
        
        if(!empty($gen_text))
            $gen_text = substr($gen_text, 0, strlen($gen_text)-5);

        return $gen_text;
	}
}

if ( ! function_exists('prepare_search_query_GET'))
{
	function prepare_search_query_GET($enabled_fields = array(), $smart_fields = array())
	{
		$CI =& get_instance();
        $check_fields = (count($enabled_fields) > 0);
        $_GET_clone = $_GET;
        
        $smart_search = '';
        if(isset($_GET_clone['smart_search']))
        $smart_search = $CI->input->get('smart_search');
        
        if(count($_GET_clone) > 0)
        {
            unset($_GET_clone['smart_search']);
            
            if(count($smart_fields) > 0 && !empty($smart_search))
            {
                $gen_q = '';
                foreach($smart_fields as $key=>$value)
                {
                    if($value == 'id' && is_numeric($smart_search))
                    {
                        $gen_q.="$value = $smart_search OR ";
                    }
                    else
                    {
                        $gen_q.="$value LIKE '%$smart_search%' OR ";
                    }
                }
                $gen_q = substr($gen_q, 0, -4);
                
                $CI->db->where("($gen_q)");
            }

            if(count($_GET_clone) > 0) foreach($_GET_clone as $field=>$value)
            {
                if($check_fields && !in_array($field, $enabled_fields))
                {
                    break;
                }
                
                $s_value = $CI->input->get($field);
                
                
                if(strpos($field, '_from') > 0)
                {
                    $field = substr($field, 0, -5);
                    if(!empty($s_value))
                    {
                        if(is_numeric($s_value))
                        {
                            $CI->db->where($field.' >', $s_value);
                        }
                    }
                }
                elseif(strpos($field, '_to') > 0)
                {
                    $field = substr($field, 0, -3);
                    if(!empty($s_value))
                    {
                        if(is_numeric($s_value))
                        {
                            $CI->db->where($field.' <', $s_value);
                        }
                    }
                }
                else
                {
                    if(!empty($s_value))
                    {
                        if($field == 'id' && is_numeric($s_value))
                        {
                            $CI->db->where($field, $s_value);
                        }
                        else
                        {
                            $CI->db->like($field, $s_value);
                        }
                    }
                }
            }
        }

	}
}

if ( ! function_exists('upload_field_admin'))
{
	function upload_field_admin($field_name, $label, $label_size=2, $value, $addition_message='')
	{
?>
                                            <div class="form-group UPLOAD-FIELD-TYPE">
                                              <label class="col-lg-<?php echo $label_size; ?> control-label">
                                              <?php _l($label); ?>
                                              <div class="ajax_loading"> </div>
                                              </label>
                                              <div class="col-lg-<?php echo intval(12-$label_size); ?>">
<div class="field-row hidden">
<?php echo form_input($field_name, set_value($field_name, isset($value)?$value:'SKIP_ON_EMPTY'), 'class="form-control skip-input" id="'.$field_name.'" placeholder="'.$label.'"')?>
</div>
<?php if( empty($value) ): ?>
<span class="label label-danger"><?php echo lang('After saving, you can add files and images');?></span>
<?php else: ?>
<!-- Button to select & upload files -->
<span class="btn btn-success fileinput-button">
    <span>Select files...</span>
    <!-- The file input field used as target for the file upload widget -->
    <input id="fileupload_<?php echo $field_name; ?>" class="FILE_UPLOAD file_<?php echo $field_name; ?>" type="file" name="files[]" multiple>
</span>
<?php 
$_addition_message='';
if(isset($value)){
    $rep_id = $value;
    $CI =& get_instance();
    //Fetch repository
    $file_rep = $CI->file_m->get_by(array('repository_id'=>$rep_id));
    if(count($file_rep)) 
        $_addition_message = $addition_message;
}
?>
<span id="additional-messag_<?php echo $field_name; ?>" class="fileupload-additional-message">
    <span><?php echo $_addition_message;?></span>
</span> 

<br style="clear: both;" />
<!-- The global progress bar -->
<p>Upload progress</p>
<div id="progress_<?php echo $field_name; ?>" class="progress progress-success progress-striped">
    <div class="bar"></div>
</div>
<!-- The list of files uploaded -->
<p>Files uploaded:</p>
<ul id="files_<?php echo $field_name; ?>">
<?php 
if(isset($value)){
    $rep_id = $value;
    $CI =& get_instance();
    
    //Fetch repository
    $file_rep = $CI->file_m->get_by(array('repository_id'=>$rep_id));
    if(count($file_rep)) foreach($file_rep as $file_r)
    {
        $delete_url = site_url_q('files/upload/rep_'.$file_r->repository_id, '_method=DELETE&amp;file='.rawurlencode($file_r->filename));
        
        echo "<li><a target=\"_blank\" href=\"".base_url('files/'.$file_r->filename)."\">$file_r->filename</a>".
             '&nbsp;&nbsp;<button class="btn btn-xs btn-danger" data-type="POST" data-url='.$delete_url.'><i class="icon-trash icon-white"></i></button></li>';
    }
}
?>
</ul>

<!-- JavaScript used to call the fileupload widget to upload files -->
<script language="javascript">
// When the server is ready...
$( document ).ready(function() {
    
    // Define the url to send the image data to
    var url_<?php echo $field_name; ?> = '<?php echo site_url('files/upload_settings/'.$field_name);?>';
    
    // Call the fileupload widget and set some parameters
    $('#fileupload_<?php echo $field_name; ?>').fileupload({
        url: url_<?php echo $field_name; ?>,
        autoUpload: true,
        dropZone: $('#fileupload_<?php echo $field_name; ?>'),
        dataType: 'json',
        done: function (e, data) {
            // Add each uploaded file name to the #files list
            $.each(data.result.files, function (index, file) {
                if(!file.hasOwnProperty("error"))
                {
                    $('#files_<?php echo $field_name; ?>').append('<li><a href="'+file.url+'" target="_blank">'+file.name+'</a>&nbsp;&nbsp;<button class="btn btn-xs btn-danger" data-type="POST" data-url='+file.delete_url+'><i class="icon-trash icon-white"></i></button></li>');
                    added=true;
                    if($('#additional-messag_<?php echo $field_name; ?>').length) {
                        $('#additional-messag_<?php echo $field_name; ?> > span').html("<?php echo $addition_message;?>");
                    }
                }
                else
                {
                    ShowStatus.show(file.error);
                }

            });
            
            //console.log(data.result.repository_id);
            //console.log('<?php echo '#'.$field_name; ?>');
            $('<?php echo '#'.$field_name; ?>').attr('value', data.result.repository_id);
            
            reset_events_<?php echo $field_name; ?>();
        },
        destroyed: function (e, data) {
            $.fn.endLoading();
            <?php if(config_item('app_type') != 'demo'):?>
            if(data.success)
            {
            }
            else
            {
                ShowStatus.show('<?php echo lang_check('Unsuccessful, possible permission problems or file not exists'); ?>');
            }
            <?php endif;?>
            return false;
        },
        <?php if(config_item('app_type') == 'demo'):?>
        added: function (e, data) {
            $.fn.endLoading();
            return false;
        },
        <?php endif;?>
        progressall: function (e, data) {
            // Update the progress bar while files are being uploaded
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress_<?php echo $field_name; ?> .bar').css(
                'width',
                progress + '%'
            );
        }
    });
    
    reset_events_<?php echo $field_name; ?>();
});

function reset_events_<?php echo $field_name; ?>(){
    $("#files_<?php echo $field_name; ?> li button").unbind();
    $("#files_<?php echo $field_name; ?> li button.btn-danger").click(function(){
        var image_el = $(this);
        
        <?php if(config_item('app_type') == 'demo'):?>
        if(true)
        {
            $.fn.endLoading();
            return false;
        }
        <?php endif;?>
        
        $.post($(this).attr('data-url'), function( data ) {
            var obj = jQuery.parseJSON(data);
            
            if(obj.success)
            {
                image_el.parent().remove();
            }
            else
            {
                ShowStatus.show('<?php echo lang_check('Unsuccessful, possible permission problems or file not exists'); ?>');
            }
            //console.log("Data Loaded: " + obj.success );
        });
        
        return false;
    });
}

</script>
<?php endif; ?>
                                              </div>
                                            </div>

<?php 
    }
}

if ( ! function_exists('form_dropdown'))
{
	function form_dropdown($name = '', $options = array(), $selected = array(), $extra = '')
	{
		if ( ! is_array($selected))
		{
			$selected = array($selected);
		}

		// If no selected state was submitted we will attempt to set it automatically
		if (count($selected) === 0)
		{
			// If the form name appears in the $_POST array we have a winner!
			if (isset($_POST[$name]))
			{
				$selected = array($_POST[$name]);
			}
		}

		if ($extra != '') $extra = ' '.$extra;

		$multiple = (count($selected) > 1 && strpos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

		$form = '<select name="'.$name.'"'.$extra.$multiple.">\n";

		foreach ($options as $key => $val)
		{
			$key = (string) $key;

			if (is_array($val) && ! empty($val))
			{
				$form .= '<optgroup label="'.$key.'">'."\n";

				foreach ($val as $optgroup_key => $optgroup_val)
				{
					$sel = (in_array($optgroup_key, $selected)) ? ' selected="selected"' : '';
                    
                    //$optgroup_key =  str_replace('"', '&quot;', $optgroup_key);
                    //echo '$optgroup_key: '.$optgroup_key.'<br />';
					$form .= '<option value="'.$optgroup_key.'"'.$sel.'>'.(string) $optgroup_val."</option>\n";
				}

				$form .= '</optgroup>'."\n";
			}
			else
			{
                if(isset($selected[0]))
                $selected[0] = str_replace('&quot;', '"', $selected[0]);

                $sel = (in_array($key, $selected)) ? ' selected="selected"' : '';
                
                $key = str_replace('"', '&quot;', $key);
                
				$form .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
			}
		}

		$form .= '</select>';

		return $form;
	}
}


/* End of file form_helper.php */
