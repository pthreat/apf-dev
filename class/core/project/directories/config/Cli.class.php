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

			public static function configure(&$config=NULL, LogInterface &$log){

				$config	=	new ProjectDirectoriesConfig($config);

			}

		}

	}
