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

			/**
			*Returns an interactively configured Sub (Module) class
			*/

			public static function configure($config=NULL,LogInterface $log){

				$log->success('[Sub configuration]');

				$config	=	new SubConfig($config);

				do{

					try{

						$config->setName(Cmd::readInput('name>',$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getName());

				$log->repeat('-',60,'white');

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

				do{

					$log->info('Specify which controllers shall be created.');
					$opt	=	Cmd::selectWithKeys(Array('N'=>'New controller','E'=>'End adding controllers'),'>',$log);

					if(strtolower($opt)=='e'){

						break;

					}

					$controllerConfig	=	new ControllerConfig();
					$config->addController(WebController::cliConfig($controllerConfig,$log));

				}while(TRUE);

				return new Sub($config,$validate='soft');

			}

		}

	}

