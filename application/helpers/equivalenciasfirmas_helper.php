<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class EquivalenciasFirmas extends CI_Controller
{

    # Codigo (salt) usado para generar hashes
    private static $tiposUsuarios = [
        1 => 'Representate Legal',
        2 => 'Contador',
        3 => 'Revisor Fiscal',
    ];

    private static $usuarioRepresentante = 1;

    public static function tiposUsuarios(){
        return self::$tiposUsuarios;
    }

    public static function usuarioRepresentante(){
        return self::$usuarioRepresentante;
    }

}
