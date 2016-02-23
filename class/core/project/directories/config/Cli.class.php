<?php

	namespace apf\core\project\directories\config{

		use \apf\core\Project;
		use \apf\core\project\Directories							as	ProjectDirectories;
		use \apf\core\project\directories\Config					as	ProjectDirectoriesConfig;
		use \apf\iface\Log												as	LogInterface;
		use \apf\core\project\Config									as	ProjectConfig;
		use \apf\core\project\config\cli\directories\Helper	as	DirectoriesHelper;

		use \apf\core\Cmd;
		use \apf\core\Directory											as	Dir;
		use \apf\iface\config\Cli										as	CliConfigInterface;

		class Cli implements CliConfigInterface{

			use \apf\traits\config\cli\RootDirectory;
			use \apf\traits\config\cli\module\Directories;
			use \apf\traits\config\cli\template\Directories;
			use \apf\traits\config\cli\fragment\Directories;

			/**
			 * Configure project directories.
			 *
			 * This interactive menu will allow the end user to configure several project directories.
			 *
			 * A) Configure the Project	directory, this is the base directory where the project will be located.
			 * B) Configure the Modules	directory, this is the directory where modules will be in.
			 * C) Configure the Templates directory, this is the directory where global templates will be stored.
			 * D) Configure the Fragments directory, this is the directory where global fragments will be stored.
			 *
			 * @params \apf\core\project\Config			A project configuration object
			 * @params \apf\iface\Log						A log interface to display messages and prompts in the command line.
			 *	@return \apf\core\project\Directories	A configured project directories object.
			 *
			 */

			public static function configure(&$config=NULL, LogInterface &$log){

				$config		=	new ProjectDirectoriesConfig($config);
				$extraMenus	=	Array('back','save','defaults','reset');

				do{

					try{

						$menu				=	DirectoriesHelper::getMenu($config,$valuesExcept=Array('project'),$extraMenus);
						$allConfigured	=	$config->hasValuesExcept(Array('project'));

						Cmd::clear();

						$log->debug("[ Configure project directories ]");
						$log->repeat('-',80,'light_purple');

						$opt	=	Cmd::selectWithKeys($menu,'directories>',$log);

						switch(trim(strtolower($opt))){

							case 's':

								try{

									return new ProjectDirectories($config,$validate='soft');

								}catch(\Exception $e){
	
									$log->warning("There are errors in your configuration.");
									$log->error($e->getMessage());
									$log->debug("Please correct the error mentioned above and try saving again.");

									Cmd::readInput('Press enter to continue ...',$log);

								}

							break;

							default:

								if(DirectoriesHelper::switchMenuOption($opt,$config,$allConfigured,$log) === FALSE){

									return FALSE;

								}

							break;

						}

					}catch(\Exception $e){

						$log->error($e->getMessage());
						Cmd::readInput('There are errors in your configuration');

					}

				}while(TRUE);

			}

		}

	}
