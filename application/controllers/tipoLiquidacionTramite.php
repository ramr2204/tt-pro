<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            contratistas
*   Ruta:              /application/controllers/contratistas.php
*   Descripcion:       controlador de contratistas
*   Fecha Creacion:    20/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-20
*
*/

class TipoLiquidacionTramite extends MY_Controller 
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
        $this->manage();
    }

    function manage()
    {
        if ($this->ion_auth->logged_in())
        {

            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipoLiquidacionTramite/manage'))
            {

                $this->data['successmessage']=$this->session->flashdata('successmessage');
                $this->data['errormessage']=$this->session->flashdata('errormessage');
                $this->data['infomessage']=$this->session->flashdata('infomessage');
                  //template data
                $this->template->set('title', 'Administrar liquidación trámites');
                $this->data['style_sheets']= array(
                'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                );

                $this->data['javascripts']= array(
                'js/jquery.dataTables.min.js',
                'js/plugins/dataTables/dataTables.bootstrap.js',
                'js/jquery.dataTables.defaults.js',
                'https://cdn.datatables.net/plug-ins/1.10.21/api/fnReloadAjax.js'
                );

                $this->template->load($this->config->item('admin_template'),'liquidacionTramites/tipoliquidaciontramites_list', $this->data);

            } 
            else 
            {
              redirect(base_url().'index.php/error_404');
            }

        } 
        else 
        {
          redirect(base_url().'index.php/users/login');
        }

    }

    function add()
    {        
        if ($this->ion_auth->logged_in()) {

            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipoLiquidacionTramite/add')) 
            {

                $this->data['successmessage']=$this->session->flashdata('message');

                if($this->input->post('tramite_existe') == '0')
                { 
                    $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
                }

                $this->data['result']['tramite_existe'] = $this->codegen_model->getSelect('liquidacion_tipo_tramites','id,nombre');

                if ($this->form_validation->run() == false) {

                    $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
                } 
                else 
                {

                    if($this->input->post('tramite_existe') == '0')
                    {
                        $data_lt = array(
                            'nombre' => $this->input->post('nombre'),
                        );

                        $respuestaProcesoLT = $this->codegen_model->add('liquidacion_tipo_tramites',$data_lt);

                        $data_lv = array(
                            'vigencia'    => date('Y'),
                            'tramite_id' => $respuestaProcesoLT->idInsercion
                        );

                    }
                    else
                    {

                        $respuestaProcesoLT = $this->codegen_model->getSelect('liquidacion_tipo_tramites', 'id', 'WHERE id = ' . $this->input->post('tramite_existe'))[0];

                        $data_lv = array(
                            'vigencia'    => date('Y'),
                            'tramite_id'  => $respuestaProcesoLT->id
                        );

                    }

                    $respuestaProceso = $this->codegen_model->add('liquidacion_valor_vigencia_tramite',$data_lv);

                    //como la cantidad de nombres debe ser igual a la de conceptos, entonces basta con count() solamente a una

                    for ($i=0; $i < count($this->input->post('nombre_concepto')); $i++) { 
                        $tramites_conceptos = array(
                            'tramite_valor_id' =>  $respuestaProceso->idInsercion,
                            'nombre_concepto'  =>  $this->input->post('nombre_concepto')[$i],
                            'valor_concepto'   => $this->input->post('valor_concepto')[$i]
                        );

                        $respuestaTramConceptos = $this->codegen_model->add('tramites_conceptos',$tramites_conceptos);

                    }


                    if ($respuestaProceso->bandRegistroExitoso) {

                        $this->session->set_flashdata('message', 'El tipo liquidación se ha creado con éxito');
                        redirect(base_url().'index.php/tipoLiquidacionTramite/add', $this->data);
                    } else {

                        $this->data['errormessage'] = 'No se pudo registrar el tipo liquidación';

                    }

                }

                $this->template->set('title', 'Nueva aplicación');
                $this->data['style_sheets']= array(
                'css/chosen.css' => 'screen'
                );

                $this->data['javascripts']= array(
                'js/chosen.jquery.min.js'
                );  

                $this->template->set('title', 'Nuevo tipo liquidación');
                $this->template->load($this->config->item('admin_template'),'liquidacionTramites/tipoliquidaciontramites_add', $this->data);

            } 
            else 
            {
                redirect(base_url().'index.php/error_404');
            }

        } else {
          redirect(base_url().'index.php/users/login');
        }

    }	


    function edit()
    {    
         if ($this->ion_auth->logged_in()) {

            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipoLiquidacionTramite/edit')) {  

                $idContratista = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;


                if ($idContratista=='')
                {
                    $this->session->set_flashdata('infomessage', 'Debe elegir un tipo trámite para editar');
                    redirect(base_url().'index.php/tipoLiquidacionTramite');
                }

                $tramites_id = $this->codegen_model->getSelect('liquidacion_tipo_tramites as lt','lt.id as lt_id, lv.id as lv_id,lt.nombre,lt.estado,lv.vigencia',' WHERE lv.id = '.$idContratista .'',' INNER JOIN liquidacion_valor_vigencia_tramite as lv ON lv.tramite_id = lt.id')[0];

                $conceptos_tramites = $this->codegen_model->getSelect('tramites_conceptos tc','tc.id,tc.nombre_concepto,valor_concepto', 'WHERE tc.tramite_valor_id = '.$tramites_id->lv_id);

                $this->form_validation->set_rules('estado', 'Estado', 'required|trim|xss_clean|max_length[256]'); 


                if ($this->form_validation->run() == false) {

                    $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);

                } else {                            

                    $data = array(
                        'nombre'   => $this->input->post('nombre'),
                        'estado'   => $this->input->post('estado'),
                    );

                    if ($this->codegen_model->edit('liquidacion_tipo_tramites',$data,'id',$tramites_id->lt_id) == TRUE) 
                    {
                        //Eliminamos los conceptos existentes para ingresar los nuevos
                        $this->codegen_model->delete('tramites_conceptos','tramite_valor_id',$tramites_id->lv_id);

                        //como la cantidad de nombres debe ser igual a la de conceptos, entonces basta con count() solamente a una

                        for ($i=0; $i < count($this->input->post('nombre_concepto')); $i++) 
                        { 
                            $tramites_conceptos = array(
                                'tramite_valor_id' =>  $tramites_id->lv_id,
                                'nombre_concepto'  =>  $this->input->post('nombre_concepto')[$i],
                                'valor_concepto'   => $this->input->post('valor_concepto')[$i]
                            );

                            $this->codegen_model->add('tramites_conceptos',$tramites_conceptos);
                        }

                        $this->session->set_flashdata('successmessage', 'El tipo trámite se ha editado con éxito');
                        redirect(base_url().'index.php/tipoLiquidacionTramite/edit/'.$idContratista);
                        
                    } 
                    else
                    {
                        $this->data['errormessage'] = 'No se pudo registrar el aplicativo';
                    }
                }   

                $this->data['style_sheets']= array(
                    'css/chosen.css' => 'screen'
                );

                $this->data['javascripts']= array(
                    'js/chosen.jquery.min.js'
                ); 

                $this->data['result']['tramites']  = $tramites_id;
                $this->data['result']['conceptos'] = $conceptos_tramites;

                $this->data['successmessage']=$this->session->flashdata('successmessage');
                $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 

                $this->template->set('title', 'Editar tipo trámite');
                $this->template->load($this->config->item('admin_template'),'liquidacionTramites/tipoliquidaciontramites_edit', $this->data);

            }
            else 
            {
                redirect(base_url().'index.php/error_404');
            }
        } 
        else 
        {
            redirect(base_url().'index.php/users/login');
        }


    }

    function delete()
    {
        if ($this->ion_auth->logged_in()) {

            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipoLiquidacionTramite/delete')) {  
                if ($this->input->post('id')==''){
                    $this->session->set_flashdata('infomessage', 'Debe elegir un tipo trámite para eliminar');
                    redirect(base_url().'index.php/tipoLiquidacionTramite');
                }

                $id = $this->input->post('id');

                if (!$this->codegen_model->depend('liquidar_tramite_persona','tipo_tramite_valor',$id)) 
                {

                    $this->codegen_model->delete('tramites_conceptos','tramite_valor_id',$id);

                    $this->codegen_model->delete('liquidacion_valor_vigencia_tramite','id',$id);

                    $this->session->set_flashdata('successmessage', 'El Tipo Trámite se ha eliminado con éxito');
                    redirect(base_url().'index.php/tipoLiquidacionTramite'); 
                } 
                else 
                {
                    $this->session->set_flashdata('errormessage', 'El Tipo Trámite se encuentra en uso, no es posible eliminarlo.');
                    redirect(base_url().'index.php/tipoLiquidacionTramite/edit/'.$id);

                }

            } 
            else 
            {
                redirect(base_url().'index.php/error_404');       
            } 
        } 
        else 
        {
          redirect(base_url().'index.php/users/login');
        }
    }


    function datatable ()
    {
        if ($this->ion_auth->logged_in()) {

            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipoLiquidacionTramite/manage') ) 
            { 

                /*
                * Se Valida si el usuario tiene la opcion de editar tipo tramite
                * para renderizar el boton de editar
                */            
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipoLiquidacionTramite/edit')) 
                {
                    $this->load->library('datatables');
                    $this->datatables->add_column('edit', '<div class="btn-toolbar">'
                        .'<a href="'.base_url().'index.php/tipoLiquidacionTramite/edit/$2" class="btn btn-success btn-xs" title="Editar tipo trámite"><i class="fa fa-pencil-square-o"></i></a>&nbsp'
                        .'<button type="button" class="btn btn-info btn-xs btn-consultar-modal-conceptos" title="Ver conceptos trámite" value="$2"><i class="fa fa-search-plus"></i></button>'
                        .'</div>', 'lt_id,lv_id');
                }
                else 
                {             
                    $this->load->library('datatables');     
                    $this->datatables->add_column('edit', '', 'lv.id');
                }

                $this->datatables->select('lv.id AS lv_id,lv.vigencia,lt.id AS lt_id,lt.nombre,lt.estado');
                $this->datatables->from('liquidacion_valor_vigencia_tramite lv');

                $this->datatables->join('liquidacion_tipo_tramites lt', 'lt.id = lv.tramite_id', 'INNER');

                echo $this->datatables->generate();

                } 
                else 
                {
                    redirect(base_url().'index.php/error_404');
                }

            } 
            else
            {
                redirect(base_url().'index.php/users/login');
            }           
        }

    function dataTableConceptos()
    {
        $id = $_GET['id'];
        $this->load->library('datatables');
        $this->datatables->select('lv.id AS lv_id,lv.nombre_concepto,lv.valor_concepto');
        $this->datatables->where('tramite_valor_id', $id);
        $this->datatables->from('tramites_conceptos lv');


        echo $this->datatables->generate();

    }
}
