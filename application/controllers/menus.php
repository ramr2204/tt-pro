<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            menus
*   Ruta:              /modulo/controllers/menus.php
*   Descripcion:       controlador de menus
*   Fecha Creacion:    14/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-14
*
*/

class Menus extends MY_Controller {
    
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
              $this->template->set('title', 'Administrar menus');
              $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
            
              $this->template->load($this->config->item('admin_template'),'menus/menus_list', $this->data);

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
              $this->form_validation->set_rules('controlador', 'Controlador', 'required|trim|xss_clean|max_length[128]');   
              $this->form_validation->set_rules('metodo', 'Método', 'required|trim|xss_clean|max_length[128]');   
              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[500]');
              $this->form_validation->set_rules('moduloid', 'Módulo',  'required|numeric|greater_than[0]');  
              $this->form_validation->set_rules('estadoid', 'Estado',  'required|numeric'); 

              if ($this->form_validation->run() == false) {

                  $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
              } else {    

                  $data = array(
                        'menu_nombre' => $this->input->post('nombre'),
                        'menu_descripcion' => $this->input->post('descripcion'),
                        'menu_controlador' => $this->input->post('controlador'),
                        'menu_metodo' => $this->input->post('metodo'),
                        'menu_moduloid' => $this->input->post('moduloid'),
                        'menu_estadoid' => $this->input->post('estadoid'),
                        'menu_ruta' => $this->input->post('controlador').'/'.$this->input->post('metodo')

                     );
                 
    			        if ($this->codegen_model->add('adm_menus',$data) == TRUE) {

                      $this->session->set_flashdata('message', 'El menú se ha creado con éxito');
                      redirect(base_url().'index.php/menus/add');
    			        } else {

    				          $this->data['errormessage'] = 'No se pudo registrar el menú';

    			        }

    		      }
                
              $this->template->set('title', 'Nuevo menú');
              $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen'
                    );
              $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js'
                    );
              $this->data['modulos']  = $this->codegen_model->getSelect('adm_modulos','modu_id,modu_nombre');
              $this->data['estados']  = $this->codegen_model->getSelect('adm_estados','esta_id,esta_nombre');
              $this->template->load($this->config->item('admin_template'),'menus/menus_add', $this->data);
             
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
              $resultado = $this->codegen_model->get('adm_menus','menu_nombre','menu_id = '.$idmodulo,1,NULL,true);
              
              foreach ($resultado as $key => $value) {
                  $aplilo[$key]=$value;
              }
              
              if ($aplilo['menu_nombre']==$this->input->post('nombre')) {
                  
                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
              
              } else {

                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
              
              }
                
              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[500]');
              $this->form_validation->set_rules('controlador', 'Controlador', 'required|trim|xss_clean|max_length[128]');
              $this->form_validation->set_rules('metodo', 'Método', 'required|trim|xss_clean|max_length[128]');
              $this->form_validation->set_rules('moduloid', 'Módulo',  'required|trim|xss_clean|numeric|greater_than[0]');  
              $this->form_validation->set_rules('estadoid', 'Estado',  'required|trim|xss_clean|numeric');   

              if ($this->form_validation->run() == false) {
                  
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                            
              } else {                            
                  
                  $data = array(
                          'menu_nombre' => $this->input->post('nombre'),
                          'menu_descripcion' => $this->input->post('descripcion'),
                          'menu_controlador' => $this->input->post('controlador'),
                          'menu_metodo' => $this->input->post('metodo'),
                          'menu_moduloid' => $this->input->post('moduloid'),
                          'menu_estadoid' => $this->input->post('estadoid'),
                          'menu_ruta' => $this->input->post('controlador').'/'.$this->input->post('metodo')

                   );
                           
                	if ($this->codegen_model->edit('adm_menus',$data,'menu_id',$idmodulo) == TRUE) {

                      $this->session->set_flashdata('successmessage', 'El menú se ha editado con éxito');
                      redirect(base_url().'index.php/menus/edit/'.$idmodulo);
                      
                	} else {
                				  
                      $this->data['errormessage'] = 'No se pudo registrar el aplilo';

                	}
              }       
                  $this->data['successmessage']=$this->session->flashdata('successmessage');
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                	$this->data['result'] = $this->codegen_model->get('adm_menus','menu_id,menu_nombre,menu_descripcion,menu_controlador,menu_metodo,menu_moduloid,menu_estadoid','menu_id = '.$idmodulo,1,NULL,true);
                  $this->template->set('title', 'Editar menú');
                  $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen'
                        );
                  $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js'
                        );
                  $this->data['modulos']  = $this->codegen_model->getSelect('adm_modulos','modu_id,modu_nombre');
                  $this->data['estados']  = $this->codegen_model->getSelect('adm_estados','esta_id,esta_nombre');
                  $this->template->load($this->config->item('admin_template'),'menus/menus_edit', $this->data);
                        
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

              if ($this->codegen_model->delete('adm_menus','menu_id',$this->input->post('id'))) {

                  $this->session->set_flashdata('successmessage', 'El menú se ha eliminado con éxito');
                  redirect(base_url().'index.php/menus');  

              } else {

                  $this->session->set_flashdata('errormessage', 'El menú se encuentra en uso, no es posible eliminarla.');
                  redirect(base_url().'index.php/menus/edit/'.$this->input->post('id'));

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
              $this->datatables->select('m.menu_id,m.menu_nombre,a.modu_nombre,m.menu_descripcion,m.menu_ruta,e.esta_nombre');
              $this->datatables->from('adm_menus m');
              $this->datatables->join('adm_modulos a', 'a.modu_id = m.menu_moduloid', 'left');
              $this->datatables->join('adm_estados e', 'e.esta_id = m.menu_estadoid', 'left');
              $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="'.base_url().'index.php/menus/edit/$1" class="btn btn-default btn-xs" title="Editar aplilo"><i class="fa fa-pencil-square-o"></i></a>
                                                           </div>
                                                     </div>', 'm.menu_id');
              echo $this->datatables->generate();

          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }           
  }
}
