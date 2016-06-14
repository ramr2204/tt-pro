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
require_once APPPATH.'/libraries/barcodegen/class/BCGFontFile.php';
require_once APPPATH.'/libraries/barcodegen/class/BCGColor.php';
require_once APPPATH.'/libraries/barcodegen/class/BCGDrawing.php';
require_once APPPATH.'/libraries/barcodegen/class/BCGgs1128.barcode.php';
// // Including all required classes
// require_once('class/BCGFontFile.php');
// require_once('class/BCGColor.php');
// require_once('class/BCGDrawing.php');

// // Including the barcode technology
// //require_once('class/BCGcode39.barcode.php');
// require_once('class/BCGgs1128.barcode.php');

// // Loading Font
// $font = new BCGFontFile('./font/Arial.ttf', 18);

// // Don't forget to sanitize user inputs
// $text = '(415)7709140080127(8020)7313100127(3900)172874000';

// // The arguments are R, G, B for color.
// $color_black = new BCGColor(0, 0, 0);
// $color_white = new BCGColor(255, 255, 255);

// $drawException = null;
// try {
//     $code = new BCGgs1128();
//     $code->setScale(2); // Resolution
//     $code->setThickness(30); // Thickness
//     $code->setForegroundColor($color_black); // Color of bars
//     $code->setBackgroundColor($color_white); // Color of spaces
//     $code->setFont(0); // Font (or 0)
//     $code->parse($text); // Text
// } catch(Exception $exception) {
//     $drawException = $exception;
// }

// /* Here is the list of the arguments
// 1 - Filename (empty : display on screen)
// 2 - Background color */
// $drawing = new BCGDrawing('himan.png', $color_white);
// if($drawException) {
//     $drawing->drawException($drawException);
// } else {
//     $drawing->setBarcode($code);
//     $drawing->draw();
// }

// // Header that says it is an image (remove it if you save the barcode to a file)
// // header('Content-Type: image/png');
// // header('Content-Disposition: inline; filename="barcode.png"');

// // Draw (or save) the image into PNG format.
// $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

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

               //Extrae el codigo del departamento según
               //los parametros en la BD
               $parametros=$this->codegen_model->get('adm_parametros','para_codigodepartamento','para_id = 1',1,NULL,true);
               $this->data['codigodepto'] = $parametros->para_codigodepartamento;
              
               
               foreach ($this->data['facturas'] as $key => $value) { 

                $this->barcode($value->fact_codigo);
                $this->data['codebar'] = str_ireplace(array('~F1', '(390y)'), array('', '(3900)'), $value->fact_codigo);

                $pdf->AddPage();
                $numerofactura=str_pad($value->fact_id, 10, '0', STR_PAD_LEFT);
                $this->data['facturaestampilla']=$value;
                $this->data['params'] = TCPDF_STATIC::serializeTCPDFtagParameters(array('(415)7709998009530~F1(8020)7341711081~F1(390y)000000760000', 'C128', '', '', 80, 17, 0.4, array('position'=>'C','align' => 'C', 'border-top'=>true, 'padding'=>2,'margin-top'=>2, 'fgcolor'=>array(0,0,0), 'bgcolor'=>'', 'text'=>false, 'font'=>'helvetica', 'fontsize'=>6, 'stretchtext'=>4), 'N'));
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


               //Extrae el codigo del departamento según
               //los parametros en la BD
               $parametros=$this->codegen_model->get('adm_parametros','para_codigodepartamento','para_id = 1',1,NULL,true);
               $this->data['codigodepto'] = $parametros->para_codigodepartamento;
                          

               foreach ($this->data['facturas'] as $key => $value) { 

                $this->barcode($value->fact_codigo);
                $this->data['codebar'] = str_ireplace(array('~F1', '(390y)'), array('', '(3900)'), $value->fact_codigo);
                
                $pdf->AddPage();
                $numerofactura=str_pad($value->fact_id, 10, '0', STR_PAD_LEFT);
                $this->data['facturaestampilla']=$value;
                                               
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
                  $pdf->SetTitle('Liquidación de estampillas - Rotulo No '.$estampilla->impr_codigopapel.' Factura');
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
             //  print_r($this->data);exit();
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

                    /*
                    * Variable que determina si se debe trabajar con papelería de contingencia
                    */
                    $objContin = $this->codegen_model->get('adm_parametros','para_contingencia','para_id = 1',1,NULL,true);
                    if($objContin->para_contingencia == 1)
                    {
                        $this->data['contingencia'] = 'C';
                    }else
                        {
                            $this->data['contingencia'] = '';
                        }

                  // set font
                   $pdf->SetFont('times', '', 8);
                   $pdf->AddPage('L',array(92,141));
                   $this->data['params'] = TCPDF_STATIC::serializeTCPDFtagParameters(array('http://qrcol.com/EP/p.php?c='.$this->uri->segment(3), 'QRCODE,H', 110, 57.5, 16, 16, $style, 'T'));
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

  function barcode ($code) {
    $text = $code;
                  
    // The arguments are R, G, B for color.
    $color_black = new BCGColor(0, 0, 0);
    $color_white = new BCGColor(255, 255, 255);

    $drawException = null;
    try {
        $code = new BCGgs1128();
        $code->setScale(2); // Resolution
        $code->setThickness(30); // Thickness
        $code->setForegroundColor($color_black); // Color of bars
        $code->setBackgroundColor($color_white); // Color of spaces
        $code->setFont(0); // Font (or 0)
        $code->parse($text); // Text
    } catch(Exception $exception) {
        $drawException = $exception;
    }
    $text = str_ireplace(array('~F1', '(390y)'), array('', '(3900)'), $text);
    /* Here is the list of the arguments
    1 - Filename (empty : display on screen)
    2 - Background color */
    $drawing = new BCGDrawing(APPPATH.'/libraries/barcodegen/'.$text.'.png', $color_white);
    if($drawException) {
        $drawing->drawException($drawException);
    } else {
        $drawing->setBarcode($code);
        $drawing->draw();
    }

    
    // Draw (or save) the image into PNG format.
    $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
  }

}
