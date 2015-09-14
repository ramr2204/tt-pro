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
                $this->data['items']  = $this->codegen_model->getItems();
                $this->data['tiposcontratistas']  = $this->codegen_model->getSelect('con_tiposcontratistas','tpco_id,tpco_nombre');
                $this->data['regimenes']  = $this->codegen_model->getSelect('con_regimenes','regi_id,regi_nombre');
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
                        
                        $path = 'uploads/ordenanzas/'.date('d-m-Y');
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

              $resultado = $this->codegen_model->get('con_contratistas','cont_nit','cont_id = '.$idOrdenanza,1,NULL,true);
       
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
