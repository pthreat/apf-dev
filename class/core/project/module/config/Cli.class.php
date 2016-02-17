<?php

	namespace apf\core\project\module\config{

		use \apf\core\Cmd;
		use \apf\iface\config\Cli						as	CliConfigInterface;
		use \apf\core\Project;
		use \apf\core\project\config\Directories;
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


			/**
			 * Configures a Module configuration object.
			 *
			 * @params \apf\core\project\module\Config	$config a module configuration to be edited (optional).
			 * @params \apf\iface\Log							$log    a log object to display configuration messages.
			 * @return \apf\core\project\Module				returns a configurated module.
			 * @return boolean FALSE							if the user aborts the configuration process.
			 */

			public static function configure(&$config=NULL, LogInterface &$log){

				$isNew	=	$config===NULL;
				$config	=	new ModuleConfig($config);

				$project	=	$config->getProject();

				if(!$project){

					throw new \LogicException("Given module configuration has no assigned project");

				}

				if(!$project->getConfig()){

					throw new \LogicException("Passed project has not been properly configured");

				}

				$projectConfig	=	$project->getConfig();
				$isNew			=	!$project->isValidated();

				do{

					Cmd::clear();

					$log->logArray(
										Array(
												"Project {$projectConfig->getName()}",
												"modules",
												$isNew ? 'create' :	'edit',
												$isNew ? ''			:	" > {$config->getName()}"
										),
										' > ',
										'light_purple'
					);

					$log->repeat('-',80,'light_purple');

					$hasSubs	=	$config->hasSubs();

					$options	=	Array(
											'N'	=>	Array(
																'value'	=>	sprintf('%s module name (%s)',$config->getName() ? 'Change' : 'Set', $config->getName()),
																'color'	=>	$config->getName()	?	'light_purple'	:	'light_cyan'
											),
											'D'	=>	"Configure module directories",
											'S'	=>	"Configure subs"
					);

					if($hasSubs){

						$options['LS']	=	'List subs';

					}

					$options['B']	=	'Back';

					$opt	=	Cmd::selectWithKeys($options,'>',$log);

					switch(strtolower($opt)){

						case 'n':

							self::configureName($config,$log);

						break;

						case 'd':

							Directories::cliConfig($config,$log);

						break;

						case 's':

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
