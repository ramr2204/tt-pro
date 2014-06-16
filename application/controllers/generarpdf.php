<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            contratos
*   Ruta:              /application/controllers/contratos.php
*   Descripcion:       controlador de contratos
*   Fecha Creacion:    20/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-20
*
*/

class Generarpdf extends CI_controller {
    
  function __construct() 
  {
      parent::__construct();
	    $this->load->library('form_validation');		
		  $this->load->helper(array('form','url','codegen_helper'));
      $this->load->model('liquidaciones_model','',TRUE);
      $this->load->model('codegen_model','',TRUE);
	}	
	
	function index()
  {
		  $this->liquidar();
	}

	
  function generar_liquidacion()
  {
      if ($this->ion_auth->logged_in()){
          if ($this->uri->segment(3)==''){
               redirect(base_url().'index.php/error_404');
          } 
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')){
              $this->load->library("Pdf");
              $idcontrato=$this->uri->segment(3);
              $this->data['result'] = $this->liquidaciones_model->getrecibos($idcontrato);
              $liquidacion = $this->data['result'];
              $this->data['facturas'] = $this->liquidaciones_model->getfacturas($liquidacion->liqu_id);
              
  // create new PDF document
              $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

              // set document information
              $pdf->SetCreator(PDF_CREATOR);
              $pdf->SetAuthor('Nicola Asuni');
              $pdf->SetTitle('TCPDF Example 003');
              $pdf->SetSubject('TCPDF Tutorial');
              $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

              // set default header data
              $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH,'Gobernación del Tolima', 'Departamento Administrativo de Asuntos Jurídicos
Dirección de Contratación');

              // set header and footer fonts
              $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
              $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

              // set default monospaced font
              $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

              // set margins
              $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
              $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
              $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

              // set auto page breaks
              $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

              // set image scale factor
              $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

              // set some language-dependent strings (optional)
              if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                  require_once(dirname(__FILE__).'/lang/eng.php');
                  $pdf->setLanguageArray($l);
              }

// ---------------------------------------------------------

               // set font
               $pdf->SetFont('times', 'BI', 12);

               // add a page
               $pdf->AddPage();
               $html = $this->load->view('generarpdf/generarpdf_vercontratoliquidado', $this->data, TRUE);  
               // set some text to print


               // print a block of text using Write()
               $pdf->writeHTML($html, true, false, true, false, '');

               // ---------------------------------------------------------

               //Close and output PDF document
               $pdf->Output('example_003.pdf', 'I');            
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
              redirect(base_url().'index.php/users/login');
      }

  }



}
