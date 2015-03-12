<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            tramites
*   Ruta:              /application/controllers/tramites.php
*   Descripcion:       controlador de tramites
*   Fecha Creacion:    20/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-20
*
*/

class Tramites extends MY_Controller {
    
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tramites/manage')){

              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
              //template data
              $this->template->set('title', 'Administrar trámites');
              $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
            
              $this->template->load($this->config->item('admin_template'),'tramites/tramites_list', $this->data);

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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tramites/add')) {

              $this->data['successmessage']=$this->session->flashdata('message');  
        		  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[200]|is_unique[est_tramites.tram_nombre]');   
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
                        'tram_nombre' => $this->input->post('nombre'),
                        'tram_observaciones' => $this->input->post('descripcion')

                     ); 
                    
    			        if ($this->codegen_model->add('est_tramites',$data) == TRUE) {
                      
                      $insertid= $this->db->insert_id();
                      $x=1;
                      while ( $x  <= $this->input->post('numero')) {
                          if ($this->input->post('porcentaje'.$x) > 0) {
                              $data = array(
                              'estr_estampillaid' => $this->input->post('estampillaid'.$x),
                              'estr_tramiteid' => $insertid,
                              'estr_porcentaje' => $this->input->post('porcentaje'.$x)
                              );
                              $this->codegen_model->add('est_estampillas_tramites',$data);
                          }
                          $x++;
                      } 

                      $this->session->set_flashdata('message', 'El trámite se ha creado con éxito');
                      redirect(base_url().'index.php/tramites/add');
    			        } else {

    				          $this->data['errormessage'] = 'No se pudo registrar el trámite';

    			        }

    		      }
                
              $this->template->set('title', 'Nuevo trámite');
              $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen'
                    );
              $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js'
                    );
              $this->data['estampillas']  = $this->codegen_model->getSelect('est_estampillas','estm_id,estm_nombre');
              $this->template->load($this->config->item('admin_template'),'tramites/tramites_add', $this->data);
             
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tramites/edit')) {  

              $idtramite = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
              if ($idtramite==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un tipo de trámite para editar');
                  redirect(base_url().'index.php/tramites');
              }
              $resultado = $this->codegen_model->get('est_tramites','tram_nombre','tram_id = '.$idtramite,1,NULL,true);
              
              foreach ($resultado as $key => $value) {
                  $aplilo[$key]=$value;
              }
              
              if ($aplilo['tram_nombre']==$this->input->post('nombre')) {
                  
                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[200]');
              
              } else {

                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[200]|is_unique[est_tramites.tram_nombre]');
              
              }

              $this->form_validation->set_rules('observaciones', 'Descripción', 'trim|xss_clean|max_length[500]');
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
                          'tram_nombre' => $this->input->post('nombre'),
                          'tram_observaciones' => $this->input->post('observaciones')
                   );
                           
                	if ($this->codegen_model->edit('est_tramites',$data,'tram_id',$idtramite) == TRUE) {

                	  $x=1; 
                      while ( $x  <= $this->input->post('numero')) {
                         
                          if ($this->input->post('estiid'.$x) > 0) {
                          	  if ($this->input->post('porcentaje'.$x) > 0) {
                                   $data = array(
                                     'estr_porcentaje' => $this->input->post('porcentaje'.$x)
                                   );
                                   $this->codegen_model->edit('est_estampillas_tramites',$data,'estr_id',$this->input->post('estiid'.$x));
                               } else {
                               	   $this->codegen_model->delete('est_estampillas_tramites','estr_id',$this->input->post('estiid'.$x));
                               }
                          } else {
                               if ($this->input->post('porcentaje'.$x) > 0) {
                               	   $data = array(
                                     'estr_estampillaid' => $this->input->post('estampillaid'.$x),
                                     'estr_tramiteid' => $idtramite,
                                     'estr_porcentaje' => $this->input->post('porcentaje'.$x)
                                   );
                                   $this->codegen_model->add('est_estampillas_tramites',$data);
                               }
                          }
                          $x++;
                      } 	

                      $this->session->set_flashdata('successmessage', 'El trámite se ha editado con éxito');
                      redirect(base_url().'index.php/tramites/edit/'.$idtramite);
                      
                	} else {
                				  
                      $this->data['errormessage'] = 'No se pudo registrar el trámite';

                	}
              }       
                  $this->data['successmessage']=$this->session->flashdata('successmessage');
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                  $this->data['result'] = $this->codegen_model->get('est_tramites','tram_id,tram_nombre,tram_observaciones','tram_id = '.$idtramite,1,NULL,true);
                  $this->template->set('title', 'Editar trámite');
                  $this->data['estampillas']  = $this->codegen_model->getSelect('est_estampillas','estm_id,estm_nombre,estr_porcentaje,estr_ordenanzaid,estr_id','','LEFT JOIN est_estampillas_tramites on estm_id = estr_estampillaid AND estr_tramiteid='.$idtramite);
                  $this->template->load($this->config->item('admin_template'),'tramites/tramites_edit', $this->data);
                        
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tramites/delete')) {  
              if ($this->input->post('id')==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un tipo de trámite para eliminar');
                  redirect(base_url().'index.php/tramites');
              }

                  $this->codegen_model->delete('est_tramites','tram_id',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'El trámite se ha eliminado con éxito');
                  redirect(base_url().'index.php/tramites');     
                         
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
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tramites/manage') ) { 
              
              $this->load->library('datatables');
              $this->datatables->select('t.tram_id,t.tram_nombre,t.tram_observaciones');
              $this->datatables->from('est_tramites t');

              if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tramites/edit')) {
                  
                  $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="'.base_url().'index.php/tramites/edit/$1" class="btn btn-default btn-xs" title="Editar trámite"><i class="fa fa-pencil-square-o"></i></a>
                                                           </div>
                                                         </div>', 't.tram_id');

              }  else {
                  
                  $this->datatables->add_column('edit', '', 't.tram_id'); 
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
