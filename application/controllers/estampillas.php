<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            estampillas
*   Ruta:              /application/controllers/estampillas.php
*   Descripcion:       controlador de estampillas
*   Fecha Creacion:    20/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-20
*
*/

class Estampillas extends MY_Controller {
    
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('estampillas/manage')){

              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
              //template data
              $this->template->set('title', 'Administrar estampillas');
              $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
            
              $this->template->load($this->config->item('admin_template'),'estampillas/estampillas_list', $this->data);

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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('estampillas/add')) {

              $this->data['successmessage']=$this->session->flashdata('message');  
        		  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
              $this->form_validation->set_rules('cuenta', 'Cuenta', 'required|trim|xss_clean|max_length[100]|is_unique[est_estampillas.estm_cuenta]');   
              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[256]');
              $this->form_validation->set_rules('bancoid', 'Tipo de régimen',  'required|numeric|greater_than[0]');

              if ($this->form_validation->run() == false) {

                  $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
              } else {    

                  $data = array(
                        'estm_nombre' => $this->input->post('nombre'),
                        'estm_cuenta' => $this->input->post('cuenta'),
                        'estm_descripcion' => $this->input->post('descripcion'),
                        'estm_bancoid' => $this->input->post('bancoid')

                     );
                 
    			        if ($this->codegen_model->add('est_estampillas',$data) == TRUE) {

                      $this->session->set_flashdata('message', 'El estampilla se ha creado con éxito');
                      redirect(base_url().'index.php/estampillas/add');
    			        } else {

    				          $this->data['errormessage'] = 'No se pudo registrar el estampilla';

    			        }

    		      }
              $this->template->set('title', 'Nueva aplicación');
              $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen'
                    );
              $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js'
                    );  
              $this->template->set('title', 'Nuevo estampilla');
              $this->data['bancos']  = $this->codegen_model->getSelect('par_bancos','banc_id,banc_nombre');
              $this->template->load($this->config->item('admin_template'),'estampillas/estampillas_add', $this->data);
             
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('estampillas/edit')) {  

              $idregimen = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
              if ($idregimen==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un estampilla para editar');
                  redirect(base_url().'index.php/estampillas');
              }
              $resultado = $this->codegen_model->get('est_estampillas','estm_cuenta','estm_id = '.$idregimen,1,NULL,true);
              
              foreach ($resultado as $key => $value) {
                  $aplilo[$key]=$value;
              }
              
              if ($aplilo['estm_cuenta']==$this->input->post('cuenta')) {
                  
                  $this->form_validation->set_rules('cuenta', 'Cuenta', 'required|trim|xss_clean|max_length[100]');
              
              } else {

                  $this->form_validation->set_rules('cuenta', 'Cuenta', 'required|trim|xss_clean|max_length[100]|is_unique[est_estampillas.estm_cuenta]');
              
              }
              $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]');   
              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[256]');
              $this->form_validation->set_rules('bancoid', 'Tipo de régimen',  'required|numeric|greater_than[0]');

              if ($this->form_validation->run() == false) {
                  
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                            
              } else {                            
                  
                  $data = array(
                        'estm_nombre' => $this->input->post('nombre'),
                        'estm_cuenta' => $this->input->post('cuenta'),
                        'estm_descripcion' => $this->input->post('descripcion'),
                        'estm_bancoid' => $this->input->post('bancoid')

                     );
                           
                	if ($this->codegen_model->edit('est_estampillas',$data,'estm_id',$idregimen) == TRUE) {

                      $this->session->set_flashdata('successmessage', 'El estampilla se ha editado con éxito');
                      redirect(base_url().'index.php/estampillas/edit/'.$idregimen);
                      
                	} else {
                				  
                      $this->data['errormessage'] = 'No se pudo registrar el aplilo';

                	}
              }   
                  $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen'
                        );
                  $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js'
                        );    
                  $this->data['successmessage']=$this->session->flashdata('successmessage');
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                	$this->data['result'] = $this->codegen_model->get('est_estampillas','estm_id,estm_nombre,estm_cuenta,estm_descripcion,estm_bancoid','estm_id = '.$idregimen,1,NULL,true);
                  $this->data['bancos']  = $this->codegen_model->getSelect('par_bancos','banc_id,banc_nombre');
                  $this->template->set('title', 'Editar estampilla');
                  $this->template->load($this->config->item('admin_template'),'estampillas/estampillas_edit', $this->data);
                        
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('estampillas/delete')) {  
              if ($this->input->post('id')==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un estampilla para eliminar');
                  redirect(base_url().'index.php/estampillas');
              }
              if (!$this->codegen_model->depend('con_tiposcontratos','estm_contratoid',$this->input->post('id'))) {

                  $this->codegen_model->delete('est_estampillas','estm_id',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'El estampilla se ha eliminado con éxito');
                  redirect(base_url().'index.php/estampillas');  

              } else {

                  $this->session->set_flashdata('errormessage', 'El estampilla se encuentra en uso, no es posible eliminarlo.');
                  redirect(base_url().'index.php/estampillas/edit/'.$this->input->post('id'));

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
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('estampillas/manage') ) { 
              
              $this->load->library('datatables');
              $this->datatables->select('e.estm_id,e.estm_nombre,e.estm_cuenta,b.banc_nombre,e.estm_descripcion');
              $this->datatables->from('est_estampillas e');
              $this->datatables->join('par_bancos b', 'b.banc_id = e.estm_bancoid', 'left');

              if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('estampillas/edit')) {
                  
                  $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="'.base_url().'index.php/estampillas/edit/$1" class="btn btn-default btn-xs" title="Editar estampilla"><i class="fa fa-pencil-square-o"></i></a>
                                                           </div>
                                                         </div>', 'e.estm_id');

              }  else {
                  
                  $this->datatables->add_column('edit', '', 'e.estm_id'); 
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
