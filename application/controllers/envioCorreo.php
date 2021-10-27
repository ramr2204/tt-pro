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
		/*$data['to']          = 'turrisystemltda@gmail.com'; 
		$data['sender_name'] = 'Estampillas Pro Boyacá'; 
		$data['subject']     = 'prueba jjeje';  

    	$send = EnvioCorreoHelper::enviar($data);

    	if($send){
    		echo 'se envio';
            $this->session->set_flashdata('envio', 'Email enviado correctamente');
        }*/
        $mail = new EnvioCorreoHelper();
        $datos_vista = [
            'code' => 'code' ,
            'subject' => 'Código Verificación Firma',
            'alt' => 'Correo sin formato'
        ];
        $view = $this->load->view('firma/code', $datos_vista,true);

        // $mail->setTo(array( 'to' => array($email_destino,$nombre_receptor) ) );
        // $mail->setSubject("Código Verificación Firma");
        // $mail->setImage(
        //     array(
        //         array(
        //             'banner' => 'images/index_r1_c1.png'
        //         )
        //     )
        // );
        // $mail->setBody($view);
        // $mail->setAlt("El codigo de verificacion es: ".$code['code']);

        $envio = $mail->enviar([
            'to'          => 'turrisystemltda@gmail.com',//$email_destino,
            'sender_name' => 'Estampillas Pro Boyacá',
            'subject'     => 'Código Verificación Firma',
            'body'        => $view,
            'alt'         => 'El codigo de verificacion es: '.$code['code']
        ]);

        if($envio === true) {
            $result['message'] = 'El correo electrónico se envió correctamente, por favor verificar el código enviado.';
            $result['status'] = 1;
        } else {
            $result['message'] = 'Se presento un problema al enviar el correo.';
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($result);
         
        //redirect(base_url("envioCorreo"));
    }
}