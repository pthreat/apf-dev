<?php

	namespace buscolaburo\model{

		class Barrio{

			private	$_localidad	=	NULL;	//Modelo de tipo Localidad

			private	$_id			=	NULL;
			private	$_nombre		=	NULL;
			private	$_table		=	"barrios";

			public function __construct( $localidad,$valor=NULL){

				$this->setLocalidad($localidad);

				if(!is_null($valor)){

					if(is_int($valor)){

						$this->setId($valor);
						$this->getBarrioById();

					}else{

						$this->setNombre($valor);
						$this->getBarrioByNombre();

					}

				}

			}

			public function setLocalidad(Localidad $localidad){

				$this->_localidad	=	$localidad;

			}

			public function setId($id){

				$this->_id	=	(int)$id;

			}

			public function getId(){

				return $this->_id;

			}

			public function getLocalidad(){

				return $this->_localidad;

			}

			public function setNombre($nombre){

				$this->_nombre	=	$nombre;

			}

			public function getNombre(){

				return $this->_nombre;

			}

			public function getBarrioByNombre(){

				$table	=	new \apf\db\mysql5\Table($this->_table);
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id","nombre"));

				$where	=	Array(

										Array(
												"field"=>"id_localidad",
												"value"=>$this->_localidad->getId()
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

					throw(new \Exception("No se encontro el barrio ".$this->_nombre." para la localidad ".$this->_localidad->getNombre()));

				}

				$this->setId($res["id"]);

			}

			public static function getInstanceById($idBarrio){

				$idBarrio	=	(int)$idBarrio;

				if(!$idBarrio){

					throw(new \Exception("id de barrio invalido especificado \"$idBarrio\""));

				}

				$table		=	new \apf\db\mysql5\Table("barrios");
				$select		=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id","id_localidad","nombre"));

				$where		=	Array(
											Array(
												"field"=>"id",
												"value"=>$idBarrio
											)
				);

				$select->where($where);

				try{

					$res			=	$select->execute();
					$localidad	=	\apf\model\Localidad::getInstanceById($res["id_localidad"]);
					$class		=	__CLASS__;
					$objBar		=	new $class($localidad);
					$objBar->setId($res["id"]);
					$objBar->setNombre($res["nombre"]);

				}catch(\Exception $e){

					$msg	=	$e->getMessage();

					throw(new \Exception("No se encontro el barrio con el id ".$idBarrio));

				}

				return $objBar;
				
			}

			public function getBarrioById(){

				$table	=	new \apf\db\mysql5\Table($this->_table);
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id","nombre"));

				$where	=	Array(
										
										Array(
												"field"=>"id_localidad",
												"value"=>$this->_localidad->getId()
											),
											Array(
												"operator"=>"AND"
											),
											Array(
												"field"=>"id",
												"value"=>$this->_id
											)
				);

				$select->where($where);

				$res	=	$select->execute();

				if(!sizeof($res)){

					throw(new \Exception("No se encontro el barrio ".$this->_nombre." para la localidad ".$this->_localidad->getNombre()));

				}

				$this->setNombre($res["nombre"]);

			}

			public function agregar(){

				$table						=	new \apf\db\mysql5\Table($this->_table);
				$insert						=	new \apf\db\mysql5\Insert($table);
				$insert->nombre			=	$this->_nombre;
				$insert->id_localidad	=	$this->_localidad->getId();

				$res	=	$insert->execute();

				$this->setId($res);

			}

		}

	}

?>
