<?php

	namespace apf\db\adapter\mysql5{

		use \apf\iface\Log									as	LogInterface;
		use \apf\core\Cmd;
		use \apf\core\Config;
		use \apf\validate\String							as	StringValidate;
		use \apf\db\Connection								as	DatabaseConnection;
		use \apf\db\adapter\mysql5\Adapter				as	Mysql5Adapter;
		use \apf\db\adapter\mysql5\connection\Config	as	Mysql5Config;

		class Connection extends DatabaseConnection{

			protected function __connect(){

				$config	=	$this->getConfig();

				$con	=	new \Mysqli(
											$config->getHost(),
											$config->getUsername(),
											$config->getPassword(),
											$config->getDatabase(),
											sprintf('%s',$config->getPort()),
											$config->getSocket() ? $config->getSocket() : NULL
				);

				if($con->connect_errno){

					$msg	=	'Could not connect to database "%s" at host %s:%s';	
					$msg	=	sprintf($msg,$config->getDatabase(),$config->getHost(),$config->getPort());
					throw new \RuntimeException($msg);

				}

				$charset	=	$config->getCharset();
				$charset	=	empty($charset)	?	'utf8'	:	$charset;
				$con->set_charset($charset);

				return $con;

			}

			protected static function dbConnectionInteractiveConfig(LogInterface &$log,Config &$config){

				if(!($config instanceof Mysql5Config)){

					throw new \LogicException("Configuration object must be a mysql5 connection configuration object");

				}

				$config->setCharset(Cmd::readWithDefault('Charset:',$config->getCharset(),$log));
				$config->setSocket(Cmd::readWithDefault('Socket:',$config->getSocket(),$log));

			}

			public function __toString(){

				return '';

			}

		}

	}

