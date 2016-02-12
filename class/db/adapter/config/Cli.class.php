<?php

	namespace apf\db\adapter\config{

		use \apf\iface\Log								as	LogInterface;
		use \apf\core\project\Config;
		use \apf\core\project\module\Config			as	ModuleConfig;
		use \apf\core\project\module\config\Cli	as	ModuleCli;
		use \apf\core\project\Config					as	ProjectConfig;
		use \apf\db\Adapter;

		use \apf\core\Cmd;

		use \apf\iface\config\Cli						as	CliConfigInterface;

		class Cli implements CliConfigInterface{

			public static function configureAdapter(ProjectConfig &$config,LogInterface &$log){

				$log->debug('[ Select database adapter ]');
				$options			=	Adapter::listAvailable();
				$options['B']	=	'Back';

				$opt	=	strtolower(Cmd::select($options,'adapter>',$log));

				if($opt=='b'){

					return;

				}

				$adapterCliClass	=	sprintf('\\apf\\db\\adapter\\%s\\config\\Cli',$opt);

				return $adapterCliClass::configure($config,$log);

			}

			public static function configure(&$config=NULL, LogInterface &$log){

				$config	=	new ProjectConfig($config);

				$options	=	Array(
										'S'	=>	'Select database adapter',
										'H'	=>	'Help',
										'B'	=>	'Back'
				);

				do{

					Cmd::clear();

					$log->debug('[Database adapter configuration]');
					$log->repeat('-',80,'light_purple');

					try{

						$option	=	Cmd::selectWithKeys($options,'>',$log);

						switch(strtolower($option)){

							case 's':

								$adapter	=	self::configureAdapter($config,$log);

								if($adapter){
								}
							break;

							case 'h':

								$log->debug('This menu will allow you to select an available database adapter for further configuration');
								$log->debug('If your project already has configured adapters, an edit menu will appear which will enable ');
								$log->debug('you to edit the selected adapter.');
								$log->debug('Press C to configure a new adapter for your project.');
								$log->debug('Press E to edit a pre existent adapter in your project.');

								Cmd::readInput('Press any key to continue ...');

							break;

							case 'q':

								break 2;

							break;

							default:

								throw new \InvalidArgumentException("Invalid option selected: $option");

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
