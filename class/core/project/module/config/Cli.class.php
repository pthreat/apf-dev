<?php

	namespace apf\core\project\module\config{

		use \apf\core\Cmd;
		use \apf\iface\config\Cli						as	CliConfigInterface;
		use \apf\core\Project;
		use \apf\core\project\Module;
		use \apf\iface\Log								as	LogInterface;
		use \apf\core\project\module\Config			as	ModuleConfig;
		use \apf\core\project\module\Sub;
		use \apf\core\project\module\sub\Config	as	SubConfig;
		use \apf\core\Directory							as	Dir;

		class Cli implements CliConfigInterface{

			public static function configure($config=NULL,LogInterface $log){
				
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

					try{

						$config->setName(Cmd::readInput('Module name:',$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

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

					$log->repeat('-',80,'white');

					$opt	=	Cmd::selectWithKeys(Array('N'=>'New sub','E'=>'End adding subs'),'>',$log);

					if(strtolower($opt)=='e'){

						break;

					}

					$subConfig	=	new SubConfig();
					$subConfig->setModule($module);
					
					$config->addSub(Sub::cliConfig($subConfig,$log));

				}while(TRUE);

				return $module;

			}

		}

	}
