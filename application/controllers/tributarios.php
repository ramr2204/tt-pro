<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            tributarios
*   Ruta:              /application/controllers/tributarios.php
*   Descripcion:       controlador de tributarios
*   Fecha Creacion:    20/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-20
*
*/

class Tributarios extends MY_Controller {
    
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tributarios/manage')){

              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
              //template data
              $this->template->set('title', 'Administrar tributarios');
              $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
            
              $this->template->load($this->config->item('admin_template'),'tributarios/tributarios_list', $this->data);

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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tributarios/add')) {

              $this->data['successmessage']=$this->session->flashdata('message');  
        		  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]|is_unique[con_tributarios.trib_nombre]');   
              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[500]');

              if ($this->form_validation->run() == false) {

                  $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
              } else {    

                  $data = array(
                        'trib_nombre' => $this->input->post('nombre'),
                        'trib_descripcion' => $this->input->post('descripcion')

                     );
                 
                     $respuestaProceso = $this->codegen_model->add('con_tributarios',$data);
    			        if ($respuestaProceso->bandRegistroExitoso) {

                      $this->session->set_flashdata('message', 'El tipo tributario se ha creado con éxito');
                      redirect(base_url().'index.php/tributarios/add');
    			        } else {

    				          $this->data['errormessage'] = 'No se pudo registrar el tipo tributario';

    			        }

    		      }
                
              $this->template->set('title', 'Nueva tipo tributario');
              $this->template->load($this->config->item('admin_template'),'tributarios/tributarios_add', $this->data);
             
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tributarios/edit')) {  

              $idtributario = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
              if ($idtributario==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un tipo tributario para editar');
                  redirect(base_url().'index.php/tributarios');
              }
              $resultado = $this->codegen_model->get('con_tributarios','trib_nombre','trib_id = '.$idtributario,1,NULL,true);
              
              foreach ($resultado as $key => $value) {
                  $aplilo[$key]=$value;
              }
              
              if ($aplilo['trib_nombre']==$this->input->post('nombre')) {
                  
                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]');
              
              } else {

                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]|is_unique[con_tributarios.trib_nombre]');
              
              }

              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[500]');

              if ($this->form_validation->run() == false) {
                  
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                            
              } else {                            
                  
                  $data = array(
                          'trib_nombre' => $this->input->post('nombre'),
                          'trib_descripcion' => $this->input->post('descripcion')
                   );
                           
                	if ($this->codegen_model->edit('con_tributarios',$data,'trib_id',$idtributario) == TRUE) {

                      $this->session->set_flashdata('successmessage', 'El tipo tributario se ha editado con éxito');
                      redirect(base_url().'index.php/tributarios/edit/'.$idtributario);
                      
                	} else {
                				  
                      $this->data['errormessage'] = 'No se pudo registrar el aplilo';

                	}
              }       
                  $this->data['successmessage']=$this->session->flashdata('successmessage');
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                	$this->data['result'] = $this->codegen_model->get('con_tributarios','trib_id,trib_nombre,trib_descripcion','trib_id = '.$idtributario,1,NULL,true);
                  $this->template->set('title', 'Editar tipo tributario');
                  $this->template->load($this->config->item('admin_template'),'tributarios/tributarios_edit', $this->data);
                        
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tributarios/delete')) {  
              if ($this->input->post('id')==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un tipo tributario para eliminar');
                  redirect(base_url().'index.php/tributarios');
              }
              if (!$this->codegen_model->depend('con_contratistas','cont_tributarioid',$this->input->post('id'))) {

                  $this->codegen_model->delete('con_tributarios','trib_id',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'El tipo tributario se ha eliminado con éxito');
                  redirect(base_url().'index.php/tributarios');  

              } else {

                  $this->session->set_flashdata('errormessage', 'El tipo tributario se encuentra en uso, no es posible eliminarlo.');
                  redirect(base_url().'index.php/tributarios/edit/'.$this->input->post('id'));

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
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tributarios/manage') ) { 
              
              $this->load->library('datatables');
              $this->datatables->select('r.trib_id,r.trib_nombre,r.trib_descripcion');
              $this->datatables->from('con_tributarios r');

              if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tributarios/edit')) {
                  
                  $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="'.base_url().'index.php/tributarios/edit/$1" class="btn btn-default btn-xs" title="Editar tipo tributario"><i class="fa fa-pencil-square-o"></i></a>
                                                           </div>
                                                         </div>', 'r.trib_id');

              }  else {
                  
                  $this->datatables->add_column('edit', '', 'r.trib_id'); 
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
