<?php

	namespace apf\db\adapter\mysql5\connection{

		use \apf\validate\String					as	StringValidate;
		use \apf\db\connection\Config				as	DatabaseConnectionConfig;
		use \apf\core\Log;
		use \apf\core\Cmd;
		use \apf\net\Host;
		use \apf\net\Port;

		class Config extends DatabaseConnectionConfig{

			public function getRootUsername(){

				return 'root';

			}

			public function setUsername($username){

				$this->username	=	StringValidate::mustBeNotEmpty($username,$useTrim=TRUE,'Username can not be empty');
				return $this;

			}

			public function setPassword($password){

				$this->password	=	$password;
				return $this;

			}

			public function setCharset($charset){

				$this->charset	=	$charset;
				return $this;

			}

			public function getCharset(){

				return parent::getCharset();

			}

			public function setSocket($socket){

				$this->socket	=	$socket;
				return $this;

			}

			public function getSocket(){

				return parent::getSocket();

			}

			public static function getDefaultInstance(){

				$instance	=	parent::getDefaultInstance();
				$instance->setHost(new Host('localhost'));
				$instance->setPort(new Port(3306));
				$instance->setCharset('utf8');

				return $instance;

			}

		}

	}

