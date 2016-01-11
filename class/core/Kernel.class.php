<?php

	namespace apf\core{

		use \apf\core\DI;
		use \apf\util\String	as	StringUtil;
		use \apf\util\Class_	as	ClassUtil;
		use \apf\core\Directory	as	Dir;

		set_error_handler(function ($errno, $errstr, $errfile, $errline ) {

			if (!(error_reporting() & $errno)) {

				return;

			}

			throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);

		});

		class Kernel{

			private static $paths			=	Array();
			private static $frameworkDir	=	NULL;
			private static	$namespaceMap	=	Array();
			private static $documentRoot	=	NULL;
			private static $projectPath	=	NULL;
			private static $appsDir			=	NULL;	//Where ALL applications can be found
			private static $appName			=	NULL; //The name of the current application
			private static	$sapi				=	NULL;
			private static $os				=	NULL;

			const	VERSION	=	'0.1';

			private static function addNamespaceMap(Array $map){

				\apf\validate\Vector::mustHaveKeys(Array("dir","namespace"),$map);
				$map["dir"]	=	\apf\validate\String::mustBeNotEmpty($map["dir"]);
				self::$namespaceMap[]	=	$map;

			}

			private static function isMapped($namespace){

				foreach(self::$namespaceMap as $map){

					if($map["namespace"]==$namespace){

						return $map;

					}

				}

				return FALSE;

			}

			public static function setProjectPath(Dir $path,$createIfNotExists=TRUE){

				if($createIfNotExists && !$path->isDir()){

					$path->create();

				}

				$path->chdir();

				self::$projectPath	=	$path;

			}

			public static function getProjectPath(){

				return self::$projectPath;

			}

			public static function getFrameworkDir(){

				return self::$frameworkDir;

			}

			public static function getFwPath(){

				return self::$frameworkDir;

			}

			public static function autoLoad($class){

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

					$path		=	sprintf("%s%s%s%s%s.%s.php",self::$frameworkDir,$ds,$type,$ds,$class,$type);

				}else{

					$path	=	substr(self::$appsDir,0,strrpos(self::$appsDir,$ds)+1);
					$path	=	sprintf('%s%s.class.php',$path,$class,self::$appsDir);

				}

				//Try to find out if we are talking about a custom namespace defined in the namespaces.ini file

				if(!file_exists($path)){

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

			}

			public static function getOS(){

				if(is_null(self::$os)){

					self::$os	=	php_uname('s');

				}

				return self::$os;

			}

			public static function getOsShortName(){

				$OS	=	strtolower(self::getOs());
				return substr($OS,0,strpos($OS,' '));

			}

			public static function makeAppPath($file){

				$ds	=	DIRECTORY_SEPARATOR;
				$file	=	is_array($file)	?	implode($ds,$file)	:	$file;

				return sprintf('%s%s%s%s%s%s%s',self::$projectPath,$ds,self::$appsDir,$ds,self::$appName,$ds,$file);

			}

			public static function makeProjectPath($file){

				$ds	=	DIRECTORY_SEPARATOR;
				$file	=	is_array($file)	?	implode($ds,$file)	:	$file;

				return sprintf('%s%s%s',self::$projectPath,$ds,$file);

			}

			public static function getAppPath(){

				$ds	=	DIRECTORY_SEPARATOR;

				return sprintf('%s%s%s%s%s',self::$projectPath,$ds,self::$appsDir,$ds,self::$appName);

			}

			public static function makeProjectConfigPath($file){

				$ds	=	DIRECTORY_SEPARATOR;
				return sprintf('%s%s%s',self::$projectPath,$ds,is_array($file) ? implode($ds,$file) : $file);

			}

			public static function makeFwPath($file){

				$ds	=	DIRECTORY_SEPARATOR;
				return sprintf('%s%s%s',self::$frameworkDir,$ds,is_array($file) ? implode($ds,$file) : $file);

			}

			public static function getSapi(){

				if(is_null(self::$sapi)){

					self::$sapi	=	php_sapi_name();

				}

				return self::$sapi;

			}

			public static function isCli(){

				return self::getSapi()=='cli';

			}

			public static function isWeb(){

				return !self::isCli();

			}

			public static function saveAppConfigFile(Config $cfg,$name){

				return $cfg->save(new File(self::makeAppPath(Array('config',$name))));

			}

			public static function saveProjectConfigFile(Config $cfg,$name){

				if(is_array($name)){

					array_unshift($name,'config');

				}else{

					$name	=	Array('config',$name);

				}

				return $cfg->save(new File(self::makeProjectPath($name)));

			}

			public static function isWindows(){

				return self::getOsShortName()=='windows';

			}

			public static function isLinux(){

				return self::getOsShortName()=='linux';

			}

			public static function isFreeBSD(){

				return self::getOsShortName()=='freebsd';

			}

			public static function boot(Array $configFiles=Array()){

				spl_autoload_register(sprintf('%s::autoLoad',__CLASS__));

				self::$documentRoot	=	dirname($_SERVER["SCRIPT_FILENAME"]);

				//In windows $_SERVER["SCRIPT_FILENAME"] will be returned with slashes instead of back
				//slashes causing the following string manipulation via substring and strpos to fail
				//Thats why we make sure that $_SERVER["SCRIPT_FILENAME"] contains the proper slashes
				//for the given OS, sadly we have to do this with preg_replace.

				$ds						=	DIRECTORY_SEPARATOR;
				self::$documentRoot	=	preg_replace('#/#',$ds,self::$documentRoot);
				self::$frameworkDir	=	realpath(sprintf('%s%s',__DIR__,"$ds..$ds..$ds"));

				if(self::isCli()=='cli'){

					self::$documentRoot	=	$_SERVER["PWD"];

				}

				if(!empty($configFiles)){

					self::configure($configFiles);

				}

			}

			public static function setAppName($appName){

				self::$appName	=	strtolower(preg_replace("/\W/",'',$appName));

			}

			public static function getAppName(){

				return self::$appName;

			}

			public static function setAppsDir(Dir $dir,$createIfNotExists=TRUE){

				self::$appsDir	=	basename($dir);

			}

			public static function configure($config){

				if($config instanceof Dir){

					$allConfigs	=	new Config();

					foreach($configDir->toArray() as $file){

						$file	=	self::makeAppPath(Array('config',$file));

						if(is_dir($file)){

							continue;

						}	

						$allConfigs->addFile($file);

					}

					DI::set("config",$allConfigs);

				}elseif($config instanceof Config){

					DI::set('config',$config);

				}

				if(!DI::get("config")->framework){

					throw new \RuntimeException("No framework configuration was found");

				}

				$cfg		=	&DI::get("config")->framework;

				if(self::isWindows()&& !empty($cfg->win_locale)){

					$locale	=	$cfg->win_locale;
					$locale	=	empty($locale)	?	'english'	:	$locale;

					if(setlocale(LC_ALL,$locale)===FALSE){

						throw new \Exception("Invalid windows locale specified in configuration");

					}

				}

				if(!self::isWindows() && !empty($cfg->locale)) {

					$locale	=	$cfg->locale;
					$locale	=	empty($locale)	?	'en_US.utf8'	:	$locale;

					if (setlocale(LC_ALL,$locale) === FALSE) {

						throw new \Exception("Invalid locale specified, it doesn't seems to be installed on your system");

					}

				}

				if(isset($cfg->dev_mode)&&(int)$cfg->dev_mode>0){

					ini_set("display_errors","On");
					error_reporting(E_STRICT);

				}

				//This should be in \apf\web\core\Kernel
				if(!self::isCli()&&isset($cfg->auto_session)){

					session_start();

				}

				if(!self::isCli()&&isset($cfg->headers)){

					header($cfg->headers);

				}

				if(isset($cfg->time_limit)){

					set_time_limit($cfg->time_limit);

				}

				if(isset($cfg->memory_limit)){

					ini_set("memory_limit",$cfg->memory_limit);

				}

				if(isset($cfg->timezone)){

					date_default_timezone_set($cfg->timezone);

				}

			}

			public static function getDocumentRoot(){

				return self::$documentRoot;

			}

			public static function getAppsDir(){

				return self::$appsDir;

			}

		}

	}

?>
