<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   VNombre:            cuantias
*   Ruta:              /application/controllers/cuantias.php
*   Descripcion:       controlador de cuantias
*   Fecha Creacion:    20/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-20
*
*/

class Cuantias extends MY_Controller {
    
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cuantias/manage')){

              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
              //template data
              $this->template->set('title', 'Administrar cuantías');
              $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
            
              $this->template->load($this->config->item('admin_template'),'cuantias/cuantias_list', $this->data);

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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cuantias/add')) {

              $this->data['successmessage']=$this->session->flashdata('message');  
        		  $this->form_validation->set_rules('vigencia', 'Vigencia', 'required|trim|xss_clean|max_length[100]|is_unique[con_cuantias.cuan_vigencia]');   
              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[500]');

              if ($this->form_validation->run() == false) {

                  $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
              } else {    

                  $data = array(
                        'cuan_vigencia' => $this->input->post('vigencia'),
                        'cuan_descripcion' => $this->input->post('descripcion')

                     );
                        
                        $respuestaProceso = $this->codegen_model->add('con_cuantias',$data);
    			        if ($respuestaProceso->bandRegistroExitoso) {

                      $this->session->set_flashdata('message', 'El tipo de contrato se ha creado con éxito');
                      redirect(base_url().'index.php/cuantias/add');
    			        } else {

    				          $this->data['errormessage'] = 'No se pudo registrar el tipo de contrato';

    			        }

    		      }
                
              $this->template->set('title', 'Nueva tipo de contrato');
              $this->template->load($this->config->item('admin_template'),'cuantias/cuantias_add', $this->data);
             
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cuantias/edit')) {  

              $idtipocontrato = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
              if ($idtipocontrato==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un tipo de tipo de contrato para editar');
                  redirect(base_url().'index.php/cuantias');
              }
              $resultado = $this->codegen_model->get('con_cuantias','cuan_vigencia','cuan_id = '.$idtipocontrato,1,NULL,true);
              
              foreach ($resultado as $key => $value) {
                  $aplilo[$key]=$value;
              }
              
              if ($aplilo['cuan_vigencia']==$this->input->post('vigencia')) {
                  
                  $this->form_validation->set_rules('vigencia', 'Vigencia', 'required|trim|xss_clean|max_length[100]');
              
              } else {

                  $this->form_validation->set_rules('vigencia', 'Vigencia', 'required|trim|xss_clean|max_length[100]|is_unique[con_cuantias.cuan_vigencia]');
              
              }

              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[500]');

              if ($this->form_validation->run() == false) {
                  
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                            
              } else {                            
                  
                  $data = array(
                          'cuan_vigencia' => $this->input->post('vigencia'),
                          'cuan_descripcion' => $this->input->post('descripcion')
                   );
                           
                	if ($this->codegen_model->edit('con_cuantias',$data,'cuan_id',$idtipocontrato) == TRUE) {

                      $this->session->set_flashdata('successmessage', 'El tipo de contrato se ha editado con éxito');
                      redirect(base_url().'index.php/cuantias/edit/'.$idtipocontrato);
                      
                	} else {
                				  
                      $this->data['errormessage'] = 'No se pudo registrar el tipo de contrato';

                	}
              }       
                  $this->data['successmessage']=$this->session->flashdata('successmessage');
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                	$this->data['result'] = $this->codegen_model->get('con_cuantias','cuan_id,cuan_vigencia,cuan_descripcion','cuan_id = '.$idtipocontrato,1,NULL,true);
                  $this->template->set('title', 'Editar tipo de contrato');
                  $this->template->load($this->config->item('admin_template'),'cuantias/cuantias_edit', $this->data);
                        
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cuantias/delete')) {  
              if ($this->input->post('id')==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un tipo de tipo de contrato para eliminar');
                  redirect(base_url().'index.php/cuantias');
              }
              if (!$this->codegen_model->depend('con_contratos','cntr_tipocontratoid',$this->input->post('id'))) {

                  $this->codegen_model->delete('con_cuantias','cuan_id',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'El tipo de contrato se ha eliminado con éxito');
                  redirect(base_url().'index.php/cuantias');  

              } else {

                  $this->session->set_flashdata('errormessage', 'El tipo de contrato se encuentra en uso, no es posible eliminarlo.');
                  redirect(base_url().'index.php/cuantias/edit/'.$this->input->post('id'));

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
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cuantias/manage') ) { 
              
              $this->load->library('datatables');
              $this->datatables->select('t.cuan_id,t.cuan_vigencia,t.cuan_minima');
              $this->datatables->from('con_cuantias t');

              if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cuantias/edit')) {
                  
                  $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="'.base_url().'index.php/cuantias/edit/$1" class="btn btn-default btn-xs" title="Editar tipo de contrato"><i class="fa fa-pencil-square-o"></i></a>
                                                           </div>
                                                         </div>', 't.cuan_id');

              }  else {
                  
                  $this->datatables->add_column('edit', '', 't.cuan_id'); 
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
