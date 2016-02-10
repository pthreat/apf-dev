<?php

	namespace apf\core\project\config{

		use \apf\iface\Log								as	LogInterface;
		use \apf\core\project\Config;
		use \apf\core\Project;
		use \apf\core\project\Module					as	ProjectModule;
		use \apf\core\project\module\Config			as	ModuleConfig;
		use \apf\core\project\module\config\Cli	as	ModuleCli;
		use \apf\core\project\Config					as	ProjectConfig;

		use \apf\web\asset\config\Cli					as	AssetCli;

		use \apf\web\asset\Javascript					as	JSAsset;
		use \apf\web\asset\Css							as	CSSAsset;

		use \apf\web\Asset;

		use \apf\core\Cmd;
		use \apf\core\Directory							as	Dir;

		use \apf\iface\config\Cli						as	CliConfigInterface;

		class Cli implements CliConfigInterface{

			public static function configure(&$config=NULL, LogInterface &$log){

				$projectOptions	=	Array(
													'C'	=>	'Create project',
													'E'	=>	'Edit project',
													'H'	=>	'Help',
													'Q'	=>	'Quit'
				);

				do{

					Cmd::clear();

					$log->debug('-[Apollo Framework interactive configuration]-');
					$log->repeat('-',80,'light_purple');

					try{

						$option	=	Cmd::selectWithKeys($projectOptions,'apf>',$log);

						switch(strtolower($option)){

							case 'c':

								self::configureProject($config,$log);

							break;

							case 'e':

								$log->debug('Edit project, select project path and then load given project configuration');	
								Cmd::readInput('press enter ...');

							break;

							case 'h':

								$log->debug('Given configuration interface will allow you to create or edit a new project.');
								$log->debug('Press N to create a new project');
								$log->debug('Press E to edit a project, in this case you will have to enter the path where the project is located');

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
