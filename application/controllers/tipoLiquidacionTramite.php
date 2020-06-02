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
                'js/jquery.dataTables.defaults.js'
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

                $this->form_validation->set_rules('valor', 'Valor', 'required|numeric|trim|xss_clean');

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
                            'valor'       => $this->input->post('valor'),
                            'vigencia'    => date('Y'),
                            'tramite_id' => $respuestaProcesoLT->idInsercion
                        );

                        $respuestaProceso = $this->codegen_model->add('liquidacion_valor_vigencia_tramite',$data_lv);

                    }
                    else
                    {

                        $respuestaProcesoLT = $this->codegen_model->getSelect('liquidacion_tipo_tramites', 'id', 'WHERE id = ' . $this->input->post('tramite_existe'))[0];

                        $data_lv = array(
                            'valor'       => $this->input->post('valor'),
                            'vigencia'    => date('Y'),
                            'tramite_id' => $respuestaProcesoLT->id
                        );

                        $respuestaProceso = $this->codegen_model->add('liquidacion_valor_vigencia_tramite',$data_lv);
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

                $tramites_id = $this->codegen_model->getSelect('liquidacion_tipo_tramites as lt','lt.id as lt_id, lv.id as lv_id,lt.nombre,lt.estado,lv.valor,lv.vigencia',' WHERE lt.id = '.$idContratista .'',' INNER JOIN liquidacion_valor_vigencia_tramite as lv ON lv.tramite_id = lt.id')[0];

                $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]');   
                $this->form_validation->set_rules('valor',  'Valor', 'required|trim|xss_clean|max_length[256]');
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
                        $data = array(
                            'valor'    => $this->input->post('valor'),
                        );

                        $this->codegen_model->edit('liquidacion_valor_vigencia_tramite',$data,'id',$tramites_id->lv_id);

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

                $this->data['successmessage']=$this->session->flashdata('successmessage');
                $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                $this->data['result'] = $tramites_id;

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

                $codigos = explode(',', $this->input->post('id'));

                if (!$this->codegen_model->depend('liquidar_tramite_persona','tipo_tramite_valor',$codigos[1])) 
                {

                    $this->codegen_model->delete('liquidacion_valor_vigencia_tramite','id',$codigos[1]);
                    $this->codegen_model->delete('liquidacion_tipo_tramites','id',$codigos[0]);

                    $this->session->set_flashdata('successmessage', 'El contratista se ha eliminado con éxito');
                    redirect(base_url().'index.php/tipoLiquidacionTramite'); 
                } 
                else 
                {
                    $this->session->set_flashdata('errormessage', 'El contratista se encuentra en uso, no es posible eliminarlo.');
                    redirect(base_url().'index.php/tipoLiquidacionTramite/edit/'.$this->input->post('id'));

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
                        .'<div>'
                        .'<a href="'.base_url().'index.php/tipoLiquidacionTramite/edit/$1" class="btn btn-success btn-xs" title="Editar tipo trámite"><i class="fa fa-pencil-square-o"></i></a>'
                        .'</div>'
                        .'</div>', 'lt.id');
                }
                else 
                {             
                    $this->load->library('datatables');     
                    $this->datatables->add_column('edit', '', 'lt.id');
                }

                $this->datatables->select('lt.id,lt.nombre,lt.estado,lv.valor,lv.vigencia');
                $this->datatables->from('liquidacion_tipo_tramites lt');

              $this->datatables->join('liquidacion_valor_vigencia_tramite lv', 'lt.id = lv.tramite_id', 'INNER');


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
}
