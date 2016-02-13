<?php

	namespace apf\core\project\config\cli{
	
		use \apf\iface\config\RootDirectory	as	RootDirectoryInterface;
		use \apf\iface\config\Templateable	as	TemplateableInterface;
		use \apf\iface\config\Subable			as	SubableInterface;
		use \apf\iface\config\Moduleable		as	ModuleableInterface;

		abstract class Directories{

			public static function getDirectoriesMenu(&$config=NULL, Array $menu=Array()){

				$options	=	Array();

				if($config instanceof RootDirectoryInterface){

					$options['R']	=	Array(
													'value'	=>	sprintf('%s root directory',$config->getDirectory() ? 'Change' : 'Set'),
													'color'	=>	$config->getDirectory() ? 'light_purple'	:	'light_cyan'
					);

				}

				if($config instanceof TemplateableInterface){

					$options['T']	=	Array(
													'value'	=>	sprintf('%s templates directory',$config->getTemplatesDirectory() ? 'Change' : 'Set'),
													'color'	=>	$config->getTemplatesDirectory() ? 'light_purple'	:	'light_cyan'
					);

					$options['F']	=	Array(
													'value'	=>	sprintf('%s fragments directory',$config->getFragmentsDirectory() ? 'Change' : 'Set'),
													'color'	=>	$config->getFragmentsDirectory() ? 'light_purple'	:	'light_cyan'
					);

				}

				if($config instanceof ModuleableInterface){

					$options['M']	=	Array(
													'value'	=>	sprintf('%s modules directory',$config->getModulesDirectory() ? 'Change' : 'Set'),
													'color'	=>	$config->getModulesDirectory() ? 'light_purple'	:	'light_cyan'
					);

				}

				if($config instanceof SubableInterface){

					$options['S']	=	Array(
													'value'	=>	sprintf('%s subs directory',$config->getSubsDirectory() ? 'Change' : 'Set'),
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
