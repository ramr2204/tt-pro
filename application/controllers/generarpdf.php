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

                $contrato = $this->codegen_model->getSelect('con_contratos','date_format(fecha_insercion,"%Y-%m-%d") AS fecha_insercion,cntr_contratistaid,cntr_objeto', 'WHERE cntr_id = "'.$idcontrato.'"');
                $contratista = $this->codegen_model->getSelect('con_contratistas','cont_direccion,cont_telefono,cont_email', 'WHERE cont_id = "'.$contrato[0]->cntr_contratistaid.'"');
                $liquidador = $this->codegen_model->getSelect('users','first_name,last_name','WHERE id = "'.$liquidacion->liqu_usuarioliquida.'"','');

                $this->data['contrato'] = $contrato[0];
                $this->data['contratista'] = $contratista[0];
                $this->data['liquidador'] = $liquidador[0];

                // create new PDF document
              $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

              // set document information
              $pdf->SetCreator(PDF_CREATOR);
              $pdf->SetAuthor('turrisystem');
              $pdf->SetTitle('Liquidación de estampillas');
              $pdf->SetSubject('Gobernación del Putumayo');
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
                $pdf->SetFont('times', 'BI', 8);

                foreach ($this->data['facturas'] as $key => $value)
                {
                    $this->barcode($value->fact_codigo);
                    $this->data['facturas'][$key]->codigo_barras = str_ireplace(array('~F1', '(390y)'), array('', '(3900)'), $value->fact_codigo);

                    // $numerofactura=str_pad($value->fact_id, 10, '0', STR_PAD_LEFT);
                    // $this->data['facturaestampilla']=$value;
                    $this->data['params'] = TCPDF_STATIC::serializeTCPDFtagParameters(array('(415)7709998009530~F1(8020)7341711081~F1(390y)000000760000', 'C128', '', '', 80, 17, 0.4, array('position'=>'C','align' => 'C', 'border-top'=>true, 'padding'=>2,'margin-top'=>2, 'fgcolor'=>array(0,0,0), 'bgcolor'=>'', 'text'=>false, 'font'=>'helvetica', 'fontsize'=>6, 'stretchtext'=>4), 'N'));
                }

                $pdf->AddPage();
                $html = $this->load->view('generarpdf/generarpdf_reciboestampilla', $this->data, TRUE);
                $pdf->writeHTML($html, true, false, true, false, '');

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

                $tramite = $this->codegen_model->getSelect(
                    'est_liquidartramites liquidacion',
                    'date_format(liquidacion.litr_fechaliquidacion,"%Y-%m-%d") AS litr_fechaliquidacion, est_tramites.tram_nombre, tramitadores.email as tramitador_email,tramitadores.direccion as tramitador_direccion,tramitadores.telefono as tramitador_telefono,liquidacion.litr_observaciones',
                    'WHERE litr_id = "'.$idcontrato.'"',
                    'LEFT JOIN est_tramites ON(est_tramites.tram_id = liquidacion.litr_tramiteid)
                    LEFT JOIN tramitadores ON(tramitadores.id = liquidacion.litr_tramitadorid)'
                );
                $this->data['tramite'] = $tramite[0];

                $liquidador = $this->codegen_model->getSelect('users','first_name,last_name','WHERE id = "'.$liquidacion->liqu_usuarioliquida.'"','');
                $this->data['liquidador'] = $liquidador[0];

             // create new PDF document
              $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

              // set document information
              $pdf->SetCreator(PDF_CREATOR);
              $pdf->SetAuthor('turrisystem');
              $pdf->SetTitle('Liquidación de estampillas');
              $pdf->SetSubject('Gobernación del Putumayo');
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
                $pdf->SetFont('times', 'BI', 8);

                foreach ($this->data['facturas'] as $key => $value)
                {
                    $this->barcode($value->fact_codigo);
                    $this->data['facturas'][$key]->codigo_barras = str_ireplace(array('~F1', '(390y)'), array('', '(3900)'), $value->fact_codigo);

                    // $numerofactura=str_pad($value->fact_id, 10, '0', STR_PAD_LEFT);
                    // $this->data['facturaestampilla']=$value;
                }

                $pdf->AddPage();
                $html = $this->load->view('generarpdf/generarpdf_reciboestampillatramite', $this->data, TRUE);  
                $pdf->writeHTML($html, true, false, true, false, '');

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
                    
                    /*
                    * Determina la distancia en y para imprimir el codigo qr
                    * dependiendo de la logitud del nombre del contribuyente
                    */
                    if(strlen($estampilla->cont_nombre) <= 146)
                    {
                        $distQRenY = 57.5;
                    }else
                        {
                            $distQRenY = 61;
                        }
                  
                  $this->load->library("Pdf");
                  $resolution= array(14, 9);
                  $pdf = new PDF(PDF_PAGE_ORIENTATION,'mm',array(92,141), true, 'UTF-8', false);


                  // set document information
                  $pdf->SetCreator(PDF_CREATOR);
                  $pdf->SetAuthor('turrisystem');                  
                  $pdf->SetTitle('Liquidación de estampillas - Rotulo No '.$estampilla->impr_codigopapel.' Factura');
                  $pdf->SetSubject('Gobernación del Putumayo');
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
                   $this->data['params'] = TCPDF_STATIC::serializeTCPDFtagParameters(array('http://qrcol.com/EPP/f.php?c='.$this->uri->segment(3), 'QRCODE,H', 110, $distQRenY, 16, 16, $style, 'T'));
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

    function certificadoPagoEstampilla()
    {
        if (!isset($_GET['id'])){
            redirect(base_url().'index.php/error_404');
        }

        $this->load->library('Pdf');
        $this->load->library('encrypt');

        $hash = $_GET['id'];
        $ids_pago = $this->encrypt->decode($hash, Equivalencias::generadorHash());

        # Para que valide que sean numeros separados por coma (tambien acepta uno)
        if(!preg_match('/^[0-9]+((,[0-9]+)*)?$/', $ids_pago)){
            redirect(base_url().'index.php/error_404');
        }

        $pagos = $this->codegen_model->getSelect(
            'pagos_estampillas AS pago',
            'factura.fact_liquidacionid AS id_liquidacion, pago.valor AS valor_cuota, factura.fact_valor AS valor_total,
                pago.numero AS cuota, factura.fact_nombre, pago.id, factura.fact_porcentaje AS porcentaje',
            'WHERE pago.id IN (' . $ids_pago . ')',
            'INNER JOIN est_facturas factura ON factura.fact_id = pago.factura_id'
        );

        $this->data['result'] = $this->liquidaciones_model->getrecibos(null, $pagos[0]->id_liquidacion);
        $liquidacion = $this->data['result'];

        $contrato = $this->codegen_model->getSelect(
            'con_contratos',
            'date_format(fecha_insercion,"%Y-%m-%d") AS fecha_insercion,cntr_contratistaid,cntr_objeto',
            'WHERE cntr_id = "'.$liquidacion->liqu_contratoid.'"'
        );
        $contratista = $this->codegen_model->getSelect(
            'con_contratistas',
            'cont_direccion,cont_telefono,cont_email',
            'WHERE cont_id = "'.$contrato[0]->cntr_contratistaid.'"'
        );
        $liquidador = $this->codegen_model->getSelect(
            'users',
            'first_name,last_name',
            'WHERE id = "'.$liquidacion->liqu_usuarioliquida.'"',
            ''
        );

        $this->data['contrato']     = $contrato[0];
        $this->data['contratista']  = $contratista[0];
        $this->data['liquidador']   = $liquidador[0];
        $this->data['pagos']        = $pagos;

        // create new PDF document
        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('turrisystem');
        $pdf->SetTitle('Pago de estampilla');
        $pdf->SetSubject('Gobernación del Putumayo');
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
        $pdf->SetFont('times', 'BI', 8);

        $pdf->AddPage();

        // set style for barcode
        $style = array(
            'position' => 'C',
            'padding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        $this->data['estilos_qr'] = array(
            '',
            'QRCODE,H',
            '',# Posicion x
            '',# Posicion y
            30,# Ancho
            30,# Alto
            $style,
            'B'# Alineacion
        );

        $html = $this->load->view('generarpdf/generarpdf_recibo_pago_estampilla', $this->data, TRUE);
        $pdf->writeHTML($html, true, false, true, false, '');

        // ---------------------------------------------------------

        //Close and output PDF document
        $pdf->Output('recibos_estampilla.pdf', 'I');
    }

    function generar_estampilla_retencion()
    {
        if (!isset($_GET['id'])){
            redirect(base_url().'index.php/error_404');
        }

        $this->load->library('Pdf');
        $this->load->library('encrypt');

        $hash = $_GET['id'];
        $id_pago = $this->encrypt->decode($hash, Equivalencias::generadorHash());

        $ultimo_pago = $this->codegen_model->get(
            'pagos_estampillas AS pago',
            'pago.factura_id, pago.fecha_insercion',
            'pago.id = "' . $id_pago . '"',
            1, null, true, ''
        );

        $pago = $this->liquidaciones_model->obtenerFacturasRetencion('factura.fact_id', $ultimo_pago->factura_id);
        $pago = $pago[0];

        # Valida si la estampilla se generará para un contrato o para un trámite

        $verificacionTramite = $this->codegen_model->get(
            'est_liquidaciones l',
            'l.liqu_tipocontrato',
            'l.liqu_id = '.$pago->id_liquidacion,
            1, null, true, ''
        );

        if($verificacionTramite->liqu_tipocontrato == 'Tramite')
        {
            $estampilla = $this->liquidaciones_model->getfactura_legalizada_tramite($pago->fact_id); 
            $estampilla->cntr_vigencia = date('Y', strtotime($estampilla->cntr_vigencia));

        }else
            {
                $estampilla = $this->liquidaciones_model->getfactura_legalizada($pago->fact_id); 
            }

        /*
        * Determina la distancia en y para imprimir el codigo qr
        * dependiendo de la logitud del nombre del contribuyente
        */
        if(strlen($estampilla->cont_nombre) <= 146)
        {
            $distQRenY = 57.5;
        }else
            {
                $distQRenY = 61;
            }

        $pdf = new PDF(PDF_PAGE_ORIENTATION,'mm',array(92,141), true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('turrisystem');
        $pdf->SetTitle('Liquidación de estampillas');
        $pdf->SetSubject('Gobernación del Putumayo');
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

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

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
        $this->data['params'] = TCPDF_STATIC::serializeTCPDFtagParameters(array(
            'http://qrcol.com/EPP/f.php?c='.$pago->fact_id,
            'QRCODE,H',
            110,
            $distQRenY,
            16,
            16,
            $style,
            'T'
        ));

        $pagado = floor($pago->valor_total - $pago->valor_pagado) == 0;

        if($pagado){
            $estampilla->pago_fecha = $ultimo_pago->fecha_insercion;
        }

        $this->data['estampilla'] = $estampilla;
        $html = $this->load->view('generarpdf/generarpdf_estampillalegalizada', $this->data, TRUE);  
        
        $pdf->writeHTML($html, true, false, true, false, '');

        if($pagado)
        {
            $pdf->SetAlpha(0.4);
            $pdf->Image($this->config->item('application_root') . 'images/pagado.png', 0, 0, 160, 100, '', '', '', false, 300, '', false, false, 0);
            $pdf->SetAlpha(1);
        }

        //Close and output PDF document
        $pdf->Output('estampilla_'.$estampilla->impr_codigopapel.'.pdf', 'I'); 
    }
}
