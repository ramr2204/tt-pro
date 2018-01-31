<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            regimenes
*   Ruta:              /application/controllers/regimenes.php
*   Descripcion:       controlador de regimenes
*   Fecha Creacion:    20/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-20
*
*/

class Regimenes extends MY_Controller {
    
  function __construct() 
  {
      parent::__construct();
	    $this->load->library('form_validation');		
		  $this->load->helper(array('form','url','codegen_helper'));
		  $this->load->model('codegen_model','',TRUE);

	}	
	
	function index()
  {
		  $this->manage();
	}

	function manage()
  {
      if ($this->ion_auth->logged_in()){

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('regimenes/manage')){

              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
              //template data
              $this->template->set('title', 'Administrar regimenes');
              $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
            
              $this->template->load($this->config->item('admin_template'),'regimenes/regimenes_list', $this->data);

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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('regimenes/add')) {

              $this->data['successmessage']=$this->session->flashdata('message');  
        		  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]|is_unique[con_regimenes.regi_nombre]');   
              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[500]');
              $this->form_validation->set_rules('iva', 'IVA', 'required|trim|xss_clean|numeric|');

              if ($this->form_validation->run() == false) {

                  $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
              } else {    

                  $data = array(
                        'regi_nombre' => $this->input->post('nombre'),
                        'regi_descripcion' => $this->input->post('descripcion'),
                        'regi_iva' => $this->input->post('iva')

                     );
                 
                    $respuestaProceso = $this->codegen_model->add('con_regimenes',$data);
    			        if ($respuestaProceso->bandRegistroExitoso) {

                      $this->session->set_flashdata('message', 'El régimen se ha creado con éxito');
                      redirect(base_url().'index.php/regimenes/add');
    			        } else {

    				          $this->data['errormessage'] = 'No se pudo registrar el régimen';

    			        }

    		      }
                
              $this->template->set('title', 'Nueva régimen');
              $this->template->load($this->config->item('admin_template'),'regimenes/regimenes_add', $this->data);
             
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('regimenes/edit')) {  

              $idregimen = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
              if ($idregimen==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un tipo de régimen para editar');
                  redirect(base_url().'index.php/regimenes');
              }
              $resultado = $this->codegen_model->get('con_regimenes','regi_nombre','regi_id = '.$idregimen,1,NULL,true);
              
              foreach ($resultado as $key => $value) {
                  $aplilo[$key]=$value;
              }
              
              if ($aplilo['regi_nombre']==$this->input->post('nombre')) {
                  
                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]');
              
              } else {

                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]|is_unique[con_regimenes.regi_nombre]');
              
              }

              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[500]');
              $this->form_validation->set_rules('iva', 'IVA', 'required|trim|xss_clean|numeric|');

              if ($this->form_validation->run() == false) {
                  
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                            
              } else {                                        

                    $data = array(
                          'regi_nombre' => $this->input->post('nombre'),
                          'regi_descripcion' => $this->input->post('descripcion'),
                          'regi_iva' => $this->input->post('iva')
                        );
                           
                	if ($this->codegen_model->edit('con_regimenes',$data,'regi_id',$idregimen) == TRUE) {
                        /*
                        * Actualiza el nombre del regimen en las posibles liquidaciones de los contratos
                        */
                        $where = 'liqu_regimenid = '.$idregimen;                                                                      
                        $datos['liqu_regimen'] = $this->input->post('nombre');   

                        $this->codegen_model->editWhere('est_liquidaciones',$datos,$where);

                      $this->session->set_flashdata('successmessage', 'El régimen se ha editado con éxito');
                      redirect(base_url().'index.php/regimenes/edit/'.$idregimen);
                      
                	} else {
                				  
                      $this->data['errormessage'] = 'No se pudo registrar el aplilo';

                	}
              }       
                  $this->data['successmessage']=$this->session->flashdata('successmessage');
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                	$this->data['result'] = $this->codegen_model->get('con_regimenes','regi_id,regi_nombre,regi_descripcion,regi_iva','regi_id = '.$idregimen,1,NULL,true);
                  $this->template->set('title', 'Editar régimen');
                  $this->template->load($this->config->item('admin_template'),'regimenes/regimenes_edit', $this->data);
                        
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('regimenes/delete')) {  
              if ($this->input->post('id')==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un tipo de régimen para eliminar');
                  redirect(base_url().'index.php/regimenes');
              }
              if (!$this->codegen_model->depend('con_contratistas','cont_regimenid',$this->input->post('id'))) {

                  $this->codegen_model->delete('con_regimenes','regi_id',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'El régimen se ha eliminado con éxito');
                  redirect(base_url().'index.php/regimenes');  

              } else {

                  $this->session->set_flashdata('errormessage', 'El régimen se encuentra en uso, no es posible eliminarlo.');
                  redirect(base_url().'index.php/regimenes/edit/'.$this->input->post('id'));

              }
                         
          } else {
              redirect(base_url().'index.php/error_404');       
          } 
      } else {
          redirect(base_url().'index.php/users/login');
      }
  }
    
 
  function datatable ()
  {
      if ($this->ion_auth->logged_in()) {
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('regimenes/manage') ) { 
              
              $this->load->library('datatables');
              $this->datatables->select('r.regi_id,r.regi_nombre,regi_iva,r.regi_descripcion');
              $this->datatables->from('con_regimenes r');

              if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('regimenes/edit')) {
                  
                  $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="'.base_url().'index.php/regimenes/edit/$1" class="btn btn-default btn-xs" title="Editar régimen"><i class="fa fa-pencil-square-o"></i></a>
                                                           </div>
                                                         </div>', 'r.regi_id');

              }  else {
                  
                  $this->datatables->add_column('edit', '', 'r.regi_id'); 
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
