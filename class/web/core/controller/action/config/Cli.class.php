<?php

	namespace apf\web\core\controller\action\config{

		use \apf\core\Cmd;
		use \apf\web\core\Route;
		use \apf\web\core\controller\Action;
		use \apf\web\core\route\config\Cli				as	RouteCli;
		use \apf\web\core\controller\Config				as	ControllerConfig;
		use \apf\web\core\controller\action\Config	as	ActionConfig;
		use \apf\iface\config\Cli							as	CliConfigInterface;
		use \apf\iface\Log									as	LogInterface;

		class Cli implements CliConfigInterface{

			public static function configure($config=NULL,LogInterface $log){

				$config	=	new ActionConfig($config);

				$log->info('[ Action configuration ]');

				do{

					try{

						$config->setName(Cmd::readInput('name>',$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getName());

				$action	=	new Action($config,$validate='soft');

				$help		=	'Add routes at an action level. Though is not recommended, an action can have many routes.';

				RouteCli::addRoutesToObject($config,$log,'Add routes to action',$help);

				return $action;

			}

		}

	}

