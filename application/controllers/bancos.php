<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            bancos
*   Ruta:              /application/controllers/bancos.php
*   Descripcion:       controlador de bancos
*   Fecha Creacion:    20/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-20
*
*/

class Bancos extends MY_Controller {
    
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bancos/manage')){

              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
              //template data
              $this->template->set('title', 'Administrar bancos');
              $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
            
              $this->template->load($this->config->item('admin_template'),'bancos/bancos_list', $this->data);

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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bancos/add')) {

              $this->data['successmessage']=$this->session->flashdata('message');  
        		  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]|is_unique[par_bancos.banc_nombre]');   
              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[500]');

              if ($this->form_validation->run() == false) {

                  $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
              } else {    

                  $data = array(
                        'banc_nombre' => $this->input->post('nombre'),
                        'banc_descripcion' => $this->input->post('descripcion')

                     );
                        
                        $respuestaProceso = $this->codegen_model->add('par_bancos',$data);
    			        if ($respuestaProceso->bandRegistroExitoso) {

                      $this->session->set_flashdata('message', 'El banco se ha creado con éxito');
                      redirect(base_url().'index.php/bancos/add');
    			        } else {

    				          $this->data['errormessage'] = 'No se pudo registrar el tipo banco';

    			        }

    		      }
                
              $this->template->set('title', 'Nueva tipo banco');
              $this->template->load($this->config->item('admin_template'),'bancos/bancos_add', $this->data);
             
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bancos/edit')) {  

              $idbanco = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
              if ($idbanco==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un banco para editar');
                  redirect(base_url().'index.php/bancos');
              }
              $resultado = $this->codegen_model->get('par_bancos','banc_nombre','banc_id = '.$idbanco,1,NULL,true);
              
              foreach ($resultado as $key => $value) {
                  $aplilo[$key]=$value;
              }
              
              if ($aplilo['banc_nombre']==$this->input->post('nombre')) {
                  
                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]');
              
              } else {

                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]|is_unique[par_bancos.banc_nombre]');
              
              }

              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[500]');

              if ($this->form_validation->run() == false) {
                  
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                            
              } else {                            
                  
                  $data = array(
                          'banc_nombre' => $this->input->post('nombre'),
                          'banc_descripcion' => $this->input->post('descripcion')
                   );
                           
                	if ($this->codegen_model->edit('par_bancos',$data,'banc_id',$idbanco) == TRUE) {

                      $this->session->set_flashdata('successmessage', 'El banco se ha editado con éxito');
                      redirect(base_url().'index.php/bancos/edit/'.$idbanco);
                      
                	} else {
                				  
                      $this->data['errormessage'] = 'No se pudo registrar el aplilo';

                	}
              }       
                  $this->data['successmessage']=$this->session->flashdata('successmessage');
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                	$this->data['result'] = $this->codegen_model->get('par_bancos','banc_id,banc_nombre,banc_descripcion','banc_id = '.$idbanco,1,NULL,true);
                  $this->template->set('title', 'Editar tipo banco');
                  $this->template->load($this->config->item('admin_template'),'bancos/bancos_edit', $this->data);
                        
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bancos/delete')) {  
              if ($this->input->post('id')==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un tipo banco para eliminar');
                  redirect(base_url().'index.php/bancos');
              }
              if (!$this->codegen_model->depend('est_estampillas','estm_bancoid',$this->input->post('id'))) {

                  $this->codegen_model->delete('par_bancos','banc_id',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'El banco se ha eliminado con éxito');
                  redirect(base_url().'index.php/bancos');  

              } else {

                  $this->session->set_flashdata('errormessage', 'El banco se encuentra en uso, no es posible eliminarlo.');
                  redirect(base_url().'index.php/bancos/edit/'.$this->input->post('id'));

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
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bancos/manage') ) { 
              
              $this->load->library('datatables');
              $this->datatables->select('b.banc_id,b.banc_nombre,b.banc_descripcion');
              $this->datatables->from('par_bancos b');

              if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bancos/edit')) {
                  
                  $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="'.base_url().'index.php/bancos/edit/$1" class="btn btn-default btn-xs" title="Editar tipo banco"><i class="fa fa-pencil-square-o"></i></a>
                                                           </div>
                                                         </div>', 'b.banc_id');

              }  else {
                  
                  $this->datatables->add_column('edit', '', 'b.banc_id'); 
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
