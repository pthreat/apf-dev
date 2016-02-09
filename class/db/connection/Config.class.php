<?php

	namespace apf\db\connection{

		use \apf\net\Host;
		use \apf\net\Port;
		use \apf\core\Cmd;
		use \apf\net\connection\Config			as	ConnectionConfig;
		use \apf\core\config\Ini					as IniConfig;
		use \apf\core\Directory						as	Dir;
		use \apf\core\Log;
		use \apf\validate\String					as StringValidate;
		use \apf\validate\Vector					as	VectorValidate;
		use \apf\db\Adapter							as	DatabaseAdapter;

		abstract class Config extends ConnectionConfig{

			public function setAdapter(Adapter $adapter){

				$this->adapter	=	$adapter;
				return $this;

			}

			public function getAdapter(){

				return parent::getAdapter();

			}

			public function setEnableLogging($boolean){

				$this->enableLogging	=	$boolean;
				return $this;

			}

			public function getEnableLogging(){

				return parent::getEnableLogging();

			}

			public function setIsProduction($boolean){

				$this->isProduction	=	(boolean)$boolean;
				return $this;

			}

			public function getIsProduction(){

				return parent::getIsProduction();

			}

			public function isProduction(){

				return (boolean)parent::getIsProduction();

			}

			public function setDatabase($database){

				$this->database	=	StringValidate::mustBeNotEmpty($database,$useTrim=TRUE,'Database name can not be empty');
				return $this;

			}

			public function getDatabase(){

				return parent::getDatabase();

			}

			abstract public function getRootUsername();

		}

	}

