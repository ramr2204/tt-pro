<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Equivalencias extends CI_Controller
{
	private static $tiposEstampillas = array(
        1 => 'normal',
        2 => 'contingencia'
    );

    private static $tipoContingencia = 2;

    public static function tiposEstampillas(){
        return self::$tiposEstampillas;
    }

    public static function tipoContingencia(){
        return self::$tipoContingencia;
    }
}
