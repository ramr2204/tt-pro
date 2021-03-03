<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Equivalencias extends CI_Controller
{
	private static $tiposEstampillas = [
        1 => 'normal',
        2 => 'contingencia'
    ];

    public static function tiposEstampillas(){
        return self::$tiposEstampillas;
    }
}
