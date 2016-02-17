<?php

	namespace apf\core\project\module\directories\config{

		use \apf\core\project\Module;
		use \apf\cpre\project\module\Config						as	ModuleConfig;
		use \apf\core\project\module\Directories				as	ModuleDirectories;
		use \apf\core\project\module\directories\Config		as	ModuleDirectoriesConfig;
		use \apf\iface\Log											as	LogInterface;

		use \apf\core\Cmd;
		use \apf\core\Directory										as	Dir;
		use \apf\iface\config\Cli									as	CliConfigInterface;

		class Cli implements CliConfigInterface{

			use \apf\traits\config\cli\RootDirectory;
			use \apf\traits\config\cli\sub\Directories;
			use \apf\traits\config\cli\template\Directories;
			use \apf\traits\config\cli\fragment\Directories;

			private static function setDefaults(ModuleDirectoriesConfig $config){

				$moduleConfig	=	$config->getModule()->getConfig();

				if($config->getRootDirectory()){

					$mainDir	=	$config->getRootDirectory();

				}else{

					$mainDir		=	new Dir(realpath(getcwd()));
					$mainDir->addPath($moduleConfig->getName());

				}

				$subsDir	=	clone($mainDir);
				$subsDir->addPath('modules');

				$templatesDir	=	clone($mainDir);
				$templatesDir->addPath('templates');

				$fragmentsDir	=	clone($mainDir);
				$fragmentsDir->addPath('fragments');

				$config->setRootDirectory($mainDir);
				$config->setSubsDirectory($subsDir);
				$config->setTemplatesDirectory($templatesDir);
				$config->setFragmentsDirectory($fragmentsDir);

			}

			/**
			 * Configure module directories.
			 *
			 * This interactive menu will allow the end user to configure several module directories.
			 *
			 * A) Configure the Module		directory, this is the base directory where the module will be located.
			 * B) Configure the Subs		directory, this is the directory where modules will be in.
			 * C) Configure the Templates directory, this is the directory where global templates will be stored.
			 * D) Configure the Fragments directory, this is the directory where global fragments will be stored.
			 *
			 * @params \apf\core\project\module\directories\Config	A module directories configuration object.
			 * @params \apf\iface\Log											A log interface to display messages and prompts in the command line.
			 *	@return \apf\core\project\module\Directories				A configured module directories object.
			 *	@return boolean	FALSE											If the user aborts the configuration process.
			 *
			 */

			public static function configure(&$config=NULL, LogInterface &$log){

				$config	=	new ModuleDirectoriesConfig($config);

				do{

					try{

						$allConfigured	=	$config->getRootDirectory() && $config->getFragmentsDirectory() && $config->getTemplatesDirectory() && $config->getSubsDirectory();

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
															'U'	=>	Array(
																				'value'	=>	sprintf('Configure subs directory (%s)',$config->getSubsDirectory()),
																				'color'	=>	$config->getSubsDirectory() ? 'light_purple'	:	'light_cyan'
															),
															'D'	=>	Array(
																				'value'	=>	'Set defaults',
																				'color'	=>	$allConfigured	?	'yellow'	:	'light_green'
															),
															'S'	=>	'Save',
															'B'	=>	'Back'
						);

						Cmd::clear();

						$log->debug("[ Configure module directories ]");

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

							case 'u':

								self::configureSubDirectories($config,$log);

							break;

							case 's':

								try{

									return new ModuleDirectories($config);

								}catch(\Exception $e){
	
									$log->warning("There are errors in your configuration.");
									$log->error($e->getMessage());
									$log->debug("Please correct the error mentioned above and try saving again.");

									Cmd::readInput('Press enter to continue ...',$log);

								}

							break;


							case 'd':

								if($allConfigured){

									$log->warning('Everything seems to be configured, are you sure you want to set defaults?');
									Cmd::yesNo('>',$log);

								}

								self::setDefaults($config);

							break;

							case 'b':

								//No values, assume safe "back"
								if(!$config->hasValues()){

									break 2;

								}

								$log->warning("You have unsaved changes in this configuration.");

								if(Cmd::yesNo("Are you sure you want to go back without saving?",$log)){

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
