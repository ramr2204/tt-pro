<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   @author            David Mahecha
*   @version           2021-10-20
*
*/

class Declaraciones extends MY_Controller
{
    function __construct() 
    {
        parent::__construct();
        $this->load->library('form_validation','Pdf');    
        $this->load->model('liquidaciones_model','',TRUE);
        $this->load->model('codegen_model','',TRUE);
        
        $this->load->helper(['form','url','codegen_helper', 'array']);
        $this->load->helper('Equivalencias');
    }

    /**
     * Lista todas las declaraciones
     * 
     * @return null
     */
    function index()
    {
    	if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('users/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/index')) //remove this elseif if you want to enable this for non-admins
		{
			//redirect them to the home page because they must be an administrator to view this
			redirect('error_404', 'refresh');
			
		}
		else
		{
			$this->data['successmessage']	= $this->session->flashdata('successmessage');
			$this->data['errormessage']		= $this->session->flashdata('errormessage');
			$this->data['infomessage']		= $this->session->flashdata('infomessage');

			//template data
            $this->template->set('title', 'Usuarios');
            $this->data['style_sheets'] = [
                'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
            ];
            $this->data['javascripts'] = [
                'js/jquery.dataTables.min.js',
                'js/plugins/dataTables/dataTables.bootstrap.js',
                'js/jquery.dataTables.defaults.js'
            ];

            $this->data['meses'] = $this->obtenerMeses();
            $this->data['tipos_declaraciones'] = Equivalencias::tipoDeclaraciones();

			$this->template->load($this->config->item('admin_template'),'declaraciones/index', $this->data);
		}
    }

    /**
     * Retorna un arreglo con los nombres de los meses
     * 
     * @param boolean? $mes_corto
     * @return array
     */
    private function obtenerMeses($mes_corto = false)
    {
        setlocale(LC_TIME, 'es');
        $meses = [];

        for($mes = 1; $mes <= 12; $mes++){
            $meses[$mes] = $mes_corto ? substr(strftime('%b', mktime(0, 0, 0, $mes)), 0, -1) : strftime('%B', mktime(0, 0, 0, $mes));
        }

        return $meses;
    }

    /**
     * Retorna datos para el procesamiento del dataTable
     * 
     * @return null
     */
    function dataTable()
    {
        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/index'))
        {
            $this->load->library('datatables');
            $this->datatables->select('d.id, empresa.nombre AS empresa, estampilla.estm_nombre AS estampilla,
                d.periodo, d.tipo_declaracion, d.fecha_creacion, d.estado');
            $this->datatables->from('declaraciones AS d');
            $this->datatables->join('empresas empresa','empresa.id = d.id_empresa','inner');
            $this->datatables->join('est_estampillas estampilla','estampilla.estm_id = d.id_estampilla','inner');

            echo $this->datatables->generate();
        }
        else
        {
          redirect(base_url().'index.php/users/login');
        }
    }

    /**
     * Renderiza la vista de crear y maneja las acciones relacionadas
     * 
     * @return null
     */
    public function create()
    {
        if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/index'))
            {
                $_POST = array_merge($_POST, ($this->session->flashdata('campos') ? $this->session->flashdata('campos') : []) );

                $this->data['successmessage'] = $this->session->flashdata('successmessage');
                $this->data['errormessage']   = $this->session->flashdata('errormessage');
                $this->data['infomessage']    = $this->session->flashdata('infomessage');

                $this->data['consulta'] = [];

                if($this->input->post('acc') == 'consultar') {
                    $this->consultar();
                }
                elseif($this->input->post('acc') == 'generar')
                {
                    $exito = $this->generar();

                    if($exito) {
                        redirect(base_url().'index.php/declaraciones/index');
                    } else {
                        # Si fallo se consultan de nuevo los datos
                        $this->consultarDatos();
                    }
                }

                $this->template->set('title', 'Administrar declaraciones');

                $this->data['style_sheets'] = array(
                    'css/chosen.css' => 'screen',
                    'css/plugins/bootstrap/bootstrap-datetimepicker.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/chosen.jquery.min.js',
                    'js/plugins/bootstrap/moment.js',
                    'js/plugins/bootstrap/bootstrap-datetimepicker.js',
                    'js/autoNumeric.js'
                );

                $this->data['empresas'] = $this->codegen_model->getSelect('empresas','id, nombre', 'WHERE estado = 1', '', '', 'ORDER BY nombre');
                $this->data['estampillas'] = $this->codegen_model->getSelect('est_estampillas', 'estm_id AS id, estm_nombre AS nombre');

                $this->data['clasificaciones_contratos'] = Equivalencias::clasificacionContratos();

                $this->template->load($this->config->item('admin_template'),'declaraciones/create', $this->data);
            } else {
                redirect(base_url().'index.php/error_404');
            }
        } else {
            redirect(base_url().'index.php/users/login');
        }
    }

    /**
     * Valida los datos para  la consulta de valores de los contratos
     * 
     * @return null
     */
    private function consultar()
    {
        $this->form_validation->set_rules('empresa', 'Empresa','required|trim|xss_clean|is_exists[empresas.id]');
        $this->form_validation->set_rules('tipo_estampilla', 'Tipo Estampilla','required|trim|xss_clean|is_exists[est_estampillas.estm_id]');

        $this->form_validation->set_rules(
            'periodo',
            'Período gravable',
            [
                'required',
                'trim',
                'xss_clean',
                'regex_match[/^\d{4}\-(0[1-9]|1[012])$/]'
            ]
        );

        if ($this->form_validation->run() == false) {
            $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
        } else {
            $this->consultarDatos();
        }
    }

    /**
     * Consulta los valores de los contratos con los parametros especificados
     * 
     * @return null
     */
    private function consultarDatos()
    {
        $consulta = [];

        $pagos = $this->codegen_model->getSelect(
            'estampillas_pro_boyaca.pagos_estampillas AS pagos',
            'contrato.clasificacion,
                SUM(cuota.valor) as base,
                SUM(pagos.valor) as pagado,
                factura.fact_porcentaje AS porcentaje',
            'WHERE factura.fact_estampillaid = '. $this->input->post('tipo_estampilla') .'
                and DATE_FORMAT(pagos.fecha, "%Y-%m") = "'. $this->input->post('periodo') .'"
                and liquidacion.id_empresa = '. $this->input->post('empresa'),
            'INNER JOIN est_facturas factura ON factura.fact_id = pagos.factura_id
                INNER JOIN est_liquidaciones liquidacion ON liquidacion.liqu_id = factura.fact_liquidacionid
                INNER JOIN cuotas_liquidacion cuota ON cuota.id = factura.id_cuota_liquidacion
                INNER JOIN con_contratos contrato ON contrato.cntr_id = liquidacion.liqu_contratoid',
            'GROUP BY contrato.clasificacion'
        );
        $pagos = array_lists($pagos, '', 'clasificacion');

        if(count($pagos) > 0)
        {
            foreach(Equivalencias::clasificacionContratos() AS $id => $nombre)
            {
                if( isset($pagos[$id]) ) {
                    $pagos[$id]->clase = $nombre;
                    $consulta[] = $pagos[$id];
                } else {
                    $consulta[] = (object)[
                        'clasificacion' => $id,
                        'clase'         => $nombre,
                        'base'          => 0,
                        'pagado'        => 0,
                        'porcentaje'    => 0,
                    ];
                }
            }

            $estampilla = $this->codegen_model->getSelect(
                'est_estampillas',
                'estm_nombre AS nombre',
                'WHERE estm_id = '.$this->input->post('tipo_estampilla')
            );

            $this->data['consulta'] = $consulta;
            $this->data['estampilla'] = $estampilla[0];
        } else {
            $this->data['errormessage'] = 'No se encontraron datos por los valores buscados.';
        }
    }

    /**
     * Procesa el registro de la declaracion
     * 
     * @return null
     */
    private function generar()
    {
        $es_correccion = $this->input->post('tipo_declaracion') == Equivalencias::declaracionCorreccion();

        $this->form_validation->set_rules('empresa', 'Empresa','required|trim|xss_clean|is_exists[empresas.id]');
        $this->form_validation->set_rules('tipo_estampilla', 'Tipo Estampilla','required|trim|xss_clean|is_exists[est_estampillas.estm_id]');

        $this->form_validation->set_rules('periodo', 'Período gravable',
            [
                'required',
                'trim',
                'xss_clean',
                'regex_match[/^\d{4}\-(0[1-9]|1[012])$/]'
            ]
        );
        $this->form_validation->set_rules('tipo_declaracion', 'Tipo de declaración',
            [
                'required',
                'trim',
                'xss_clean',
                'in_list['. implode(',', array_keys(Equivalencias::tipoDeclaraciones()) ) .']'
            ]
        );

        if($es_correccion)
        {
            $this->form_validation->set_rules('declaracion_correccion', 'No. declaración (Corrección)','required|trim|xss_clean|integer');
            $this->form_validation->set_rules('radicacion_correccion', 'No. de radicación (Corrección)','required|trim|xss_clean|integer');
            $this->form_validation->set_rules('fecha_correccion', 'Fecha (Corrección)',
                [
                    'required',
                    'trim',
                    'xss_clean',
                    'regex_match[/^(19|20)\d\d-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/]'
                ]
            );
            $this->form_validation->set_rules('periodo_correccion', 'Período gravable (Corrección)',
                [
                    'required',
                    'trim',
                    'xss_clean',
                    'regex_match[/^\d{4}\-(0[1-9]|1[012])$/]'
                ]
            );
        }

        $this->form_validation->set_rules('recaudado', 'Valor recaudado','required|trim|xss_clean|numeric');
        $this->form_validation->set_rules('sanciones', 'Valor sanciones','required|trim|xss_clean|numeric');
        $this->form_validation->set_rules('intereses', 'Valor intereses','required|trim|xss_clean|numeric');
        $this->form_validation->set_rules('total_base', 'Total a favor del departameto','required|trim|xss_clean|numeric');
        $this->form_validation->set_rules('total_estampillas', 'Valor liquidado estampilla pro cultura','required|trim|xss_clean|numeric');
        $this->form_validation->set_rules('saldo_periodo_anterior', 'Saldo a favor período anterior','required|trim|xss_clean|numeric');
        $this->form_validation->set_rules('sanciones_pago', 'Valor sanciones','required|trim|xss_clean|numeric');
        $this->form_validation->set_rules('intereses_mora', 'Intereses de mora','required|trim|xss_clean|numeric');
        $this->form_validation->set_rules('total_cargo', 'Total a cargo por recaudo estampilla, sanciones e intereses','required|trim|xss_clean|numeric');
        $this->form_validation->set_rules('saldo_favor', 'Saldo a favor','required|trim|xss_clean|numeric');

        foreach(Equivalencias::clasificacionContratos() AS $id => $nombre)
        {
            $this->form_validation->set_rules('detalle_vigencia_actual['. $id .']', 'Vigencia actual '. $id,
                [
                    'required',
                    'trim',
                    'xss_clean',
                    'regex_match[/^(19|20)\d\d$/]'
                ]
            );
            $this->form_validation->set_rules('detalle_vigencia_anterior['. $id .']', 'Vigencia anterior '. $id,
                [
                    'required',
                    'trim',
                    'xss_clean',
                    'regex_match[/^(19|20)\d\d$/]'
                ]
            );
            $this->form_validation->set_rules('detalle_porcentaje['. $id .']', 'Tarifa '. $id, 'required|trim|xss_clean|numeric');
            $this->form_validation->set_rules('detalle_base['. $id .']', 'Valor base '. $id, 'required|trim|xss_clean|numeric');
            $this->form_validation->set_rules('detalle_pagado['. $id .']', 'Valor recaudo estampilla '. $id, 'required|trim|xss_clean|numeric');
        }

        if ($this->form_validation->run() == false) {
            $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
            return false;
        } else {
            if($this->input->post('tipo_declaracion') != Equivalencias::declaracionCorreccion()) {
                $validacion = $this->codegen_model->countwhere('declaraciones',
                    'id_empresa = "'. $this->input->post('empresa') .'"
                        AND id_estampilla = "'. $this->input->post('tipo_estampilla') .'"
                        AND DATE_FORMAT(periodo, "%Y-%m") = "'. $this->input->post('periodo') .'"');
                
                if($validacion->contador > 0) {
                    $this->data['errormessage'] = 'Otra declaración con los mismos parametros inciales ya ha sido generada.';
                    return false;
                }
            }

            # Se agregan "-00" al final de los campos periodo para que guarde como fecha
            $insercion = [
                'id_empresa'                => $this->input->post('empresa'),
                'id_estampilla'             => $this->input->post('tipo_estampilla'),
                'periodo'                   => $this->input->post('periodo') . '-00',
                'tipo_declaracion'          => $this->input->post('tipo_declaracion'),
                'recaudado'                 => $this->input->post('recaudado'),
                'sanciones'                 => $this->input->post('sanciones'),
                'intereses'                 => $this->input->post('intereses'),
                'total_base'                => $this->input->post('total_base'),
                'total_estampillas'         => $this->input->post('total_estampillas'),
                'saldo_periodo_anterior'    => $this->input->post('saldo_periodo_anterior'),
                'sanciones_pago'            => $this->input->post('sanciones_pago'),
                'intereses_mora'            => $this->input->post('intereses_mora'),
                'total_cargo'               => $this->input->post('total_cargo'),
                'saldo_favor'               => $this->input->post('saldo_favor'),
                'fecha_creacion'            => date('Y-m-d H:i:s'),
            ];

            if($es_correccion)
            {
                $insercion['declaracion_correccion']    = $this->input->post('declaracion_correccion');
                $insercion['radicacion_correccion']     = $this->input->post('radicacion_correccion');
                $insercion['fecha_correccion']          = $this->input->post('fecha_correccion');
                $insercion['periodo_correccion']        = $this->input->post('periodo_correccion') . '-00';
            }

            $guardo = $this->codegen_model->add('declaraciones', $insercion);

            if($guardo->bandRegistroExitoso)
            {
                foreach(Equivalencias::clasificacionContratos() AS $id => $nombre)
                {
                    $insercion = [
                        'id_declaracion'    => $guardo->idInsercion,
                        'renglon'           => $id,
                        'base'              => $this->input->post('detalle_base')[$id],
                        'vigencia_actual'   => $this->input->post('detalle_vigencia_actual')[$id],
                        'vigencia_anterior' => $this->input->post('detalle_vigencia_anterior')[$id],
                        'porcentaje'        => $this->input->post('detalle_porcentaje')[$id],
                        'valor_estampilla'  => $this->input->post('detalle_pagado')[$id],
                    ];

                    $guardo_detalle = $this->codegen_model->add('detalles_declaracion', $insercion);
                }

                $this->session->set_flashdata('successmessage', 'Se registró correctamente la declaración.');

                return true;
            } else {
                $this->data['errormessage'] = 'Ocurrió un problema al generar la declaración por favor intente nuevamente.';
                return false;
            }
        }
    }

    private function generarPdf()
    {
        // $this->load->library('encrypt');
        // $hash = $_GET['id'];
        // $ids_pago = $this->encrypt->decode($hash, Equivalencias::generadorHash());

        // echo '$algo<pre>';var_dump( $hash, $ids_pago );echo '</pre>';

        $this->load->library('CustomPdf');

        $pdf = new CustomPdf();

        $html = $this->load->view('generarpdf/declaracion_estampilla', $this->data, TRUE);

        # Se agrega la paginacion
        $pdf->setFooter('{PAGENO}');
        $pdf->WriteHTML($html);
        ob_end_clean();

        $pdf->Output( 'Prueba', 'I');
        die();

        $this->load->library('Pdf');

        // create new PDF document
        $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('turrisystem');
        $pdf->SetTitle('Declaración de estampilla');
        $pdf->SetSubject('Gobernación del Putumayo');
        $pdf->SetKeywords('estampillas,gobernación');
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 2, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 2);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------

        // set font
        $pdf->SetFont('times', 'BI', 8);

        $pdf->AddPage();

        $this->data = array();

        $html = $this->load->view('generarpdf/declaracion_estampilla', $this->data, TRUE);
        $pdf->writeHTML($html, true, false, true, false, '');

        // ---------------------------------------------------------

        //Close and output PDF document
        $pdf->Output('recibos_estampilla.pdf', 'I');
    }

    /**
     * Verifica si el usuario esta asociado a una empresa o tiene control total
     * 
     * @return bool|int
     */
    private function verificarRestriccionEmpresa()
    {
        if($this->ion_auth->is_admin()) {
            return true;
        }

        $usuario = $this->codegen_model->get(
            'users',
            'id_empresa',
            'id = '. $this->session->userdata('user_id'),
            1,NULL,true
        );

        return $usuario->id_empresa;
    }
}
