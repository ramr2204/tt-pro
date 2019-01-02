<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            parametros
*   Ruta:              /modulo/controllers/parametros.php
*   Descripcion:       controlador de parametros
*   Fecha Creacion:    14/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-14
*
*/

class Parametros extends MY_Controller {
    
  function __construct() 
  {
      parent::__construct();
	    $this->load->library('form_validation');		
		  $this->load->helper(array('form','url','codegen_helper'));
		  $this->load->model('codegen_model','',TRUE);

	}	
	
	function index()
  {
		  $this->edit();
	}

	
	function edit()
  {    
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin()) {  

              $idparametro =1;

              $this->form_validation->set_rules('redondeo', 'Cifra de redondeo',  'required|trim|xss_clean|numeric|greater_than[0]');  
              $this->form_validation->set_rules('salariominimo', 'Salario mínimo',  'required|trim|xss_clean|numeric|greater_than[0]');
              $this->form_validation->set_rules('estampillassaldo', 'Saldo estampillas',  'required|trim|xss_clean|numeric|greater_than[0]');
              $this->form_validation->set_rules('rotulosminimos', 'Cantidad rotulos',  'required|trim|xss_clean|numeric|greater_than[0]');

              if ($this->form_validation->run() == false) {
                  
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                            
              } else {

                /*
                * Valida si se seleccionó la bandera $contingencia
                * para registrar el rango de papeleria con ese atributo
                */        
                $contingencia = 0;                
                if(isset($_POST['contingencia']) && $this->input->post('contingencia') == 'SI')
                {
                    $contingencia = 1;
                }
                  
                  $data = array(
                          'para_redondeo' => $this->input->post('redondeo'),
                          'para_salariominimo' => $this->input->post('salariominimo'),
                          'para_contingencia' => $contingencia,
                          'para_estampillasnotificacion' => $this->input->post('estampillassaldo'),
                          'para_rotulosminimosusuario' => $this->input->post('rotulosminimos'),
                   );
                           
                	if ($this->codegen_model->edit('adm_parametros',$data,'para_id',$idparametro) == TRUE) {

                      $this->session->set_flashdata('successmessage', 'Los parámetros se han editado con éxito');
                      redirect(base_url().'index.php/parametros/edit/'.$idparametro);
                      
                	} else {
                				  
                      $this->data['errormessage'] = 'No se pudo registrar los parámetros';

                	}
              }
              
                  $this->data['successmessage']=$this->session->flashdata('successmessage');
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                  $this->data['result'] = $this->codegen_model->get('adm_parametros', 'para_redondeo,para_salariominimo,para_contingencia,para_estampillasnotificacion,para_rotulosminimosusuario','para_id = '.$idparametro,1,NULL,true);
                  $this->template->set('title', 'Editar parámetros');
                  $this->template->load($this->config->item('admin_template'),'parametros/parametros_edit', $this->data);
                        
          }else {
              redirect(base_url().'index.php/error_404');
          }
      } else {
          redirect(base_url().'index.php/users/login');
      }
        
  }
	
 
}
