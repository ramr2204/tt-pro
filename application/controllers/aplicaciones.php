<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            aplicaciones
*   Ruta:              /application/controllers/aplicaciones.php
*   Descripcion:       controlador de aplicaciones
*   Fecha Creacion:    12/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-12
*
*/

class Aplicaciones extends MY_Controller {
    
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

          if ($this->ion_auth->is_admin()){

              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              //template data
              $this->template->set('title', 'Administrar aplicaciones');
              $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
            
              $this->template->load($this->config->item('admin_template'),'aplicaciones/aplicaciones_list', $this->data);

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

          if ($this->ion_auth->is_admin()) {

              $this->data['successmessage']=$this->session->flashdata('message');  
        		  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');   
              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[500]');
              $this->form_validation->set_rules('procesoid', 'Proceso',  'required|numeric|greater_than[0]');  
              $this->form_validation->set_rules('estadoid', 'Estado',  'required|numeric'); 

              if ($this->form_validation->run() == false) {

                  $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
              } else {    

                  $data = array(
                        'apli_nombre' => $this->input->post('nombre'),
                        'apli_descripcion' => $this->input->post('descripcion'),
                        'apli_procesoid' => $this->input->post('procesoid'),
                        'apli_estadoid' => $this->input->post('estadoid')

                     );
                    
                    $respuestaProceso = $this->codegen_model->add('adm_aplicaciones',$data);
                    if ($respuestaProceso->bandRegistroExitoso) 
                    {

                      $this->session->set_flashdata('message', 'La aplicación se ha creado con éxito');
                      redirect(base_url().'index.php/aplicaciones/add');
                    } else {

    				          $this->data['errormessage'] = 'No se pudo registrar el aplicación';

    			        }

    		      }
                
              $this->template->set('title', 'Nueva aplicación');
              $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen'
                    );
              $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js'
                    );
              $this->data['procesos']  = $this->codegen_model->getSelect('adm_procesos','proc_id,proc_nombre');
              $this->data['estados']  = $this->codegen_model->getSelect('adm_estados','esta_id,esta_nombre');
              $this->template->load($this->config->item('admin_template'),'aplicaciones/aplicaciones_add', $this->data);
             
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

          if ($this->ion_auth->is_admin()) {  

              $idaplilo = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
              $resultado = $this->codegen_model->get('adm_aplicaciones','apli_nombre','apli_id = '.$idaplilo,1,NULL,true);
              
              foreach ($resultado as $key => $value) {
                  $aplilo[$key]=$value;
              }
              
              if ($aplilo['apli_nombre']==$this->input->post('nombre')) {
                  
                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
              
              } else {

                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
              
              }

              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[500]');
              $this->form_validation->set_rules('procesoid', 'Proceso',  'required|trim|xss_clean|numeric|greater_than[0]');  
              $this->form_validation->set_rules('estadoid', 'Estado',  'required|trim|xss_clean|numeric');   

              if ($this->form_validation->run() == false) {
                  
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                            
              } else {                            
                  
                  $data = array(
                          'apli_nombre' => $this->input->post('nombre'),
                          'apli_descripcion' => $this->input->post('descripcion'),
                          'apli_procesoid' => $this->input->post('procesoid'),
                          'apli_estadoid' => $this->input->post('estadoid')
                   );
                           
                	if ($this->codegen_model->edit('adm_aplicaciones',$data,'apli_id',$idaplilo) == TRUE) {

                      $this->session->set_flashdata('successmessage', 'El aplicación se ha editado con éxito');
                      redirect(base_url().'index.php/aplicaciones/edit/'.$idaplilo);
                      
                	} else {
                				  
                      $this->data['errormessage'] = 'No se pudo registrar el aplilo';

                	}
              }       
                  $this->data['successmessage']=$this->session->flashdata('successmessage');
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                	$this->data['result'] = $this->codegen_model->get('adm_aplicaciones','apli_id,apli_nombre,apli_descripcion,apli_procesoid,apli_estadoid','apli_id = '.$idaplilo,1,NULL,true);
                  $this->template->set('title', 'Editar aplicación');
                  $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen'
                        );
                  $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js'
                        );
                  $this->data['procesos']  = $this->codegen_model->getSelect('adm_procesos','proc_id,proc_nombre');
                  $this->data['estados']  = $this->codegen_model->getSelect('adm_estados','esta_id,esta_nombre');
                  $this->template->load($this->config->item('admin_template'),'aplicaciones/aplicaciones_edit', $this->data);
                        
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

          if ($this->ion_auth->is_admin()) {  

              if (!$this->codegen_model->depend('adm_modulos','modu_aplicacionid',$this->input->post('id'))) {

                  $this->codegen_model->delete('adm_aplicaciones','apli_id',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'La aplicación se ha eliminado con éxito');
                  redirect(base_url().'index.php/aplicaciones');  

              } else {

                  $this->session->set_flashdata('errormessage', 'La aplicación se encuentra en uso, no es posible eliminarla.');
                  redirect(base_url().'index.php/aplicaciones/edit/'.$this->input->post('id'));

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
          
          if ($this->ion_auth->is_admin()) { 
                
              $this->load->library('datatables');
              $this->datatables->select('a.apli_id,a.apli_nombre,p.proc_nombre,a.apli_descripcion,e.esta_nombre');
              $this->datatables->from('adm_aplicaciones a');
              $this->datatables->join('adm_procesos p', 'p.proc_id = a.apli_procesoid', 'left');
              $this->datatables->join('adm_estados e', 'e.esta_id = a.apli_estadoid', 'left');
              $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="'.base_url().'index.php/aplicaciones/edit/$1" class="btn btn-default btn-xs" title="Editar aplilo"><i class="fa fa-pencil-square-o"></i></a>
                                                           </div>
                                                     </div>', 'a.apli_id');
              echo $this->datatables->generate();

          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }           
  }
}
