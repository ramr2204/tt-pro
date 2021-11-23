<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
* Clase que ofrece metodos de ayuda en general
*/
Class HelperGeneral extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('codegen_model', '', true);
        $this->load->helper(['Equivalencias']);
    }

    /**
     * Funcion de apoyo agregada por Mike para crear un arreglo
     * a partir de una coleccion de objetos resultados de get_results
     *
     * @param array $arr
     * @param string $val
     * @param string $llave
     * @return array
    */
	public static function lists($arr = array(), $val = '', $llave = '')
    {
        $vectorResultado = array();
    
        if($val != '' || $llave != '')
        {
            foreach($arr as $objeto)
            {
                /*
                * Si solo llega el valor
                * cree el arreglo con solo el valor
                * sin indices
                */
                if($llave == '' && $val != '') {
                    $vectorResultado[] = $objeto->$val;
                } elseif($llave != '' && $val != '') {
                    /*
                    * Si llega la llave y valor
                    * construya un vector con indice y valor
                    */
                    $vectorResultado[$objeto->$llave] = $objeto->$val;
                } elseif ($llave != '' && $val == '') {
                    /*
                    * Si llega llave pero no valor,
                    * devuelva el mismo arreglo, con las llaves seleccionadas
                    */
                    $vectorResultado[$objeto->$llave] = $objeto;
                }
            }
            return $vectorResultado;
        }else
            {
                echo 'Debe Suministrar por lo menos el nombre del campo para extraer los valores del arreglo';exit();
            }
    }

    /**
     * Retorna la cantidad de rotulos disponibles para impresion para un liquidador
     */
    public function obtenerCantidadPapeleriaDisponibleUsuario($idUsuario, $esContingencia = 'NO')
    {
        $where = ' WHERE pape_usuario = ' . $idUsuario . ' AND pape_estadoContintencia = "' . $esContingencia . '"';
        $rangosPapelUsuario = $this->codegen_model
            ->getSelect('est_papeles',"pape_id,pape_codigoinicial,pape_codigofinal",$where);

        $cantPapelesDisponibles = 0;
        if(count($rangosPapelUsuario) > 0)
        {
            $idsRangosPapelUsuario = HelperGeneral::lists($rangosPapelUsuario,'pape_id');
            $where = ' WHERE impr_codigopapel != 0 AND impr_papelid IN (' . implode(',',$idsRangosPapelUsuario) .') ';
            $group = ' GROUP BY impr_papelid ';

            $cantidadesImpresas = $this->codegen_model
                ->getSelect('est_impresiones', "COUNT(*) AS contador, impr_papelid", $where ,'', $group);

            $vectorCantidadesImpresas = array();
            if(count($cantidadesImpresas) > 0)
            {
                $vectorCantidadesImpresas = HelperGeneral::lists($cantidadesImpresas, 'contador', 'impr_papelid');
            }

            foreach($rangosPapelUsuario as $objRangoPapel)
            {
                if(!array_key_exists($objRangoPapel->pape_id ,$vectorCantidadesImpresas))
                {
                    $vectorCantidadesImpresas[$objRangoPapel->pape_id] = 0;
                }

                $cantPapelRango = ((int)$objRangoPapel->pape_codigofinal - (int)$objRangoPapel->pape_codigoinicial) + 1;

                if($cantPapelRango > 0)
                {
                    $cantPapelesDisponibles += (int)$cantPapelRango - 
                        (int)$vectorCantidadesImpresas[$objRangoPapel->pape_id];
                }
            }
        }

		return $cantPapelesDisponibles;
    }

    /**
     * Retorna la cantidad de rotulos anulados pendientes por verificacion
     */
    public function obtenerCantidadRotulosAnuladosNoVerificados()
    {
        $where = ' WHERE impr_estado != 1 AND anulacion_verificada = 0 AND impr_codigopapel != 0 ';
        $rotulosAnuladosNoVerificados = $this->codegen_model
            ->getSelect('est_impresiones',"count(*) as cantidad ",$where);

		return $rotulosAnuladosNoVerificados[0]->cantidad;
    }

    /**
     * Retorna la cantidad minima de rotulos disponibles
     * establecidos para usuarios en general
     */
    public function obtenerCantidadMinimaRotulosUsuario()
    {
        $objParametros = $this->codegen_model->get('adm_parametros', 'para_rotulosminimosusuario',
            'para_id = 1', 1, null, true);

        $cantidadMinimaRotulos = 0;
        if($objParametros)
        {
            $cantidadMinimaRotulos = $objParametros->para_rotulosminimosusuario;
        }

        return $cantidadMinimaRotulos;
    }

    /**
     * Retorna un string que indica si estan activados o no
     * para impresion los rotulos de contingencia
     */
    public function estanActivosRotulosContingencia()
    {
        $objParametros = $this->codegen_model->get('adm_parametros', 'para_contingencia', 'para_id = 1', 1, null, true);

        $contingencia = 'NO';
        if($objParametros->para_contingencia == 1)
        {
            $contingencia = 'SI';
        }

        return $contingencia;
    }

    /**
     * Retorna un array con informacion acerca de la alerta para rotulos
     * disponibles para un usuario
     */
    public function obtenerInformacionAlertaRotulosMinimosUsuario($idUsuario)
    {
        $informacionAlerta = array(
            'mostrarAlerta'   => false,
            'noMostrarAlerta' => true,
            'cantidadRotulosDisponiblesUsuario' => 0
        );

        $cantidadMinimaRotulos     = $this->obtenerCantidadMinimaRotulosUsuario();
        $estadoRotulosContingencia = $this->estanActivosRotulosContingencia();
        $cantidadRotulosDisponiblesUsuario = $this->obtenerCantidadPapeleriaDisponibleUsuario($idUsuario, 
            $estadoRotulosContingencia);
        
        $informacionAlerta['cantidadRotulosDisponiblesUsuario'] = $cantidadRotulosDisponiblesUsuario;
        if((int)$cantidadRotulosDisponiblesUsuario <= $cantidadMinimaRotulos)
        {
            $informacionAlerta['mostrarAlerta'] = true;
            $informacionAlerta['noMostrarAlerta'] = false;
        }

        return $informacionAlerta;
    }

    /**
     * Retorna un array con informacion acerca de la alerta para rotulos
     * disponibles para el usuario autenticado en el sistema
     */
    public function solicitarInformacionAlertaRotulosMinimosUsuarioAutenticado()
    {
        $informacionAlerta = array(
            'mostrarAlerta' => false,
            'noMostrarAlerta' => true,
            'cantidadRotulosDisponiblesUsuario' => 0
        );

        /*
        * Verifica que el usuario autenticado tenga perfil liquidador
        */
        $usuarioLogueado = $this->ion_auth->user()->row();
        if($usuarioLogueado->perfilid == 4)
        {
            $informacionAlerta = $this->obtenerInformacionAlertaRotulosMinimosUsuario($usuarioLogueado->id);
        }

        return $informacionAlerta;
    }

    /**
     * Retorna un array con informacion acerca de la alerta para rotulos
     * anulados sin verificar
     */
    public function solicitarInformacionAlertaRotulosAnuladosSinVerificar()
    {
        $informacionAlerta = array(
            'mostrarAlerta' => false,
            'noMostrarAlerta' => true,
            'cantidadRotulosAnulados' => 0
        );

        $cantidadRotulos = $this->obtenerCantidadRotulosAnuladosNoVerificados();
        if($cantidadRotulos > 0)
        {
            $informacionAlerta['mostrarAlerta'] = true;
            $informacionAlerta['noMostrarAlerta'] = false;
            $informacionAlerta['cantidadRotulosAnulados'] = $cantidadRotulos;
        }

        return $informacionAlerta;
    }

    /**
     * Verifica si el usuario esta asociado a una empresa o tiene control total
     * 
     * @return bool|int
    */
    public function verificarRestriccionEmpresa()
    {
        if($this->ion_auth->is_admin()) {
            return true;
        }

        $usuario = $this->ion_auth->user()->row();

        return $usuario->id_empresa;
    }

    /**
     * Verifica el mensaje a mostrar para las empresas
     * (posible proximo vencimiento para reportar declaraciones)
     * 
     * @return string
    */
    public function verificarVencimientoEmpresa()
    {
        $mensaje = '';

        if( $this->ion_auth->logged_in() && $this->ion_auth->in_menu('declaraciones/add') )
        {
            $usuario = $this->ion_auth->user()->row();

            if($usuario->id_empresa)
            {
                # Se verifica si ya se presento la declaracion sobre el mes vencido
                $declaracion = $this->codegen_model->get(
                    'declaraciones',
                    'id',
                    'DATE_FORMAT(periodo, "%Y-%m") = "'. date('Y-m', strtotime('-1 months')) .'"
                        AND tipo_declaracion = '. Equivalencias::declaracionInicial(),
                    1,NULL,true
                );

                if(!isset($declaracion->id))
                {
                    $empresa = $this->codegen_model->get(
                        'con_contratantes',
                        'nit',
                        'id = '.$usuario->id_empresa,
                        1,NULL,true
                    );
    
                    $vencimiento = $this->codegen_model->get(
                        'vencimiento_declaraciones',
                        'dia',
                        'ultimo_digito = "'. substr($empresa->nit, -1) .'"',
                        1,NULL,true
                    );

                    $diferencia_dias = date_diff(
                        date_create(date('Y-m-d')),
                        date_create( date('Y-m-') . $vencimiento->dia )
                    )->format('%R%a');

                    if($diferencia_dias >= 0 && $diferencia_dias <= 3) {
                        $mensaje = 'Atención, cuenta con '. ((int)$diferencia_dias) .' día(s) para la presentación del informe, del caso contrario se cobrarán sanciones.';
                    }
                }

            }
        }

        return $mensaje;
    }
}
