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
        2 => 'Firmado',
        3 => 'Pagado',
        4 => 'Solicitado Corrección',
        5 => 'Corregido',
        6 => 'Aceptado',
        7 => 'Rechazado',
    ];

    private static $declaracionIniciada = 1;
    private static $declaracionFirmada = 2;
    private static $declaracionPagada = 3;
    private static $declaracionSolicitadaCorreccion = 4;
    private static $declaracionCorregida = 5;
    private static $declaracionAceptada = 6;
    private static $declaracionRechazada = 7;

    private static $correccionIniciada = 1;
    private static $correccionAceptada = 2;
    private static $correccionRechazada = 3;

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

    public static function declaracionSolicitadaCorreccion(){
        return self::$declaracionSolicitadaCorreccion;
    }

    public static function declaracionCorregida(){
        return self::$declaracionCorregida;
    }

    public static function declaracionAceptada(){
        return self::$declaracionAceptada;
    }

    public static function declaracionRechazada(){
        return self::$declaracionRechazada;
    }

    public static function correccionIniciada(){
        return self::$correccionIniciada;
    }

    public static function correccionAceptada(){
        return self::$correccionAceptada;
    }

    public static function correccionRechazada(){
        return self::$correccionRechazada;
    }
}
