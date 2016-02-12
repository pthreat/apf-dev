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


			/**
			 * Configure project directories.
			 * This interactive menu will allow the end user to configure several project directories.
			 *
			 * A) Configure the Project Root directory, this is the base directory where the project will be located.
			 * B) Configure the DocumentRoot directory, this is the directory that your HTTP server will use as the DocumentRoot
			 * C) Configure the Templates directory, this is the directory where global templates will be stored.
			 * D) Configure the fragments directory, this is the directory where global fragments will be stored.
			 *
			 * @params \apf\core\project\Config	A project configuration object
			 * @params \apf\iface\Log           A log interface so we can display messages and prompts in the command line.
			 *
			 */

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

			/**
			 * Configure your project name.
			 * Your project must have a configured name for it to make sense, you can call it whatever you want :)
			 *
			 * @params \apf\core\project\Config	A project configuration object
			 * @params \apf\iface\Log           A log interface so we can display messages and prompts in the command line.
			 * 
			 */

			public static function configureName(ProjectConfig &$config,LogInterface $log){

				do{

					Cmd::clear();

					$log->info('Configure project name');
					$log->repeat('-',80,'light_purple');

					try{

						$config->setName(Cmd::readWithDefault('name>',$config->getName(),$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());
						Cmd::readInput('Press enter to continue ...');

					}

				}while(!$config->getName());

			}

			//Delete modules from a project configuration
			public static function deleteModules(ProjectConfig &$config,LogInterface $log){

			}

			/**
			 *
			 * List modules that belong to this project.
			 * A project is break down into modules and subs.
			 *
			 * This method allows the end user to view which modules does his project has.
			 *
			 * @params \apf\core\project\Config	A project configuration object
			 * @params \apf\iface\Log           A log interface so we can display messages and prompts in the command line.
			 *
			 */
			
			public static function listModules(ProjectConfig &$config,LogInterface $log){

				$modules	=	$config->getModules();

				if(!sizeof($modules)){

					$log->warning('Given project configuration has no modules assigned');

				}

				foreach($modules as $module){

					$log->info($module);

				}

			}

			/**
			 *
			 * Configure the project root directory, this is the directory where the project will live.
			 *
			 * @params \apf\core\project\Config	A project configuration object
			 * @params \apf\iface\Log           A log interface so we can display messages and prompts in the command line.
			 *
			 */

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

			/**
			 *
			 * Configure the modules  directory, this is the directory where the project modules will live.
			 * It is a good idea that the modules reside inside the same directory the project is in.
			 *
			 * However the end user can choose any other directory of their preference, even outside of the project.
			 *	This could be a good idea when you have two projects sharing the same modules.
			 *
			 * @params \apf\core\project\Config	A project configuration object
			 * @params \apf\iface\Log           A log interface so we can display messages and prompts in the command line.
			 *
			 */

			public static function configureModulesDirectory(ProjectConfig &$config,LogInterface $log){

				do{

					Cmd::clear();

					$log->info('[ Please specify the modules directory for this project ]');
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

			public static function configureDatabaseConnection(ProjectConfig $config,LogInterface $log,DatabaseConnection $connection=NULL){

				do{

					Cmd::clear();

					$title	=	$connection===NULL	?	'New database connection'	:	'Edit database connection';

					$log->debug("[ $title ]");
					$log->repeat('-',80,'light_purple');

					$options	=	Array(
											'A'	=>	'Select adapter',
											'S'	=>	'Set change',
											'U'	=>	'Set username',
											'K'	=>	'Set password',
											'P'	=>	'Set port',
					);

					$hasConnections	=	$config->hasDatabaseConnections();

					if($hasDatabaseConnections){

						$options['E']	=	'Edit connections';
						$options['D']	=	'Delete connections';

					}

					$options['H']	=	'Help';
					$options['B']	=	'Back';


					try{

						$opt	=	Cmd::selectWithKeys($options,'>',$log);

						switch(strtolower($opt)){

							case 'n':
								$config->addDatabaseConnection(self::configureDatabaseConnection($config,$log));
							break;

							case 'e':
							break;

							case 'd':
							break;

							case 'h':

								$log->debug('In this menu you will be able to add/edit database connections for your project.');
								$log->debug('Press N to configure a new database connection');
								$log->debug('Press E to edit a database connection');
								$log->debug('Press D to delete a database connection');

								Cmd::readInput('Press enter to continue ...');

							break;

							case 'b':
								break 2;
							break;

						}

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(TRUE);

			}

			public static function configureDatabaseConnections(ProjectConfig $config, LogInterface $log){

					do{

						Cmd::clear();
						$log->debug('[ Database connections ]');
						$log->repeat('-',80,'light_purple');

						$options	=	Array(
												'N'	=>	'New connection'
						);

						$hasDatabaseConnections	=	$config->hasConnectionsOfType('database');

						if($hasDatabaseConnections){

							$options['L']	=	'List connections';
							$options['E']	=	'Edit connection';
							$options['D']	=	'Delete connections';

						}

						$options['H']	=	'Help';
						$options['B']	=	'Back';

						try{

							$opt	=	Cmd::selectWithKeys($options,'>',$log);

							switch(strtolower($opt)){

								case 'n':
									$config->addDatabaseConnection(self::configureDatabaseConnection($config,$log));
								break;

								case 'e':
								break;

								case 'd':
								break;

								case 'h':

									$log->debug('In this menu you will be able to add/edit database connections for your project.');
									$log->debug('Database connections hold a database adapter object inside them.');
									$log->debug('This means you will have to also configure the adapter parameters for said connection.');

									$log->debug('Press N to configure a new database adapter');
									$log->debug('Press E to edit a database adapter');
									$log->debug('Press D to delete a database adapter');

									Cmd::readInput('Press enter to continue ...');

								break;

								case 'b':
									break 2;
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
												'DB'	=>	'Database connections',
												'NC'	=>	'Network connections',
												'B'	=>	'Back'
						);


						try{

							$opt	=	Cmd::selectWithKeys($options,'>',$log);

							switch(strtolower($opt)){

								case 'db':
									self::configureDatabaseConnections($config,$log);
								break;

								case 'nc':
									self::configureNetworkConnections($config,$log);
								break;

								case 'b':
									break 2;
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

					$options['S']	=	'Save';
					$options['B']	=	'Back';

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

							case 'c':
								self::configureConnections($config,$log);
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
							case 'b':

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

				return; 

			}

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
