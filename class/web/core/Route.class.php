<?php

	namespace apf\web\core{

		class Route extends apf\core\Configurable{

			public function __interactiveConfig($config,$log){

				$config	=	new RouteConfig($config);
				$log->info('[ Route configuration ]');

				do{

					$config->setName(Cmd::readInput('name>',$log));

				}while !$config->getName();


			}

		}

	}
