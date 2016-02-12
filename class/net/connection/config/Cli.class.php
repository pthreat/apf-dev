<?php

	namespace apf\net\connection\config{

		use \apf\core\Cmd;
		use \apf\net\Host;
		use \apf\net\Port;
		use \apf\net\connection\Config	as	NetConfig;
		use \apf\net\Adapter					as	NetworkAdapter;
		use \apf\iface\Log					as	LogInterface;
		use \apf\iface\config\Cli			as	CliConfigInterface;

		class Cli implements CliConfigInterface{

			//Configure connection name / identifier
			////////////////////////////////////////////

			public static function configureName(NetConfig &$config,LogInterface $log){

				do{

					try{

						$config->setName(Cmd::readWithDefault('name>',$config->getName(),$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getName());

			}

			public static function configureHost(NetConfig &$config,LogInterface $log){

				do{

					try{

						$config->setHost(new Host(Cmd::readWithDefault('host>',$config->getHost(),$log)));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getHost());

			}

			public static function configurePort(NetConfig &$config,LogInterface $log){

				do{

					$config->setPort(new Port(Cmd::readWithDefault('port>',$config->getPort(),$log)));

				}while(!$config->getPort());


			}

			public static function configureUsername(NetConfig &$config,LogInterface $log){

				do{

					try{

						$config->setUsername(Cmd::readWithDefault('username>',$config->getUsername()),$log);

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getUsername());

			}

			public static function configurePassword(NetConfig &$config,LogInterface $log){

				do{

					try{

						$config->setPassword(Cmd::readWithDefault('password>',$config->getPassword(),$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getPassword());

			}

			public static function configureMode(NetConfig &$config,LogInterface &$log){

				$log->debug('[ Specify connection mode ]');

				$log->success('1 = Production');
				$log->warning('0 = Development');

				$config->setIsProduction(Cmd::readInput('mode>',$log));

			}

			public static function getConfigurationMenu(&$config,Array $menu=Array()){

					$options	=	Array(
											'N'	=>	Array(
																'color'	=>	$config->getName() ? 'light_purple'	:	'light_cyan',
																'value'	=>	sprintf('%s connection name %s',
																											$config->getName() ?	'Change' : 'Set', 
																											$config->getName() ?	"({$config->getName()})" : ""
																)
											),
											'M'	=>	Array(
																'color'	=>	$config->getIsProduction() ? 'light_green'	:	'yellow',
																'value'	=>	sprintf('Set as %s',
																											$config->getIsProduction() ?	'production' : 'dev', 
																											$config->getIsProduction() ?	"({$config->getIsProduction()})" : ""
																)
											),
											'H'	=>	Array(
																'color'	=>	$config->getHost() ? 'light_purple'	:	'light_cyan',
																'value'	=>	sprintf('%s host %s',
																											$config->getHost() ?	'Change' : 'Set', 
																											$config->getHost() ?	"({$config->getHost()})" : ""
																)
											),
											'P'	=>	Array(
																'color'	=>	$config->getPort() ? 'light_purple'	:	'light_cyan',
																'value'	=>	sprintf('%s port %s',
																											$config->getPort() ?	'Change' : 'Set', 
																											$config->getPort() ?	"({$config->getPort()})" : ""
																)
											),
											'U'	=>	Array(
																'color'	=>	$config->getUsername() ? 'light_purple'	:	'light_cyan',
																'value'	=>	sprintf('%s username %s',
																											$config->getUsername() ?	'Change' : 'Set', 
																											$config->getUsername() ?	"({$config->getUsername()})" : ""
																)
											),
											'S'	=>	Array(
																'color'	=>	$config->getPassword() ? 'light_purple'	:	'light_cyan',
																'value'	=>	sprintf('%s password %s',
																											$config->getPassword() ?	'Change' : 'Set', 
																											$config->getPassword() ?	"({$config->getPassword()})" : ""
																)
											)
					);

					return array_merge($options,$menu);

			}

			public static function configure(&$config=NULL, LogInterface &$log){
			}

			public static function configureConnection($option,NetConfig &$config,LogInterface $log){

				switch(strtolower($option)){

					case 'n':
						self::configureName($config,$log);
					break;

					case 'u':
						self::configureUsername($config,$log);
					break;

					case 's':
						self::configurePassword($config,$log);
					break;

					case 'p':
						self::configurePort($config,$log);
					break;

					case 'm':
						self::configureMode($config,$log);
					break;

					case 'h':
						self::configureHost($config,$log);
					break;
				}

			}

		}

	}


