<?php

require_once(APPPATH.'libraries/barcodegen/class/BCGFontFile.php');
require_once(APPPATH.'libraries/barcodegen/class/BCGColor.php');
require_once(APPPATH.'libraries/barcodegen/class/BCGDrawing.php');
require_once(APPPATH.'libraries/barcodegen/class/BCGcode128.barcode.php');
header('Content-type: image/png');

class GenerarBarcode extends CI_controller 
{
	function __construct() 
    {
        parent::__construct();
	    $this->load->library('form_validation');
        $this->load->helper('HelperGeneral');
        $this->load->helper(array('form','url','codegen_helper'));
        $this->load->model('codegen_model','',TRUE);
	}	
	
	function index()
    {
		$this->generarBarcode();
	}

	function generarBarcode($value='')
	{
		if(isset($_GET['generar_barcode_text']))
		{
		    $colorFront = new BCGColor(0, 0, 0);
		    $colorBack = new BCGColor(255, 255, 255);

		    $code = new BCGcode128();
		    $code->setScale(2);
		    $code->setThickness(20);
		    $code->setForegroundColor($colorFront);
		    $code->setBackgroundColor($colorBack);
		    $code->parse($_GET['generar_barcode_text']);

		    $drawing = new BCGDrawing('', $colorBack);
		    $drawing->setBarcode($code);

		    $drawing->draw();
		    $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

		}
	}
}