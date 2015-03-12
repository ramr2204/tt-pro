<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:             admin template
*   Ruta:              /application/views/templates/admingroup.php
*   Descripcion:       Plantilla exclusiva para usuarios registrados
*   Fecha Creacion:    12/may/2014
*   Autor:             Iván Viña
*
*/

class Procesos extends MY_Controller {
    
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
              $this->template->set('title', 'Administrar procesos');
              $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
            
              $this->template->load($this->config->item('admin_template'),'procesos/procesos_list', $this->data);

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
        		  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]|is_unique[adm_procesos.proc_nombre]');   
              $this->form_validation->set_rules('descripcion', 'Descripcion', 'trim|xss_clean|max_length[128]'); 
              $this->form_validation->set_rules('estadoid', 'Estado',  'required|numeric'); 
              if ($this->form_validation->run() == false) {

                  $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
              } else {    

                  $data = array(
                        'proc_nombre' => $this->input->post('nombre'),
                        'proc_descripcion' => $this->input->post('descripcion'),
                        'proc_estadoid' => $this->input->post('estadoid')
                     );
                 
    			        if ($this->codegen_model->add('adm_procesos',$data) == TRUE) {

                      $this->session->set_flashdata('message', 'El proceso se ha creado con éxito');
                      redirect(base_url().'index.php/procesos/add');
    			        } else {

    				          $this->data['errormessage'] = 'No se pudo registrar el proceso';

    			        }

    		      }
                
              $this->template->set('title', 'Nuevo proceso');
              $this->data['estados']  = $this->codegen_model->getSelect('adm_estados','esta_id,esta_nombre');
              $this->template->load($this->config->item('admin_template'),'procesos/procesos_add', $this->data);
             
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

              $idproceso = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
              $resultado = $this->codegen_model->get('adm_procesos','proc_nombre','proc_id = '.$idproceso,1,NULL,true);
              
              foreach ($resultado as $key => $value) {
                  $proceso[$key]=$value;
              }
              
              if ($proceso['proc_nombre']==$this->input->post('nombre')) {
                  
                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
              
              } else {

                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]|is_unique[adm_procesos.proc_nombre]');
              
              }

              $this->form_validation->set_rules('descripcion', 'Descripcion', 'trim|xss_clean|max_length[128]'); 
              $this->form_validation->set_rules('estadoid', 'Estado',  'required|numeric'); 

              if ($this->form_validation->run() == false) {
                  
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                            
              } else {                            
                  
                  $data = array(
                          'proc_nombre' => $this->input->post('nombre'),
                          'proc_descripcion' => $this->input->post('descripcion'),
                          'proc_estadoid' => $this->input->post('estadoid')
                   );
                           
                	if ($this->codegen_model->edit('adm_procesos',$data,'proc_id',$idproceso) == TRUE) {

                      $this->session->set_flashdata('successmessage', 'El proceso se ha editado con éxito');
                      redirect(base_url().'index.php/procesos/edit/'.$idproceso);
                      
                	} else {
                				  
                      $this->data['errormessage'] = 'No se pudo registrar el proceso';

                	}
              }       
                  $this->data['successmessage']=$this->session->flashdata('successmessage');
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                	$this->data['result'] = $this->codegen_model->get('adm_procesos','proc_id,proc_nombre,proc_descripcion, proc_estadoid','proc_id = '.$idproceso,1,NULL,true);
                  $this->data['estados']  = $this->codegen_model->getSelect('adm_estados','esta_id,esta_nombre');
                  $this->template->set('title', 'Editar proceso');
                  $this->template->load($this->config->item('admin_template'),'procesos/procesos_edit', $this->data);
                        
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

              if (!$this->codegen_model->depend('adm_aplicaciones','apli_procesoid',$this->input->post('id'))) {

                  $this->codegen_model->delete('adm_procesos','proc_id',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'El proceso se ha eliminado con éxito');
                  redirect(base_url().'index.php/procesos');  

              } else {

                  $this->session->set_flashdata('errormessage', 'El proceso se encuentra en uso, no es posible eliminarlo.');
                  redirect(base_url().'index.php/procesos/edit/'.$this->input->post('id'));

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
              $this->datatables->select('p.proc_id,p.proc_nombre,p.proc_descripcion,e.esta_nombre');
              $this->datatables->from('adm_procesos p');
              $this->datatables->join('adm_estados e', 'e.esta_id = p.proc_estadoid', 'left');
              $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="'.base_url().'index.php/procesos/edit/$1" class="btn btn-default btn-xs" title="Editar proceso"><i class="fa fa-pencil-square-o"></i></a>
                                                           </div>
                                                     </div>', 'p.proc_id');
              echo $this->datatables->generate();

          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }           
  }
}
