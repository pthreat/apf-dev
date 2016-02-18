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

			private static function setDefaults(ProjectDirectoriesConfig $config){

				$projectConfig	=	$config->getProject()->getConfig();

				if($config->getRootDirectory()){

					$mainDir	=	$config->getRootDirectory();

				}else{

					$mainDir		=	new Dir(realpath(getcwd()));
					$mainDir->addPath($projectConfig->getName());

				}

				$modulesDir	=	clone($mainDir);
				$modulesDir->addPath('modules');

				$templatesDir	=	clone($mainDir);
				$templatesDir->addPath('templates');

				$fragmentsDir	=	clone($mainDir);
				$fragmentsDir->addPath('fragments');

				$config->setRootDirectory($mainDir);
				$config->setModulesDirectory($modulesDir);
				$config->setTemplatesDirectory($templatesDir);
				$config->setFragmentsDirectory($fragmentsDir);

			}

			public function resetConfiguration(&$config){

				$config->unsetRootDirectory();
				$config->unsetModulesDirectory();
				$config->unsetFragmentsDirectory();
				$config->unsetTemplatesDirectory();

			}

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

				$config	=	new ProjectDirectoriesConfig($config);

				do{

					try{

						$allConfigured	=	$config->getRootDirectory()		&& 
												$config->getFragmentsDirectory() && 
												$config->getTemplatesDirectory() && 
												$config->getModulesDirectory();

						$menu				=	Array(
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
															'L'	=>	Array(
																				'value'	=>	'Load defaults',
																				'color'	=>	$allConfigured	?	'yellow'			:	'light_green'
															)
						);


						if($config->hasValuesExcept('project')){

							$menu['E']	=	Array(
														'value'	=>	'Reset configuration',
														'color'	=>	$allConfigured	?	'red'				:	'yellow'
							);

						}

						$menu['S']	=	Array(
													'value'	=>	'Save',
													'color'	=>	$allConfigured	?	'light_green'	:	'yellow',
						);

						$menu['B']	=	Array(
													'value'	=>	'Back',
													'color'	=>	$allConfigured	?	'light_cyan'	:	'yellow'
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

							case 'e':

								if($config->hasValues() && Cmd::yesNo('Are you sure you want to reset the entire configuration?',$log)){

									self::resetConfiguration($config);

								}

							break;

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

							case 'l':

								if($allConfigured){

									$log->warning('Everything seems to be configured, are you sure you want to set defaults?');
									Cmd::yesNo('>',$log);

								}

								self::setDefaults($config);

							break;

							case 'b':

								//No values, assume safe "back"

								if(!$config->hasValues()||$allConfigured){

									return FALSE;

								}

								$log->warning("You have unsaved changes in this configuration.");

								if(Cmd::yesNo("Are you sure you want to go back without saving?",$log)){

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
