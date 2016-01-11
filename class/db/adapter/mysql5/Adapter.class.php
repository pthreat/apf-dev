<?php

	namespace apf\db\adapter\mysql5{

		use \apf\db\Adapter	as	BaseAdapter;		

		class Adapter extends BaseAdapter{

			protected function __getCommentOpenings(){

				return Array('/*','--','#','\'');

			}

			protected function __getQuoteCharacter(){

				return "'";

			}

			protected function __getCommentClosings(){

				return Array('*/');

			}

			protected function __startTransaction(){

				return $this->getConnection()->query('START TRANSACTION');

			}

			protected function __commitTransaction(){

				return $this->getConnection()->query('COMMIT');	

			}

			protected function __rollback(){

				return $this->getConnection()->query('ROLLBACK');

			}

			public function findTable($name){

				foreach($this->listTables() as $table){

					if($table->TABLE_NAME == $name){

						return Table::getInstance($name);

					}

				}

				throw new \RuntimeException(sprintf('Table "%s" could not be found in schema "%s"',$name,$this->params['name']));

			}

			public function listTables($cache=TRUE){

				if($cache && sizeof($this->tables)){

					return $this->tables;

				}

				$select	=	new Select('information_schema.TABLES');

				$select->where(Array(
											Array(
													'field'	=>	'TABLE_SCHEMA',
													'value'	=>	$this->params['name']
											)
				));

				return $this->tables	=	$select->execute('\apf\db\mysql5\Table',$smart=FALSE);

			}

			public function __getDate($format="Y-m-d H:i:s"){

				$date	=	$this->getConnection()->query('SELECT NOW() AS date');

				if($asObject){

					$date	=	\DateTime::createFromFormat("Y-m-d H:i:s",$date);

				}

				return $date;
			
			}

			protected function __escapeValue($value){

				return $this->getConnection()->real_escape_string($value);

			}

			protected function __directQuery($sql){

				$result	=	$this->connection->query($sql);

				if($result===FALSE){

					throw(new \Exception($this->connection->error));

				}

				return $result;
			
			}

		}

	}

