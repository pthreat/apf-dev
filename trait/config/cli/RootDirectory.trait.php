<?php 

	namespace apf\traits\config\cli{

		use \apf\core\Cmd;
		use \apf\core\Directory					as	Dir;
		use \apf\iface\config\RootDirectory	as	RootDirectoryInterface;
		use \apf\iface\Log						as	LogInterface;

		trait RootDirectory{

			/**
			 *
			 * Configure the root directory for an object implementing the RootDirectory interface.
			 *
			 * @params \apf\iface\RootDirectoryInterface	An object implementing the root directory interface.
			 * @params \apf\iface\Log							An object implementing the log interface.
			 *
			 */

			public static function configureRootDirectory(RootDirectoryInterface &$config,LogInterface &$log){

				do{

					try{

						Cmd::clear();

						$log->info('[ Please specify the root directory ]');
						$log->repeat('-',80,'light_purple');
						$log->info('Press \'<\' to go back | Press \'!\' to reset this option');
						$log->repeat('-',80,'light_purple');

						$dir	=	$config->getRootDirectory();

						if($dir){

							$log->success("Current value: {$config->getRootDirectory()}");
							$log->repeat('-',80,'light_purple');

						}

						if(!$dir){

							$dir	=	new Dir(realpath(getcwd()));

							if($config instanceof NameableInterface){

								$dir->addPath($config->getName());

							}

						}

						$opt	=	trim(Cmd::readWithDefault('>',$dir,$log));

						if($opt=='<'){

							return;

						}

						if($opt=='!'){

							$config->unsetRootDirectory();
							continue;

						}

						$config->setRootDirectory(new Dir($opt));

					}catch(\Exception $e){

						$log->error($e->getMessage());
						Cmd::readInput('Press enter to continue ...');

					}

				}while(TRUE);

				return TRUE;

			}

		}

	}

