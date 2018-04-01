<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Config extends CI_Config{

	/**
	 * List of paths to search when trying to load a config file
	 *
	 * @var array
	 */
	var $_config_paths = array(APPPATH);

	public function add_config_path($path)
	{
		$this->_config_paths = array_merge(array($path), $this->_config_paths);
    }
}

