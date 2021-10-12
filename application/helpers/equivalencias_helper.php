<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Equivalencias extends CI_Controller
{
	private static $tiposEstampillas = array(
        1 => 'normal',
        2 => 'retención'
    );

    private static $tipoRetencion = 2;

    # Codigo (salt) usado para generar hashes
    private static $generadorHash = '729se32sm3owg=.we__hl';

    public static function tiposEstampillas(){
        return self::$tiposEstampillas;
    }

    public static function tipoRetencion(){
        return self::$tipoRetencion;
    }

    public static function generadorHash(){
        return self::$generadorHash;
    }
}
