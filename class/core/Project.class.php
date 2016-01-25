<?php

	namespace apf\core{

		use \apf\iface\Log						as	LogInterface;
		use \apf\core\project\Config			as	ProjectConfig;
		use \apf\core\project\Module			as	ProjectModule;
		use \apf\core\project\module\Config	as	ModuleConfig;
		use \apf\core\Config;
		use \apf\core\Cmd;
		use \apf\core\Directory					as	Dir;

		class Project extends Configurable{

			const CONFIG_DIRECTORY	=	'config';

			public function create(LogInterface $log){

				$this->validateConfig();

				$log->info("Creating project {$config->getName()}");
			
				$log->info('Creating project directory ...');
				$this->config->getDirectory()->create();

				$log->info('Creating modules directory ...');
				$this->config->getModulesDirectory()->create();

				$log->info('Creating modules ...');

				foreach($this->config->getModules() as $module){

					$module->create($log);

				}

				$log->success('Done creating project');

			}

			public function __validateConfig(){
			}

			protected static function __interactiveConfig(Array $arguments){

				$log	=	$arguments['log'];

				$log->success('[New project configuration]');

				$config	=	new ProjectConfig(is_null($config) ? Array() : $config->toArray());

				do{

					try{

						$config->setName(Cmd::readInput('Project name:',$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(empty($config->getName()));

				do{

					try{

						$log->info('Please specify the directory where the project should be created');

						$dir	=	new Dir(realpath(getcwd()));
						$dir->addPath($config->getName());

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

				do{

					try{

						$log->info('Please specify where your modules will be created');

						$dir	=	clone($config->getDirectory());
						$dir->addPath('modules');

						$config->setModulesDirectory(
																new Dir(
																			Cmd::readWithDefault(
																										'modules directory>',
																										$dir,
																										$log
																			)
																)
						);

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getModulesDirectory());

				do{

					try{

						$log->info('Specify a common templates directory');

						$dir	=	clone($config->getDirectory());
						$dir->addPath('resources')
						->addPath('templates');

						$config->setCommonTemplatesDirectory(
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

				}while(!$config->getCommonTemplatesDirectory());

				do{

					try{

						$log->info('Specify a common fragments directory');

						$dir	=	clone($config->getDirectory());

						$dir->addPath('resources')
						->addPath('fragments');

						$config->setCommonFragmentsDirectory(
																			new Dir(
																						Cmd::readWithDefault(
																													'directory>',
																													new Dir($dir),
																													$log
																						)
																			)
						);

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getCommonFragmentsDirectory());

				$project	=	new static($config);

				do{

					$log->info('Specify which custom modules shall be created. By default the frontend and the backend modules will be created');
					$opt	=	Cmd::selectWithKeys(Array('N'=>'New module','E'=>'End adding modules'),'>',$log);

					if(strtolower($opt)=='e'){

						break;

					}

					$config->addModule(ProjectModule::interactiveConfig(Array('log'=>$log,'project'=>$project)));

				}while(TRUE);

				$log->success('Done configuring project');

				return $project;

			}

		}

	}

