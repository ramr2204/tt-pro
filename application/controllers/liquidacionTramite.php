<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
error_reporting(0);
/**
*   Nombre:            contratistas
*   Ruta:              /application/controllers/liquidacionTramite.php
*   Descripcion:       controlador de liquidaciones trramites
*   Fecha Creacion:    20/may/2014
*   @author            Maria Monica Gutierrez Torres <monica.gutierrez@turrisystem.com>
*   @version           2014-05-20
*
*/
require_once(APPPATH.'libraries/tcpdf/tcpdf.php');
require_once(APPPATH.'libraries/numeroletras/src/NumeroALetras.php');
require_once APPPATH.'libraries/barcodegen/class/BCGFontFile.php';
require_once APPPATH.'libraries/barcodegen/class/BCGColor.php';
require_once APPPATH.'libraries/barcodegen/class/BCGDrawing.php';
require_once APPPATH.'libraries/barcodegen/class/BCGgs1128.barcode.php';

class LiquidacionTramite extends MY_Controller
{

    
    function __construct() 
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('HelperGeneral');
        $this->load->helper('MYPDF');
        $this->load->helper(array('form','url','codegen_helper'));
        $this->load->model('codegen_model','',TRUE);
    }   
    
    function index()
    {
        $this->manage();
    }

    function add()
    {        
        if ($this->ion_auth->logged_in()) {

            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidacionTramite/add')) 
            {

                $this->data['successmessage']=$this->session->flashdata('message');  
                $this->form_validation->set_rules('ndocumento', 'Número documento', 'required|numeric');
                $this->form_validation->set_rules('tipo_documento', 'Tipo documento', 'required|numeric');
                $this->form_validation->set_rules('primer_nombre', 'Primer nombre', 'required');
                $this->form_validation->set_rules('segundo_nombre', 'Segundo nombre', 'required');
                $this->form_validation->set_rules('primer_apellido', 'Primer apellido', 'required');
                $this->form_validation->set_rules('segundo_apellido', 'Segundo apellido', 'required');
                $this->form_validation->set_rules('telefono1', 'Telefono 1', 'required|numeric');
                $this->form_validation->set_rules('direccion', 'Direccion', 'required');
                $this->form_validation->set_rules('tipo_tramite', 'Tipo tramite', 'required');
                $this->form_validation->set_rules('departamento_residencia', 'Departamento residencia', 'required');
                $this->form_validation->set_rules('municipio', 'municipio', 'required');

                if ($this->form_validation->run() == false) {

                    $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
                } 
                else 
                {
                    $data = array(
                        'ndocumento'         => $this->input->post('ndocumento'),
                        'primer_nombre'      => $this->input->post('primer_nombre'),
                        'segundo_nombre'     => $this->input->post('segundo_nombre'),
                        'primer_apellido'    => $this->input->post('primer_apellido'),
                        'segundo_apellido'   => $this->input->post('segundo_apellido'),
                        'telefono1'          => $this->input->post('telefono1'),
                        'telefono2'          => $this->input->post('telefono2'),
                        'direccion'          => $this->input->post('direccion'),
                        'tipo_tramite_valor' => $this->input->post('tipo_tramite'),
                        'tipo_documento'     => $this->input->post('tipo_documento'),
                        'departamento_residencia' => $this->input->post('departamento_residencia'),
                        'municipio'          => $this->input->post('municipio'),
                        'fecha_creacion'     => date('Y-m-d H:i:s')
                    );
                    
                    $respuestaProceso = $this->codegen_model->add('liquidar_tramite_persona',$data);

                    //consultar vigencia
                    $consultarTramite = $this->codegen_model->getSelect('liquidacion_valor_vigencia_tramite','vigencia', ' WHERE id = '. $this->input->post('tipo_tramite'))[0];

                    //suma de conceptos
                    $sumConceptos = $this->codegen_model->getSelect('tramites_conceptos','SUM(valor_concepto) AS valor', ' WHERE tramite_valor_id = '. $this->input->post('tipo_tramite'))[0];

                    //415 + codigo que de el banco + 820 + numerofactura + 3900 valor factura + 96 + fechavencimieno

                    //$tex_barcode = '415' . '000' . '8020' . $respuestaProceso->idInsercion . $consultarTramite->vigencia. '3900' .  str_pad((int) $sumConceptos, 10, "0", STR_PAD_LEFT) . '96' . str_replace('-','','00000000');

                    //Deben quedar de 10 digitos donde
                    //415 + codigo que de el banco + 820 + numerofactura + 3900 valor factura + 96 + fechavencimieno

                    $valorFactura       = str_pad($sumConceptos->valor, 10, 0, STR_PAD_LEFT);
                    $consecutivoFactura = $respuestaProceso->idInsercion . $consultarTramite->vigencia;

                    //por ahora 7709085131274
                    $codigoParaBarra='(415)'.'7709085131274'.'~F1(8020)'.$consecutivoFactura.'~F1(390y)'.$valorFactura;

                    //editar numero_factura
                    $data_editar = array(
                        'numero_factura' => $respuestaProceso->idInsercion . $consultarTramite->vigencia,
                        'codigo_barras'  => $codigoParaBarra,
                    );

                    $this->codegen_model->edit('liquidar_tramite_persona',$data_editar,'id',$respuestaProceso->idInsercion);

                    if ($respuestaProceso->bandRegistroExitoso) {

                        $this->session->set_flashdata('message', 'La liquidación trámite se ha creado con exito');
                        $this->session->set_flashdata('id', $respuestaProceso->idInsercion);
                        redirect(base_url().'index.php/liquidacionTramite/add');
                    } else {

                        $this->data['errormessage'] = 'No se pudo registrar la liquidación';

                    }

                }

                $this->template->set('title', 'Nueva aplicación');
                $this->data['style_sheets']= array(
                'css/chosen.css' => 'screen'
                );

                $this->data['javascripts']= array(
                'js/chosen.jquery.min.js'
                );  

                 $this->data['result'] = array(
                    'departamentos' => $this->codegen_model->getSelect('par_departamentos','depa_id,depa_nombre'),
                    'tipo_tramites' => $this->codegen_model->getSelect('liquidacion_tipo_tramites as lt','lv.id as lv_id,lt.nombre,lv.vigencia', 'WHERE lv.vigencia = '. date('Y'), 'INNER JOIN liquidacion_valor_vigencia_tramite as lv on lt.id = lv.tramite_id'),
                    'tipo_documento' => $this->codegen_model->getSelect('tipo_documento','id,nombre,sigla'),
                );

                $this->template->set('title', 'Nueva liquidación trámite');
                $this->template->load($this->config->item('admin_template'),'liquidacionTramites/liquidacionpersonatramite', $this->data);

            } 
            else 
            {
                redirect(base_url().'index.php/error_404');
            }

        } else {
          redirect(base_url().'index.php/users/login');
        }

    }   

    function consultarTramite()
    {
        $tramites_vigencia = $this->codegen_model->getSelect('liquidacion_valor_vigencia_tramite lv', 'lv.id, lt.nombre', 'WHERE lv.vigencia = ' . $_GET['vigencia_tramite'], 'INNER JOIN liquidacion_tipo_tramites lt on lt.id = lv.tramite_id');

        echo json_encode($tramites_vigencia);
    }

    function consultarMunicipios()
    {
        $deptos = $this->codegen_model->getSelect('par_municipios','muni_id,muni_nombre',' WHERE muni_departamentoid = '.$_GET['depto']);

        echo json_encode($deptos);
    }

    function pdf()
    {
        error_reporting(0);
        ob_end_clean();

        $joins = ' INNER JOIN liquidacion_valor_vigencia_tramite ON liquidacion_valor_vigencia_tramite.id = liquidar_tramite_persona.tipo_tramite_valor'.
            ' INNER JOIN liquidacion_tipo_tramites ON liquidacion_tipo_tramites.id = liquidacion_valor_vigencia_tramite.tramite_id'.
            ' INNER JOIN par_departamentos ON par_departamentos.depa_id = liquidar_tramite_persona.departamento_residencia'.
            ' INNER JOIN par_municipios ON par_municipios.muni_id = liquidar_tramite_persona.municipio'.
            ' INNER JOIN tipo_documento ON tipo_documento.id = liquidar_tramite_persona.tipo_documento'
            ;

        $consultarParametros = $this->codegen_model->getSelect('liquidar_tramite_persona','*,tipo_documento.nombre AS nombre_documento, liquidacion_tipo_tramites.nombre AS nombre_tramite, liquidacion_valor_vigencia_tramite.id AS id_tipo_tramite, liquidar_tramite_persona.id AS persona_tramite ',' WHERE liquidar_tramite_persona.id = '.$_GET['id'], $joins)[0];

        $conceptos = $this->codegen_model->getSelect('tramites_conceptos', '*', 'WHERE tramite_valor_id = '. $consultarParametros->id_tipo_tramite);
        $sumConceptos = $this->codegen_model->getSelect('tramites_conceptos', 'SUM(valor_concepto) AS valor', 'WHERE tramite_valor_id = '. $consultarParametros->id_tipo_tramite)[0];

        //establecerHTML
        
            
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // add a page
        $pdf->AddPage();

        $this->barcode($consultarParametros->codigo_barras);

        // CODE 39 EXTENDED
        $this->data['codebar'] = str_ireplace(array('~F1', '(390y)'), array('', '(3900)'), $consultarParametros->codigo_barras);

        $numeroLetras = new NumeroAletras;
        $this->data['consultarParametros'] = $consultarParametros;
        $this->data['conceptos'] =  $conceptos;
        $this->data['sumConceptos'] =  $sumConceptos->valor;
        $this->data['numeroLetras'] = $numeroLetras::convertir($sumConceptos->valor);
        $this->data['id'] = $_GET['id'];

        $html_barcode = $this->load->view('generarBarcode/barcodeFacturas', $this->data, TRUE);  

        $tabla_valores .= $html_barcode;

        $tabla_valores .= $div_fecha;

        $pdf->writeHTML($tabla_valores, true, false, true, false, '');

        // ---------------------------------------------------------

        //Close and output PDF document
        $pdf->Output('example_003.pdf', 'I');
    }

    public function establecerHTMLpdf($consultarParametros, $conceptos)
    {
       
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
