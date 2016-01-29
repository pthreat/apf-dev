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

			public function setProject(Project $project){

				if(!$project->getConfig()->isValidated()){

					throw new \InvalidArgumentException("A properly configured project is needed");

				}

				$this->project	=	$project;

				return $this;

			}


			public function getProject(){

				return parent::getProject();

			}

			public function __validateConfig(){
			}

			public function listSubs(){

				if(!$this->config->getDirectory()){

					throw new \LogicException('Can not list subs without a valid directory for this module');

				}

				return $this->config->getDirectory()->getIterator();

			}

			protected static function __interactiveConfig(ModuleConfig $config=NULL,LogInterface $log=NULL){

				$config			=	$config	?	$config	:	new ModuleConfig();
				$projectConfig	=	$config->getProjectConfig();

				do{

					$config->setName(Cmd::readInput('Module name:',$log));

				}while(empty($config->getName()));

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

				$module	=	new Module($config);

				$log->info('Please specify which subs would you like to create for this module');

				do{

					$opt	=	Cmd::selectWithKeys(Array('N'=>'New sub','E'=>'End adding subs'),'>',$log);

					if(strtolower($opt)=='e'){

						break;

					}

					$subArguments	=	array_merge(Array('log'=>$log,'module'=>$module));

					$config->addSub(Sub::interactiveConfig($subArguments));

				}while(TRUE);

			}

		}

	}

