<?php

	namespace apf\core\project\config\cli\directories{

		use \apf\iface\config\Nameable;

		use \apf\iface\config\RootDirectory;
		use \apf\iface\config\module\Directories		as	ModuleDirectories;
		use \apf\iface\config\sub\Directories			as	SubDirectories;
		use \apf\iface\config\template\Directories	as	TemplateDirectories;
		use \apf\iface\config\fragment\Directories	as	FragmentDirectories;

		class Helper{

			private static function setDefaults(&$config){

				$mainDir	=	NULL;

				if($config instanceof RootDirectory){

					if($config->getRootDirectory()){

						$mainDir	=	$config->getRootDirectory();

					}elseif($config instanceof Nameable){

						$mainDir		=	new Dir(realpath(getcwd()));
						$mainDir->addPath($config->getName());

					}

					$config->setRootDirectory($mainDir);

				}

				if($config instanceof ModuleDirectories){

					$modulesDir	=	clone($mainDir);
					$modulesDir->addPath('modules');

					$config->setModulesDirectory($modulesDir);

				}

				if($config instanceof TemplateDirectories){

					$templatesDir	=	clone($mainDir);
					$templatesDir->addPath('templates');

				}

				if($config instanceof FragmentDirectories){

					$fragmentsDir	=	clone($mainDir);
					$fragmentsDir->addPath('fragments');
					$config->setFragmentsDirectories($fragmentsDir);

				}

			}

			public static function reset(&$config){

				if($config instanceof RootDirectory){

					$config->unsetRootDirectory();

				}

				if($config instanceof ModuleDirectories){

					$config->unsetModulesDirectories();

				}

				if($config instanceof TemplateDirectories){

					$config->unsetTemplatesDirectory();

				}

				if($config instanceof FragmentDirectories){

					$config->unsetFragmentsDirectory();

				}

				if($config instanceof SubDirectories){

					$config->unsetSubsDirectory();

				}

			}

			public static function getMenu(&$object){


			}

		}

	}
