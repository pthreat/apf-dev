<?php 

	namespace apf\traits\config\cli\module{

		use \apf\iface\config\module\Directories	as	ModuleDirectoriesInterface;
		use \apf\iface\Log								as	LogInterface;

		trait Directories{

			/**
			 *
			 * Configure the root directory for an object implementing the RootDirectory interface.
			 *
			 * @params \apf\iface\RootDirectoryInterface	An object implementing the root directory interface.
			 * @params \apf\iface\Log							An object implementing the log interface.
			 *
			 */

			public static function configureModuleDirectories(ModuleDirectoriesInterface &$config,LogInterface &$log){

				do{

					try{

						Cmd::clear();

						$log->info('[ Please specify the modules directory ]');
						$log->repeat('-',80,'light_purple');
						$log->info('Press \'<\' to go back | Press \'!\' to reset this option');
						$log->repeat('-',80,'light_purple');

						$dir	=	$config->getModulesDirectory();

						if($dir){

							$log->success("Current value: {$config->getModulesDirectory()}");
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

							$config->unsetModulesDirectory();
							continue;

						}

						$config->setModulesDirectory(new Dir($opt));

					}catch(\Exception $e){

						$log->error($e->getMessage());
						Cmd::readInput('Press enter to continue ...');

					}

				}while(!$config->getModulesDirectory());

				return TRUE;

			}

		}

	}

