<?php

	namespace apf\db\adapter\mysql5\config{

		use \apf\core\Cmd;
		use \apf\iface\Log											as	LogInterface;
		use \apf\iface\config\Cli									as	CliConfigInterface;
		use \apf\net\connection\config\Cli						as	NetConnectionCli;
		use \apf\db\adapter\mysql5\Config						as	AdapterConfig;
		use \apf\db\adapter\mysql5\Connection					as	Mysql5Connection;
		use \apf\db\adapter\mysql5\connection\Config			as	Mysql5ConnectionConfig;
		use \apf\db\adapter\mysql5\connection\config\Cli	as	Mysql5ConnectionCliConfig;

		class Cli implements CliConfigInterface{

			public static function configure($config=NULL,LogInterface $log){

				$config		=	new AdapterConfig($config);

				$options	=	Array(
										'C'	=>	'Set/Change connection parameters',
										'P'	=>	'Print connection parameters',
										'B'	=>	'Back'
				);

				$connectionConfig	=	new Mysql5ConnectionConfig();
				do{

					Cmd::clear();
					$log->debug('[ Configure MySQL 5 database adapter ]');
					$log->repeat('-',80,'light_purple');
					$opt	=	Cmd::selectWithKeys($options,'>',$log);

					if($config->getConnection()){

						$connectionConfig	=	$config->getConnection()->getConfig();

					}

					switch(strtolower($opt)){

						case 'c':

							do{

								try{

									$config->setConnection(Mysql5ConnectionCliConfig::configur($connectionConfig,$log));

								}catch(\Exception $e){

									$log->debug('[You have errors in your connection configuration]');
									$log->error($e->getMessage());
									$opt	=	Cmd::selectWithKeys(Array('A'=>'Abort connection configuration','C'=>'Correct errors'),'>',$log);

									if(strtolower($opt)=='a'){

										break;

									}

								}

							}while(TRUE);

						break;

						case 'p':
							echo $config->getConnection();
						break;

						case 'b':
							break 2;
						break;

					}

				}while(TRUE);

				return $config;

			}

		}

	}
