<?//a qui empieza la prueba 19/04 para usuarios con dos empresas	
					   $username = $this->input->post('id');
					   $id_empresa = $this->input->post('id_empresa');
					   
					   $data1 = array(
							'id' => $this->input->post('id'),
							'empresa' => $this->input->post('id_empresa'),
							

					   );

					   $this->db->insert('doble_empresa',$data1);

					//   $sql = "INSERT INTO doble_empresa (id, id_empresa, id_contratante) VALUES ('Test', 'Testing', 'Testing@tesing.com')";

					 
						
					  /* $query = $this->db->insert('INSERT INTO doble_empresa (id, id_empresa, id_nombre')->result_array();
			
			foreach($query as $i=>$id) {

				
				$this->db->where('u.id', $id['u.id']);
				$images_query = $this->db->insert('doble_empresa')->result_array();
			 
				
				$query[$i]['doble_empresa'] = $images_query;
			 
			 }*/


			 $res2=$this->codegen_model->getSelect(
                'declaraciones AS d',
                'd.id, d.id_empresa., estampilla.estm_nombre AS estampilla,
                d.periodo, d.tipo_declaracion, d.fecha_creacion, d.estado, d.soporte',
                '',
                'INNER JOIN con_contratantes empresa', 'empresa.id = d.id_empresa',
                'INNER JOIN est_estampillas estampilla', 'estampilla.estm_id = d.id_estampilla',
                'ORDER BY d.id'
            );



			$user = $this->ion_auth->user()->row();
            $data1 = array(
                'id' => $this->input->post('id'),
                'first_name' => $this->input->post('nombres'),
                'last_name' => $this->input->post('apellidos'),
                'phone' => $this->input->post('telefono')
            );
            $this->ion_auth->update($user->id, $data1);
            
            $datos=explode(', ', $data1->id_empresa);

			if(count($datos)>1){
				$txt='';
				foreach ($datos as $x) {
					$res2=$this->codegen_model->getSelect(
						'con_contratantes',
						'nombre',
						'WHERE id = '.$x
					);
					if(isset($res2[0]->nombre)){
						$txt .= ', '.$res2[0]->nombre;		
					}
				}
				$usuarios_empresas1[] = [
					$data1->id,
					$data1->email,
					substr($txt, 2), 
					$data1->perf_nombre,
					$data1->tipo,
					$data1->active,
					$estado
				];
			}else{
				$id_emp=$data1->id_empresa;
				$res2=$this->codegen_model->getSelect(
					'con_contratantes',
					'nombre',
					'WHERE id = '.($id_emp?$id_emp:0)
				);
				$nombre = '';
				if(isset($res2[0]->nombre)){
					$nombre = $res2[0]->nombre;		
				}
				
				$usuarios_empresas1[] = [
					$data1->id,
					$data1->email,
					$nombre, 
					$data1->perf_nombre,
					$data1->tipo,
					$data1->active,
					$estado
				];



// a qui va los requerimientos por revisar de boyaca listado 

/*Buen día

 

Ingeniero Carlos, envió listado de temas pendientes y recomendaciones por realizar en la plataforma desde el uso de las entidades como desde los usuarios de la Gobernación de Boyacá.

 

Desde el uso de la plataforma como entidades declarantes:

Editar o eliminar contratos antes de generar la primera estampilla de cada contrato, después de generada estampilla no se podrá editar ni mucho menos borrar. 
Al crear contratista que la plataforma te visualice los contratistas de la empresa primero, con el hecho que estén seguros que si quedo creado sus contratistas y ellos puedan evidenciar cuantos tienen creados como entidad. 
Poder modificar y eliminar contratista antes de generar la primera estampilla que tenga el contrato al cual esté ligado, después de generar la primera estampilla no permitir modificación o eliminación del contratista. 
Al generar las liquidaciones que antes de liquidar finalmente, cuando aparece la sugerida por el sistema, tenga la opción de editar el valor cuota, ya que en algunos casos se evidencia que ingresan el valor con IVA y hasta los descuentos sugeridos por la plataforma evidencian el error. 
Que los cargues masivos se puedan anular masivamente de las dos plantillas, antes de efectuar las estampillas, por temas que las entidades cargan nuevamente toda la plantilla corregida. 
Para las entidades Descentralizadas cuando quieran realizar cargue masivos en temas de liquidación van a tener un conflicto porque ellos manejan 3 o hasta 4 estampillas, y la plantilla solo está diseñada para las empresas de servicios públicos, por favor prever este posible problema. 
Cuando se genera una declaración hasta que no esté firmada por el contador y representante legal por favor que los datos sean editables encaso que quieran incluir un valor o generar una estampilla que se omitió. 
Que al crear las estampillas deje ajustar o colocar la fecha en que se realizó la estampilla, y no la fecha en la que se está ingresando los datos, ya que se ha observado que emite la fecha en la que se diligencia la información y no brinda la opción de asignar fecha. 
Que los usuarios contador o revisor fiscal y representante legal tengan la oportunidad primero de visualizar y revisar la declaración con el tema de las estampillas emitidas desde sus plataformas y segundo que puedan solicitar correcciones y ajustes al usuario liquidador antes de firmar. 
Que aparezcan todos los datos en el formulario de la entidad, como Nit, Teléfono, Correo, etc... 
Que finalmente al cerrar cada mes el sistema diferencie o agrupe todos los datos ingresados o generados en ese periodo, con el motivo que ellos tengan presente y control de que se está generado en ese nuevo periodo.  
Finalmente, que se tenga la numeración de cuantos contratos están vigentes, y cuantos contratistas tiene la empresa ingresados en la plataforma, motivo para que cada empresa tenga fácil acceso visual de primer impacto del uso de la plataforma, y por otra parte el total de estampillas generadas del periodo. 
 
Desde el uso de la plataforma como Gobernación de Boyacá: 

Que se tenga la oportunidad de anular, modificar las estampillas que se generen por error. 
Que se tenga enumerado como sucede en la bandeja de entrada de un correo normal, las solicitudes o declaraciones allegadas, las que se revisaron o abrieron y las que quedan pendientes por abrir, el motivo es para tener fácil acceso a la información y control de lo que se allegue día a día. 
Que la información visual de contratos, contratistas y declaraciones se puedan agrupar por entidades, ya que a la fecha se están generando hojas y hojas que su visualización por entidad se hace complejo. 
Que los tipos de contratos se pueda editar o modificar los % de estampillas. 
En la lista de estampillas pro debería están habilitada la opción de ver que estampillas están en uso, y ya sea el punto anterior poder modificar el %. 
Presentadas las declaraciones por parte de la entidad, y pasado el periodo de declaración el sistema me agrupe por mes y después por entidad las declaraciones presentadas, para fácil consulta y acceso de búsqueda. 
Que en el sistema presentada la declaración, como funcionario brinde la opción de anexar el comprobante de tesorería para dejar cerrado el proceso de la declaración con motivo que hasta que se reconozca el valor en tesorería el proceso se cierra. 
Que el botón de notificaciones funcione.  
Que a la fecha exista una plataforma de prueba con la cual el funcionario de la Gobernación pueda brindar capacitaciones, que se ve imposibilitado de crear contratistas contratos y emitir estampillas y finalmente declaraciones. 
Finalmente, que el funcionario de la Gobernación tenga la posibilidad de cerrar un contrato que no se efectuó en su totalidad porque no se ejecutó lo destinado para la vigencia, anexando soporte aclaratorio por la entidad, o en algunos casos cuando el total de los contratos está incluido el IVA y así lo ingresan a la plataforma y finalmente si o si queda un saldo por ejecutar que corresponde a lo real al impuesto del IVA. 
 

Deantemano agradezco su atención y pronta respuesta a las sugerencias expuestas.*/