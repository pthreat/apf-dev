<?php

	namespace apf\core\project{

		use \apf\core\Project;
		use \apf\core\Config							as	Cfg;
		use \apf\core\Cmd;
		use \apf\core\Configurable;
		use \apf\iface\Log							as	LogInterface;
		use \apf\core\project\module\Config		as	ModuleConfig;
		use \apf\core\project\module\Sub;
		use \apf\core\Directory						as	Dir;

		class Module extends Configurable{

			private	$project	=	NULL;

			public function listSubs(){

				if(!$this->isValidated()){

					throw new \LogicException('Can not list subs without a valid directory for this module');

				}

				return $this->config->getDirectory()->getIterator();

			}

			protected static function __interactiveConfig($config,$log){
				
				$config	=	new ModuleConfig($config);
				$project	=	$config->getProject();

				if(!$project){

					throw new \LogicException("Given module configuration has no assigned project");

				}

				if(!$project->getConfig()){

					throw new \LogicException("Passed project has not been properly configured");

				}

				$projectConfig	=	$project->getConfig();

				do{

					$config->setName(Cmd::readInput('Module name:',$log));

				}while(!$config->getName());

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

				do{

					$log->info('Please specify the fragments directory for this module');

					$dir	=	clone($config->getDirectory());
					$dir->addPath('fragments');

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

				$module	=	new Module($config,$validate='soft');

				$log->info('Please specify which subs would you like to create for this module');

				do{

					$opt	=	Cmd::selectWithKeys(Array('N'=>'New sub','E'=>'End adding subs'),'>',$log);

					if(strtolower($opt)=='e'){

						break;

					}

					$subArguments	=	array_merge(Array('log'=>$log,'module'=>$module));

					$subConfig	=	new SubConfig();
					$subConfig->setModule($module);

					$config->addSub(Sub::interactiveConfig($subConfig,$log));

				}while(TRUE);

				return $module;

			}

		}

	}

