<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            impresiones
*   Ruta:              /application/controllers/impresiones.php
*   Descripcion:       controlador de impresiones
*   Fecha Creacion:    20/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-20
*
*/

class Impresiones extends MY_Controller {
    
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('impresiones/manage')){

              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
              //template data
              $this->template->set('title', 'Administrar impresiones');
              $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
            
              $this->template->load($this->config->item('admin_template'),'impresiones/impresiones_list', $this->data);

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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('impresiones/add')) {

              $this->data['successmessage']=$this->session->flashdata('message');  
        		  $this->form_validation->set_rules('tipoanulacion', 'Nombre', 'required|trim|xss_clean|max_length[100]');   
              $this->form_validation->set_rules('observaciones', 'Observaciones', 'trim|xss_clean|max_length[500]');
              $this->form_validation->set_rules('codigopapel', 'Consecutivo', 'required|trim|xss_clean|numeric|');

             

              if ($this->form_validation->run() == false) {

                  $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
              } else {    

                   $resultado = $this->codegen_model->get('est_tiposanulaciones','tisa_id',"tisa_nombre = '".$this->input->post('tipoanulacion')."'",1,NULL,true);

                   //si no se encuentra creado el tipo de anulación
                   //en esta sección se crea en la bd
                   if (!$resultado->tisa_id>0){

                      $datos = array(
                        'tisa_nombre' => $this->input->post('tipoanulacion')
                     );
                      $this->codegen_model->add('est_tiposanulaciones',$datos); 
                      $tipoanulacionid=$this->db->insert_id();
                  //////////////////////////////////////////////////
                   } else {
                      $tipoanulacionid=$resultado->tisa_id;
                   }

                   //Verifica si el codigo de papel a eliminar esta asignado a un
                   //contrato en caso de estarlo realiza las operaciones necesarias
                   //para actualizar el estado del contrato y el estado de la impresion
                    $result= $this->codegen_model->get('est_impresiones','impr_id,impr_facturaid',"impr_codigopapel = '".$this->input->post('codigopapel')."'",1,NULL,true);

                  if ($result) {
                       $facturas= $this->codegen_model->get('est_facturas','fact_liquidacionid',"fact_id = '".$result->impr_facturaid."'",1,NULL,true);
                       $liquidaciones= $this->codegen_model->get('est_liquidaciones','liqu_contratoid',"liqu_id = '".$facturas->fact_liquidacionid."'",1,NULL,true);
                       
                       $cdata = array(
                         'cntr_estadolocalid' => 1,
                      );
                       $this->codegen_model->edit('con_contratos',$cdata,'cntr_id',$liquidaciones->liqu_contratoid);

                       //Sobre escribe el id de la factura que se habia generado con ese papel
                       //por cero (0) y actualiza el estado de impresión a 2
                       $data = array(
                         'impr_codigopapel' => $this->input->post('codigopapel'),
                         'impr_observaciones' => $this->input->post('observaciones'),
                         'impr_tipoanulacionid' => $tipoanulacionid,
                         'impr_facturaid' => 0,
                         'impr_estado' => 2,
                      );

                      if ($this->codegen_model->edit('est_impresiones',$data,'impr_id',$result->impr_id) == TRUE) {
                          
                          $this->session->set_flashdata('successmessage', 'La anulación se ha creado con éxito');
                          redirect(base_url().'index.php/impresiones');
                      } else {  
                          $this->data['errormessage'] = 'No se pudo registrar la anulación';
                      }
                    
                  } else {

                      //En caso de no existir una factura ni contrato asignado al papel que se
                      //va a anular se crea solamente la anulación con el codigo del papel respectivo
                      $papeles = $this->codegen_model->get('est_papeles','pape_id,pape_codigoinicial,pape_codigofinal','pape_codigoinicial <= '.$this->input->post('codigopapel').' AND pape_codigofinal >= '.$this->input->post('codigopapel'),1,NULL,true);
                      $data = array(
                        'impr_codigopapel' => $this->input->post('codigopapel'),
                        'impr_observaciones' => $this->input->post('observaciones'),
                        'impr_tipoanulacionid' => $tipoanulacionid,
                        'impr_estado' => 2,
                        'impr_fecha' => date('Y-m-d H:i:s',now()),
                        'impr_papelid' => $papeles->pape_id
                     );
                    
                       
                     if ($this->codegen_model->add('est_impresiones',$data) == TRUE) {

                         $this->session->set_flashdata('message', 'La anulación se ha creado con éxito');
                         redirect(base_url().'index.php/impresiones');
                      } else {
                         $this->data['errormessage'] = 'No se pudo registrar la anulación';
                      }
                  }

    		      }
                
              $this->template->set('title', 'Nueva anulación');
              $this->data['javascripts']= array(
                        'js/plugins/typeahead/typeahead.bundle.min.js'
                       );
              $this->data['style_sheets']= array(
                            'css/plugins/typeahead/typeahead.css' => 'screen'
                        );
              $tiposanulaciones = $this->codegen_model->getSelect('est_tiposanulaciones','tisa_nombre');
              $anulaciones='[';
              foreach ($tiposanulaciones as $key => $value) {
                $anulaciones.= "'".$value->tisa_nombre."' , ";
              }
              $anulaciones=substr($anulaciones, 0, -3);
              $anulaciones.=']';
              $this->data['tiposanulaciones'] = $anulaciones;
              $this->template->load($this->config->item('admin_template'),'impresiones/impresiones_add', $this->data);
             
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('impresiones/edit')) {  

              $idregimen = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
              if ($idregimen==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un tipo de anulación para editar');
                  redirect(base_url().'index.php/impresiones');
              }
              $resultado = $this->codegen_model->get('est_impresiones','impr_nombre','impr_id = '.$idregimen,1,NULL,true);
              
              foreach ($resultado as $key => $value) {
                  $aplilo[$key]=$value;
              }
              
              if ($aplilo['impr_nombre']==$this->input->post('nombre')) {
                  
                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]');
              
              } else {

                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]|is_unique[est_impresiones.impr_nombre]');
              
              }

              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[500]');
              $this->form_validation->set_rules('iva', 'IVA', 'required|trim|xss_clean|numeric|');

              if ($this->form_validation->run() == false) {
                  
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                            
              } else {                            
                  
                  $data = array(
                          'impr_nombre' => $this->input->post('nombre'),
                          'impr_descripcion' => $this->input->post('descripcion'),
                          'impr_iva' => $this->input->post('iva')
                   );
                           
                	if ($this->codegen_model->edit('est_impresiones',$data,'impr_id',$idregimen) == TRUE) {

                      $this->session->set_flashdata('successmessage', 'El anulación se ha editado con éxito');
                      redirect(base_url().'index.php/impresiones/edit/'.$idregimen);
                      
                	} else {
                				  
                      $this->data['errormessage'] = 'No se pudo registrar el aplilo';

                	}
              }       
                  $this->data['successmessage']=$this->session->flashdata('successmessage');
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                	$this->data['result'] = $this->codegen_model->get('est_impresiones','impr_id,impr_nombre,impr_descripcion,impr_iva','impr_id = '.$idregimen,1,NULL,true);
                  $this->template->set('title', 'Editar anulación');
                  $this->template->load($this->config->item('admin_template'),'impresiones/impresiones_edit', $this->data);
                        
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('impresiones/delete')) {  
              if ($this->input->post('id')==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un tipo de anulación para eliminar');
                  redirect(base_url().'index.php/impresiones');
              }
              if (!$this->codegen_model->depend('est_contratistas','cont_regimenid',$this->input->post('id'))) {

                  $this->codegen_model->delete('est_impresiones','impr_id',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'El anulación se ha eliminado con éxito');
                  redirect(base_url().'index.php/impresiones');  

              } else {

                  $this->session->set_flashdata('errormessage', 'El anulación se encuentra en uso, no es posible eliminarlo.');
                  redirect(base_url().'index.php/impresiones/edit/'.$this->input->post('id'));

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
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('impresiones/manage') ) { 
              
              $this->load->library('datatables');
              $this->datatables->select('i.impr_id,i.impr_codigopapel,i.impr_fecha,f.fact_nombre,ta.tisa_nombre,i.impr_observaciones,i.impr_estado');
              $this->datatables->from('est_impresiones i');
              $this->datatables->join('est_facturas f', 'f.fact_id = i.impr_facturaid', 'left');
              $this->datatables->join('est_tiposanulaciones ta', 'ta.tisa_id = i.impr_tipoanulacionid', 'left');
              echo $this->datatables->generate();

          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }           
  }

}


