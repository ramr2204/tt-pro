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

class Generarpdf extends CI_controller {
    
  function __construct() 
  {
      parent::__construct();
	    $this->load->library('form_validation','Pdf');		
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
              $pdf->SetAuthor('turrisystem');
              $pdf->SetTitle('Liquidación de estampillas');
              $pdf->SetSubject('Gobernación del Tolima');
              $pdf->SetKeywords('estampillas,gobernación');
              $pdf->SetPrintHeader(false);
              $pdf->SetPrintFooter(false);
              // set default monospaced font
              $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

              // set margins
              $pdf->SetMargins(PDF_MARGIN_LEFT, 2, PDF_MARGIN_RIGHT);
              $pdf->SetHeaderMargin(0);
              $pdf->SetFooterMargin(0);

              // set auto page breaks
              $pdf->SetAutoPageBreak(TRUE, 2);

              // set image scale factor
              $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

              // set some language-dependent strings (optional)
              if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                  require_once(dirname(__FILE__).'/lang/eng.php');
                  $pdf->setLanguageArray($l);
              }

// ---------------------------------------------------------
            
               // set font
               $pdf->SetFont('times', 'BI', 10);
                   
               
               foreach ($this->data['facturas'] as $key => $value) { 
                $pdf->AddPage();
                $numerofactura=str_pad($value->fact_id, 10, '0', STR_PAD_LEFT);
                $this->data['facturaestampilla']=$value;
                $this->data['params'] = TCPDF_STATIC::serializeTCPDFtagParameters(array('(415)7709998009530'.chr(247).'(8020)7341711081'.chr(247).'(390y)000000760000'.chr(247).'(96', 'C128', '', '', 80, 17, 0.4, array('position'=>'C','align' => 'C', 'border-top'=>true, 'padding'=>2,'margin-top'=>2, 'fgcolor'=>array(0,0,0), 'bgcolor'=>'', 'text'=>false, 'font'=>'helvetica', 'fontsize'=>6, 'stretchtext'=>4), 'N'));
                $html = $this->load->view('generarpdf/generarpdf_reciboestampilla', $this->data, TRUE);  
                $pdf->writeHTML($html, true, false, true, false, '');
               }

               // ---------------------------------------------------------

               //Close and output PDF document
               $pdf->Output('recibos_'.$liquidacion->liqu_contratoid.'.pdf', 'I');            
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
              redirect(base_url().'index.php/users/login');
      }

  }



 function generar_liquidaciontramite()
 {
      if ($this->ion_auth->logged_in()){
          if ($this->uri->segment(3)==''){
               redirect(base_url().'index.php/error_404');
          } 
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')){
              $this->load->library("Pdf");
              $idcontrato=$this->uri->segment(3);
              $this->data['result'] = $this->liquidaciones_model->getrecibostramites($idcontrato);
              $liquidacion = $this->data['result'];
              $this->data['facturas'] = $this->liquidaciones_model->getfacturas($liquidacion->liqu_id);
              
             // create new PDF document
              $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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
              $pdf->SetMargins(PDF_MARGIN_LEFT, 2, PDF_MARGIN_RIGHT);
              $pdf->SetHeaderMargin(0);
              $pdf->SetFooterMargin(0);

              // set auto page breaks
              $pdf->SetAutoPageBreak(TRUE, 2);

              // set image scale factor
              $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

              // set some language-dependent strings (optional)
              if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                  require_once(dirname(__FILE__).'/lang/eng.php');
                  $pdf->setLanguageArray($l);
              }

// ---------------------------------------------------------
            
               // set font
               $pdf->SetFont('times', 'BI', 10);
                   
               
               foreach ($this->data['facturas'] as $key => $value) { 
                $pdf->AddPage();
                $numerofactura=str_pad($value->fact_id, 10, '0', STR_PAD_LEFT);
                $this->data['facturaestampilla']=$value;
                $this->data['params'] = TCPDF_STATIC::serializeTCPDFtagParameters(array('(415)7709998009530'.chr(247).'(8020)7341711081'.chr(247).'(390y)000000760000'.chr(247).'(96', 'C128', '', '', 80, 17, 0.4, array('position'=>'C','align' => 'C', 'border-top'=>true, 'padding'=>2,'margin-top'=>2, 'fgcolor'=>array(0,0,0), 'bgcolor'=>'', 'text'=>false, 'font'=>'helvetica', 'fontsize'=>6, 'stretchtext'=>4), 'N'));
                $html = $this->load->view('generarpdf/generarpdf_reciboestampillatramite', $this->data, TRUE);  
                $pdf->writeHTML($html, true, false, true, false, '');
               }

               // ---------------------------------------------------------

               //Close and output PDF document
               $pdf->Output('recibos_'.$liquidacion->liqu_contratoid.'.pdf', 'I');            
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
              redirect(base_url().'index.php/users/login');
      }

  }


 function generar_estampilla()
  {
      if ($this->ion_auth->logged_in()){

          if ($this->uri->segment(3)==''){
               redirect(base_url().'index.php/error_404');
          } 


          //verifica que el usuario que llama el metodo
          //tenga perfil de liquidador
          $usuarioLogueado=$this->ion_auth->user()->row();

          if ($usuarioLogueado->perfilid==4){

                  $this->data['estampilla'] = $this->liquidaciones_model->getfactura_legalizada($this->uri->segment(3),$doc=TRUE);
              
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
            
                  // set font
                   $pdf->SetFont('times', '', 8);
                   $pdf->AddPage('L',array(92,141));
                   $this->data['params'] = TCPDF_STATIC::serializeTCPDFtagParameters(array('(415)7709998009530'.chr(247).'(8020)7341711081'.chr(247).'(390y)000000760000'.chr(247).'(96', 'C128', '', '', 84.5, 14.5, 0.4, array('position'=>'C', 'padding'=>0.5, 'fgcolor'=>array(0,0,0), 'bgcolor'=>'', 'text'=>false, 'font'=>'helvetica', 'fontsize'=>6, 'stretchtext'=>4), 'M'));
                   $html = $this->load->view('generarpdf/generarpdf_estampillalegalizada', $this->data, TRUE);  
                
                   $pdf->writeHTML($html, true, false, true, false, '');
           

                  // ---------------------------------------------------------

                  //Close and output PDF document
                  $pdf->Output('estampilla_'.$estampilla->impr_codigopapel.'.pdf', 'I'); 
              

          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
              redirect(base_url().'index.php/users/login');
      }

  }

}
