<?php

	namespace apf\core\project\module\config{

		use \apf\core\Cmd;
		use \apf\iface\config\Cli						as	CliConfigInterface;
		use \apf\core\Project;
		use \apf\core\project\Config					as	ProjectConfig;
		use \apf\core\project\Module;
		use \apf\iface\Log								as	LogInterface;
		use \apf\core\project\module\Config			as	ModuleConfig;
		use \apf\core\project\module\config\Cli	as	ModuleCli;
		use \apf\core\project\module\Sub;
		use \apf\core\project\module\sub\Config	as	SubConfig;
		use \apf\core\Directory							as	Dir;

		class Cli implements CliConfigInterface{

			use \apf\traits\config\cli\Nameable;
			use \apf\traits\config\cli\Templateable;

			public static function configureSubsDirectory(ModuleConfig &$config,LogInterface $log){

				do{

					$log->info('Please specify the subs directory for this module');

					$dir	=	clone($config->getDirectory());
					$dir->addPath('subs');

					$config->setSubsDirectory(
													new Dir(
																Cmd::readWithDefault(
																							'directory>',
																							$dir,
																							$log
																)
													)
					);


				}while(!$config->getSubsDirectory());

			}

			/**
			 *Subs CRUD for a module configuration object
			 */

			public static function configureSubs(ModuleConfig &$config,LogInterface $log){

				do{

					$log->repeat('-',80,'white');

					$opt	=	Cmd::selectWithKeys(Array('N'=>'New sub','E'=>'End adding subs'),'>',$log);

					if(strtolower($opt)=='e'){

						break;

					}

					$subConfig	=	new SubConfig();
					$subConfig->setModule($module);
					$config->addSub(Sub::configure($subConfig,$log));

				}while(TRUE);

			}

			public static function configure(&$config=NULL, LogInterface &$log){
				
				$config	=	new ModuleConfig($config);
				$project	=	$config->getProject();

				if(!$project){

					throw new \LogicException("Given module configuration has no assigned project");

				}

				if(!$project->getConfig()){

					throw new \LogicException("Passed project has not been properly configured");

				}


				do{

					Cmd::clear();
					$log->info('Module configuration');
					$log->repeat('-',80,'light_purple');

					$hasSubs	=	$config->hasSubs();

					$options	=	Array(
											'MN'	=>	Array(
																'value'	=>	sprintf('%s module name (%s)',$config->getName() ? 'Change' : 'Set', $config->getName()),
																'color'	=>	$config->getName()	?	'light_purple'	:	'light_cyan'
											),
											'MD'	=>	"Configure module directories",
											'CS'	=>	"Configure subs"
					);

					if($hasSubs){

						$options['LS']	=	'List subs';

					}

					$options['B']	=	'Back';

					$opt	=	Cmd::selectWithKeys($options,'>',$log);

					switch(strtolower($opt)){

						case 'mn':
							self::configureName($config,$log);
						break;

						case 'md':
							self::configureDirectories($config,$log);
						break;

						case 'as':
							self::configureSubs($config,$log);
						break;

						case 'b':
							break 2;
						break;

					}

				}while(TRUE);

				return new Module($config,$validate='soft');

			}

		}

	}
