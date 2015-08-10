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

class Items extends MY_Controller {
    
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
                $this->template->set('title', 'Administrar Items');
                $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
                $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );            
                $this->template->load($this->config->item('admin_template'),'items/items_list', $this->data);
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
                $this->template->set('title', 'Nuevo Item');                            
                $this->template->load($this->config->item('admin_template'),'items/items_add', $this->data);             
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
                $this->form_validation->set_rules('tabla', 'Tabla', 'required|trim|xss_clean|max_length[128]');   
                $this->form_validation->set_rules('key', 'Key', 'required|trim|xss_clean|max_length[128]');                

                if ($this->form_validation->run() == false) 
                {                    
                    $this->session->set_flashdata('errormessage', (validation_errors() ? validation_errors(): false));
                    redirect(base_url().'index.php/items/add');
                }else 
                    {    
                        $data = array(
                            'itod_nombre' => $this->input->post('nombre'),
                            'itod_tabla' => $this->input->post('tabla'),
                            'itod_campoid' => $this->input->post('key'),
                            'itod_descripcion' => $this->input->post('descripcion')                            
                           );
                 
                        if ($this->codegen_model->add('est_itemordenanza',$data) == TRUE) 
                        {
                            $this->session->set_flashdata('message', 'El Item se ha creado con éxito');
                            redirect(base_url().'index.php/items/add');
                        }else 
                            {
                            	$this->session->set_flashdata('errormessage', 'No se pudo registrar el Item');
                                redirect(base_url().'index.php/items/add');                                
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

    
    /*
    * Funcion que renderiza la vista para editar
    * el item especificado
    */
	function edit()
    {    
        if ($this->ion_auth->logged_in()) 
        {
            if ($this->ion_auth->is_admin()) 
            {  
                $idItem = $this->uri->segment(3);
                /*
                * Valida que llegue 
                */
                if ($idItem == '')
                {
                    $this->session->set_flashdata('infomessage', 'Debe elegir un Item para editar');
                    redirect(base_url().'index.php/items');
                }

                /*
                * Valida que el item exista
                */
                $this->data['item'] = $this->codegen_model->get('est_itemordenanza','itod_id,itod_nombre,itod_tabla,itod_campoid,itod_descripcion','itod_id = '.$idItem,1,NULL,true);
                if(count($this->data['item']) <= 0)
                {
                    $this->session->set_flashdata('errormessage', 'No existe el Item Suministrado!');
                    redirect(base_url().'index.php/items');
                }else
                    {
                        $this->data['successmessage'] = $this->session->flashdata('successmessage');                                                                
                        $this->template->set('title', 'Editar Item');
                        $this->template->load($this->config->item('admin_template'),'items/items_edit', $this->data);
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

    /*
    * Funcion que administra la modificacion del item
    * especificado
    */
    function update()
    {
        
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
              $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
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
    * de los items para ordenanzas
    */
    function datatable ()
    {
        if ($this->ion_auth->logged_in()) 
        {          
            if ($this->ion_auth->is_admin()) 
            {                                 
              $this->load->library('datatables'); 
              $this->datatables->select('itod_id,itod_nombre');
              $this->datatables->from('est_itemordenanza');
              $this->datatables->add_column('edit', '<div class="btn-toolbar">'
                        .'<div class="btn-group text-center">'
                        .'<a href="'.base_url().'index.php/items/edit/$1" class="btn btn-default btn-xs" title="Editar"><i class="fa fa-pencil-square-o"></i> Editar</a>'
                        .'</div>'
                        .'</div>', 'itod_id');
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
