<?php

	namespace apf\core\kernel{

		use \apf\core\File;
		use \apf\core\Log;
		use \apf\iface\Log		as	LogInterface;
		use \apf\core\log\File	as	FileLog;

		class Helper{

			private static $apfDirectory			=	NULL;
			private static $log						=	NULL;

			public static function setLog(LogInterface $log){

				self::$log	=	$log;

			}

			public static function getLog(){

				return self::$log;

			}

			public static function autoLoad($class){

				if(class_exists($class,$autoload=FALSE)){

					return;

				}

				$ds			=	'/';

				$class		=	preg_replace('#\\\#',$ds,$class);
				$isAPFClass	=	substr($class,0,strpos($class,$ds))=="apf";

				if($isAPFClass){

					$class	=	substr($class,strpos($class,$ds)+1);
					$type		=	substr($class,0,strpos($class,'/'));

					$dir		=	'';

					switch($type){

						case 'iface':
							$type		=	'interface';
							$class	=	substr($class,strpos($class,'/')+1);
						break;

						case 'traits':
							$type		=	'trait';
							$class	=	substr($class,strpos($class,'/')+1);
						break;

						case 'class':
						default:
							$type	=	'class';
						break;
						
					}

					$path		=	sprintf("%s%s%s%s%s.%s.php",self::getFrameworkDirectory(),$ds,$type,$ds,$class,$type);

				}else{

					return;

					$path	=	sprintf('%s.class.php',$class);

				}

				//Try to find out if we are talking about a custom namespace defined in the namespaces.ini file

				if(!file_exists($path)){
					throw new \Exception("Class $class not found in path $path");
					$namespacesConfigDir	=	sprintf('%s%sconfig%snamespaces',self::$appsDir,DIRECTORY_SEPARATOR,DIRECTORY_SEPARATOR);

					if(is_dir($namespacesConfigDir)){

						$dir			=	new \apf\core\Directory($namespacesConfigDir);

						$base			=	basename($class);
						$namespace	=	preg_replace("#/#",'\\',substr($class,0,(strlen($base)+1)*-1));

						foreach($dir->getFilesAsArray() as $namespaceFile){

							$nss			=	parse_ini_file(sprintf('%s%s%s',$dir,DIRECTORY_SEPARATOR,$namespaceFile),TRUE);

							foreach($nss as $ns=>$map){

								$map	=	$map[key($map)];

								if($ns == $namespace){

									$fileName	=	sprintf('%s.class.php',StringUtil::toUpperCamelCase($base));
									return (new \apf\core\File(sprintf('%s%s%s',$map,DIRECTORY_SEPARATOR,$fileName)))->requireIt();

								}

							}

						}

					}

					throw new \Exception("Class not found $path");

				}

				require $path;

				return self::postAutoLoad($path,$type);

			}

			private static function postAutoLoad($file,$type){

				$log	=	self::getLog();

				if(!$log){
					return FALSE;
				}

				return $log->debug("Loaded $type: $file ...");

			}

			public static function getFrameworkDirectory(){

				if(self::$apfDirectory){

					return self::$apfDirectory;

				}

				return self::$apfDirectory	=	realpath(sprintf('%s%s%s%s%s%s%s',__DIR__,DIRECTORY_SEPARATOR,'..',DIRECTORY_SEPARATOR,'..',DIRECTORY_SEPARATOR,'..'));

			}

		}

	}

