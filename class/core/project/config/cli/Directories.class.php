<?php

	namespace apf\core\project\config\cli{

		use \apf\core\Cmd;
		use \apf\core\Directory					as	Dir;
		use \apf\iface\Log						as	LogInterface;	
		use \apf\iface\config\RootDirectory	as	RootDirectoryInterface;
		use \apf\iface\config\Templateable	as	TemplateableInterface;
		use \apf\iface\config\Subable			as	SubableInterface;
		use \apf\iface\config\Moduleable		as	ModuleableInterface;

		abstract class Directories{

			/**
			 *
			 * Configure the root directory for an object implementing the RootDirectory interface.
			 *
			 * @params \apf\iface\RootDirectoryInterface	An object implementing the root directory interface.
			 * @params \apf\iface\Log							An object implementing the log interface.
			 *
			 */

			public static function configureDirectory(RootDirectoryInterface &$config,LogInterface &$log){

				do{

					try{

						Cmd::clear();

						$log->info('[ Please specify the root directory ]');
						$log->repeat('-',80,'light_purple');
						$log->info('Press \'<\' to go back | Press \'!\' to reset this option');
						$log->repeat('-',80,'light_purple');

						$dir	=	$config->getDirectory();

						if($dir){

							$log->success("Current value: {$config->getDirectory()}");
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

							$config->unsetDirectory();
							continue;

						}

						$config->setDirectory(new Dir($opt));

					}catch(\Exception $e){

						$log->error($e->getMessage());
						Cmd::readInput('Press enter to continue ...');

					}

				}while(!$config->getDirectory());

				return TRUE;

			}

			/**
			 *
			 * Configure the templates directory for an object implementing the Templateable interface.
			 *
			 * @params \apf\iface\TemplateableInterface	An object implementing the templateable interface.
			 * @params \apf\iface\Log							An object implementing the log interface.
			 *
			 */

			public static function configureTemplatesDirectory(TemplateableInterface &$config,LogInterface &$log){

				return self::__configureDirectory($config,$log,"Configure templates directory","TemplatesDirectory","templates");

			}

			private static function __configureDirectory(&$config,LogInterface &$log,$title,$method,$defaultPath){

				$unset	=	sprintf('unset%s',ucwords($method));
				$getter	=	sprintf('get%s',ucwords($method));
				$setter	=	sprintf('set%s',ucwords($method));

				do{

					try{

						Cmd::clear();

						$log->debug("[ $title ]");
						$log->repeat('-',80,'light_purple');
						$log->info('Press \'<\' to go back | Press \'!\' to reset this value');
						$log->repeat('-',80,'light_purple');

						$dir	=	$config->$getter();

						if(!$dir){

							$dir	=	new Dir($config instanceof RootDirectoryInterface	?	$config->getDirectory()	:	realpath(getcwd()));
							$dir->addPath($defaultPath);

						}

						$opt	=	trim(Cmd::readWithDefault('>',$dir,$log));

						if($opt=='<'){

							return FALSE;

						}

						if($opt=='!'){

							$config->$unset();
							continue;

						}

						$config->$setter(new Dir($opt));

					}catch(\Exception $e){

						$log->error($e->getMessage());

						Cmd::readInput('Press enter to continue ...');

					}

				}while(!$config->$getter());

				return TRUE;

			}

			/**
			 *
			 * Configure the fragments directory for an object implementing the Templateable interface.
			 *
			 * @params \apf\iface\config\Templateable	An object implementing the templateable interface.
			 * @params \apf\iface\Log						An object implementing the log interface.
			 *
			 */

			public static function configureFragmentsDirectory(TemplateableInterface &$config,LogInterface &$log){

				return self::__configureDirectory($config,$log,"Configure fragments directory","FragmentsDirectory","fragments");

			}

			/**
			 *
			 * Configure the modules directory for an object implementing the Moduleable interface.
			 *
			 * @params \apf\iface\config\Moduleable	An object implementing the moduleable interface.
			 * @params \apf\iface\Log						An object implementing the log interface.
			 *
			 */

			public static function configureModulesDirectory(ModuleableInterface &$config,LogInterface &$log){

				return self::__configureDirectory($config,$log,"Configure modules directory","ModulesDirectory","modules");

			}

			/**
			 *
			 * Configure the subs directory for an object implementing the Moduleable interface.
			 *
			 * @params \apf\iface\config\Subable	An object implementing the subable interface.
			 * @params \apf\iface\Log					An object implementing the log interface.
			 *
			 */

			public static function configureSubsDirectory(SubableInterface &$config,LogInterface &$log){

				return self::__configureDirectory($config,$log,"Configure subs directory","SubsDirectory","subs");

			}

			public static function getDirectoriesMenu(&$config=NULL, Array $menu=Array()){

				$options	=	Array();

				if($config instanceof RootDirectoryInterface){

					$options['R']	=	Array(
													'value'	=>	sprintf('%s root directory (%s)',$config->getDirectory() ? 'Change' : 'Set',$config->getDirectory()),
													'color'	=>	$config->getDirectory() ? 'light_purple'	:	'light_cyan'
					);

				}

				if($config instanceof TemplateableInterface){

					$options['T']	=	Array(
													'value'	=>	sprintf('%s templates directory (%s)',$config->getTemplatesDirectory() ? 'Change' : 'Set',$config->getTemplatesDirectory()),
													'color'	=>	$config->getTemplatesDirectory() ? 'light_purple'	:	'light_cyan'
					);

					$options['F']	=	Array(
													'value'	=>	sprintf('%s fragments directory (%s)',$config->getFragmentsDirectory() ? 'Change' : 'Set',$config->getFragmentsDirectory()),
													'color'	=>	$config->getFragmentsDirectory() ? 'light_purple'	:	'light_cyan'
					);

				}

				if($config instanceof ModuleableInterface){

					$options['M']	=	Array(
													'value'	=>	sprintf('%s modules directory (%s)',$config->getModulesDirectory() ? 'Change' : 'Set',$config->getModulesDirectory()),
													'color'	=>	$config->getModulesDirectory() ? 'light_purple'	:	'light_cyan'
					);

				}

				if($config instanceof SubableInterface){

					$options['S']	=	Array(
													'value'	=>	sprintf('%s subs directory (%s)',$config->getSubsDirectory() ? 'Change' : 'Set',$config->getSubsDirectory()),
													'color'	=>	$config->getSubsDirectory() ? 'light_purple'	:	'light_cyan'
					);

				}

				return sizeof($menu) ? array_merge($options,$menu)	:	$options;

			}

			public static function switchDirectoriesOption($opt,&$config,LogInterface &$log){

				$opt	=	strtolower($opt);

				if($config instanceof RootDirectoryInterface && $opt == 'r'){

					return self::configureDirectory($config,$log);

				}

				if($config instanceof TemplateableInterface){

					switch($opt){

						case 't':
							return self::configureTemplatesDirectory($config,$log);
						break;

						case 'f':
							return self::configureFragmentsDirectory($config,$log);
						break;

					}

				}

				if($config instanceof ModuleableInterface && $opt=='m'){

					return self::configureModulesDirectory($config,$log);

				}

			}

		}

	}
