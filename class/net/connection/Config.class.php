<?php

	/*
	 * This is a connection configuration object
	 * it is to be used to be passed to any connection class
	 * through the constructor or through the configure method
	 */

	namespace apf\net\connection{

		use \apf\net\Host;
		use \apf\net\Port;
		use \apf\core\Config as BaseConfig;
		use \apf\core\Log;
		use \apf\core\Cmd;
		use \apf\net\Adapter	as	 NetworkAdapter;

		abstract class Config extends BaseConfig{

			public function setName($name){

				$name	=	trim($name);

				if(empty($name)){

					throw new \InvalidArgumentException("Connection name can not be empty");

				}

				$this->name	=	$name;

				return $this;

			}

			public function getName(){

				return parent::getName();

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

			public function setHost(Host $host){

				$this->host	=	$host;
				return $this;

			}

			public function getHost(){

				return parent::getHost();

			}

			public function setPort(Port $port){

				$this->port	=	$port;
				return $this;

			}

			public function getPort(){

				return parent::getPort();

			}

			public function setUsername($username){

				$this->username	=	$username;
				return $this;

			}

			public function getUsername(){

				return parent::getUsername();

			}

			public function setPassword($password){

				$this->password	=	$password;
				return $this;

			}

			public function getPassword(){

				return parent::getPassword();

			}

			public function setAdapter(NetworkAdapter $adapter){

				$this->adapter	=	$adapter;
				return $this;

			}

			public function getAdapter(){

				return parent::getAdapter();

			}

			abstract public function getType();

		}

	}

