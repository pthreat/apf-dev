<?php

	namespace buscolaburo\model{

		class Pais{

			private	$_id		=	NULL;
			private	$_nombre	=	NULL;
			private	$_table	=	"paises";

			public function __construct($valor=NULL){

				if(!is_null($valor)){

					if(is_int($valor)){

						$this->setId($valor);
						$this->getPaisById($valor);

					}else{

						$this->setNombre($valor);
						$this->getPaisByNombre($valor);					
	
					}

				}

			}

			public function setId($id=0){

				$this->_id	=	(int)$id;

			}

			public function getId(){

				return $this->_id;

			}

			public function setNombre($nombre=NULL){

				if(is_null($nombre)){

					throw(new \Exception("Debe especificar un nombre de pais"));	

				}

				$this->_nombre	=	$nombre;

			}

			public function getNombre(){

				return $this->_nombre;

			}

			public function getPaisByNombre(){

				$table	=	new \apf\db\mysql5\Table($this->_table);
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id","nombre"));

				$where	=	Array(
											Array(
												"field"=>"nombre",
												"value"=>$this->_nombre
											)
				);

				$select->where($where);

				$res	=	$select->execute();

				$this->setId($res["id"]);

			}

			public function getPaisById(){

				$table	=	new \apf\db\mysql5\Table($this->_table);
				$select	=	new \apf\db\mysql5\Select($table);

				$select->fields(Array("id","nombre"));

				$where	=	Array(
											Array(
												"field"=>"id",
												"value"=>$this->_id
											)
				);

				$select->where($where);

				$res		=	$select->execute($smartMode=TRUE);

				if(!$res){

					return FALSE;

				}

				$this->setId($res["id"]);
				$this->setNombre($res["nombre"]);

			}

		}

	}

?>
