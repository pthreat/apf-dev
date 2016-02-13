<?php

	namespace apf\traits\config\cli{

		use \apf\core\Cmd;
		use \apf\core\Directory					as	Dir;
		use \apf\iface\Log						as	LogInterface;
		use \apf\iface\config\Templateable	as	TemplateableInterface;

		trait Templateable{

			public static function configureTemplatesDirectory(TemplateableInterface &$config,LogInterface &$log){

				do{

					Cmd::clear();

					$log->info('Please specify the templates directory');
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

			public static function configureFragmentsDirectory(TemplateableInterface &$config,LogInterface &$log){

				do{

					Cmd::clear();

					$log->info('Please specify the fragments directory');
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

		}

	}
