<?php

	namespace apf\core{

		use \apf\db\connection\Config			as	DatabaseConfig;
		use \apf\core\project\Config			as	ProjectConfig;
		use \apf\core\project\Module			as	ProjectModule;
		use \apf\core\project\module\Config	as	ModuleConfig;
		use \apf\core\Cmd;

		class Project extends Configurable{

			public function create(){

				$this->validateConfig();

			}

			public function validateConfig(){
			}

			public function interactiveConfig(LogInterface $log,ProjectConfig $config=NULL){

				$config	=	new ProjectConfig();

				do{

					$config->setName(Cmd::readInput('Project name',$log));

				}while(empty($config->getName()));

				do{

					$log->info('Please specify the directory where the project should be created');

					$dir	=	realpath(getcwd());

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

				do{

					$log->info('Please specify where your modules will be created');
					$dir	=	$config->getDirectory()->addPath('modules');

					$config->setModulesDirectory(
															Cmd::readWithDefault(
																						'modules directory>',
																						new Dir($dir),
																						$log
															)
					);

				}while(!$config->getModulesDirectory());

				do{

					$log->info('Specify which custom modules shall be created. By default the frontend and the backend modules will be created');
					$opt	=	Cmd::select(Array('N'=>'New module','E'=>'End adding modules'),'>',$log);

					if(strtolower($opt)=='e'){
						break;
					}

					$config->addModule(ProjectModule::interactiveConfig($log));

				}while(TRUE);

				do{
				}while(!$config->getCommonFragmentsDirectory());

			}

		}

	}

