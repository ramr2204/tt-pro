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
                * Valida que llegue id del item
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
    	if ($this->ion_auth->logged_in()) 
        {
            if ($this->ion_auth->is_admin()) 
            {  
                $idItem = $this->uri->segment(3);

                /*
                * Valida que llegue id del item
                */
                if ($idItem == '')
                {
                    $this->session->set_flashdata('infomessage', 'Debe elegir un Item para editar');
                    redirect(base_url().'index.php/items');
                }

                /*
                * Valida que el item exista
                */
                $vItem = $this->codegen_model->get('est_itemordenanza','itod_id,itod_nombre,itod_tabla,itod_campoid,itod_descripcion','itod_id = '.$idItem,1,NULL,true);
                if(count($vItem) <= 0)
                {
                    $this->session->set_flashdata('errormessage', 'No existe el Item Suministrado!');
                    redirect(base_url().'index.php/items');
                }else
                    {
                        $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[128]');
                        $this->form_validation->set_rules('tabla', 'Tabla', 'required|trim|xss_clean|max_length[128]');   
                        $this->form_validation->set_rules('key', 'Key', 'required|trim|xss_clean|max_length[128]');
                    	
                        if($this->form_validation->run() == false) 
                        {
                        	$this->session->set_flashdata('errormessage', (validation_errors() ? validation_errors(): false));
                            redirect(base_url().'index.php/items/edit/$idItem');
                        }else
                            {
                                $data = array(
                                    'itod_nombre' => $this->input->post('nombre'),
                                    'itod_tabla' => $this->input->post('tabla'),
                                    'itod_campoid' => $this->input->post('key'),
                                    'itod_descripcion' => $this->input->post('descripcion')                            
                                    );

                	            if($this->codegen_model->edit('est_itemordenanza',$data,'itod_id',$idItem) == TRUE) 
                	            {           
                                    $this->session->set_flashdata('successmessage', 'El Item se ha editado con éxito');
                                    redirect(base_url().'index.php/items/edit/'.$idItem);                                  
                	            }else
                	                {                            				 
                                        $this->session->set_flashdata('errormessage', 'No se Pudo editar el Item.');
                                        redirect(base_url().'index.php/items/edit/'.$idItem);
                	                }
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
