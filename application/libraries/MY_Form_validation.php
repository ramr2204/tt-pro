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

    /**
	 * Valida que tenga un minimo de caracteres numericos
	 *
	 * @param	string
	 * @param	field
	 * @return	bool
	 */
	public function length_number($str, $field)
	{
        return $this->verificarCoincidenciasRegex('/([0-9]+)/', $str, $field);
    }

    /**
	 * Valida que contenga n cantidad de minusculas
	 *
	 * @param	string
	 * @param	field
	 * @return	bool
	 */
	public function length_lower($str, $field)
	{
        return $this->verificarCoincidenciasRegex('/([a-z]+)/', $str, $field);
    }

    /**
	 * Valida que contenga n cantidad de mayusculas
	 *
	 * @param	string
	 * @param	field
	 * @return	bool
	 */
	public function length_upper($str, $field)
	{
        return $this->verificarCoincidenciasRegex('/([A-Z]+)/', $str, $field);
    }

    private function verificarCoincidenciasRegex($pattern, $str, $len)
    {
        $number_len = 0;
        if( preg_match_all($pattern, $str, $matches) ){
            foreach( $matches[0] AS $key => $val ){
                $number_len += strlen($val);
            }
        }

        # Verificamos si el total de coincidencias es lo requerido
        if( $number_len >= $len ){
            return true;
        }
        return false;
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
