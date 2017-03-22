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
	  $this->load->helper(array('form','url','codegen_helper'));
      $this->load->model('liquidaciones_model','',TRUE);
      $this->load->model('codegen_model','',TRUE);
	}
	
	function index()
    {
		  $this->liquidar();
	}

	
  function liquidar()
  {
      if ($this->ion_auth->logged_in()){

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')){

              $this->data['errorModal']=$this->session->flashdata('errorModal');
              $this->data['successmessage']=$this->session->flashdata('successmessage');
              $this->data['errormessage']=$this->session->flashdata('errormessage');
              $this->data['infomessage']=$this->session->flashdata('infomessage');
              $this->data['accion']=$this->session->flashdata('accion');
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
              $idcontrato=$this->uri->segment(3);
              $this->data['result'] = $this->liquidaciones_model->get($idcontrato);
              $contrato = $this->data['result'];
   
              $estampillas = $this->liquidaciones_model->getestampillas($contrato->cntr_tipocontratoid);  
              $this->data['estampillas'] = [];

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

              //arreglo que guarda los distintos valores
              //de liquidacion de las estampillas    
              $totalestampilla= array(); 

              $valortotal=0;
              $parametros=$this->codegen_model->get('adm_parametros','para_redondeo,para_salariominimo','para_id = 1',1,NULL,true);
              
            foreach ($estampillas as $key => $value) 
            {
                /*
                * Valida si el tipo de estampilla es Pro-Hospitales
                * y el municipio del contrato es distinto a Ibagué
                * para no liquidar la estampilla
                */
                /************************
                *
                * SE COMENTA ESTA VALIDACION MIENTRAS EL INGENIERO EVELIO
                * DEFINE CUANDO DEBE SER HABILITADA
                * SOLO SE DEBE MODIFICAR EL MUNICIPIO DE ORIGEN AL ID DEL MUNICIPIO
                * QUE EL INGE EVELIO DIGA QUE NO SE DEBE COBRAR LA ESTAMPILLA PRO HOSPITALES
                */
                //if(($value->estm_id == 4 && $contrato->cntr_municipio_origen != 1028) || $value->estm_id != 4)
                //{
                    //Realiza la validación para los contratos de tipo
                    //consultoria o concesión y que el valor del contrato
                    //sea >= 10 SMMLV para aplicar la estampilla pro grandeza de colombia                
                    if($value->estm_id == 8)
                    {
                        if($contrato->cntr_tipocontratoid==9 || $contrato->cntr_tipocontratoid==7)
                        {
                             $valor10SMMLV = $parametros->para_salariominimo * 10;
    
                             if($contrato->cntr_valor >= $valor10SMMLV)
                             {
                                  $totalestampilla[$value->estm_id] = (($valorsiniva*$value->esti_porcentaje)/100);
                                  $totalestampilla[$value->estm_id] = round ( $totalestampilla[$value->estm_id], -$parametros->para_redondeo );
                                  array_push($this->data['estampillas'], $value);
                             }
                        }else
                            {
                                 $totalestampilla[$value->estm_id] = (($valorsiniva*$value->esti_porcentaje)/100);
                                 $totalestampilla[$value->estm_id] = round ( $totalestampilla[$value->estm_id], -$parametros->para_redondeo );
                                 array_push($this->data['estampillas'], $value);  
                            }
                    }else
                        {
                             $totalestampilla[$value->estm_id] = (($valorsiniva*$value->esti_porcentaje)/100);
                             $totalestampilla[$value->estm_id] = round ( $totalestampilla[$value->estm_id], -$parametros->para_redondeo );
                             array_push($this->data['estampillas'], $value);
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
                //}
            }

              $this->data['est_totalestampilla']=$totalestampilla;
              $this->data['cnrt_valorsiniva']=$valorsiniva;
              $this->data['est_valortotal']=$valortotal;
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
      if ($this->ion_auth->logged_in()) {

          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {

            /*
            * Extrae el usuario autenticado para establecer que usuario
            * realizó la liquidación
            */
            $usuario = $this->ion_auth->user()->row();

            $codigo = 00000;
            $idcontrato=$this->input->post('idcontrato');
            $data = array(
                'liqu_contratoid' => $this->input->post('idcontrato'),
                'liqu_nombrecontratista' => $this->input->post('nombrecontratista'),
                'liqu_nit' => $this->input->post('nit'),
                'liqu_tipocontratista' => $this->input->post('tipocontratista'),
                'liqu_numero' => $this->input->post('numero'),
                'liqu_vigencia' => $this->input->post('vigencia'),
                'liqu_valorconiva' => $this->input->post('valorconiva'),
                'liqu_valorsiniva' => $this->input->post('valorsiniva'),
                'liqu_tipocontrato' => $this->input->post('tipocontrato'),
                'liqu_regimenid' => $this->input->post('idregimen'),
                'liqu_regimen' => $this->input->post('regimen'),
                'liqu_nombreestampilla' => $this->input->post('nombreestampilla'),
                'liqu_cuentas' => $this->input->post('cuentas'),
                'liqu_porcentajes' => $this->input->post('porcentajes'),
                'liqu_totalestampilla' => $this->input->post('totalestampillas'),
                'liqu_valortotal' => $this->input->post('valortotal'),
                'liqu_comentarios' => $this->input->post('comentarios'),
                'liqu_codigo' => $codigo,
                'liqu_fecha' => date('Y-m-d'),
                'liqu_usuarioliquida' => $usuario->id,
                'liqu_tiempoliquida' => date('Y-m-d H:i:s')
                );

              if ($this->codegen_model->add('est_liquidaciones',$data) == TRUE) {
              	  $liquidacionid=$this->db->insert_id();
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
                        * Se valida que el usuario loggeado que va a liquidar 
                        * sea heberth por si liquidan en otra parte no se 
                        * alteren los contratos el array debe ir asi
                        * idEstampilla => valor
                        $usuario = $this->ion_auth->user()->row();
                        if($usuario->id == 93414560) 
                        {     
                            $facturaExcedente = array(4 => 385, 9 => 385, 7 => 193);
                            foreach($facturaExcedente as $idEstampilla => $valorAPoner)     
                            {         
                                if($data['fact_estampillaid'] == $idEstampilla)         
                                {             
                                    $data['fact_valor'] = $valorAPoner;
                                }     
                            }  
                        }
                        */ 
                       
                       $this->codegen_model->add('est_facturas',$data);
                       
                       /**
                       * Solicita la Asignación del codigo para el codigo de barras
                       */
                       $this->asignarCodigoParaBarras($liquidacionid,$this->input->post('idestampilla'.$i));
                    }
                    
                  }

                 
                  $data = array(
                   'cntr_estadolocalid' => 1,
                   );
                  if ($this->codegen_model->edit('con_contratos',$data,'cntr_id',$idcontrato) == TRUE) {
                      
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
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar')) {
              $idcontrato=$this->uri->segment(3);
              $this->data['result'] = $this->liquidaciones_model->getrecibos($idcontrato);
              $liquidacion = $this->data['result'];
              $this->data['facturas'] = $this->liquidaciones_model->getfacturas($liquidacion->liqu_id);


              //$todopago variable bandera indica si la totalidad de facturas 
              //no se han pagado
              $todopago=0;
              $numerocomprobantes=0;
              $ncomprobantescargados=0;
              $totalpagado=0;
              //vector $comprobantecargado
              //almacena la relacion indice->valor
              //tal que ==>    (id factura)->(true)
              //si no se ha cargado comprobante para
              //esa factura.   (id factura)->(false)
              //si ya se cargó comprobante
              $comprobantecargado=array();
              $facturapagada=array();
              $facturas=$this->data['facturas']; 

              //Itera por las filas de informacion de las facturas
              //creadas para cada estampilla asignada al contrato
              foreach ($facturas as $key => $value) {
                  $totalpagado += $value->pago_valor;
                  $numerocomprobantes++;  

                  //si el valor en la tabla pagos es mayor o igual
                  //al valor de la factura de la estampilla
                  //se asigna al vector $facturapagada el indice 
                  //del id de la factura y el valor true
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
                                $this->codegen_model->add('est_pagos',$datos);                                
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
                  $this->data['result']->litr_tramitadornombre = $tramite->litr_tramitadornombre.' - Numero de Placa ('.$tramite->litr_placaVehiculo.')';
              }

              $parametros=$this->codegen_model->get('adm_parametros','para_redondeo,para_salariominimo','para_id = 1',1,NULL,true);
              $this->data['estampillas'] = $this->liquidaciones_model->getestampillastramites($this->data['result']->litr_tramiteid);

              $estampillas=$this->data['estampillas'];   

              //calcula el SMDLV
              $salarioMinimoDiario = round((float)$parametros->para_salariominimo/30,0);

              $totalestampilla= array();
              $valortotal=0;
              
              foreach ($estampillas as $key => $value) {
                
                 $totalestampilla[$value->estm_id] = (($salarioMinimoDiario*$value->estr_porcentaje)/100);
                 $totalestampilla[$value->estm_id] = Liquidaciones::ceiling($totalestampilla[$value->estm_id], 1000);                 
                 $valortotal+=$totalestampilla[$value->estm_id];
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
  function ceiling($number, $significance = 1)
  {
        return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;
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
                  
              if ($this->codegen_model->add('est_liquidaciones',$data) == TRUE) {
                  $liquidacionid=$this->db->insert_id();

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

                          $this->codegen_model->add('est_facturas',$data);

                          /**
                          * Solicita la Asignación del codigo para el codigo de barras
                          */
                          $this->asignarCodigoParaBarras($liquidacionid,$this->input->post('idestampilla'.$i));
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

              $this->form_validation->set_rules('encontrado', 'encontrado','required|trim|xss_clean|numeric');
              $this->form_validation->set_rules('documento', 'Documento',  'required|trim|xss_clean');  
              $this->form_validation->set_rules('nombre', 'Nombre',  'required|trim|xss_clean');  
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
                    $data = array(
                        'litr_tramiteid' => $this->input->post('tramiteid'),
                        'litr_tramitadorid' => $this->input->post('documento'),
                        'litr_tramitadornombre' => $this->input->post('nombre'),
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
                     
                    if ($this->codegen_model->add('est_liquidartramites',$data) == TRUE) 
                    {
                        $id=$this->db->insert_id();
                        $this->session->set_flashdata('message','La liquidación se realizó con éxito');
                        $this->session->set_flashdata('accion', 'creado');
                        redirect(base_url().'index.php/liquidaciones/liquidartramites/'.$id);
                    }else 
                        {
                            $this->data['errormessage'] = 'No se pudo realizar la liquidación';
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
            
              $tramitador= $this->codegen_model->get('est_liquidartramites','litr_tramitadorid,litr_tramitadornombre','litr_tramitadorid = '.$this->input->post('documento'),1,NULL,true);
              if ($tramitador) {
                 echo $tramitador->litr_tramitadornombre;
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
              $this->datatables->select('l.litr_id,l.litr_tramitadorid,l.litr_tramitadornombre,tr.tram_nombre,l.litr_fechaliquidacion,l.litr_observaciones,el.eslo_nombre');
              $this->datatables->from('est_liquidartramites l');              
              $this->datatables->join('est_tramites tr', 'tr.tram_id = l.litr_tramiteid', 'left');
              $this->datatables->join('con_estadoslocales el', 'el.eslo_id = l.litr_estadolocalid', 'left');
              $this->datatables->add_column('edit', '-');
              echo $this->datatables->generate();

          } else {
              redirect(base_url().'index.php/error_404');
          }
               
      } else{
              redirect(base_url().'index.php/users/login');
      }           
  }




  function liquidaciones_datatable ()
  { 
      if ($this->ion_auth->logged_in()) {
          
          if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/liquidar') ) { 
              
              $this->load->library('datatables');
              $this->datatables->select('c.cntr_id,c.cntr_numero,co.cont_nit,co.cont_nombre,c.cntr_fecha_firma,c.cntr_objeto,c.cntr_valor,el.eslo_nombre');
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
               * Exrae el listado de tipos de actos liquidados para realizar
               * la consulta
               */
              $tiposContrato = $this->codegen_model->getSelect('con_tiposcontratos',"tico_id,tico_nombre");
              $tiposTramite = $this->codegen_model->getSelect('est_tramites',"tram_id,tram_nombre");

              $vTiposActo = array();
              if(count($tiposContrato) > 0)
              {
                  foreach($tiposContrato as $tipoA)
                  {
                      $vTiposActo['c_'.$tipoA->tico_id] = $tipoA->tico_nombre.' ( Contrato )';
                  }
              }

              if(count($tiposTramite) > 0)
              {
                  foreach($tiposTramite as $tipoA)
                  {
                      $vTiposActo['t_'.$tipoA->tram_id] = $tipoA->tram_nombre.' ( Tramite )';
                  }
              }

              $this->data['actos'] = $vTiposActo;

              /*
               * Extrae los contratistas creados para filtrar por contratos
               */
              $contratistas = $this->codegen_model->getSelect('con_contratistas',"cont_id,cont_nombre,cont_nit");
              $tramitadores = $this->codegen_model->getSelect('est_liquidartramites',"litr_id,litr_tramitadornombre,litr_tramitadorid",'','','GROUP BY litr_tramitadorid');

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
                      $vecContribuyentes['t_'.$tramitador->litr_tramitadorid] = $tramitador->litr_tramitadorid.' - '.$tramitador->litr_tramitadornombre.' ( Tramitador )';
                  }
              }

              $this->data['contribuyentes'] = $vecContribuyentes;

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

              $this->load->library('datatables');
              $this->datatables->select('l.liqu_id,l.liqu_tipocontrato,l.liqu_nit,l.liqu_nombrecontratista,l.liqu_valortotal,l.liqu_fecha, p.pago_fecha, f.fact_valor, f.fact_nombre');              
              $this->datatables->from('est_facturas f');  
              $this->datatables->join('est_liquidaciones l', 'l.liqu_id = f.fact_liquidacionid', 'left');
              $this->datatables->join('est_pagos p', 'p.pago_facturaid = f.fact_id', 'left');
              $this->datatables->where($whereIn);

                           

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
                            $objContin = $this->codegen_model->get('adm_parametros','para_contingencia','para_id = 1',1,NULL,true);
                            if($objContin->para_contingencia == 1)
                            {
                                $contingencia = 'SI';
                            }else
                                {
                                    $contingencia = 'NO';
                                }

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
                              
                                    $this->codegen_model->add('est_impresiones',$data);

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
                                            $this->codegen_model->add('est_detconpap',$datos);
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

  function renderizarPDF()
  {
    if ($this->ion_auth->logged_in()) 
    {
        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/consultar') ) 
        {
            $resultadosFiltros = Liquidaciones::extraerRegistrosDetalleImpresiones($_GET);

                /*
                * Valida si hubo resultados para generar el pdf
                */
                if(count($resultadosFiltros['vec_liquidaciones']) <= 0)
                {
                    $this->session->set_flashdata('errormessage', 'La fecha elegida no presenta registros!'); 
                    redirect(base_url().'index.php/liquidaciones/consultar');
                }

                $datos['fecha'] = $resultadosFiltros['fecha'];

                $datos['liquidaciones'] = $resultadosFiltros['vec_liquidaciones'];

                //Creación del PDF
                $this->load->library("Pdf");                  
                $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $pdf->setPageOrientation('l');

                // set document information
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('turrisystem');
                $pdf->SetTitle('Listado de Impresiones');
                $pdf->SetSubject('Gobernación del Tolima');
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
                * Valida que fecha llega a la vista para preparar la leyenda
                */
                if(isset($fechaUnica) && $fechaUnica != '')
                {
                    $pdf->Output('Impresiones_'.$datos['fecha'].'.pdf', 'I');
                }else
                    {
                        $pdf->Output('Impresiones'.str_replace(' ','_',$datos['fecha']).'.pdf', 'I');
                    }
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
   function extraerRegistrosDetalleImpresiones($vectorGet)
    {
        $fecha_inicial = $vectorGet['fecha_I'];
        $tipoEst = $vectorGet['est'];
        $tipoActo = $vectorGet['acto'];
        $contribuyente = $vectorGet['contribuyente'];

        /*
        * Valida si llega la fecha final o no
        */
        if(isset($vectorGet['fecha_F']))
        {
            $fecha_final = $vectorGet['fecha_F'];
        }else
        {
            $fecha_final = "";
        }

        /*
        * Valida que lleguen fechas
        */
        if($fecha_inicial == "" && $fecha_final == "")
        {
            $this->session->set_flashdata('errormessage', 'Debe Elegir un Rango de Fechas Valido!');
            redirect(base_url().'index.php/liquidaciones/consultar');
        }

        /*
         * Extrae el posible tipo de acto
         */
        $bandValidarActo = false;
        if($tipoActo != '0')
        {
            $bandValidarActo = true;
            if(preg_match('/^c_([0-9]+)$/',$tipoActo,$coincidencias))
            {
                $id_acto = $coincidencias[1];
                $t_acto = 'contrato';
            }elseif(preg_match('/^t_([0-9]+)$/',$tipoActo,$coincidencias))
            {
                $id_acto = $coincidencias[1];
                $t_acto = 'tramite';
            }
        }

        /*
        * Se Validan los valores que llegan para construir el where
        */
        $where = 'WHERE i.impr_estado = 1 ';
        if($fecha_inicial != "" && $fecha_final != "")
        {
            $where .= ' AND date_format(i.impr_fecha,"%Y-%m-%d") BETWEEN "'.$fecha_inicial.'" AND "'.$fecha_final.'"';
        }
        if($fecha_inicial != "" && $fecha_final == "")
        {
            $where .= ' AND date_format(i.impr_fecha,"%Y-%m-%d") = "'.$fecha_inicial.'"';
            //Bandera para la leyenda de la fecha
            $fechaUnica = $fecha_inicial;
        }
        if($fecha_final != "" && $fecha_inicial == "")
        {
            $where .= ' AND date_format(i.impr_fecha,"%Y-%m-%d") = "'.$fecha_final.'"';
            //Bandera para la leyenda de la fecha
            $fechaUnica = $fecha_final;
        }

        /*
        * Crea la consulta para el perfil de administrador o Usuario conciliación
        * para mostrar todas las impresiones
        */
        $usuario = $this->ion_auth->user()->row();
        $join = '';

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
        $whereIn = 'where l.liqu_id in '.$idLiquidaciones;
        $join2 = ' INNER JOIN est_liquidaciones l ON l.liqu_id = f.fact_liquidacionid';

        $campos = 'l.liqu_contratoid,l.liqu_tramiteid,l.liqu_id,l.liqu_tipocontrato,l.liqu_nit,l.liqu_nombrecontratista,l.liqu_valortotal,l.liqu_valorsiniva,l.liqu_fecha';
        $where = $whereIn;
        $group = 'GROUP BY l.liqu_id';

        /*
         * Valida si se suministró el id de un contribuyente para
         * realizar el filtrado en las liquidaciones
         */
        $whereContribuyente = '';
        if($contribuyente != '0')
        {
            preg_match('/^[t|c]_([0-9\-]+)$/',$contribuyente,$coincidencia);
            $whereContribuyente = ' AND l.liqu_nit = "'. $coincidencia[1] .'" ';
        }

        $where .= $whereContribuyente;

        $liquidaciones = $this->codegen_model->getSelect('est_facturas f',$campos,$where,$join2, $group);

        $vLiquidaciones = array();
        if($liquidaciones)
        {
            /*
            * Vector para almacenar las liquidaciones
            * que si tuvieron resultados, para enviarlas
            * a la vista
            */
            foreach ($liquidaciones as $liquidacion)
            {
                $where = 'where f.fact_liquidacionid = ' . $liquidacion->liqu_id;
                $join3 = ' INNER JOIN est_impresiones i ON i.impr_facturaid=f.fact_id';
                $join3 .= ' INNER JOIN est_pagos pag ON pag.pago_facturaid=f.fact_id';
                $join3 .= ' INNER JOIN est_papeles p ON i.impr_papelid=p.pape_id';
                $join3 .= ' INNER JOIN users u ON u.id=p.pape_usuario';

                /*
                * Se valida si llegó tipo de estampilla para construir
                * la consulta con la restriccion
                */
                if ($tipoEst != 0) {
                    $where .= ' AND f.fact_estampillaid = ' . $tipoEst;
                }

                $resultado = $this->codegen_model->getSelect('est_facturas f', "f.fact_nombre, f.fact_valor, u.first_name, u.last_name, u.id, i.impr_fecha, i.impr_codigopapel, pag.pago_fecha", $where, $join3);

                /*
                * Valida si hubo resultado para incluir o no la liquidacion
                * en el grupo para el informe
                */
                if (count($resultado) > 0)
                {
                    $bandAgregar = true;
                    if ($bandValidarActo) {
                        /*
                         * Valida si se debe agregar el registro a la coleccion
                         * dependiendo si es del tipo de acto suministrado o no
                         */
                        $bandAgregar = false;
                        if ($liquidacion->liqu_contratoid != '0' && $t_acto == 'contrato') {
                            $datosContrato = $this->codegen_model->getSelect('con_contratos c', 'c.cntr_numero, c.cntr_valor, c.cntr_tipocontratoid', 'WHERE cntr_id = ' . $liquidacion->liqu_contratoid);
                            if ($datosContrato[0]->cntr_tipocontratoid == $id_acto) {
                                $bandAgregar = true;
                            }
                        } elseif ($liquidacion->liqu_tramiteid != '0' && $t_acto == 'tramite') {
                            $datosTramite = $this->codegen_model->getSelect('est_liquidartramites t', 't.litr_tramiteid', 'WHERE litr_id = ' . $liquidacion->liqu_tramiteid);
                            if ($datosTramite[0]->litr_tramiteid == $id_acto) {
                                $bandAgregar = true;
                            }
                        }
                    }

                    if ($bandAgregar)
                    {
                        /*
                        * Agrega el objeto de la liquidacion al vector
                        * que irá a la vista
                        */
                        $vLiquidaciones[] = $liquidacion;

                        $facturas = [];
                        $liquidador = '';
                        $cantEstampillas = 0;
                        foreach ($resultado as $value) {
                            $facturas[] = ['tipo' => $value->fact_nombre,
                                'rotulo' => $value->impr_codigopapel,
                                'valor' => $value->fact_valor,
                                'fecha_impr' => $value->impr_fecha,
                                'fecha_pago' => $value->pago_fecha];
                            /*
                            * Valida que el nombre del liquidador no haya sido asignado
                            * para asignarlo una sola vez
                            */
                            if ($liquidador == '') {
                                $liquidador = strtoupper($value->first_name)
                                    . ' ' . strtoupper($value->last_name)
                                    . '<br>' . $value->id;
                            }
                            /*
                            * Cuenta la cantidad de estampillas para establecer
                            * maquetacion en la renderizacion del listado
                            */
                            $cantEstampillas++;
                        }
                        $liquidacion->liquidador = $liquidador;
                        $liquidacion->estampillas = $facturas;
                        $liquidacion->cantEstampillas = $cantEstampillas;

                        /*
                        * Valida si la liquidacion fue de tramite o de contrato
                        * para extraer el numero de contrato y el valor del acto
                        */
                        if ($liquidacion->liqu_contratoid != 0)
                        {
                            $datosContrato = $this->codegen_model->getSelect('con_contratos c', 'c.cntr_numero, c.cntr_valor, c.cntr_tipocontratoid', 'WHERE cntr_id = ' . $liquidacion->liqu_contratoid);
                            $liquidacion->numActo = $datosContrato[0]->cntr_numero;
                            $liquidacion->valorActo = $datosContrato[0]->cntr_valor;
                            $liquidacion->liqu_tipocontrato = 'Contrato '.$liquidacion->liqu_tipocontrato;
                        } else
                            {
                                $datosTramite = $this->codegen_model->getSelect('est_liquidartramites t', 't.litr_tramiteid', 'WHERE litr_id = ' . $liquidacion->liqu_tramiteid);
                                $nombreTramite = $this->codegen_model->getSelect('est_tramites ta', 'ta.tram_nombre', 'WHERE tram_id = ' . $datosTramite[0]->litr_tramiteid);
                                $liquidacion->numActo = 'N/A';
                                $liquidacion->valorActo = $liquidacion->liqu_valorsiniva;
                                $liquidacion->liqu_tipocontrato = $nombreTramite[0]->tram_nombre;
                            }
                    }
                }
            }
        }

        /*
        * Valida que fecha llega a la vista para preparar la leyenda
        */
        if(isset($fechaUnica) && $fechaUnica != '')
        {
            $fecha = $fechaUnica;
        }else
            {
                $fecha = 'PERIODO COMPRENDIDO ENTRE LAS FECHAS'.$fecha_inicial.' Y '.$fecha_final;
            }

        return array(
            'vec_liquidaciones' => $vLiquidaciones,
            'fecha' => $fecha
        );
    }


/**
* Funcion que ordena la renderizacion o no del EXCEL
* de las liquidaciones de la fecha especificada
* Mike Ortiz
*/
function renderizarExcel()
{
    if ($this->ion_auth->logged_in()) 
    {
        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('liquidaciones/consultar') ) 
        {
            $resultadosFiltros = Liquidaciones::extraerRegistrosDetalleImpresiones($_GET);

            /*
            * Valida si hubo resultados para generar el pdf
            */
            if(count($resultadosFiltros['vec_liquidaciones']) <= 0)
            {
                $this->session->set_flashdata('errormessage', 'La fecha elegida no presenta registros!');
                redirect(base_url().'index.php/liquidaciones/consultar');
            }

            $datos['fecha'] = $resultadosFiltros['fecha'];

            $datos['liquidaciones'] = $resultadosFiltros['vec_liquidaciones'];

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
                    $pdf->SetSubject('Gobernación del Tolima');
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
function renderizarRangoImpresionesPDF()
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
            $fecha_inicial = $_GET['fecha_I'];
            $fecha_final = $_GET['fecha_F'];
            
            /*
            * Valida que lleguen fechas
            */
            if($fecha_inicial == "" && $fecha_final == "")
            {
                $this->session->set_flashdata('errormessage', 'Debe Elegir un Rango de Fechas Valido!'); 
                redirect(base_url().'index.php/liquidaciones/consultar');
            }

            /*
            * Se Validan los valores que llegan para construir el where
            */
            $where = 'WHERE i.impr_estado = 1 ';
            if($fecha_inicial != "" && $fecha_final != "")
            {
                $where .= ' AND date_format(i.impr_fecha,"%Y-%m-%d") BETWEEN "'.$fecha_inicial.'" AND "'.$fecha_final.'"';
                /*
                * Agrega al vector de parametro para la vista
                * las fechas de rango
                */
                $datos['fecha_i'] = $fecha_inicial;
                $datos['fecha_f'] = $fecha_final;
            }
            if($fecha_inicial != "" && $fecha_final == "")
            {
                $where .= ' AND date_format(i.impr_fecha,"%Y-%m-%d") = "'.$fecha_inicial.'"';
                /*
                * Agrega al vector de parametro para la vista
                * las fecha unica
                */
                $datos['fecha_u'] = $fecha_inicial;                
            }
            if($fecha_final != "" && $fecha_inicial == "")
            {
                $where .= ' AND date_format(i.impr_fecha,"%Y-%m-%d") = "'.$fecha_final.'"';
                /*
                * Agrega al vector de parametro para la vista
                * las fecha unica
                */
                $datos['fecha_u'] = $fecha_final; 
            }                                
            
            $join = ' INNER JOIN est_facturas f ON i.impr_facturaid=f.fact_id ';
            $groupby = ' GROUP BY f.fact_estampillaid';  
            $campos = 'date_format(i.impr_fecha,"%Y-%m-%d") as fecha, f.fact_estampillaid, f.fact_nombre, count(f.fact_estampillaid) as cant, sum(f.fact_valor) as valor ';
  
            $estampillas = $this->codegen_model->getSelect('est_impresiones i',$campos,$where,$join,$groupby);
  
            if($estampillas)
            {                
                /*
                * Calcula el total de estampillas impresas en el rango
                */
                $total = 0;
                $valorTotal = 0;
                foreach ($estampillas as $estampilla) 
                {   
                    $total += $estampilla->cant;
                    $valorTotal += $estampilla->valor;
                }                
                
                $usuario = $this->ion_auth->user()->row();
                $datos['usuario'] = $usuario->first_name.' '.$usuario->last_name;
                $datos['estampillas'] = $estampillas;
                $datos['total'] = $total;      
                $datos['valorTotal'] = $valorTotal;
                
                //Creación del PDF
                $this->load->library("Pdf");                  
                $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);                  
      
                // set document information
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('turrisystem');
                $pdf->SetTitle('Relacion Estampillas Impresas Rango');
                $pdf->SetSubject('Gobernación del Tolima');
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
                $html = $this->load->view('generarpdf/generarpdf_relacionRangoEstampillas', $datos, TRUE);  
                      
                $pdf->writeHTML($html, true, false, true, false, '');
                 
      
                // ---------------------------------------------------------
                //para evitar el error de que se ha impreso algo antes de enviar
                //el PDF 
                ob_end_clean();
                //Close and output PDF document
                $pdf->Output('Relacion_Entrega_Estampillas_Rango.pdf', 'I');                
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

        if ($usuarioLogueado->perfilid==4)
        {
            $datosConsecutivo = Liquidaciones::determinarSiguienteRotulo($usuarioLogueado);
            if($datosConsecutivo['nuevoIngreso'] == 'NO')
            {
                $datosConsecutivo['nuevoIngreso'] = '(No posee papeleria para imprimir)';
            }
            echo json_encode(['rotulo' => $datosConsecutivo['nuevoIngreso']]);
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

    //verifica si ya habia asignado por lo menos
    //un consecutivo a una impresion
    //de lo contrario elige el primer codigo

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
                       

    //extrae los posibles rangos de papeleria asignados
    //al usuario que se encuentra logueado que debe ser
    //un liquidador, en los que pueda estar el nuevo 
    //codigo a asignar
                   
    $papeles = $this->codegen_model->get('est_papeles','pape_id'
        .',pape_codigoinicial,pape_codigofinal',
        'pape_codigoinicial <= '.$nuevoingreso
        .' AND pape_codigofinal >='
        .$nuevoingreso
        .' AND pape_estadoContintencia = "'. $contingencia .'"'
        .' AND pape_usuario = '.$usuarioLogueado->id,1,NULL,true);


    //verifica que exista un rango de papeleria asignado
    //al liquidador en el que se encuentre el posible
    //codigo a registrar
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
            //extrae los posibles rangos de papeleria asignados
            //al usuario que se encuentra logueado que debe ser
            //un liquidador                   
            $papelesAsignados = $this->codegen_model->getSelect('est_papeles','pape_id'
                .',pape_codigoinicial,pape_codigofinal',                                    
                ' where pape_usuario = '. $usuarioLogueado->id .' AND pape_estadoContintencia = "'. $contingencia .'"', '', '',
                'order by pape_codigoinicial');
            

            foreach ($papelesAsignados as $value) 
            {
                if($nuevoingreso < (int)$value->pape_codigoinicial)
                {
                    $nuevoingreso = (int)$value->pape_codigoinicial;

                    //comprueba si ya se está usando el codigo del papel
                    //en alguna impresión y ademas que no se salga del rango
                    //si sale del rango va y compara con el rango siguiente
                    $nousado=0;

                    while ($nousado==0 && $nuevoingreso <= (int)$value->pape_codigofinal)
                    {
                        $combrobacionImpresiones = $this->codegen_model->get('est_impresiones','impr_id','impr_codigopapel = '. $nuevoingreso .' AND impr_estadoContintencia = "'. $contingencia .'"',1,NULL,true);
    
                        if(!$combrobacionImpresiones) 
                        {
                            $nousado=1;
                        }else
                            {
                                $nuevoingreso++;
                            }                                                  
                    }

                    //valida si ya se encontró un codigo para asignar
                    //y rompe el ciclo foreach
                    if($nousado == 1)
                    {
                        $idRangoPapel = $value->pape_id;
                        $banPapelDisponible = true;
                        break;
                    }
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

}
