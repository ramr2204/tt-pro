<?php

class Pagos extends MY_Controller {
		
		function __construct() {
				parent::__construct();
		$this->load->library('form_validation');		
		$this->load->helper(array('form','url','codegen_helper'));
		$this->load->model('codegen_model','',TRUE);

	}	
	 
	function index(){
		$this->manage();
	}

	function manage(){
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('facturas/manage'))
							 {
								//template data
								$this->template->set('title', 'Planilla única');
								$this->data['style_sheets']= array(
														'css/jquery.dataTables_themeroller.css' => 'screen'
												);
								$this->data['javascripts']= array(
														'js/jquery.dataTables.min.js',
														'js/jquery.dataTables.defaults.js'
												);
								$this->data['message']=$this->session->flashdata('message');
								$this->template->load($this->config->item('admin_template'), 'facturas/facturas_list',$this->data); 
							 }else {
								$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
											redirect(base_url().'inicio');
							 } 

						}else
						{
							redirect(base_url().'users/login');
						}
		}
	
		function cargar(){        
				if ($this->ion_auth->logged_in())
			  {
				   if ($this->ion_auth->is_admin() || $this->ion_auth->in_group_menu('facturas/cargar'))
				   {   
               $this->form_validation->set_rules('archivo', 'Archivo', '');
				   	   if ($this->form_validation->run() === TRUE)
               { 
                  $this->data['message'] = '';                   
                  $path = "uploads/facturas";
                  if(!is_dir($path)) //create the folder if it's not already exists
                  {
                    mkdir($path,0777,TRUE);      
                  }
                  $config['upload_path'] = $path;
                  $config['allowed_types'] = 'txt';
                  $config['remove_spaces']=TRUE;
                  $config['max_size']    = '2048';

                  $this->load->library('upload', $config);

                  if ($this->upload->do_upload("archivo")) 
                  {  
                     $this->load->helper('path');
                     $this->load->helper('file');
                     $file_data= $this->upload->data();
                     $path2 = $path."/".$file_data['raw_name'].".txt";
                     $string = file_get_contents($path2);
                     if(set_realpath($path2)){
                       $this->data['message'] = substr($string, 0,20);
                       //$this->load->helper('pdfexport');
                        $this->load->library("Pdf");
                        $this->data= array(
														'numerofactura'=>'0001',
														'nombre'=>'nombre',
														'nombre2'=>'nombre2',
														'texto'=> $string 
												);
                     
                        $this->data['message'] = substr($string, 0,20);
                       // $data['title'] = "Annual Report"; // it can be any variable with content that the code will use

                       $fileName = date('YmdHis');
                       $pdfView  = $this->load->view('facturas/pdf_template', $this->data, TRUE); // we need to use a view as PDF content
                       $cssView  = $this->load->view('facturas/pdf_template_css', NULL, TRUE); // the use a css stylesheet is optional
                       
                        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);   
 
    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Oficina de servicios públicos domiciliarios de Anzoátegui');
    $pdf->SetTitle('Oficina de servicios públicos domiciliarios de Anzoátegui');
    $pdf->SetSubject('NIT:890.772.018-4');
    $pdf->SetKeywords('');  
 
    // set default header data
    /*$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
    $pdf->setFooterData(array(0,64,0), array(0,64,128));
 
    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA)); 
 
    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
 
    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);   
 
    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
 
    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
 
    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
    }  
 */
    // ---------------------------------------------------------   
 
    // set default font subsetting mode
    $pdf->setFontSubsetting(true);  
 
    // Set font
    // dejavusans is a UTF-8 Unicode font, if you only need to
    // print standard ASCII chars, you can use core fonts like
    // helvetica or times to reduce file size.
    $pdf->SetFont('dejavusans', '', 14, '', true);  
 
    // Add a page
    // This method has several options, check the source code documentation for more information.
    $pdf->AddPage();
 
    // set text shadow effect
    $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));   
 
    // Set some content to print
    $html = <<<EOD
    <h1>Welcome to <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a>!</h1>
    <i>This is the first example of TCPDF library.</i>
    <p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>
    <p>Please check the source code documentation and other examples for further information.</p>
    <p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT, PLEASE <a href="http://sourceforge.net/donate/index.php?group_id=128076">MAKE A DONATION!</a></p>
EOD;
 
    // Print text using writeHTMLCell()
    $pdf->writeHTMLCell(0, 0, '', '', $pdfView, 0, 1, 0, true, '', true);  
 
    // ---------------------------------------------------------   
 
    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    $pdf->Output('example_001.pdf', 'I');  
                        //exportMeAsMPDF($fileName, $pdfView, $cssView, 'P'); // then define the content and filename     

                     }
                     
                  } else
                  {
                   $this->data['message'] = $this->upload->display_errors(); 
                  }
               } else
               {
                $this->data['message'] = validation_errors() ? '<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.validation_errors().'</div>' : '';
               }  
            $this->template->load($this->config->item('admin_template'), 'facturas/facturas_cargar', $this->data);
				    
				   }else 
           {
					    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
					    redirect(base_url().'error_404');
			     }
				}
				else
				{
					 redirect(base_url().'user/login');
				}
		}	


	function edit(){    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('facturas/edit'))
							 {    
								$ID =  $this->uri->segment(3);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
													redirect(base_url().'facturas');
										}else{
															$this->load->library('form_validation');  
													$this->data['message'] = '';
													$this->form_validation->set_rules('valortasa', 'Valor Tasa', 'required|numeric');  
															$this->form_validation->set_rules('estado_id', 'Estado',  'required|numeric|greater_than[0]');  
															if ($this->form_validation->run() == false)
															{
																	 $this->data['message'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);
																	
															} else
															{                            
																	$data = array(
																					'VALORTASA' => $this->input->post('valortasa'),
																					'IDESTADO' => $this->input->post('estado_id')
																	);
																 
														if ($this->codegen_model->edit('PLANILLAUNICA_DET',$data,'IDTASA',$this->input->post('id')) == TRUE)
														{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La tasa de pago se ha editado correctamente.</div>');
																			redirect(base_url().'facturas/');
														}
														else
														{
															$this->data['message'] = '<div class="error"><p>Ha ocurrido un error</p></div>';

														}
													}
															$this->data['result'] = $this->codegen_model->get('PLANILLAUNICA_DET','IDTASA,IDCONCEPTO,VALORTASA,IDESTADO','IDTASA = '.$this->uri->segment(3),1,1,true);
															$this->data['estados']  = $this->codegen_model->getSelect('ESTADOS','IDESTADO,NOMBREESTADO');
															$this->data['conceptos']  = $this->codegen_model->getSelect('CONCEPTO','IDCONCEPTO,NOMBRECONCEPTO');
																	
																	//add style an js files for inputs selects
																	$this->data['style_sheets']= array(
																					'css/chosen.css' => 'screen'
																			);
																	$this->data['javascripts']= array(
																					'js/chosen.jquery.min.js'
																			);

																	$this->template->load($this->config->item('admin_template'), 'facturas/facturas_edit', $this->data); 
											}
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'facturas');
									 }
								
						}else
								{
								redirect(base_url().'auth/login');
								}
				
		}
	
		function delete(){
				 if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('facturas/delete'))
							 {
										$ID =  $this->uri->segment(3);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Eliminar mediante edición.</div>');
													redirect(base_url().'facturas');
										}else{
											 $data = array(
                                    				'IDESTADO' => '2'

				                            	);
				           				if($this->codegen_model->edit('PLANILLAUNICA_DET',$data,'IDTASA',$ID) == TRUE){
												//$this->codegen_model->delete('PLANILLAUNICA_DET','IDTASA',$ID);             
												$this->template->set('title', 'facturas');
												$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La tasa de pado se eliminó correctamente.'.$ID.'</div>');
												redirect(base_url().'facturas/');
										}
									}
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'facturas');
									 }
					}else
						{
							redirect(base_url().'auth/login');
						}
		}
 
		 function datatable (){
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('facturas/manage'))
							 {
							 
							 if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('facturas/edit'))
							 {
								$this->load->library('datatables');
								$this->datatables->select('PLANILLAUNICA_DET.COD_PLANILLAUNICA,PLANILLAUNICA_DET.NRO_IDENTIFICACION_EMPLEADO,PLANILLAUNICA_DET.SECUENCIA,PLANILLAUNICA_DET.PRIMER_NOMBRE');
								$this->datatables->from('PLANILLAUNICA_DET'); 
								$this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="'.base_url().'facturas/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'PLANILLAUNICA_DET.COD_TRANSACCION');
							}else{
								$this->load->library('datatables');
								$this->datatables->select('PLANILLAUNICA_DET.COD_PLANILLAUNICA, PLANILLAUNICA_DET.NRO_IDENTIFICACION_EMPLEADO,PLANILLAUNICA_DET.SECUENCIA,PLANILLAUNICA_DET.PRIMER_NOMBRE');
								$this->datatables->from('PLANILLAUNICA_DET'); 
								$this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'PLANILLAUNICA_DET.COD_TRANSACCION');
							}
								echo $this->datatables->generate();
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'facturas');
									 }
						}else
						{
							redirect(base_url().'auth/login');
						}           
		}
}


/* End of file facturas.php */
/* Location: ./system/application/controllers/facturas.php */