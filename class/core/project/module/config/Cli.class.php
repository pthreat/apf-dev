<?php

	namespace apf\core\project\module\config{

		use \apf\core\Cmd;
		use \apf\iface\config\Cli						as	CliConfigInterface;
		use \apf\core\Project;
		use \apf\core\project\Config					as	ProjectConfig;
		use \apf\core\project\Module;
		use \apf\iface\Log								as	LogInterface;
		use \apf\core\project\module\Config			as	ModuleConfig;
		use \apf\core\project\module\config\Cli	as	ModuleCli;
		use \apf\core\project\module\Sub;
		use \apf\core\project\module\sub\Config	as	SubConfig;
		use \apf\core\Directory							as	Dir;

		class Cli implements CliConfigInterface{

			public static function addModules(Project &$project,LogInterface $log){

				$config	=	$project->getConfig();

				do{

					Cmd::clear();

					$log->info('Project modules');
					$log->repeat('-','80','light_purple');

					$options	=	Array(
											'N'	=>	'New Module'
					);

					$hasModules	=	$config->hasModules();

					if($hasModules){

						self::listModules($config,$log);

						$options['E']	=	'Edit modules';
						$options['L']	=	'List modules';

					}

					$options['B']	=	'Back';

					$opt	=	Cmd::selectWithKeys($options,'>',$log);

					switch(strtolower($opt)){

						case 'n':

							$moduleConfig	=	new ModuleConfig();
							$moduleConfig->setProject($project);
							self::configure($moduleConfig,$log);

						break;

						case 'e':

							if($hasModules){

								self::editModules($config,$log);

							}

						break;

						case 'l':

							if($hasModules){

								self::listModules($config,$log);
								Cmd::readInput('Press enter to continue ...');

							}

						break;

						case 'd':

							if($hasModules){

								self::deleteModules($config,$log);

							}

						break;

						case 'b':

							break 2;

						break;

					}

				}while(TRUE);

			}


			//Configure module name
			public static function configureModuleName(ModuleConfig &$config, LogInterface $log){

				do{

					try{

						$config->setName(Cmd::readInput('Module name:',$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getName());

			}

			//Configure root directory

			public static function configureModuleDirectory(ModuleConfig $config,LogInterface $log){

				$projectConfig	=	$config->getProject()->getConfig();

				do{

					$log->info('Please specify the directory for this module');

					$dir	=	$projectConfig->getModulesDirectory()->addPath($config->getName());

					$config->setDirectory(
													new Dir(
																Cmd::readWithDefault(
																							'directory>',
																							$dir,
																							$log
																)
													)
					);

				}while(!$config->getDirectory());

			}

			//Configure subs directory

			public static function configureSubsDirectory(ModuleConfig &$config,LogInterface $log){

				do{

					$log->info('Please specify the subs directory for this module');

					$dir	=	clone($config->getDirectory());
					$dir->addPath('subs');

					$config->setSubsDirectory(
													new Dir(
																Cmd::readWithDefault(
																							'directory>',
																							$dir,
																							$log
																)
													)
					);


				}while(!$config->getSubsDirectory());

			}

			//Configure module templates directories

			public static function configureTemplatesDirectory(ModuleConfig &$config,LogInterface $log){

				do{

					$log->info('Please specify the templates directory for this module');

					$dir	=	clone($config->getDirectory());
					$dir->addPath('templates');

					$config->setTemplatesDirectory(
													new Dir(
																Cmd::readWithDefault(
																							'directory>',
																							$dir,
																							$log
																)
													)
					);


				}while(!$config->getTemplatesDirectory());

			}

			//Configure module fragments directories

			public static function configureFragmentsDirectory(ModuleConfig &$config,LogInterface $log){

				do{

					$log->info('Please specify the fragments directory for this module');

					$dir	=	$config->getFragmentsDirectory();

					if(!$dir){

						$dir	=	clone($config->getDirectory());
						$dir->addPath('fragments');

					}

					$config->setFragmentsDirectory(
													new Dir(
																Cmd::readWithDefault(
																							'directory>',
																							$dir,
																							$log
																)
													)
					);


				}while(!$config->getFragmentsDirectory());

			}

			//Add a sub to a module
			public static function addSubs(ModuleConfig &$config,LogInterface $log){

				do{

					$log->repeat('-',80,'white');

					$opt	=	Cmd::selectWithKeys(Array('N'=>'New sub','E'=>'End adding subs'),'>',$log);

					if(strtolower($opt)=='e'){

						break;

					}

					$subConfig	=	new SubConfig();
					$subConfig->setModule($module);
					$config->addSub(Sub::configure($subConfig,$log));

				}while(TRUE);

			}

			public static function configureModuleDirectories(ModuleConfig &$config,LogInterface $log){

				do{

					Cmd::clear();

					$log->info('Configure module directories');
					$log->repeat('-',80,'light_purple');

					$options	=	Array(
											'D'	=>	Array(
																'value'	=>	"Set/Change Root directory {$config->getDirectory()}",
																'color'	=>	$config->getDirectory()	?	'light_purple'	:	'light_cyan'
											),
											'T'	=>	Array(
																'value'	=>	"Set/Change templates directory {$config->getTemplatesDirectory()}",
																'color'	=>	$config->getTemplatesDirectory()	?	'light_purple'	:	'light_cyan'
											),
											'F'	=>	Array(
																'value'	=>	"Set/Change fragments directory {$config->getFragmentsDirectory()}",
																'color'	=>	$config->getFragmentsDirectory()	?	'light_purple'	:	'light_cyan'
											),
											'B'	=>	'Back'
					);

					$opt	=	Cmd::selectWithKeys($options,'>',$log);

					switch(strtolower($opt)){

						case 'd':
							self::configureModuleDirectory($config,$log);
						break;

						case 't':
							self::configureTemplatesDirectory($config,$log);
						break;

						case 'f':
							self::configureFragmentsDirectory($config,$log);
						break;

						case 'b':
							break 2;
						break;

					}

				}while(TRUE);

			}

			public static function configure($config=NULL,LogInterface $log){
				
				$config	=	new ModuleConfig($config);
				$project	=	$config->getProject();

				if(!$project){

					throw new \LogicException("Given module configuration has no assigned project");

				}

				if(!$project->getConfig()){

					throw new \LogicException("Passed project has not been properly configured");

				}


				do{

					Cmd::clear();
					$log->info('Module configuration');
					$log->repeat('-',80,'light_purple');

					$hasSubs	=	$config->hasSubs();

					$options	=	Array(
											'MN'	=>	"Set/Change module name {$config->getName()}",
											'MD'	=>	"Set/Edit module directories",
											'AS'	=>	"Add a new sub",
					);

					if($hasSubs){

						$options['LS']	=	'List subs';

					}

					$options['B']	=	'Back';

					$opt	=	Cmd::selectWithKeys($options,'>',$log);

					switch(strtolower($opt)){

						case 'mn':
							self::configureModuleName($config,$log);
						break;

						case 'md':
							self::configureModuleDirectories($config,$log);
						break;

						case 'as':
							self::addSubs($config,$log);
						break;

						case 'b':
							break 2;
						break;

					}

				}while(TRUE);

				return new Module($config,$validate='soft');

			}

		}

	}
