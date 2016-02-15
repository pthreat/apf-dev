<?php

	namespace apf\core\project\config{

		use \apf\core\Project;
		use \apf\core\project\Directories					as	ProjectDirectories;
		use \apf\core\project\directories\Config			as	ProjectDirectoriesConfig;
		use \apf\iface\Log										as	LogInterface;
		use \apf\core\project\Config							as	ProjectConfig;

		use \apf\core\Cmd;
		use \apf\core\Directory									as	Dir;
		use \apf\iface\config\Cli								as	CliConfigInterface;

		class Cli implements CliConfigInterface{

			use \apf\traits\config\cli\RootDirectory;
			use \apf\traits\config\cli\template\Directories;
			use \apf\traits\config\cli\fragment\Directories;
			use \apf\traits\config\cli\module\Directories;

			/**
			 * Configure project directories.
			 *
			 * This interactive menu will allow the end user to configure several project directories.
			 *
			 * A) Configure the Project	directory, this is the base directory where the project will be located.
			 * B) Configure the Modules	directory, this is the directory where modules will be in.
			 * C) Configure the Templates directory, this is the directory where global templates will be stored.
			 * D) Configure the fragments directory, this is the directory where global fragments will be stored.
			 *
			 * @params \apf\core\project\Config			A project configuration object
			 * @params \apf\iface\Log						A log interface to display messages and prompts in the command line.
			 *	@return \apf\core\project\Directories	A configured project directories object.
			 *
			 */

			public static function configure(&$config=NULL, LogInterface &$log){

				$config	=	new ProjectDirectoriesConfig($config);
				$project	=	$config->getProject();

			}

		}

	}
