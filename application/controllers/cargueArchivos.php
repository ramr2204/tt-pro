<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            documentosNormativos
*   Ruta:              /application/controllers/documentosNormativos.php
*   Descripcion:       controlador de documentos normativos
*   Fecha Creacion:    06/Ene/2016
*   @author            Mike Ortiz <engineermikeortiz@gmail.com>
*   @version           2016-01-06
*
*/

class CargueArchivos extends MY_Controller {
    
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
    * de la informacion principal de los documentos normativos
    */
	function manual()
    {
        if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin())
            {
                $this->data['successmessage']=$this->session->flashdata('successmessage');
                $this->data['errormessage']=$this->session->flashdata('errormessage');
                $this->data['infomessage']=$this->session->flashdata('infomessage');
                //template data
                $this->template->set('title', 'Cargue archivos manual ');
                $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
                $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
                $this->template->load($this->config->item('admin_template'),'documentosNormativos/documentosNormativos_list', $this->data);
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
    * Funcion que renderiza la vista para agregar documentos normativos
    */
    function asobancaria()
    {        
        if ($this->ion_auth->logged_in()) 
        {
            if ($this->ion_auth->is_admin()) 
            {
                $this->data['successmessage'] = $this->session->flashdata('successmessage');
                $this->data['errormessage'] = $this->session->flashdata('errormessage');

                $this->template->set('title', 'Nuevo Documento');
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

                /*
                * Extrae los tipos de Documento Normativo para enviar a la vista
                */                
                $this->data['tiposDocumentoN'] = $this->codegen_model->getSelect('tipos_docnormativos',"tidocn_id,tidocn_nombre");                
                
                $this->template->load($this->config->item('admin_template'),'documentosNormativos/documentosNormativos_add', $this->data);             
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
    * Funcion que administra el registro de un
    * nuevo documento normativo
    */
    function save()
    {
        if ($this->ion_auth->logged_in()) 
        {
            if ($this->ion_auth->is_admin()) 
            {                
                $this->form_validation->set_rules('docnor_fecha', 'Fecha Documento', 'required|trim|xss_clean|required');   
                $this->form_validation->set_rules('docnor_iniciovigencia', 'Fecha Inicio Vigencia', 'trim|xss_clean|required');
                $this->form_validation->set_rules('docnor_numero', 'Número de Documento', 'trim|xss_clean|required');
                $this->form_validation->set_rules('docnor_tipo', 'Tipo de Documento', 'numeric|trim|xss_clean|required');

                if($this->form_validation->run() == false) 
                {
                    $this->session->set_flashdata('errormessage', validation_errors());
                    redirect(base_url().'index.php/documentosNormativos/add');                            
                }else
                    {   
                        /*
                        * Valida que las fechas suministradas tengan formato valido
                        */
                        $fechaExpedicion = $this->input->post('docnor_fecha');
                        $fechaInicioVigencia = $this->input->post('docnor_iniciovigencia');

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
                            redirect(base_url().'index.php/documentosNormativos/add');
                        }

                        /*
                        * Valida que el tipo de documento normativo seleccionado exista
                        */
                        $where = 'WHERE tidocn_id = '.$this->input->post('docnor_tipo');
                        $vTipoDoc = $this->codegen_model->getSelect('tipos_docnormativos',"tidocn_id,tidocn_nombre", $where);
                        if(count($vTipoDoc) == 0)
                        {
                            $this->session->set_flashdata('errormessage', 'El Tipo de Documento Normativo Suministrado es Invalido!');
                            redirect(base_url().'index.php/documentosNormativos/add');
                        }

                        /*
                        * Valida que no haya una documento del mismo tipo con el mismo numero
                        * en el mismo año
                        */
                        $year = explode('-', $this->input->post('docnor_fecha'));
                        $year = $year[0];

                        $where = 'WHERE docnor_numero = "'.$this->input->post('docnor_numero').'"'
                            .' AND docnor_year ="'.$year.'"'
                            .' AND docnor_tipo = '.$vTipoDoc[0]->tidocn_id;

                        $vDocumento = $this->codegen_model->getSelect('est_documentosnorma',"docnor_id", $where);
                        if(count($vDocumento) > 0)
                        {
                            $this->session->set_flashdata('errormessage', 'Ya Existe una '. $vTipoDoc[0]->tidocn_nombre .' con el Número ['.$this->input->post('docnor_numero').'] para el Año ['.$year.']');
                            redirect(base_url().'index.php/documentosNormativos/add');
                        }
                        
                        $path = 'uploads/documentosNormativos';
                        if(!is_dir($path)) 
                        { //create the folder if this does not exists
                           mkdir($path,0777,TRUE);      
                        }
                
                        $config['upload_path'] = $path;
                        $config['allowed_types'] = 'jpg|jpeg|gif|png|tif|pdf';
                        $config['remove_spaces']= TRUE;
                        $config['max_size'] = '3000';
                        $config['overwrite'] = TRUE;
                        $config['file_name'] = $vTipoDoc[0]->tidocn_nombre.'_'.$this->input->post('docnor_numero').'_'.$year;                      

                        $this->load->library('upload');
                        $this->upload->initialize($config);  

                        if($this->upload->do_upload("archivo")) 
                        {
                            /*
                            * Se registran los datos del documento normativo
                            */
                            $datos = $this->input->post(NULL,true);                            
                            $datos['docnor_year'] = $year;                            

                            /*
                            * Establece la informacion para actualizar el documento normativo
                            * en este caso la ruta de la copia del documento
                            */
                            $file_datos= $this->upload->data();
                            $datos['docnor_rutadocumento'] = $path.'/'.$file_datos['orig_name'];

                            /*
                            * Se Registra el Documento Normativo
                            */
                            $respuestaProceso = $this->codegen_model->add('est_documentosnorma',$datos);

                            /*
                            * Se redirecciona a la vista
                            */
                            $this->session->set_flashdata('successmessage', 'Se Cargó con éxito la '. $vTipoDoc[0]->tidocn_nombre .' Número ['.$datos['docnor_numero'].'] con Fecha '.$datos['docnor_fecha']);
                            redirect(base_url().'index.php/documentosNormativos/add');
                        }else
                            {
                                $err = $this->upload->display_errors();
                                $this->session->set_flashdata('errormessage', $err);
                                redirect(base_url().'index.php/documentosNormativos/add');
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

    function save()
    {
        if ($this->ion_auth->logged_in()) 
        {
            if ($this->ion_auth->is_admin()) 
            {                
                $this->form_validation->set_rules('docnor_fecha', 'Fecha Documento', 'required|trim|xss_clean|required');   
                $this->form_validation->set_rules('docnor_iniciovigencia', 'Fecha Inicio Vigencia', 'trim|xss_clean|required');
                $this->form_validation->set_rules('docnor_numero', 'Número de Documento', 'trim|xss_clean|required');
                $this->form_validation->set_rules('docnor_tipo', 'Tipo de Documento', 'numeric|trim|xss_clean|required');

                if($this->form_validation->run() == false) 
                {
                    $this->session->set_flashdata('errormessage', validation_errors());
                    redirect(base_url().'index.php/documentosNormativos/add');                            
                }else
                    {   
                        /*
                        * Valida que las fechas suministradas tengan formato valido
                        */
                        $fechaExpedicion = $this->input->post('docnor_fecha');
                        $fechaInicioVigencia = $this->input->post('docnor_iniciovigencia');

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
                            redirect(base_url().'index.php/documentosNormativos/add');
                        }

                        /*
                        * Valida que el tipo de documento normativo seleccionado exista
                        */
                        $where = 'WHERE tidocn_id = '.$this->input->post('docnor_tipo');
                        $vTipoDoc = $this->codegen_model->getSelect('tipos_docnormativos',"tidocn_id,tidocn_nombre", $where);
                        if(count($vTipoDoc) == 0)
                        {
                            $this->session->set_flashdata('errormessage', 'El Tipo de Documento Normativo Suministrado es Invalido!');
                            redirect(base_url().'index.php/documentosNormativos/add');
                        }

                        /*
                        * Valida que no haya una documento del mismo tipo con el mismo numero
                        * en el mismo año
                        */
                        $year = explode('-', $this->input->post('docnor_fecha'));
                        $year = $year[0];

                        $where = 'WHERE docnor_numero = "'.$this->input->post('docnor_numero').'"'
                            .' AND docnor_year ="'.$year.'"'
                            .' AND docnor_tipo = '.$vTipoDoc[0]->tidocn_id;

                        $vDocumento = $this->codegen_model->getSelect('est_documentosnorma',"docnor_id", $where);
                        if(count($vDocumento) > 0)
                        {
                            $this->session->set_flashdata('errormessage', 'Ya Existe una '. $vTipoDoc[0]->tidocn_nombre .' con el Número ['.$this->input->post('docnor_numero').'] para el Año ['.$year.']');
                            redirect(base_url().'index.php/documentosNormativos/add');
                        }
                        
                        $path = 'uploads/documentosNormativos';
                        if(!is_dir($path)) 
                        { //create the folder if this does not exists
                           mkdir($path,0777,TRUE);      
                        }
                
                        $config['upload_path'] = $path;
                        $config['allowed_types'] = 'jpg|jpeg|gif|png|tif|pdf';
                        $config['remove_spaces']= TRUE;
                        $config['max_size'] = '3000';
                        $config['overwrite'] = TRUE;
                        $config['file_name'] = $vTipoDoc[0]->tidocn_nombre.'_'.$this->input->post('docnor_numero').'_'.$year;                      

                        $this->load->library('upload');
                        $this->upload->initialize($config);  

                        if($this->upload->do_upload("archivo")) 
                        {
                            /*
                            * Se registran los datos del documento normativo
                            */
                            $datos = $this->input->post(NULL,true);                            
                            $datos['docnor_year'] = $year;                            

                            /*
                            * Establece la informacion para actualizar el documento normativo
                            * en este caso la ruta de la copia del documento
                            */
                            $file_datos= $this->upload->data();
                            $datos['docnor_rutadocumento'] = $path.'/'.$file_datos['orig_name'];

                            /*
                            * Se Registra el Documento Normativo
                            */
                            $respuestaProceso = $this->codegen_model->add('est_documentosnorma',$datos);

                            /*
                            * Se redirecciona a la vista
                            */
                            $this->session->set_flashdata('successmessage', 'Se Cargó con éxito la '. $vTipoDoc[0]->tidocn_nombre .' Número ['.$datos['docnor_numero'].'] con Fecha '.$datos['docnor_fecha']);
                            redirect(base_url().'index.php/documentosNormativos/add');
                        }else
                            {
                                $err = $this->upload->display_errors();
                                $this->session->set_flashdata('errormessage', $err);
                                redirect(base_url().'index.php/documentosNormativos/add');
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

}
