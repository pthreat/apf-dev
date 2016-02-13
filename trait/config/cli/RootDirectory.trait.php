<?php

	namespace apf\traits\config\cli{

		use \apf\core\Cmd;
		use \apf\core\Directory					as	Dir;
		use \apf\iface\Log						as	LogInterface;
		use \apf\iface\config\RootDirectory	as	RootDirectoryInterface;
		use \apf\iface\config\Nameable		as	NameableInterface;

		trait RootDirectory{

			/**
			 *
			 * Configure the root directory for an object implementing the RootDirectory interface.
			 *
			 * @params \apf\core\project\Config	A project configuration object
			 * @params \apf\iface\Log           A log interface so we can display messages and prompts in the command line.
			 *
			 */

			public static function configureDirectory(RootDirectoryInterface &$config,LogInterface &$log){

				do{

					try{

						Cmd::clear();

						$log->info('Please specify the root directory');
						$log->repeat('-',80,'light_purple');

						$dir	=	$config->getDirectory();

						if(!$dir){

							$dir	=	new Dir(realpath(getcwd()));

							if($config instanceof NameableInterface){

								$dir->addPath($config->getName());

							}

						}

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

			}

		}
		
	}

