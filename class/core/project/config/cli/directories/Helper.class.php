<?php

	namespace apf\core\project\config\cli\directories{

		use \apf\iface\config\Nameable;
		use \apf\iface\Log									as	LogInterface;
		use \apf\core\Cmd;
		use \apf\core\Directory								as	Dir;
		use \apf\iface\config\RootDirectory;
		use \apf\iface\config\module\Directories		as	ModuleDirectories;
		use \apf\iface\config\sub\Directories			as	SubDirectories;
		use \apf\iface\config\template\Directories	as	TemplateDirectories;
		use \apf\iface\config\fragment\Directories	as	FragmentDirectories;

		class Helper{

			use \apf\traits\config\cli\RootDirectory;
			use \apf\traits\config\cli\module\Directories;
			use \apf\traits\config\cli\template\Directories;
			use \apf\traits\config\cli\fragment\Directories;

			public static function reset(&$config){

				if($config instanceof RootDirectory){

					$config->unsetRootDirectory();

				}

				if($config instanceof ModuleDirectories){

					$config->unsetModulesDirectory();

				}

				if($config instanceof SubsDirectories){

					$config->unsetSubsDirectory();

				}

				if($config instanceof TemplateDirectories){

					$config->unsetTemplatesDirectory();

				}

				if($config instanceof FragmentDirectories){

					$config->unsetFragmentsDirectory();

				}

			}

			public static function defaults(&$config){

				$mainDir		=	new Dir(realpath(getcwd()));

				if($config instanceof RootDirectory){

					if($config->getRootDirectory()){

						$mainDir	=	$config->getRootDirectory();

					}elseif($config instanceof Nameable){

						$mainDir->addPath($config->getName());

					}

				}

				$config->setRootDirectory($mainDir);

				if($config instanceof ModuleDirectories){

					$modulesDir	=	clone($mainDir);
					$modulesDir->addPath('modules');

					$config->setModulesDirectory($modulesDir);

				}

				if($config instanceof SubsDirectories){

					$subsDir	=	clone($mainDir);
					$subsDir->addPath('subs');

					$config->setSubsDirectory($subsDir);

				}

				if($config instanceof TemplateDirectories){

					$templatesDir	=	clone($mainDir);
					$templatesDir->addPath('templates');

					$config->setTemplatesDirectory($templatesDir);

				}

				if($config instanceof FragmentDirectories){

					$fragmentsDir	=	clone($mainDir);
					$fragmentsDir->addPath('fragments');
					$config->setFragmentsDirectory($fragmentsDir);

				}

			}

			public static function getMenu(&$config,Array $valuesExcept=Array(),$extraMenus=Array(),$customMenus=Array()){

				$allConfigured	=	$config->hasValuesExcept($valuesExcept);

				$menu				=	Array();

				if($config instanceof RootDirectory){

					$menu['R']	=	Array(
												'value'	=>	sprintf('Configure root directory (%s)',$config->getRootDirectory()),
												'color'	=>	$config->getRootDirectory() ? 'light_purple'	:	'light_cyan'
					);

				}

				if($config instanceof TemplateDirectories){

					$menu['T']	=	Array(
												'value'	=>	sprintf('Configure templates directory (%s)',$config->getTemplatesDirectory()),
												'color'	=>	$config->getTemplatesDirectory() ? 'light_purple'	:	'light_cyan'
					);

				}

				if($config instanceof FragmentDirectories){

					$menu['F']	=	Array(
												'value'	=>	sprintf('Configure fragments directory (%s)',$config->getFragmentsDirectory()),
												'color'	=>	$config->getFragmentsDirectory() ? 'light_purple'	:	'light_cyan'
					);

				}

				if($config instanceof ModuleDirectories){

					$menu['M']	=	Array(
												'value'	=>	sprintf('Configure modules directory (%s)',$config->getModulesDirectory()),
												'color'	=>	$config->getModulesDirectory() ? 'light_purple'	:	'light_cyan'
					);

				}

				if($config instanceof SubDirectories){

					$menu['U']	=	Array(
												'value'	=>	sprintf('Configure sub (modules) directory (%s)',$config->getSubsDirectory()),
												'color'	=>	$config->getSubsDirectory() ? 'light_purple'	:	'light_cyan'
					);

				}

				if($allConfigured && in_array('reset',$extraMenus)){

					$menu['E']	=	Array(
												'value'	=>	'Reset configuration',
												'color'	=>	$allConfigured	?	'red'			:	'yellow'
					);

				}

				if(in_array('defaults',$extraMenus)){

					$menu['L']	=	Array(
												'value'	=>	'Load defaults',
												'color'	=>	$allConfigured	?	'yellow'		:	'light_green'
					);

				}

				if(in_array('save',$extraMenus)){

					$menu['S']	=	Array(
												'value'	=>	'Save',
												'color'	=>	$allConfigured	?	'yellow'			:	'light_green'
					);

				}

				if(in_array('back',$extraMenus)){

					$menu['B']	=	Array(
												'value'	=>	'Back',
												'color'	=>	$allConfigured	?	'light_cyan'	:	'yellow'
					);

				}

				return $menu;

			}

			public static function switchMenuOption($opt,&$config,$allConfigured=FALSE,LogInterface &$log){

				switch(trim(strtolower($opt))){

					case 'r':

						self::configureRootdirectory($config,$log);

					break;

					case 'e':

						if($config->hasValues() && Cmd::yesNo('Are you sure you want to reset the entire configuration?',$log)){

							self::reset($config);

						}

					break;

					case 't':

						self::configureTemplateDirectories($config,$log);

					break;

					case 'f':

						self::configureFragmentDirectories($config,$log);

					break;

					case 'm':

						self::configureModuleDirectories($config,$log);

					break;

					case 'u':

						self::configureSubDirectories($config,$log);

					break;

					case 'b':

						//No values, assume safe "back"

						if(!$config->hasValuesExcept('project')||$allConfigured){

							return FALSE;

						}

						$log->warning("You have unsaved changes in this configuration.");

						if(Cmd::yesNo("Are you sure you want to go back without saving?",$log)){

							return FALSE;

						}

					break;

					case 'l':

						if(!$config->hasValues()||$allConfigured){

							$log->warning("You have configured some directories");

							if(!Cmd::yesNo("Are you sure you want to load the default values?",$log)){

								return;

							}

						}
						
						self::defaults($config);

					break;

				}

			}

		}

	}

