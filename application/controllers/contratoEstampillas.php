<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            Ordenanzas
*   Ruta:              /application/controllers/contratoEstampillas.php
*   Descripcion:       controlador de ordenanzas
*   Fecha Creacion:    05/Ene/2016
*   @author            Mike Ortiz <engineermikeortiz@gmail.com>
*   @version           2016-01-05
*
*/

class ContratoEstampillas extends MY_Controller {
    
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
    * la informacion principal de los contratos
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
                $this->template->set('title', 'Administrar Contratos de Estampillas');
                $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
                $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );            
                $this->template->load($this->config->item('admin_template'),'contratosestampillas/contratosestampillas_list', $this->data);
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
    * Funcion que renderiza la vista para agregar contratos de estampillas
    */
    function add()
    {        
        if ($this->ion_auth->logged_in()) 
        {
            if ($this->ion_auth->is_admin()) 
            {
                $this->data['successmessage'] = $this->session->flashdata('successmessage');
                $this->data['errormessage'] = $this->session->flashdata('errormessage');

                $this->template->set('title', 'Nuevo Contrato Estampillas');
                $this->data['style_sheets'] = array(
                        'css/chosen.css' => 'screen',
                        'css/plugins/bootstrap/bootstrap-datetimepicker.css' => 'screen'                        
                    );
                $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js',
                        'js/plugins/bootstrap/moment.js',
                        'js/plugins/bootstrap/bootstrap-datetimepicker.js'                        
                    );
                
                $this->template->load($this->config->item('admin_template'),'contratosestampillas/contratosestampillas_add', $this->data);             
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
    * nuevo contrato de estampillas
    */
    function save()
    {
        if ($this->ion_auth->logged_in()) 
        {
            if ($this->ion_auth->is_admin()) 
            {                
                $this->form_validation->set_rules('conpap_fecha', 'Fecha Contrato', 'required|trim|xss_clean|required');   
                $this->form_validation->set_rules('conpap_numero', 'Número Contrato', 'numeric|trim|xss_clean|required');
                $this->form_validation->set_rules('conpap_cantidad', 'Cantidad Estampillas', 'numeric|trim|xss_clean|required');

                if($this->form_validation->run() == false) 
                {
                    $this->session->set_flashdata('errormessage', validation_errors());
                    redirect(base_url().'index.php/contratoEstampillas/add');                            
                }else
                    {   
                        /*
                        * Valida que la fecha suministrada tenga formato valido
                        */
                        $fechaContrato = $this->input->post('conpap_fecha');                        

                        $patronFecha = '/^[0-9]{4,4}-[0-9]{2,2}-([0-9]{2,2})$/';
                        $errorF = 'Error:<br>';
                        if(!preg_match($patronFecha, $fechaContrato))
                        {
                            $errorF .= 'La Fecha de Contrato debe tener un formato correcto<br>';
                        }                        

                        if($errorF != 'Error:<br>')
                        {
                            $this->session->set_flashdata('errormessage', $errorF);
                            redirect(base_url().'index.php/contratoEstampillas/add');
                        }

                        /*
                        * Valida que no haya un contrato con el mismo numero
                        * en el mismo año
                        */
                        $year = explode('-', $this->input->post('conpap_fecha'));
                        $year = $year[0];

                        $where = 'WHERE conpap_numero = '.$this->input->post('conpap_numero')
                            .' AND conpap_year ="'.$year.'"';
                        $vContratoE = $this->codegen_model->getSelect('est_contratopapeles',"conpap_id", $where);
                        if(count($vContratoE) > 0)
                        {
                            $this->session->set_flashdata('errormessage', 'Ya Existe un Contrato de Estampillas con el Número ['.$this->input->post('conpap_numero').'] para el Año ['.$year.']');
                            redirect(base_url().'index.php/contratoEstampillas/add');
                        }                                                                                                                                   

                        /*
                        * Se registran los datos del contrato de estampillas
                        */
                        $datos = $this->input->post(NULL, TRUE);
                        $datos['conpap_year'] = $year;
                        $datos['conpap_estado'] = 1;                                                

                        /*
                        * Se Registra la Ordenanza
                        */
                        $this->codegen_model->add('est_contratopapeles',$datos);

                        /*
                        * Se redirecciona a la vista
                        */
                        $this->session->set_flashdata('successmessage', 'Se Creó con éxito el Contrato de Estampillas Número ['.$datos['conpap_numero'].'] con Fecha '.$datos['conpap_fecha']);
                        redirect(base_url().'index.php/contratoEstampillas/index');                        
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
    * de la informacion principal de los contratos de estampillas
    */
    function datatable ()
    {
        if ($this->ion_auth->logged_in()) 
        {          
            if ($this->ion_auth->is_admin()) 
            {                                 
                $this->load->library('datatables'); 
                $this->datatables->select('conpap_numero,conpap_fecha,conpap_cantidad,conpap_impresos,conpap_observaciones');
                $this->datatables->from('est_contratopapeles');              

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
