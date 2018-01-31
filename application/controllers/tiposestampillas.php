<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            tiposestampillas
*   Ruta:              /application/controllers/tiposestampillas.php
*   Descripcion:       controlador de tiposestampillas
*   Fecha Creacion:    20/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-20
*
*/

class Tiposestampillas extends MY_Controller {
    
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tiposestampillas/manage')){

              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
              //template data
              $this->template->set('title', 'Administrar tipos estampillas');
              $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
            
              $this->template->load($this->config->item('admin_template'),'tiposestampillas/tiposestampillas_list', $this->data);

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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tiposestampillas/add')) {

              $this->data['successmessage']=$this->session->flashdata('message');  
        		  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]|is_unique[est_tiposestampillas.ties_nombre]');   
              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[500]');

              if ($this->form_validation->run() == false) {

                  $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
              } else {    

                  $data = array(
                        'ties_nombre' => $this->input->post('nombre'),
                        'ties_descripcion' => $this->input->post('descripcion')

                     );
                 
                        $respuestaProceso = $this->codegen_model->add('est_tiposestampillas',$data);
    			        if ($respuestaProceso->bandRegistroExitoso) {

                      $this->session->set_flashdata('message', 'El tipo de estampilla se ha creado con éxito');
                      redirect(base_url().'index.php/tiposestampillas/add');
    			        } else {

    				          $this->data['errormessage'] = 'No se pudo registrar el tipo de estampilla';

    			        }

    		      }
                
              $this->template->set('title', 'Nuevo tipo de estampilla');
              $this->template->load($this->config->item('admin_template'),'tiposestampillas/tiposestampillas_add', $this->data);
             
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tiposestampillas/edit')) {  

              $idtipocontrato = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
              if ($idtipocontrato==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un tipo de estampilla para editar');
                  redirect(base_url().'index.php/tiposestampillas');
              }
              $resultado = $this->codegen_model->get('est_tiposestampillas','ties_nombre','ties_id = '.$idtipocontrato,1,NULL,true);
              
              foreach ($resultado as $key => $value) {
                  $aplilo[$key]=$value;
              }
              
              if ($aplilo['ties_nombre']==$this->input->post('nombre')) {
                  
                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]');
              
              } else {

                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]|is_unique[est_tiposestampillas.ties_nombre]');
              
              }

              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[500]');

              if ($this->form_validation->run() == false) {
                  
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                            
              } else {                            
                  
                  $data = array(
                          'ties_nombre' => $this->input->post('nombre'),
                          'ties_descripcion' => $this->input->post('descripcion')
                   );
                           
                	if ($this->codegen_model->edit('est_tiposestampillas',$data,'ties_id',$idtipocontrato) == TRUE) {

                      $this->session->set_flashdata('successmessage', 'El tipo de estampilla se ha editado con éxito');
                      redirect(base_url().'index.php/tiposestampillas/edit/'.$idtipocontrato);
                      
                	} else {
                				  
                      $this->data['errormessage'] = 'No se pudo registrar el tipo de estampilla';

                	}
              }       
                  $this->data['successmessage']=$this->session->flashdata('successmessage');
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                	$this->data['result'] = $this->codegen_model->get('est_tiposestampillas','ties_id,ties_nombre,ties_descripcion','ties_id = '.$idtipocontrato,1,NULL,true);
                  $this->template->set('title', 'Editar tipo de estampilla');
                  $this->template->load($this->config->item('admin_template'),'tiposestampillas/tiposestampillas_edit', $this->data);
                        
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tiposestampillas/delete')) {  
              if ($this->input->post('id')==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un tipo de estampilla para eliminar');
                  redirect(base_url().'index.php/tiposestampillas');
              }

                  $this->codegen_model->delete('est_tiposestampillas','ties_id',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'El tipo de estampilla se ha eliminado con éxito');
                  redirect(base_url().'index.php/tiposestampillas');        
                         
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
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tiposestampillas/manage') ) { 
              
              $this->load->library('datatables');
              $this->datatables->select('t.ties_id,t.ties_nombre,t.ties_descripcion');
              $this->datatables->from('est_tiposestampillas t');

              if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tiposestampillas/edit')) {
                  
                  $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="'.base_url().'index.php/tiposestampillas/edit/$1" class="btn btn-default btn-xs" title="Editar tipo de estampilla"><i class="fa fa-pencil-square-o"></i></a>
                                                           </div>
                                                         </div>', 't.ties_id');

              }  else {
                  
                  $this->datatables->add_column('edit', '', 't.ties_id'); 
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
