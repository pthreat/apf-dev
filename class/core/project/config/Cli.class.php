<?php

	namespace apf\core\project\config{

		use \apf\iface\Log										as	LogInterface;

		use \apf\core\Project;
		use \apf\core\project\Config;

		use \apf\core\project\Directories					as	ProjectDirectories;
		use \apf\core\project\directories\Config			as	ProjectDirectoriesConfig;

		use \apf\core\project\Module							as	Module;
		use \apf\core\project\module\Config					as	ModuleConfig;
		use \apf\core\project\module\config\Cli			as	ModuleCli;
		use \apf\core\project\Config							as	ProjectConfig;
		use \apf\db\connection\config\Cli					as	DatabaseConnectionCliConfig;
		use \apf\web\asset\config\Cli							as	AssetCli;

		use \apf\web\asset\Javascript							as	JSAsset;
		use \apf\web\asset\Css									as	CSSAsset;

		use \apf\web\Asset;

		use \apf\core\Cmd;
		use \apf\core\Directory									as	Dir;

		use \apf\iface\config\Cli								as	CliConfigInterface;

		class Cli implements CliConfigInterface{

			use \apf\traits\config\cli\Nameable;

			/**
			 * CRUD for project modules
			 */

			public static function configureModules(Project &$project,LogInterface &$log){

				$projectConfig	=	$project->getConfig();

				do{

					Cmd::clear();

					$log->debug('[ Project modules ]');
					$log->repeat('-','80','light_purple');

					$options	=	Array(
											'N'	=>	'New Module'
					);

					$hasModules	=	$projectConfig->hasModules();

					if($hasModules){

						self::listModules($projectConfig,$log);

						$options['C']	=	'Copy module';
						$options['E']	=	'Edit modules';
						$options['L']	=	'List modules';

					}

					$options['B']	=	'Back';

					$opt	=	Cmd::selectWithKeys($options,'>',$log);

					switch(strtolower($opt)){

						case 'n':

							$moduleConfig	=	new ModuleConfig();
							$moduleConfig->setProject($project);

							$module			=	Module::cliConfig($moduleConfig,$log);

							if($module){

								$config->addModule($module);

							}

						break;

						case 'e':

							if($hasModules){

								self::editModules($projectConfig,$log);

							}

						break;

						case 'c':

							$module	=	self::copyModule($config,$log);

							if($module){

								$config->addModule($copy);

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

			public function copyModule(ProjectConfig &$config, LogInterface &$log){

			}

			//Delete modules from a project configuration
			public static function deleteModules(ProjectConfig &$config,LogInterface $log){

			}

			/**
			 *
			 * List modules that belong to a project.
			 *
			 * This method allows the end user to view which modules does the project has assigned.
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

			public static function configureDatabaseConnections(ProjectConfig $config, LogInterface $log){

					do{

						Cmd::clear();
						$log->debug("[ Configure \"{$config->getName()}'s\" database connections ]");
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

									$connection	=	DatabaseConnectionCliConfig::configure($cfg=NULL,$log);

									if($connection){

										$config->addConnection($connection);

									}

								break;

								case 'e':
								break;

								case 'd':
								break;

								case 'h':

									$log->debug('In this menu you will be able to add/edit database connections for your project.');
									$log->debug('Database connections hold a database adapter object inside them.');
									$log->debug('This means you will have to also configure the adapter parameters for said connection.');

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

			public static function configureProject(&$config,LogInterface &$log){

				$title	=	$config	?	sprintf('Edit project < %s >',$config->getName())	:	'New project';
				$config	=	new ProjectConfig($config);
				$project	=	new Project($config,$validate='none');

				do{

					Cmd::clear();

					$hasName	=	$config->getName();

					$directoriesConfigured	=	$config->getDirectories()&&$config->getDirectories()->isValidated();

					$options	=	Array(
											'N'	=>	Array(
																'value'	=>	sprintf('Project name (%s)',$hasName	?	$config->getName()	:	''),
																'color'	=>	$hasName	?	'light_green'	:	'yellow'
											),
											'D'	=>	Array(
																'value'	=>	sprintf('Directories %s',$directoriesConfigured	?	'(configured)' : $hasName	?	''	:	'(Set project name first)'),
																'color'	=> $directoriesConfigured	? 'light_green'	:	($hasName	?	'yellow'	:	'gray')
											),
											'C'	=>	'Connections',
											'A'	=>	'Assets',
					);


					$enableModulesEntry	=	$hasName	&& $config->getDirectories();

					$modulesColor			=	$enableModulesEntry	?	'yellow'	:	'gray';

					$options['M']	=	Array(
													'color'	=>	$modulesColor,
													'value'	=>	$enableModulesEntry ? 'Modules' : 'Modules (configure name and directories first)'
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

								if(!$hasName){

									throw new \LogicException("You must configure the project name before configuring directories");

								}

								$projectDirectoriesConfig	=	$config->getDirectories()	?	
								$config->getDirectories()->getConfig()	:	new ProjectDirectoriesConfig($noConfig=NULL);

								$projectDirectoriesConfig->setProject($project);

								$projectDirectoriesConfig	=	ProjectDirectories::cliConfig($projectDirectoriesConfig,$log);

								if($projectDirectoriesConfig){

									$config->setDirectories($projectDirectoriesConfig);

								}

							break;

							//Configure connections
							case 'c':

								self::configureConnections($config,$log);

							break;

							//Add modules to the project
							case 'm':

								if(!$enableModulesEntry){

									throw new \LogicException("You must configure project name and directories before adding any modules");

								}

								self::configureModules($project,$log);

							break;

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
