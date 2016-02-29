<?php

	namespace apf\core{

		use \apf\core\OS;
		use \apf\core\Sapi;
		use \apf\core\Project;
		use \apf\core\Configurable;
		use \apf\core\Router;
		use \apf\util\String		as	StringUtil;
		use \apf\util\Class_		as	ClassUtil;
		use \apf\core\Directory	as	Dir;
		use \apf\iface\Logable	as	LogableInterface;

		set_error_handler(function ($errno, $errstr, $errfile, $errline ){

			if (!(error_reporting() & $errno)) {

				return;

			}

			throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);

		});

		if(!class_exists('\apf\core\kernel\Helper')){

			require realpath(sprintf('%s%skernel%sHelper.class.php',__DIR__,DIRECTORY_SEPARATOR,DIRECTORY_SEPARATOR));

		}

		spl_autoload_register('\apf\core\kernel\Helper::autoLoad');

		class Kernel extends Configurable implements LogableInterface{

			use \apf\traits\log\Inner;

			private	static $sapi	=	NULL;
			private	static $os		=	NULL;

			const	VERSION		=	'0.1';

			public static function boot(){

			}

			public static function postAutoLoad($class){
			}

			public function init($file){

				$this->getConfig()
				->setProject(Project::factory($file));

			}

			public static function getOS(){

				if(self::$os){

					return self::$os;

				}

				return self::$os	=	new OS();

			}

			public static function getSapi(){

				if(self::$sapi){

					return self::$sapi;

				}

				return self::$sapi	=	new Sapi();

			}

		}

	}

