<?php

	namespace apf\core{

		use \apf\iface\Log						as	LogInterface;
		use \apf\core\project\Config;
		use \apf\core\project\Module			as	ProjectModule;
		use \apf\core\project\module\Config	as	ModuleConfig;
		use \apf\core\project\Config			as	ProjectConfig;
		use \apf\core\Cmd;
		use \apf\core\Directory					as	Dir;

		class Project extends Configurable{

			const CONFIG_DIRECTORY	=	'config';

			public function create(LogInterface $log){

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

			protected static function __softConfigValidation(Config $config){

				//Validate project name

				if(!$config->getName()){

					throw new \LogicException("The project name is invalid");

				}

				//Validate modules directory

				if(!$config->getModulesDirectory()){

					throw new \LogicException("The project modules directory has not been set");

				}

				if(!$config->getModulesDirectory()->exists()){

					throw new \LogicException("The project modules directory does not exists.");

				}

				//Validate project directory

				if(!$config->getDirectory()){

					throw new \LogicException("The project directory has not been set");

				}

				if(!$config->getDirectory()->exists()){

					throw new \LogicException("The project directory does not exists");

				}

				//Validate common fragments directory

				if(!$config->getCommonFragmentsDirectory()){

					throw new \LogicException("The project common fragments directory has not been set");

				}

				if(!$config->getCommonFragmentsDirectory()->exists()){

					throw new \LogicException("The project common fragments directory does not exists.");

				}

				//Validate templates directory

				if(!$config->getTemplatesDirectory()->exists()){

					throw new \LogicException("The project templates directory does not exists.");

				}

			}

			protected static function __hardConfigValidation(Config $config){

				if($config->getModulesDirectory()->isWritable()){

					throw new \LogicException("The project modules directory has not been set");

				}

			}

			protected static function __finalConfigValidation($config){
			}

			protected static function __interactiveConfig($config=NULL,$log=NULL){

				$log->success('[New project configuration]');

				$config	=	new ProjectConfig($config);

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

					$moduleConfig	=	new ModuleConfig();
					$moduleConfig->setProject($project);

					$config->addModule(ProjectModule::interactiveConfig($moduleConfig,$log));

				}while(TRUE);

				$log->success('Done configuring project');

				return $project;

			}

		}

	}

