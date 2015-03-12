<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            pagos
*   Ruta:              /application/controllers/pagos.php
*   Descripcion:       controlador de pagos
*   Fecha Creacion:    20/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-20
*
*/

class Pagos extends MY_Controller {
    
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pagos/manage')){

              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
              //template data
              $this->template->set('title', 'Administrar pagos');
              $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
            
              $this->template->load($this->config->item('admin_template'),'pagos/pagos_list', $this->data);

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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pagos/add')) {

              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
               
              //carga las librerias para los estilos
              //y funcionalidad del boton de carga de 
              //archivos

              $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen',
                        'css/plugins/bootstrap/fileinput.css' => 'screen'
                    );
              $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js',
                        'js/plugins/bootstrap/fileinput.min.js'
                    );    
              
              $this->template->set('title', 'Cargar archivo de pagos');
              $this->template->load($this->config->item('admin_template'),'pagos/pagos_add', $this->data);
             
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  }	

  function doadd()
  {
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pagos/add')) {
               
              $path = 'uploads/pagos/'.date('d-m-Y');
               if(!is_dir($path)) { //create the folder if it's not already exists
                   mkdir($path,0777,TRUE);      
               }
               $config['upload_path'] = $path;
               $config['allowed_types'] = 'txt';
               $config['remove_spaces']=TRUE;
               $config['max_size']    = '2048';
               $config['overwrite']    = TRUE;

               //inicializa la variable que guardara los mensajes de :
               //información
               $msjInfo='';       


               $this->load->library('upload');
               $this->upload->initialize($config);  

               if ($this->upload->do_upload("archivo")) {
         
                    $file_data= $this->upload->data();
                    $success=0;
                    $error=0;

                    $path2 = $path."/".$file_data['raw_name'].".txt";
                    $string = file_get_contents($path2);
                    $file = fopen($path2,"r");
                    while(!feof($file)) {
                        $linea = fgets($file);
                        $explode = explode(',', $linea);

                        $resultado = $this->codegen_model->get('est_pagos','pago_id','pago_facturaid = '."'$explode[0]'",1,NULL,true);
                        if (!$resultado) {                               
                            $data = array(
                               'pago_facturaid' => $explode[0],
                               'pago_fecha' => $explode[1],
                              'pago_valor' => $explode[2],
                               'pago_metodo' => 'Archivo'
                            );
                           //acá hay que hacer las validaciones
         
         
                           if ($this->codegen_model->add('est_pagos',$data) == TRUE) {
                               $success++;
                            } else {
                               $error++;
                           }
                       }else 
                           {                 
                                //valida que no sea una linea en blanco
                                //o sin codigo para no almacenar el mensaje vacio
                                 if($explode[0])
                                {
                                      $msjInfo .= 'El pago de la factura No. '.$explode[0]. ' ya fue registrado.<br>';
                                 }                                
                            }
         
                    }
         
                    fclose($file);               
         
                   //Valida si la cantidad de pagos en error o exito
                   //se alteraron para cargar el mensaje de exito
                   if($error > 0 || $success > 0)
                   {
                       $this->session->set_flashdata('successmessage', 'Se cargaron '.$success.' pagos con éxito y '.$error. ' con errores');                                                               
                   }
                                
         
               } else {
                     $err = $this->upload->display_errors(); 
                    $this->session->set_flashdata('errormessage', $err);                                                                
                             
                }  
                          
              //carga el mensaje de información si existe
               if($msjInfo != '')
                {                            
                    $this->session->set_flashdata('infomessage', $msjInfo);
               } 
                    
                         
              redirect(base_url().'index.php/pagos/add');

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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pagos/edit')) {  

              $idpago = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
              if ($idpago==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un pago para editar');
                  redirect(base_url().'index.php/pagos');
              }
              $resultado = $this->codegen_model->get('par_pagos','banc_nombre','banc_id = '.$idpago,1,NULL,true);
              
              foreach ($resultado as $key => $value) {
                  $aplilo[$key]=$value;
              }
              
              if ($aplilo['banc_nombre']==$this->input->post('nombre')) {
                  
                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]');
              
              } else {

                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]|is_unique[par_pagos.banc_nombre]');
              
              }

              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[500]');

              if ($this->form_validation->run() == false) {
                  
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                            
              } else {                            
                  
                  $data = array(
                          'banc_nombre' => $this->input->post('nombre'),
                          'banc_descripcion' => $this->input->post('descripcion')
                   );
                           
                	if ($this->codegen_model->edit('par_pagos',$data,'banc_id',$idpago) == TRUE) {

                      $this->session->set_flashdata('successmessage', 'El pago se ha editado con éxito');
                      redirect(base_url().'index.php/pagos/edit/'.$idpago);
                      
                	} else {
                				  
                      $this->data['errormessage'] = 'No se pudo registrar el aplilo';

                	}
              }       
                  $this->data['successmessage']=$this->session->flashdata('successmessage');
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                	$this->data['result'] = $this->codegen_model->get('par_pagos','banc_id,banc_nombre,banc_descripcion','banc_id = '.$idpago,1,NULL,true);
                  $this->template->set('title', 'Editar tipo pago');
                  $this->template->load($this->config->item('admin_template'),'pagos/pagos_edit', $this->data);
                        
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pagos/delete')) {  
              if ($this->input->post('id')==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un tipo pago para eliminar');
                  redirect(base_url().'index.php/pagos');
              }
              if (!$this->codegen_model->depend('est_estampillas','estm_pagoid',$this->input->post('id'))) {

                  $this->codegen_model->delete('par_pagos','banc_id',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'El pago se ha eliminado con éxito');
                  redirect(base_url().'index.php/pagos');  

              } else {

                  $this->session->set_flashdata('errormessage', 'El pago se encuentra en uso, no es posible eliminarlo.');
                  redirect(base_url().'index.php/pagos/edit/'.$this->input->post('id'));

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
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pagos/manage') ) { 
              
              $this->load->library('datatables');
              $this->datatables->select('b.banc_id,b.banc_nombre,b.banc_descripcion');
              $this->datatables->from('par_pagos b');

              if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pagos/edit')) {
                  
                  $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="'.base_url().'index.php/pagos/edit/$1" class="btn btn-default btn-xs" title="Editar tipo pago"><i class="fa fa-pencil-square-o"></i></a>
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
