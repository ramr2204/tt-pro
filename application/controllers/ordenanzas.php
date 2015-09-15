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
                $this->data['successmessage'] = $this->session->flashdata('successmessage');
                $this->data['errormessage'] = $this->session->flashdata('errormessage');

                $this->template->set('title', 'Nueva Ordenanza');
                $this->data['style_sheets'] = array(
                        'css/chosen.css' => 'screen',
                        'css/plugins/bootstrap/bootstrap-datetimepicker.css' => 'screen',
                        'css/plugins/bootstrap/fileinput.css' => 'screen'
                    );
                $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js',
                        'js/plugins/bootstrap/moment.js',
                        'js/plugins/bootstrap/bootstrap-datetimepicker.js',
                        'js/plugins/bootstrap/fileinput.min.js'
                    );
                
                $this->template->load($this->config->item('admin_template'),'ordenanzas/ordenanzas_add', $this->data);             
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
                $this->form_validation->set_rules('orde_fecha', 'Fecha Pago', 'required|trim|xss_clean|required');   
                $this->form_validation->set_rules('orde_iniciovigencia', 'Fecha Inicio Vigencia', 'trim|xss_clean|required');
                $this->form_validation->set_rules('orde_numero', 'Número de Ordenanza', 'numeric|trim|xss_clean|required');

                if($this->form_validation->run() == false) 
                {
                    $this->session->set_flashdata('errormessage', validation_errors());
                    redirect(base_url().'index.php/ordenanzas/add');                            
                }else
                    {   
                        /*
                        * Valida que las fechas suministradas tengan formato valido
                        */
                        $fechaExpedicion = $this->input->post('orde_fecha');
                        $fechaInicioVigencia = $this->input->post('orde_iniciovigencia');

                        $patronFecha = '/^[0-9]{4,4}-[0-9]{2,2}-([0-9]{2,2})$/';
                        $errorF = 'Error:<br>';
                        if(!preg_match($patronFecha, $fechaExpedicion))
                        {
                            $errorF .= 'La Fecha de Expedición debe tener un formato correcto<br>';
                        }
                        if(!preg_match($patronFecha, $fechaInicioVigencia))
                        {
                            $errorF .= 'La Fecha de Inicio de Vigencia debe tener un formato correcto<br>';
                        }

                        if($errorF != 'Error:<br>')
                        {
                            $this->session->set_flashdata('errormessage', $errorF);
                            redirect(base_url().'index.php/ordenanzas/add');
                        }

                        /*
                        * Valida que no haya una ordenanza con el mismo numero
                        * en el mismo año
                        */
                        $year = explode('-', $this->input->post('orde_fecha'));
                        $year = $year[0];

                        $where = 'WHERE orde_numero = '.$this->input->post('orde_numero')
                            .' AND orde_year ="'.$year.'"';
                        $vOrdenanza = $this->codegen_model->getSelect('est_ordenanzas',"orde_id", $where);
                        if(count($vOrdenanza) > 0)
                        {
                            $this->session->set_flashdata('errormessage', 'Ya Existe una Ordenanza con el Número ['.$this->input->post('orde_numero').'] para el Año ['.$year.']');
                            redirect(base_url().'index.php/ordenanzas/add');
                        }
                        
                        $path = 'uploads/ordenanzas';
                        if(!is_dir($path)) 
                        { //create the folder if this does not exists
                           mkdir($path,0777,TRUE);      
                        }
                
                        $config['upload_path'] = $path;
                        $config['allowed_types'] = 'jpg|jpeg|gif|png|tif|pdf';
                        $config['remove_spaces']= TRUE;
                        $config['max_size'] = '2048';
                        $config['overwrite'] = TRUE;
                        $config['file_name']='ordenanza_'.$this->input->post('orde_numero').'_'.$year;                      

                        $this->load->library('upload');
                        $this->upload->initialize($config);  

                        if($this->upload->do_upload("archivo")) 
                        {
                            /*
                            * Establece la informacion para actualizar la liquidacion
                            * en este caso la ruta de la copia del objeto del contrato
                            */
                            $file_datos= $this->upload->data();
                            $datos['orde_rutadocumento'] = $path.'/'.$file_datos['orig_name'];

                            /*
                            * Se registran los datos de la ordenanza
                            */                      
                            $datos['orde_numero'] = $this->input->post('orde_numero');
                            $datos['orde_fecha'] = $this->input->post('orde_fecha');
                            $datos['orde_iniciovigencia'] = $this->input->post('orde_iniciovigencia');                            
                            $datos['orde_year'] = $year;
                            $datos['orde_estado'] = 1;

                            /*
                            * Se Registra la Ordenanza
                            */
                            $this->codegen_model->add('est_ordenanzas',$datos);

                            /*
                            * Se redirecciona a la vista
                            */
                            $this->session->set_flashdata('successmessage', 'Se Cargó con éxito la Ordenanza Número ['.$datos['orde_numero'].'] con Fecha '.$datos['orde_fecha']);
                            redirect(base_url().'index.php/ordenanzas/add');
                        }else
                            {
                                $err = $this->upload->display_errors();
                                $this->session->set_flashdata('errormessage', $err);
                                redirect(base_url().'index.php/ordenanzas/add');
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
    if ($this->ion_auth->logged_in()) 
    {
        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('ordenanzas/edit')) 
        {  
            $idOrdenanza = $this->uri->segment(3);
            
            /*
            * Valida que el id de la ordenanza no llegue vacio
            */
            if($idOrdenanza == '')
            {
                $this->session->set_flashdata('errormessage', 'Debe elegir una Ordenanza para Editar');
                redirect(base_url().'index.php/ordenanzas');
            }

            /*
            * Valida que la ordenanza exista
            */
            $where = 'WHERE orde_id = '.$idOrdenanza;            
            $vOrdenanza = $this->codegen_model->getSelect('est_ordenanzas',"*", $where);
            
            if(count($vOrdenanza) <= 0)
            {
                $this->session->set_flashdata('errormessage', 'La Ordenanza Suministrada no Existe!');
                redirect(base_url().'index.php/ordenanzas');
            }

            $this->data['successmessage'] = $this->session->flashdata('successmessage');
            $this->data['errormessage'] = $this->session->flashdata('errormessage');
            $this->data['ordenanza'] = $vOrdenanza[0];

            $this->template->set('title', 'Editar Ordenanza');
            $this->data['style_sheets'] = array(
                    'css/chosen.css' => 'screen',
                    'css/plugins/bootstrap/bootstrap-datetimepicker.css' => 'screen',
                    'css/plugins/bootstrap/fileinput.css' => 'screen'
                );
            $this->data['javascripts']= array(
                    'js/chosen.jquery.min.js',
                    'js/plugins/bootstrap/moment.js',
                    'js/plugins/bootstrap/bootstrap-datetimepicker.js',
                    'js/plugins/bootstrap/fileinput.min.js'
                );
                
            $this->template->load($this->config->item('admin_template'),'ordenanzas/ordenanzas_edit', $this->data);              
                        
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
    function update()
    {
        if ($this->ion_auth->logged_in()) 
        {
            if ($this->ion_auth->is_admin()) 
            {
                /*
                * Valida que la ordenanza suministrada exista                        
                */
                $idOrdenanza = $this->input->post('id');
                $where = 'WHERE orde_id = '.$idOrdenanza;            
                $vOrdenanza = $this->codegen_model->getSelect('est_ordenanzas',"*", $where);

                if(count($vOrdenanza) <= 0)
                {
                    $this->session->set_flashdata('errormessage', 'La Ordenanza Suministrada no Existe!');
                    redirect(base_url().'index.php/ordenanzas');
                }

                $this->form_validation->set_rules('orde_fecha', 'Fecha Pago', 'required|trim|xss_clean|required');   
                $this->form_validation->set_rules('orde_iniciovigencia', 'Fecha Inicio Vigencia', 'trim|xss_clean|required');
                $this->form_validation->set_rules('orde_numero', 'Número de Ordenanza', 'numeric|trim|xss_clean|required');

                if($this->form_validation->run() == false) 
                {
                    $this->session->set_flashdata('errormessage', validation_errors());
                    redirect(base_url().'index.php/ordenanzas/edit/'.$idOrdenanza);                            
                }else
                    {   
                        /*
                        * Valida que las fechas suministradas tengan formato valido
                        */
                        $fechaExpedicion = $this->input->post('orde_fecha');
                        $fechaInicioVigencia = $this->input->post('orde_iniciovigencia');

                        $patronFecha = '/^[0-9]{4,4}-[0-9]{2,2}-([0-9]{2,2})$/';
                        $errorF = 'Error:<br>';
                        if(!preg_match($patronFecha, $fechaExpedicion))
                        {
                            $errorF .= 'La Fecha de Expedición debe tener un formato correcto<br>';
                        }
                        if(!preg_match($patronFecha, $fechaInicioVigencia))
                        {
                            $errorF .= 'La Fecha de Inicio de Vigencia debe tener un formato correcto<br>';
                        }

                        if($errorF != 'Error:<br>')
                        {
                            $this->session->set_flashdata('errormessage', $errorF);
                            redirect(base_url().'index.php/ordenanzas/edit/'.$idOrdenanza);
                        }                                                
                        
                        /*
                        * Valida si el numero de ordenanza y año suministrados
                        * son los mismos para verificar o no que no se repita
                        * el mismo numero de ordenanza en el mismo año
                        */                        
                        $year = explode('-', $this->input->post('orde_fecha'));
                        $year = $year[0];

                        if($vOrdenanza[0]->orde_numero != $this->input->post('orde_numero') || $vOrdenanza[0]->orde_year != $year)
                        {
                            $where = 'WHERE orde_numero = '.$this->input->post('orde_numero')
                                .' AND orde_year ="'.$year.'"';
                            $vOrdenanza = $this->codegen_model->getSelect('est_ordenanzas',"orde_id", $where);
                            if(count($vOrdenanza) > 0)
                            {
                                $this->session->set_flashdata('errormessage', 'Ya Existe una Ordenanza con el Número ['.$this->input->post('orde_numero').'] para el Año ['.$year.']');
                                redirect(base_url().'index.php/ordenanzas/edit/'.$idOrdenanza);
                            }                            
                        }

                        /*
                        * Valida si el archivo fue cargado
                        * para modificarlo o no
                        */        
                        if (isset($_FILES['upload_field_name']) && is_uploaded_file($_FILES['archivo']['tmp_name'])) 
                        {
                            $path = 'uploads/ordenanzas';
                            if(!is_dir($path)) 
                            { //create the folder if this does not exists
                               mkdir($path,0777,TRUE);      
                            }
                    
                            $config['upload_path'] = $path;
                            $config['allowed_types'] = 'jpg|jpeg|gif|png|tif|pdf';
                            $config['remove_spaces']= TRUE;
                            $config['max_size'] = '2048';
                            $config['overwrite'] = TRUE;
                            $config['file_name']='ordenanza_'.$this->input->post('orde_numero').'_'.$year;                      
    
                            $this->load->library('upload');
                            $this->upload->initialize($config);
                            
                            if($this->upload->do_upload("archivo")) 
                            {
                                /*
                                * Establece la informacion para actualizar la liquidacion
                                * en este caso la ruta de la copia del objeto del contrato
                                */
                                $file_datos= $this->upload->data();
                                $datos['orde_rutadocumento'] = $path.'/'.$file_datos['orig_name'];
                            }else
                                {
                                    $err = $this->upload->display_errors();
                                    $this->session->set_flashdata('errormessage', $err);
                                    redirect(base_url().'index.php/ordenanzas/edit/'.$idOrdenanza);
                                }                          
                        }                                                

                        /*
                        * Se registran los datos de la ordenanza
                        */                      
                        $datos['orde_numero'] = $this->input->post('orde_numero');
                        $datos['orde_fecha'] = $this->input->post('orde_fecha');
                        $datos['orde_iniciovigencia'] = $this->input->post('orde_iniciovigencia');                            
                        $datos['orde_year'] = $year;
                        $datos['orde_estado'] = 1;

                        /*
                        * Se Actualiza la Ordenanza
                        */
                        $this->codegen_model->edit('est_ordenanzas',$datos,'orde_id',$idOrdenanza);

                        /*
                        * Se redirecciona a la vista
                        */
                        $this->session->set_flashdata('successmessage', 'Se Modificó con éxito la Ordenanza Número ['.$datos['orde_numero'].'] con Fecha '.$datos['orde_fecha']);
                        redirect(base_url().'index.php/ordenanzas/edit/'.$idOrdenanza);
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
              $this->datatables->edit_column('orde_rutadocumento','<a class="btn btn-success" href="'.base_url().'$1" target="_blank"><img src="'.base_url().'$1" class="file-preview-image" alt="Ver Ordenanza" title="ordenanza" height="120mm"></a>','orde_rutadocumento');

              $this->datatables->add_column('edit', '<div class="btn-toolbar">'
                        .'<div class="btn-group text-center">'
                        .'<a href="'.base_url().'index.php/ordenanzas/edit/$1" class="btn btn-default btn-xs" title="Modificar"><i class="fa fa-pencil-square-o"></i> Editar</a>'
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
