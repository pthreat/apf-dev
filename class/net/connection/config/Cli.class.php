<?php

	namespace apf\net\connection\config{

		use \apf\core\Cmd;
		use \apf\net\Host;
		use \apf\net\Port;
		use \apf\net\connection\Config	as	NetConfig;

		public static function configureName(NetConfig &$config,LogInterface $log){

			do{

				$config->setName(Cmd::readWithDefault('name>',$config->getName(),$log));

			}while(!$config->getName());

		}

		public static function configureHost(NetConfig &$config,LogInterface $log){

			do{

				$config->setHost('host>',new Host(Cmd::readWithDefault($config->getHost())),$log);

			}while(!$config->getHost());

		}

		public static function configurePort(NetConfig &$config,LogInterface $log){

			do{

				$config->setPort('port>',new Port(Cmd::readWithDefault($config->getPort())),$log);

			}while(!$config->getPort());

		}

		public static function configureUsername(NetConfig &$config,LogInterface $log){

			do{

				$config->setUsername('username>',Cmd::readWithDefault($config->getUsername()),$log);

			}while(!$config->getUsername());

		}

		public static function configurePassword(NetConfig &$config,LogInterface $log){

			do{

				$config->setPassword('password>',Cmd::readWithDefault($config->getPassword()),$log);

			}while(!$config->getPassword());

		}

		public static function configure($config=NULL,LogInterface $log){

			$config	=	new NetConfig($config);

			$options	=	Array(
									'N'	=>	Array(
														'color'	=>	$config->getName() ? 'light_purple'	:	'light_cyan',
														'value'	=>	sprintf('%s connection name %s',
																									$config->getName() ?	'Change' : 'Set', 
																									$config->getName() ?	"({$config->getName()})" : ""
									),
									'H'	=>	Array(
														'color'	=>	$config->getHost() ? 'light_purple'	:	'light_cyan',
														'value'	=>	sprintf('%s port %s',
																									$config->getHost() ?	'Change' : 'Set', 
																									$config->getHost() ?	"({$config->getHost()})" : ""
									),
									'U'	=>	Array(
														'color'	=>	$config->getUsername() ? 'light_purple'	:	'light_cyan',
														'value'	=>	sprintf('%s port %s',
																									$config->getUsername() ?	'Change' : 'Set', 
																									$config->getUsername() ?	"({$config->getUsername()})" : ""
									),
									'S'	=>	Array(
														'color'	=>	$config->getPassword() ? 'light_purple'	:	'light_cyan',
														'value'	=>	sprintf('%s port %s',
																									$config->getPassword() ?	'Change' : 'Set', 
																									$config->getPassword() ?	"({$config->getPassword()})" : ""
									),
									'P'	=>	Array(
														'color'	=>	$config->getPort() ? 'light_purple'	:	'light_cyan',
														'value'	=>	sprintf('%s port %s',
																									$config->getPort() ?	'Change' : 'Set', 
																									$config->getPort() ?	"({$config->getPort()})" : ""
									),
									'B'	=>	'Back'
			);	

			do{

				$opt	=	Cmd::selectWithKeys($options,'>',$log);

				switch(strtolower($opt)){

					case 'n':
						self::configureName($config,$log);
					break;

					case 's':
						self::configurePassword($config,$log);
					break;

					case 'p':
						self::configurePort($config,$log);
					break;

					case 'h':
						self::configureHost($config,$log);
					break;

					case 'b':
						break 2;
					break;

				}

			}while(TRUE);

			return $config;

		}

	}


