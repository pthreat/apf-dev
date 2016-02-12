<?php

	namespace apf\db\connection\config{

		use \apf\core\Cmd;
		use \apf\net\Host;
		use \apf\net\Port;
		use \apf\iface\Log									as	LogInterface;
		use \apf\net\connection\config\Cli				as	NetConnectionCliConfig;
		use \apf\iface\config\Cli							as	CliConfig;
		use \apf\db\Adapter;

		class Cli implements CliConfig{

			public static function getConfigurationMenu(NetworkConnection &$config){

				return Array(
									'T'	=>	Array(
														'color'	=>	$config->getSocket() ? 'light_purple'	:	'light_cyan',
														'value'	=>	sprintf('%s socket %s',
																									$config->getSocket() ?	'Change' : 'Set', 
																									$config->getSocket() ?	"({$config->getSocket()})" : ""
														)
									),
									'C'	=>	Array(
														'color'	=>	$config->getCharset() ? 'light_purple'	:	'light_cyan',
														'value'	=>	sprintf('%s socket %s',
																									$config->getCharset() ?	'Change' : 'Set', 
																									$config->getCharset() ?	"({$config->getCharset()})" : ""
														)
									)
				);

			}

			public static function configure(&$config=NULL,LogInterface &$log){

				do{

					Cmd::clear();

					$log->debug('[ Select database connection type ]');

					if(!$config){

						$options			=	Adapter::listAvailable();
						$options['B']	=	'Back';
						$opt				=	Cmd::selectWithKeys($options,'>',$log);

					}

					switch(strtolower($opt)){

						case 'b':
							break 2;
						break;

						default:

							$opt							=	$options[$opt];

							$adapterClass				=	"\\apf\\db\\adapter\\$opt\\Adapter";

							$adapterConfigClass		=	"\\apf\\db\\adapter\\$opt\\adapter\\Config";

							$connectionClass			=	"\\apf\\db\\adapter\\$opt\\Connection";
							$connectionConfigClass	=	"\\apf\\db\\adapter\\$opt\\connection\\Config";
							$cliConfigClass			=	"\\apf\\db\\adapter\\$opt\\connection\\config\\Cli";

							$connectionConfig			=	new $connectionConfigClass();

							do{

								try{

									$connection					=	$cliConfigClass::configure($connectionConfig,$log);

									$adapterConfig				=	new $adapterConfigClass();
									$adapterConfig->setConnection($connection);
									$adapter	=	new $adapterClass($adapterConfig);
									$connectionConfig->setAdapter($adapter);

									return $connection;


								}catch(\Exception $e){

									$log->error($e->getMessage());
									$opt	=	Cmd::selectWithKeys(Array('A'=>'Abort','C'=>'Correct errors'),'>',$log);

									if(strtolower($opt)=='a'){

										break 2;

									}

								}

							}while(TRUE);

						break;

					}

				}while(TRUE);

			}

		}

	}

