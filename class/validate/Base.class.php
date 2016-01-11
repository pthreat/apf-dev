<?php
	namespace apf\validate{
		
		abstract class Base implements \apf\iface\Validate{

			public static function parameterValidation($param1){
				return static::parameterValidation($param1);
			}

			public static function getStandardExceptionMessages(){
				return static::getStandardExceptionMessages();
			}

			protected static function imperativeValidation($curValue,$exCode,$msg,Array $codesAndMessages=Array()){

				self::validateUserSuppliedExceptionCode($exCode);

				if(sizeof($codesAndMessages)){

					$codesAndMessages	=	array_merge(self::getStandardExceptionMessages(),$codesAndMessages);

				}else{

					$codesAndMessages	=	self::getStandardExceptionMessages();

				}

				foreach($codesAndMessages as $cam){

					if($cam["value"]===$curValue){

						$_msg	=	\apf\validate\String::isEmpty($msg)	?	$cam["msg"]	:	$msg;
						$ex	=	isset($cam["exception"])	?	$cam["exception"]	:	"\InvalidArgumentException";
						throw new $ex($_msg);

					}

				}

			}

			/**
			*Validates that an exception code must be greater than 0
			*@throws \InvalidArgumentException in case the given code is not greater than 0
			*@return NULL if everything worked as expected.
			*/

			private static function validateUserSuppliedExceptionCode($code){

				$code	=	(int)$code;

				if($code<0){

					throw new \InvalidArgumentException("Exception code must be greater than 0");

				}

			}
			
		}
		
	}
