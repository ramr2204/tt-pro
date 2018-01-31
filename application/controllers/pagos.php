<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            pagos
*   Ruta:              /application/controllers/pagos.php
*   Descripcion:       controlador de pagos
*   Fecha Creacion:    20/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-20
*
*/

class Pagos extends MY_Controller {
    
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

	function manage()
  {
      if ($this->ion_auth->logged_in()){

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pagos/manage')){

              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
              //template data
              $this->template->set('title', 'Administrar pagos');
              $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js'
                       );
            
              $this->template->load($this->config->item('admin_template'),'pagos/pagos_list', $this->data);

          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
              redirect(base_url().'index.php/users/login');
      }

  }
	
  function add()
  {        
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pagos/add')) {            

              $this->data['successmessage'] = $this->session->flashdata('successmessage');
              $this->data['errormessage'] = $this->session->flashdata('errormessage');
              $this->data['infomessage'] = $this->session->flashdata('infomessage');
              $this->data['warnigmessage'] = $this->session->flashdata('warnigmessage');

              /*
              * Valida si están seteadas las variables de sesion
              * alertas o errores para para asignarlas al vector data
              */
              session_start();
              if(isset($_SESSION['errores']) && $_SESSION['errores'] != '')
              {
                  $this->data['errormessage'] .= $_SESSION['errores'];
                  /*
                  * Se limpia la variable de sesion
                  */
                  $_SESSION['errores'] = '';
              }

              if(isset($_SESSION['alertas']) && $_SESSION['alertas'] != '')
              {
                  $this->data['warnigmessage'] .= $_SESSION['alertas'];
                  /*
                  * Se limpia la variable de sesion
                  */
                  $_SESSION['alertas'] = '';
              }
              
              //carga las librerias para los estilos
              //y funcionalidad del boton de carga de 
              //archivos              
              $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen',
                        'css/plugins/bootstrap/bootstrap-datetimepicker.css' => 'screen',
                        'css/plugins/bootstrap/fileinput.css' => 'screen',
                        'css/animate.css' => 'screen',
                        'css/applicationStyles.css' => 'screen'
                    );
              $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js',
                        'js/plugins/bootstrap/moment.js',
                        'js/plugins/bootstrap/bootstrap-datetimepicker.js',
                        'js/plugins/bootstrap/fileinput.min.js'
                    );    

              /*
              * Consulta los bancos para renderizar
              */
              $bancos = $this->codegen_model->getSelect('par_bancos',"banc_id,banc_nombre");
              $vectorBancos = array();
              foreach ($bancos as $banco) 
              {
                  $vectorBancos[$banco->banc_id] = $banco->banc_nombre;
              }              

              /*
              * Valida si hay bancos para cargar el archivo de conciliacion
              * de pagos
              */
              if(count($vectorBancos) < 1)
              {
                  $this->session->set_flashdata('errormessage', 'No hay Bancos Registrados para Realizar la Conciliacion!');
                  redirect(base_url().'index.php/liquidaciones/liquidar');
              }

              $this->data['bancos'] = $vectorBancos;

              $this->template->set('title', 'Cargar archivo de pagos');
              $this->template->load($this->config->item('admin_template'),'pagos/pagos_add', $this->data);
             
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  }	

    function doadd()
    {
        if ($this->ion_auth->logged_in()) 
        {
           if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pagos/add')) 
           {
                $fecha = $this->input->post('f_conciliacion');
                $banco = $this->input->post('bancoid');

                /*
                * Valida que la fecha no llegue vacia
                */
                if($fecha != '')
                {
                    /*
                    * Valida que el banco no llegue vacio
                    */
                    if($banco == 0)
                    {
                        $this->session->set_flashdata('errormessage', 'Debe Seleccionar un Banco para Realizar la Conciliación!');
                        redirect(base_url().'index.php/pagos/add');
                    }
                }else
                    {
                        $this->session->set_flashdata('errormessage', 'Debe Seleccionar una Fecha para Realizar la Conciliación!');
                        redirect(base_url().'index.php/pagos/add');
                    }
               
                $path = 'uploads/pagos/'.date('d-m-Y');
                if(!is_dir($path)) 
                { //create the folder if this does not exists
                   mkdir($path,0777,TRUE);      
                }
                
                $config['upload_path'] = $path;
                $config['allowed_types'] = 'txt|dat|';
                $config['remove_spaces']=TRUE;
                $config['max_size']    = '2048';
                $config['overwrite']    = TRUE;                            

                $this->load->library('upload');
                $this->upload->initialize($config);  

                if ($this->upload->do_upload("archivo")) 
                {         
                    $file_data= $this->upload->data();                   

                    $path2 = $path."/".$file_data['raw_name'].".txt";
                    $string = file_get_contents($path2);
                    $file = fopen($path2,"r");

                    /*
                    * Se crea un vector para almacenar los datos
                    * de los distintos pagos
                    */
                    $vectorPagos = array();
                    while(!feof($file)) 
                    {
                        $linea = fgets($file);
                        
                        /*
                        * Valida con la expresion regular si la linea
                        * inicia con 06, lo que indica que es un registro de pagos
                        */
                        $patron = '/^06.*/';                       

                        if(preg_match($patron, $linea))
                        {
                            /*
                            * Se extrae el id de la factura según las posiciones
                            * en la cadena
                            */
                            $idFactura = substr($linea,36,10);

                            /*
                            * Se eliminan los 0 de relleno de la izquierda
                            */
                            $idFactura = ltrim($idFactura, '0');

                            /*
                            * Se extrae el valor del pago según las posiciones
                            * en la cadena
                            */
                            $valorPago = substr($linea,51,12);

                            /*
                            * Se eliminan los 0 de relleno de la izquierda
                            */
                            $valorPago = ltrim($valorPago, '0');

                            /*
                            * Se registra el pago en el vector
                            */
                            $vectorPagos[$idFactura] = $valorPago;
                        }
                    }

                    /*
                    * Se cierra el archivo
                    */
                    fclose($file);

                    /*
                    * Valida si hubo por lo menos un pago en el archivo                    
                    */
                    if(count($vectorPagos) > 0)
                    {
                        //cantidad conciliaciones correctas
                        //e incorrectas
                        $cantCorrectas = 0;
                        $cantIncorrectas = 0;
                        $cantAlertas = 0;
                        $mensajeAlerta = '';
                        $mensajeError = '';
                        foreach($vectorPagos as $factura => $valor)
                        {
                            $alertaLinea = '';
                            $errorLinea = '';
                            /*
                            * Valida que exista una factura para el identificador
                            */
                            $where = 'WHERE fact_id = '.$factura;
                            $vFactura = $this->codegen_model->getSelect('est_facturas',"fact_id", $where);
                            if(count($vFactura) > 0)
                            {
                                /*
                                * Valida si ya se creó un pago para la factura
                                */
                                $where = 'WHERE pago_facturaid = '.$factura;
                                $vPago = $this->codegen_model->getSelect('est_pagos',"pago_id , pago_valor, pago_valorconciliacion", $where);

                                if(count($vPago) > 0)
                                {
                                    /*
                                    * Valida que el pago no tenga conciliacion registrada
                                    */
                                    if($vPago[0]->pago_valorconciliacion != null)
                                    {
                                        /*
                                        * Se cuenta un error en conciliacion
                                        */
                                        $cantIncorrectas++;

                                        /*
                                        * Se especifica el error
                                        */
                                        $errorLinea .= 'Error:<br>La Factura con Id ('.$factura.') ya Ha sido Conciliada.';
                                    }else
                                        {
                                            /*
                                            * Se solicita el calculo de valores para conciliacion con el objeto
                                            * de pago existente
                                            */                                                                                           
                                            $data = $this->calcularDatosConciliacion($fecha, $banco, $valor, $vPago);

                                            /*
                                            * Valida el estado de la conciliacion para crear o no Alerta
                                            */
                                            if($data['pago_estadoconciliacion'] == 2 || $data['pago_estadoconciliacion'] == 3)
                                            {
                                                /*
                                                * Se cuenta una alerta
                                                */
                                                $cantAlertas++;

                                                /*
                                                * Se especifica la alerta
                                                */
                                                $alertaLinea .= 'Alerta:<br>La Conciliación para la Factura con Id ('.$factura.') se Resolvió con estado ['.$data['pago_descconciliacion'].'].';
                                            }else
                                                {
                                                    /*
                                                    * Se cuenta un éxito en conciliacion
                                                    */
                                                    $cantCorrectas++;
                                                }
        
                                            /*
                                            * Se Actualiza el registro del pago
                                            */
                                            $this->codegen_model->edit('est_pagos',$data,'pago_id',$vPago[0]->pago_id);
                                        }
                                }else
                                    {
                                        /*
                                        * Se solicita el calculo de valores para conciliacion con el id
                                        * de factura del pago
                                        */                                                                                           
                                        $data = $this->calcularDatosConciliacion($fecha, $banco, $valor, '', $factura);
                                                                               
                                        /*
                                        * Se cuenta una alerta
                                        */
                                        $cantAlertas++;

                                        /*
                                        * Se especifica la alerta
                                        */
                                        $alertaLinea .= 'Alerta:<br>La Conciliación para la Factura con Id ('.$factura.') se Resolvió con estado ['.$data['pago_descconciliacion'].'].';

                                        /*
                                        * Si no existe el pago se crea la instancia para el pago
                                        */
                                        $respuestaProceso = $this->codegen_model->add('est_pagos',$data);
                                    }
                            }else
                                {
                                    /*
                                    * Se cuenta un error en conciliacion
                                    */
                                    $cantIncorrectas++;

                                    /*
                                    * Se especifica el error
                                    */
                                    $errorLinea .= 'Error:<br>La Factura con Id ('.$factura.') no Existe en la Base de Datos.';
                                }
                            
                            /*
                            * Si los mensajes llegan vacios no se agregan al mensaje
                            */
                            if($errorLinea != '')
                            {
                                $mensajeError .= $errorLinea.'<br>';                                
                            }
                            if($alertaLinea != '')
                            {
                                $mensajeAlerta .= $alertaLinea.'<br>';                                
                            }
                        }

                        /*
                        * Valida las cantidades de cada notificacion para enviar o no los string
                        * de la descripcion
                        */
                        if($cantCorrectas > 0)
                        {
                            $this->session->set_flashdata('successmessage', 'Se Conciliaron con éxito ['.$cantCorrectas.'] Facturas de Pago!');
                        }
                        

                        /*
                        * Valida si hubo errores o alertas para iniciar la sesion y enviar los mensajes
                        * por la sesion
                        */    
                        if($cantIncorrectas > 0 || $cantAlertas > 0)
                        {
                            session_start();
                        }

                        if($cantIncorrectas > 0)
                        {
                            $this->session->set_flashdata('errormessage', 'No se pudo Realizar la Conciliación de ['.$cantIncorrectas.'] Facturas!<br>');
                            $_SESSION['errores'] = $mensajeError;
                        }

                        if($cantAlertas > 0)
                        {
                            $this->session->set_flashdata('warnigmessage', 'Se Realizó la Conciliación de ['.$cantAlertas.'] Facturas con las Siguientes Especificaciones!<br>');
                            $_SESSION['alertas'] = $mensajeAlerta;
                        }                        

                        redirect(base_url().'index.php/pagos/add');
                    }else
                        {
                            $this->session->set_flashdata('errormessage', 'El Archivo no contiene registros de pago para Conciliación');
                            redirect(base_url().'index.php/pagos/add');
                        }         
                }else
                    {
                        $err = $this->upload->display_errors(); 
                        $this->session->set_flashdata('errormessage', $err);
                        redirect(base_url().'index.php/pagos/add');
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


    /**
    * Funcion de Apoyo que genera el arreglo
    * de datos para asignar a los campos de conciliacion
    * de pago de estampillas
    * @param 
    */
    function calcularDatosConciliacion($fecha, $banco, $valor, $objPago = '', $factura = '', $liquidador = false)
    {
        $data = array();        
        $data['pago_valorconciliacion'] = $valor;
        $data['pago_fechaconciliacion'] = $fecha;
        $data['pago_bancoconciliacion'] = $banco;

        /*
        * Valida si llegó la bandera de liquidador para asginar
        * el id del liquidador en el vector y no modificar
        * el id ya guardado de quien cargó el archivo plano
        */
        if($liquidador)
        {
            $liquidador = $this->ion_auth->user()->row();
            $data['pago_liquidadorconciliacion'] = $liquidador->id;
        }else
            {
                /*
                * Si no llegó la bandera de liquidador indica
                * que es conciliacion por archivo plano
                * entonces asigna el id del usuario autenticado
                */
                $usuario = $this->ion_auth->user()->row();
                $data['pago_userconciliacion'] = $usuario->id;
            }

        /*
        * Valida si llegó el objeto de pago para asignar el valor
        * a la variable $pagoRegistrado para la comparación
        */
        if($objPago == '')
        {
            /*
            * Si no llega un objeto de pago es porque
            * no se ha creado un registro de pago
            * entonces se debe crear uno
            */
            $pagoRegistrado = 0;

            /*
            * Valida que la factura no llegue vacia
            */
            if($factura != '')
            {
                $data['pago_facturaid'] = $factura;
            }else
                {
                    echo 'Debe especificar un id de factura para crear el registro de pago';
                    exit();
                }
        }else
            {
                $pagoRegistrado = $objPago[0]->pago_valor;
            }

        /*
        * Valida si el valor del pago registrado es igual
        * al valor del pago reportado por el banco para
        * registrar el estado de la conciliacion
        */

        /** ESTADOS DE CONCILIACION POR CARGUE DE ARCHIVO PLANO
        *******************************************************
        ** 1 paz y salvo
        ** 2 diferencia pagó mas
        ** 3 diferencia pagó menos
        ** 4 diferencia pagó mas (No se Había registrado pago para esa factura)        
        *******************************************************
        **/
        /** ESTADOS DE CONCILIACION POR CARGUE DE SOPORTE DEL LIQUIDADOR
        *******************************************************
        ** 5 paz y salvo (Cruce Liquidador)
        ** 6 diferencia pagó mas (Cruce Liquidador)
        ** 7 diferencia pagó menos (Cruce Liquidador)
        *******************************************************
        **/
        if((float)$pagoRegistrado != (float)$valor)
        {
            /*
            * Valida si valor de pago registrado es mayor
            * al valor de pago reportado por el banco
            */
            $data['pago_diferenciaconciliacion'] = (float)$pagoRegistrado - (float)$valor;                                        
            if($data['pago_diferenciaconciliacion'] < 0)
            {
                /*
                * Valida si la bandera liquidador es true para asignar el estado
                * correspondiente para cruce de liquidador
                */
                if($liquidador)
                {
                    /*
                    * Si es mayor y fué cruce por liquidador establece el estado en 6
                    */
                    $data['pago_estadoconciliacion'] = 6;
                    $data['pago_descconciliacion'] = 'Diferencia pagó mas (Cruce Liquidador)';  
                }else
                    {
                        /*
                        * Valida si llegó la variable $factura lo que indica
                        * que no se había registrado pago para esa factura
                        */
                        if(isset($data['pago_facturaid']))
                        {
                            /*
                            * Si es menor y llegó el id de la factura establece el estado en 4
                            */
                            $data['pago_estadoconciliacion'] = 4;
                            $data['pago_descconciliacion'] = 'Diferencia pagó mas (No se Había registrado pago para la Factura)';
                        }else
                            {
                                /*
                                * Si es mayor establece el estado en 2
                                */
                                $data['pago_estadoconciliacion'] = 2;
                                $data['pago_descconciliacion'] = 'Diferencia pagó mas';                                
                            }
                    }
                /*
                * Se convierte la diferencia en un valor positivo
                */
                $data['pago_diferenciaconciliacion'] = $data['pago_diferenciaconciliacion'] * (-1);                
            }else
                {
                    /*
                    * Valida si la bandera liquidador es true para asignar el estado
                    * correspondiente para cruce de liquidador
                    */
                    if($liquidador)
                    {
                        /*
                        * Si es menor y fué cruce por liquidador establece el estado en 7
                        */
                        $data['pago_estadoconciliacion'] = 7;
                        $data['pago_descconciliacion'] = 'Diferencia pagó menos (Cruce Liquidador)';  
                    }else
                        {
                            /*
                            * Si es menor establece el estado en 3
                            */
                            $data['pago_estadoconciliacion'] = 3;
                            $data['pago_descconciliacion'] = 'Diferencia pagó menos';
                        }                    
                }                                      
        }else
            {
                /*
                * Valida si la bandera liquidador es true para asignar el estado
                * correspondiente para cruce de liquidador
                */
                if($liquidador)
                {
                    /*
                    * Si es igual y fué cruce por liquidador establece el estado en 5
                    */
                    $data['pago_estadoconciliacion'] = 5;
                    $data['pago_descconciliacion'] = 'Paz y Salvo (Cruce Liquidador)'; 

                    /*
                    * Se establece la diferencia de conciliacion en cero
                    */
                    $data['pago_diferenciaconciliacion'] = 0;
                }else
                    {
                        /*
                        * Si es igual establece el estado en 1
                        */
                        $data['pago_estadoconciliacion'] = 1;
                        $data['pago_descconciliacion'] = 'Paz y Salvo';
                    }
            }  
        return $data;
    }
  
/*
* Funcion que permite renderizar la vista de listado de conciliaciones
*/
function conciliacionesIndex()
{
    if ($this->ion_auth->logged_in()) 
    {
        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pagos/conciliacionesIndex')) 
        {
            $this->data['successmessage'] = $this->session->flashdata('successmessage');
            $this->data['errormessage'] = $this->session->flashdata('errormessage');
            $this->data['infomessage'] = $this->session->flashdata('infomessage');
            $this->data['warnigmessage'] = $this->session->flashdata('warnigmessage');

            $this->template->set('title', 'Listado de Conciliaciones');

            //carga las librerias para los estilos
            //y funcionalidad del datatable
            $this->data['style_sheets']= array(
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen',
                            'css/applicationStyles.css' => 'screen'
                        );

            $this->data['javascripts']= array(
                    'js/jquery.dataTables.min.js',
                    'js/plugins/dataTables/dataTables.bootstrap.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/plugins/dataTables/jquery.dataTables.columnFilter.js',
                    'js/accounting.min.js',
                    );      

            $this->template->load($this->config->item('admin_template'),'pagos/pagos_listadoConciliaciones', $this->data);
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
* Funcion de apoyo para renderizar el listado de conciliaciones
*/
function conciliacionesDataTable()
{
    if ($this->ion_auth->logged_in()) 
    {
        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pagos/conciliacionesIndex') ) 
        {
            /**
            * Valida si es administrador o Usuario conciliación para mostrar todas las conciliaciones
            */              
            $usuario = $this->ion_auth->user()->row();
            if($this->ion_auth->is_admin() || $usuario->perfilid == 5)
            {
                $where = '';                
            }else
                {
                    //Extrae los id de las facturas para las que se han hecho conciliaciones
                    $where = 'where pago_liquidadorpago = '.$usuario->id;                    
                }              
            
            /*
            * Valida si se creó un where en la condicion anterior
            * para concatenar con and o where
            */
            if($where == '')
            {
                $where .= ' WHERE pago_estadoconciliacion <> ""';
            }else
                {
                    $where .= ' AND pago_estadoconciliacion <> ""';
                }
            
            $facturas = $this->codegen_model->getSelect('est_pagos',"pago_facturaid",$where);
         
            //se extrae el vector con los id de las facturas
            $idFacturas = '(';
            foreach ($facturas as $factura) 
            {
                $idFacturas .= $factura->pago_facturaid.',';
            }  
            $idFacturas .= '0)';
            $where = 'where fact_id in '.$idFacturas;                            
              
            //Extrae los id de las liquidaciones
            $group = ' GROUP BY f.fact_liquidacionid';
            $liquidaciones = $this->codegen_model->getSelect('est_facturas f',"distinct f.fact_liquidacionid",$where, '', $group);

            //se extrae el vector con los id de las liquidaciones
            $idLiquidaciones = '(';
            foreach ($liquidaciones as $liquidacion) 
            {
                $idLiquidaciones .= $liquidacion->fact_liquidacionid.',';
            }  
            $idLiquidaciones .= '0)';
            $whereIn = 'l.liqu_id in '.$idLiquidaciones;

            $this->load->library('datatables');
            $this->datatables->select('l.liqu_id,l.liqu_tipocontrato,l.liqu_nit,l.liqu_valortotal,p.pago_fecha, p.pago_valor, p.pago_fechaconciliacion, p.pago_valorconciliacion, p.pago_descconciliacion, p.pago_diferenciaconciliacion, p.pago_userconciliacion, b.banc_nombre, f.fact_nombre');
            $this->datatables->from('est_facturas f');  
            $this->datatables->join('est_liquidaciones l', 'l.liqu_id = f.fact_liquidacionid', 'left');
            $this->datatables->join('est_pagos p', 'p.pago_facturaid = f.fact_id', 'left');
            $this->datatables->join('par_bancos b', 'p.pago_bancoconciliacion = b.banc_id', 'left');
            $this->datatables->where($whereIn);
            $this->datatables->where('p.pago_estadoconciliacion <> ""');
                                           
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


function extraerDatos()
{
    if ($this->ion_auth->logged_in()) 
    {
        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pagos/conciliacionesIndex')) 
        {  
            $idLiquidacion = $this->input->post('idLiquidacion');
            $idUsuario = $this->input->post('idUsuario');

            /*
            * Extrae el nombre del contratista
            */            
            $nomContratista = $this->codegen_model->getSelect('est_liquidaciones',"liqu_nombrecontratista",'WHERE liqu_id = '.$idLiquidacion);

            /*
            * Extrae el nombre del usuario que cargó
            * el archivo de pagos para conciliación
            */
            $nomUsuario = $this->codegen_model->getSelect('users',"first_name,last_name",'WHERE id = '.$idUsuario);
            
            echo json_encode(array('usuario'=>$nomUsuario[0]->first_name.' '.$nomUsuario[0]->last_name, 'contratista'=>$nomContratista[0]->liqu_nombrecontratista));
        }else
            {
                redirect(base_url().'index.php/error_404');       
            } 
    }else 
        {
            redirect(base_url().'index.php/users/login');
        }
}

	function edit()
  {    
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pagos/edit')) {  

              $idpago = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->input->post('id') ;
              if ($idpago==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un pago para editar');
                  redirect(base_url().'index.php/pagos');
              }
              $resultado = $this->codegen_model->get('par_pagos','banc_nombre','banc_id = '.$idpago,1,NULL,true);
              
              foreach ($resultado as $key => $value) {
                  $aplilo[$key]=$value;
              }
              
              if ($aplilo['banc_nombre']==$this->input->post('nombre')) {
                  
                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]');
              
              } else {

                  $this->form_validation->set_rules('nombre', 'Nombre', 'required|trim|xss_clean|max_length[100]|is_unique[par_pagos.banc_nombre]');
              
              }

              $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|xss_clean|max_length[500]');

              if ($this->form_validation->run() == false) {
                  
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : false);
                            
              } else {                            
                  
                  $data = array(
                          'banc_nombre' => $this->input->post('nombre'),
                          'banc_descripcion' => $this->input->post('descripcion')
                   );
                           
                	if ($this->codegen_model->edit('par_pagos',$data,'banc_id',$idpago) == TRUE) {

                      $this->session->set_flashdata('successmessage', 'El pago se ha editado con éxito');
                      redirect(base_url().'index.php/pagos/edit/'.$idpago);
                      
                	} else {
                				  
                      $this->data['errormessage'] = 'No se pudo registrar el aplilo';

                	}
              }       
                  $this->data['successmessage']=$this->session->flashdata('successmessage');
                  $this->data['errormessage'] = (validation_errors() ? validation_errors() : $this->session->flashdata('errormessage')); 
                	$this->data['result'] = $this->codegen_model->get('par_pagos','banc_id,banc_nombre,banc_descripcion','banc_id = '.$idpago,1,NULL,true);
                  $this->template->set('title', 'Editar tipo pago');
                  $this->template->load($this->config->item('admin_template'),'pagos/pagos_edit', $this->data);
                        
          }else {
              redirect(base_url().'index.php/error_404');
          }
      } else {
          redirect(base_url().'index.php/users/login');
      }
        
  }
	
  function delete()
  {
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pagos/delete')) {  
              if ($this->input->post('id')==''){
                  $this->session->set_flashdata('infomessage', 'Debe elegir un tipo pago para eliminar');
                  redirect(base_url().'index.php/pagos');
              }
              if (!$this->codegen_model->depend('est_estampillas','estm_pagoid',$this->input->post('id'))) {

                  $this->codegen_model->delete('par_pagos','banc_id',$this->input->post('id'));
                  $this->session->set_flashdata('successmessage', 'El pago se ha eliminado con éxito');
                  redirect(base_url().'index.php/pagos');  

              } else {

                  $this->session->set_flashdata('errormessage', 'El pago se encuentra en uso, no es posible eliminarlo.');
                  redirect(base_url().'index.php/pagos/edit/'.$this->input->post('id'));

              }
                         
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
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pagos/manage') ) { 
              
              $this->load->library('datatables');
              $this->datatables->select('b.banc_id,b.banc_nombre,b.banc_descripcion');
              $this->datatables->from('par_pagos b');

              if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pagos/edit')) {
                  
                  $this->datatables->add_column('edit', '<div class="btn-toolbar">
                                                           <div class="btn-group">
                                                              <a href="'.base_url().'index.php/pagos/edit/$1" class="btn btn-default btn-xs" title="Editar tipo pago"><i class="fa fa-pencil-square-o"></i></a>
                                                           </div>
                                                         </div>', 'b.banc_id');

              }  else {
                  
                  $this->datatables->add_column('edit', '', 'b.banc_id'); 
              }
              
              echo $this->datatables->generate();

          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }           
  }
}
