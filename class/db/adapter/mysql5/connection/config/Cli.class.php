<?php

	namespace apf\net\connection\config{

		use \apf\core\Cmd;
		use \apf\net\Host;
		use \apf\net\Port;
		use \apf\db\mysql5\connection\Config	as	Mysql5ConnectionConfig;

		public static function configure($config=NULL,LogInterface $log){

			$config	=	new Mysql5ConnectionConfig($config);

			$options	=	Array(
									
									'P'	=>	'Configure connection parameters',
									'E'	=>	Array(
														'color'	=>	$config->getCharset() ? 'light_purple'	:	'light_cyan',
														'value'	=>	sprintf('%s port %s',
																									$config->getCharset() ?	'Change' : 'Set', 
																									$config->getCharset() ?	"({$config->getCharset()})" : ""
									),
									'T'	=>	Array(
														'color'	=>	$config->getSocket() ? 'light_purple'	:	'light_cyan',
														'value'	=>	sprintf('%s connection socket %s',
																									$config->getSocket() ?	'Change' : 'Set', 
																									$config->getSocket() ?	"({$config->getSocket()})" : ""
									),
									'B'	=>	'Back'
			);	

			do{

				$opt	=	Cmd::selectWithKeys($options,'>',$log);

				switch(strtolower($opt)){

					case 'p':
						NetCliConfig::configure($config,$log);
					break;

					case 'e':
						self::configureEncoding($config,$log);
					break;

					case 't':
						self::configureSocket($config,$Log);
					break;

					case 'b':
						break 2;
					break;

				}

			}while(TRUE);

			return $config;

		}

	}


