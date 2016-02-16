<?php

	namespace apf\core\project\directories\config{

		use \apf\core\Project;
		use \apf\core\project\Directories					as	ProjectDirectories;
		use \apf\core\project\directories\Config			as	ProjectDirectoriesConfig;
		use \apf\iface\Log										as	LogInterface;
		use \apf\core\project\Config							as	ProjectConfig;

		use \apf\core\Cmd;
		use \apf\core\Directory									as	Dir;
		use \apf\iface\config\Cli								as	CliConfigInterface;

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
			 * D) Configure the fragments directory, this is the directory where global fragments will be stored.
			 *
			 * @params \apf\core\project\Config			A project configuration object
			 * @params \apf\iface\Log						A log interface to display messages and prompts in the command line.
			 *	@return \apf\core\project\Directories	A configured project directories object.
			 *
			 */

			public static function configure(&$config=NULL, LogInterface &$log){

				$config	=	new ProjectDirectoriesConfig($config);

				do{

					try{

						$menu		=	Array(
												'R'	=>	Array(
																	'value'	=>	sprintf('Configure root directory (%s)',$config->getRootDirectory()),
																	'color'	=>	$config->getRootDirectory() ? 'light_purple'	:	'light_cyan'
												),
												'T'	=>	Array(
																	'value'	=>	sprintf('Configure templates directory (%s)',$config->getTemplatesDirectory()),
																	'color'	=>	$config->getTemplatesDirectory() ? 'light_purple'	:	'light_cyan'
												),
												'F'	=>	Array(
																	'value'	=>	sprintf('Configure fragments directory (%s)',$config->getFragmentsDirectory()),
																	'color'	=>	$config->getFragmentsDirectory() ? 'light_purple'	:	'light_cyan'
												),
												'M'	=>	Array(
																	'value'	=>	sprintf('Configure modules directory (%s)',$config->getModulesDirectory()),
																	'color'	=>	$config->getModulesDirectory() ? 'light_purple'	:	'light_cyan'
												),
												'D'	=>	'Set defaults',
												'S'	=>	'Save',
												'B'	=>	'Back'
						);

						Cmd::clear();

						$log->debug("[ Configure project directories ]");

						$log->repeat('-',80,'light_purple');

						$opt	=	Cmd::selectWithKeys($menu,'directories>',$log);

						switch(trim(strtolower($opt))){

							case 'r':
								self::configureRootdirectory($config,$log);
							break;

							case 't':
								self::configureTemplateDirectories($config,$log);
							break;

							case 'f':
								self::configureFragmentDirectories($config,$log);
							break;

							case 'm':
								self::configureModuleDirectories($config,$log);
							break;

							case 's':

								try{

									return new ProjectDirectories($config);

								}catch(\Exception $e){
	
									$log->warning("There are errors in your configuration.");
									$log->error($e->getMessage());
									$log->debug("Please correct the error mentioned above and try saving again.");

									Cmd::readInput('Press enter to continue ...',$log);

								}

							break;

							case 'b':

								//No values, assume safe "back"
								if(!$config->hasValues()){

									break 2;

								}

								$log->warning("You have unsaved changes in this configuration.");

								if(Cmd::selectWithKeys("Are you sure you want to go back?",$log)){

									break 2;

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
