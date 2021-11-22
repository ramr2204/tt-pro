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
        $this->load->helper(['Equivalencias', 'EquivalenciasFirmas']);
        $this->load->helper('HelperGeneral');

        setlocale(LC_TIME, 'es_CO');

        # Se toma como base las clasificaciones de los contratos pero se le agregan "Adiciones" por que se calcula de otra forma
        $this->tipos_detalles = Equivalencias::clasificacionContratos();
        $this->tipos_detalles[3] = 'Adiciones';
    }

    /**
     * Lista todas las declaraciones
     * 
     * @return null
     */
    public function index()
    {
    	if (!$this->ion_auth->logged_in())
		{
			//redirect them to the login page
			redirect('users/login', 'refresh');
		}
		elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/index'))) //remove this elseif if you want to enable this for non-admins
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
            $this->template->set('title', 'Administrar declaraciones');
            $this->data['style_sheets'] = [
                'css/plugins/bootstrap/fileinput.css' => 'screen',
                'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
            ];
            $this->data['javascripts'] = [
                'js/plugins/bootstrap/fileinput.min.js',
                'js/jquery.dataTables.min.js',
                'js/plugins/dataTables/dataTables.bootstrap.js',
                'js/jquery.dataTables.defaults.js',
                'js/sweetalert.min.js',
            ];

            $this->data['meses'] = $this->obtenerMeses();
            $this->data['tipos_declaraciones'] = Equivalencias::tipoDeclaraciones();

            $this->data['firma'] = $this->codegen_model->get(
                'usuarios_firma',
                'id, change_password',
                'id_usuario = '. $this->session->userdata('user_id').'
                    AND estado = '. Equivalencias::estadoActivo(),
                1,NULL,true
            );

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
        $meses = [];

        for($mes = 1; $mes <= 12; $mes++){
            $meses[$mes] = $mes_corto ? strftime('%b', mktime(0, 0, 0, $mes)) : strftime('%B', mktime(0, 0, 0, $mes));
        }

        return $meses;
    }

    /**
     * Retorna datos para el procesamiento del dataTable
     * 
     * @return null
     */
    public function dataTable()
    {
        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/index'))
        {
            $this->load->library('datatables');
            $this->datatables->select('d.id, empresa.nombre AS empresa, estampilla.estm_nombre AS estampilla,
                d.periodo, d.tipo_declaracion, d.fecha_creacion, d.estado, d.soporte');
            $this->datatables->from('declaraciones AS d');
            $this->datatables->join('con_contratantes empresa','empresa.id = d.id_empresa','inner');
            $this->datatables->join('est_estampillas estampilla','estampilla.estm_id = d.id_estampilla','inner');

            $helper = new HelperGeneral;
            $verificacion = $helper->verificarRestriccionEmpresa();

            if($verificacion !== true) {
                $this->datatables->where('d.id_empresa = "'. $verificacion .'"');
            }

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
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/add'))
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

                $this->template->set('title', 'Ingreso de declaraciones');

                $this->data['style_sheets'] = [
                    'css/chosen.css' => 'screen',
                    'css/plugins/bootstrap/bootstrap-datetimepicker.css' => 'screen',
                    'css/plugins/bootstrap/fileinput.css' => 'screen',
                ];
                $this->data['javascripts'] = [
                    'js/chosen.jquery.min.js',
                    'js/plugins/bootstrap/moment.js',
                    'js/plugins/bootstrap/bootstrap-datetimepicker.js',
                    'js/plugins/bootstrap/fileinput.min.js',
                    'js/autoNumeric.js',
                ];

                $this->data['empresas'] = $this->codegen_model->getSelect(
                    'con_contratantes',
                    'id, nombre',
                    '', '', '',
                    'ORDER BY nombre'
                );
                $this->data['estampillas'] = $this->codegen_model->getSelect('est_estampillas', 'estm_id AS id, estm_nombre AS nombre');

                $this->data['clasificaciones_contratos'] = $this->tipos_detalles;

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
        $this->form_validation->set_rules('empresa', 'Empresa','required|trim|xss_clean|is_exists[con_contratantes.id]');
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
            'pagos_estampillas AS pagos',
            'contrato.clasificacion,
                SUM(cuota.valor) as base,
                SUM(pagos.valor) as pagado,
                factura.fact_porcentaje AS porcentaje',
            'WHERE factura.fact_estampillaid = '. $this->input->post('tipo_estampilla') .'
                AND DATE_FORMAT(pagos.fecha, "%Y-%m") = "'. $this->input->post('periodo') .'"
                AND contrato.cntr_contratanteid = '. $this->input->post('empresa'),
            'INNER JOIN est_facturas factura ON factura.fact_id = pagos.factura_id
                INNER JOIN cuotas_liquidacion cuota ON (
                    cuota.id = factura.id_cuota_liquidacion
                    AND cuota.estado = '. Equivalencias::cuotaPaga() .'
                    AND cuota.tipo = '. Equivalencias::cuotaNormal() .'
                )
                INNER JOIN est_liquidaciones liquidacion ON liquidacion.liqu_id = factura.fact_liquidacionid
                INNER JOIN con_contratos contrato ON contrato.cntr_id = liquidacion.liqu_contratoid',
            'GROUP BY contrato.clasificacion'
        );
        $pagos = HelperGeneral::lists($pagos, '', 'clasificacion');

        if(count($pagos) > 0)
        {
            foreach(Equivalencias::clasificacionContratos() AS $id => $nombre)
            {
                if( isset($pagos[$id]) ) {
                    $pagos[$id]->clase = $nombre;
                    $consulta[$id] = $pagos[$id];
                } else {
                    $consulta[$id] = (object)[
                        'clasificacion' => $id,
                        'clase'         => $nombre,
                        'base'          => 0,
                        'pagado'        => 0,
                        'porcentaje'    => 0,
                    ];
                }
            }
        }

        $adiciones = $this->codegen_model->getSelect(
            'pagos_estampillas AS pagos',
            'SUM(cuota.valor) as base,
                SUM(pagos.valor) as pagado,
                factura.fact_porcentaje AS porcentaje',
            'WHERE factura.fact_estampillaid = '. $this->input->post('tipo_estampilla') .'
                AND DATE_FORMAT(pagos.fecha, "%Y-%m") = "'. $this->input->post('periodo') .'"
                AND contrato.cntr_contratanteid = '. $this->input->post('empresa'),
            'INNER JOIN est_facturas factura ON factura.fact_id = pagos.factura_id
                INNER JOIN cuotas_liquidacion cuota ON (
                    cuota.id = factura.id_cuota_liquidacion
                    AND cuota.estado = '. Equivalencias::cuotaPaga() .'
                    AND cuota.tipo = '. Equivalencias::cuotaAdicion() .'
                )
                INNER JOIN est_liquidaciones liquidacion ON liquidacion.liqu_id = factura.fact_liquidacionid
                INNER JOIN con_contratos contrato ON contrato.cntr_id = liquidacion.liqu_contratoid'
        );

        if(count($adiciones) > 0)
        {
            $adiciones = $adiciones[0];

            $consulta[3] = (object)[
                'clasificacion' => 3,
                'clase'         => 'Adiciones',
                'base'          => $adiciones->base,
                'pagado'        => $adiciones->pagado,
                'porcentaje'    => $adiciones->porcentaje,
            ];
        }

        if(count($consulta) > 0)
        {
            # Se ordenan los detalles por el indice
            ksort($consulta);

            $estampilla = $this->codegen_model->getSelect(
                'est_estampillas',
                'estm_nombre AS nombre',
                'WHERE estm_id = '.$this->input->post('tipo_estampilla')
            );

            $this->data['consulta'] = $consulta;
            $this->data['estampilla'] = $estampilla[0];
        }
        else {
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

        $this->form_validation->set_rules('empresa', 'Empresa','required|trim|xss_clean|is_exists[con_contratantes.id]');
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

        foreach($this->tipos_detalles AS $id => $nombre)
        {
            $this->form_validation->set_rules('detalle_vigencia_actual['. $id .']', 'Vigencia actual '. $id, 'required|trim|xss_clean|numeric');
            $this->form_validation->set_rules('detalle_vigencia_anterior['. $id .']', 'Vigencia anterior '. $id, 'required|trim|xss_clean|numeric');
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
                        AND DATE_FORMAT(periodo, "%Y-%m") = "'. $this->input->post('periodo') .'"
                        AND tipo_declaracion != "'. Equivalencias::declaracionCorreccion() .'"');
                
                if($validacion->contador > 0) {
                    $this->data['errormessage'] = 'Otra declaración con los mismos parametros inciales ya ha sido generada.';
                    return false;
                }
            }

            # Se agregan "-01" al final de los campos periodo para que guarde como fecha
            $insercion = [
                'id_empresa'                => $this->input->post('empresa'),
                'id_estampilla'             => $this->input->post('tipo_estampilla'),
                'periodo'                   => $this->input->post('periodo') . '-01',
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
                'creado_por'                => $this->session->userdata('user_id'),
            ];

            if($es_correccion)
            {
                $insercion['declaracion_correccion']    = $this->input->post('declaracion_correccion');
                $insercion['radicacion_correccion']     = $this->input->post('radicacion_correccion');
                $insercion['fecha_correccion']          = $this->input->post('fecha_correccion');
                $insercion['periodo_correccion']        = $this->input->post('periodo_correccion') . '-01';
            }

            $guardo = $this->codegen_model->add('declaraciones', $insercion);

            if($guardo->bandRegistroExitoso)
            {
                foreach($this->tipos_detalles AS $id => $nombre)
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

    /**
     * Genera el pdf de la declaracion firmada
     * 
     * @param int $id_declaracion
     * @param array $datos_firma
     * @param array $info
     * @return string
     */
    public function generarPdf($id_declaracion, $datos_firma, $info)
    {
        $this->load->library('CustomPdf');
        $pdf = new CustomPdf();

        $declaracion = $this->codegen_model->get(
            'declaraciones AS d',
            'd.id_empresa, e.estm_nombre AS estampilla, d.periodo,
                d.tipo_declaracion, d.declaracion_correccion, d.radicacion_correccion,
                d.fecha_correccion, d.periodo_correccion, d.recaudado,
                d.sanciones, d.intereses, d.total_base,
                d.total_estampillas, d.saldo_periodo_anterior, d.sanciones_pago,
                d.intereses_mora, d.total_cargo, d.saldo_favor,
                d.id, d.fecha_creacion AS fecha, e.estm_rutaimagen AS imagen_estampilla,
                d.id_estampilla, d.creado_por',
            'd.id = "'. $id_declaracion .'"',
            1,NULL,true, '',
            'est_estampillas e', 'e.estm_id = d.id_estampilla'
        );

        $this->data['empresa'] = $this->codegen_model->get(
            'con_contratantes AS e',
            'e.nit, e.nombre, e.email,
                e.direccion, e.telefono, m.muni_nombre AS municipio',
            'e.id = "'. $declaracion->id_empresa .'"',
            1,NULL,true, '',
            'par_municipios m', 'm.muni_id = e.municipioid'
        );

        $this->data['detalles'] = $this->codegen_model->getSelect(
            'detalles_declaracion',
            'renglon, base, vigencia_actual,
                vigencia_anterior, porcentaje, valor_estampilla',
            'WHERE id_declaracion = "'. $id_declaracion .'"',
            '',
            '',
            'ORDER BY renglon'
        );

        $this->data['pagos'] = $this->consultarDetalles($declaracion);

        $this->data['funcionario'] = $this->codegen_model->get(
            'users',
            'first_name, last_name',
            'id = "'. $declaracion->creado_por .'"',
            1,NULL,true
        );

        $this->data['declaracion'] = $declaracion;
        $this->data['firmas'] = $info['info'] ? $info['info'] : [];

        $representante = array_filter($this->data['firmas'], function($firma) {
            return $firma['tipo_usuario'] == EquivalenciasFirmas::usuarioRepresentante();
        });
        $this->data['representante'] = end($representante);

        $this->data['tipo_correccion'] = Equivalencias::declaracionCorreccion();
        $this->data['meses'] = $this->obtenerMeses(true);
        $this->data['clasificaciones'] = $this->tipos_detalles;

        $this->data['formatear_valor'] = function($numero) {
            return '$'.number_format($numero, 2, ',', '.');
        };

        $html = $this->load->view('generarpdf/declaracion_estampilla', $this->data, TRUE);

        # Se agrega la paginacion
        $pdf->setFooter('{PAGENO}');
        $pdf->WriteHTML($html);

        $pdf->setPathServer('uploads/declaraciones');
        $pdf->setFileName('comprobante_declaracion_'. $id_declaracion .'.pdf');

        $pdf->addXMP($datos_firma);

        return $pdf->generar();
    }

    /**
     * Genera la vista de los detalles de la declaracion
     * 
     * @return null
     */
    public function detalles()
    {
        //redirect them to the login page
        if (!$this->ion_auth->logged_in()) {
			redirect('users/login', 'refresh');
		}
		elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/detalles'))) {
			redirect('error_404', 'refresh');
		}
        elseif ($this->uri->segment(3)==''){
            redirect(base_url().'index.php/error_404');
        }

        $this->data['id_declaracion'] = $this->uri->segment(3);

        $this->template->set('title', 'Detalle de declaracion');
        $this->data['style_sheets'] = [
            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
        ];
        $this->data['javascripts'] = [
            'js/jquery.dataTables.min.js',
            'js/plugins/dataTables/dataTables.bootstrap.js',
            'js/jquery.dataTables.defaults.js',
            'js/accounting.min.js',
        ];

        $this->template->load($this->config->item('admin_template'),'declaraciones/detalles', $this->data);
    }

    /**
     * Consulta los datos del datatable de los detalles
     * 
     * @return null
     */
    public function detallesDatatable()
    {
        //redirect them to the login page
        if (!$this->ion_auth->logged_in()) {
			redirect('users/login', 'refresh');
		}
		elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/detalles'))) {
			redirect('error_404', 'refresh');
		}
        elseif (!isset($_GET['id_declaracion'])){
            redirect(base_url().'index.php/error_404');
        }

        $this->load->library('datatables');

        $declaracion = $this->codegen_model->get(
            'declaraciones AS d',
            'd.id_estampilla, d.periodo, d.id_empresa',
            'd.id = "'. $_GET['id_declaracion'] .'"',
            1,NULL,true, '',
            'est_estampillas e', 'e.estm_id = d.id_estampilla'
        );

        $this->datatables->select('contratista.cont_nombre AS nombre_contratista,
            contratista.cont_nit AS nit_contratista,
            pagos.fecha,
            liquidacion.liqu_valorsiniva AS valor_contrato,
            cuota.valor as base_pago,
            pagos.valor as pagado,
            contrato.cntr_numero AS contrato,
            pagos.id AS pago,
            factura.fact_id AS factura');

        $this->datatables->from('pagos_estampillas AS pagos');
        $this->datatables->join('est_facturas factura','factura.fact_id = pagos.factura_id','inner');
        $this->datatables->join('est_liquidaciones liquidacion', 'liquidacion.liqu_id = factura.fact_liquidacionid', 'inner');
        $this->datatables->join('cuotas_liquidacion cuota', 'cuota.id = factura.id_cuota_liquidacion', 'inner');
        $this->datatables->join('con_contratos contrato', 'contrato.cntr_id = liquidacion.liqu_contratoid', 'inner');
        $this->datatables->join('con_contratistas contratista', 'contratista.cont_id = contrato.cntr_contratistaid', 'left');

        $this->datatables->where('factura.fact_estampillaid = '. $declaracion->id_estampilla);
        $this->datatables->where('DATE_FORMAT(pagos.fecha, "%Y-%m") = "'. date('Y-m', strtotime($declaracion->periodo)) .'"');
        $this->datatables->where('contrato.cntr_contratanteid = '. $declaracion->id_empresa);

        $helper = new HelperGeneral;
        $verificacion = $helper->verificarRestriccionEmpresa();

        # Valida si esta requerido la empresa genere una consulta vacia
        if($verificacion !== true && $verificacion != $declaracion->id_empresa) {
            $this->datatables->where('0 != 1');
        }

        echo $this->datatables->generate();
    }

    /**
     * Conulta los pagos relacionados a una declaracion
     * 
     * @param object $declaracion
     * @return array
     */
    private function consultarDetalles($declaracion)
    {
        return $this->codegen_model->getSelect(
            'pagos_estampillas AS pagos',
            'contratista.cont_nombre AS nombre_contratista,
                contratista.cont_nit AS nit_contratista,
                pagos.fecha,
                contrato.cntr_numero AS contrato,
                pagos.id AS pago,
                factura.fact_id AS factura,
                liquidacion.liqu_valorsiniva AS valor_contrato,
                cuota.valor as base_pago,
                pagos.valor as pagado',
            'WHERE factura.fact_estampillaid = '. $declaracion->id_estampilla .'
                AND DATE_FORMAT(pagos.fecha, "%Y-%m") = "'. date('Y-m', strtotime($declaracion->periodo)) .'"
                AND contrato.cntr_contratanteid = '. $declaracion->id_empresa,
            'INNER JOIN est_facturas factura ON factura.fact_id = pagos.factura_id
                INNER JOIN est_liquidaciones liquidacion ON liquidacion.liqu_id = factura.fact_liquidacionid
                INNER JOIN cuotas_liquidacion cuota ON cuota.id = factura.id_cuota_liquidacion
                INNER JOIN con_contratos contrato ON contrato.cntr_id = liquidacion.liqu_contratoid
                LEFT JOIN con_contratistas contratista ON contratista.cont_id = contrato.cntr_contratistaid',
            '', 'ORDER BY pagos.fecha DESC'
        );
    }

    /**
     * Crea el excel de los detalles de las declaraciones
     * 
     * @return null
     */
    public function detallesExcel()
    {
        //redirect them to the login page
        if (!$this->ion_auth->logged_in()) {
			redirect('users/login', 'refresh');
		}
		elseif (!($this->ion_auth->is_admin() || $this->ion_auth->in_menu('declaraciones/detalles'))) {
			redirect('error_404', 'refresh');
		}
        elseif ($this->uri->segment(3)=='') {
            redirect(base_url().'index.php/error_404');
        }

        $declaracion = $this->codegen_model->get(
            'declaraciones AS d',
            'd.id_estampilla, d.periodo, d.id_empresa',
            'd.id = "'. $this->uri->segment(3) .'"',
            1,NULL,true, '',
            'est_estampillas e', 'e.estm_id = d.id_estampilla'
        );

        $this->data['detalles'] = $this->consultarDetalles($declaracion);

        $this->data['formatear_valor'] = function($numero) {
            return '$'.number_format($numero, 2, ',', '.');
        };

        $_SESSION['fecha_informe_excel'] = 'detalles declaraciones';

        $this->template->load($this->config->item('excel_template'),'declaraciones/excel', $this->data);
    }

    /**
     * Adjunta el soporte de pago
     * 
     * @return null
     */
    public function cargarPago()
    {
        $this->form_validation->set_rules('declaracion', 'Identificador de la declaración','required|trim|xss_clean|is_exists[declaraciones.id]');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('errormessage', (validation_errors() ? validation_errors() : false));
        } else {

            # Cargue del anexo
            $ruta_soporte = '';

            if (!isset($_FILES['upload_field_name']) && !is_uploaded_file($_FILES['soporte_pago']['tmp_name'])) 
            {
                $this->session->set_flashdata('errormessage', '<strong>Error!</strong> Debe cargar el soporte del pago.');
            }
            else
            {
                $path = 'uploads/anexosDeclaraciones';
                if(!is_dir($path)) { //crea la carpeta para los objetos si no existe
                    mkdir($path,0777,TRUE);
                }
                $config['upload_path'] = $path;
                $config['allowed_types'] = 'jpg|jpeg|gif|png|tif|pdf';
                $config['remove_spaces']=TRUE;
                $config['max_size']    = '99999';
                $config['overwrite']    = TRUE;
                $this->load->library('upload');

                $config['file_name'] = 'anexo_'.time();
                $this->upload->initialize($config);

                //Valida si se carga correctamente el soporte
                if ($this->upload->do_upload('soporte_pago'))
                {
                    /*
                    * Establece la informacion para actualizar la liquidacion
                    * en este caso la ruta de la copia del objeto del contrato
                    */
                    $file_datos= $this->upload->data();
                    $ruta_soporte = $path.'/'.$file_datos['orig_name'];
                }
                else {
                    $this->session->set_flashdata('errormessage', '<strong>Error!</strong> '.$this->upload->display_errors());
                }
            }

            if($ruta_soporte)
            {
                $registros_afectados = $this->codegen_model->edit(
                    'declaraciones',
                    [
                        'soporte' => $ruta_soporte,
                        'estado' => EquivalenciasFirmas::declaracionPagada(),
                    ],
                    'id', $this->input->post('declaracion'),
                    true
                );
    
                if($registros_afectados > 0) {
                    $this->session->set_flashdata('successmessage', 'El soporte se cargo correctamente.');
                } else {
                    $this->session->set_flashdata('errormessage', '<strong>Error!</strong> No se pudo cargar el pago correctamente.');
                }
            }
        }

        redirect(base_url().'index.php/declaraciones/index');
    }
}
