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

			public static function configureDirectories(ProjectConfig &$config,LogInterface $log){

				if(!$config->getName()){

					throw new \LogicException("Must configure project name first");

				}

				do{

					Cmd::clear();

					$log->info('Configure project directories');
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
											'M'	=>	Array(
																'value'	=>	"Set/Change modules directory {$config->getModulesDirectory()}",
																'color'	=>	$config->getModulesDirectory()	?	'light_purple'	:	'light_cyan'
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
							self::configureDirectory($config,$log);
						break;

						case 't':
							self::configureTemplatesDirectory($config,$log);
						break;

						case 'm':
							self::configureModulesDirectory($config,$log);
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

			public static function configureName(ProjectConfig &$config,LogInterface $log){

				Cmd::clear();

				$log->info('Configure project name');
				$log->repeat('-',80,'light_purple');

				do{

					try{

						$config->setName(Cmd::readWithDefault('name>',$config->getName(),$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getName());

			}

			//Delete modules from a project configuration
			public static function deleteModules(ProjectConfig &$config,LogInterface $log){

			}

			//List modules in project
			public static function listModules(ProjectConfig &$config,LogInterface $log){

				$modules	=	$config->getModules();

				if(!sizeof($modules)){

					$log->warning('Given project configuration has no modules assigned');

				}

				foreach($modules as $module){

					$log->info($module);

				}

			}

			//Configure root directory
			public static function configureDirectory(ProjectConfig $config,LogInterface $log){

				do{

					try{

						Cmd::clear();

						$log->info('Please specify the project main directory');

						$dir	=	$config->getDirectory();

						if(!$dir){

							$dir	=	new Dir(realpath(getcwd()));
							$dir->addPath($config->getName());

						}

						$config->setDirectory(
														new Dir(
																	Cmd::readWithDefault(
																								'directory>',
																								$dir,
																								$log
																	)
														)
						);

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getDirectory());

			}

			//Configure modules directory

			public static function configureModulesDirectory(ProjectConfig &$config,LogInterface $log){

				do{

					Cmd::clear();

					$log->info('Please specify the modules directory for this project');
					$log->repeat('-',80,'light_purple');

					$dir	=	$config->getModulesDirectory();

					if(!$dir){

						$dir	=	clone($config->getDirectory());
						$dir->addPath('modules');

					}

					$config->setModulesDirectory(
													new Dir(
																Cmd::readWithDefault(
																							'directory>',
																							$dir,
																							$log
																)
													)
					);


				}while(!$config->getModulesDirectory());

			}

			//Configure module templates directories

			public static function configureTemplatesDirectory(ProjectConfig &$config,LogInterface $log){

				do{

					Cmd::clear();
					$log->info('Please specify the templates directory for this project');
					$log->repeat('-',80,'light_purple');

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

			public static function configureFragmentsDirectory(ProjectConfig &$config,LogInterface $log){

				do{

					Cmd::clear();

					$log->info('Please specify the fragments directory for this project');
					$log->repeat('-',80,'light_purple');

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

			public static function configureDatabaseConnections(ProjectConfig $config, LogInterface $log){

					do{

						Cmd::clear();
						$log->debug('[ Network configuration ]');
						$log->repeat('-',80,'light_purple');

						$options	=	Array(
												'N'	=>	'New database connection'
						);

						$options['B']	=	'Back';

						Cmd::selectWithKeys($options,'>',$log);

						try{

							switch(strtolower($opt)){

								case 'db':
									self::configureDatabaseConnections($config,$log);
								break;

								case 'nc':
									self::configureNetworkConnections($config,$log);
								break;

							}

						}catch(\Exception $e){

							$log->error($e->getMessage());

						}

					}while(TRUE);

			}

			public static function configureConnections(ProjectConfig $config,LogInterface $log){

					do{

						Cmd::clear();
						$log->debug('[ Network configuration ]');
						$log->repeat('-',80,'light_purple');

						$options	=	Array(
												'DB'	=>	'Configure database connections',
												'NC'	=>	'Configure network connections',
												'B'	=>	'B'
						);

						Cmd::selectWithKeys($options,'>',$log);

						try{

							switch(strtolower($opt)){

								case 'db':
									self::configureDatabaseConnections($config,$log);
								break;

								case 'nc':
									self::configureNetworkConnections($config,$log);
								break;

							}

						}catch(\Exception $e){

							$log->error($e->getMessage());

						}

					}while(TRUE);


			}

			public static function configureProject($config,LogInterface $log){

				$title	=	$config	?	sprintf('Edit project %s',$config->getName())	:	'New project';
				$config	=	new ProjectConfig($config);

				do{

					Cmd::clear();

					$options	=	Array(
											'N'	=>	'Project name',
											'D'	=>	'Directories',
											'C'	=>	'Connections',
											'A'	=>	'Assets',
					);

					$enableModulesEntry	=	$config->getName()					&& 
													$config->getDirectory()				&& 
													$config->getModulesDirectory()	&& 
													$config->getTemplatesDirectory() &&
													$config->getFragmentsDirectory();

					$modulesColor			=	$enableModulesEntry	?	'yellow'	:	'gray';

					$options['M']	=	Array(
													'color'	=>	$modulesColor,
													'value'	=>	'Modules'
					);

					$options['F']	=	'Quit';

					try{

						Cmd::clear();

						$log->success("[ $title ]");
						$log->repeat('-','80','light_purple');
						$log->debug("Project {$config->getName()}>");
						$log->repeat('-','80','light_purple');

						$option	=	Cmd::selectWithKeys($options,'>',$log);

						switch(strtolower($option)){

							//Configure project name
							case 'n':
								self::configureName($config,$log);
							break;

							//Configure Assets
							case 'a':

									$help	=	'Add assets at a project level. This means that every asset you add here will be ';
									$help	=	sprintf('%s present in each controller or action',$help);

									AssetCli::assetConfiguration(
																		$config,
																		'Project assets',
																		$help,
																		$log
									);

							break;

							//Configure project directories
							case 'd':

								self::configureDirectories($config,$log);

							break;

							//Add modules to the project
							case 'm':

								if(!$enableModulesEntry){

									throw new \LogicException("You must configure project name and directories before adding any modules");

								}

								$project	=	new Project($config,$validateMode='soft');
								ModuleCli::addModules($project,$log);

							break;

							//Finish CLI configuration process
							case 'f':

								break 2;

							break;

							default:
								throw new \InvalidArgumentException("Invalid option selected");
							break;

						}

					}catch(\Exception $e){

						$log->error($e->getMessage());
						$log->debug($e->getTraceAsString());

						Cmd::readInput('Press enter to continue ...',$log);

					}

				}while(TRUE);

				$log->info("Select default module");
				$log->info("Select default sub");
				$log->info("Select default controller");
				$log->info("Select default action?");

				$log->success('Done configuring project');

				return $project;


			}

			public static function configure($config=NULL,LogInterface $log){

				$projectOptions	=	Array(
													'N'	=>	'Create project',
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

							case 'n':

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
