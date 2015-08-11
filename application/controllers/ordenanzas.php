<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            Ordenanzas
*   Ruta:              /application/controllers/ordenanzas.php
*   Descripcion:       controlador de ordenanzas
*   Fecha Creacion:    10/Ago/2015
*   @author            Mike Ortiz <engineermikeortiz@gmail.com>
*   @version           2015-08-10
*
*/

class Ordenanzas extends MY_Controller {
    
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
    
    /*
    * Funcion de apoyo que renderiza vista que contiene
    * de la informacion principal de la ordenanza
    */
	function manage()
    {
        if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin())
            {
                $this->data['successmessage']=$this->session->flashdata('successmessage');
                $this->data['errormessage']=$this->session->flashdata('errormessage');
                $this->data['infomessage']=$this->session->flashdata('infomessage');
                //template data
                $this->template->set('title', 'Administrar Ordenanzas');
                $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
                $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );            
                $this->template->load($this->config->item('admin_template'),'ordenanzas/ordenanzas_list', $this->data);
            }else 
                {
                    redirect(base_url().'index.php/error_404');
                }
        }else 
            {
                redirect(base_url().'index.php/users/login');
            }
    }
	
    /*
    * Funcion que renderiza la vista para agregar ordenanzas
    */
    function add()
    {        
        if ($this->ion_auth->logged_in()) 
        {
            if ($this->ion_auth->is_admin()) 
            {
                $this->data['successmessage'] = $this->session->flashdata('message');             
                $this->template->set('title', 'Nueva Ordenanza');
                $this->data['style_sheets'] = array(
                        'css/chosen.css' => 'screen'
                    );
                $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js'
                    );
                $this->data['items']  = $this->codegen_model->getItems();
                $this->data['tiposcontratistas']  = $this->codegen_model->getSelect('con_tiposcontratistas','tpco_id,tpco_nombre');
                $this->data['regimenes']  = $this->codegen_model->getSelect('con_regimenes','regi_id,regi_nombre');
                $this->template->load($this->config->item('admin_template'),'contratistas/contratistas_add', $this->data);             
            }else 
                {
                    redirect(base_url().'index.php/error_404');
                }
        }else
            {
                redirect(base_url().'index.php/users/login');
            }

  }	

  /*
  * Funcion que administra el registro de una
  * nueva ordenanza
  */
  function save()
  {
        if ($this->ion_auth->logged_in()) 
        {
            if ($this->ion_auth->is_admin()) 
            {
                $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
                $this->form_validation->set_rules('nit', 'NIT', 'required|numeric|trim|xss_clean|max_length[100]|is_unique[con_contratistas.cont_nit]');   
                $this->form_validation->set_rules('telefono', 'Telefono', 'numeric|trim|xss_clean|max_length[15]');
                $this->form_validation->set_rules('direccion', 'Dirección', 'trim|xss_clean|max_length[256]');
                $this->form_validation->set_rules('municipioid', 'Municipio',  'required|numeric|greater_than[0]');
                $this->form_validation->set_rules('regimenid', 'Tipo de régimen',  'required|numeric|greater_than[0]');
                $this->form_validation->set_rules('tipocontratistaid', 'Tipo tributario',  'required|numeric|greater_than[0]'); 

                  if ($this->form_validation->run() == false) {

                  $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
              } else {    

                  $data = array(
                        'cont_nombre' => $this->input->post('nombre'),
                        'cont_tipocontratistaid' => $this->input->post('tipocontratistaid'),
                        'cont_nit' => $this->input->post('nit'),
                        'cont_direccion' => $this->input->post('direccion'),
                        'cont_municipioid' => $this->input->post('municipioid'),
                        'cont_regimenid' => $this->input->post('regimenid'),
                        'cont_telefono' => $this->input->post('telefono'),
                        'cont_fecha' => date('Y-m-d')

                     );
                 
                        if ($this->codegen_model->add('con_contratistas',$data) == TRUE) {

                      $this->session->set_flashdata('message', 'El contratista se ha creado con éxito');
                      redirect(base_url().'index.php/contratistas/add');
                        } else {

                              $this->data['errormessage'] = 'No se pudo registrar el contratista';

                        }

                  }
            }else 
                    {
                        redirect(base_url().'index.php/error_404');
                    }
        }else
            {
                redirect(base_url().'index.php/users/login');
            }
  }


	function edit()
  {    
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratistas/edit')) {  

              $idregimen = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
              if ($idregimen==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un contratista para editar');
                  redirect(base_url().'index.php/contratistas');
              }
              $resultado = $this->codegen_model->get('con_contratistas','cont_nit','cont_id = '.$idregimen,1,NULL,true);
       
              foreach ($resultado as $key => $value) {
                  $aplilo[$key]=$value;
              }
             
              if ($aplilo['cont_nit']==$this->input->post('nit')) {
                  
                  $this->form_validation->set_rules('nit', 'NIT', 'required|trim|xss_clean|max_length[100]');
              
              } else {

                  $this->form_validation->set_rules('nit', 'NIT', 'required|trim|xss_clean|max_length[100]|is_unique[con_contratistas.cont_nit]');
              
              }
              $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]');   
              $this->form_validation->set_rules('direccion', 'Dirección', 'trim|xss_clean|max_length[256]');
              $this->form_validation->set_rules('telefono', 'Telefono', 'numeric|trim|xss_clean|max_length[15]');
              $this->form_validation->set_rules('municipioid', 'Municipio',  'required|numeric|greater_than[0]');
              $this->form_validation->set_rules('regimenid', 'Tipo de régimen',  'required|numeric|greater_than[0]');
              $this->form_validation->set_rules('tipocontratistaid', 'Tipo tributario',  'required|numeric|greater_than[0]');   

              if ($this->form_validation->run() == false) {
                  
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                            
              } else {                            
                  
                  $data = array(
                        'cont_nombre' => $this->input->post('nombre'),
                        'cont_nit' => $this->input->post('nit'),
                        'cont_direccion' => $this->input->post('direccion'),
                        'cont_municipioid' => $this->input->post('municipioid'),
                        'cont_regimenid' => $this->input->post('regimenid'),
                        'cont_telefono' => $this->input->post('telefono'),
                        'cont_tipocontratistaid' => $this->input->post('tipocontratistaid')

                     );
                           
                	if ($this->codegen_model->edit('con_contratistas',$data,'cont_id',$idregimen) == TRUE) {

                      $this->session->set_flashdata('successmessage', 'El contratista se ha editado con éxito');
                      redirect(base_url().'index.php/contratistas/edit/'.$idregimen);
                      
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
                	$this->data['result'] = $this->codegen_model->get('con_contratistas','cont_id,cont_nombre,cont_nit,cont_direccion,cont_telefono,cont_municipioid,cont_regimenid,cont_tributarioid, cont_tipocontratistaid','cont_id = '.$idregimen,1,NULL,true);
                  $this->data['municipios']  = $this->codegen_model->getMunicipios();
                  $this->data['regimenes']  = $this->codegen_model->getSelect('con_regimenes','regi_id,regi_nombre');
                  $this->data['tiposcontratistas']  = $this->codegen_model->getSelect('con_tiposcontratistas','tpco_id,tpco_nombre');
                  $this->template->set('title', 'Editar contratista');
                  $this->template->load($this->config->item('admin_template'),'contratistas/contratistas_edit', $this->data);
                        
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('contratistas/delete')) {  
              if ($this->input->post('id')==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un contratista para eliminar');
                  redirect(base_url().'index.php/contratistas');
              }
              if (!$this->codegen_model->depend('con_contratos','cntr_contratistaid',$this->input->post('id'))) {

                  $this->codegen_model->delete('con_contratistas','cont_id',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'El contratista se ha eliminado con éxito');
                  redirect(base_url().'index.php/contratistas');  

              } else {

                  $this->session->set_flashdata('errormessage', 'El contratista se encuentra en uso, no es posible eliminarlo.');
                  redirect(base_url().'index.php/contratistas/edit/'.$this->input->post('id'));

              }
                         
          } else {
              redirect(base_url().'index.php/error_404');       
          } 
      } else {
          redirect(base_url().'index.php/users/login');
      }
  }
    
    function detalles ()
    {
        if ($this->ion_auth->logged_in()) 
        {          
            if ($this->ion_auth->is_admin()) 
            {                                 
              $this->load->library('datatables'); 
              $this->datatables->select('orde_id,orde_numero,orde_fecha,orde_iniciovigencia,orde_rutadocumento');
              $this->datatables->from('est_ordenanzas');
              $this->datatables->add_column('edit', '<div class="btn-toolbar">'
                        .'<div class="btn-group">'
                        .'<a href="'.base_url().'index.php/contratistas/edit/$1" class="btn btn-default btn-xs" title="Editar contratista"><i class="fa fa-pencil-square-o"></i></a>'
                        .'</div>'
                        .'</div>', 'c.cont_id');

              echo $this->datatables->generate();
            }else
                {
                    redirect(base_url().'index.php/error_404');
                }               
        }else
            {
                redirect(base_url().'index.php/users/login');
            }           
    }
    
    /*
    * Funcion de apoyo que renderiza la datatable
    * de la informacion principal de la ordenanza
    */
    function datatable ()
    {
        if ($this->ion_auth->logged_in()) 
        {          
            if ($this->ion_auth->is_admin()) 
            {                                 
              $this->load->library('datatables'); 
              $this->datatables->select('orde_id,orde_numero,orde_fecha,orde_iniciovigencia,orde_rutadocumento');
              $this->datatables->from('est_ordenanzas');
              $this->datatables->edit_column('orde_rutadocumento','<a href="'.base_url().'$1" target="_blank"><img src="'.base_url().'$1" class="file-preview-image" alt="ordenanza" title="ordenanza" height="120mm"></a>','orde_rutadocumento');

              $this->datatables->add_column('edit', '<div class="btn-toolbar">'
                        .'<div class="btn-group text-center">'
                        .'<a href="'.base_url().'index.php/contratistas/edit/$1" class="btn btn-default btn-xs" title="Ver Detalles"><i class="fa fa-search"></i> Ver</a>'
                        .'</div>'
                        .'</div>', 'orde_id');
              echo $this->datatables->generate();
            }else
                {
                    redirect(base_url().'index.php/error_404');
                }               
        }else
            {
                redirect(base_url().'index.php/users/login');
            }           
    }
}
