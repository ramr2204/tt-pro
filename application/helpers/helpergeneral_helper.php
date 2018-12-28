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
    }

	public function lists($arr = array(), $val = '', $llave = '')
    {
        $vectorResultado = array();
    
        if($val != '')
        {
            foreach($arr as $objeto)
            {
                /*
                * Si solo llega el valor
                * cree el arreglo con solo el valor
                * sin indices
                */
                if($llave == '' && $val != '')
                {
                    $vectorResultado[] = $objeto->$val;
                }elseif($llave != '' && $val != '')
                    {
                        /*
                        * Si llega la llave y valor
                        * construya un vector con indice y valor
                        */
                        $vectorResultado[$objeto->$llave] = $objeto->$val;
                    }
            }
            return $vectorResultado;
        }else
            {
                echo 'Debe Suministrar por lo menos el nombre del campo para extraer los valores del arreglo';exit();
            }
    }

    public function obtenerCantidadPapeleriaDisponibleUsuario($idUsuario, $esContingencia = 'NO')
    {
        $where = ' WHERE pape_usuario = ' . $idUsuario . ' AND pape_estadoContintencia = "' . $esContingencia . '"';
        $rangosPapelUsuario = $this->codegen_model
            ->getSelect('est_papeles',"pape_id,pape_codigoinicial,pape_codigofinal",$where);

        $cantPapelesDisponibles = 0;
        if(count($rangosPapelUsuario) > 0)
        {
            $idsRangosPapelUsuario = $this->lists($rangosPapelUsuario,'pape_id');
            $where = ' WHERE impr_codigopapel != 0 AND impr_papelid IN (' . implode(',',$idsRangosPapelUsuario) .') ';
            $group = ' GROUP BY impr_papelid ';

            $cantidadesImpresas = $this->codegen_model
                ->getSelect('est_impresiones', "COUNT(*) AS contador, impr_papelid", $where ,'', $group);

            $vectorCantidadesImpresas = array();
            if(count($cantidadesImpresas) > 0)
            {
                $vectorCantidadesImpresas = $this->lists($cantidadesImpresas, 'contador', 'impr_papelid');
            }

            foreach($rangosPapelUsuario as $objRangoPapel)
            {
                $cantPapelRango = ((int)$objRangoPapel->pape_codigofinal - (int)$objRangoPapel->pape_codigoinicial) + 1;
                $cantPapelesDisponibles += (int)$cantPapelRango - (int)$vectorCantidadesImpresas[$objRangoPapel->pape_id];
            }
        }

		echo'<pre>';print_r($cantPapelesDisponibles);echo'</pre>';exit();
    }
}