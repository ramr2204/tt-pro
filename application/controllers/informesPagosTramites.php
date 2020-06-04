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

class InformesPagosTramites extends MY_Controller {
    
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

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('InformesPagosTramites/index')){

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


            $this->template->load($this->config->item('admin_template'),'informePagosTramites/index', $this->data);
              
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

            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('InformesPagosTramites/index') ) 
            { 

                /*
                * Se Valida si el usuario tiene la opcion de editar tipo tramite
                * para renderizar el boton de editar
                */            
        
                $this->load->library('datatables');     
                $this->datatables->add_column('factura', '<div class="btn-toolbar">'
                        .'<div>'
                        .'<a href="'.base_url().'liquidacionTramite/pdf?id=$1" class="btn btn-success btn-xs" title="ver factura"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>'
                        .'</div>'
                        .'</div>', 'lp.id');

                $this->datatables->select( 'lp.id,
                                            lp.primer_nombre,
                                            lp.segundo_nombre,
                                            lp.primer_apellido,
                                            lp.segundo_apellido,
                                            lp.telefono1,
                                            lp.telefono2,
                                            lp.direccion,
                                            depto.depa_nombre,
                                            muni.muni_nombre,
                                            lt.nombre as nombre_tramite,
                                            lp.fecha_creacion,
                                            lp.pagado,
                                            td.nombre,
                    
                                            lp.fecha_pago,
                                            bancos.banc_nombre,
                                            lp.numero_factura,
                                            lp.ruta_archivo_pago');

                $this->datatables->from('liquidar_tramite_persona lp');

                $this->datatables->join('par_departamentos depto', 'depto.depa_id = lp.departamento_residencia', 'INNER');
                $this->datatables->join('par_municipios muni', 'muni.muni_id = lp.municipio', 'INNER');
                $this->datatables->join('liquidacion_valor_vigencia_tramite lv', 'lv.id = lp.tipo_tramite_valor', 'INNER');
                $this->datatables->join('liquidacion_tipo_tramites lt', 'lt.id = lv.tramite_id', 'INNER');
                $this->datatables->join('tipo_documento td', 'td.id = lp.tipo_documento', 'INNER');
                $this->datatables->join('par_bancos bancos', 'bancos.banc_id = lp.banco', 'LEFT');

                if(isset($_GET["fecha_ini"]) || isset($_GET["fecha_fin"]))
                {
                    if($_GET["fecha_ini"] == "" && $_GET["fecha_fin"] != "")
                    {
                        $this->datatables->where("lp.fecha_creacion <= '" . $_GET["fecha_fin"] . "'");
                    }else if($_GET["fecha_ini"] != "" && $_GET["fecha_fin"] == "")
                    {
                        $this->datatables->where("lp.fecha_creacion >= '" . $_GET["fecha_ini"] . "'");

                    }else if($_GET["fecha_ini"] != "" && $_GET["fecha_fin"] != "")
                    {
                        $this->datatables->where("lp.fecha_creacion >= '" . $_GET["fecha_ini"] . "' " . "AND lp.fecha_creacion <= '". $_GET["fecha_fin"] . "'");
                    }
                }

                if(isset($_GET['tipo_tramite']))
                {
                    $this->datatables->where("lp.tipo_tramite_valor = ". $_GET['tipo_tramite'] );
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
        $pagado    = '';
        $fecha_ini = '';
        $fecha_fin = '';
        $wheres    = '';

        $columnas = 'lp.primer_nombre,
                    lp.segundo_nombre,
                    lp.primer_apellido,
                    lp.segundo_apellido,
                    lp.telefono1,
                    lp.telefono2,
                    lp.direccion,
                    depto.depa_nombre,
                    muni.muni_nombre,
                    lt.nombre as nombre_tramite,
                    lp.fecha_creacion,
                    lp.pagado,
                    td.nombre,
                    lp.fecha_pago,
                    bancos.banc_nombre,
                    lp.numero_factura';

        $encabezados = array(
                    'Primer Nombre',
                    'Segundo Nombre',
                    'Primer Apellido',
                    'Segundo Apellido',
                    'Telefono 1',
                    'Telefono 2',
                    'Dirección',
                    'Departamento',
                    'Municipio',
                    'Trámite',
                    'Fecha Creación',
                    'Pagado',
                    'Documento',
                    'Fecha Pago',
                    'Banco',
                    'Numero Factura');

        $select = "SELECT ".$columnas." FROM liquidar_tramite_persona lp";


        $joins = " INNER JOIN `par_departamentos` depto ON `depto`.`depa_id` = `lp`.`departamento_residencia`".
        " INNER JOIN `par_municipios` muni ON `muni`.`muni_id` = `lp`.`municipio`".
        " INNER JOIN `liquidacion_valor_vigencia_tramite` lv ON `lv`.`id` = `lp`.`tipo_tramite_valor`".
        " INNER JOIN `liquidacion_tipo_tramites` lt ON `lt`.`id` = `lv`.`tramite_id`".
        " INNER JOIN `tipo_documento` td ON `td`.`id` = `lp`.`tipo_documento`".
        " LEFT JOIN `par_bancos` bancos ON `bancos`.`banc_id` = `lp`.`banco`";


        if($_GET['pagado'] != 'undefined')
        {
            $pagado .= " lp.pagado = ". $_GET['pagado'];
        }

        if($_GET['fecha_ini'] != '')
        {
            $fecha_ini .= " lp.fecha_creacion >= '" . $_GET['fecha_ini'] . "'";
        }

        if($_GET['fecha_fin'] != '')
        {
            $fecha_fin.= " lp.fecha_creacion <= '" . $_GET['fecha_fin'] . "'";
        }

        if($_GET['tipo_tramite'] != '' && $_GET['tipo_tramite'] != 'null')
        {
            $tipo_tramite .= " lp.tipo_tramite_valor =" . $_GET["tipo_tramite"];
        }

        if($pagado != '')
        {
            $wheres .= ' WHERE ' . $pagado ;
        }

        if($fecha_ini != '')
        {
            if($wheres != '')
            {
                $wheres .= ' AND ' . $fecha_ini;
            }
            else
            {
                $wheres .= ' WHERE '. $fecha_ini;
            }
        }

        if($fecha_fin != '')
        {
            if($wheres != '')
            {
                $wheres .= ' AND '. $fecha_fin;
            }
            else
            {
                $wheres .= ' WHERE '. $fecha_ini;
            }
        }

        if($tipo_tramite != '')
        {
            if($wheres != '')
            {
                $wheres .= ' AND '. $tipo_tramite;
            }
            else
            {
                $wheres .= ' WHERE '. $tipo_tramite;
            }
        }

        $results = $this->codegen_model->getSelect('liquidar_tramite_persona lp', $columnas, $wheres, $joins);

        $datos_vista = array(
            'nomArchivo'         => 'Liquidacion personas',
            'tituloTabla'        => 'Liquidacion personas',
            'registros'          => $results,
            'encabezado'         => $encabezados,
            'columnasOcultar'    => array(),
            'condicionAdicional' => array('campo'=> 'pagado','comparar'=>'1','condicionIf' => "Sí", 'condicionElse' => 'No')//opcional
        );
        include APPPATH.'views/templates/exportar_excel_datatable.php';
    }

}
