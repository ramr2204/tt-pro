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

class CargueArchivosManual extends MY_Controller {
    
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
                $this->template->set('title', 'Cargue archivos manual ');
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

                $this->template->load($this->config->item('admin_template'),'cargueFacturacionArchivos/manual/index', $this->data);
            }
            else 
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
                $this->form_validation->set_rules('fecha_pago_tramite', 'Fecha pago', 'required|trim|xss_clean|required');   
                $this->form_validation->set_rules('banco_tramite', 'Banco trámite', 'trim|xss_clean|required');
                $this->form_validation->set_rules('numero_factura_tramite', 'Número factura', 'trim|xss_clean|required');

                if($this->form_validation->run() == false) 
                {
                    $this->session->set_flashdata('errormessage', validation_errors());
                    redirect(base_url().'index.php/cargueArchivosManual/index');                            
                }
                else
                {   

                    /*
                    * Valida que el número de factura exista
                    */
                    $where = "WHERE numero_factura = '".$this->input->post('numero_factura_tramite') . "'";
                    $vFactura = $this->codegen_model->getSelect('liquidar_tramite_persona',"*", $where);

                    if(count($vFactura) == 0)
                    {
                        $this->session->set_flashdata('errormessage', 'El Numero Factura Suministrado no existe!');
                        redirect(base_url().'index.php/cargueArchivosManual/index');
                    }else if($vFactura[0]->pagado == 1)
                    {
                        $this->session->set_flashdata('errormessage', 'Ya se subió el soporte para esta factura!');
                        redirect(base_url().'index.php/cargueArchivosManual/index');
                    }

                   
                    $path = 'uploads/cargueFacturasPago';
                    if(!is_dir($path)) 
                    { //create the folder if this does not exists
                       mkdir($path,0777,TRUE);      
                    }
            
                    $config['upload_path'] = $path;
                    $config['allowed_types'] = 'jpg|jpeg|gif|png|tif|pdf';
                    $config['remove_spaces']= TRUE;
                    $config['max_size'] = '3000';
                    $config['overwrite'] = TRUE;
                    $config['file_name'] = $vFactura[0]->numero_factura.'_'.$vFactura[0]->ndocumento;                      

                    $this->load->library('upload');
                    $this->upload->initialize($config);  

                    if($this->upload->do_upload("imagen_tramite")) 
                    {
                        /*
                        * Establece la informacion para actualizar el documento normativo
                        * en este caso la ruta de la copia del documento
                        */
                        $file_datos= $this->upload->data();
                        $datos['ruta_archivo_pago'] = $path.'/'.$file_datos['orig_name'];

                        $data_edit = array(
                            'ruta_archivo_pago' => $datos['ruta_archivo_pago'],
                            'fecha_pago' => $this->input->post('fecha_pago_tramite'),
                            'banco' => $this->input->post('banco_tramite'),
                            'pagado' => 1,
                        );
                        
                        $this->codegen_model->edit('liquidar_tramite_persona',$data_edit,'numero_factura',$this->input->post('numero_factura_tramite'));

                        /*
                        * Se redirecciona a la vista
                        */
                        $this->session->set_flashdata('successmessage', 'Se Cargó con éxito la factura N. ' . $vFactura[0]->numero_factura. ' en ' . $datos['ruta_archivo_pago']);
                        redirect(base_url().'index.php/cargueArchivosManual/index');
                    }
                    else
                    {
                        $err = $this->upload->display_errors();
                        $this->session->set_flashdata('errormessage', $err);
                        redirect(base_url().'index.php/cargueArchivosManual/index');
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
