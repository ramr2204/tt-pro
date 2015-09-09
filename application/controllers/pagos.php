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

              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
               
              //carga las librerias para los estilos
              //y funcionalidad del boton de carga de 
              //archivos              
              $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen',
                        'css/plugins/bootstrap/bootstrap-datetimepicker.css' => 'screen',
                        'css/plugins/bootstrap/fileinput.css' => 'screen'
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
        if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('pagos/add')) {

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
                        $mensajeSuccess = '';
                        $mensajeError = '';
                        foreach($vectorPagos as $factura => $valor)
                        {
                            $errorLinea = 'Error:';
                            $alertaLinea = 'Alerta:';
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
                                $vPago = $this->codegen_model->getSelect('est_pagos',"pago_id ,pago_facturaid, pago_valor", $where);

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
                                        $errorLinea .= '<br>La Factura con Id ('.$factura.') ya Ha sido Conciliada.';
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
                                                $alertaLinea .= '<br>La Conciliación para la Factura con Id ('.$factura.') se Resolvió con estado ['.$data['pago_descconciliacion'].'].';
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
                                        * Si no existe el pago se crea la instancia para el pago
                                        */
                                        $this->codegen_model->add('est_pagos',$data);
                                    }
                            }else
                                {

                                }
                        }
                    }

                    print_r($vectorPagos);exit();
                            $resultado = $this->codegen_model->get('est_pagos','pago_id','pago_facturaid = '."'$explode[0]'",1,NULL,true);
                            if (!$resultado) {                               
                                $data = array(
                                'pago_facturaid' => $explode[0],
                                'pago_fecha' => $explode[1],
                                'pago_valor' => $explode[2],
                                'pago_metodo' => 'Archivo'
                                );}
                            //acá hay que hacer las validaciones
         
         
                        //     if ($this->codegen_model->add('est_pagos',$data) == TRUE) {
                        //         $success++;
                        //      } else {
                        //         $error++;
                        //     }
                        // }else 
                        //     {                 
                        //          //valida que no sea una linea en blanco
                        //          //o sin codigo para no almacenar el mensaje vacio
                        //           if($explode[0])
                        //          {
                        //                $msjInfo .= 'El pago de la factura No. '.$explode[0]. ' ya fue registrado.<br>';
                        //           }                                
                        //      }
                                                                                                                                                                                                         
         
                   //Valida si la cantidad de pagos en error o exito
                   //se alteraron para cargar el mensaje de exito
                   if($error > 0 || $success > 0)
                   {
                       $this->session->set_flashdata('successmessage', 'Se cargaron '.$success.' pagos con éxito y '.$error. ' con errores');                                                               
                   }
                                
         
               } else {
                     $err = $this->upload->display_errors(); 
                     $this->session->set_flashdata('errormessage', $err);                                                                
                             
                }  
                          
              //carga el mensaje de información si existe
               if($msjInfo != '')
                {                            
                    $this->session->set_flashdata('infomessage', $msjInfo);
               } 
                    
                         
              redirect(base_url().'index.php/pagos/add');

          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  }


    /**
    * Funcion de Apoyo que genera el arreglo
    * de datos para asignar a los campos de conciliacion
    * de pago de estampillas
    * @param 
    */
    function calcularDatosConciliacion($fecha, $banco, $valor, $objPago = '', $factura = '')
    {
        $data = array();
        $usuario = $this->ion_auth->user()->row();
        $data['pago_userconciliacion'] = $usuario->id;
        $data['pago_valorconciliacion'] = $valor;
        $data['pago_fechaconciliacion'] = $fecha;
        $data['pago_bancoconciliacion'] = $banco;

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

        /** ESTADOS DE CONCILIACION
        ************************************************
        ** 1 paz y salvo
        ** 2 diferencia pagó mas
        ** 3 diferencia pagó menos
        ************************************************
        **/
        if((float)$pagoRegistrado != (float)$valor)
        {
            /*
            * Valida si valor de pago registrado es mayor
            * al valor de pago reportado por el banco
            */
            $data['pago_diferenciaconciliacion'] = (float)$pagoRegistrado - (float)$valor;                                        
            if($data['pago_diferenciaconciliacion'] > 0)
            {
                /*
                * Si es mayor establece el estado en 2
                */
                $data['pago_estadoconciliacion'] = 2;
                $data['pago_descconciliacion'] = 'Diferencia pagó mas';
            }else
                {
                    /*
                    * Si es menor establece el estado en 3
                    */
                    $data['pago_estadoconciliacion'] = 3;
                    $data['pago_descconciliacion'] = 'Diferencia pagó menos';

                    /*
                    * Se convierte la diferencia en un valor positivo
                    */
                    $data['pago_diferenciaconciliacion'] = $data['pago_diferenciaconciliacion'] * (-1);
                }                                        
        }else
            {
                /*
                * Si es igual establece el estado en 1
                */
                $data['pago_estadoconciliacion'] = 1;
                $data['pago_descconciliacion'] = 'Paz y Salvo';
            }  
        return $data;
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
