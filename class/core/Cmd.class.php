<?php

	namespace apf\core{

		class Cmd{

			public static function searchOpt($option,$default=NULL){

				foreach($_SERVER["argv"] as $opt=>$value){

					if(strtolower($value)!=strtolower($option)){
						continue;
					}

					if(isset($_SERVER["argv"][$opt+1])){

						if (!preg_match("/\-/",$_SERVER["argv"][$opt+1])){
							return $_SERVER["argv"][$opt+1];
						}

					}

					return '&';

				}

				return $default ? $default : FALSE;

			}

			public static function demandOpt($option){

				$search	=	self::searchOpt($option);

				if(!$search || $search =='&'){

					throw new \RuntimeException("Option $option is required but was not provided");

				}

				return $search;

			}

			public static function select(Array $options,$prompt='SELECT>',\apf\core\Log $log=NULL){

				\apf\validate\String::mustBeString($prompt,'Given prompt must be a string');

				$amountOfOptions	=	sizeof($options);

				if(is_null($log)){

					$log	=	new \apf\core\Log();

				}

				while(TRUE){

					foreach($options as $opt){

						$log->info(" $opt",0,"light_cyan");

					}

					$selected	=	strtolower(trim(self::readInput($prompt,$log),"\r\n"));

					if($amountOfOptions==1 && $selected==''){
						return $options[0];
					}

					foreach($options as $opt){

						if(strtolower($opt)==$selected){
							return $opt;	
						}

					}

				}

			}

			public static function selectWithKeys(Array $options,$prompt='SELECT>',\apf\core\Log $log=NULL){

				\apf\validate\String::mustBeString($prompt,'Given prompt must be a string');

				$amountOfOptions	=	sizeof($options);

				if(is_null($log)){

					$log	=	new \apf\core\Log();

				}

				$len	=	0;

				foreach($options as $key=>$opt){

					$len	=	strlen($key) > $len ? strlen($key) : $len;
						
				}

				$len+=3;

				while(TRUE){

					foreach($options as $key=>$opt){

						$log->info(sprintf('%s)%s%s',$key,str_repeat(' ',$len),$opt));

					}

					$selected	=	strtolower(trim(self::readInput($prompt,$log),"\r\n"));

					foreach($options as $key=>$opt){

						if(strtolower($key)==$selected){
							return $key;	
						}

					}

				}

			}

			public static function yesNo($msg,\apf\core\Log $log=NULL){

				\apf\validate\String::mustBeString($msg);

				if(is_null($log)){

					$log	=	new \apf\core\Log();

				}

				$msg		=	sprintf('%s (y/n):',$msg);
				$options	=	['y','affirmative','yes','ya','ye','yeah','yep','n','no','nope','negative'];
				$hasEcho	=	$log->getEcho();

				if($hasEcho){

					$log->setEcho(FALSE);

				}

				$select	=	substr(self::select($options,$msg,$log),0,1);

				if($hasEcho){

					$log->setEcho(TRUE);

				}

				return $select=="y";

			}

			public static function readInput($prompt=NULL,\apf\core\Log $log=NULL){

				if(!is_null($prompt)){

					if(is_null($log)){

						$log	=	new \apf\core\Log();

					}

					$echo	=	$log->getEcho();

					if(!$echo){

						$log->setEcho(TRUE);

					}

					$log->setNoLf();
					$log->setNoPrefix();
					$log->info($prompt);

					if(!$echo){

						$log->setEcho(FALSE);

					}

				}

				$fp	=	fopen("/dev/stdin",'r');
				$ret	=	fgets($fp,1024);

				fclose($fp);

				if(!is_null($log)){

					$log->usePrefix();
					$log->setLf();

				}

				return trim($ret);
	
			}

			public static function setOpt($name,$value){

				$_SERVER['argv'][sizeof($_SERVER['argv'])] = $name;
				$_SERVER['argv'][]	=	$value;

			}

			public static function readWithDefault($prompt,$default,Log $log=NULL){

				$prompt	=	sprintf('%s <default: %s>',$prompt,$default);
				$value	=	self::readInput($prompt,$log);

				return $value	?	$value	:	$default;

			}

			public static function readWhileEmpty($prompt=NULL,\apf\core\Log $log=NULL){

				while(TRUE){

					$input	=	preg_replace("/[\r\n]/",'',self::readInput($prompt,$log));

					if(!empty($input)){

						return $input;

					}

				}

			}

		}

	}

