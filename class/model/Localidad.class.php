<?php

	namespace buscolaburo\model{

		class Localidad{

			private	$_provincia		=	NULL;	//Modelo de tipo provincia

			private	$_id				=	NULL;
			private	$_nombre			=	NULL;
			private	$_normalizado	=	FALSE;
			private	$_table			=	"localidades";

			public function __construct(Provincia $provincia,$valor=NULL){

				$this->setProvincia($provincia);

				if(!is_null($valor)){

					if(is_int($valor)){

						$this->setId($valor);
						$this->getLocalidadById();

					}else{

						$this->setNombre($valor);
						$this->getLocalidadByNombre();

					}

				}

			}

			public function getBarrios(){

				$table		=	new \apf\db\mysql5\Table("barrios");
				$select		=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id","nombre"));

				$where		=	Array(
										Array(
												"field"=>"id_localidad",
												"value"=>$this->getId()
										)
				);


				$orderBy = Array("nombre");
				$select->orderBy($orderBy,"DESC");	
				$select->where($where);

				$res	=	$select->execute($smartMode=FALSE);

				if(!$res){
					return FALSE;
				}				

				$return	=	Array();

				foreach($res as $r){

					$class	=	"\\apf\\model\\Barrio";
					$obj		=	new $class($this);
					$obj->setId($r["id"]);
					$obj->setNombre($r["nombre"]);

					$return[]	=	$obj;
			  }
	
			  return $return;

			}

			public static function getLocalidadesByUsuario($id_usuario=NULL){
				
				if($id_usuario==NULL) {
					return false;
				}

            $table      =  new \apf\db\mysql5\Table("usuarios_ubicaciones");
				$select	   =	new \apf\db\mysql5\Select($table);

            $select->fields(Array("id_localidad"));

            $where      =  Array(
                              Array(
                                    "field"=>"id_usuario",
                                    "value"=>$id_usuario
                              )
	         );

				$select->where($where);
				$res	=	$select->execute();	

				return $res ;

			}

			public function setProvincia(Provincia $provincia){

				$this->_provincia	=	$provincia;

			}

			public function setId($id){

				$this->_id	=	(int)$id;

			}

			public function getId(){

				return $this->_id;

			}

			public function getProvincia(){

				return $this->_provincia;

			}

			public function setNombre($nombre){

				$this->_nombre	=	$nombre;

			}

			public function setNormalizado($boolean=TRUE){

				$this->_normalizado	=	$boolean;

			}

			public function getNormalizado(){

				return $this->_normalizado;

			}

			public function getNombre(){

				return $this->_nombre;

			}

			public function getLocalidadByNombre(){

				$table	=	new \apf\db\mysql5\Table($this->_table);
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id","nombre"));

				$where	=	Array(
											Array(
												"field"=>"id_provincia",
												"value"=>$this->_provincia->getId()
											),
											Array(
												"operator"=>"AND"
											),
											Array(
												"field"=>"nombre",
												"value"=>$this->_nombre
											)
				);

				$select->where($where);

				$res	=	$select->execute();

				if(!sizeof($res)){

					throw(new \Exception("No se encontro la localidad ".$this->_nombre));

				}

				$this->setId($res["id"]);

			}

			public static function getInstanceById($idLocalidad=NULL){
					
				if(!(int)$idLocalidad){

					throw(new \Exception("id de localidad especificado no es un entero"));

				}

				$table	=	new \apf\db\mysql5\Table("localidades");
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id","id_provincia","nombre"));
				
				$where	=	Array(
											Array(
												"field"=>"id",
												"value"=>$idLocalidad
											)
				);

				$select->where($where);


				try{

					$res			=	$select->execute();
					$provincia	=	\apf\model\Provincia::getInstanceById($res["id_provincia"]);
					$class		=	__CLASS__;
					$objLoc		=	new $class($provincia);
					$objLoc->setId($res["id"]);
					$objLoc->setNombre($res["nombre"]);

				}catch(\Exception $e){

					$msg	=	$e->getMessage();

					throw(new \Exception("No se encontro la localidad con el id ".$idLocalidad));

				}

				return $objLoc;

			}

			public function getLocalidadById(){

				$table	=	new \apf\db\mysql5\Table($this->_table);
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id","id_provincia","nombre"));

				$where	=	Array(
											Array(
												"field"=>"id",
												"value"=>$this->_id
											)
				);

				$select->where($where);

				try{

					$res			=	$select->execute();

					$provincia	=	new Provincia(NULL,$res["id_provincia"]);
					$this->setProvincia($provincia);

					$this->setId($res["id"]);
					$this->setNombre($res["nombre"]);

				}catch(\Exception $e){

					$msg	=	$e->getMessage();

					throw(new \Exception("No se encontro la localidad con el id ".$this->_id));

				}

			}

			public function agregar(){

				$table						=	new \apf\db\mysql5\Table($this->_table);
				$insert						=	new \apf\db\mysql5\Insert($table);
				$insert->nombre			=	$this->_nombre;
				$insert->id_provincia	=	$this->_provincia->getId();
				$insert->normalizado		=	(int)$this->_normalizado;

				$res	=	$insert->execute();

				$this->setId($res);

			}

		}

	}

?>
