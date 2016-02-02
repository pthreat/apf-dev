<?php

	namespace apf\web\core\controller{

		use \apf\core\Configurable;
		use \apf\web\core\Route;
		use \apf\web\core\controller\Config				as	ControllerConfig;
		use \apf\web\core\controller\action\Config	as	ActionConfig;
		use \apf\core\Cmd;

		class Action extends Configurable{

			public function __interactiveConfig($config,$log){

				$config	=	new ActionConfig($config);

				$log->info('[ Action configuration ]');

				do{

					try{

						$config->setName(Cmd::readInput('name>',$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getName());

				do{


				}while(TRUE);

				$controller	=	new Action($config,$validate='soft')

				do{

					$log->info('Add actions to your controller.');

					$opt	=	Cmd::selectWithKeys(Array('N'=>'New Action','E'=>'End adding actions'),'>',$log);

					if(strtolower($opt)=='e'){

						break;

					}

					$actionConfig	=	new ActionConfig();
					$actionConfig->setController($controller);

					$config->addAction(Action::interactiveConfig($actionConfig,$log));

				}while(TRUE);

				return $controller;

			}

		}

	}

