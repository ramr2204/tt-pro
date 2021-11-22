<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class EquivalenciasFirmas extends CI_Controller
{

    # Codigo (salt) usado para generar hashes
    private static $tiposUsuarios = [
        1 => 'Representate Legal',
        2 => 'Contador',
        3 => 'Revisor Fiscal',
    ];

    private static $tiposGrupos = [
        'R' => [1],
        'A' => [2, 3],
    ];

    private static $tiposGruposNombres = [
        'R' => 'Representante Legal',
        'A' => 'Revisor Fiscal / Contador',
    ];

    private static $usuarioRepresentante = 1;

    private static $estadosDeclaracion = [
        1 => 'Iniciado',
        3 => 'Pagado',
        2 => 'Firmado',
    ];

    private static $declaracionIniciada = 1;
    private static $declaracionPagada = 3;
    private static $declaracionFirmada = 2;

    public static function tiposUsuarios(){
        return self::$tiposUsuarios;
    }

    public static function usuarioRepresentante(){
        return self::$usuarioRepresentante;
    }

    public static function tiposGrupos(){
        return self::$tiposGrupos;
    }

    public static function tiposGruposNombres(){
        return self::$tiposGruposNombres;
    }

    public static function estadosDeclaracion(){
        return self::$estadosDeclaracion;
    }

    public static function declaracionIniciada(){
        return self::$declaracionIniciada;
    }

    public static function declaracionPagada(){
        return self::$declaracionPagada;
    }

    public static function declaracionFirmada(){
        return self::$declaracionFirmada;
    }

}
