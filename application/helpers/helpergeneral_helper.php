<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
* Clase que ofrece metodos de ayuda en general
*/
Class HelperGeneral
{
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
}