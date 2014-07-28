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
              $this->form_validation->set_rules('bancoid', 'Banco',  'required|numeric|greater_than[0]');
              

              $path = "uploads/imagenesestampillas";
              if(!is_dir($path)) { //create the folder if it's not already exists
                  mkdir($path,0777,TRUE);      
              }
              $config['upload_path'] = $path;
              $config['allowed_types'] = 'jpg|jpeg|gif|png';
              $config['remove_spaces']=TRUE;
              $config['max_size']    = '2048';
              $config['file_name']=$this->input->post('nombre').'_'.date("F_d_Y");
              //$config['overwrite']    = TRUE;
              $this->load->library('upload');
              $ruta='';


              if ($this->form_validation->run() == false) {

                  $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
              } else {    
                      $this->upload->initialize($config);
                   if ($this->upload->do_upload("imagen")) {
                       $file_data= $this->upload->data();
                       $ruta =  $path.'/'.$file_data['orig_name'];

                       $data = array(
                          'estm_nombre' => $this->input->post('nombre'),
                          'estm_cuenta' => $this->input->post('cuenta'),
                          'estm_descripcion' => $this->input->post('descripcion'),
                          'estm_bancoid' => $this->input->post('bancoid'),
                          'estm_rutaimagen' => $ruta
                       );
                 
                       if ($this->codegen_model->add('est_estampillas',$data) == TRUE) {

                           $this->session->set_flashdata('message', 'El estampilla se ha creado con éxito');
                           redirect(base_url().'index.php/estampillas/add');
                       } else {

                           $this->data['errormessage'] = 'No se pudo registrar el estampilla';

                       }
                                                          
                   } else {
                       $this->data['errormessage'] =$this->upload->display_errors(); 
                   } 
                 
                 

    		      }
              $this->template->set('title', 'Nueva aplicación');
              $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen',
                        'css/plugins/bootstrap/fileinput.css' => 'screen'
                    );
              $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js',
                        'js/plugins/bootstrap/fileinput.min.js'
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

              $idestampilla = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
              if ($idestampilla==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un estampilla para editar');
                  redirect(base_url().'index.php/estampillas');
              }
              $resultado = $this->codegen_model->get('est_estampillas','estm_cuenta','estm_id = '.$idestampilla,1,NULL,true);
              
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
              $path = "uploads/imagenesestampillas";
              if(!is_dir($path)) { //create the folder if it's not already exists
                  mkdir($path,0777,TRUE);      
              }
              $config['upload_path'] = $path;
              $config['allowed_types'] = 'jpg|jpeg|gif|png';
              $config['remove_spaces']=TRUE;
              $config['max_size']    = '2048';
              $config['file_name']=$this->input->post('nombre').'_'.date("F_d_Y").'_'.rand(1, 15);
              //$config['overwrite']    = TRUE;
              $this->load->library('upload');
              $ruta='';

              if ($this->form_validation->run() == false) {
                  
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                            
              } else {                            
                  $this->upload->initialize($config);
                  if ($this->upload->do_upload("imagen")) {
                       $file_data= $this->upload->data();
                       $ruta =  $path.'/'.$file_data['orig_name'];
                       $data = array(
                        'estm_nombre' => $this->input->post('nombre'),
                        'estm_cuenta' => $this->input->post('cuenta'),
                        'estm_descripcion' => $this->input->post('descripcion'),
                        'estm_bancoid' => $this->input->post('bancoid'),
                        'estm_rutaimagen' => $ruta

                       );
                  } else {
                       $this->data['errormessage'] =$this->upload->display_errors();
                        $data = array(
                        'estm_nombre' => $this->input->post('nombre'),
                        'estm_cuenta' => $this->input->post('cuenta'),
                        'estm_descripcion' => $this->input->post('descripcion'),
                        'estm_bancoid' => $this->input->post('bancoid')

                       );
                  }
                  // echo "<pre>";
                  // print_r($data);
                  // echo "</pre>";         
                	if ($this->codegen_model->edit('est_estampillas',$data,'estm_id',$idestampilla) == TRUE) {

                      $this->session->set_flashdata('successmessage', 'El estampilla se ha editado con éxito');
                      //redirect(base_url().'index.php/estampillas/edit/'.$idestampilla);
                      
                	} else {
                				  
                      $this->data['errormessage'] = 'No se pudo registrar la estampilla';
                      $data = array(
                        'estm_nombre' => $this->input->post('nombre'),
                        'estm_cuenta' => $this->input->post('cuenta'),
                        'estm_descripcion' => $this->input->post('descripcion'),
                        'estm_bancoid' => $this->input->post('bancoid')

                       );

                	}
              }   
                  $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen',
                        'css/plugins/bootstrap/fileinput.css' => 'screen'
                        );
                  $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js',
                        'js/plugins/bootstrap/fileinput.min.js'
                        );    
                  $this->data['successmessage']=$this->session->flashdata('successmessage');
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                	$this->data['result'] = $this->codegen_model->get('est_estampillas','estm_id,estm_nombre,estm_cuenta,estm_descripcion,estm_bancoid,estm_rutaimagen','estm_id = '.$idestampilla,1,NULL,true);
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
	function cobros()
  {    
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('estampillas/contratos')) {  

              $idestampilla = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
              if ($idestampilla==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un estampilla para editar');
                  redirect(base_url().'index.php/estampillas');
              }
 
                  $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen'
                        );
                  $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js'
                        );    
                  $this->data['successmessage']=$this->session->flashdata('successmessage');
                  $this->data['errormessage'] = $this->session->flashdata('errorsmessage'); 
                  $this->data['result'] = $this->codegen_model->get('est_estampillas','estm_id,estm_nombre,estm_cuenta,estm_descripcion,estm_bancoid,estm_rutaimagen','estm_id = '.$idestampilla,1,NULL,true);
                  $consultatipocontratos  = $this->codegen_model->getSelect('con_tiposcontratos','esti_id,tico_id,tico_nombre,esti_porcentaje,esti_estampillaid ','','LEFT JOIN est_estampillas_tiposcontratos on tico_id = esti_tipocontratoid AND esti_estampillaid ='.$idestampilla);
                  $consultatramites  = $this->codegen_model->getSelect('est_tramites','estr_id,tram_id,tram_nombre,estr_porcentaje','','LEFT JOIN est_estampillas_tramites on tram_id = estr_tramiteid AND estr_estampillaid ='.$idestampilla);
                  
                  $tiposcontratos_activos=array();
                  $tiposcontratos_inactivos=array();
                  $porcentajes_activos=array();
                  foreach ($consultatipocontratos  as $key => $value) {
                      if ($value->esti_porcentaje>0) {
                           $tiposcontratos_activos[$value->esti_id]=$value->tico_nombre;
                           $porcentajes_activos[$value->esti_id]=$value->esti_porcentaje;
                        } else {
                           $tiposcontratos_inactivos[$value->tico_id]=$value->tico_nombre; 
                        } 
                  }

                  $tramites_activos=array();
                  $tramites_inactivos=array();
                  $porcentajes_activost=array();
                  foreach ($consultatramites  as $key => $value) {
                      if ($value->estr_porcentaje>0) {
                           $tramites_activos[$value->estr_id]=$value->tram_nombre;
                           $porcentajes_activost[$value->estr_id]=$value->estr_porcentaje;
                        } else {
                           $tramites_inactivos[$value->tram_id]=$value->tram_nombre; 
                        } 
                  }
                 //echo "<pre>";  print_r($consultatipocontratos);   echo "</pre>";  
                  $this->data['tiposcontratos']=$tiposcontratos_activos;
                  $this->data['porcentajes']=$porcentajes_activos;
                  $this->data['selecttiposcontratos']=$tiposcontratos_inactivos;

                  $this->data['tramites']=$tramites_activos;
                  $this->data['porcentajest']=$porcentajes_activost;
                  $this->data['selecttramites']=$tramites_inactivos;

                  $this->template->set('title', 'Editar cobros de estampilla');
                  $this->template->load($this->config->item('admin_template'),'estampillas/estampillas_contratos', $this->data);
                        
          }else {
              redirect(base_url().'index.php/error_404');
          }
      } else {
          redirect(base_url().'index.php/users/login');
      }
        
  }
function agregarcobro()
  {        
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('estampillas/agregarcobro')) {

              //$this->form_validation->set_rules('estampillaid', 'estampilla',  'required|numeric|greater_than[0]');
              //$this->form_validation->set_rules('porcentaje', 'Porcentaje',  'required|numeric|greater_than[0]');

                
              if ($this->input->post('tipocobro')=='contrato' && $this->input->post('porcentaje') > 0 && $this->input->post('tipocontratoid') > 0) {
                  
                  //$this->form_validation->set_rules('tipocontratoid', 'Tipo de contrato',  'required|numeric|greater_than[0]'); 
                  $data = array(
                    'esti_estampillaid' => $this->input->post('estampillaid'),
                    'esti_tipocontratoid' => $this->input->post('tipocontratoid'),
                    'esti_porcentaje' => $this->input->post('porcentaje')
                  );
                  if ($this->form_validation->run() == false) { 
                      if ($this->codegen_model->add('est_estampillas_tiposcontratos',$data) == TRUE) {
                            
                        $this->session->set_flashdata('successmessage', 'El cobro se ha creado con éxito');
                      } else {
                         $this->session->set_flashdata('errormessage', 'No se pudo registrar el cobro');
                          
                      }
                  } else {
                      $this->session->set_flashdata('errormessage', validation_errors());
                  }
              } else {
                $this->session->set_flashdata('errormessage', 'No se pudo registrar el cobro');
              }    
              if ($this->input->post('tipocobro')=='tramite' && $this->input->post('porcentaje') > 0 && $this->input->post('tramiteid') > 0) {
                 
                  $data = array(
                    'estr_estampillaid' => $this->input->post('estampillaid'),
                    'estr_tramiteid' => $this->input->post('tramiteid'),
                    'estr_porcentaje' => $this->input->post('porcentaje')
                  );
                  if ($this->form_validation->run() == false) {
                      if ($this->codegen_model->add('est_estampillas_tramites',$data) == TRUE) {

                        $this->session->set_flashdata('successmessage', 'El cobro se ha creado con éxito');
                      } else {
                         $this->session->set_flashdata('errormessage', 'No se pudo registrar el cobro');

                      }
                  } else {
                      $this->session->set_flashdata('errormessage', validation_errors());
                  }

              } else {
                $this->session->set_flashdata('errormessage', 'No se pudo registrar el cobro');
              }
                 
                 redirect(base_url().'index.php/estampillas/cobros/'.$this->input->post('estampillaid'));  
                 //echo "<pre>";  print_r($data);   echo "</pre>";  
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  } 
  function eliminarcobro()
  {
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('estampillas/eliminarcobro')) {  
              if ($this->input->post('id')==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un cobro para eliminar');
                  redirect(base_url().'index.php/estampillas');
              }
                 if ($this->input->post('tipo')=='contrato'){
                     $this->codegen_model->delete('est_estampillas_tiposcontratos','esti_id',$this->input->post('id'));
                     $this->session->set_flashdata('successmessage', 'El contrato se eliminó de esta estampilla con éxito');
                 }
                 if ($this->input->post('tipo')=='tramite'){
                     $this->codegen_model->delete('est_estampillas_tramites','estr_id',$this->input->post('id'));
                     $this->session->set_flashdata('successmessage', 'El tármite se eliminó de estampilla con éxito');
                 }
                  redirect(base_url().'index.php/estampillas/cobros/'.$this->input->post('estampillaid'));  

                         
          } else {
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

                  $this->codegen_model->delete('est_estampillas','estm_id',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'El estampilla se ha eliminado con éxito');
                  redirect(base_url().'index.php/estampillas');  

                         
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
                                                              <a href="'.base_url().'index.php/estampillas/cobros/$1" class="btn btn-default btn-xs" title="Editar cobros de esta estampilla"><i class="fa fa-file-text-o"></i></a>
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
