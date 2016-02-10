<?php

	namespace apf\net\connection\config{

		use \apf\core\config\Validator	as	BaseValidator;
		use \apf\net\Host;
		use \apf\net\Port;

		abstract class Validator extends BaseValidator{

			protected static function __softConfigValidation($config){

				$config->setName($config->getName());

				if(!$config->getHost()){

					throw new \InvalidArgumentException("Host name must be specified");

				}

				if(!$config->getPort()){

					throw new \InvalidArgumentException("Port number must be specified");

				}

				$config->setHost(new Host($config->getHost()));
				$config->setPort(new Port($config->getPort()));
				$config->setUsername($config->getUsername());
				$config->setPassword($config->getPassword());

				return TRUE;

			}

			protected static function __hardConfigValidation($config){

				return TRUE;

			}

			protected static function __extraConfigValidation($config){

				return TRUE;

			}

		}

	}

