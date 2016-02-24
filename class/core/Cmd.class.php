<?php

	namespace apf\core{

		use \apf\iface\Log	as	LogInterface;

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

			public static function clear(){

				return print("\033[2J\033[;H");

			}

			public static function select(Array $options,$prompt='SELECT>',LogInterface &$log){

				\apf\validate\String::mustBeString($prompt,'Given prompt must be a string');

				$amountOfOptions	=	sizeof($options);

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

			public static function selectWithKeys(Array $options,$prompt='SELECT>',LogInterface &$log){

				\apf\validate\String::mustBeString($prompt,'Given prompt must be a string');

				$amountOfOptions	=	sizeof($options);

				$len	=	0;

				foreach($options as $key=>$opt){

					$len	=	strlen($key) > $len ? strlen($key) : $len;
						
				}

				$len+=3;

				while(TRUE){

					foreach($options as $key=>$opt){

						if(is_array($opt)){

							if(isset($opt['color'])){

								$log->log(sprintf('%s)%s%s',$key,str_repeat(' ',$len),$opt['value']),$type=0,$opt['color']);

							}

						}else{

							$log->info(sprintf('%s)%s%s',$key,str_repeat(' ',$len),$opt));

						}

					}

					$selected	=	strtolower(trim(self::readInput($prompt,$log),"\r\n"));

					foreach($options as $key=>$opt){

						if(strtolower($key)==$selected){
							return $key;	
						}

					}

				}

			}

			public static function yesNo($msg,LogInterface &$log){

				\apf\validate\String::mustBeString($msg);

				$msg		=	sprintf('%s (y/n):',$msg);
				$options	=	['y','affirmative','yes','ya','ye','yeah','yep','n','no','nope','negative'];

				$select	=	substr(self::select($options,$msg,$log),0,1);

				return $select=="y";

			}

			public static function readInput($prompt=NULL,LogInterface $log){

				if(!is_null($prompt)){

					$log->setNoLf();
					$log->setNoPrefix();
					$log->info($prompt);

				}

				$fp	=	fopen("php://stdin",'r');
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

			public static function readWithDefault($prompt,$default,LogInterface &$log){

				$prompt	=	sprintf('%s <default: %s>',$prompt,$default);
				$value	=	self::readInput($prompt,$log);

				return $value	?	$value	:	$default;

			}

			public static function readWhileEmpty($prompt=NULL,LogInterface &$log){

				while(TRUE){

					$input	=	preg_replace("/[\r\n]/",'',self::readInput($prompt,$log));

					if(!empty($input)){

						return $input;

					}

				}

			}

		}

	}

