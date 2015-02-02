<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            contratos
*   Ruta:              /application/controllers/generarpdf.php
*   Descripcion:       controlador de contratos
*   Fecha Creacion:    20/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-20
*
*/

class Qr extends CI_controller {
    
  function __construct() 
  {
      parent::__construct();
      $this->load->library('form_validation','Pdf');    
      $this->load->helper(array('form','url','codegen_helper'));
      $this->load->model('liquidaciones_model','',TRUE);
      $this->load->model('codegen_model','',TRUE);
  } 
  
function q()
  {              
                 //Valida si la estampilla se generará para un contrato
                 //o para un trámite
     
                  $verificacionTramite = $this->codegen_model->get('est_liquidaciones l','l.liqu_tipocontrato'
                       ,'f.fact_id = '.$this->uri->segment(3), 1, null, true, ''
                       ,'est_facturas f', 'f.fact_liquidacionid = l.liqu_id');

                  if($verificacionTramite->liqu_tipocontrato == 'Tramite')
                  {
                       $this->data['estampilla'] = $this->liquidaciones_model->getfactura_legalizada_tramite($this->uri->segment(3),$doc=TRUE); 
                       $this->data['estampilla']->cntr_vigencia = date('Y', strtotime($this->data['estampilla']->cntr_vigencia));                       

                  }else
                      {
                           $this->data['estampilla'] = $this->liquidaciones_model->getfactura_legalizada($this->uri->segment(3),$doc=TRUE); 
                      }

                  
              
                  $estampilla=$this->data['estampilla'];  

                  
                  $this->load->library("Pdf");
                  $resolution= array(14, 9);
                  $pdf = new PDF(PDF_PAGE_ORIENTATION,'mm',array(92,141), true, 'UTF-8', false);


                  // set document information
                  $pdf->SetCreator(PDF_CREATOR);
                  $pdf->SetAuthor('turrisystem');
                  $pdf->SetTitle('Liquidación de estampillas');
                  $pdf->SetSubject('Gobernación del Tolima');
                  $pdf->SetKeywords('estampillas,gobernación');
                  $pdf->SetPrintHeader(false);
                  $pdf->SetPrintFooter(false);
                  // set default monospaced font
                  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

                  // set margins
                  $pdf->setPageUnit('mm');
                  $pdf->SetMargins(16, 2.5, 4.9, true);
      
                  // set auto page breaks
                  $pdf->SetAutoPageBreak(FALSE, 1);

                  // set image scale factor
                  //$pdf->setImageScale(100.5);

                  // set some language-dependent strings (optional)
                  if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                      require_once(dirname(__FILE__).'/lang/eng.php');
                      $pdf->setLanguageArray($l);
                  }
               
                  // ---------------------------------------------------------
                  // set style for barcode
                  $style = array(
                      'border' => 2,
                      'vpadding' => 1,
                      'hpadding' => 1,
                      'fgcolor' => array(0,0,0),
                      'bgcolor' => false, //array(255,255,255)
                      'module_width' => 1, // width of a single module in points
                      'module_height' => 1 // height of a single module in points                      
                  );

                  // set font
                   $pdf->SetFont('times', '', 8);
                   $pdf->AddPage('L',array(92,141));
                   $this->data['params'] = TCPDF_STATIC::serializeTCPDFtagParameters(array('http://190.85.28.74:8086/estampillas-pro/index.php/qr/q/'.$this->uri->segment(3), 'QRCODE,H', 110, 56, 16, 16, $style, 'T'));
                   $html = $this->load->view('generarpdf/generarpdf_estampillalegalizada', $this->data, TRUE);  
                
                   $pdf->writeHTML($html, true, false, true, false, '');
           

                  // ---------------------------------------------------------

                  //Close and output PDF document
                  $pdf->Output('estampilla_'.$estampilla->impr_codigopapel.'.pdf', 'I'); 
      

  }

}