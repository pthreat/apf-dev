<?php

	namespace apf\web\core\controller\config{

		use \apf\core\Configurable;
		use \apf\web\core\Controller;
		use \apf\web\core\controller\Action;
		use \apf\web\core\controller\action\Config	as	ActionConfig;
		use \apf\web\core\controller\Config				as	ControllerConfig;
		use \apf\web\asset\config\Cli						as	AssetCli;
		use \apf\iface\Log									as	LogInterface;
		use \apf\iface\config\Cli							as	CliConfigInterface;

		use \apf\core\Cmd;

		class Cli implements CliConfigInterface{

			public static function configure(&$config=NULL, LogInterface &$log){

				$config	=	new ControllerConfig($config);

				$log->info('[ Controller configuration ]');

				do{

					try{

						$config->setName(Cmd::readInput('name>',$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getName());

				$controller	=	new Controller($config,$validate='soft');

				AssetCli::addAssetsToObject(
													$config,
													'Add project assets',
													'Add assets at a controller level. This means that every asset you add here will be present in each acton of this controller',
													$log
				);

				do{

					$log->info('Add actions to your controller.');

					$opt	=	Cmd::selectWithKeys(Array('N'=>'New Action','E'=>'End adding actions'),'>',$log);

					if(strtolower($opt)=='e'){

						break;

					}

					$actionConfig	=	new ActionConfig();
					$actionConfig->setController($controller);

					$config->addAction(Action::cliConfig($actionConfig,$log));

				}while(TRUE);

				return $controller;

			}

		}

	}
