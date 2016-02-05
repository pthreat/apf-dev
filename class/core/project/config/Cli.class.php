<?php

	namespace apf\core\project\config{

		use \apf\iface\Log						as	LogInterface;
		use \apf\core\project\Config;
		use \apf\core\Project;
		use \apf\core\project\Module			as	ProjectModule;
		use \apf\core\project\module\Config	as	ModuleConfig;
		use \apf\core\project\Config			as	ProjectConfig;

		use \apf\web\asset\config\Cli			as	AssetCli;

		use \apf\web\asset\Javascript			as	JSAsset;
		use \apf\web\asset\Css					as	CSSAsset;

		use \apf\web\Asset;

		use \apf\core\Cmd;
		use \apf\core\Directory					as	Dir;

		use \apf\iface\config\Cli				as	CliConfigInterface;

		class Cli implements CliConfigInterface{

			public static function configure($config=NULL,LogInterface $log){

				$log->success('[New project configuration]');

				$config	=	new ProjectConfig($config);

				do{

					try{

						$config->setName(Cmd::readInput('Project name:',$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getName());

				do{

					try{

						$log->info('Please specify the project directory');

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

						$config->setTemplatesDirectory(
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

				}while(!$config->getTemplatesDirectory());

				do{

					try{

						$log->info('Specify a common fragments directory');

						$dir	=	clone($config->getDirectory());

						$dir->addPath('resources')
						->addPath('fragments');

						$config->setFragmentsDirectory(
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

				}while(!$config->getFragmentsDirectory());

				$project	=	new Project($config,$validateMode='soft');


				$options	=	Array(
										'A'	=>	'Add assets',
										'M'	=>	'Create module',
										'F'	=>	'Finish'
				);

				$options	=	Cmd::selectWithKeys($options,'>',$log);

				do{

					switch(strtolower($options)){

						case 'a':

								$help	=	'Add assets at a project level. This means that every asset you add here will be ';
								$help	=	sprintf('%s present in each controller or action',$help);

								AssetCli::addAssetsToObject(
																	$config,
																	'Add project assets',
																	$help,
																	$log
								);

						break;

						case 'm':

							do{

								$log->info('Specify which modules will be created');
								$opt	=	Cmd::selectWithKeys(Array('N'=>'New module','E'=>'End adding modules'),'>',$log);

								if(strtolower($opt)=='e'){

									break;

								}

								$moduleConfig	=	new ModuleConfig();
								$moduleConfig->setProject($project);

								$config->addModule(ProjectModule::cliConfig($moduleConfig,$log));

							}while(TRUE);

						break;

						case 'f':

							break 2;

						break;

					}

				}while(TRUE);

				$log->info("Select default module");
				$log->info("Select default sub");
				$log->info("Select default controller");
				$log->info("Select default action?");

				$log->success('Done configuring project');

				return $project;

			}

		}

	}
