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

class Contratos extends MY_Controller {
    
  function __construct() 
  {
      parent::__construct();
	    $this->load->library('form_validation');		
		  $this->load->helper(array('form','url','codegen_helper'));
		  $this->load->model('codegen_model','',TRUE);
      $this->load->model('liquidaciones_model','',TRUE);
	}	
	
	function index()
  {
		  $this->manage();
	}

	function manage()
  {
      if ($this->ion_auth->logged_in()){

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratos/manage')){

              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
              //template data
              $this->template->set('title', 'Administrar contratos');
              $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js',
                        'js/plugins/dataTables/jquery.dataTables.columnFilter.js',
                       );
            
              $this->template->load($this->config->item('admin_template'),'contratos/contratos_list', $this->data);

          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
              redirect(base_url().'index.php/users/login');
      }

  }
	
 function add()
  {        
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratistas/add')) {

              $this->data['successmessage']=$this->session->flashdata('message');  
              $valor=str_replace('.','',$this->input->post('valor'));
              $vigencia=explode("-", $this->input->post('fecha'));
              $this->form_validation->set_rules('contratistaid', 'contratista','required|trim|xss_clean|numeric|greater_than[0]');
              $this->form_validation->set_rules('tipocontratoid', 'Tipo de contrato','required|trim|xss_clean|numeric|greater_than[0]');
              $this->form_validation->set_rules('fecha', 'Fecha',  'required|trim|xss_clean');  
              $this->form_validation->set_rules('objeto', 'objeto',  'required|trim|xss_clean');  
              $this->form_validation->set_rules('numero', 'Número','required|trim|xss_clean|numeric|greater_than[0]');
              $this->form_validation->set_rules('valor', 'valor','required|trim|xss_clean'); 

              if ($this->form_validation->run() == false) {

                  $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
              } else {    

                  $data = array(
                        'cntr_contratistaid' => $this->input->post('contratistaid'),
                        'cntr_tipocontratoid' => $this->input->post('tipocontratoid'),
                        'cntr_fecha_firma' => $this->input->post('fecha'),
                        'cntr_numero' => $this->input->post('numero'),
                        'cntr_objeto' => $this->input->post('objeto'),
                        'cntr_valor' => $valor,
                        'cntr_vigencia' => $vigencia[0],
                     );
                 
                  if ($this->codegen_model->add('con_contratos',$data) == TRUE) {

                      $this->session->set_flashdata('message', 'El contrato se ha creado con éxito');
                      redirect(base_url().'index.php/contratos/add');
                  } else {

                      $this->data['errormessage'] = 'No se pudo registrar el contratista';

                  }

              }
              $this->template->set('title', 'Nueva aplicación');
              $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen',
                        'css/plugins/bootstrap/bootstrap-datetimepicker.css' => 'screen'
                    );
              $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js',
                        'js/plugins/bootstrap/moment.js',
                        'js/plugins/bootstrap/bootstrap-datetimepicker.js',
                        'js/autoNumeric.js'
                    );  
              $this->template->set('title', 'Ingreso manual de contrato');
              $this->data['tiposcontratos']  = $this->codegen_model->getSelect('con_tiposcontratos','tico_id,tico_nombre');
              $this->data['contratistas']  = $this->codegen_model->getSelect('con_contratistas','cont_id,cont_nombre,cont_nit');
              $this->template->load($this->config->item('admin_template'),'contratos/contratos_add', $this->data);
             
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  } 


	function edit()
  {    
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratos/edit')) {  

              $idcontrato = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
              if ($idcontrato==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un contrato para editar');
                  redirect(base_url().'index.php/contratos');
              }
              
              //$valor=str_replace('.','',$this->input->post('valor'));
              $vigencia=explode("-", $this->input->post('fecha'));
              $this->form_validation->set_rules('contratistaid', 'contratista','required|trim|xss_clean|numeric|greater_than[0]');
              $this->form_validation->set_rules('tipocontratoid', 'Tipo de contrato','required|trim|xss_clean|numeric|greater_than[0]');
              $this->form_validation->set_rules('fecha', 'Fecha',  'required|trim|xss_clean');  
              $this->form_validation->set_rules('objeto', 'objeto',  'required|trim|xss_clean');  
              $this->form_validation->set_rules('numero', 'Número','required|trim|xss_clean|numeric|greater_than[0]');
              $this->form_validation->set_rules('valor', 'valor','required|trim|xss_clean');  

              if ($this->form_validation->run() == false) {
                  
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                            
              } else {                            
                 
                  $data = array(
                        'cntr_contratistaid' => $this->input->post('contratistaid'),
                        'cntr_tipocontratoid' => $this->input->post('tipocontratoid'),
                        'cntr_fecha_firma' => $this->input->post('fecha'),
                        'cntr_numero' => $this->input->post('numero'),
                        'cntr_objeto' => $this->input->post('objeto'),
                        'cntr_valor' => $this->input->post('valor'),
                        'cntr_vigencia' => $vigencia[0],
                     ); 
                	if ($this->codegen_model->edit('con_contratos',$data,'cntr_id',$idcontrato) == TRUE) {

                      $this->session->set_flashdata('successmessage', 'El contrato se ha editado con éxito');
                      //redirect(base_url().'index.php/contratos/edit/'.$idcontrato);
                      
                	} else {
                				  
                      $this->data['errormessage'] = 'No se pudo registrar el contrato';

                	}
              }   
                  $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen',
                        'css/plugins/bootstrap/bootstrap-datetimepicker.css' => 'screen'
                    );
              $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js',
                        'js/plugins/bootstrap/moment.js',
                        'js/plugins/bootstrap/bootstrap-datetimepicker.js',
                        'js/autoNumeric.js'
                    );

                  $this->data['successmessage']=$this->session->flashdata('successmessage');
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                	$this->data['result'] = $this->codegen_model->get('con_contratos','cntr_id,cntr_contratistaid,cntr_tipocontratoid,cntr_fecha_firma,cntr_numero,cntr_objeto,cntr_valor','cntr_id = '.$idcontrato,1,NULL,true);
                  $this->data['tiposcontratos']  = $this->codegen_model->getSelect('con_tiposcontratos','tico_id,tico_nombre');
                  $this->data['contratistas']  = $this->codegen_model->getSelect('con_contratistas','cont_id,cont_nombre,cont_nit');
                  $this->template->set('title', 'Editar contrato');
                  $this->template->load($this->config->item('admin_template'),'contratos/contratos_edit', $this->data);
                        
          }else {
              redirect(base_url().'index.php/error_404');
          }
      } else {
          redirect(base_url().'index.php/users/login');
      }
        
  }
	
  function delete()
  {
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratos/delete')) {  
              if ($this->input->post('id')==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un contrato para eliminar');
                  redirect(base_url().'index.php/contratos');
              }
              if (!$this->codegen_model->depend('con_contratos','cntr_contratoid',$this->input->post('id'))) {

                  $this->codegen_model->delete('con_contratos','cntr_id',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'El contrato se ha eliminado con éxito');
                  redirect(base_url().'index.php/contratos');  

              } else {

                  $this->session->set_flashdata('errormessage', 'El contrato se encuentra en uso, no es posible eliminarlo.');
                  redirect(base_url().'index.php/contratos/edit/'.$this->input->post('id'));

              }
                         
          } else {
              redirect(base_url().'index.php/error_404');       
          } 
      } else {
          redirect(base_url().'index.php/users/login');
      }
  }
  
  function liquidaciones_datatable ()
  {
      if ($this->ion_auth->logged_in()) {
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratos/liquidar') ) { 
              
              $this->load->library('datatables');
              $this->datatables->select('c.cntr_id,c.cntr_numero,co.cont_nit,co.cont_nombre,c.cntr_fecha_firma,c.cntr_objeto,c.cntr_valor,el.eslo_nombre');
              $this->datatables->from('con_contratos c');
              $this->datatables->join('con_contratistas co', 'co.cont_id = c.cntr_contratistaid', 'left');
              $this->datatables->join('con_estadoslocales el', 'el.eslo_id = c.cntr_estadolocalid', 'left');

              if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratos/edit')) {
                  
                  $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="'.base_url().'index.php/contratos/edit/$1" class="btn btn-default btn-xs agrega" title="Editar contrato" id="$1"><i class="fa fa-pencil-square-o"></i></a>
                                                           </div>
                                                         </div>', 'c.cntr_id');

              }  else {
                  
                  $this->datatables->add_column('edit', '', 'c.cntr_id'); 
              }
              
              echo $this->datatables->generate();

          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }           
  }


 
  function datatable ()
  {
      if ($this->ion_auth->logged_in()) {
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratos/manage') ) { 
              
              $this->load->library('datatables');
              $this->datatables->select('c.cntr_id,c.cntr_numero,co.cont_nit,co.cont_nombre,c.cntr_fecha_firma,c.cntr_objeto,c.cntr_valor,c.cntr_vigencia');
              $this->datatables->from('con_contratos c');
              $this->datatables->join('con_contratistas co', 'co.cont_id = c.cntr_contratistaid', 'left');

              if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratos/edit')) {
                  
                  $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="'.base_url().'index.php/contratos/edit/$1" class="btn btn-default btn-xs agrega" title="Editar contrato" id="$1"><i class="fa fa-pencil-square-o"></i></a>
                                                           </div>
                                                         </div>', 'c.cntr_id');

              }  else {
                  
                  $this->datatables->add_column('edit', '', 'c.cntr_id'); 
              }
              
              echo $this->datatables->generate();

          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }           
  }
}
