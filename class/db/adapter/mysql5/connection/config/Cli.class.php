<?php

	namespace apf\db\adapter\mysql5\connection\config{

		use \apf\core\Cmd;
		use \apf\net\Host;
		use \apf\net\Port;
		use \apf\iface\Log									as	LogInterface;
		use \apf\db\adapter\mysql5\Connection			as	Mysql5Connection;
		use \apf\db\adapter\mysql5\connection\Config	as	Mysql5ConnectionConfig;
		use \apf\net\connection\config\Cli				as	NetConnectionCliConfig;
		use \apf\iface\config\Cli							as	CliConfig;

		class Cli implements CliConfig{

			public static function configureCharset(&$config,LogInterface $log){

				do{
					try{

						$config->setCharset(Cmd::readWithDefault('charset>',$config->getCharset(),$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getCharset());

			}

			public static function configureSocket(&$config,LogInterface $log){

				do{

					try{

						$config->setSocket(Cmd::readWithDefault('socket>',$config->getSocket(),$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getSocket());

			}

			public static function configure(&$config=NULL,LogInterface &$log){

				$config	=	new Mysql5ConnectionConfig($config);

				do{

					Cmd::clear();

					$log->debug('[ Configure Mysql5 database connection ]');
					$log->repeat('-',80,'light_purple');

					$options	=	Array(
											
											'E'	=>	Array(
																'color'	=>	$config->getCharset() ? 'light_purple'	:	'light_cyan',
																'value'	=>	sprintf('%s charset %s',
																											$config->getCharset() ?	'Change' : 'Set', 
																											$config->getCharset() ?	"({$config->getCharset()})" : ""
																)
											),
											'T'	=>	Array(
																'color'	=>	$config->getSocket() ? 'light_purple'	:	'light_cyan',
																'value'	=>	sprintf('%s connection socket %s',
																											$config->getSocket() ?	'Change' : 'Set', 
																											$config->getSocket() ?	"({$config->getSocket()})" : ""
																)
											),
											'B'	=>	'Back'
					);	

					$options	=	NetConnectionCliConfig::getConfigurationMenu($config,$options);
					$opt		=	Cmd::selectWithKeys($options,'>',$log);

					switch(strtolower($opt)){

						case 'e':
							self::configureCharset($config,$log);
						break;

						case 't':
							self::configureSocket($config,$log);
						break;

						case 'b':

							$connection	=	new Mysql5Connection($config);
							return $connection;

						break;

						default:
							NetConnectionCliConfig::configureConnection($opt,$config,$log);
						break;

					}

				}while(TRUE);

			}

		}

	}


