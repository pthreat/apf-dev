<?php

	namespace apf\core\project\config{

		use \apf\iface\Log										as	LogInterface;
		use \apf\core\project\DocumentRoot;
		use \apf\core\Cmd;
		use \apf\iface\config\Cli								as	CliConfigInterface;

		class Cli implements CliConfigInterface{

			use \apf\traits\config\cli\RootDirectory;

			public static function configure(&$config=NULL, LogInterface &$log){

																																	
				do{

					$options	=	Array(
												'R'	=>	Array(
																	'value'	=>	sprintf('%s root directory (%s)',$config->getDirectory() ? 'Change' : 'Set',$config->getDirectory(),
																	'color'	=>	$config->getDirectory()	?	'light_purple'	:	'light_cyan'
												)
												'H'	=>	'Help',
												'B'	=>	'Back'
					);

					Cmd::clear();

					$log->debug('-[ Document Root configuration ]-');
					$log->repeat('-',80,'light_purple');

					try{

						$option	=	Cmd::selectWithKeys($options,'document root>',$log);

						switch(strtolower($option)){

							case 'r':

								self::configureDirectory($config,$log);

							break;

							case 'h':

								$log->debug('Given configuration interface will allow you to configure a document root object');
								$log->debug('Press R to change the document root directory');
								$log->debug('Press B to exit this menu');

								Cmd::readInput('Press any key to continue ...');

							break;

							case 'b':

								break 2;

							break;

						}

					}catch(\Exception $e){

						$log->error($e->getMessage());
						Cmd::readInput('Press enter to continue ...',$log);

					}

				}while(TRUE);

			}

		}

	}
