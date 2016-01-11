<?php

	namespace buscolaburo\model{

		class Oferta{

			/*Datos de ubicacion*/

			private	$_localidades			=	Array();
			private	$_barrios				=	Array();
			private	$_categorias			=	Array();

			/*Otras propiedades de la clase*/

			private	$_id						=	NULL;
			private	$_contacto				=	NULL;
			private	$_titulo					=	NULL;
			private	$_cuerpo					=	NULL;
			private	$_empresa				=	NULL;
			private	$_duracion				=	NULL;
			private	$_salario				=	NULL;
			private	$_comienzo				=	NULL;
			private	$_fechaPublicacion	=	NULL;
			private	$_nombreContacto		=	NULL;

			private	$_uri						=	NULL;

			private	$_table					=	"ofertas";

			//El email va en esta clase porque las empresas generalmente crean un mail por oferta 
			//Y no uno general por empresa.

			private	$_email					=	NULL;	

			public static function getOfertasFromDate($intervalo=1,$tipo="DAY"){

				$arrayObj	=	Array();
				$class		=	__CLASS__;

				$obj			=	new $class();

				$table		=	new \apf\db\mysql5\Table("ofertas");
				$select		=	new \apf\db\mysql5\Select($table);
            
				$where	=	Array(
										Array(

											"field"=>"fecha_publicacion",
                                 "operator"=>"between",
                                 "value"=>Array("min"=>"DATE_SUB(NOW(),INTERVAL $intervalo $tipo)","max"=>"NOW()") 
                            )  
										
				);
            
            $fields = Array();
            $select->fields($fields);
				$select->where($where);

            $res = $select->execute();

            return $res ;


			}

			public static function getOfertaById($idOferta){

				$ofertasTable					=	new \apf\db\mysql5\Table("ofertas");
				$empresasTable					=	new \apf\db\mysql5\Table("empresas");
				$empresasDatosTable			=	new \apf\db\mysql5\Table("empresas_datos");
				$ofertasUbicacionTable		=	new \apf\db\mysql5\Table("ofertas_ubicacion");
				$localidadesTable				=	new \apf\db\mysql5\Table("localidades");
				$ofertasCategoriaTable		=	new \apf\db\mysql5\Table("ofertas_categoria");
				$categoriasTable				=	new \apf\db\mysql5\Table("categorias");

				$select			=	new \apf\db\mysql5\Select($ofertasTable);

				$fields			=	Array(
													"ofertas.id AS idOferta",
													"ofertas.email",
													"ofertas.titulo",
													"ofertas.cuerpo",
													"GROUP_CONCAT(categorias.id) AS categorias",
													"GROUP_CONCAT(localidades.id) AS localidades"
				);

				$select->fields($fields);


				//INNER JOIN ofertas_ubicacion
				/////////////////////////////////////////////////////////

				$joinOfertasUbicacion		=	new \apf\db\mysql5\Join($ofertasUbicacionTable);
				$joinOfertasUbicacion->type("INNER");

				$on	=	Array(
									Array(
											"field"=>"ofertas.id",
											"value"=>"ofertas_ubicacion.id_oferta",
											"quote"=>FALSE
									)
				);

				$joinOfertasUbicacion->on($on);

				$select->join($joinOfertasUbicacion);

				//INNER JOIN localidades
				/////////////////////////////////////////////////////////

				$joinLocalidades	=	new \apf\db\mysql5\Join($localidadesTable);
				$joinLocalidades->type("INNER");

				$on						=	Array(
														Array(
															"field"=>"localidades.id",
															"value"=>"ofertas_ubicacion.id_localidad",
															"quote"=>FALSE
														)
				);

				$joinLocalidades->on($on);

				$select->join($joinLocalidades);

				//INNER JOIN ofertas_categoria
				/////////////////////////////////////////////////////////

				$joinOfertasCategoria	=	new \apf\db\mysql5\Join($ofertasCategoriaTable);
				$joinOfertasCategoria->type("INNER");

				$on						=	Array(
														Array(
															"field"=>"ofertas_categoria.id_oferta",
															"value"=>"ofertas.id",
															"quote"=>FALSE
														)
				);
	
				$joinOfertasCategoria->on($on);

				$select->join($joinOfertasCategoria);

				//INNER JOIN categorias
				/////////////////////////////////////////////////////////

				$joinCategorias	=	new \apf\db\mysql5\Join($categoriasTable);
				$joinCategorias->type("INNER");

				$on						=	Array(
														Array(
															"field"=>"ofertas_categoria.id_categoria",
															"value"=>"categorias.id",
															"quote"=>FALSE
														)
				);
	
				$joinCategorias->on($on);

				$select->join($joinCategorias);

				//WHERE
				/////////////////////////////////////////////////////////

				$where	=	Array(
											Array(
												"field"=>"ofertas.id",
												"value"=>(int)$idOferta
											)
				);

				$select->where($where);
					
				$select->group(Array("ofertas.id"));

				$res		=	$select->execute($smartMode=TRUE);

				$class	=	__CLASS__;

				$oferta	=	new Oferta();

				$oferta->setId($res["idOferta"]);
				$oferta->setEmail($res["email"]);
				$oferta->setTitulo($res["titulo"]);
				$oferta->setCuerpo($res["cuerpo"]);

				return $oferta;

			}

			public static function obtenerOfertasParaUsuario(Usuario $usuario,$intervalo=2,$tipo="DAY"){

				$ofertasTable					=	new \apf\db\mysql5\Table("ofertas");
				$empresasTable					=	new \apf\db\mysql5\Table("empresas");
				$empresasDatosTable			=	new \apf\db\mysql5\Table("empresas_datos");
				$ofertasUbicacionTable		=	new \apf\db\mysql5\Table("ofertas_ubicacion");
				$localidadesTable				=	new \apf\db\mysql5\Table("localidades");
				$ofertasCategoriaTable		=	new \apf\db\mysql5\Table("ofertas_categoria");
				$categoriasTable				=	new \apf\db\mysql5\Table("categorias");

				$select			=	new \apf\db\mysql5\Select($ofertasTable);

				$fields			=	Array(
													"ofertas.id AS idOferta",
													"ofertas.email",
													"ofertas.titulo",
													"GROUP_CONCAT(categorias.id) AS categorias",
													"GROUP_CONCAT(localidades.id) AS localidades"
				);

				$select->fields($fields);


				//INNER JOIN ofertas_ubicacion
				/////////////////////////////////////////////////////////

				$joinOfertasUbicacion		=	new \apf\db\mysql5\Join($ofertasUbicacionTable);
				$joinOfertasUbicacion->type("INNER");

				$on	=	Array(
									Array(
											"field"=>"ofertas.id",
											"value"=>"ofertas_ubicacion.id_oferta",
											"quote"=>FALSE
									)
				);

				$joinOfertasUbicacion->on($on);

				$select->join($joinOfertasUbicacion);

				//INNER JOIN localidades
				/////////////////////////////////////////////////////////

				$joinLocalidades	=	new \apf\db\mysql5\Join($localidadesTable);
				$joinLocalidades->type("INNER");

				$on						=	Array(
														Array(
															"field"=>"localidades.id",
															"value"=>"ofertas_ubicacion.id_localidad",
															"quote"=>FALSE
														)
				);

				$joinLocalidades->on($on);

				$select->join($joinLocalidades);

				//INNER JOIN ofertas_categoria
				/////////////////////////////////////////////////////////

				$joinOfertasCategoria	=	new \apf\db\mysql5\Join($ofertasCategoriaTable);
				$joinOfertasCategoria->type("INNER");

				$on						=	Array(
														Array(
															"field"=>"ofertas_categoria.id_oferta",
															"value"=>"ofertas.id",
															"quote"=>FALSE
														)
				);
	
				$joinOfertasCategoria->on($on);

				$select->join($joinOfertasCategoria);

				//INNER JOIN categorias
				/////////////////////////////////////////////////////////

				$joinCategorias	=	new \apf\db\mysql5\Join($categoriasTable);
				$joinCategorias->type("INNER");

				$on						=	Array(
														Array(
															"field"=>"ofertas_categoria.id_categoria",
															"value"=>"categorias.id",
															"quote"=>FALSE
														)
				);
	
				$joinCategorias->on($on);

				$select->join($joinCategorias);

				//WHERE
				/////////////////////////////////////////////////////////

				$where				=	Array();
				$tmpWhere			=	Array();

				$usuariosCategoria	=	new \apf\db\mysql5\Table("usuarios_categorias");
				$selectCategorias		=	new \apf\db\mysql5\Select($usuariosCategoria);

				$selectCategorias->fields(Array("id_categoria"));

				$selectCategorias->where(Array(
															Array(
																"field"=>"id_usuario",
																"value"=>$usuario->getId()
															)
				));

				$tmpWhere[]	=	Array(
											"field"=>"ofertas_categoria.id_categoria",
											"operator"=>"IN",
											"value"=>$selectCategorias
				);

				$prefUbicaciones		=	new \apf\db\mysql5\Table("usuarios_ubicaciones");
				$selectLocalidades	=	new \apf\db\mysql5\Select($prefUbicaciones);

				$selectLocalidades->fields(Array("id_localidad"));

				$selectLocalidades->where(Array(

														Array(
																"field"=>"id_usuario",
																"value"=>$usuario->getId()
														)
				));

				$tmpWhere[]	=	Array(

											"field"=>"ofertas_ubicacion.id_localidad",
											"operator"=>"IN",
											"value"=>$selectLocalidades

				);

				$postulacionesTable	=	new \apf\db\mysql5\Table("usuarios_postulaciones");
				$selectPostulaciones	=	new \apf\db\mysql5\Select($postulacionesTable);

				$selectPostulaciones->fields(Array("id_oferta"));

				$selectPostulaciones->where(Array(
																Array(
																	"field"=>"id_usuario",
																	"value"=>$usuario->getId()
																)
				));

				$tmpWhere[]	=	Array(

											"field"=>"ofertas.id",
											"operator"=>"NOT IN",
											"value"=>$selectPostulaciones

				);

				$prefCriterios	=	$usuario->getPreferencia("criterios");

				if($prefCriterios){

					$cantCriterios	=	sizeof($prefCriterios);

					foreach($prefCriterios as $key=>$criterio){

						if($key==0){

							$tmpWhere[]	=	Array(
														"field"=>"ofertas.titulo",
														"operator"=>"LIKE",
														"value"=>"%$criterio%",
														"begin_enclose"=>TRUE
							);

						}else{

							if($key==$cantCriterios-1){

								$tmpWhere[]	=	Array(
															"field"=>"ofertas.titulo",
															"operator"=>"LIKE",
															"value"=>"%$criterio%",
															"end_enclose"=>TRUE
								);

							}else{

								$tmpWhere[]	=	Array(
															"field"=>"ofertas.titulo",
															"operator"=>"LIKE",
															"value"=>"%$criterio%",
								);

							}

						}

					}

				}

				foreach($tmpWhere as $key=>$value){

					$where[]	=	$value;

					$op	=	($value["operator"]=="LIKE")	?	"OR"	:	"AND";
						
					$where[]	=	Array(
											"operator"=>$op
					);

				}

				unset($where[sizeof($where)-1]);

				if($intervalo){

					$where[]	=	Array(
										"operator"=>"AND"
					);


					$where[]	=	Array(

										"field"=>"ofertas.fecha_publicacion",
										"operator"=>"BETWEEN",
										"value"=>Array(
															"min"=>"DATE_SUB(NOW(), INTERVAL $intervalo $tipo)",
															"max"=>"NOW()"
										)
					);

				}

				$select->where($where);
					
				$select->group(Array("ofertas.id"));

				$select->orderBy(Array("fecha_publicacion"),"DESC");

				$res	=	$select->execute($smartMode=FALSE);

				$ofertas	=	Array();	

				$class	=	__CLASS__;

				foreach($res as $r){

					$oferta	=	new Oferta();
					$oferta->setId($r["idOferta"]);
					$oferta->setEmail($r["email"]);
					$oferta->setTitulo($r["titulo"]);

					$ofertas[]	=	$oferta;

				}

				return $ofertas;

			}


			public function setId($id){

				$this->_id	=	(int)$id;

			}

			public function getId(){

				return $this->_id;
		
			}

			private function setNombreContacto($nombreContacto){

				$this->_nombreContacto	=	$nombreContacto;

			}

			private function getNombreContacto(){

				return $this->_nombreContacto;

			}


			public function setUri(\aidsql\parser\Uri $uri){

				$this->_uri	=	$uri;

			}

			public function getUri(){

				return $this->_uri;

			}

			public function setTitulo($titulo=NULL){

				$this->_titulo	=	$titulo;	

			}

			public function setContacto($nombre){

				$this->_contacto	=	$nombre;

			}

			public function getContacto(){

				return $this->_contacto;

			}

			public function getTitulo(){

				return $this->_titulo;

			}

			public function setCuerpo($cuerpo=NULL){

				$this->_cuerpo	=	$cuerpo;

			}

			public function getCuerpo(){

				return $this->_cuerpo;

			}

			public function setDuracion($duracion=NULL){

				$this->_duracion	=	$duracion;

			}

			public function getDuracion(){

				return $this->_duracion;

			}

			public function setComienzo($comienzo=NULL){

				$this->_comienzo	=	$comienzo;

			}

			public function getComienzo(){

				return $this->_comienzo;

			}

			public function setSalario($salario=NULL){

				$this->_salario	=	$salario;

			}

			public function getSalario(){

				return $this->_salario;

			}

			public function setFechaPublicacion($fecha=NULL){

				$this->_fechaPublicacion	=	$fecha;

			}

			public function getFechaPublicacion(){

				return $this->_fechaPublicacion;

			}


			public function setEmpresa(Empresa $empresa){

				$this->_empresa	=	$empresa;

			}

			public function getEmpresa(){

				return $this->_empresa;

			}


			public function setEmail($email){

				$this->_email	=	$email;

			}

			public function getEmail(){

				return $this->_email;

			}

			public function addLocalidad(Localidad $addLocalidad){

				if(sizeof($this->_localidades)){

					foreach($this->_localidades as $localidad){

						if($addLocalidad==$localidad){

							return FALSE;

						}

					}

				}

				$this->_localidades[]	=	$addLocalidad;

			}

			public function getLocalidades(){

				return $this->_localidades;

			}

			public function addBarrio(Barrio $addBarrio){

				if(sizeof($this->_barrios)){

					foreach($this->_barrios as $barrio){

						if($barrio==$addBarrio){

							return FALSE;

						}

					}

				}

				$this->_barrios[]	=	$addBarrio;

			}

			public function getBarrios(){

				return $this->_barrio;

			}

			public function addCategoria(Categoria $addCategoria){

				foreach($this->_categorias as $categoria){

					if($categoria==$addCategoria){

						return FALSE;

					}

				}

				$this->_categorias[]	=	$addCategoria;

			}

			public function getCategorias(){

				return $this->_categorias;

			}

			public function existe(){

				$table	=	new \apf\db\mysql5\Table("ofertas");
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id"));

				$where	=	Array(
											Array(
												"field"=>"id_empresa",
												"value"=>$this->_empresa->getId()
											),
											Array(
												"operator"=>"AND"
											),
											Array(
												"field"=>"email",
												"value"=>$this->_email
											),
											Array(
												"operator"=>"AND"
											),
											Array(
												"field"=>"titulo",
												"value"=>$this->_titulo
											)
				);

				$select->where($where);

				return $select->execute();

			}

			public function agregar(){

				$table	=	new \apf\db\mysql5\Table("ofertas");
				$insert	=	new \apf\db\mysql5\Insert($table);

				if(is_null($this->_empresa)){

					throw(new \Exception("Para agregar una oferta debe especificar una empresa"));

				}

				//Insertar la oferta en si

				$insert->id_empresa			=	$this->_empresa->getId();
				$insert->titulo				=	$this->_titulo;
				$insert->cuerpo				=	$this->_cuerpo;
				$insert->duracion				=	$this->_duracion;
				$insert->salario				=	$this->_salario;
				$insert->fecha_publicacion	=	$this->_fechaPublicacion;
				$insert->comienzo				=	$this->_comienzo;
				$insert->email					=	$this->_email;

				try{

					$idOferta	=	$insert->execute();

					$this->setId($idOferta);

					//Insertada la oferta, procedo a insertar los datos de la ubicacion de la misma

					try{

						$this->agregarUbicacion();

						try{

							//Ahora insertar en que categoria pertenece

							$this->agregarCategoria();

							//Ahora insertar la URI de donde se obtuvieron los datos

							try{

								$this->agregarUri();

							}catch(\Exception $e){

								throw(new \Exception("No se pudo agregar la URI a la oferta!"));

								$this->borrarCategoria(FALSE);

							}	

						}catch(\Exception $e){

							$this->borrarUbicacion(FALSE);

							throw(new \Exception("No se pudo agregar la ubicacion de la oferta"));

						}
					

					}catch(\Exception $e){

						$this->borrarOfertaById();
						throw(new \Exception("No se pudo agregar la ubicacion de la oferta"));

					}

				}catch(\Exception $e){

					throw(new \Exception("No se pudo agregar la oferta"));

				}

			}

			public function borrarOfertaById(){

				$table	=	new \apf\db\mysql5\Table("ofertas");
				$delete	=	new \apf\db\mysql5\Delete($table);
				$delete->where(Array("id"=>$this->_id));

				$delete->execute();

				$table	=	new \apf\db\mysql5\Table("ofertas_ubicacion");
				$delete	=	new \apf\db\mysql5\Delete($table);
				$delete->where(Array("id_oferta"=>$this->_id));

				$delete->execute();

				$table	=	new \apf\db\mysql5\Table("ofertas_categoria");
				$delete	=	new \apf\db\mysql5\Delete($table);
				$delete->where(Array("id_oferta"=>$this->_id));

				$delete->execute();

			}

			public function actualizarOferta(){


				$table	=	new \apf\db\mysql5\Table("ofertas");
				$update	=	new \apf\db\mysql5\Update($table);

				$fields	=	Array(

										"titulo"=>$this->_titulo,
										"id_empresa"=>$this->_empresa->getId(),
										"contacto"=>$this->_contacto,
										"titulo"=>$this->_titulo,
										"cuerpo"=>$this->_cuerpo,
										"salario"=>$this->_salario,
										"duracion"=>$this->_duracion,
										"comienzo"=>$this->_comienzo,
										"fecha_publicacion"=>$this->_fechaPublicacion,
										"email"=>$this->_email
				);

				$update->fields($fields);

				$where	=	Array(
											Array(
												"field"=>"id",
												"value"=>$this->_id
											)
				);

				$update->where($where);

				return $update->execute();

			}

			public function existeOfertaEnCategoria(){
	
				$cantCategorias	=	sizeof($this->_categorias);

				if(!$cantCategorias){

					throw(new \Exception("No se puede consultar si existe la oferta en una categoria si no se seteo al menos una categoria en la clase"));

				}

				$categorias	=	Array();	


				if($cantCategorias==1){

						$categorias	=	$this->_categorias[0]->getId();
						$operator	=	'=';

				}else{

					$operator	=	"IN";

					foreach($this->_categorias as $categoria){

						$categorias[]	=	$categoria->getId();

					}

				}

				$table	=	new \apf\db\mysql5\Table("ofertas_categoria");
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id_oferta"));

				$where	=	Array(
											Array(
												"field"=>"id_oferta",
												"value"=>$this->_id
											),
											Array(
												"operator"=>"AND"
											),
											Array(
												"field"=>"id_categoria",
												"operator"=>$operator,
												"value"=>$categorias
											)
				);

				$select->where($where);

				$res	=	$select->execute();

				if(!sizeof($res)){

					return FALSE;

				}

				return $res;

			}

			public function agregarCategoria(){

				$cantCategorias	=	sizeof($this->_categorias);

				if(!$cantCategorias){

					throw(new \Exception("No se puede AGREGAR la oferta a una categoria si no se seteo al menos una categoria en la clase"));

				}

				$table	=	new \apf\db\mysql5\Table("ofertas_categoria");
				$insert	=	new \apf\db\mysql5\Insert($table);

				$insert->id_oferta	=	$this->_id;

				foreach($this->_categorias as $categoria){

					$insert->id_categoria	=	$categoria->getId();
					$insert->execute();

				}

			}

			public function agregarUbicacion(){

				$table	=	new \apf\db\mysql5\Table("ofertas_ubicacion");
				$insert	=	new \apf\db\mysql5\Insert($table);

				$insert->id_oferta		=	$this->_id;

				if(!sizeof($this->_barrios)){

					foreach($this->_localidades as $localidad){

						$insert->id_localidad	=	$localidad->getId();
						$res	=	$insert->execute();

					}

				}else{

					foreach($this->_barrios as $barrio){

						$localidad					=	$barrio->getLocalidad();
						$insert->id_localidad	=	$localidad->getId();
						$insert->id_barrio		=	$barrio->getId();

						$res	=	$insert->execute();

					}

				}

				return $res;

			}

			public function existeOfertaEnUbicacion(){

				$table	=	new \apf\db\mysql5\Table("ofertas_ubicacion");
				$select	=	new \apf\db\mysql5\Select($table);

				$fields	=	Array("id_oferta","id_localidad","id_barrio");

				$select->fields($fields);

				$localidades	=	Array();

				if(sizeof($this->_localidades)&&!sizeof($this->_barrios)){

					foreach($this->_localidades as $localidad){

						$localidades[]	=	$localidad->getId();

					}

				}

				$barrios	=	Array();

				if(sizeof($this->_barrios)){

					foreach($this->_barrios as $barrio){

						$idBarrio	=	$barrio->getId();

						if(!is_null($idBarrio)){

							$barrios[]	=	$barrio->getId();

						}

					}

				}

				$where	=	Array(
											Array(
												"field"=>"id_oferta",
												"value"=>$this->_id
											),
				);

				if(sizeof($localidades)){

					if(sizeof($localidades)==1){

						$operator		=	'=';
						$localidades	=	$localidades[0];

					}else{

						$operator	=	'IN';

					}

					$where[]		=	Array(
												"operator"=>"AND"
					);

					$where[]		=	Array(
												"field"=>"id_localidad",
												"operator"=>$operator,
												"value"=>$localidades
					);

				}

				if(sizeof($barrios)){

					if(sizeof($barrios)==1){

						$operator	=	'=';
						$barrios		=	$barrios[0];

					}else{

						$operator	=	"IN";

					}

					$where[]	=	Array(
											"operator"=>"AND"
					);

					$where[]	=	Array(
												"field"=>"id_barrio",
												"operator"=>$operator,
												"value"=>$barrios
					);

				}

				$select->where($where);

				$res	=	$select->execute();

				if($res){

					return TRUE;

				}

				return FALSE;
				
			}

			public function existeUriParaOferta(){

				$table	=	new \apf\db\mysql5\Table("ofertas_uri");
				$select	=	new \apf\db\mysql5\Select($table);

				$fields	=	Array("uri");

				$select->fields($fields);

				$where	=	Array(

									Array(
										"field"=>"uri",
										"value"=>sprintf("%s",$this->_uri)
									)
				);

				$res		=	$select->execute();

				if($res){

					return TRUE;

				}

				return FALSE;

			}

			public function agregarUri(){

				$table	=	new \apf\db\mysql5\Table("ofertas_uri");
				$insert	=	new \apf\db\mysql5\Insert($table);

				$insert->id_oferta	=	$this->_id;
				$insert->uri			=	$this->_uri;

				$res	=	$insert->execute();

				return $res;

			}

			public function borrarUbicacion($todo=FALSE){

				$table	=	new \apf\db\mysql5\Table("ofertas_ubicacion");
				$delete	=	new \apf\db\mysql5\Delete($table);


				$where	=	Array(
											Array(
												"field"=>"id_oferta",
												"value"=>$this->_id
											)
				);

				if(!$todo){

					$where	=	Array(
												Array(
													"field"=>"id_oferta",
													"value"=>$this->_id
												),
												Array(
													"operator"=>"AND"
												),
												Array(
													"field"=>"id_pais",
													"value"=>$this->_pais->getId()
												),
												Array(
													"operator"=>"AND"
												),
												Array(
													"field"=>"id_provincia",
													"value"=>$this->_provincia->getID()
												),
												Array(
													"operator"=>"AND"
												),
												Array(
													"field"=>"id_localidad",
													"value"=>$this->_localidad->getID()
												),
												Array(
													"operator"=>"AND"
												),
												Array(
													"field"=>"id_barrio",
													"value"=>$this->_barrio->getID()
												)
					);

				}

				$delete->where($where);

				return $delete->execute();
				
			}

			public function borrarUri($todo=FALSE){

				$table	=	new \apf\db\mysql5\Table("ofertas_uri");
				$delete	=	new \apf\db\mysql5\Delete($table);


				$where	=	Array(
											Array(
												"field"=>"id_oferta",
												"value"=>$this->_id
											)
				);

				if(!$todo){


					$where[]	=	Array("operator"=>"AND");

					$where[]	=	Array(
											"field"=>"uri",
											"value"=>sprintf("%s",$this->_uri)
					);

				}

				$delete->where($where);

				return $delete->execute();

			}


			public function borrarCategoria($todo=FALSE){

				$table	=	new \apf\db\mysql5\Table("ofertas_categoria");
				$delete	=	new \apf\db\mysql5\Delete($table);

				$where	=	Array(
											Array(
												"field"=>"id_oferta",
												"value"=>$this->_id
											)
				);

				if(!$todo){

					$where[]	=	Array("operator"=>"AND");

					$where[]	=	Array(
											"field"=>"id_categoria",
											"value"=>$this->_categoria->getId()
					);

				}

				$delete->where($where);

				return $delete->execute();

			}

		}

	}

?>
