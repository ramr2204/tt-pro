<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   VNombre:            envioCorreo
*   Ruta:              /application/controllers/envioCorreo.php
*   Descripcion:       controlador de envioCorreo
*   Fecha Creacion:    20/may/2014
*   @author            Monica Gutierrez <shinexmonic@gmail.com>
*   @version           2020-10-25
*
*/

class envioCorreo extends MY_Controller 
{
	function __construct() 
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('EnvioCorreoHelper');
        //$this->load->helper('MYPDF');
        $this->load->helper(array('form','url','codegen_helper'));
        $this->load->model('codegen_model','',TRUE);
    }   

    function index()
    {
    	return '<h1>Hiii</h1>';
    }

    function enviarCorreo()
    {
		$data['to']          = 'turrisystemltda@gmail.com'; 
		$data['sender_name'] = 'Estampillas Pro Boyacá'; 
		$data['subject']     = 'prueba jjeje';  

    	$send = EnvioCorreoHelper::enviar($data);

    	if($send){
    		echo 'se envio';
            $this->session->set_flashdata('envio', 'Email enviado correctamente');
        }
         
        //redirect(base_url("envioCorreo"));
    }
}