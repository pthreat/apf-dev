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

		class Config extends BaseConfig{

			public function setId($id){

				$id	=	trim($id);

				if(empty($id)){

					throw new \InvalidArgumentException("Connection identifier can not be empty");

				}

				$this->id	=	$id;

				return $this;

			}

			public function getId(){

				return parent::getId();

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

			public static function getDefaultInstance(){

				$obj	=	new static();

				$obj->setHost(new Host('localhost'));
				$obj->setPort(new Port(0));

				return $obj;

			}

		}

	}

