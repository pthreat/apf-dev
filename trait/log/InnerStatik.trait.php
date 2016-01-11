<?php

	namespace apf\traits\log{

		use apf\iface\Log as LogInterface;

		trait InnerStatik{

			private static $logObj	=	NULL;

			public static function setLog(LogInterface &$log){

				$log->setPrepend('['.__CLASS__.']');
				self::$logObj	=	$log;

			}

			public static function getLog(){

				return self::$logObj;

			}

			private static function __innerStaticLogDebug($text=NULL){

				if(self::$logObj === NULL){

					return;

				}

				self::$logObj->debug($text);

			}

			private static function __innerStaticLogInfo($text=NULL){

				if(self::$logObj===NULL){

					return;

				}

				return self::$logObj->info($text);

			}

			private static function __innerStaticLogWarning($text=NULL){

				if(self::$logObj===NULL){

					return;

				}

				return self::$logObj->warning($text);

			}

			private static function __innerStaticLogError($text=NULL){

				if(self::$logObj===NULL){

					return;

				}

				return self::$logObj->error($text);

			}

			private static function __innerStaticLogEmergency($text=NULL){

				if(self::$logObj===NULL){

					return;

				}

				return self::$logObj->emergency($text,1,"red");

			}

			private static function __innerStaticLogSuccess($text=NULL){

				if(self::$logObj===NULL){

					return;

				}

				return self::$logObj->success($text);
				
			}
	
		}

	}
