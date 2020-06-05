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

        //establecerHTML
        $htmlDivs = self::establecerHTMLpdf($consultarParametros);
            
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // add a page
        $pdf->AddPage();

        // set some text to print
        $html = $htmlDivs['htmlInformacion'];

        // output the HTML content
        $pdf->writeHTMLCell(0, 0, '', '36', $html);

        $tabla_valores = $htmlDivs['htmlTablaValores'];


        $pdf->writeHTMLCell(0, 0, '', '74', $tabla_valores);

        $div_pesos = $htmlDivs['htmlDivPrecios'];

        $pdf->writeHTMLCell(0, 0, '', '93', $div_pesos);

        // CODE 39 EXTENDED
        $this->barcode($consultarParametros->codigo_barras);

        $this->data['codebar'] = str_ireplace(array('~F1', '(390y)'), array('', '(3900)'), $consultarParametros->codigo_barras);

        $html_barcode = $this->load->view('generarBarcode/barcodeFacturas', $this->data, TRUE);  

        $pdf->writeHTMLCell(0,0,50,104,$html_barcode);
        $pdf->writeHTMLCell(0,0,64,119, '<br> <small>'.$this->data['codebar'].'</small>');

        $pdf->Ln();

        $div_fecha = '<div style="font-size:9px">'.date('d/m/Y H:i:s').'</div>';

        $pdf->writeHTMLCell(0, 0, '', '128', $div_fecha);

        $div_fecha = '<div style="font-size:9px">--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</div>';

        $pdf->writeHTMLCell(0, 0, '', '137', $div_fecha);

        //////////////////////////////////////////////////////////////////
        //OTRO HEAD

        $divHead = $htmlDivs['htmlHead'];

        $pdf->writeHTMLCell(0, 0, '', '147', $divHead);

        //LOGOS
        $image_escudo = K_PATH_IMAGES.'gobernacion_tolima1.png';
        $image_refran = K_PATH_IMAGES.'gobernacion_tolima2.png';

        $pdf->Image($image_refran, 160, 150, 36, 15, 'png', '', 'T', true, 600, '', false, false, 0, false, false, false);
        $pdf->Image($image_escudo, 13, 145, 35, 25, 'png', '', 'T', true, 100, '', false, false, 0, false, false, false);


        // output the HTML content
        $pdf->writeHTMLCell(0, 0, '', '176', $html);

        $tabla_valores = $htmlDivs['htmlTablaValores'];


        $pdf->writeHTMLCell(0, 0, '', '215', $tabla_valores);

        $div_pesos = $htmlDivs['htmlDivPrecios'];

        $pdf->writeHTMLCell(0, 0, '', '234', $div_pesos);

        $pdf->writeHTMLCell(0, 0, 50, 245, $html_barcode);

        $pdf->writeHTMLCell(0,0,64,260, '<br> <small>'.$this->data['codebar'].'</small>');

        $pdf->Ln();

        $div_fecha = '<div style="font-size:9px">'.date('d/m/Y H:i:s').'</div>';

        $pdf->writeHTMLCell(0, 0, '', '270', $div_fecha);


        // ---------------------------------------------------------

        //Close and output PDF document
        $pdf->Output('example_003.pdf', 'I');
    }

    public function establecerHTMLpdf($consultarParametros)
    {
        $numeroLetras = new NumeroAletras;
        // set some text to print
        $htmlInformacion = '
            <style>
                .letra-tama
                {
                    font-size:10px;
                    font-style: italic;
                }
                .encabezados
                {
                    font-weight: bold;
                }
            </style>
            <table cellpadding="2" cellspacing="3">
                <tr>
                    <td class="letra-tama encabezados">CÓDIGO</td>
                    <td class="letra-tama">'.$consultarParametros->numero_factura.'</td>
                    <td class="letra-tama encabezados">FECHA</td>
                    <td class="letra-tama">'.$consultarParametros->fecha_creacion.'</td>
                </tr>
                <tr>
                    <td class="letra-tama encabezados">DOCUMENTO</td>
                    <td class="letra-tama">'.$consultarParametros->nombre_documento.'</td>
                    <td class="letra-tama encabezados">NUMERO DOCUMENTO</td>
                    <td class="letra-tama">'.$consultarParametros->ndocumento.'</td>
                </tr>
                <tr> 
                    <td class="letra-tama encabezados">DIRECCION</td>
                    <td class="letra-tama">'.$consultarParametros->direccion.'</td>
                    <td class="letra-tama encabezados">TELEFONO</td>
                    <td class="letra-tama">'.$consultarParametros->telefono1.'</td>
                </tr>
                <tr>
                    
                </tr>
                <tr>
                    <td class="letra-tama encabezados">TELEFONO 2</td>
                    <td class="letra-tama">'.$consultarParametros->telefono2.'</td>
                    <td class="letra-tama encabezados">NOMBRE</td>
                    <td class="letra-tama">'.$consultarParametros->primer_nombre. ' '. $consultarParametros->segundo_nombre. ' '. $consultarParametros->primer_apellido . ' '. $consultarParametros->segundo_apellido.'</td>
                <tr>
            </table>';

        $htmlTablaValores = '
            <style>
                .letra-tama
                {
                    font-size:10px;
                    font-style: italic;
                }
                .encabezados
                {
                    font-weight: bold;
                }
                table td {
                  border: 1px solid black;
                  padding: 2px;
                }
                .sin-borde {
                  border: none
                }
            </style>
            <table style="border-collapse: collapse;">
                <tr>
                    <td class="letra-tama encabezados">CODIGO TRÁMITE</td>
                    <td class="letra-tama encabezados">TIPO TRÁMITE</td>
                    <td class="letra-tama encabezados">VIGENCIA</td>
                    <td class="letra-tama encabezados">VALOR</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="letra-tama">'.$consultarParametros->id_tipo_tramite.'</td>
                    <td class="letra-tama">'.$consultarParametros->nombre_tramite.'</td>
                    <td class="letra-tama">'.$consultarParametros->vigencia.'</td>
                    <td class="letra-tama">'.$consultarParametros->valor.'</td>
                    
                    <td></td>
                    
                </tr>
                <tr>
                    <td class="letra-tama sin-borde"></td>
                    <td class="letra-tama sin-borde"></td>
                    <td class="letra-tama sin-borde"></td>
                    <td class="letra-tama sin-borde encabezados">SUBTOTAL</td>
                    <td class="letra-tama sin-borde">'.$consultarParametros->valor.'</td>
                </tr>
            </table>';

        $htmlDivPrecios =  '<div>'.$numeroLetras::convertir($consultarParametros->valor).'PESOS MDA, CTE.'.'</div>';


        $htmlHead = '<table align="center" cellspacing="">
                        <tr>
                            <td style="font-size:10px">GOBERNACION DEL PUTUMAYO</td>
                        </tr>
                        <tr>
                            <td style="font-size:10px">Secretaría de hacienda departamental</td>
                        </tr>
                        <tr>
                            <td style="font-size:10px">Nit: 800094164-4</td>
                        </tr>
                        <tr>
                            <td style="font-size:10px">Liquidación de impuestos</td>
                        </tr>
                        <tr>
                            <td style="font-size:10px">Número liquidación: '.$_GET['id'].'</td>
                        </tr>
                    </table>';

        return array(
            'htmlInformacion'  => $htmlInformacion,
            'htmlTablaValores' => $htmlTablaValores,
            'htmlDivPrecios'   => $htmlDivPrecios,
            'htmlHead'         => $htmlHead,
            'imageEscudo'      => K_PATH_IMAGES.'escudo_putuma.jpg',
            'imageRefran'      => K_PATH_IMAGES.'refran_gog_putumayo.jpg',
        );

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
                    $consultarTramite = $this->codegen_model->getSelect('liquidacion_valor_vigencia_tramite','vigencia,valor', ' WHERE id = '. $this->input->post('tipo_tramite'))[0];
                            

                    //415 + codigo que de el banco + 820 + numerofactura + 3900 valor factura + 96 + fechavencimieno

                    $tex_barcode = '415' . '000' . '8020' . $respuestaProceso->idInsercion . $consultarTramite->vigencia. '3900' .  str_pad((int) $consultarTramite->valor, 10, "0", STR_PAD_LEFT) . '96' . str_replace('-','','00000000');

                    //Deben quedar de 10 digitos donde
                    //415 + codigo que de el banco + 820 + numerofactura + 3900 valor factura + 96 + fechavencimieno

                    $numerofactura = $respuestaProceso->idInsercion . $consultarTramite->vigencia;
                    $valorFactura = str_pad($consultarTramite->valor, 10, 0, STR_PAD_LEFT);
                    $consecutivoFactura = str_pad($numerofactura, 10, 0, STR_PAD_LEFT);

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
                    'tipo_tramites' => $this->codegen_model->getSelect('liquidacion_tipo_tramites as lt','lv.id,lt.nombre,lv.vigencia', 'WHERE lv.vigencia = '. date('Y'), 'INNER JOIN liquidacion_valor_vigencia_tramite as lv on lt.id = lv.tramite_id'),
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
