<?php

	namespace apf\core\project{

		use apf\core\Config as BaseConfig;
		use apf\core\Dir;

		class Config extends BaseConfig{

			public function setDirectory(Dir $dir){

				$this->directory	=	$dir;
				return $this;

			}

			public function addDatabaseConnection(DatabaseConnection $connection){

				if(!sizeof($this->connections)){

					$this->connections	=	Array();

				}

				$this->connections[]	=	$connection->getConfig();

				return $this;

			}

			public function getDatabaseConnection($connectionName){
			}

		}

	}

