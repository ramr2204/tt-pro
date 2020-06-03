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

class TotalizadoPersonaTramite extends MY_Controller {
    
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
       if ($this->ion_auth->logged_in()){

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('TotalizadoPersonaTramite/index')){

              $this->data['errorModal']=$this->session->flashdata('errorModal');
              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
              $this->data['accion']=$this->session->flashdata('accion');

              //template data
              $this->template->set('title', 'Administrar liquidaciones');
              $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen',
                            'css/plugins/bootstrap/fileinput.css' => 'screen',
                            'css/plugins/bootstrap/bootstrap-switch.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js',
                        'js/plugins/dataTables/jquery.dataTables.columnFilter.js',
                        'js/accounting.min.js',
                        'js/plugins/bootstrap/fileinput.min.js',
                        'js/plugins/bootstrap/bootstrap-switch.min.js',
                        'js/applicationEvents.js'
                       );

            $this->data['result']['vigencia_tramite'] = $this->codegen_model->getSelect('liquidacion_valor_vigencia_tramite lv', 'lv.vigencia', 'GROUP BY lv.vigencia');


            $this->template->load($this->config->item('admin_template'),'totalizadoPersonaTramite/index', $this->data);
              
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
              redirect(base_url().'index.php/users/login');
      }

    }
	

    function datatable ()
    {
        if ($this->ion_auth->logged_in()) {

            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('TotalizadoPersonaTramite/index') ) 
            { 

                /*
                * Se Valida si el usuario tiene la opcion de editar tipo tramite
                * para renderizar el boton de editar
                */            
        
                $this->load->library('datatables');   

                $this->datatables->select('lp.id,lv.vigencia,lv.valor,lt.nombre, COUNT(lp.id) AS personas, SUM(lv.valor) AS total');
                $this->datatables->from('liquidacion_valor_vigencia_tramite lv');

                $this->datatables->join('liquidacion_tipo_tramites lt', 'lt.id = lv.tramite_id', 'INNER');
                $this->datatables->join('liquidar_tramite_persona lp', 'lp.tipo_tramite_valor = lv.id', 'INNER');
                $this->datatables->group_by('lp.tipo_tramite_valor');

                if(isset($_GET['tipo_tramite']) && $_GET['tipo_tramite'] != '')
                {
                    $this->datatables->where('lp.tipo_tramite_valor = '. $_GET['tipo_tramite']);
                }

                echo $this->datatables->generate();

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

    function exportarExcelFacturacion()
    {   
        $wheres    = '';

        $columnas ='lv.vigencia,
                    lv.valor,
                    lt.nombre,
                    COUNT(lp.id) AS personas, 
                    SUM(lv.valor) AS total';

        $encabezados = array(
                    'Vigencia',
                    'Valor',
                    'Nombre Trámite',
                    'Cantidad Personas',
                    'Total');

        $joins = ' INNER JOIN liquidacion_tipo_tramites lt ON lt.id = lv.tramite_id INNER JOIN liquidar_tramite_persona lp ON lp.tipo_tramite_valor = lv.id';

        $groupBy = "GROUP BY lp.tipo_tramite_valor";

        if(isset($_GET['tipo_tramite']) && $_GET['tipo_tramite'] != '')
        {
            $wheres = 'WHERE lp.tipo_tramite_valor = '. $_GET['tipo_tramite'];
        }

        $results = $this->codegen_model->getSelect('liquidacion_valor_vigencia_tramite lv', $columnas, $wheres, $joins, $groupBy);
        
        $datos_vista = array(
            'nomArchivo'         => 'Liquidacion tramites totalizado',
            'tituloTabla'        => 'Liquidacion tramites totalizado',
            'registros'          => $results,
            'encabezado'         => $encabezados,
            'columnasOcultar'    => array(),
            'condicionAdicional' => array()//opcional
        );
        include APPPATH.'views/templates/exportar_excel_datatable.php';
    }

}
