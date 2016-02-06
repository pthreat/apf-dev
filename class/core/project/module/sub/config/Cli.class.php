<?php

	namespace apf\core\project\module\sub\config{

		use \apf\core\Cmd;
		use \apf\core\Directory							as	Dir;
		use \apf\core\Configurable;
		use \apf\core\project\module\Config			as ModuleConfig;
		use \apf\core\project\Module;
		use \apf\core\project\module\sub\Config	as	SubConfig;
		use \apf\iface\Log								as	LogInterface;
		use \apf\iface\Crud								as	CrudInterface;
		use \apf\web\core\Controller					as	WebController;
		use \apf\web\core\controller\Config			as	ControllerConfig;
		use \apf\iface\config\Cli						as	CliConfigInterface;

		class Cli implements CliConfigInterface{

			public static function configureName(ProjectConfig &$config,LogInterface $log){

				Cmd::clear();

				$log->info('Configure sub name');
				$log->repeat('-',80,'light_purple');

				do{

					try{

						$config->setName(Cmd::readWithDefault('name>',$config->getName(),$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getName());

			}

			public static function addSubs(Module &$module,LogInterface $log){

				$config	=	$module->getConfig();

				do{

					Cmd::clear();

					$log->info('Module subs');
					$log->repeat('-','80','light_purple');

					$options	=	Array(
											'N'	=>	'New Sub'
					);

					$hasSubs	=	$config->hasSubs();

					if($hasSubs){

						self::listSubs($config,$log);

						$options['E']	=	'Edit subs';
						$options['L']	=	'List subs';

					}

					$options['B']	=	'Back';

					$opt	=	Cmd::selectWithKeys($options,'>',$log);

					switch(strtolower($opt)){

						case 'n':

							$moduleConfig	=	new SubConfig();
							$moduleConfig->setModule($module);
							self::configure($moduleConfig,$log);

						break;

						case 'e':

							if($hasSubs){

								self::editSubs($config,$log);

							}

						break;

						case 'l':

							if($hasSubs){

								self::listSubs($config,$log);
								Cmd::readInput('Press enter to continue ...');

							}

						break;

						case 'd':

							if($hasSubs){

								self::deleteSubs($config,$log);

							}

						break;

						case 'b':

							break 2;

						break;

					}

				}while(TRUE);

			}

			public function listSubs(ModuleConfig &$config, LogInterface $log){

				$subs	=	$config->getSubs();

				if(!$subs){

					$log->warning("No subs could be found for this module configuration");
					return;

				}

				foreach($subs as $sub){

					$log->info($sub);

				}

			}

			public static function configureDirectories(SubConfig &$config,LogInterface $log){

				do{

					Cmd::clear();

					$log->info('Configure sub (module) directories');
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


			public static function configureDirectory(SubConfig &$config,LogInterface $log){

				do{

					$log->info('Please specify the directory for this sub');

					$dir	=	$config->getModule()
					->getConfig()
					->getDirectory()
					->addPath($config->getName());

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

			}

			//Configure templates directories

			public static function configureTemplatesDirectory(SubConfig &$config,LogInterface $log){

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

			public static function configureFragmentsDirectory(SubConfig &$config,LogInterface $log){

				do{

					Cmd::clear();

					$log->info('Please specify the fragments directory for this sub');
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

			public static function addControllers(SubConfig &$config,LogInterface $log){
			}


			/**
			*Returns an interactively configured Sub (Module) class
			*/

			public static function configure($config=NULL,LogInterface $log){

				$log->success('Sub configuration');
				$log->repeat('-',80,'light_purple');

				$options	=	Array(
										'D'	=>	'Directories',
										'C'	=>	'Controllers',
										'A'	=>	'Assets',
										'B'	=>	'Back'
				);

				$config	=	new SubConfig($config);

				do{

					case 'd':
						self::configureDirectories($config,$log);
					break;

					case 'c':
						self::addControllers($config);
					break;

					case 'a':
					break;

					case 'b':
						break 2;
					break;

				}while(TRUE);

				return new Sub($config,$validate='soft');

			}

		}

	}

