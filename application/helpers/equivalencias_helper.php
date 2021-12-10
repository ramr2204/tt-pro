<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Equivalencias extends CI_Controller
{
    # Codigo (salt) usado para generar hashes
    private static $generadorHash = '729se32sm3owg=.we__hl';

    private static $clasificacionContratos = [
        1 => 'Contratos',
        2 => 'Modificaciones',
        4 => 'Otros',
    ];
    
    private static $contratoNormal = 1;
    private static $contratoModificacion = 2;
    private static $contratoOtros = 4;

    private static $cuotaPendiente = 2;
    private static $cuotaPaga = 1;

    private static $contratoModificado = 4;

    private static $perfilesEmpresa = [
        4, # Liquidador,
        9, # Firmante
    ];

    private static $tipoDeclaraciones = [
        1 => 'Inicial',
        2 => 'Corrección',
    ];

    private static $declaracionInicial = 1;
    private static $declaracionCorreccion = 2;

    private static $estados = [
        0 => 'Inactivo',
        1 => 'Activo',
    ];

    private static $cuotaNormal = 1;
    private static $cuotaAdicion = 2;

    private static $estadoActivo = 1;

    private static $perfilFirmante = 9;

    private static $tiposCuotas = [
        1 => 'Normal',
        2 => 'Adicion',
    ];

    public static function generadorHash(){
        return self::$generadorHash;
    }

    public static function clasificacionContratos(){
        return self::$clasificacionContratos;
    }

    public static function contratoNormal(){
        return self::$contratoNormal;
    }

    public static function contratoModificacion(){
        return self::$contratoModificacion;
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

    public static function perfilesEmpresa(){
        return self::$perfilesEmpresa;
    }

    public static function tipoDeclaraciones(){
        return self::$tipoDeclaraciones;
    }

    public static function declaracionInicial(){
        return self::$declaracionInicial;
    }

    public static function declaracionCorreccion(){
        return self::$declaracionCorreccion;
    }

    public static function estados(){
        return self::$estados;
    }

    public static function estadoActivo(){
        return self::$estadoActivo;
    }

    public static function perfilFirmante(){
        return self::$perfilFirmante;
    }

    public static function cuotaNormal(){
        return self::$cuotaNormal;
    }

    public static function cuotaAdicion(){
        return self::$cuotaAdicion;
    }

    public static function tiposCuotas(){
        return self::$tiposCuotas;
    }
}
