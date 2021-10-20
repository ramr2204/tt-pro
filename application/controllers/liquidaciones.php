<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
/**
*   Nombre:            liquidaciones
*   Ruta:              /application/controllers/liquidaciones.php
*   Descripcion:       controlador de liquidaciones
*   Fecha Creacion:    20/may/2014
*   @author            Iván Viña <ivandariovinam@gmail.com>
*   @version           2014-05-20
*
*/

require_once dirname(__FILE__) . '/pagos.php';
 
class Liquidaciones extends MY_Controller {
    
  function __construct() 
  {
      parent::__construct();
	  $this->load->library('form_validation','Pdf');		
      $this->load->helper(array('form','url','codegen_helper', 'HelperGeneral'));
      $this->load->model('liquidaciones_model','',TRUE);
      $this->load->model('codegen_model','',TRUE);
      $this->load->helper('Equivalencias');
	}
	
	function index()
    {
		  $this->liquidar();
	}

	
  function liquidar()
  {
      if ($this->ion_auth->logged_in()){

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')){

              $this->data['errorModal']     = $this->session->flashdata('errorModal');
              $this->data['successmessage'] = $this->session->flashdata('successmessage');
              $this->data['errormessage']   = $this->session->flashdata('errormessage');
              $this->data['infomessage']    = $this->session->flashdata('infomessage');
              $this->data['accion']         = $this->session->flashdata('accion');

              if ($this->uri->segment(3)>0){
                  $this->data['idcontrato']= $this->uri->segment(3);
               } else {
               	  $this->data['idcontrato']= 0;
               }
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
                'js/autoNumeric.js',
                'js/applicationEvents.js'
              );
              $resultado = $this->codegen_model->max('con_contratos','cntr_fecha_firma');
              
              foreach ($resultado as $key => $value) {
                  $aplilo[$key]=$value;
              }
              $vigencia_mayor=substr($aplilo['cntr_fecha_firma'], 0, 4);
              $vigencia_anterior=$vigencia_mayor-1;
              $this->data['vigencias']= array($vigencia_mayor,$vigencia_anterior);

            /*
            * Extrae los datos del contrato de estampillas activo+
            * y de la cantidad de estampillas para notificación
            */
            $where = 'WHERE conpap_estado = 1';                            
            $vContratoE = $this->codegen_model->getSelect('est_contratopapeles',"conpap_id,conpap_estado,conpap_cantidad,conpap_impresos", $where);

            $estampillasNotificacion = $this->codegen_model->getSelect('adm_parametros',"para_estampillasnotificacion", 'where para_id = 1');

            /*
            * Valida si hay un contrato de estampillas activo
            */
            $this->data['band_saldoestampillas'] = false;
            if(count($vContratoE) > 0)
            {
                $saldoEstampillasContrato = (int)$vContratoE[0]->conpap_cantidad - (int)$vContratoE[0]->conpap_impresos;
                if((int)$saldoEstampillasContrato <= (int)$estampillasNotificacion[0]->para_estampillasnotificacion)
                {
                    $this->data['band_saldoestampillas'] = true;
                    $this->data['notif_saldoestampillas'] = "ACTUALMENTE QUEDAN <b>(". $saldoEstampillasContrato .")</b> ESTAMPILLAS RESTANTES"
                        ." PARA CULMINAR EL CONTRATO ACTUAL, POR FAVOR GESTIONE EL TRAMITE PARA UN NUEVO CONTRATO";
                }
            }else 
                {
                    $this->data['band_saldoestampillas'] = true;
                    $this->data['notif_saldoestampillas'] = "ACTUALMENTE NO CUENTA CON UN CONTRATO ACTIVO PARA IMPRESION DE ESTAMPILLAS,"
                        ." POR FAVOR GESTIONE EL TRAMITE PARA UN NUEVO CONTRATO";
                }

            $this->template->load($this->config->item('admin_template'),'liquidaciones/liquidaciones_liquidar', $this->data);
              
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
              redirect(base_url().'index.php/users/login');
      }

  }

    /*
    * Funcion que ordena la renderización de los contratos con regimen otros para realizar
    * auditoria a las liquidaciones
    */
    public function listarLiquidacionesIVADescuentos()
    {
        if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar'))
            {
                $this->data['successmessage']=$this->session->flashdata('successmessage');
                $this->data['errormessage']=$this->session->flashdata('errormessage');
                $this->data['infomessage']=$this->session->flashdata('infomessage');
              
                //template data
                $this->template->set('title', 'Auditar liquidaciones');
                $this->data['style_sheets']= array(
                        'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen',
                        'css/applicationStyles.css' => 'screen'
                        );

                $this->data['javascripts']= array(
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js',
                        'js/plugins/dataTables/jquery.dataTables.columnFilter.js',
                        'js/autoNumeric.js',
                        'js/applicationEvents.js'
                       );
                
                /*
                * Extrae la vigencia maxima de los contratos para el select
                * en los filtros
                */
                $resultado = $this->codegen_model->max('con_contratos','cntr_fecha_firma');
              
                foreach($resultado as $key => $value)
                {
                    $aplilo[$key] = $value;
                }
                
                $vigencia_mayor = substr($aplilo['cntr_fecha_firma'], 0, 4);
                $vigencia_anterior = $vigencia_mayor - 1;

                /*
                * Construye el vector para las vigencias para js
                */
                $a = "[";
                foreach (array($vigencia_mayor,$vigencia_anterior) as $key => $value)
                {
                    $a .= '"'.$value.'", ';
                }
                $a = substr($a, 0, -2);
                $a .= "]";

                $this->data['vigencias'] = $a;
                $this->template->load($this->config->item('admin_template'),'liquidaciones/liquidaciones_listadoivaotros', $this->data);
            }else 
                {
                    redirect(base_url().'index.php/error_404');
                }
        }else
            {
                redirect(base_url().'index.php/users/login');
            }
    }

 function liquidartramites()
  {
      if ($this->ion_auth->logged_in()){

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')){
 
              $this->data['errorModal']=$this->session->flashdata('errorModal');
              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
              $this->data['accion']=$this->session->flashdata('accion');
              if ($this->uri->segment(3)>0){
                  $this->data['idtramite']= $this->uri->segment(3);
               } else {
                  $this->data['idtramite']= 0;
               }
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
                        'js/plugins/bootstrap/bootstrap-switch.min.js'
                       );
       
              $this->template->load($this->config->item('admin_template'),'liquidaciones/liquidaciones_liquidartramites', $this->data);
              
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
              redirect(base_url().'index.php/users/login');
      }

  }

  function liquidarcontrato()
  {
      if ($this->ion_auth->logged_in()) {
          if ($this->uri->segment(3)==''){
               redirect(base_url().'index.php/error_404');
          }    
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {
                $idcontrato = $this->uri->segment(3);
                $this->data = $this->obtenerInfoFacturas($idcontrato);

                $this->template->set('title', 'Editar contrato');
                $this->load->view('liquidaciones/liquidaciones_liquidarcontrato', $this->data); 
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  } 

 
	function procesarliquidacion()
	{
		if ($this->ion_auth->logged_in())
		{
			if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar'))
			{
				/*
				* Extrae el usuario autenticado para establecer que usuario
				* realizó la liquidación
				*/
				$usuario = $this->ion_auth->user()->row();

				$codigo = 00000;
				$idcontrato=$this->input->post('idcontrato');

				/*
				* Extrae el objeto del contrato
				*/
				$contrato = $this->liquidaciones_model->get($idcontrato);

				$data = array(
					'liqu_contratoid'           => $this->input->post('idcontrato'),
					'liqu_nombrecontratista'    => $this->input->post('nombrecontratista'),
					'liqu_nit'                  => $this->input->post('nit'),
					'liqu_tipocontratista'      => $this->input->post('tipocontratista'),
					'liqu_numero'               => $this->input->post('numero'),
					'liqu_vigencia'             => $this->input->post('vigencia'),
					'liqu_valorconiva'          => $this->input->post('valorconiva'),
					'liqu_valorsiniva'          => $this->input->post('valorsiniva'),
					'liqu_tipocontrato'         => $this->input->post('tipocontrato'),
					'liqu_regimenid'            => $this->input->post('idregimen'),
					'liqu_regimen'              => $this->input->post('regimen'),
					'liqu_nombreestampilla'     => $this->input->post('nombreestampilla'),
					'liqu_cuentas'              => $this->input->post('cuentas'),
					'liqu_porcentajes'          => $this->input->post('porcentajes'),
					'liqu_totalestampilla'      => $this->input->post('totalestampillas'),
					'liqu_valortotal'           => $this->input->post('valortotal'),
					'liqu_comentarios'          => $this->input->post('comentarios'),
					'liqu_codigo'               => $codigo,
					'liqu_fecha'                => date('Y-m-d'),
					'liqu_usuarioliquida'       => $usuario->id,
					'liqu_tiempoliquida'        => date('Y-m-d H:i:s'),
                    'id_empresa'                => $contrato->id_empresa
				);

				$respuestaProceso = $this->codegen_model->add('est_liquidaciones',$data);
				if ($respuestaProceso->bandRegistroExitoso)
				{
					$data = array(
						'cntr_estadolocalid' => 1,
					);
					if ($this->codegen_model->edit('con_contratos',$data,'cntr_id',$idcontrato) == TRUE)
					{
						$this->session->set_flashdata('successmessage', 'La liquidación se realizó con éxito');
						$this->session->set_flashdata('accion', 'liquidado');
						redirect(base_url().'index.php/liquidaciones/liquidar/'.$idcontrato);
					}
				}
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  } 

 
	function verrecibos()
	{
		if ($this->ion_auth->logged_in()) {
			if ($this->uri->segment(3)==''){
				redirect(base_url().'index.php/error_404');
			}
			if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar'))
			{
				$idcontrato				= $this->uri->segment(3);
				$this->data['result']	= $this->liquidaciones_model->getrecibos($idcontrato);

                $this->data['facturas']	= [];

				$contrato				= $this->codegen_model->getSelect('con_contratos','date_format(fecha_insercion,"%Y-%m-%d") AS fecha_insercion', 'WHERE cntr_id = "'.$idcontrato.'"');
				$this->data['contrato']	= $contrato[0];

				$this->template->set('title', 'Contrato liquidado');
				$this->load->view('liquidaciones/liquidaciones_vercontratoliquidado', $this->data); 
			} else {
				redirect(base_url().'index.php/error_404');
			}
		} else {
			redirect(base_url().'index.php/users/login');
		}
	}


  function cargar_comprobante()
  {
      if ($this->ion_auth->logged_in()) 
      {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) 
            {
              $this->data['successmessage']=$this->session->flashdata('message');
              $this->data['errormessage']=''; 
              $this->form_validation->set_rules('numeroarchivos', 'numero archivos', 'trim|xss_clean|numeric|integer|greater_than[0]');

              //Validaciones para el id ya sea de tramite o contrato
              //elije el nombre de carpeta y el id
              if ($this->input->post('contratoid')) {
                  $this->form_validation->set_rules('contratoid', 'contrato id', 'trim|xss_clean|numeric|integer|greater_than[0]');
                  $carpeta='facturas';
                  $id=$this->input->post('contratoid');
              
              }
              if ($this->input->post('tramiteid')) {
                  $this->form_validation->set_rules('tramiteid', 'trámite id', 'trim|xss_clean|numeric|integer|greater_than[0]');
                  $carpeta='facturas_tramites';
                  $id=$this->input->post('tramiteid');
              }

            /*
            * Extrae el usuario autenticado para establecer que usuario
            * creó el contrato
            */
            $usuario = $this->ion_auth->user()->row();
                
              /*
              * Valida si es un contrato para verificar la carga de la copia del objeto
              */
              if($this->input->post('contratoid'))
              {
                /*
                * Valida que la liquidacion tenga registrada la ruta del soporte
                */
                $vSoporteObjeto = $parametros=$this->codegen_model->get('est_liquidaciones','liqu_soporteobjeto','liqu_id = '.$this->input->post('liquida_id'),1,NULL,true);
                if($vSoporteObjeto->liqu_soporteobjeto == '')
                {
                    /*
                    * Valida si el archivo fue cargado
                    */        
                    if (!isset($_FILES['upload_field_name']) && !is_uploaded_file($_FILES['comprobante_objeto']['tmp_name'])) 
                    {
                        $this->session->set_flashdata('errorModal', '<strong>Error!</strong> Debe cargar la Copia de Objeto del Contrato.');
                        $this->session->set_flashdata('accion', 'liquidado');                        
                        $this->session->set_flashdata('errormessage', '<strong>Error!</strong> Debe cargar la Copia de Objeto del Contrato.');
  
                        //Valida para redirecionar a la vista respectiva
                        //tramite o contrato
                        if ($this->input->post('contratoid'))
                        {
                            redirect(base_url().'index.php/liquidaciones/liquidar/'.$id);
                        }elseif ($this->input->post('tramiteid'))
                            {
                                redirect(base_url().'index.php/liquidaciones/liquidartramites/'.$id);
                            }                      
                    }else
                        {
                            $path = 'uploads/objetosContrato/liquidacion'.$id;
                            if(!is_dir($path)) { //crea la carpeta para los objetos si no existe
                                mkdir($path,0777,TRUE);      
                            }
                            $config['upload_path'] = $path;
                            $config['allowed_types'] = 'jpg|jpeg|gif|png|tif|pdf';
                            $config['remove_spaces']=TRUE;
                            $config['max_size']    = '99999';
                            $config['overwrite']    = TRUE;
                            $this->load->library('upload');
          
                            $idComprobante = $this->input->post('liquida_id');
                            $config['file_name']='liquidacion_'.$idComprobante.'_'.date("F_d_Y");
                            $this->upload->initialize($config);

                            //Valida si se carga correctamente el soporte
                            if ($this->upload->do_upload("comprobante_objeto")) 
                            {
                                /*
                                * Establece la informacion para actualizar la liquidacion
                                * en este caso la ruta de la copia del objeto del contrato
                                */
                                $file_datos= $this->upload->data();
                                $data = array(
                                    'liqu_soporteobjeto' => $path.'/'.$file_datos['orig_name']                          
                                  );
          
                                if ($this->codegen_model->edit('est_liquidaciones',$data,'liqu_id',$idComprobante) == FALSE)
                                {
                                    $this->session->set_flashdata('errorModal', '<strong>Error!</strong> No se pudo registrar la Copia de Objeto del Contrato.');
                                    $this->session->set_flashdata('accion', 'liquidado');                        
                                    $this->session->set_flashdata('errormessage', '<strong>Error!</strong> No se pudo registrar la Copia de Objeto del Contrato.');
          
                                    //Valida para redirecionar a la vista respectiva
                                    //tramite o contrato
                                    if ($this->input->post('contratoid'))
                                    {
                                        redirect(base_url().'index.php/liquidaciones/liquidar/'.$id);
                                    }elseif ($this->input->post('tramiteid'))
                                        {
                                            redirect(base_url().'index.php/liquidaciones/liquidartramites/'.$id);
                                        }
                                }
                            }else 
                                {
                                    $this->session->set_flashdata('errorModal', '<strong>Error!</strong> '.$this->upload->display_errors());
                                    $this->session->set_flashdata('accion', 'liquidado');
                                    $this->session->set_flashdata('errormessage', '<strong>Error!</strong> '.$this->upload->display_errors());
          
                                    //Valida para redirecionar a la vista respectiva
                                    //tramite o contrato
                                    if ($this->input->post('contratoid'))
                                    {
                                        redirect(base_url().'index.php/liquidaciones/liquidar/'.$id);
                                    }elseif ($this->input->post('tramiteid'))
                                        {
                                            redirect(base_url().'index.php/liquidaciones/liquidartramites/'.$id);
                                        }
                                }            
                            
                        }
                }
              }
              
              $numeroarchivos=$this->input->post('numeroarchivos');
              
              if ($id >0 && $numeroarchivos > 0 ) {
                  $path = 'uploads/'.$carpeta.'/'.$id;
                  if(!is_dir($path)) { //create the folder if it's not already exists
                      mkdir($path,0777,TRUE);      
                  }
                  $config['upload_path'] = $path;
                  $config['allowed_types'] = 'jpg|jpeg|gif|png|tif|pdf';
                  $config['remove_spaces']=TRUE;
                  $config['max_size']    = '2048';
                  $config['overwrite']    = TRUE;
                  $this->load->library('upload');


                  $success=0;
                  $referenciaCargados='';
                  for ($i=0; $i < $numeroarchivos; $i++) {
                 
                    if(isset($_POST['fecha_pago_'.$i]) && $_POST['fecha_pago_'.$i] == '')
                    {
                        $this->session->set_flashdata('errorModal', '<strong>Error!</strong> Debe Ingresar una Fecha para el Pago.');
                        $this->session->set_flashdata('accion', 'liquidado');                        
                        $this->session->set_flashdata('errormessage', '<strong>Error!</strong> Debe Ingresar una Fecha para el Pago.');
                        
                        //Valida para redirecionar a la vista respectiva
                        //tramite o contrato
                        if ($this->input->post('contratoid'))
                        {
                            redirect(base_url().'index.php/liquidaciones/liquidar/'.$id);
                        }elseif ($this->input->post('tramiteid'))
                            {
                                redirect(base_url().'index.php/liquidaciones/liquidartramites/'.$id);
                            }
                        

                    }elseif(isset($_POST['fecha_pago_'.$i]) && $_POST['fecha_pago_'.$i] != '')
                        {
                            if(strtotime($_POST['fecha_pago_'.$i]) > strtotime(date('Y-m-d')))
                            {
                                $this->session->set_flashdata('errorModal', '<strong>Error!</strong> la Fecha de Pago no Puede ser Mayor al Dia actual.');
                                $this->session->set_flashdata('accion', 'liquidado');
                                $this->session->set_flashdata('errormessage', '<strong>Error!</strong> la Fecha de Pago no Puede ser Mayor al Dia actual.');
                                
                                //Valida para redirecionar a la vista respectiva
                                //tramite o contrato
                                if ($this->input->post('contratoid'))
                                {
                                    redirect(base_url().'index.php/liquidaciones/liquidar/'.$id);
                                }elseif ($this->input->post('tramiteid'))
                                    {
                                        redirect(base_url().'index.php/liquidaciones/liquidartramites/'.$id);
                                    }
                            }
                        }

                    //Si se envia el pago en el checkbox
                    //permitira entrar a la consulta 
                    //para crear la factura, de lo contrario
                    //creará una bandera
                    if(isset($_POST['pago'.$i]))
                    {
                        $pago = $this->input->post('pago'.$i);  
                    }else
                        {
                            $pago = 'flag'  ;
                        }
                   
                    
                    $idfactura=$this->input->post('facturaid'.$i);
                    $config['file_name']=$idfactura.'_'.date("F_d_Y");
                    $this->upload->initialize($config);

                    if ($pago != 'flag') 
                    {
                        /*
                        * Valida si ya se creó un pago para la factura
                        */
                        $where = 'WHERE pago_facturaid = '.$idfactura;
                        $vPago = $this->codegen_model->getSelect('est_pagos',"pago_id, pago_valor, pago_valorconciliacion, pago_fechaconciliacion, pago_bancoconciliacion", $where);
                        
                        /*
                        * Se extrae el objeto del usuario autenticado
                        */
                        $usuario = $this->ion_auth->user()->row();
                        if(count($vPago) > 0)
                        {
                            /*
                            * Valida si el pago tiene conciliacion registrada
                            */
                            if($vPago[0]->pago_valorconciliacion != null)
                            {
                                /*
                                * Se solicita el calculo de valores para conciliacion con el objeto
                                * de pago existente asignandole el valor que llega del formulario
                                */
                                $vPago[0]->pago_valor = $pago;
                                $datos = Pagos::calcularDatosConciliacion($vPago[0]->pago_fechaconciliacion, $vPago[0]->pago_bancoconciliacion, $vPago[0]->pago_valorconciliacion, $vPago, '', true);

                                /*
                                * Se Agregan los datos del pago manual
                                */                                
                                $datos['pago_facturaid'] = $idfactura;
                                $datos['pago_fecha'] = $_POST['fecha_pago_'.$i];
                                $datos['pago_valor'] = $pago;
                                $datos['pago_liquidadorpago'] = $usuario->id;
                                $datos['pago_metodo'] = 'manual';                                         

                                /*
                                * Se Actualiza el registro del pago
                                */
                                $this->codegen_model->edit('est_pagos',$datos,'pago_id',$vPago[0]->pago_id);                                        
                            }
                        }else
                            {
                                /*
                                * Si no hay un pago creado se crea el registro
                                */
                                $datos = array(
                                         'pago_facturaid' => $idfactura,
                                         'pago_fecha' => $_POST['fecha_pago_'.$i],
                                         'pago_valor' => $pago,
                                         'pago_liquidadorpago' => $usuario->id,
                                         'pago_metodo' => 'manual',
                                       );

                                $respuestaProceso = $this->codegen_model->add('est_pagos',$datos);
                            }
                      }
                      

                      $this->form_validation->set_rules('facturaid'.$i, 'factura id '.$i, 'trim|xss_clean|numeric|integer|greater_than[0]'); 
                      if ($this->form_validation->run() == false) {
                          $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
                      } else {

                          if ($this->upload->do_upload("comprobante".$i)) {
                              $file_data = $this->upload->data();
                              $data = array(
                                 'fact_rutacomprobante' => $path.'/'.$file_data['orig_name'],
                                 'fact_fechacomprobante' => date("Y-m-d H:i:s"),
                                 'fact_usercomprobante' => $usuario->id
                               );
                              if ($this->codegen_model->edit('est_facturas',$data,'fact_id',$idfactura) == TRUE) {
                                  $success++;
                                  $referenciaCargados.=' Comprobante: '.$this->input->post('facturaNombre'.$i).': archivo -> '.$file_data['client_name'].' ||';
                                    
                              } else {
                                   $this->data['errormessage'] .= '<br>No se pudo registrar el comprobante'.$i;
                              }  
                          } else {
                            $this->data['errormessage'] .=$this->upload->display_errors(); 
                          }  
                      }

                  }
              } else {
                  $this->data['errormessage'] = 'Datos incorrectos'.$this->input->post('contratoid').' ---- '.$numeroarchivos;
              }
              if ($success > 0) {
                $this->session->set_flashdata('successmessage', 'Se Cargó con éxito'.$referenciaCargados); 

              } else {
                $this->session->set_flashdata('errormessage', '<strong>Error!</strong> '.$this->data['errormessage'] );
              }
              $this->session->set_flashdata('accion', 'liquidado');

              //Dependiendo del tipo de liquidacion redirecciona
              //a la ruta respectiva
              if ($this->input->post('contratoid')) {
                  redirect(base_url().'index.php/liquidaciones/liquidar/'.$id);    
              }
              if ($this->input->post('tramiteid')) {
                  redirect(base_url().'index.php/liquidaciones/liquidartramites/'.$id);
              }   
              
              
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }  
  }



function legalizar()
  {        
      if ($this->ion_auth->logged_in()) 
      {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) 
          {
               
               //realiza el proceso de actualización
               //dependiendo del tipo de documento
               //para el que se generó el evento
               if ($this->input->post('contratoid')) 
               {
                   $id=$this->input->post('contratoid');

                   $data = array(
                   'cntr_estadolocalid' => 2,
                   );
                   if ($this->codegen_model->edit('con_contratos',$data,'cntr_id',$id) == TRUE) {
                       $this->session->set_flashdata('accion', 'legalizado');
                       $this->session->set_flashdata('successmessage', 'La legalización se realizó con éxito');
                   }

                   redirect(base_url().'index.php/liquidaciones/liquidar/'.$id);
               }

               if ($this->input->post('tramiteid')) 
               {
                   $id=$this->input->post('tramiteid');

                   $data = array(
                   'litr_estadolocalid' => 2,
                   'litr_fechalegalizacion' => date('Y-m-d H:i:s')
                   );
                   if ($this->codegen_model->edit('est_liquidartramites',$data,'litr_id',$id) == TRUE) {
                       $this->session->set_flashdata('accion', 'legalizado');
                       $this->session->set_flashdata('successmessage', 'La legalización se realizó con éxito');
                   }

                   redirect(base_url().'index.php/liquidaciones/liquidartramites/'.$id);
               }

                
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }
  } 




function vercontratolegalizado()
 {
      if ($this->ion_auth->logged_in()) {
          if ($this->uri->segment(3)==''){
               redirect(base_url().'index.php/error_404');
          }    
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {
              
              //verifica que el usuario que llama el metodo
              //tenga perfil de liquidador para cargar
              //el proximo codigo de estampilla fisica a imprimir  
              $usuarioLogueado=$this->ion_auth->user()->row();                   
              
              $idcontrato=$this->uri->segment(3);
              $this->data['result'] = $this->liquidaciones_model->getrecibos($idcontrato);
              $liquidacion = $this->data['result'];
              $this->data['facturas'] = $this->liquidaciones_model->getfacturas($liquidacion->liqu_id);
              $todopago=0;
              $numerocomprobantes=0;
              $ncomprobantescargados=0;
              $totalpagado=0;
              $comprobantecargado=array();
              $facturapagada=array();
              $facturas=$this->data['facturas']; 
              foreach ($facturas as $key => $value) {
                  $totalpagado += $value->pago_valor;
                  $numerocomprobantes++;  
                  if ($value->pago_valor >= $value->fact_valor) {
                      $facturapagada[$value->fact_id]=true;
                  } else {
                    $todopago=1;
                    $facturapagada[$value->fact_id]=false;
                  }
                  if ($value->fact_rutacomprobante=='') {
                      $comprobantecargado[$value->fact_id]=true;
                         
                  } else {
                    $comprobantecargado[$value->fact_id]=false;
                    $ncomprobantescargados++;
                  }
              }
             
              $this->data['comprobantecargado'] = $comprobantecargado;
              $this->data['facturapagada'] =$facturapagada;
              $this->data['comprobantes'] = ($numerocomprobantes==$ncomprobantescargados) ? true : false ;
              $this->data['todopago'] = ($todopago==1) ? false : true ;
              $this->data['completado'] = ($todopago AND $this->data['comprobantes'] ) ? false : true ;
              $this->data['totalpagado'] =$totalpagado;
              $this->data['numerocomprobantes'] =$numerocomprobantes;
              $this->data['ncomprobantescargados'] =$ncomprobantescargados;
              $this->template->set('title', 'Contrato liquidado');
              $this->load->view('liquidaciones/liquidaciones_vercontratolegalizado', $this->data); 
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  }

function verliquidartramite()
  {
      if ($this->ion_auth->logged_in()) {
          if ($this->uri->segment(3)==''){
               redirect(base_url().'index.php/error_404');
          }    
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {
              $idliquidacion=$this->uri->segment(3);
              
              $this->data['result'] = $this->liquidaciones_model->getliquidartramite($idliquidacion);
              $tramite = $this->data['result'];
              
              /*
              * Valida si la liquidacion del tramite tiene el numero de placa
              * para concatenarlo con el nombre del tramitador
              */
              if($tramite->litr_placaVehiculo != '')
              {
                  $this->data['result']->tramitador_nombre = $tramite->tramitador_nombre.' - Numero de Placa ('.$tramite->litr_placaVehiculo.')';
              }

              $parametros=$this->codegen_model->get('adm_parametros','para_redondeo,para_salariominimo','para_id = 1',1,NULL,true);
              $this->data['estampillas'] = $this->liquidaciones_model->getestampillastramites($this->data['result']->litr_tramiteid);

              $estampillas=$this->data['estampillas'];   

              //calcula el SMDLV
              $salarioMinimoDiario = round((float)$parametros->para_salariominimo/30,0);

              $totalestampilla= array();
              $valortotal=0;
              
            foreach ($estampillas as $key => $value) 
            {
                /*
                * Se valida si la estampilla a almacenar es pro electrificacion
                * y si la fecha de liquidacion (fecha actual) es mayor al 21 de mayo de 2017
                * no se incluya la estampilla en las liquidaciones según ordenanza 026 de 2007
                */
                $bandRegistrarFactura = Liquidaciones::validarInclusionEstampilla($value->estm_id);
                if($bandRegistrarFactura)
                {
                    $totalestampilla[$value->estm_id] = (($salarioMinimoDiario*$value->estr_porcentaje)/100);
                    $totalestampilla[$value->estm_id] = Liquidaciones::rounding($totalestampilla[$value->estm_id], 1000);                 
                    $valortotal+=$totalestampilla[$value->estm_id];
                }
            }
              $this->data['idtramite']=$idliquidacion;
              $this->data['est_totalestampilla']=$totalestampilla;
              $this->data['cnrt_valorsiniva']=$salarioMinimoDiario;
              $this->data['est_valortotal']=$valortotal;
              $this->template->set('title', 'Liquidar Tramite');
              $this->load->view('liquidaciones/liquidaciones_verliquidartramite', $this->data); 
             
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  } 

  /*
  * Funcion de apoyo que redondea hacia arriba
  * la cifra dada dependiendo del grado de redondeo
  */
  function rounding($number, $significance = 1)
  {
        return ( is_numeric($number) && is_numeric($significance) ) ? (round($number/$significance)*$significance) : false;
  }
 
 function procesarliquidaciontramite()
  {        
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {

            /*
            * Extrae el usuario autenticado para establecer que usuario
            * realizó la liquidación
            */
            $usuario = $this->ion_auth->user()->row();

            $codigo='00000000';
            $idtramite=$this->input->post('idtramite');
            $data = array(
                   'liqu_tramiteid' => $this->input->post('idtramite'),
                   'liqu_nombrecontratista' => $this->input->post('nombretramitador'),
                   'liqu_nit' => $this->input->post('idtramitador'),
                   'liqu_valorsiniva' => $this->input->post('valorsiniva'),
                   'liqu_totalestampilla' => $this->input->post('totalestampillas'),
                   'liqu_valortotal' => $this->input->post('valortotal'),
                   'liqu_tipocontrato' => 'Tramite',
                   'liqu_codigo' => $codigo,
                   'liqu_fecha' => date('Y-m-d'),
                   'liqu_usuarioliquida' => $usuario->id,
                   'liqu_tiempoliquida' => date('Y-m-d H:i:s')
                 );
                  
                $respuestaProceso = $this->codegen_model->add('est_liquidaciones',$data);
              if ($respuestaProceso->bandRegistroExitoso) {
                  $liquidacionid = $respuestaProceso->idInsercion;

                  for ($i=1; $i < $this->input->post('numeroestampillas'); $i++) { 
                      
                      //Valida si la factura viene en valor cero
                      //no guarda factura
                      $valor = $this->input->post('totalestampilla'.$i);
                      
                      if($valor > 0)
                      {  
                          $data = array(
                          'fact_nombre' => $this->input->post('nombreestampilla'.$i),
                          'fact_porcentaje' => $this->input->post('porcentaje'.$i),
                          'fact_valor' => $this->input->post('totalestampilla'.$i),
                          'fact_banco' => $this->input->post('banco'.$i),
                          'fact_cuenta' => $this->input->post('cuenta'.$i),
                          'fact_liquidacionid' => $liquidacionid,
                          'fact_estampillaid' => $this->input->post('idestampilla'.$i),
                          'fact_rutaimagen' => $this->input->post('rutaimagen'.$i),
                          );

                            /*
                            * Se valida si la estampilla a almacenar es pro electrificacion
                            * y si la fecha de liquidacion (fecha actual) es mayor al 21 de mayo de 2017
                            * no se incluya la estampilla en las liquidaciones según ordenanza 026 de 2007
                            */
                            $bandRegistrarFactura = Liquidaciones::validarInclusionEstampilla($data['fact_estampillaid']);
                            if($bandRegistrarFactura)
                            {
                                $respuestaProceso = $this->codegen_model->add('est_facturas',$data);
    
                                /**
                                * Solicita la Asignación del codigo para el codigo de barras
                                */
                                $this->asignarCodigoParaBarras($liquidacionid,$this->input->post('idestampilla'.$i));
                            }
                      }
                  }

                  //print_r($data);
                  $data = array(
                   'litr_estadolocalid' => 1,
                   );
                  if ($this->codegen_model->edit('est_liquidartramites',$data,'litr_id',$idtramite) == TRUE) {
                      
                      $this->session->set_flashdata('successmessage', 'La liquidación se realizó con éxito');
                      $this->session->set_flashdata('accion', 'liquidado');
                      redirect(base_url().'index.php/liquidaciones/liquidartramites/'.$idtramite);
                
                  }
              }
                
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  } 

  function vertramiteliquidado()
 {        
      if ($this->ion_auth->logged_in()) {
          if ($this->uri->segment(3)==''){
               redirect(base_url().'index.php/error_404');
          }    
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {
              $idtramite=$this->uri->segment(3);
              $this->data['result'] = $this->liquidaciones_model->getrecibostramites($idtramite);
              $liquidacion = $this->data['result'];
              $this->data['facturas'] = $this->liquidaciones_model->getfacturas($liquidacion->liqu_id);
              $todopago=0;
              $numerocomprobantes=0;
              $ncomprobantescargados=0;
              $totalpagado=0;
              $comprobantecargado=array();
              $facturapagada=array();
              $facturas=$this->data['facturas']; 
              foreach ($facturas as $key => $value) {
                  $totalpagado += $value->pago_valor;
                  $numerocomprobantes++;  
                 if ($value->pago_valor >= $value->fact_valor) {
                     $facturapagada[$value->fact_id]=true;
                 } else {
                    $todopago=1;
                    $facturapagada[$value->fact_id]=false;
                 }
                 if ($value->fact_rutacomprobante=='') {
                     $comprobantecargado[$value->fact_id]=true;
                     
                 } else {
                   $comprobantecargado[$value->fact_id]=false;
                   $ncomprobantescargados++;
                 }
              }
    
              $this->data['comprobantecargado'] = $comprobantecargado;
              $this->data['facturapagada'] =$facturapagada;
              $this->data['comprobantes'] = ($numerocomprobantes==$ncomprobantescargados) ? true : false ;
              $this->data['todopago'] = ($todopago==1) ? false : true ;
              $this->data['completado'] = ($todopago AND $this->data['comprobantes'] ) ? false : true ;
              $this->data['totalpagado'] =$totalpagado;
              $this->data['numerocomprobantes'] =$numerocomprobantes;
              $this->data['ncomprobantescargados'] =$ncomprobantescargados;
              $this->template->set('title', 'Tramite liquidado');
              
              $this->load->view('liquidaciones/liquidaciones_vertramiteliquidado', $this->data); 
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  } 



  function vertramitelegalizado()
 {        
      if ($this->ion_auth->logged_in()) {
          if ($this->uri->segment(3)==''){
               redirect(base_url().'index.php/error_404');
          }    
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {

          //verifica que el usuario que llama el metodo
          //tenga perfil de liquidador para cargar
          //el proximo codigo de estampilla fisica a imprimir  
          $usuarioLogueado=$this->ion_auth->user()->row();

          if ($usuarioLogueado->perfilid==4)
          {
               //extrae el ultimo codigo de papeleria resgistrado
               //en las impresiones para el liquidador autenticado
               $tablaJoin='est_papeles';
               $equivalentesJoin='est_impresiones.impr_papelid = est_papeles.pape_id';
               $where='est_papeles.pape_usuario ='.$usuarioLogueado->id;

               $max = $this->codegen_model->max('est_impresiones','impr_codigopapel',$where, $tablaJoin, $equivalentesJoin);
               
               //verifica si ya habia asignado por lo menos
               //un consecutivo a una impresion
               //de lo contrario elige el primer codigo

               if((int)$max['impr_codigopapel']>0)
               {
                                $nuevoingreso=$max['impr_codigopapel']+1;
               }else
                   {
                         //extrae el primer codigo de papeleria resgistrado
                         //en los rangos de papel asginado al liquidador autenticado
                         $where='est_papeles.pape_usuario ='.$usuarioLogueado->id;
                         $primerCodigo = $this->codegen_model->min('est_papeles','pape_codigoinicial',$where);
                         $nuevoingreso = (int)$primerCodigo['pape_codigoinicial'];
                   }
                   
                $this->data['proximaImpresion'] = $nuevoingreso;   
          }     

              $idtramite=$this->uri->segment(3);
              $this->data['result'] = $this->liquidaciones_model->getrecibostramites($idtramite);
              $liquidacion = $this->data['result'];
              $this->data['facturas'] = $this->liquidaciones_model->getfacturas($liquidacion->liqu_id);
              $todopago=0;
              $numerocomprobantes=0;
              $ncomprobantescargados=0;
              $totalpagado=0;
              $comprobantecargado=array();
              $facturapagada=array();
              $facturas=$this->data['facturas']; 
              foreach ($facturas as $key => $value) {
                  $totalpagado += $value->pago_valor;
                  $numerocomprobantes++;  
                 if ($value->pago_valor >= $value->fact_valor) {
                     $facturapagada[$value->fact_id]=true;
                 } else {
                    $todopago=1;
                    $facturapagada[$value->fact_id]=false;
                 }
                 if ($value->fact_rutacomprobante=='') {
                     $comprobantecargado[$value->fact_id]=true;
                     
                 } else {
                   $comprobantecargado[$value->fact_id]=false;
                   $ncomprobantescargados++;
                 }
              }
         
              $this->data['comprobantecargado'] = $comprobantecargado;
              $this->data['facturapagada'] =$facturapagada;
              $this->data['comprobantes'] = ($numerocomprobantes==$ncomprobantescargados) ? true : false ;
              $this->data['todopago'] = ($todopago==1) ? false : true ;
              $this->data['completado'] = ($todopago AND $this->data['comprobantes'] ) ? false : true ;
              $this->data['totalpagado'] =$totalpagado;
              $this->data['numerocomprobantes'] =$numerocomprobantes;
              $this->data['ncomprobantescargados'] =$ncomprobantescargados;
              $this->template->set('title', 'Contrato liquidado');
              
              $this->load->view('liquidaciones/liquidaciones_vertramitelegalizado', $this->data); 
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  }


 function addtramite()
  {
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {

              $this->data['successmessage']=$this->session->flashdata('message');
              $this->data['infomessage']= $this->session->flashdata('infomessage');

                # Comprobar si el tramitador ya ha sido registrado con anterioridad
                $tramitador = $this->codegen_model->get('tramitadores','nit,id','nit = "'.$this->input->post('documento').'"',1,NULL,true);
                $existeTramitador = !empty($tramitador);

                if($existeTramitador){
                    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
                    $this->form_validation->set_rules('documento', 'Documento',  'required|trim|xss_clean');
                }else{
                    $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[tramitadores.email]');
                    $this->form_validation->set_rules('documento', 'Documento',  'required|trim|xss_clean|is_unique[tramitadores.nit]');
                }

              $this->form_validation->set_rules('encontrado', 'encontrado','required|trim|xss_clean|numeric');
              $this->form_validation->set_rules('nombre', 'Nombre',  'required|trim|xss_clean');
              $this->form_validation->set_rules('direccion', 'Dirección', 'required|trim|xss_clean|max_length[256]');
              $this->form_validation->set_rules('telefono', 'Telefono', 'required|numeric|trim|xss_clean|max_length[15]');
              $this->form_validation->set_rules('tramiteid', 'Trámite','required|trim|xss_clean|numeric|greater_than[0]');
              $this->form_validation->set_rules('observaciones', 'Observaciones','trim|xss_clean');

            /*
            * Extrae el usuario autenticado para establecer que usuario
            * creó el contrato
            */
            $usuario = $this->ion_auth->user()->row();
              
            if ($this->form_validation->run() == false) 
            {
                $this->data['errormessage'] = (validation_errors() ? validation_errors(): false);
            }else
                {
                    $tramitadorId = null;

                    if($existeTramitador)
                    {
                        $tramitadorId = $tramitador->id;
                    }else{
                        $respuestaTramitador = $this->codegen_model->add('tramitadores',array(
                            'nit'       => $this->input->post('documento'),
                            'nombre'    => $this->input->post('nombre'),
                            'direccion' => $this->input->post('direccion'),
                            'telefono'  => $this->input->post('telefono'),
                            'email'     => $this->input->post('email'),
                        ));

                        if ($respuestaTramitador->bandRegistroExitoso){
                            $tramitadorId = $respuestaTramitador->idInsercion;
                        }
                    }

                    if($tramitadorId)
                    {
                        $data = array(
                            'litr_tramiteid' => $this->input->post('tramiteid'),
                            'litr_tramitadorid' => $tramitadorId,
                            'litr_fechaliquidacion' => date("Y-m-d H:i:s"),
                            'litr_usuarioliquidacion' => $usuario->id,
                            'litr_estadolocalid' => 0,
                            'litr_observaciones' => $this->input->post('observaciones')
                            );
    
                        /*
                        * Valida si el tramite suministrado es 16
                        * certificado de paz y salvo de impuesto de vehiculos
                        * se valida que llegue una placa
                        */
                        if($this->input->post('tramiteid') == 16)
                        {
                            $this->form_validation->set_rules('placa', 'Placa del Vehiculo','required|trim|xss_clean');
                            if ($this->form_validation->run() == false) 
                            {
                                $this->session->set_flashdata('infomessage', validation_errors());
                                redirect(base_url().'index.php/liquidaciones/addtramite');
                            }else
                                {
                                    /*
                                    * Se incluye el numero de la placa para registrarlo
                                    * en el tramite
                                    */
                                    $data['litr_placaVehiculo'] = $this->input->post('placa');
                                }
                        }

                        $respuestaProceso = $this->codegen_model->add('est_liquidartramites',$data);
                        if ($respuestaProceso->bandRegistroExitoso) 
                        {
                            $id = $respuestaProceso->idInsercion;
                            $this->session->set_flashdata('message','La liquidación se realizó con éxito');
                            $this->session->set_flashdata('accion', 'creado');
                            redirect(base_url().'index.php/liquidaciones/liquidartramites/'.$id);
                        }else 
                            {
                                $this->data['errormessage'] = 'No se pudo realizar la liquidación';
                            }
                    }else
                        {
                            $this->data['errormessage'] = 'No se pudo realizar la liquidación, ocurrio un error al procesar los datos del tramitador';
                        }

                }

              $this->template->set('title', 'Liquidar trámites');
              $this->data['style_sheets']= array(
                        'css/chosen.css' => 'screen'
                    );
              $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js'
                    );  
              $this->data['tramites']  = $this->codegen_model->getSelect('est_tramites','tram_id,tram_nombre');
              $this->template->load($this->config->item('admin_template'),'liquidaciones/liquidaciones_addtramite', $this->data);
             
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  }


  function consultardocumento()
  {
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {

              $tramitador= $this->codegen_model->get('tramitadores','nombre,telefono,email,direccion','nit = '.$this->input->post('documento'),1,NULL,true);
              if ($tramitador) {
                    echo json_encode(array(
                        'nombre' => $tramitador->nombre,
                        'telefono' => $tramitador->telefono,
                        'email' => $tramitador->email,
                        'direccion' => $tramitador->direccion,
                    ));
              } else {
                echo 0;
              }
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
          redirect(base_url().'index.php/users/login');
      }

  } 

 function tramites_datatable ()
  {
      if ($this->ion_auth->logged_in()) {
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar') ) { 
              
              $this->load->library('datatables');
              $this->datatables->select('l.litr_id,tramitadores.nit,tramitadores.nombre,tr.tram_nombre,l.litr_fechaliquidacion,l.litr_observaciones,el.eslo_nombre');
              $this->datatables->from('est_liquidartramites l');
              $this->datatables->join('est_tramites tr', 'tr.tram_id = l.litr_tramiteid', 'left');
              $this->datatables->join('con_estadoslocales el', 'el.eslo_id = l.litr_estadolocalid', 'left');
              $this->datatables->join('tramitadores', 'tramitadores.id = l.litr_tramitadorid', 'left');
              $this->datatables->add_column('edit', '-');
              echo $this->datatables->generate();

          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }           
  }

    function consultarLiquidacion()
    {
        $id = $this->uri->segment(3);

        $enviar = [
            'facturas' => $this->liquidaciones_model->getfacturas($id)
        ];

        echo json_encode($enviar, JSON_UNESCAPED_UNICODE);
    }

    function pagarContrato()
    {
        if ($this->ion_auth->logged_in()){

            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar'))
            {
                //$this->data['band_saldoestampillas'] = true;

                $this->template->load($this->config->item('admin_template'),'liquidaciones/pagarLiquidacion', $this->data);
            } else {
                redirect(base_url().'index.php/error_404');
            }

        } 
        else
        {
            redirect(base_url().'index.php/users/login');
        } 
    }

    function pagarContacto()
    {
        var_dump('le toca a david');
    }


  function liquidaciones_datatable ()
  { 
      if ($this->ion_auth->logged_in()) {
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar') ) { 
              
              $this->load->library('datatables');
              $this->datatables->select(
                  'c.cntr_id,c.cntr_numero,co.cont_nit,
                  co.cont_nombre,c.cntr_fecha_firma,c.cntr_objeto,
                  c.cntr_valor,el.eslo_nombre,c.pagado');
              $this->datatables->from('con_contratos c');
              $this->datatables->join('con_contratistas co', 'co.cont_id = c.cntr_contratistaid', 'left');
              $this->datatables->join('con_estadoslocales el', 'el.eslo_id = c.cntr_estadolocalid', 'left');
              $this->datatables->add_column('edit', '-');
              echo $this->datatables->generate();

          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }        
  }

    /*
    * Funcion que genera información para la datatable del listado de contratos
    * liquidados con regimen otros por AIU
    */
    public function liquidaciones_regimenotros()
    {
        if ($this->ion_auth->logged_in()) 
        {
            if($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/listarLiquidacionesIVADescuentos'))
            {
                $this->load->library('datatables');
                $this->datatables->select('c.cntr_id,c.cntr_numero,co.cont_nit,c.cntr_valor,c.cntr_iva_otros,li.liqu_id,li.liqu_fecha,li.liqu_soporteobjeto,li.liqu_auditado,li.liqu_ok,us.first_name,us.last_name,li.liqu_usuarioliquida');
                $this->datatables->from('con_contratos c');
                $this->datatables->join('est_liquidaciones li', 'c.cntr_id = li.liqu_contratoid', 'left');
                $this->datatables->join('con_contratistas co', 'co.cont_id = c.cntr_contratistaid', 'left');
                $this->datatables->join('users us', 'li.liqu_usuarioliquida = us.id', 'left');
                $this->datatables->where('li.liqu_regimenid = 6');
                $this->datatables->where('c.cntr_estadolocalid = 2');
                $this->datatables->add_column('edit', '-');
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

    /*
    * Funcion que retorna los valores de auditoria de liquidaciones
    * a partir de el id de la liquidacion
    */
    public function datosAuditoria()
    {
        if ($this->ion_auth->logged_in()) 
        {
            if($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/listarLiquidacionesIVADescuentos'))
            {
                /*
                * Extrae el id de la liquidación de la petición
                */
                $liquidacion = $this->input->post('liquId');

                /*
                * Realiza la consulta de los datos de auditoría
                */
                $where = 'where liqu_id = '.$liquidacion;
                $resultado = $this->codegen_model->getSelect('est_liquidaciones',"liqu_id,liqu_ok, liqu_usuario_audita, liqu_fecha_auditoria, liqu_observacionesaudit",$where);

                echo json_encode($resultado[0]);
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
    * Funcion que registra los datos de auditoria suministrados
    */
    public function registrarAuditoria()
    {
        if ($this->ion_auth->logged_in()) 
        {
            if($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/listarLiquidacionesIVADescuentos'))
            {
                /*
                * Extrae los datos a almacenar
                */
                $usuario = $this->ion_auth->user()->row();
                $liquidacion = $this->input->post('idLiquidacion');
                $obs_auditoria = $this->input->post('obsAuditoriaForm');
                $estado_liquidacion = $this->input->post('ok_liquidacion');

                /*
                * Establece los datos de modificación
                */
                $datosModificacion = array(
                        'liqu_auditado' => 1,
                        'liqu_ok' => $estado_liquidacion,
                        'liqu_usuario_audita' => $usuario->id,
                        'liqu_fecha_auditoria' => date('Y-m-d H:i:s'),
                        'liqu_observacionesaudit' => $obs_auditoria
                        );

                /*
                * Se realiza la query de modificación
                */
                $this->codegen_model->edit('est_liquidaciones', $datosModificacion, 'liqu_id', $liquidacion);

                echo json_encode(array('mensaje' => 'Se registró la información de auditoria exitosamente!', 'datos' => $datosModificacion, 'liquidacion' => $liquidacion));
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
* Funcion que renderiza la vista de consulta de liquidaciones
* Mike Ortiz
*/

function consultar()
  {
      if ($this->ion_auth->logged_in()){

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/consultar')){

              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
                            
              //template data
              $this->template->set('title', 'Listado de Liquidaciones');
              $this->data['style_sheets']= array(
                            'css/chosen.css' => 'screen',
                            'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen',
                            'css/plugins/bootstrap/fileinput.css' => 'screen',
                            'css/plugins/bootstrap/bootstrap-datetimepicker.css' => 'screen',
                            'css/applicationStyles.css' => 'screen'
                        );
              $this->data['javascripts']= array(
                        'js/chosen.jquery.min.js',
                        'js/jquery.dataTables.min.js',
                        'js/plugins/dataTables/dataTables.bootstrap.js',
                        'js/jquery.dataTables.defaults.js',
                        'js/plugins/dataTables/jquery.dataTables.columnFilter.js',
                        'js/accounting.min.js',
                        'js/plugins/bootstrap/moment.js',
                        'js/plugins/bootstrap/bootstrap-datetimepicker.js',
                        'js/plugins/bootstrap/fileinput.min.js',
                        'js/plugins/bootstrap/bootstrap-switch.min.js'                        
                       );

              /*
              * Crea el vector con los tipos de acto para filtrar
              */
              $this->data['tipos_acto'] = array('1' => 'Contrato', '2' => 'Tramite');

              /*
              * Extrae el listado de tipos de estampilla
              */
              $tiposEstampilla = $this->codegen_model->getSelect('est_estampillas',"estm_id,estm_nombre");
              $vTiposEst = array();
              if(count($tiposEstampilla) > 0)
              {
                  foreach($tiposEstampilla as $tipoE)
                  {
                      $vTiposEst[$tipoE->estm_id] = $tipoE->estm_nombre;
                  }
              }
              $this->data['estampillas'] = $vTiposEst;

              /*
               * Extrae el listado de tipos de actos liquidados para realizar
               * la consulta
               */
              $tiposContrato = $this->codegen_model->getSelect('con_tiposcontratos',"tico_id,tico_nombre");
              $tiposTramite = $this->codegen_model->getSelect('est_tramites',"tram_id,tram_nombre");

              $vSubTiposActo = array();
              if(count($tiposContrato) > 0)
              {
                  foreach($tiposContrato as $tipoA)
                  {
                      $vSubTiposActo['c_'.$tipoA->tico_id] = $tipoA->tico_nombre.' ( Contrato )';
                  }
              }

              if(count($tiposTramite) > 0)
              {
                  foreach($tiposTramite as $tipoA)
                  {
                      $vSubTiposActo['t_'.$tipoA->tram_id] = $tipoA->tram_nombre.' ( Tramite )';
                  }
              }

              $this->data['subtipos_acto'] = $vSubTiposActo;

              /*
               * Extrae los contratistas creados para filtrar por contratos
               */
              $contratantes = $this->codegen_model->getSelect('con_contratantes',"id,nombre,nit");
              $contratistas = $this->codegen_model->getSelect('con_contratistas',"cont_id,cont_nombre,cont_nit");
              $tramitadores = $this->codegen_model->getSelect('tramitadores','id,nit,nombre','','','GROUP BY nit');

              $vecContribuyentes = array();
              if(count($contratistas) > 0)
              {
                  foreach($contratistas as $contratista)
                  {
                      $vecContribuyentes['c_'.$contratista->cont_nit] = $contratista->cont_nit.' - '.$contratista->cont_nombre.' ( Contratista )';
                  }
              }

              if(count($tramitadores) > 0)
              {
                  foreach($tramitadores as $tramitador)
                  {
                      $vecContribuyentes['t_'.$tramitador->nit] = $tramitador->nit.' - '.$tramitador->nombre.' ( Tramitador )';
                  }
              }

              $vecContratantes = array();
              if(count($contratantes) > 0)
              {
                  foreach($contratantes as $contratante)
                  {
                      $vecContratantes[$contratante->id] = $contratante->nit.' - '.$contratante->nombre;
                  }
              }

              $this->data['contribuyentes'] = $vecContribuyentes;
              $this->data['contratantes']   = $vecContratantes;
              $this->data['municipios']  = $this->codegen_model->getSelect('par_municipios','muni_id,muni_nombre', 'WHERE muni_departamentoid = 6');

              $this->template->load($this->config->item('admin_template'),'liquidaciones/liquidaciones_consultar', $this->data);
              
          } else {
              redirect(base_url().'index.php/error_404');
          }

      } else {
              redirect(base_url().'index.php/users/login');
      }

  }



  function  consultas_dataTable ()
  { 
      if ($this->ion_auth->logged_in()) {
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/consultar') ) {
              

              /**
              * Valida si es administrador o Usuario conciliación para mostrar todas las impresiones
              */
              $usuario = $this->ion_auth->user()->row();
              if($this->ion_auth->is_admin() || $usuario->perfilid == 5)
              {
                  $where = '';
                  $join = 'join est_papeles p on p.pape_id = i.impr_papelid';  
              }else
                  {
                      //Extrae los id de las facturas para las que se han hecho impresiones
                      $where = 'where pape_usuario = '.$usuario->id;
                      $join = 'join est_papeles p on p.pape_id = i.impr_papelid';
                  }              

              $facturas = $this->codegen_model->getSelect('est_impresiones i',"i.impr_facturaid",$where,$join);
         
              //se extrae el vector con los id de las facturas
              $idFacturas = '(';
              foreach ($facturas as $factura) 
              {
                  $idFacturas .= $factura->impr_facturaid.',';
              }  
              $idFacturas .= '0)';
              $where = 'where fact_id in '.$idFacturas;                            
              
              //Extrae los id de las liquidaciones
              $liquidaciones = $this->codegen_model->getSelect('est_facturas f',"distinct f.fact_liquidacionid",$where);

              //se extrae el vector con los id de las liquidaciones
              $idLiquidaciones = '(';
              foreach ($liquidaciones as $liquidacion) 
              {
                  $idLiquidaciones .= $liquidacion->fact_liquidacionid.',';
              }  
              $idLiquidaciones .= '0)';
              $whereIn = 'l.liqu_id in '.$idLiquidaciones;

              /* 
              p.pago_fecha,
              */
              $this->load->library('datatables');
              $this->datatables->select('
                  l.liqu_id,l.liqu_tipocontrato,
                  l.liqu_nit,
                  l.liqu_nombrecontratista,
                  l.liqu_valortotal,
                  l.liqu_fecha,
                  l.liqu_id AS pago_fecha,
                  f.fact_valor,
                  f.fact_nombre
              ');
              $this->datatables->from('est_facturas f');
              $this->datatables->join('est_liquidaciones l', 'l.liqu_id = f.fact_liquidacionid', 'left');
            //   $this->datatables->join('est_pagos p', 'p.pago_facturaid = f.fact_id', 'left');
            //   $this->datatables->where($whereIn);

                       /*
               * Extrae el listado de tipos de actos liquidados para realizar
               * la consulta
               */
              $tiposContrato = $this->codegen_model->getSelect('con_tiposcontratos',"tico_id,tico_nombre");
              $tiposTramite = $this->codegen_model->getSelect('est_tramites',"tram_id,tram_nombre");    

              $this->datatables->add_column('facturas','edess');              
              echo $this->datatables->generate();

          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }        
  }


  function procesarConsecutivos()
  {
    if ($this->ion_auth->logged_in())
    {
            //verifica que el usuario que llama el metodo
            //tenga perfil de liquidador
            $usuarioLogueado = $this->ion_auth->user()->row();

            if ($usuarioLogueado->perfilid == 4)
            {
                /*
                * Valida que el usuario tenga papeleria asignada
                */          
                $validacionPapeleriaAsignada = $this->codegen_model->getSelect('est_papeles',
                    'pape_codigoinicial, pape_codigofinal', ' where pape_usuario = '.$usuarioLogueado->id);
               
                if($validacionPapeleriaAsignada)
                {
                    if ($this->uri->segment(3)=='')
                    {
                        redirect(base_url().'index.php/error_404');
                  
                    }else 
                       {
                            /*
                            * Variable que determina si se debe trabajar con papelería de contingencia
                            */
                            $objHelper = new HelperGeneral;
                            $contingencia = $objHelper->estanActivosRotulosContingencia();

                            $idFactura = $this->uri->segment(3);

                            $codigo='00000000';

                            $ObjetoFactura = $this->liquidaciones_model->getfacturaIndividual($idFactura);

                            $datosIngreso = Liquidaciones::determinarSiguienteRotulo($usuarioLogueado);
                           
                            /*
                            * Valida que el nuevo ingreso no sea la palabra NO
                            * que determina que el usuario no tiene papeleria disponible para imprimir
                            */
                            if($datosIngreso['nuevoIngreso'] != 'NO')
                            {
                                $nuevoingreso = $datosIngreso['nuevoIngreso'];
                                $idRangoPapel = $datosIngreso['rangoPapel'];

                                /*
                                * verifica si no se encuentra asignada papeleria a la factura para la que se solicita
                                * la impresion de la estampilla en la tabla de impresiones, para crear el registro de la impresion
                                */
                                $impresiones = $this->codegen_model->get('est_impresiones','impr_id,impr_estado,impr_codigopapel','impr_facturaid = '.$ObjetoFactura[0]->fact_id,1,NULL,true);
                                if(!$impresiones)
                                {
                                    do{
  
                                        $c = 0;
                                        $m = 0;           
                                        $codificacion = $this->generadorIdEstampilla($ObjetoFactura[0]->fact_estampillaid, $ObjetoFactura[0]->liqu_nit);                                               

                                        $codes = $this->codegen_model->getSelect('est_impresiones','impr_estampillaid');

                                        foreach ($codes as $code) 
                                        {
                                            if($codificacion != $code->impr_estampillaid)
                                            {
                                               $c++;          
                                            }
                                            $m++;
                                        }                                                                                                                               

                                    }while($c != $m);
                                    
                                    /*
                                    * Valida que exista un contrato en estado 1 (activo | con estampillas por imprimir)
                                    * para registrar la impresión
                                    */
                                    $where = 'WHERE conpap_estado = 1';
                                    $vContratoE = $this->codegen_model->getSelect('est_contratopapeles',"conpap_id,conpap_cantidad", $where);

                                    if(count($vContratoE) == 0)
                                    {
                                        $this->session->set_flashdata('errormessage', 'No Existe un Contrato de Estampillas Activo, Debe Solicitar el Registro de un Contrato para Realizar la Impresión!');
                                        redirect(base_url().'index.php/liquidaciones/liquidar');
                                    }

                                    /*
                                    * Valida que el contrato aún tenga estampillas a imprimir 
                                    * antes de registrar la impresion
                                    */
                                    $where = 'impr_estado = 1 AND impr_contratopapel = '.$vContratoE[0]->conpap_id;
                                    $resultado = $this->codegen_model->countwhere('est_impresiones',$where);                                    
                                    
                                    if($vContratoE[0]->conpap_cantidad <= $resultado->contador)
                                    {
                                        /*
                                        * Si ya se alcanzó la cantidad maxima se modifica el estado
                                        * del contrato de estampillas a completado (2)
                                        * y la cantidad de estampillas impresas encontradas
                                        */
                                        $this->codegen_model->edit('est_contratopapeles',
                                            ['conpap_estado' => 2,
                                            'conpap_impresos' => $resultado->contador],
                                            'conpap_id', $vContratoE[0]->conpap_id);

                                        $this->session->set_flashdata('errormessage', 'No Existe un Contrato de Estampillas Activo, Debe Solicitar el Registro de un Contrato para Realizar la Impresión!');
                                        redirect(base_url().'index.php/liquidaciones/liquidar');                                        
                                    }                                    

                                    $data = array(
                                    'impr_codigopapel' => str_pad($nuevoingreso, 4, '0', STR_PAD_LEFT),
                                    'impr_papelid' => $idRangoPapel,
                                    'impr_facturaid' => $ObjetoFactura[0]->fact_id,
                                    'impr_observaciones' => 'Correcta',
                                    'impr_fecha' => date('Y-m-d H:i:s'),
                                    'impr_usuario' => $usuarioLogueado->id,
                                    'impr_codigo' => $codigo,
                                    'impr_estampillaid' => $codificacion,
                                    'impr_estadoContintencia' => $contingencia,
                                    'impr_estado' => '1',
                                    'impr_contratopapel' => $vContratoE[0]->conpap_id
                                    );
    
                                    //extrae la cantidad actual impresa para el rango
                                    //de papeleria de donde se sacará el consecutivo
                                    //luego aumenta ese valor y lo actualiza en la bd
                                    $cantidadImpresa = $this->codegen_model->getSelect('est_papeles','pape_imprimidos',
                                    'where pape_usuario = '.$usuarioLogueado->id
                                    .' AND pape_id = '.$idRangoPapel);
                                 
                                    $cantidadNeta=(int)$cantidadImpresa[0]->pape_imprimidos;
                                                            
                                    $this->codegen_model->edit('est_papeles',
                                    ['pape_imprimidos'=>$cantidadNeta+1],
                                    'pape_id', $idRangoPapel);
                              
                                    $respuestaProceso = $this->codegen_model->add('est_impresiones',$data);

                                    /*
                                    * Valida que el contrato aún tenga estampillas a imprimir
                                    * despues de registrar la impresion
                                    */
                                    $where = 'impr_estado = 1 AND impr_contratopapel = '.$vContratoE[0]->conpap_id;
                                    $resultado = $this->codegen_model->countwhere('est_impresiones',$where);                                    
                                    
                                    if($vContratoE[0]->conpap_cantidad <= $resultado->contador)
                                    {
                                        /*
                                        * Si ya se alcanzó la cantidad maxima se modifica el estado
                                        * del contrato de estampillas a completado (2)
                                        * y actualiza la cantidad de estampillas impresas
                                        */
                                        $this->codegen_model->edit('est_contratopapeles',
                                            ['conpap_estado' => 2,
                                            'conpap_impresos' => $resultado->contador],
                                            'conpap_id', $vContratoE[0]->conpap_id);
                                    }else
                                        {
                                            /*
                                            * Si no se ha alcanzado la cantidad maxima de estampillas
                                            * para el contrato solamente actualiza la cantidad 
                                            * de estampillas impresas
                                            */
                                            $this->codegen_model->edit('est_contratopapeles',
                                                ['conpap_impresos' => $resultado->contador],
                                                'conpap_id', $vContratoE[0]->conpap_id);          
                                        }

                                    /*
                                    * Extrae la cantidad impresa de estampillas
                                    * de el rango para el contrato
                                    */
                                    $where = 'impr_estado = 1 AND impr_contratopapel = '.$vContratoE[0]->conpap_id
                                        .' AND impr_papelid = '.$idRangoPapel;
                                    $resultado = $this->codegen_model->countwhere('est_impresiones',$where);

                                    /*
                                    * Valida si ya está creado el rango como detalle del contrato
                                    * para actualizar la cantidad impresa de ese rango
                                    * si no está creado lo crea con cantidad 1
                                    */
                                    $where = 'WHERE detpap_rango = '. $idRangoPapel .' AND detpap_contrato = '.$vContratoE[0]->conpap_id;
                                    $vRangoPap = $this->codegen_model->getSelect('est_detconpap',"detpap_id", $where);
                                    if(count($vRangoPap) > 0)
                                    {
                                        $this->codegen_model->edit('est_detconpap',
                                            ['detpap_cantidad' => $resultado->contador],
                                            'detpap_id', $vRangoPap[0]->detpap_id);
                                    }else
                                        {
                                            $datos = array(
                                                'detpap_contrato' => $vContratoE[0]->conpap_id,
                                                'detpap_rango' => $idRangoPapel,
                                                'detpap_cantidad' => $resultado->contador
                                                );
                                            
                                            $respuestaProceso = $this->codegen_model->add('est_detconpap',$datos);
                                        }

                                    redirect(base_url().'index.php/generarpdf/generar_estampilla/'.$idFactura);                                     

                                }else
                                    {
                                        $this->session->set_flashdata('errormessage', '<strong>Error!</strong> Ya se ha impreso la Estampilla No.'.$impresiones->impr_codigopapel.' !');
                                        redirect(base_url().'index.php/liquidaciones/liquidar'); 
                                    }                                
                            }else
                                {   
                                    $this->session->set_flashdata('errormessage', '<strong>Error!</strong> Usted no tiene papeleria disponible para imprimir!');
                                        redirect(base_url().'index.php/liquidaciones/liquidar'); 
                                }
                        }
                }else
                    {
                        $this->session->set_flashdata('errormessage', '<strong>Error!</strong> No se puede imprimir, usted no tiene asignada papeleria!');
                        redirect(base_url().'index.php/liquidaciones/liquidar');  
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

  
   
  //Funcion que genera el codigo que identifica la estampilla impresa
  function generadorIdEstampilla($tipoEstampilla, $nit)
  {
        if ($this->ion_auth->logged_in()) {

            //verifica que el usuario que llama el metodo
            //tenga perfil de liquidador
            $usuarioLogueado=$this->ion_auth->user()->row();

            if ($usuarioLogueado->perfilid==4)
            { 
                 mt_srand(strtotime(date('H:i:s')));
                 $alea = mt_rand();
                 $codificado = '73-'.$tipoEstampilla.substr($nit, -5).date('d').date('m').date('y').substr($alea, -4);
                 
                 return $codificado;

            } else {
                redirect(base_url().'index.php/error_404');
            }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }        
  }


/**
* Funcion que extrae las estampillas y sus respectivos valores
* para la vista de consultar liquidaciones
* Mike Ortiz
*/

  function extraerFacturas()
  {
        $liquidacion = $this->input->post('id');
        $where = 'where fact_liquidacionid = '.$liquidacion;
        $resultado = $this->codegen_model->getSelect('est_facturas',"fact_nombre, fact_valor, fact_porcentaje",$where);
       
        $vector_facturas['estampillas'] = '<table class="table table-bordered">'
           .'<tr><th>Estampilla</th><th>Porcentaje</th><th>Valor</th></tr>';

       foreach ($resultado as $value) 
       {
            $vector_facturas['estampillas'] .= '<tr>'
                    .'<td>'. $value->fact_nombre .'</td>'
                    .'<td>(%'. $value->fact_porcentaje .')</td>'
                    .'<td>($'. number_format($value->fact_valor,0,',','.') .')</td>'
                .'</tr>';
       }
       $vector_facturas['estampillas'] .= '</table>';
    
       echo json_encode($vector_facturas); 
  }


/**
* Funcion que ordena la renderizacion o no del PDF
* de las liquidaciones de la fecha especificada
* Mike Ortiz
*/
function renderizarDetalleRangoPDF()
{
    if ($this->ion_auth->logged_in()) 
    {
        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/consultar') ) 
        {
            $resultadosFiltros = Liquidaciones::extraerRegistrosDetalleImpresiones($_GET, true);

            /*
            * Valida si hubo resultados para generar el pdf
            */
            if(count($resultadosFiltros['vec_liquidaciones']) <= 0)
            {
                $this->session->set_flashdata('errormessage', 'La fecha elegida no presenta registros!'); 
                redirect(base_url().'index.php/liquidaciones/consultar');
            }

            $datos['fecha']            = $resultadosFiltros['fecha'];
            $datos['liquidaciones']    = $resultadosFiltros['vec_liquidaciones'];
            $datos['totalRecaudado']   = $resultadosFiltros['total_recaudado'];
            $datos['totalEstampillas'] = $resultadosFiltros['cant_total_estampillas'];
            $datos['vec_filtros']      = $resultadosFiltros['vec_filtros'];

            //Creación del PDF
            $this->load->library("Pdf");                  
            $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->setPageOrientation('l');

            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('turrisystem');
            $pdf->SetTitle('Listado de Impresiones');
            $pdf->SetSubject('Gobernación de Boyacá');
            $pdf->SetKeywords('estampillas,gobernación');
            $pdf->SetPrintHeader(false);
            $pdf->SetPrintFooter(false);
            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->setPageUnit('mm');
            $pdf->SetMargins(10, 5, 20, true);
            $pdf->SetHeaderMargin(0);
            $pdf->SetFooterMargin(0);
      
            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, 2);

            // set some language-dependent strings (optional)
            if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                require_once(dirname(__FILE__).'/lang/eng.php');
                $pdf->setLanguageArray($l);
            }
               
            // ---------------------------------------------------------
            
            // set font
            $pdf->SetFont('helvetica', '', 9);
            $pdf->AddPage();
            $html = $this->load->view('generarpdf/generarpdf_impresiones', $datos, TRUE);  
                
            $pdf->writeHTML($html, true, false, true, false, '');
           

            // ---------------------------------------------------------
            //para evitar el error de que se ha impreso algo antes de enviar
            //el PDF 
            ob_end_clean();
            //Close and output PDF document

            /*
            * Establece el nombre del archivo
            */
            $pdf->Output('Impresiones_'. str_replace(' ','_',$datos['fecha']) .'.pdf', 'I');
        } else 
            {
                redirect(base_url().'index.php/error_404');
            }
               
    }else
        {
            redirect(base_url().'index.php/users/login');
        }
}


    /**
    *  Función de apoyo que realiza la consulta para renderizar el detalle
    * de impresiones según los filtros suministrados
    */
    function extraerRegistrosDetalleImpresiones($vectorGet, $bandDetallado = false)
    {
        header("Expires: 0");
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $vecFiltrosAplicados = array(
            'tipo_fecha' => '',
            'tipo_estampilla' => '',
            'tipo_acto' => '',
            'tipo_contrato' => '',
            'tipo_tramite' => '',
            'contribuyente' => '',
            'contratante' => '',
            'municipio' => '',
        );

        $strTipoFechaFiltrada = "";

        $bandFiltroFechaLiquidacion = true;
        $bandFiltroFechaImpresion = true;
        $bandFiltroFechaPago = true;

        /*
        * Valida cuales fechas llegan
        */
        $fecha_inicial_impr = "";
        $fecha_final_impr   = "";
        $fecha_inicial_pago = "";
        $fecha_final_pago   = "";
        $fecha_inicial_liqu = "";
        $fecha_final_liqu   = "";
        if(isset($vectorGet['fecha_I_impr']) && !empty($vectorGet['fecha_I_impr']))
        {
            $fecha_inicial_impr = $vectorGet['fecha_I_impr'];
        }else
            {
                $bandFiltroFechaImpresion = false;
            }

        if(isset($vectorGet['fecha_F_impr']) && !empty($vectorGet['fecha_F_impr']))
        {
            $fecha_final_impr = $vectorGet['fecha_F_impr'];
        }else
            {
                $bandFiltroFechaImpresion = false;
            }

        if(isset($vectorGet['fecha_I_pago']) && !empty($vectorGet['fecha_I_pago']))
        {
            $fecha_inicial_pago = $vectorGet['fecha_I_pago'];
        }else
            {
                $bandFiltroFechaPago = false;
            }

        if(isset($vectorGet['fecha_F_pago']) && !empty($vectorGet['fecha_F_pago']))
        {
            $fecha_final_pago = $vectorGet['fecha_F_pago'];
        }else
            {
                $bandFiltroFechaPago = false;
            }
        
        if(isset($vectorGet['fecha_I_liqu']) && !empty($vectorGet['fecha_I_liqu']))
        {
            $fecha_inicial_liqu = $vectorGet['fecha_I_liqu'];
        }else
            {
                $bandFiltroFechaLiquidacion = false;
            }

        if(isset($vectorGet['fecha_F_liqu']) && !empty($vectorGet['fecha_F_liqu']))
        {
            $fecha_final_liqu = $vectorGet['fecha_F_liqu'];
        }else
            {
                $bandFiltroFechaLiquidacion = false;
            }

        /*
        * Valida que lleguen fechas solamente para un tipo de filtro
        * impresión o pago
        */
        if(($bandFiltroFechaImpresion && $bandFiltroFechaPago) || ($bandFiltroFechaImpresion && $bandFiltroFechaLiquidacion) || ($bandFiltroFechaPago && $bandFiltroFechaLiquidacion) || ($bandFiltroFechaPago && $bandFiltroFechaLiquidacion && $bandFiltroFechaImpresion))
        {
            $this->session->set_flashdata('errormessage', 'Debe Elegir solamente un Rango de Fechas para el informe (Fecha de pago o Fecha de Impresi&oacute;n o Fecha de Liquidaci&oacute;n)!');
            redirect(base_url().'index.php/liquidaciones/consultar');
        }

        /*
        * Valida que lleguen fechas para por lo menos un tipo de filtro
        */
        if(!$bandFiltroFechaImpresion && !$bandFiltroFechaPago && !$bandFiltroFechaLiquidacion)
        {
            $this->session->set_flashdata('errormessage', 'Debe Elegir un Rango de Fechas valido para el informe (Fecha de pago o Fecha de Impresi&oacute;n o Fecha de Liquidaci&oacute;n)!');
            redirect(base_url().'index.php/liquidaciones/consultar');
        }
        
        $tipoEst       = $vectorGet['est'];
        $tipoActo      = $vectorGet['acto'];
        $subTipoActo   = $vectorGet['subtipo'];
        $contribuyente = $vectorGet['contribuyente'];
        $contratante   = $vectorGet['contratante'];
        $municipio     = $vectorGet['municipio'];

        /*
         * Extrae el posible subtipo de acto (tipo de tramite o tipo de contrato)
         */
        $bandValidarSubtipoActo = false;
        if($subTipoActo != '0')
        {
            $bandValidarSubtipoActo = true;
            if(preg_match('/^c_([0-9]+)$/',$subTipoActo,$coincidencias))
            {
                $id_subtipoacto = $coincidencias[1];
                $t_acto = 'contrato';
            }elseif(preg_match('/^t_([0-9]+)$/',$subTipoActo,$coincidencias))
            {
                $id_subtipoacto = $coincidencias[1];
                $t_acto = 'tramite';
            }
        }

        /*
        * Construye la query inicial
        */
        $campos = ' liq.liqu_id,'
            .' if(liq.liqu_contratoid = 0,"N/A", concat("Contrato"," ",liq.liqu_tipocontrato)) as liqu_tipocontrato, '
            .' if(liq.liqu_contratoid = 0,"tramite","contrato") as tipoacto,'
            .' if(liq.liqu_numero = "","N/A", liq. liqu_numero) as numActo,'
            .' liq.liqu_nombrecontratista,'
            .' liq.liqu_nit,'
            .' liq.liqu_fecha,'
            .' liq.liqu_valorsiniva as valorActo,'
            .' fac.`fact_id`,'
            .' fac.`fact_nombre`,'
            .' fac.fact_valor,'
            .' fac.fact_estampillaid,'
            .' "" AS impr_codigopapel,'
            .' "" AS impr_fecha,'
            .' pag.pago_fecha,'
            .' concat(u.first_name," ",u.last_name) as liquidador';
        
        $join = ' INNER JOIN `est_liquidaciones` liq ON liq.`liqu_id` = fac.`fact_liquidacionid`'
            .' LEFT JOIN est_pagos pag ON pag.pago_facturaid = fac.`fact_id`'
            .' INNER JOIN users u ON u.id = liq.liqu_usuarioliquida';

        /*
        * Se Validan los valores que llegan para construir el where
        */
        $where = 'WHERE 1 = 1 ';

        if($bandFiltroFechaImpresion)
        {
            // $fecha_inicial = $fecha_inicial_impr;
            // $fecha_final   = $fecha_final_impr;
            // $strTipoFechaFiltrada = "FECHA DE IMPRESION";
            // $where .= ' AND date_format(imp.impr_fecha,"%Y-%m-%d") BETWEEN "'.$fecha_inicial_impr.'" AND "'.$fecha_final_impr.'"';
        }

        if($bandFiltroFechaPago)
        {
            $fecha_inicial = $fecha_inicial_pago;
            $fecha_final   = $fecha_final_pago;
            $strTipoFechaFiltrada = "FECHA DE PAGO";
            $where .= ' AND date_format(pag.pago_fecha,"%Y-%m-%d") BETWEEN "'.$fecha_inicial_pago.'" AND "'.$fecha_final_pago.'"';
        }

        if($bandFiltroFechaLiquidacion)
        {
            $fecha_inicial = $fecha_inicial_liqu;
            $fecha_final   = $fecha_final_liqu;
            $strTipoFechaFiltrada = "FECHA DE LIQUIDACION";
            $where .= ' AND date_format(liq.liqu_fecha,"%Y-%m-%d") BETWEEN "'.$fecha_inicial_liqu.'" AND "'.$fecha_final_liqu.'"';
        }

        $vecFiltrosAplicados['tipo_fecha'] = ucfirst(strtolower($strTipoFechaFiltrada));

        if($tipoEst != '0')
        {
            $where = Liquidaciones::concatenarWhere($where);
            $where .= ' fac.`fact_estampillaid` = '.$tipoEst;

            $vecFiltrosAplicados['tipo_estampilla'] = $tipoEst;
        }

        if($contribuyente != '0')
        {
            $where = Liquidaciones::concatenarWhere($where);
            preg_match('/^[t|c]_([0-9\-]+)$/',$contribuyente,$coincidencia);
            $where .= ' liq.liqu_nit = "'. $coincidencia[1] .'" ';

            $vecFiltrosAplicados['contribuyente'] = $coincidencia[1];
        }

        if($tipoActo != '0')
        {
            if($tipoActo == '1') //Valida si se solicitan solo contratos
            {
                $where = Liquidaciones::concatenarWhere($where);
                $where .= ' liq.liqu_contratoid <> 0';

                $vecFiltrosAplicados['tipo_acto'] = 'Contrato';
            }elseif($tipoActo == '2') //Valida si se solicitan solo tramites
                {
                    $where = Liquidaciones::concatenarWhere($where);
                    $where .= ' liq.liqu_contratoid = 0';

                    $vecFiltrosAplicados['tipo_acto'] = 'Tramite';
                }
        }

        if($bandValidarSubtipoActo)
        {
            if($t_acto == 'contrato')
            {
                $where = Liquidaciones::concatenarWhere($where);
                $where .= ' liq.liqu_contratoid <> 0';

                $join .= ' INNER JOIN `con_contratos` con ON con.`cntr_id` = liq.`liqu_contratoid`';

                $where = Liquidaciones::concatenarWhere($where);
                $where .= ' con.`cntr_tipocontratoid` = '.$id_subtipoacto;

                $vecFiltrosAplicados['tipo_contrato'] = $id_subtipoacto;
            }
    
            if($t_acto == 'tramite')
            {
                $where = Liquidaciones::concatenarWhere($where);
                $where .= ' liq.liqu_contratoid = 0';

                $join .= ' INNER JOIN `est_liquidartramites` liqt ON liqt.`litr_id` = liq.`liqu_tramiteid`';

                $where = Liquidaciones::concatenarWhere($where);
                $where .= ' liqt.`litr_tramiteid` = '.$id_subtipoacto;

                $vecFiltrosAplicados['tipo_tramite'] = $id_subtipoacto;
            }
        }

        if($contratante != '0')
        {
            $where = Liquidaciones::concatenarWhere($where);
            $where .= ' liq.liqu_contratoid <> 0';

            $join .= ' INNER JOIN `con_contratos` conn ON conn.`cntr_id` = liq.`liqu_contratoid`';

            $where = Liquidaciones::concatenarWhere($where);
            $where .= ' conn.`cntr_contratanteid` = ' . $contratante;

            $vecFiltrosAplicados['contratante'] = $contratante;
        }

        if($municipio)
        {
            $where = Liquidaciones::concatenarWhere($where);
            $where .= ' liq.liqu_contratoid <> 0 ';

            $join .= ' INNER JOIN `con_contratos` connt ON connt.`cntr_id` = liq.`liqu_contratoid` ';

            $where = Liquidaciones::concatenarWhere($where);
            $where .= ' connt.`cntr_municipio_origen` = '. $municipio;

            $vecFiltrosAplicados['municipio'] = $municipio;
        }

        /*
        * Si no se pidió detallar se agrupa por tipo de estampilla 
        * para extraer los valores totales
        */
        $groupBy = '';
        if(!$bandDetallado)
        {
            $campos = 'fac.fact_nombre as nombre_estampilla,'
                .' sum(fac.fact_valor) as valor_estampilla,'
                .' count(fac.fact_id) as cant_estampilla';
            
            $groupBy = 'GROUP BY fac.fact_estampillaid';
        }

        /*
        * Se inicializan las variables de respuesta
        */
        $fecha = '';
        $vEstampillas      = array();
        $liquidaciones     = array();
        $total_recaudado   = 0;
        $cant_agrupaciones = 0;
        $cant_total_estampillas = 0;
        
        /*
        * Si se pidió detallar se realizan 2 consultas para tramites
        * y contratos respectivamente y luego se extraen los detalles
        */
        if($bandDetallado)
        {
            /*
            * Datos para tramites
            */
            $joinTramites = $join
                .' INNER JOIN `est_liquidartramites` liqt2 ON liqt2.`litr_id` = liq.`liqu_tramiteid`'
                .' INNER JOIN `est_tramites` ttra ON ttra.`tram_id` = liqt2.`litr_tramiteid`';

            $camposTramites = $campos
                .', ttra.tram_nombre as liqu_tipocontrato,'
                .' concat("tipotramite_",liqt2.litr_tramiteid) as subtipoacto';

            $whereTramites = $where. ' AND liq.liqu_contratoid = 0';
            $liquidacionesTramites = $this->codegen_model->getSelect('est_facturas fac',$camposTramites,$whereTramites,$joinTramites, $groupBy);

            /*
            * Datos para contratos
            */
            $join .= ' INNER JOIN con_contratos con2 ON con2.cntr_id = liq.liqu_contratoid';
            $campos .= ', concat("tipocontrato_",con2.cntr_tipocontratoid) as subtipoacto';
            $where .= ' AND liq.liqu_contratoid <> 0';
            $liquidacionesContratos = $this->codegen_model->getSelect('est_facturas fac',$campos,$where,$join, $groupBy);

            /*
            * Datos consolidados
            */
            $liquidaciones = array_merge($liquidacionesTramites, $liquidacionesContratos);

            $resultadosDetallados = $this->extraerDetallesLiquidaciones($liquidaciones, $vectorGet);

            $vEstampillas       = $resultadosDetallados->vEstampillas;
            $liquidaciones      = $resultadosDetallados->vLiquidaciones;
            $total_recaudado    = $resultadosDetallados->total_recaudado;
            $cant_agrupaciones  = $resultadosDetallados->cant_agrupaciones;
            $cant_total_estampillas  = $resultadosDetallados->cant_total_estampillas;
        }

        /*
        * Si no se pidió detallar se realiza una sola consulta
        * de tramites y contratos y se calculan los totales
        */
        if(!$bandDetallado)
        {
            $liquidaciones = $this->codegen_model->getSelect('est_facturas fac',$campos,$where,$join, $groupBy);

            $vEstampillas = $liquidaciones;
            $total_recaudado = 0;
            $cant_total_estampillas = 0;
            foreach($liquidaciones as $objAgrupadoEstampilla)
            {
                $total_recaudado += (double)$objAgrupadoEstampilla->valor_estampilla;
                $cant_total_estampillas += (int)$objAgrupadoEstampilla->cant_estampilla;
            }
        }

        /*
        * Valida que fecha llega a la vista para preparar la leyenda
        */
        if(isset($fechaUnica) && $fechaUnica != '')
        {
            $fecha = Liquidaciones::fechaEnLetras($fechaUnica);
            $fecha_nombre_archivo = 'fecha_'.$fechaUnica;
        }else
            {
                $fecha = 'PERIODO COMPRENDIDO ENTRE LAS FECHAS '. Liquidaciones::fechaEnLetras($fecha_inicial)
                    .' Y '. Liquidaciones::fechaEnLetras($fecha_final)
                    .', FILTRO REALIZADO POR '. $strTipoFechaFiltrada;
                $fecha_nombre_archivo = 'entre_fechas_'.$fecha_inicial.'_y_'. $fecha_final;
            }

        $vecFiltrosAplicados = $this->extraerInformacionFiltrosAplicados($vecFiltrosAplicados);

        return array(
            'vec_filtros'            => $vecFiltrosAplicados,
            'vec_liquidaciones'      => $liquidaciones,
            'vec_estampillas'        => $vEstampillas,
            'cant_agrupacion'        => $cant_agrupaciones,
            'cant_total_estampillas' => $cant_total_estampillas,
            'total_recaudado'        => $total_recaudado,
            'fecha'                  => $fecha,
            'fecha_nombre_archivo'   => $fecha_nombre_archivo,
        );
    }

    /*
    * Función de apoyo para extraer los detalles de liquidaciones de actos
    */
    function extraerDetallesLiquidaciones($vecLiquidaciones, $vectorGet)
    {
        /*
        * Inicializa la variable para la respuesta
        */
        $objResponse = (object)array(
            'vEstampillas'   => array(),
            'vLiquidaciones' => array(),
            'cant_total_estampillas' => 0,
            'total_recaudado'   => 0,
            'cant_agrupaciones' => 0
        );
        
        /*
        * Vector que se utiliza solamente para garantizar
        * que no se repitan las facturas en una liquidacion
        */
        $vecFactUnicas = array();

        if(count($vecLiquidaciones))
        {
            foreach ($vecLiquidaciones as $objLiquidacion)
            {
                /*
                * Agrega el objeto de la liquidacion al vector
                * que irá a la vista
                */
                if(!array_key_exists($objLiquidacion->liqu_id,$objResponse->vLiquidaciones))
                {
                    $objResponse->vLiquidaciones[$objLiquidacion->liqu_id] = (object)array(
                        'liqu_id'    => $objLiquidacion->liqu_id,
                        'liquidador' => $objLiquidacion->liquidador,
                        'liqu_nombrecontratista' => $objLiquidacion->liqu_nombrecontratista,
                        'liqu_nit'   => $objLiquidacion->liqu_nit,
                        'liqu_fecha' => $objLiquidacion->liqu_fecha,
                        'numActo'    => $objLiquidacion->numActo,
                        'valorActo'  => $objLiquidacion->valorActo,
                        'liqu_tipocontrato' => $objLiquidacion->liqu_tipocontrato,
                        'subtipoacto' => $objLiquidacion->subtipoacto,
                        'tipoacto'    => $objLiquidacion->tipoacto,
                        'estampillas' => array(),
                        'cantEstampillas' => 0,
                        'liqu_valortotal' => 0
                    );
                }
                
                if(!array_key_exists($objLiquidacion->liqu_id.'-'.$objLiquidacion->fact_id, $vecFactUnicas))
                {
                    $objResponse->vLiquidaciones[$objLiquidacion->liqu_id]->estampillas[] = array(
                        'tipo'   => $objLiquidacion->fact_nombre,
                        'rotulo' => $objLiquidacion->impr_codigopapel,
                        'valor'  => $objLiquidacion->fact_valor,
                        'fecha_impr' => $objLiquidacion->impr_fecha,
                        'fecha_pago' => $objLiquidacion->pago_fecha
                    );
                }

                /*
                * Solicita la agrupación de los resultados
                * dependiendo de los parametros de agrupacion recibidos
                */
                if(isset($vectorGet['agruparvista']) && $vectorGet['agruparvista'] == '1')
                {
                    $resultadosAgrupacion = $this->agruparResultadosImpresiones($objLiquidacion,
                        $objLiquidacion,$objResponse->vEstampillas, $vectorGet);

                    $objResponse->vEstampillas      = $resultadosAgrupacion['vec'];
                    $objResponse->cant_agrupaciones = $resultadosAgrupacion['cant_agrupacion'];
                }

                /*
                * Cuenta la cantidad de estampillas para establecer
                * maquetacion en la renderizacion del listado
                */
                $objResponse->vLiquidaciones[$objLiquidacion->liqu_id]->cantEstampillas++;

                /*
                * Acumula el total de las estampillas incluidas
                */
                $objResponse->vLiquidaciones[$objLiquidacion->liqu_id]->liqu_valortotal += (double)$objLiquidacion->fact_valor;

                /*
                * Acumula el total recaudado según los filtros
                * solicitados para el informe
                */
                $objResponse->total_recaudado += (double)$objLiquidacion->fact_valor;

                /*
                * Acumula la cantidad total de estampillas impresas
                */
                $objResponse->cant_total_estampillas++;
            }
        }

        return $objResponse;
    }

    /*
    * Función de apoyo para agrupar los resultados de la consulta de impresiones
    * de estampillas dependiendo de los parametros de agrupacion recibidos
    */
    function agruparResultadosImpresiones($objLiquidacion ,$objFactura, $vEstampillas, $vectorGet)
    {
        /*
        * Se extraen los valores para agrupacion
        */
        $fecha_dividida     = explode('-', $objFactura->impr_fecha);
        $anio               = $fecha_dividida[0];
        $mes                = $fecha_dividida[1];
        $nombre_tipoacto    = $objLiquidacion->tipoacto;
        $nombre_subtipoacto = $objLiquidacion->subtipoacto;
        $contribuyente      = $objLiquidacion->liqu_nit;
        $mesesEspanol       = array(
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        );

        $objEstampillas      = (object)$vEstampillas;
        $objValidar          = $objEstampillas;
        $cant_agrupacion     = 0;
        $titulos_agrupacion  = array();

        /*
        * Si se solicitó agrupar por año se crea el indice si no existe
        */
        if($vectorGet['group_anio'] == '1')
        {
            if(!property_exists($objValidar, $anio))
            {
                $objValidar->$anio = (object) array(
                    'nombre_titulo' => $anio,
                    'datos'  => new stdClass()
                    );
            }
            $objGrupo   = $objValidar->$anio;
            $objValidar = $objGrupo->datos;

            $cant_agrupacion++;
            $titulos_agrupacion[] = $anio;
        }

        /*
        * Si se solicitó agrupar por mes se crea el indice si no existe
        */
        if($vectorGet['group_mes'] == '1')
        {
            if(!property_exists($objValidar, $mes))
            {
                $objValidar->$mes = (object) array(
                    'nombre_titulo' => $mesesEspanol[$mes],
                    'datos'  => new stdClass()
                    );
            }
            $objGrupo   = $objValidar->$mes;
            $objValidar = $objGrupo->datos;

            $cant_agrupacion++;
            $titulos_agrupacion[] = $mesesEspanol[$mes];
        }

        /*
        * Si se solicitó agrupar por contribuyente se crea el indice si no existe
        */
        if($vectorGet['group_contribuyente'] == '1')
        {
            if(!property_exists($objValidar, $contribuyente))
            {
                $objValidar->$contribuyente = (object) array(
                    'nombre_titulo' => $objLiquidacion->liqu_nombrecontratista,
                    'datos'  => new stdClass()
                    );
            }
            $objGrupo   = $objValidar->$contribuyente;
            $objValidar = $objGrupo->datos;

            $cant_agrupacion++;
            $titulos_agrupacion[] = $objLiquidacion->liqu_nombrecontratista;
        }

        /*
        * Si se solicitó agrupar por tipo acto se crea el indice si no existe
        */
        if($vectorGet['group_tipoacto'] == '1')
        {
            if(!property_exists($objValidar, $nombre_tipoacto))
            {
                $objValidar->$nombre_tipoacto = (object) array(
                    'nombre_titulo' => $objLiquidacion->tipoacto,
                    'datos'  => new stdClass()
                    );
            }
            $objGrupo   = $objValidar->$nombre_tipoacto;
            $objValidar = $objGrupo->datos;

            $cant_agrupacion++;
            $titulos_agrupacion[] = $objLiquidacion->tipoacto;
        }

        /*
        * Si se solicitó agrupar por subtipo acto se crea el indice si no existe
        */
        if($vectorGet['group_subtipoacto'] == '1')
        {
            if(!property_exists($objValidar, $nombre_subtipoacto))
            {
                $objValidar->$nombre_subtipoacto = (object) array(
                    'nombre_titulo' => $objLiquidacion->liqu_tipocontrato,
                    'datos'  => new stdClass()
                    );
            }
            $objGrupo   = $objValidar->$nombre_subtipoacto;
            $objValidar = $objGrupo->datos;

            $cant_agrupacion++;
            $titulos_agrupacion[] = $objLiquidacion->liqu_tipocontrato;
        }

        /*
        * Registra la informacion del tipo de estampilla
        * en el vector independiente para el informe consolidado
        */
        $strTipoEstampilla = $objFactura->fact_estampillaid;
        if(!property_exists($objValidar, $strTipoEstampilla))
        {
            $objValidar->$strTipoEstampilla = (object)array(
                'nombre_estampilla'  => $objFactura->fact_nombre,
                'cant_estampilla'    => 0,
                'valor_estampilla'   => 0,
                'titulos_agrupacion' => $titulos_agrupacion
                );
        }
        $objValidar = $objValidar->$strTipoEstampilla;

        $objValidar->cant_estampilla++;
        $objValidar->valor_estampilla += (double)$objFactura->fact_valor;
        
        return array('vec' => $objEstampillas, 'cant_agrupacion' => $cant_agrupacion);
    }

    /**
     * Retorna el vector con informacion de filtros aplicados
     */
    function extraerInformacionFiltrosAplicados($vecFiltrosAplicados)
    {
        if($vecFiltrosAplicados['tipo_contrato'] != '')
        {
            $tipoContrato = $this->codegen_model->getSelect("con_tiposcontratos","*",
                " WHERE tico_id = '". $vecFiltrosAplicados['tipo_contrato'] ."'");
            if(count($tipoContrato) > 0)
            {
                $vecFiltrosAplicados['tipo_contrato'] = $tipoContrato[0]->tico_nombre;
            }

            if($vecFiltrosAplicados['contribuyente'] != '')
            {
                $contribuyente = $this->codegen_model->getSelect("con_contratistas","*",
                    " WHERE cont_nit = '". $vecFiltrosAplicados['contribuyente'] ."'");
                if(count($contribuyente) > 0)
                {
                    $vecFiltrosAplicados['contribuyente'] = $contribuyente[0]->cont_nombre;
                }
            }
        }

        if($vecFiltrosAplicados['tipo_tramite'] != '') 
        {
            $tipoTramite = $this->codegen_model->getSelect("est_tramites","*",
                " WHERE tram_id = '" . $vecFiltrosAplicados['tipo_tramite'] . "'"
            );
            if (count($tipoTramite) > 0) 
            {
                $vecFiltrosAplicados['tipo_tramite'] = $tipoTramite[0]->tram_nombre;
            }

            if($vecFiltrosAplicados['contribuyente'] != '')
            {
                $contribuyente = $this->codegen_model->getSelect('tramitadores','nombre',
                    " WHERE nit = '". $vecFiltrosAplicados['contribuyente'] ."'");
                if(count($contribuyente) > 0)
                {
                    $vecFiltrosAplicados['contribuyente'] = $contribuyente[0]->nombre;
                }
            }
        }

        if($vecFiltrosAplicados['tipo_estampilla'] != '') 
        {
            $tipoEstampilla = $this->codegen_model->getSelect("est_estampillas","*",
                " WHERE estm_id = '" . $vecFiltrosAplicados['tipo_estampilla'] . "'"
            );
            if (count($tipoEstampilla) > 0) 
            {
                $vecFiltrosAplicados['tipo_estampilla'] = $tipoEstampilla[0]->estm_nombre;
            }
        }

        if($vecFiltrosAplicados['contratante'] != '')
        {
            $infoContratante = $this->codegen_model->getSelect("con_contratantes","*",
                " WHERE id = '" . $vecFiltrosAplicados['contratante'] . "'"
            );
            if (count($infoContratante) > 0) 
            {
                $vecFiltrosAplicados['contratante'] = $infoContratante[0]->nombre;
            }
        }

        if($vecFiltrosAplicados['municipio'] != '')
        {
            $infoMunicipio = $this->codegen_model->getSelect("par_municipios","*",
                " WHERE muni_id = '" . $vecFiltrosAplicados['municipio'] . "'"
            );
            if (count($infoMunicipio) > 0) 
            {
                $vecFiltrosAplicados['municipio'] = $infoMunicipio[0]->muni_nombre;
            }
        }
        
        return $vecFiltrosAplicados;
    }

    /*
    * Función de apoyo que concatena al string de la tabla html
    * las filas titulo o de información dependiendo del contenido
    */
    public static function concatenarStrTabla($vecStrTabla, $objAgrupacion, $cantAgrupacion)
    {
        if(property_exists($objAgrupacion, 'nombre_titulo'))
        {
            $vecStrTabla = Liquidaciones::concatenarStrTabla($vecStrTabla, $objAgrupacion->datos, $cantAgrupacion);
        }else
            {
                /*
                * Valida si es una agrupación para iterar en las 
                * agrupaciones inferiores
                */
                foreach($objAgrupacion as $objTestear)
                {
                    if(!property_exists($objTestear, 'nombre_estampilla'))
                    {
                        $vecStrTabla = Liquidaciones::concatenarStrTabla($vecStrTabla, $objTestear->datos, $cantAgrupacion);
                    }else
                        {
                            /*
                            * Determina la extensión de las celdas en los titulos
                            */
                            $expansion_celda1 = 1;
                            $expansion_titulo1 = 1;
                            if($cantAgrupacion > 3)
                            {
                                $expansion_celda1 = (int)$cantAgrupacion - 2;
                            }elseif($cantAgrupacion == 2)
                                {
                                    $expansion_titulo1 = 2;
                                }elseif($cantAgrupacion == 1)
                                    {
                                        $expansion_titulo1 = 3;
                                    }
    
                            $strTemporal = $vecStrTabla['plantilla_inicio'].'<tr>';
                            
                            /*
                            * Se agregan los titulos de la agrupación
                            */
                            $strExpansionTitulo = '';
                            foreach($objTestear->titulos_agrupacion as $nom_grupo)
                            {
                                if($strExpansionTitulo == '')
                                {
                                    $strExpansionTitulo = 'colspan="'. $expansion_titulo1 .'"';
                                    $strTemporal .= '<th '. $strExpansionTitulo .' style="background-color:#00632D;color:white;">'. $nom_grupo .'</th>';
                                }else
                                    {
                                        $strTemporal .= '<th style="background-color:#00632D;color:white;">'. $nom_grupo .'</th>';
                                    }
                            }

                            $strTemporal .= '</tr>'
                                .'<tr>'
                                    .'<th style="background-color:#3C3C3C;color:white;" colspan="'. $expansion_celda1 .'">Tipo Estampilla</th>'
                                    .'<th style="background-color:#3C3C3C;color:white;">Cantidad</th>'
                                    .'<th style="background-color:#3C3C3C;color:white;">Valor</th>'
                                .'</tr>'
                                .'</thead>'
                                .'<tbody>';
                            
                            $valor_total_agrupacion = 0;
                            $cant_total_agrupacion  = 0;
                            foreach($objAgrupacion as $objEstampilla)
                            {
                                $strTemporal .= '<tr>'
                                        .'<td colspan="'. $expansion_celda1 .'">'. $objEstampilla->nombre_estampilla .'</td>'
                                        .'<td>'. number_format($objEstampilla->cant_estampilla,0,',','.') .'</td>'
                                        .'<td>'. number_format($objEstampilla->valor_estampilla,0,',','.') .'</td>'
                                    .'</tr>';

                                $valor_total_agrupacion += (double)$objEstampilla->valor_estampilla;
                                $cant_total_agrupacion  += (double)$objEstampilla->cant_estampilla;
                            }
                            
                            $strTemporal .= '<tr>'
                                    .'<td colspan="'. $expansion_celda1 .'"><b>TOTAL</b></td>'
                                    .'<td><b>'. number_format($cant_total_agrupacion,0,',','.') .'</b></td>'
                                    .'<td><b>'. number_format($valor_total_agrupacion,0,',','.') .'</b></td>'
                                .'</tr>'
                                .$vecStrTabla['plantilla_fin'].'<br><br><br>';

                            $vecStrTabla['str_tablas_completas'] .= $strTemporal;
                            
                            /*
                            * Se rompe el ciclo porque en una sola iteración se construye
                            * la tabla para las estampillas en la agrupación
                            */
                            break;
                        }
                }
            }
        return $vecStrTabla;
    }

    /*
    * Funcion de apoyo que procesa la construccion del bloque where
    * para la vista index
    */
    public static function concatenarWhere($strWhere)
    {
        if($strWhere == '')
        {
            $strWhere .= ' WHERE ';
        }else
        {
            $strWhere .= ' AND ';
        }
        return $strWhere;
    }

    /*
    * Funcion de apoyo para extraer la fecha en letras segun los valores especificados
    */
    public static function fechaEnLetras($fecha = '', $vDiaSemana = '')
    {
        /*
        * Separa la fecha en un arreglo segun la expresion regular
        */
        preg_match('/(\d{4})-(\d{2})-(\d{2})/',$fecha, $partes);
        $diaNumero = $partes[3];
        $diaNombre = date('l',strtotime($fecha));
        $mesNumero = $partes[2];
        $anioNumero = $partes[1];
    
        switch ($diaNombre) 
        {
            case 'Sunday': $diaNombre = 'Domingo';        
                break;
        
            case 'Monday': $diaNombre = 'Lunes';        
                break;
        
            case 'Tuesday': $diaNombre = 'Martes';        
                break;
        
            case 'Wednesday': $diaNombre = 'Miercoles';        
                break;
        
            case 'Thursday': $diaNombre = 'Jueves';        
                break;
        
            case 'Friday': $diaNombre = 'Viernes';        
                break;
        
            case 'Saturday': $diaNombre = 'Sabado';        
                break;
                
        }
    
        switch ($mesNumero) 
        {
            case '01': $mesNombre = 'Enero';        
                break;
        
            case '02': $mesNombre = 'Febrero';        
                break;
        
            case '03': $mesNombre = 'Marzo';        
                break;
        
            case '04': $mesNombre = 'Abril';        
                break;
        
            case '05': $mesNombre = 'Mayo';        
                break;
        
            case '06': $mesNombre = 'Junio';        
                break;
        
            case '07': $mesNombre = 'Julio';        
                break;
        
            case '08': $mesNombre = 'Agosto';        
                break;
        
            case '09': $mesNombre = 'Septiembre';        
                break;
        
            case '10': $mesNombre = 'Octubre';        
                break;
        
            case '11': $mesNombre = 'Noviembre';        
                break;
        
            case '12': $mesNombre = 'Diciembre';        
                break;
                
        }
        
        /*
        * Valida el tipo de fecha requerida para retornar
        */
        if($vDiaSemana)
        {
            $fechaLetras = strtoupper($diaNombre.' '.$diaNumero.' de '.$mesNombre.' de '.$anioNumero);
        }else
            {
                $fechaLetras = strtoupper($diaNumero.' de '.$mesNombre.' de '.$anioNumero);
            }
        return $fechaLetras;
    }

    /*
    * Funcion que retorna los subtipos de acto según el tipo de acto
    * suministrado
    */
    function extraerSubtiposActo()
    {
        if($this->ion_auth->logged_in()) 
        {
            if($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/consultar') )
            {
                $tipoActo = $this->input->post('tipo_acto');

                /*
                * Extrae el listado de subtipos de actos para realizar
                * la consulta
                */
                $tiposContrato = $this->codegen_model->getSelect('con_tiposcontratos',"tico_id,tico_nombre");
                $tiposTramite  = $this->codegen_model->getSelect('est_tramites',"tram_id,tram_nombre"); 
                
                $vSubTiposActo        = array();
                $bandIncluirContratos = true;
                $bandIncluirTramites  = true;

                /*
                * Si el tipo de acto suministrado es contrato (1)
                * se inhabilita la inclusión de tramites
                */
                if($tipoActo == '1')
                {
                    $bandIncluirTramites = false;
                }

                /*
                * Si el tipo de acto suministrado es tramite (2)
                * se inhabilita la inclusión de contratos
                */
                if($tipoActo == '2')
                {
                    $bandIncluirContratos = false;
                }

                if($bandIncluirContratos)
                {
                    if(count($tiposContrato) > 0)
                    {
                        foreach($tiposContrato as $tipoA)
                        {
                            $vSubTiposActo['c_'.$tipoA->tico_id] = $tipoA->tico_nombre.' ( Contrato )';
                        }
                    }
                }
                
                if($bandIncluirTramites)
                {
                    if(count($tiposTramite) > 0)
                    {
                        foreach($tiposTramite as $tipoA)
                        {
                            $vSubTiposActo['t_'.$tipoA->tram_id] = $tipoA->tram_nombre.' ( Tramite )';
                        }
                    }
                }

                echo json_encode($vSubTiposActo);
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
* Funcion que ordena la renderizacion o no del EXCEL
* de las liquidaciones de la fecha especificada
* Mike Ortiz
*/
function renderizarDetalleRangoExcel()
{
    if ($this->ion_auth->logged_in()) 
    {
        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/consultar') ) 
        {
            $resultadosFiltros = Liquidaciones::extraerRegistrosDetalleImpresiones($_GET, true);

            /*
            * Valida si hubo resultados para generar el pdf
            */
            if(count($resultadosFiltros['vec_liquidaciones']) <= 0)
            {
                $this->session->set_flashdata('errormessage', 'La fecha elegida no presenta registros!');
                redirect(base_url().'index.php/liquidaciones/consultar');
            }

            $datos['fecha']            = $resultadosFiltros['fecha'];
            $datos['liquidaciones']    = $resultadosFiltros['vec_liquidaciones'];
            $datos['totalRecaudado']   = $resultadosFiltros['total_recaudado'];
            $datos['totalEstampillas'] = $resultadosFiltros['cant_total_estampillas'];
            $datos['vec_filtros']      = $resultadosFiltros['vec_filtros'];
            
            session_start();
            $_SESSION['fecha_informe_excel'] = $resultadosFiltros['fecha_nombre_archivo'];

            $this->template->load($this->config->item('excel_template'),'generarexcel/generarexcel_impresiones', $datos);

        } else 
            {
                redirect(base_url().'index.php/error_404');
            }
    }else
        {
            redirect(base_url().'index.php/users/login');
        } 
      
}


/**
* Funcion que ordena la renderizacion o no del PDF
* de la Relación de Entrega de estampillas de la fecha especificada
* Mike Ortiz
*/

    function renderizarRelacionEstampillasPDF()
    {
        if ($this->ion_auth->logged_in()) 
        {
            
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/consultar') ) 
            { 
                $fecha = $_GET['fecha'];
                
                /*
                * Crea la consulta para el perfil de administrador o Usuario conciliacion
                * para no filtrar por usuario
                */
                $usuario = $this->ion_auth->user()->row();
                if($this->ion_auth->is_admin() || $usuario->perfilid == 5)
                {
                    //Extrae los id de las facturas para las que se han hecho impresiones  
                    //y las fechas de las impresiones hechas por los usuarios liquidadores                    
                    $where = 'where DATE(i.impr_fecha) = "'.$_GET['fecha'].'"';              
                    $join = '';
                }else
                    {
                        /*
                        * Crea la consulta para el perfil de liquidador con el id del usuario autenticado
                        */

                        //Extrae los id de las facturas para las que se han hecho impresiones  
                        //y las fechas de las impresiones hechas por el liquidador autenticado                        
                        $where = 'where p.pape_usuario = '.$usuario->id.' and DATE(i.impr_fecha) = "'.$_GET['fecha'].'"';              
                        $join = 'join est_papeles p on p.pape_id = i.impr_papelid';
                    }
  
                $facturas = $this->codegen_model->getSelect('est_impresiones i',"i.impr_facturaid, DATE(i.impr_fecha) as fecha",$where,$join);
  
                if($facturas)
                {
                    //se extrae el vector con los id de las facturas
                    //asociadas a las impresiones para la fecha suministrada              
                    $idFacturas = '(';
                    foreach ($facturas as $factura) 
                    {   
                        if($factura->fecha == $fecha)
                        {
                            $idFacturas .= $factura->impr_facturaid.',';                                            
      
                        }                  
                    }  
                    $idFacturas .= '0)';
                    $where = 'where fact_id in '.$idFacturas;                            
                    
                    //Extrae las facturas
                    $liquidaciones = $this->codegen_model->getSelect('est_facturas f',"f.fact_valor, f.fact_estampillaid, f.fact_nombre",$where);
                   
                    $vectorIdEstampillas = [];
                    $vectorValorEstampillas = [];
                                            
                    foreach ($liquidaciones as $liquidacion) 
                    {                                                  
                        //Selecciona los id de las estampillas
                        //impresas y crea un arreglo por cada tipo de estampilla
                        //inicializando el valor en cero y guardando el nombre de la estampilla
                        if(!in_array($liquidacion->fact_estampillaid, $vectorIdEstampillas))
                        {
                            $vectorIdEstampillas[] = $liquidacion->fact_estampillaid;
                            $vectorValorEstampillas[$liquidacion->fact_estampillaid]['valor'] = 0;
                            $vectorValorEstampillas[$liquidacion->fact_estampillaid]['nombre'] = $liquidacion->fact_nombre;;
                        }
      
                        //acumula los valores por id de estampillas
                        if(in_array($liquidacion->fact_estampillaid, $vectorIdEstampillas))
                        {
                            $vectorValorEstampillas[$liquidacion->fact_estampillaid]['valor'] += $liquidacion->fact_valor;
                        }                             
                    }
      
                    $datos['liquidaciones'] = $vectorValorEstampillas;
                    $datos['fecha'] = $fecha;
                    $datos['usuario'] = $usuario->first_name.' '.$usuario->last_name;
                    //Creación del PDF
                    $this->load->library("Pdf");                  
                    $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);                  
      
                    // set document information
                    $pdf->SetCreator(PDF_CREATOR);
                    $pdf->SetAuthor('turrisystem');
                    $pdf->SetTitle('Relacion Entrega Estampillas');
                    $pdf->SetSubject('Gobernación de Boyacá');
                    $pdf->SetKeywords('estampillas,gobernación');
                    $pdf->SetPrintHeader(false);
                    $pdf->SetPrintFooter(false);
                    // set default monospaced font
                    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      
                    // set margins
                    $pdf->setPageUnit('mm');
                    $pdf->SetMargins(30, 10, 20, true);
                    $pdf->SetHeaderMargin(0);
                    $pdf->SetFooterMargin(0);
            
                    // set auto page breaks
                    $pdf->SetAutoPageBreak(TRUE, 2);
      
                    // set some language-dependent strings (optional)
                    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                        require_once(dirname(__FILE__).'/lang/eng.php');
                        $pdf->setLanguageArray($l);
                    }
                     
                        // ---------------------------------------------------------
                  
                    // set font
                    $pdf->SetFont('helvetica', '', 10);
                    $pdf->AddPage();                  
                    $html = $this->load->view('generarpdf/generarpdf_relacionEntregaEstampillas', $datos, TRUE);  
                      
                    $pdf->writeHTML($html, true, false, true, false, '');
                 
      
                    // ---------------------------------------------------------
                    //para evitar el error de que se ha impreso algo antes de enviar
                    //el PDF 
                    ob_end_clean();
                    //Close and output PDF document
                    $pdf->Output('Relacion_Entrega_Estampillas_'.$fecha.'.pdf', 'I');
  
  
                }else
                    {   
                        $this->session->set_flashdata('errormessage', 'La fecha elegida no presenta registros!'); 
                        redirect(base_url().'index.php/liquidaciones/consultar');  
                    }

            } else {
                redirect(base_url().'index.php/error_404');
            }
                 
        } else{
                redirect(base_url().'index.php/users/login');
        } 
        
    }

/*
* Funcion que ordena la renderizacion o no del PDF
* de la Relación de Entrega de estampillas por rango de fecha especificado
* Mike Ortiz
*/
function renderizarConsolidadoRangoImpresionesPDF()
{
    if ($this->ion_auth->logged_in()) 
    {
        /*
        * Extrae el objeto del usuario autenticado para validar
        * si es usuario conciliacion
        */
        $usuario = $this->ion_auth->user()->row();
        if ($this->ion_auth->is_admin() || $usuario->perfilid == 5 || $usuario->perfilid == 4) 
        {
            if($_GET['agruparvista'] == '1')
            {
                $resultadosFiltros = Liquidaciones::extraerRegistrosDetalleImpresiones($_GET, true);
            }else
                {
                    $resultadosFiltros = Liquidaciones::extraerRegistrosDetalleImpresiones($_GET);
                }

            if(!empty($resultadosFiltros['vec_estampillas']))
            {
                $usuario = $this->ion_auth->user()->row();

                $datos['usuario']    = $usuario->first_name.' '.$usuario->last_name;
                $datos['resultados'] = $resultadosFiltros;
                
                //Creación del PDF
                $this->load->library("Pdf");
                $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                // set document information
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('turrisystem');
                $pdf->SetTitle('Relacion Estampillas Impresas Rango');
                $pdf->SetSubject('Gobernación de Boyacá');
                $pdf->SetKeywords('estampillas,gobernación');
                $pdf->SetPrintHeader(false);
                $pdf->SetPrintFooter(false);

                // set default monospaced font
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

                /*
                * Dependiendo de si se solicitó agrupacion se establece
                * la orientación del documento y la vista para el PDF
                */
                $especificacion_nombre_archivo = '';
                if($_GET['agruparvista'] == '1')
                {
                    $especificacion_nombre_archivo = 'Agrupado_';

                    $vistaPDF = 'generarpdf_relacionRangoAgrupado';
                    $pdf->setPageOrientation('l');

                    // set margins
                    $pdf->setPageUnit('mm');
                    $pdf->SetMargins(10, 5, 20, true);
                    $pdf->SetHeaderMargin(0);
                    $pdf->SetFooterMargin(0);
                }else
                    {
                        $vistaPDF = 'generarpdf_relacionRangoEstampillas';

                        // set margins
                        $pdf->setPageUnit('mm');
                        $pdf->SetMargins(30, 10, 20, true);
                        $pdf->SetHeaderMargin(0);
                        $pdf->SetFooterMargin(0);
                    }
            
                // set auto page breaks
                $pdf->SetAutoPageBreak(TRUE, 2);
      
                // set some language-dependent strings (optional)
                if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                    require_once(dirname(__FILE__).'/lang/eng.php');
                    $pdf->setLanguageArray($l);
                }
                  
                // set font
                $pdf->SetFont('helvetica', '', 10);
                $pdf->AddPage();                  
                $html = $this->load->view('generarpdf/'.$vistaPDF, $datos, TRUE);  
                      
                $pdf->writeHTML($html, true, false, true, false, '');
                 
      
                // ---------------------------------------------------------
                //para evitar el error de que se ha impreso algo antes de enviar
                //el PDF 
                ob_end_clean();
                //Close and output PDF document
                $pdf->Output('Relacion_Entrega_Estampillas_Rango_'. $especificacion_nombre_archivo . str_replace(' ', '_', $resultadosFiltros['fecha']) .'.pdf', 'I');
            }else
                {   
                    $this->session->set_flashdata('errormessage', 'El Rango de fechas elegido no presenta registros!'); 
                    redirect(base_url().'index.php/liquidaciones/consultar');  
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
* Funcion de apoyo que crea y asigna el codigo a la factura
* para generar el codigo de barras del recibo
*/
function asignarCodigoParaBarras($idLiquidacion,$idEstampilla)
{
    /**
    * Crea el codigo para generar el codigo de barras
    */                        
    //Extrae la factura creada y el codigo de la estampilla
    $tabla = 'est_facturas f';
    $campos = 'f.fact_id, f.fact_estampillaid, f.fact_valor, e.estm_codigoB'; 
    $donde = 'WHERE fact_liquidacionid = '.$idLiquidacion.' AND fact_estampillaid = '.$idEstampilla;
    $join = 'INNER JOIN est_estampillas e ON e.estm_id = f.fact_estampillaid';
    $factura = $this->codegen_model->getSelect($tabla, $campos, $donde, $join);

    /*
    * Se eliminan los decimales del valor de la factura
    */
    $factura[0]->fact_valor = number_format((double)$factura[0]->fact_valor, 0, '.', '');

    //Formatea el valor y consecutivo de la factura para que quede de 10 digitos
    $valorEstampilla = str_pad($factura[0]->fact_valor, 10, 0, STR_PAD_LEFT);
    $consecutivoFactura = str_pad($factura[0]->fact_id, 10, 0, STR_PAD_LEFT);
    $codigoParaBarra='(415)'.$factura[0]->estm_codigoB.'~F1(8020)'.$consecutivoFactura.'~F1(390y)'.$valorEstampilla;                                                

    $info = array('fact_codigo' => $codigoParaBarra);
    //Actualiza el registro de la Factura para asignarle el codigo                        
    $t = $this->codegen_model->edit('est_facturas',$info,'fact_id',$factura[0]->fact_id);                          
}



/**
* Funcion de apoyo que extrae el ultimo numero de rotulo
* para el usuario que lo solicita
*/
function solicitarUltimoRotuloImpreso()
{
    if(isset($_POST['usuario']) && $_POST['usuario'] != '') 
    {
        //verifica que el usuario que llama el metodo
        //tenga perfil de liquidador para cargar
        //el proximo codigo de estampilla fisica a imprimir 
        $usuario = $_POST['usuario'];
        $usuarioLogueado=$this->ion_auth->user($usuario)->row();

        $respuestaPeticion = array(
            'hayRotuloDisponible' => 'NO',
            'numeroRotulo' => '',
            'notificacionErr' => ''
        );

        if($usuarioLogueado->perfilid==4)
        {
            $datosConsecutivo = Liquidaciones::determinarSiguienteRotulo($usuarioLogueado);
            $respuestaPeticion['hayRotuloDisponible'] = 'SI';
            $respuestaPeticion['numeroRotulo'] = $datosConsecutivo['nuevoIngreso'];
            
            if($datosConsecutivo['nuevoIngreso'] == 'NO')
            {
                $respuestaPeticion['hayRotuloDisponible'] = 'NO';
                $respuestaPeticion['notificacionErr'] = "(No posee papeleria para imprimir)";
            }
            echo json_encode($respuestaPeticion);
        }
    }     
}

/*
* Funcion de apoyo que determina el numero del rotulo siguiente a imprimir
*/
function determinarSiguienteRotulo($usuarioLogueado)
{
    /*
    * Variable que determina si se debe trabajar con papelería de contingencia
    */
    $objContin = $this->codegen_model->get('adm_parametros','para_contingencia','para_id = 1',1,NULL,true);
    if($objContin->para_contingencia == 1)
    {
        $contingencia = 'SI';
    }else
        {
            $contingencia = 'NO';
        }

    //extrae el ultimo codigo de papeleria registrado
    //en las impresiones para el liquidador autenticado
    $tablaJoin='est_papeles';
    $equivalentesJoin='est_impresiones.impr_papelid = est_papeles.pape_id';
    $where='est_papeles.pape_usuario ='. $usuarioLogueado->id .' AND est_impresiones.impr_estadoContintencia = "'. $contingencia .'"';
    
    $max = $this->codegen_model->max('est_impresiones','impr_codigopapel',$where, $tablaJoin, $equivalentesJoin);                          

    /*
    * verifica si ya habia asignado por lo menos un consecutivo a una impresion
    * de lo contrario elige el primer codigo
    */
    if((int)$max['impr_codigopapel']>0)
    {
        //extrae el ultimo codigo de papeleria asignado al
        //liquidador para verificar que el ultimo impreso
        //no sea el ultimo asignado                           
        $where='pape_usuario ='. $usuarioLogueado->id .' AND pape_estadoContintencia = "'. $contingencia .'"';

        $maxAsignado = $this->codegen_model->max('est_papeles','pape_codigofinal',$where);                                

        $nuevoingreso=$max['impr_codigopapel']+1;

    }else
        {
            //extrae el primer codigo de papeleria registrado
            //en los rangos de papel asginado al liquidador autenticado
            $where='est_papeles.pape_usuario ='. $usuarioLogueado->id .' AND pape_estadoContintencia = "'. $contingencia .'"';
            $primerCodigo = $this->codegen_model->min('est_papeles','pape_codigoinicial',$where);
            $nuevoingreso = (int)$primerCodigo['pape_codigoinicial'];
        }

    /*
    * extrae los posibles rangos de papeleria asignados al usuario 
    * que se encuentra logueado que debe ser un liquidador,
    * en los que pueda estar el nuevo codigo a asignar
    */
    $papeles = $this->codegen_model->get('est_papeles','pape_id'
        .',pape_codigoinicial,pape_codigofinal',
        'pape_codigoinicial <= '.$nuevoingreso
        .' AND pape_codigofinal >='
        .$nuevoingreso
        .' AND pape_estadoContintencia = "'. $contingencia .'"'
        .' AND pape_usuario = '.$usuarioLogueado->id,1,NULL,true);

    /*
    * verifica que exista un rango de papeleria asignado
    * al liquidador en el que se encuentre el posible
    * codigo a registrar
    */
    $idRangoPapel = 0;
    $banPapelDisponible = false;
    if($papeles)
    {
        /*
        * Comprueba si ya se está usando el codigo del papel
        */
        $nousado = 0;

        while ($nousado==0)
        {
            $combrobacionImpresiones = $this->codegen_model->get('est_impresiones','impr_id,impr_papelid','impr_codigopapel = '.$nuevoingreso.' AND impr_estadoContintencia = "'. $contingencia .'"',1,NULL,true);

            if(!$combrobacionImpresiones) 
            {
                $nousado = 1;
                $idRangoPapel = $papeles->pape_id;
                $banPapelDisponible = true;
            }else
                {
                    $nuevoingreso++;

                    /*
                    * Valida si el numero siguiente luego del incremento continua 
                    * estando dentro del rango de papeleria del usuario
                    * determinado en la consulta anterior, si salió del rango ordena
                    * que salga del bucle
                    */
                    if($papeles->pape_codigoinicial > $nuevoingreso || $papeles->pape_codigofinal < $nuevoingreso)
                    {
                        $nousado = 1;
                    }
                }
        }

        /*
        * Si la bandera de papel disponible no se activó significa que en el momento
        * de solicitar el consecutivo alguien más lo utilizó entonces solicita
        * ejecutar nuevamente esta funcion para extraer un consecutivo de otro rango
        */
        if(!$banPapelDisponible)
        {
            $resultado = Liquidaciones::determinarSiguienteRotulo($usuarioLogueado);

            /*
            * Si se recibe el valor NO indica que no tiene papeleria disponible
            * si se recibe cualquier cosa diferente, se activa la bandera de papel disponible
            */
            if($resultado['nuevoIngreso'] != 'NO')
            {
                $nuevoIngreso = $resultado['nuevoIngreso'];
                $idRangoPapel = $resultado['rangoPapel'];
                $banPapelDisponible = true;
            }
        }
                                
    }else
        {
            /*
            * Extrae los posibles rangos de papeleria asignados
            * al usuario que se encuentra logueado que debe ser
            * un liquidador
            */
            $papelesAsignados = $this->codegen_model->getSelect('est_papeles','*',
                ' where pape_usuario = '. $usuarioLogueado->id .' AND pape_estadoContintencia = "'. $contingencia .'"',
                '', '','order by pape_codigoinicial');
            
            /*
            * Valida cual de los rangos no ha impreso la totalidad
            * de los rotulos
            */
            $rangosDisponibles = array();
            $objRangoMenor = array();
            foreach($papelesAsignados as $objRangoPapeles)
            {
                $vDisponibilidad = $this->verificarEstadoDisponibilidadEstampillas($objRangoPapeles);
                if($vDisponibilidad->estadoDisponibilidad)
                {
                    $rangosDisponibles[$objRangoPapeles->pape_id] = $objRangoPapeles;

                    /*
                    * Se establece el primer objeto de rango
                    * cómo el rango de menor numeración
                    */
                    if(empty($objRangoMenor))
                    {
                        $objRangoMenor = $objRangoPapeles;
                    }

                    /*
                    * Determina cual de los rangos tiene el menor
                    * valor en codigo inicial para tomarlo e imprimir
                    */
                    if((int)$objRangoPapeles->pape_codigoinicial < (int)$objRangoMenor->pape_codigoinicial)
                    {
                        $objRangoMenor = $objRangoPapeles;
                    }
                }
            }

            /*
            * Si hay rangos con papeleria disponible se realiza la verificación
            * para el rango que tiene menor numeración
            */
            if(count($rangosDisponibles) > 0)
            {
                $nuevoingreso = (int)$objRangoMenor->pape_codigoinicial;

                /*
                * Comprueba si ya se está usando el codigo del papel
                * en alguna impresión y ademas que no se salga del rango
                */
                $nousado = 0;
                while($nousado==0 && $nuevoingreso <= (int)$objRangoMenor->pape_codigofinal)
                {
                    $combrobacionImpresiones = $this->codegen_model->get('est_impresiones',
                        'impr_id','impr_codigopapel = '. $nuevoingreso .' AND impr_estadoContintencia = "'
                        .$contingencia .'"',1,NULL,true);
    
                    if(!$combrobacionImpresiones) 
                    {
                        $nousado = 1;
                    }else
                        {
                            $nuevoingreso++;
                        }
                }

                /*
                * Valida si se encontró un codigo para asignar
                */
                if($nousado == 1)
                {
                    $idRangoPapel = $objRangoMenor->pape_id;
                    $banPapelDisponible = true;
                }
            }
        }

        /*
        * Valida si se activó la bandera que indica que hay papeleria
        * disponible para impresion
        */
        if($banPapelDisponible)
        {
            $retornar = array('nuevoIngreso' => $nuevoingreso, 'rangoPapel' => $idRangoPapel);
        }else
            {
                $retornar = array('nuevoIngreso' => 'NO');
            }

        return $retornar;
}

/**
 * Funcion de apoyo que determina el estado de disponibilidad
 * de un rango de estampillas
*/
public function verificarEstadoDisponibilidadEstampillas($objRango)
{
    $respuestaProceso = (object)array(
        'estadoDisponibilidad' => false,
        'cantidadDisponible'   => 0
    );

    /*
    * Se consulta la cantidad de rotulos correctos y anulados
    * utilizados para el rango de papeleria
    */
    $where = 'WHERE impr_codigopapel != 0 AND impr_papelid = '.$objRango->pape_id;
    $impresionesActuales = $this->codegen_model->getSelect('est_impresiones',"COUNT(*) AS contador", $where);
    
    /*
    * Se incrementa en 1 la diferencia porque el rango
    * incluye el código inicial
    */
    $cantidadRango = ((int)$objRango->pape_codigofinal - (int)$objRango->pape_codigoinicial) + 1;
    if((int)$impresionesActuales[0]->contador < $cantidadRango)
    {
        $respuestaProceso->estadoDisponibilidad = true;
        $respuestaProceso->cantidadDisponible = (int)$cantidadRango - (int)$impresionesActuales[0]->contador;
    }

    return $respuestaProceso;
}

/*
* Funcion de apoyo que realiza la validacion para inclusion
* o exclusion de una estampilla según casos especificos
*/
public static function validarInclusionEstampilla($idTipoEstampilla, $fecha_validar = '', $contrato_validar = '')
{
    /*
    * Si no se suministró fecha para validar se establece la fecha actual
    */
    if($fecha_validar == '')
    {
        $fecha_validar = date('Y-m-d');
    }

    $bandRegistrarFactura = true;

    /*
    | PRO ELECTRIFICACION
    | Se valida si la estampilla a almacenar es pro electrificacion y la fecha de liquidacion
    | (fecha actual) es mayor al 21 de mayo de 2017, no se incluya la estampilla 
    | en las liquidaciones según ordenanza 026 de 20017
    | [SE REACTIVA EL COBRO DE LA ESTAMPILLA A PARTIR DEL 9 DE ENERO DE 2018]
    */
    if($idTipoEstampilla == 7)
    {
        if(strtotime('2017-05-21') < strtotime($fecha_validar) && strtotime('2018-01-01') > strtotime($fecha_validar))
        {
            $bandRegistrarFactura = false;
        }
    }

    /*
    | PRO CULTURA
    | Se valida si la estampilla a almacenar es pro cultura, el tipo de contrato es OBRA
    | y la fecha del contrato es anterior al 2018, no se incluya la estampilla
    | en las liquidaciones según ordenanza DFRI-163-5709 de 2018
    */
    $idsContratoObra = array(4);
    if($idTipoEstampilla == 9 && in_array($contrato_validar, $idsContratoObra))
    {
        if(strtotime('2018-01-01') > strtotime($fecha_validar))
        {
            $bandRegistrarFactura = false;
        }
    }

    return $bandRegistrarFactura;
}

	function pagarEstampilla()
	{
		if ($this->ion_auth->logged_in())
		{
			if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) 
			{
				$this->data['successmessage'] = $this->session->flashdata('message');
				$this->data['errormessage'] = '';

				$this->form_validation->set_rules('id_factura', 'Identificador de la facturas', 'trim|xss_clean|numeric|integer|greater_than[0]');
				$this->form_validation->set_rules('fecha', 'Fecha', 'required|trim|xss_clean|required');
				$this->form_validation->set_rules('observaciones', 'Observaciones', 'trim|xss_clean');
                $this->form_validation->set_rules('valor', 'valor','required|trim|xss_clean');

				if ($this->form_validation->run() == false) {
					$this->session->set_flashdata('errorModal', true);
					$this->session->set_flashdata('errormessage', (validation_errors() ? validation_errors(): false));
					$this->session->set_flashdata('accion', 'retencion');
					redirect(base_url().'index.php/liquidaciones/estampillasRetencion/'.$this->input->post('id_contrato'));
				}

                $is_pagos = array();

                # Se toma una factura de todo el contrato para obtener la liquidacion y buscar las demas
                $factura_muestra = $this->codegen_model->get(
                    'est_facturas',
                    'fact_liquidacionid AS id_liquidacion, id_cuota_liquidacion',
                    'fact_id = "' . $this->input->post('id_factura') . '"',
                    1, null, true
                );

                if($this->input->post('todos') != '1')
                {
                    # Se da formato al valor para que guarde los valores decimales
                    $valor = str_replace(',', '.', str_replace('.','',$this->input->post('valor')));

                    $guardo = $this->pagarEstampillaIndividual(
                        $this->input->post('id_factura'),
                        $this->input->post('id_contrato'),
                        $this->input->post('fecha'),
                        $this->input->post('observaciones'),
                        $valor
                    );
                    $is_pagos[] = $guardo->idInsercion;
                }
                else
                {
                    $facturas = $this->liquidaciones_model->obtenerFacturasRetencion('factura.fact_liquidacionid', $factura_muestra->id_liquidacion);

                    foreach($facturas AS $factura)
                    {
                        $saldo = floor($factura->valor_total - $factura->valor_pagado);

                        # Si el saldo no es cero, es decir no ha sido pagada
                        if($saldo != 0)
                        {
                            $guardo = $this->pagarEstampillaIndividual(
                                $factura->fact_id,
                                $this->input->post('id_contrato'),
                                $this->input->post('fecha'),
                                $this->input->post('observaciones'),
                                # El valor de la cuota sera el total restante (saldo)
                                $saldo,
                                $factura
                            );
                            $is_pagos[] = $guardo->idInsercion;
    
                            # Si falla algun proceso rompa el for y se valide despues
                            if (!$guardo->bandRegistroExitoso){
                                break;
                            }
                        }
                    }
                }

                # Se obtenienen las facturas (de nuevo en el caso que se hayan pagado todas para obtener la informacion actucalizada)
                $facturas = $this->liquidaciones_model->obtenerFacturasRetencion('factura.fact_liquidacionid', $factura_muestra->id_liquidacion);
                $todo_pago = true;

                # Se recorre para saber si todas las estampillas han sido pagadas para terminar la cuota del contrato
                foreach($facturas AS $factura)
                {
                    # Si el saldo no es cero, es decir no ha sido pagada
                    if(floor($factura->valor_total - $factura->valor_pagado) != 0) {
                        $todo_pago = false;
                        break;
                    }
                }

                if($todo_pago)
                {
                    $this->codegen_model->edit(
                        'cuotas_liquidacion',
                        [
                            'estado' => Equivalencias::cuotaPaga()
                        ],
                        'id', $factura_muestra->id_cuota_liquidacion
                    );
                }

				if ($guardo->bandRegistroExitoso)
                {
                    $this->load->library('encrypt');
   
					$this->session->set_flashdata('successmessage', 'Se pagó con éxito la factura');
					$this->session->set_flashdata('idPagoFactura', $this->encrypt->encode(implode(',', $is_pagos), Equivalencias::generadorHash()));
				}
				else{
					$this->session->set_flashdata('errormessage', '<strong>Error!</strong> Ocurrió un error al registrar el pago.');
				}

				$this->session->set_flashdata('accion', 'retencion');
				redirect(base_url().'index.php/liquidaciones/estampillasRetencion/'.$this->input->post('id_contrato'));
			}
			else {
				redirect(base_url().'index.php/error_404');
			}
		} else {
            redirect(base_url().'index.php/users/login');
        }
	}

    private function pagarEstampillaIndividual($id_factura, $id_contrato, $fecha, $observaciones, $valor, $factura = null)
    {
        $ruta_soporte = '';

        if (!isset($_FILES['upload_field_name']) && !is_uploaded_file($_FILES['soporte']['tmp_name'])) 
        {
            // $this->session->set_flashdata('errorModal', true);
            // $this->session->set_flashdata('errormessage', '<strong>Error!</strong> Debe cargar el soporte del pago.');
        }
        else
        {
            $path = 'uploads/pagosFacturas';
            if(!is_dir($path)) { //crea la carpeta para los objetos si no existe
                mkdir($path,0777,TRUE);
            }
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'jpg|jpeg|gif|png|tif|pdf';
            $config['remove_spaces']=TRUE;
            $config['max_size']    = '99999';
            $config['overwrite']    = TRUE;
            $this->load->library('upload');

            $config['file_name'] = $id_factura.'_'.date("F_d_Y");
            $this->upload->initialize($config);

            //Valida si se carga correctamente el soporte
            if ($this->upload->do_upload("soporte"))
            {
                /*
                * Establece la informacion para actualizar la liquidacion
                * en este caso la ruta de la copia del objeto del contrato
                */
                $file_datos= $this->upload->data();
                $ruta_soporte = $path.'/'.$file_datos['orig_name'];
            }
            else {
                $this->session->set_flashdata('errorModal', true);
                $this->session->set_flashdata('errormessage', '<strong>Error!</strong> '.$this->upload->display_errors());
                $this->session->set_flashdata('accion', 'retencion');
                redirect(base_url().'index.php/liquidaciones/estampillasRetencion/'.$id_contrato);
            }
        }

        if($factura === null){
            $factura = $this->liquidaciones_model->obtenerFacturasRetencion('factura.fact_id', $id_factura);
            $factura = $factura[0];
        }

        # Si lo que paga supera el saldo, lo que debe pagar
        if( $valor > floor($factura->valor_total - $factura->valor_pagado) ){
            $this->session->set_flashdata('errorModal', true);
            $this->session->set_flashdata('errormessage', '<strong>Error!</strong> El valor del pago no puede ser mayor que el saldo.');
            $this->session->set_flashdata('accion', 'retencion');
            redirect(base_url().'index.php/liquidaciones/estampillasRetencion/'.$id_contrato);
        }

        $guardo = $this->codegen_model->add(
            'pagos_estampillas',
            array(
                'factura_id'		=> $id_factura,
                'valor'				=> $valor,
                'numero'			=> ($factura->numero_cuota + 1),
                'soporte'			=> $ruta_soporte,
                'fecha'				=> $fecha,
                'observaciones'		=> $observaciones,
                'fecha_insercion'	=> date('Y-m-d H:i:s')
            )
        );

        return $guardo;
    }

    function estampillasRetencion()
    {
		if ($this->ion_auth->logged_in()) {
			if ($this->uri->segment(3)==''){
				redirect(base_url().'index.php/error_404');
			}
			if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar'))
            {
                $this->data['successmessage'] = $this->session->flashdata('successmessage');
                $this->data['errormessage']   = $this->session->flashdata('errormessage');
                $this->data['infomessage']    = $this->session->flashdata('infomessage');
                $this->data['idPagoFactura']  = $this->session->flashdata('idPagoFactura');

				$idcontrato = $this->uri->segment(3);

                $liquidacion = $this->codegen_model->get(
                    'est_liquidaciones',
                    'liqu_id, liqu_valorsiniva AS valor_total, liqu_numero AS numero, liqu_tipocontrato AS tipo_contrato, liqu_vigencia AS vigencia',
                    'liqu_contratoid = '.$idcontrato,
                    1,NULL,true
                );

                $cuota_activa = $this->liquidaciones_model->cuotaLiquidacionActiva('id, valor', $liquidacion->liqu_id);

                $this->data['facturas'] = [];
                $this->data['cuota'] = $cuota_activa;
                $this->data['id_contrato'] = $idcontrato;
                $this->data['liquidacion'] = $liquidacion;

                if($cuota_activa) {
                    $this->data['facturas'] = $this->liquidaciones_model->obtenerFacturasRetencion('factura.id_cuota_liquidacion', $cuota_activa->id);
                }

                $cuotas_pagadas = $this->codegen_model->get(
                    'cuotas_liquidacion',
                    'SUM(valor) AS total',
                    'id_liquidacion = "' . $liquidacion->liqu_id .'" AND estado = '. Equivalencias::cuotaPaga(),
                    1, null, true
                );  
                $this->data['saldo_contrato'] = $liquidacion->valor_total - ($cuotas_pagadas->total ? $cuotas_pagadas->total : 0);

				$this->template->set('title', 'Contrato liquidado');

                $this->data['style_sheets'] = [
                    'css/plugins/dataTables/dataTables.bootstrap.css' => 'screen',
                    'css/plugins/bootstrap/fileinput.css' => 'screen',
                    'css/plugins/bootstrap/bootstrap-switch.css' => 'screen'
                ];
                $this->data['javascripts'] = [
                    'js/jquery.dataTables.min.js',
                    'js/plugins/dataTables/dataTables.bootstrap.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/plugins/dataTables/jquery.dataTables.columnFilter.js',
                    'js/accounting.min.js',
                    'js/plugins/bootstrap/fileinput.min.js',
                    'js/plugins/bootstrap/bootstrap-switch.min.js',
                    'js/autoNumeric.js',
                    'js/applicationEvents.js'
                ];

                $this->template->load($this->config->item('admin_template'),'liquidaciones/liquidaciones_ver_facturas_retencion', $this->data);
			} else {
				redirect(base_url().'index.php/error_404');
			}
		} else {
			redirect(base_url().'index.php/users/login');
		}
	}

    function descuentoEstampilla()
	{
		if ($this->ion_auth->logged_in())
		{
			if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) 
			{
				$this->data['successmessage'] = $this->session->flashdata('message');
				$this->data['errormessage'] = '';

                # Se da formato al valor para que guarde los valores decimales
                $valor = str_replace(',', '.', str_replace('.','',$this->input->post('valor')));

				$this->form_validation->set_rules('id_factura', 'Identificador de la facturas', 'trim|xss_clean|numeric|integer|greater_than[0]');
				$this->form_validation->set_rules('observaciones', 'Observaciones', 'trim|xss_clean');
                $this->form_validation->set_rules('valor', 'valor','required|trim|xss_clean');

				if ($this->form_validation->run() == false) {
					$this->session->set_flashdata('errorModal', true);
					$this->session->set_flashdata('errormessage', (validation_errors() ? validation_errors(): false));
					$this->session->set_flashdata('accion', 'retencion');
					redirect(base_url().'index.php/liquidaciones/liquidar/'.$this->input->post('id_contrato'));
				}

				$guardo = $this->codegen_model->add(
					'descuentos_estampillas',
					array(
						'factura_id'		=> $this->input->post('id_factura'),
						'valor'				=> $valor,
						'observaciones'		=> $this->input->post('observaciones'),
						'fecha_insercion'	=> date('Y-m-d H:i:s')
					)
				);

				if ($guardo->bandRegistroExitoso){
					$this->session->set_flashdata('errorModal', true);
					$this->session->set_flashdata('successmessage', 'Se agregó el descuento con éxito la factura');
				}
				else{
					$this->session->set_flashdata('errorModal', true);
					$this->session->set_flashdata('errormessage', '<strong>Error!</strong> Ocurrió un error al registrar el pago.');
				}

				$this->session->set_flashdata('accion', 'retencion');
				redirect(base_url().'index.php/liquidaciones/liquidar/'.$this->input->post('id_contrato'));
			}
			else {
				redirect(base_url().'index.php/error_404');
			}
		} else {
            redirect(base_url().'index.php/users/login');
        }
	}

    private function obtenerInfoFacturas($idcontrato, $valor = null)
    {
        $respuesta = [];

        $respuesta['result'] = $this->liquidaciones_model->get($idcontrato);
        $contrato = $respuesta['result'];

        $estampillas = $this->liquidaciones_model->getestampillas($contrato->cntr_tipocontratoid);  
        $respuesta['estampillas'] = [];

        /*
        * Valida si el régimen del contratista es otros para calcular el valor
        * restando el valor del IVA suministrado en la creación del contrato
        */
        if($contrato->regi_id == 6 || $contrato->regi_id == 8)
        {
            $valorsiniva = (float)$contrato->cntr_valor - (float)$contrato->cntr_iva_otros;
        }else
            {
                //valida el valor del porcentaje según el regimen
                //del contratista para realizar un calcúlo acertado
                if($contrato->regi_iva > 0)
                {
                    $valorsiniva = (float)$contrato->cntr_valor/(((float)$contrato->regi_iva/100)+1);
    
                    //Formatea el resultado del calculo de valor sin iva
                    //para que redondee por decimales y unidades de mil
                    //ej valorsiniva=204519396.55172 ->decimales -> 204519397 ->centenas ->204519400
                    $sinIvaRedondeoDecimales = round($valorsiniva);
                    $sinIvaRedondeoCentenas = round($sinIvaRedondeoDecimales, -2);  
                    unset($valorsiniva);
                    $valorsiniva = $sinIvaRedondeoCentenas;
                }else
                    {
                        $valorsiniva = (float)$contrato->cntr_valor;
                    }
            }
        $respuesta['valor_verdadero'] = $valorsiniva;
        
        if($valor != null) {
            $valorsiniva = (float)$valor;
        }

        //arreglo que guarda los distintos valores
        //de liquidacion de las estampillas
        $totalestampilla= array();

        $valortotal=0;
        $parametros=$this->codegen_model->get('adm_parametros','para_redondeo,para_salariominimo','para_id = 1',1,NULL,true);

        foreach ($estampillas as $key => $value) 
        {
            /*
            * Se valida si la estampilla a almacenar es pro electrificacion
            * y si la fecha de liquidacion (fecha actual) es mayor al 21 de mayo de 2017
            * no se incluya la estampilla en las liquidaciones según ordenanza 026 de 2007
            */
            $bandRegistrarFactura = Liquidaciones::validarInclusionEstampilla($value->estm_id, $contrato->cntr_fecha_firma, $contrato->cntr_tipocontratoid);
            if($bandRegistrarFactura)
            {
                /*
                * Para la estampilla procultura y que sean contratos de obra civil,
                * suministros y bienes y servicios que superen los 25 salarios se
                * les aplica el porcentaje de la estampilla
                */
                if($value->estm_id == 2 && in_array($contrato->cntr_tipocontratoid, array(2,4,43)) )
                {
                    if( $contrato->cntr_valor >= ($parametros->para_salariominimo * 25) )
                    {
                        $totalestampilla[$value->estm_id] = (($valorsiniva*$value->esti_porcentaje)/100);
                        $totalestampilla[$value->estm_id] = round ( $totalestampilla[$value->estm_id], -$parametros->para_redondeo );
                        array_push($respuesta['estampillas'], $value);
                    }
                }else
                    {
                        $totalestampilla[$value->estm_id] = (($valorsiniva*$value->esti_porcentaje)/100);
                        $totalestampilla[$value->estm_id] = round ( $totalestampilla[$value->estm_id], -$parametros->para_redondeo );
                        array_push($respuesta['estampillas'], $value);
                    }

                /*
                * Valida si el valor establecido para la estampilla es igual a cero
                * para establecer el valor minimo 1000
                */
                if(isset($totalestampilla[$value->estm_id]))
                {
                    if($totalestampilla[$value->estm_id] <= 0)
                    {
                        $totalestampilla[$value->estm_id] = 1000;
                    }

                    /*
                    * Calcula el total a pagar
                    */
                    $valortotal += (double)$totalestampilla[$value->estm_id];
                }
            }
        }

        $respuesta['est_totalestampilla']   = $totalestampilla;
        $respuesta['cnrt_valorsiniva']      = $valorsiniva;
        $respuesta['est_valortotal']        = $valortotal;

        return $respuesta;
    }

    public function registrarCuotaLiquidacion()
    {
        if ($this->ion_auth->logged_in())
        {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar'))
            {
                $this->form_validation->set_rules('id_contrato', 'Identificador del contrato', 'trim|xss_clean|numeric|integer|greater_than[0]'); 
                $this->form_validation->set_rules('valor', 'valor','required|trim|xss_clean');

                if ($this->form_validation->run() == false) {
					$this->session->set_flashdata('errorModal', true);
					$this->session->set_flashdata('errormessage', (validation_errors() ? validation_errors(): false));
					$this->session->set_flashdata('accion', 'retencion');
                    redirect(base_url().'index.php/liquidaciones/estampillasRetencion/'.$this->input->post('id_contrato'));
				}

                # Se da formato al valor para que guarde los valores decimales
                $valor = str_replace(',', '.', str_replace('.','',$this->input->post('valor')));

                $liquidacion = $this->codegen_model->get(
                    'est_liquidaciones',
                    'liqu_id, liqu_valorsiniva AS valor_total',
                    'liqu_contratoid = '.$this->input->post('id_contrato'),
                    1,NULL,true
                );

                $datos = $this->obtenerInfoFacturas( $this->input->post('id_contrato'), $valor );

                $cuotas_pagadas = $this->codegen_model->get(
                    'cuotas_liquidacion',
                    'SUM(valor) AS total',
                    'id_liquidacion = "' . $liquidacion->liqu_id .'" AND estado = '. Equivalencias::cuotaPaga(),
                    1, null, true
                );  
                $saldo_contrato = $liquidacion->valor_total - ($cuotas_pagadas->total ? $cuotas_pagadas->total : 0);

                if($valor > $saldo_contrato){
                    $this->session->set_flashdata('errorModal', true);
                    $this->session->set_flashdata('errormessage', '<strong>Error!</strong> El pago de la cuota no puede ser mayor que el saldo.');
					$this->session->set_flashdata('accion', 'retencion');
                    redirect(base_url().'index.php/liquidaciones/estampillasRetencion/'.$this->input->post('id_contrato'));
                }

                $guardo = $this->codegen_model->add(
					'cuotas_liquidacion',
					array(
						'id_liquidacion'	=> $liquidacion->liqu_id,
						'valor'				=> $valor,
						'estado'		    => Equivalencias::cuotaPendiente(),
						'fecha_creacion'	=> date('Y-m-d H:i:s')
					)
				);

                if ($guardo->bandRegistroExitoso)
                {
                    foreach($datos['estampillas'] AS $factura)
					{
						//Valida si la factura viene en valor cero
						//no guarda factura
						$valor = $datos['est_totalestampilla'][$factura->estm_id];

						if($valor > 0)
						{
							$data = array(
								'fact_nombre'			=> $factura->estm_nombre,
								'fact_porcentaje'		=> $factura->esti_porcentaje,
								'fact_valor'			=> $datos['est_totalestampilla'][$factura->estm_id],
								'fact_banco'			=> $factura->banc_nombre,
								'fact_cuenta'			=> $factura->estm_cuenta,
								'fact_liquidacionid'	=> $liquidacion->liqu_id,
								'fact_estampillaid'		=> $factura->estm_id,
								'fact_rutaimagen'		=> $factura->estm_rutaimagen,
                                'id_cuota_liquidacion'  => $guardo->idInsercion,
							);

							/*
							* Se valida si la estampilla a almacenar es pro electrificacion
							* y si la fecha de liquidacion (fecha actual) es mayor al 21 de mayo de 2017
							* no se incluya la estampilla en las liquidaciones según ordenanza 026 de 2007
							*/
							$bandRegistrarFactura = Liquidaciones::validarInclusionEstampilla(
                                $data['fact_estampillaid'],
                                $datos['result']->cntr_fecha_firma,
                                $datos['result']->cntr_tipocontratoid
                            );
							if($bandRegistrarFactura)
							{
								$respuestaProceso = $this->codegen_model->add('est_facturas',$data);

								/**
								* Solicita la Asignación del codigo para el codigo de barras
								*/
								$this->asignarCodigoParaBarras($liquidacion->liqu_id, $factura->estm_id);
							}
							
						}
					}

					$this->session->set_flashdata('successmessage', 'Se registro el valor de la cuota');
				}
				else{
					$this->session->set_flashdata('errormessage', '<strong>Error!</strong> Ocurrió un error al registrar el pago.');
				}

                redirect(base_url().'index.php/liquidaciones/estampillasRetencion/'.$this->input->post('id_contrato'));
            } else {
                redirect(base_url().'index.php/error_404');
            }
        } else {
            redirect(base_url().'index.php/users/login');
        }
    }

}
