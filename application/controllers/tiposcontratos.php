<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            tiposcontratos
*   Ruta:              /application/controllers/tiposcontratos.php
*   Descripcion:       controlador de tiposcontratos
*   Fecha Creacion:    20/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-20
*
*/

class Tiposcontratos extends MY_Controller {
    
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tiposcontratos/manage')){

              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
              //template data
              $this->template->set('title', 'Administrar tipos contratos');
              $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
            
              $this->template->load($this->config->item('admin_template'),'tiposcontratos/tiposcontratos_list', $this->data);

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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tiposcontratos/add')) {

              $this->data['successmessage']=$this->session->flashdata('message');  
        		  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]|is_unique[con_tiposcontratos.tico_nombre]');   
              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[500]');
              $x=1;
              while ( $x  <= $this->input->post('numero')) {
                  $this->form_validation->set_rules('estampillaid'.$x, 'estampillaid', 'trim|xss_clean|max_length[5]|numeric');
                  $this->form_validation->set_rules('porcentaje'.$x, 'Porcentaje'.$x, 'trim|xss_clean|max_length[5]|numeric');
                  $x++;
               } 




              if ($this->form_validation->run() == false) {

                  $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
              } else {    

                    $data = array(
                        'tico_nombre' => $this->input->post('nombre'),
                        'tico_descripcion' => $this->input->post('descripcion')

                     ); 
                    
    			        if ($this->codegen_model->add('con_tiposcontratos',$data) == TRUE) {
                      
                      $insertid= $this->db->insert_id();
                      $x=1;
                      while ( $x  <= $this->input->post('numero')) {
                          if ($this->input->post('porcentaje'.$x) > 0) {
                              $data = array(
                                'esti_estampillaid' => $this->input->post('estampillaid'.$x),
                                'esti_tipocontratoid' => $insertid,
                                'esti_porcentaje' => $this->input->post('porcentaje'.$x)
                              );
                              $this->codegen_model->add('est_estampillas_tiposcontratos',$data);
                          }
                          $x++;
                      } 

                      $this->session->set_flashdata('message', 'El tipo de contrato se ha creado con éxito');
                      redirect(base_url().'index.php/tiposcontratos/add');
    			        } else {

    				          $this->data['errormessage'] = 'No se pudo registrar el tipo de contrato';

    			        }

    		      }
                
              $this->template->set('title', 'Nueva tipo de contrato');
              $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen'
                    );
              $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js'
                    );
              $this->data['estampillas']  = $this->codegen_model->getSelect('est_estampillas','estm_id,estm_nombre');
              $this->template->load($this->config->item('admin_template'),'tiposcontratos/tiposcontratos_add', $this->data);
             
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tiposcontratos/edit')) {  

              $idtipocontrato = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
              if ($idtipocontrato==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un tipo de tipo de contrato para editar');
                  redirect(base_url().'index.php/tiposcontratos');
              }
              $resultado = $this->codegen_model->get('con_tiposcontratos','tico_nombre','tico_id = '.$idtipocontrato,1,NULL,true);
              
              foreach ($resultado as $key => $value) {
                  $aplilo[$key]=$value;
              }
              
              if ($aplilo['tico_nombre']==$this->input->post('nombre')) {
                  
                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]');
              
              } else {

                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]|is_unique[con_tiposcontratos.tico_nombre]');
              
              }

              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[500]');
              $x=1;
              while ( $x  <= $this->input->post('numero')) {
                  $this->form_validation->set_rules('estampillaid'.$x, 'estampillaid', 'trim|xss_clean|max_length[5]|numeric');
                  $this->form_validation->set_rules('estiid'.$x, 'estampillaid', 'trim|xss_clean|max_length[5]|numeric');
                  $this->form_validation->set_rules('porcentaje'.$x, 'Porcentaje'.$x, 'trim|xss_clean|max_length[5]|numeric');
                  $x++;
               }

              if ($this->form_validation->run() == false) {
                  
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                            
              } else {                            
                  
                  $data = array(
                          'tico_nombre' => $this->input->post('nombre'),
                          'tico_descripcion' => $this->input->post('descripcion')
                   );
                           
                	if ($this->codegen_model->edit('con_tiposcontratos',$data,'tico_id',$idtipocontrato) == TRUE) {

                	  $x=1; 
                      while ( $x  <= $this->input->post('numero')) {
                         
                          if ($this->input->post('estiid'.$x) > 0) {
                          	  if ($this->input->post('porcentaje'.$x) > 0) {
                                   $data = array(
                                     'esti_porcentaje' => $this->input->post('porcentaje'.$x)
                                   );
                                   $this->codegen_model->edit('est_estampillas_tiposcontratos',$data,'esti_id',$this->input->post('estiid'.$x));
                               } else {
                               	   $this->codegen_model->delete('est_estampillas_tiposcontratos','esti_id',$this->input->post('estiid'.$x));
                               }
                          } else {
                               if ($this->input->post('porcentaje'.$x) > 0) {
                               	   $data = array(
                                     'esti_estampillaid' => $this->input->post('estampillaid'.$x),
                                     'esti_tipocontratoid' => $idtipocontrato,
                                     'esti_porcentaje' => $this->input->post('porcentaje'.$x)
                                   );
                                   $this->codegen_model->add('est_estampillas_tiposcontratos',$data);
                               }
                          }
                          $x++;
                      } 	

                      $this->session->set_flashdata('successmessage', 'El tipo de contrato se ha editado con éxito');
                      redirect(base_url().'index.php/tiposcontratos/edit/'.$idtipocontrato);
                      
                	} else {
                				  
                      $this->data['errormessage'] = 'No se pudo registrar el tipo de contrato';

                	}
              }       
                  $this->data['successmessage']=$this->session->flashdata('successmessage');
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                  $this->data['result'] = $this->codegen_model->get('con_tiposcontratos','tico_id,tico_nombre,tico_descripcion','tico_id = '.$idtipocontrato,1,NULL,true);
                  $this->template->set('title', 'Editar tipo de contrato');
                  $this->data['estampillas']  = $this->codegen_model->getSelect('est_estampillas','estm_id,estm_nombre,esti_porcentaje,esti_ordenanzaid,esti_id','','LEFT JOIN est_estampillas_tiposcontratos on estm_id = esti_estampillaid AND esti_tipocontratoid ='.$idtipocontrato);
                  //echo $this->db->last_query();
                  $this->template->load($this->config->item('admin_template'),'tiposcontratos/tiposcontratos_edit', $this->data);
                        
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tiposcontratos/delete')) {  
              if ($this->input->post('id')==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un tipo de tipo de contrato para eliminar');
                  redirect(base_url().'index.php/tiposcontratos');
              }
              if (!$this->codegen_model->depend('con_contratos','cntr_tipocontratoid',$this->input->post('id'))) {

                  $this->codegen_model->delete('con_tiposcontratos','tico_id',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'El tipo de contrato se ha eliminado con éxito');
                  redirect(base_url().'index.php/tiposcontratos');  

              } else {

                  $this->session->set_flashdata('errormessage', 'El tipo de contrato se encuentra en uso, no es posible eliminarlo.');
                  redirect(base_url().'index.php/tiposcontratos/edit/'.$this->input->post('id'));

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
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tiposcontratos/manage') ) { 
              
              $this->load->library('datatables');
              $this->datatables->select('t.tico_id,t.tico_nombre,t.tico_descripcion');
              $this->datatables->from('con_tiposcontratos t');

              if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tiposcontratos/edit')) {
                  
                  $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="'.base_url().'index.php/tiposcontratos/edit/$1" class="btn btn-default btn-xs" title="Editar tipo de contrato"><i class="fa fa-pencil-square-o"></i></a>
                                                           </div>
                                                         </div>', 't.tico_id');

              }  else {
                  
                  $this->datatables->add_column('edit', '', 't.tico_id'); 
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
