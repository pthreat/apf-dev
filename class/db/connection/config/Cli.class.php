<?php

	namespace apf\db\connection\config{

		use \apf\iface\Log								as	LogInterface;
		use \apf\core\project\Config					as	ProjectConfig;
		use \apf\db\connection\Config					as	ConnectionConfig;
		use \apf\core\Cmd;
		use \apf\core\Directory							as	Dir;
		use \apf\iface\config\Cli						as	CliConfigInterface;
		use \apf\db\Connection							as DatabaseConnection;

		class Cli implements CliConfigInterface{

			public static function selectAdapter(LogInterface $log){

				Cmd::select(DatabaseConnection::listConnections(),'>',$log);

			}

			public static function configure($config=NULL,LogInterface $log){

				$options	=	Array(
										'A'	=>	'Choose Adapter'
				);

				$adapter	=	NULL;

				//According  to the chosen adapter, call the corresponding connection configuration
				do{

					Cmd::clear();

					$options['H']	=	'Help';
					$options['B']	=	'Back';

					$log->debug('[ Select database connection ]');
					$log->repeat('-',80,'light_purple');

					try{

						$option	=	Cmd::selectWithKeys($projectOptions,'>',$log);

						switch(strtolower($option)){

							case 'a':
								$adapter	=	self::selectAdapter($config,$log);
							break;

							case 'e':

							break;

							case 'h':

							break;

							case 'b':

								break 2;

							break;

						}

					}catch(\Exception $e){

						$log->error($e->getMessage());
						Cmd::readInput('Press enter to continue ...',$log);

					}

				}while(TRUE);

			}

		}

	}
