<?php

	namespace apf\db{

		use \apf\db\Connection	as	DatabaseConnection;

		abstract class Query{

			private	$connection		=	NULL;
			private	$description	=	NULL;
			private	$sql				=	NULL;
			private	$fields			=	Array();

			public function __construct(DatabaseConnection &$connection){

				$this->connection	=	$connection;

			}

			protected function getConnection(){

				return $this->connection;

			}

			/**
			 * Query description, this is free text, it's purpose is to let the end user to add 
			 * descriptive text i.e What the fuck is this query about, in general lines.
			 * Example: This lists users yo!: SELECT id,name FROM users;
			 */

			public function setDescription($description){

				$this->description	=	$description;
				return $this;

			}

			public function getDescription(){

				return $this->description;

			}

			public function execute($map=NULL,$smart=TRUE){

				$sql		=	sprintf("%s",$this->sql);

				if($this->error){

					throw new \Exception($this->error);

				}

				$this->result	=	$this->connection->query($sql);

				if(!$this->result){

					throw(new \Exception("QUERY FAILED: $sql (".$this->adapter->error.' | '.$this->adapter->errno.')'));

				}

				return $this->getResult($map,$smart);

			}

			public function __toString(){

				return sprintf('%s',$this->sql);

			}

			//Abstract methods
			abstract public function __parseSQL();
			abstract protected function escapeValue($val);

		}

	}

