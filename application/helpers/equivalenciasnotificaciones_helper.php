<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class EquivalenciasNotificaciones extends CI_Controller
{
    private static $solicitudCorreccion = 1;
    private static $correccionAprobada = 2;
    private static $correccionNegada = 3;
    private static $aceptada = 4;
    private static $negada = 5;
    private static $correccion = 6;

    private static $descripciones = [
        1 => 'Solicitud de corrección',
        2 => 'Solcitud de corrección aprobada',
        3 => 'Solcitud de corrección negada',
        4 => 'La declaracion ha sido aprobada',
        5 => 'La declaracion ha sido negada',
        6 => 'Corrección para la declaración',
    ];

    private static $estilos = [
        1 => [
            'icono' => 'question-sign',
            'color' => 'primary',
        ],
        2 => [
            'icono' => 'ok-sign',
            'color' => 'success',
        ],
        3 => [
            'icono' => 'remove-sign',
            'color' => 'danger',
        ],
        4 => [
            'icono' => 'ok-sign',
            'color' => 'success',
        ],
        5 => [
            'icono' => 'remove-sign',
            'color' => 'danger',
        ],
        6 => [
            'icono' => 'exclamation-sign',
            'color' => 'primary',
        ],
    ];

    # Por llave es el id del perfil y el valor es un arreglo con los tipos permitidos
    private static $permisos = [
        # Administrador
        1 => [1],
        # Liquidador
        4 => [2,3,4,5,6],
        # Firmante
        9 => [2,3,4,5],
    ];

    private static $notificacionesSinEmpresa = [1];

    public static function solicitudCorreccion(){
        return self::$solicitudCorreccion;
    }

    public static function correccionAprobada(){
        return self::$correccionAprobada;
    }

    public static function correccionNegada(){
        return self::$correccionNegada;
    }

    public static function aceptada(){
        return self::$aceptada;
    }

    public static function negada(){
        return self::$negada;
    }

    public static function correccion(){
        return self::$correccion;
    }

    public static function descripciones(){
        return self::$descripciones;
    }

    public static function estilos(){
        return self::$estilos;
    }

    public static function permisos(){
        return self::$permisos;
    }

    public static function notificacionesSinEmpresa(){
        return self::$notificacionesSinEmpresa;
    }
}
