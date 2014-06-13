<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            modulos
*   Ruta:              /modulo/controllers/modulos.php
*   Descripcion:       controlador de modulos
*   Fecha Creacion:    14/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-14
*
*/

class Modulos extends MY_Controller {
    
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
              $this->template->set('title', 'Administrar módulos');
              $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
            
              $this->template->load($this->config->item('admin_template'),'modulos/modulos_list', $this->data);

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
              $this->form_validation->set_rules('aplicacionid', 'Aplicación',  'required|numeric|greater_than[0]');  
              $this->form_validation->set_rules('estadoid', 'Estado',  'required|numeric'); 

              if ($this->form_validation->run() == false) {

                  $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
              } else {    

                  $data = array(
                        'modu_nombre' => $this->input->post('nombre'),
                        'modu_descripcion' => $this->input->post('descripcion'),
                        'modu_aplicacionid' => $this->input->post('aplicacionid'),
                        'modu_estadoid' => $this->input->post('estadoid')

                     );
                 
    			        if ($this->codegen_model->add('adm_modulos',$data) == TRUE) {

                      $this->session->set_flashdata('message', 'El módulo se ha creado con éxito');
                      redirect(base_url().'index.php/modulos/add');
    			        } else {

    				          $this->data['errormessage'] = 'No se pudo registrar el módulo';

    			        }

    		      }
                
              $this->template->set('title', 'Nuevo módulo');
              $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen'
                    );
              $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js'
                    );
              $this->data['aplicaciones']  = $this->codegen_model->getSelect('adm_aplicaciones','apli_id,apli_nombre');
              $this->data['estados']  = $this->codegen_model->getSelect('adm_estados','esta_id,esta_nombre');
              $this->template->load($this->config->item('admin_template'),'modulos/modulos_add', $this->data);
             
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

              $idmodulo = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
              $resultado = $this->codegen_model->get('adm_modulos','modu_nombre','modu_id = '.$idmodulo,1,NULL,true);
              
              foreach ($resultado as $key => $value) {
                  $modulo[$key]=$value;
              }
              
              if ($modulo['modu_nombre']==$this->input->post('nombre')) {
                  
                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
              
              } else {

                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
              
              }

              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[500]');
              $this->form_validation->set_rules('aplicacionid', 'Aplicación',  'required|trim|xss_clean|numeric|greater_than[0]');  
              $this->form_validation->set_rules('estadoid', 'Estado',  'required|trim|xss_clean|numeric');   

              if ($this->form_validation->run() == false) {
                  
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                            
              } else {                            
                  
                  $data = array(
                          'modu_nombre' => $this->input->post('nombre'),
                          'modu_descripcion' => $this->input->post('descripcion'),
                          'modu_aplicacionid' => $this->input->post('aplicacionid'),
                          'modu_estadoid' => $this->input->post('estadoid')
                   );
                           
                	if ($this->codegen_model->edit('adm_modulos',$data,'modu_id',$idmodulo) == TRUE) {

                      $this->session->set_flashdata('successmessage', 'El módulo se ha editado con éxito');
                      redirect(base_url().'index.php/modulos/edit/'.$idmodulo);
                      
                	} else {
                				  
                      $this->data['errormessage'] = 'No se pudo registrar el modulo';

                	}
              }       
                  $this->data['successmessage']=$this->session->flashdata('successmessage');
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                	$this->data['result'] = $this->codegen_model->get('adm_modulos','modu_id,modu_nombre,modu_descripcion,modu_aplicacionid,modu_estadoid','modu_id = '.$idmodulo,1,NULL,true);
                  $this->template->set('title', 'Editar módulo');
                  $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen'
                        );
                  $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js'
                        );
                  $this->data['aplicaciones']  = $this->codegen_model->getSelect('adm_aplicaciones','apli_id,apli_nombre');
                  $this->data['estados']  = $this->codegen_model->getSelect('adm_estados','esta_id,esta_nombre');
                  $this->template->load($this->config->item('admin_template'),'modulos/modulos_edit', $this->data);
                        
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

              if (!$this->codegen_model->depend('adm_menus','menu_moduloid',$this->input->post('id'))) {

                  $this->codegen_model->delete('adm_modulos','modu_id',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'El módulo se ha eliminado con éxito');
                  redirect(base_url().'index.php/modulos');  

              } else {

                  $this->session->set_flashdata('errormessage', 'El módulo se encuentra en uso, no es posible eliminarlo.');
                  redirect(base_url().'index.php/modulos/edit/'.$this->input->post('id'));

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
              $this->datatables->select('m.modu_id,m.modu_nombre,a.apli_nombre,m.modu_descripcion,e.esta_nombre');
              $this->datatables->from('adm_modulos m');
              $this->datatables->join('adm_aplicaciones a', 'a.apli_id = m.modu_aplicacionid', 'left');
              $this->datatables->join('adm_estados e', 'e.esta_id = m.modu_estadoid', 'left');
              $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="'.base_url().'index.php/modulos/edit/$1" class="btn btn-default btn-xs" title="Editar módulo"><i class="fa fa-pencil-square-o"></i></a>
                                                           </div>
                                                     </div>', 'm.modu_id');
              echo $this->datatables->generate();

          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }           
  }
}
