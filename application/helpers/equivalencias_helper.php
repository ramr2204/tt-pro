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

    private static $clasificacionCOntratos = array(
        1 => 'Normal',
        2 => 'Adición',
        3 => 'Ajuste',
    );
    
    private static $contratoNormal = 1;
    private static $contratoAdicion = 2;
    private static $contratoAjuste = 3;

    public static function tiposEstampillas(){
        return self::$tiposEstampillas;
    }

    public static function tipoRetencion(){
        return self::$tipoRetencion;
    }

    public static function generadorHash(){
        return self::$generadorHash;
    }

    public static function clasificacionCOntratos(){
        return self::$clasificacionCOntratos;
    }

    public static function contratoNormal(){
        return self::$contratoNormal;
    }

    public static function contratoAdicion(){
        return self::$contratoAdicion;
    }

    public static function contratoAjuste(){
        return self::$contratoAjuste;
    }
}
