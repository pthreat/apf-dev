<?php

	namespace apf\web\core\controller\action\config{

		use \apf\web\core\Route;
		use \apf\iface\config\Cli							as	CliConfigInterface;
		use \apf\iface\Log									as	LogInterface;
		use \apf\web\core\controller\Config				as	ControllerConfig;
		use \apf\web\core\controller\action\Config	as	ActionConfig;
		use \apf\core\Cmd;

		class Cli implements CliConfigInterface{

			public function configure($config=NULL,LogInterface $log){

				$config	=	new ActionConfig($config);

				$log->info('[ Action configuration ]');

				do{

					try{

						$config->setName(Cmd::readInput('name>',$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getName());

				$action	=	new Action($config,$validate='soft')

				do{

					$log->info('Add a route to your action.');

					$opt	=	Cmd::selectWithKeys(Array('N'=>'New Route','E'=>'End adding actions'),'>',$log);

					if(strtolower($opt)=='e'){

						break;

					}

					$actionConfig	=	new ActionConfig();
					$actionConfig->setController($controller);

					$config->addAction(Action::interactiveConfig($actionConfig,$log));

				}while(TRUE);

				return $action;

			}

		}

	}

