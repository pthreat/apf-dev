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

						$dir		=	$config->getRootDirectory();
						$hasDir	=	(boolean)$dir;

						if($hasDir){

							$log->success("Current value: {$config->getRootDirectory()}");
							$log->repeat('-',80,'light_purple');

						}

						$options	=	Array(
												'C'	=>	Array(
																	'value'	=>	sprintf('%s directory (%s)',$hasDir ? 'Change' : 'Set',$dir),
																	'color'	=>	$hasDir	?	'light_purple'	:	'light_cyan'
												)
						);

						if($hasDir){

							$options['R']	=	Array(
															'value'	=>	'Reset value',
															'color'	=>	'yellow'
							);

						}

						$options['B']	=	'Back';
					
						$opt	=	Cmd::selectWithKeys($options,'>',$log);

						switch(strtolower($opt)){

							case 'r':
								$config->unsetRootDirectory();
							break;

							case 'c':

								$config->setRootDirectory(
																	new Dir(
																				Cmd::readWithDefault(
																											'>',
																											$config->getRootDirectory(),
																											$log
																				)
																	)
								);

							break;

							case 'b':
								break 2;
							break;

						}

					}catch(\Exception $e){

						$log->error($e->getMessage());
						Cmd::readInput('Press enter to continue ...');

					}

				}while(TRUE);

				return TRUE;

			}

		}

	}

