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

		abstract class Config extends ConnectionConfig{

			public function __construct(Array $values=Array()){

				parent::__construct($values);

				$adapter	=	get_class($this);
				$adapter	=	substr($adapter,strpos($adapter,'adapter'));
				$adapter	=	substr($adapter,0,strrpos($adapter,'\\'));
				$adapter	=	substr($adapter,strpos($adapter,'\\')+1);
				$adapter	=	substr($adapter,0,strpos($adapter,'\\'));

				$this->setAdapter($adapter);

			}

			public function setAdapter($adapter){

				$adapter			=	trim(strtolower($adapter));
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

