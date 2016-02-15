<?php 

	namespace apf\traits\config\cli\fragment{

		use \apf\iface\config\fragment\Directories	as	FragmentDirectoriesInterface;
		use \apf\iface\Log									as	LogInterface;

		trait Directories{

			/**
			 *
			 * Configure the root directory for an object implementing the RootDirectory interface.
			 *
			 * @params \apf\iface\RootDirectoryInterface	An object implementing the root directory interface.
			 * @params \apf\iface\Log							An object implementing the log interface.
			 *
			 */

			public static function configureFragmentDirectories(FragmentDirectoriesInterface &$config,LogInterface &$log){

				do{

					try{

						Cmd::clear();

						$log->info('[ Please specify the fragments directory ]');
						$log->repeat('-',80,'light_purple');
						$log->info('Press \'<\' to go back | Press \'!\' to reset this option');
						$log->repeat('-',80,'light_purple');

						$dir	=	$config->getFragmentsDirectory();

						if($dir){

							$log->success("Current value: {$config->getFragmentsDirectory()}");
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

							$config->unsetFragmentsDirectory();
							continue;

						}

						$config->setFragmentsDirectory(new Dir($opt));

					}catch(\Exception $e){

						$log->error($e->getMessage());
						Cmd::readInput('Press enter to continue ...');

					}

				}while(!$config->getFragmentsDirectory());

				return TRUE;

			}

		}

	}

