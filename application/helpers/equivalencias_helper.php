<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Equivalencias extends CI_Controller
{

    # Codigo (salt) usado para generar hashes
    private static $generadorHash = '729se32sm3owg=.we__hl';

    private static $clasificacionCOntratos = array(
        1 => 'Contratos',
        2 => 'Modificaciones',
        3 => 'Adiciones',
        4 => 'Otros',
    );
    
    private static $contratoNormal = 1;
    private static $contratoModificacion = 2;
    private static $contratoAdicion = 3;
    private static $contratoOtros = 4;

    private static $cuotaPendiente = 2;
    private static $cuotaPaga = 1;

    private static $contratoModificado = 4;

    private static $perfilLiquidador = 4;

    public static function generadorHash(){
        return self::$generadorHash;
    }

    public static function clasificacionCOntratos(){
        return self::$clasificacionCOntratos;
    }

    public static function contratoNormal(){
        return self::$contratoNormal;
    }

    public static function contratoModificacion(){
        return self::$contratoModificacion;
    }

    public static function contratoAdicion(){
        return self::$contratoAdicion;
    }

    public static function contratoOtros(){
        return self::$contratoOtros;
    }

    public static function cuotaPendiente(){
        return self::$cuotaPendiente;
    }

    public static function cuotaPaga(){
        return self::$cuotaPaga;
    }

    public static function contratoModificado(){
        return self::$contratoModificado;
    }

    public static function perfilLiquidador(){
        return self::$perfilLiquidador;
    }
}
