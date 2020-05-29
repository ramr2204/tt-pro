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

class CargueArchivosAsobancaria extends MY_Controller {
    
    function __construct() 
    {
      parent::__construct();
	    $this->load->library('form_validation');		
		$this->load->helper(array('form','url','codegen_helper'));
		$this->load->model('codegen_model','',TRUE);
	}	
	
    
    /*
    * Funcion de apoyo que renderiza vista que contiene
    * de la informacion principal de los documentos normativos
    */
	function index()
    {
         if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin())
            {
                $this->data['successmessage']=$this->session->flashdata('successmessage');
                $this->data['errormessage']=$this->session->flashdata('errormessage');
                $this->data['infomessage']=$this->session->flashdata('infomessage');
                //template data
                $this->template->set('title', 'Cargue archivos asobancaria ');
                $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
                $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
                $this->data['result'] = array(
                    'bancos' => $this->codegen_model->getSelect('par_bancos','banc_id,banc_nombre')
                );

                $this->template->load($this->config->item('admin_template'),'cargueFacturacionArchivos/asobancaria/index', $this->data);
            }
            else 
            {
                redirect(base_url().'index.php/error_404');
            }
        }
        else
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
                $this->form_validation->set_rules('banco_tramite', 'Banco trámite', 'trim|xss_clean|required');

                if($this->form_validation->run() == false) 
                {
                    $this->session->set_flashdata('errormessage', validation_errors());
                    redirect(base_url().'index.php/cargueArchivosAsobancaria/index');                            
                }
                else
                {   
                    $_FILES["archivo_asobancaria"]["size"] ;
                    $tipo_archivo = $_FILES["archivo_asobancaria"]["type"];

                    if (isset($_FILES["archivo_asobancaria"]) && is_uploaded_file($_FILES['archivo_asobancaria']['tmp_name'])) 
                    {

                        if (count(strpos($tipo_archivo, "text"))>0 || count(strpos($tipo_archivo, "octet-stream"))>0)
                        {
  
                            //SE ABRE EL archivo_asobancaria EN MODO LECTURA
                            $fp = fopen($_FILES['archivo_asobancaria']['tmp_name'], "r");
                            //SE RECORRE
                            $contarFacturas = 0;
                            while (!feof($fp))
                            { 
                                //SI SE LEE SEPARADO POR COMAS
                                $data  = explode(",", fgets($fp));

                                /*
                                * Valida que el número de factura exista
                                */
                                $data = trim($data[0]);

                                $vFactura = $this->codegen_model->getSelect("liquidar_tramite_persona","id", "WHERE numero_factura = '".$data ."' AND pagado = 0");

                                
                                if(!count($vFactura) == 0)
                                {
                                    $data_edit = array(
                                        'banco' => $this->input->post('banco_tramite'),
                                        'pagado' => 1,
                                    );
                                    
                                    $editarFactura = $this->codegen_model->edit('liquidar_tramite_persona',$data_edit,'numero_factura',$data);
                                    
                                    $contarFacturas++;
                                }

                            } 

                            if($contarFacturas == 0)
                            {
                                $this->session->set_flashdata('errormessage', 'Ninguna factura correspondiente al archivo pertenece al sistema');
                                redirect(base_url().'index.php/cargueArchivosAsobancaria/index');
                            }
                            else
                            {
                                $this->session->set_flashdata('successmessage', 'Se insertaron correctamente un total de '. $contarFacturas . ' facturas.');
                                redirect(base_url().'index.php/cargueArchivosAsobancaria/index');
                            }
                        }
                        else
                        {
                            $this->session->set_flashdata('errormessage', 'Solo se permiten extensiones .txt');
                            redirect(base_url().'index.php/cargueArchivosAsobancaria/index');
                        }
    
                    } 
                    else
                    {
                        $this->session->set_flashdata('errormessage', 'Error de subida');
                        redirect(base_url().'index.php/cargueArchivosAsobancaria/index');
                    }

                }
            }
            else 
            {
                redirect(base_url().'index.php/error_404');
            }
        }
        else 
        {
            redirect(base_url().'index.php/users/login');
        }
    }


}
