<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
 *
 * @author      David Mahecha
 */

class MY_Form_validation extends CI_Form_validation
{
	protected $CI;

    public function __construct()
    {
        parent::__construct();

        // reference to the CodeIgniter super object
        $this->CI =& get_instance();
    }

    public function reset_validation()
    {
        $this->_field_data = [];
        $this->_config_rules = [];
        $this->_error_array = [];
        $this->_error_messages = [];
        $this->error_string = '';

        return $this;
    }
}
