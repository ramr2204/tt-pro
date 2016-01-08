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

class DocumentosNormativos extends MY_Controller {
    
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
                $this->template->set('title', 'Administrar Documentos Normativos');
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
    function add()
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
                $this->form_validation->set_rules('docnor_numero', 'Número de Documento', 'numeric|trim|xss_clean|required');
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

                        $where = 'WHERE docnor_numero = '.$this->input->post('docnor_numero')
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
                            $this->codegen_model->add('est_documentosnorma',$datos);

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


    /*
    * Funcion que renderiza la vista para modificar un documento normativo
    */
    function edit()
    {    
        if ($this->ion_auth->logged_in()) 
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('documentosNormativos/edit')) 
            {  
                $idDocumentoN = $this->uri->segment(3);
                
                /*
                * Valida que el id del documento normativo no llegue vacio
                */
                if($idDocumentoN == '')
                {
                    $this->session->set_flashdata('errormessage', 'Debe elegir una Documento Normativo para Editar');
                    redirect(base_url().'index.php/documentosNormativos');
                }
    
                /*
                * Valida que el documento normativo exista
                */
                $where = 'WHERE docnor_id = '.$idDocumentoN;            
                $vDocumentoN = $this->codegen_model->getSelect('est_documentosnorma',"*", $where);
                
                if(count($vDocumentoN) <= 0)
                {
                    $this->session->set_flashdata('errormessage', 'El Documento Normativo Suministrado no Existe!');
                    redirect(base_url().'index.php/documentosNormativos');
                }
    
                $this->data['successmessage'] = $this->session->flashdata('successmessage');
                $this->data['errormessage'] = $this->session->flashdata('errormessage');
                $this->data['documentoN'] = $vDocumentoN[0];
    
                $this->template->set('title', 'Editar Documento Normativo');
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
                    
                $this->template->load($this->config->item('admin_template'),'documentosNormativos/documentosNormativos_edit', $this->data);              
                            
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
    * Funcion que administra la modificacion de un
    * documento normativo
    */
    function update()
    {
        if ($this->ion_auth->logged_in()) 
        {
            if ($this->ion_auth->is_admin()) 
            {
                /*
                * Valida que el documento normativo suministrado exista                        
                */
                $idDocumentoN = $this->input->post('docnor_id');
                $where = 'WHERE docnor_id = '.$idDocumentoN;            
                $vDocumentoN = $this->codegen_model->getSelect('est_documentosnorma',"*", $where);

                if(count($vDocumentoN) <= 0)
                {
                    $this->session->set_flashdata('errormessage', 'El Documento Normativo Suministrado no Existe!');
                    redirect(base_url().'index.php/documentosNormativos');
                }

                $this->form_validation->set_rules('docnor_fecha', 'Fecha Documento', 'required|trim|xss_clean|required');   
                $this->form_validation->set_rules('docnor_iniciovigencia', 'Fecha Inicio Vigencia', 'trim|xss_clean|required');
                $this->form_validation->set_rules('docnor_numero', 'Número de Documento', 'numeric|trim|xss_clean|required');
                $this->form_validation->set_rules('docnor_tipo', 'Tipo de Documento', 'numeric|trim|xss_clean|required');

                if($this->form_validation->run() == false) 
                {
                    $this->session->set_flashdata('errormessage', validation_errors());
                    redirect(base_url().'index.php/documentosNormativos/edit/'.$idDocumentoN);                            
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
                            redirect(base_url().'index.php/documentosNormativos/edit/'.$idDocumentoN);
                        }

                        /*
                        * Valida que el tipo de documento normativo seleccionado exista
                        */
                        $where = 'WHERE tidocn_id = '.$this->input->post('docnor_tipo');
                        $vTipoDoc = $this->codegen_model->getSelect('tipos_docnormativos',"tidocn_id,tidocn_nombre", $where);
                        if(count($vTipoDoc) == 0)
                        {
                            $this->session->set_flashdata('errormessage', 'El Tipo de Documento Normativo Suministrado es Invalido!');
                            redirect(base_url().'index.php/documentosNormativos/edit/'.$idDocumentoN);
                        }
                        
                        /*
                        * Valida si el numero de Documento Normativo, tipo y año suministrados
                        * son los mismos para verificar o no que no se repita
                        * el mismo numero de Documento Normativo en el mismo año
                        */                        
                        $year = explode('-', $this->input->post('docnor_fecha'));
                        $year = $year[0];

                        if($vDocumentoN[0]->docnor_numero != $this->input->post('docnor_numero') || $vDocumentoN[0]->docnor_year != $year || $vDocumentoN[0]->docnor_tipo != $this->input->post('docnor_tipo'))
                        {
                            $where = 'WHERE docnor_numero = '.$this->input->post('docnor_numero')
                            .' AND docnor_year ="'.$year.'"'
                            .' AND docnor_tipo = '.$vTipoDoc[0]->tidocn_id;

                            $vDocumento = $this->codegen_model->getSelect('est_documentosnorma',"docnor_id", $where);
                            if(count($vDocumento) > 0)
                            {
                                $this->session->set_flashdata('errormessage', 'Ya Existe una '. $vTipoDoc[0]->tidocn_nombre .' con el Número ['.$this->input->post('docnor_numero').'] para el Año ['.$year.']');
                                redirect(base_url().'index.php/documentosNormativos/edit/'.$idDocumentoN);
                            }                            
                        }

                        /*
                        * Se registran los datos del documento normativo
                        */
                        $datos = $this->input->post(NULL,true);                            
                        $datos['docnor_year'] = $year;
                        unset($datos['docnor_id']);

                        /*
                        * Valida si el archivo fue cargado
                        * para modificarlo o no
                        */
                        if (isset($_FILES['archivo']) && is_uploaded_file($_FILES['archivo']['tmp_name'])) 
                        {
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
                            $config['file_name']= $vTipoDoc[0]->tidocn_nombre.'_'.$this->input->post('docnor_numero').'_'.$year;                      
    
                            $this->load->library('upload');
                            $this->upload->initialize($config);
                            
                            if($this->upload->do_upload("archivo")) 
                            {
                                /*
                                * Establece la informacion para actualizar el documento normativo
                                * en este caso la ruta de la copia del documento
                                */
                                $file_datos= $this->upload->data();
                                $datos['docnor_rutadocumento'] = $path.'/'.$file_datos['orig_name'];
                            }else
                                {
                                    $err = $this->upload->display_errors();
                                    $this->session->set_flashdata('errormessage', $err);
                                    redirect(base_url().'index.php/documentosNormativos/edit/'.$idDocumentoN);
                                }                          
                        }                        

                        /*
                        * Se Actualiza el documento normativo
                        */
                        $this->codegen_model->edit('est_documentosnorma',$datos,'docnor_id',$idDocumentoN);

                        /*
                        * Se redirecciona a la vista
                        */
                        $this->session->set_flashdata('successmessage', 'Se Modificó con éxito la '. $vTipoDoc[0]->tidocn_nombre .' Número ['.$datos['docnor_numero'].'] con Fecha '.$datos['docnor_fecha']);
                        redirect(base_url().'index.php/documentosNormativos/edit/'.$idDocumentoN);
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
    * de la informacion principal de los documentos normativos
    */
    function datatable ()
    {
        if ($this->ion_auth->logged_in()) 
        {          
            if ($this->ion_auth->is_admin()) 
            {                                 
                $this->load->library('datatables'); 
                $this->datatables->select('dc.docnor_id,tdc.tidocn_nombre,dc.docnor_numero,dc.docnor_fecha,dc.docnor_iniciovigencia,dc.docnor_rutadocumento');
                $this->datatables->from('est_documentosnorma dc');
                $this->datatables->join('tipos_docnormativos tdc', 'tdc.tidocn_id = dc.docnor_tipo', 'left');
                $this->datatables->edit_column('dc.docnor_rutadocumento','<a class="btn btn-success" href="'.base_url().'$1" target="_blank"><img src="'.base_url().'$1" class="file-preview-image" alt="Ver Documento" title="documento" height="120mm"></a>','dc.docnor_rutadocumento');

                $this->datatables->add_column('edit', '<div class="btn-toolbar">'
                    .'<div class="btn-group text-center">'
                    .'<a href="'.base_url().'index.php/documentosNormativos/edit/$1" class="btn btn-default btn-xs" title="Modificar"><i class="fa fa-pencil-square-o"></i> Editar</a>'
                    .'</div>'
                    .'</div>', 'dc.docnor_id');
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
