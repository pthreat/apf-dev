<?php

	/**
	*This class is part of Apollo PHP Framework.
	*
	*Namespace	:	apf\validate
	*Class		:	String
	*Description:	A class for validating string properties such as length, emptiness, etc
	*
	*Author		:	Federico Stange <jpfstange@gmail.com>
	*License		:	3 clause BSD
	*
	*Copyright (c) 2015, Federico Stange
	*
	*All rights reserved.
	*
	*Redistribution and use in source and binary forms, with or without modification, 
	*are permitted provided that the following conditions are met:
	*
	*1. Redistributions of source code must retain the above copyright notice, 
	*this list of conditions and the following disclaimer.
	*
	*2. Redistributions in binary form must reproduce the above copyright notice, 
	*this list of conditions and the following disclaimer in the documentation and/or other 
	*materials provided with the distribution.
	*
	*3. Neither the name of the copyright holder nor the names of its contributors may be used to 
	*endorse or promote products derived from this software without specific prior written permission.
	*
	*THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS 
	*OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY 
	*AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER 
	*OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR 
	*CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
	*LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY 
	*OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) 
	*ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY 
	*OF SUCH DAMAGE.
	*
	*/


	namespace apf\validate{

		use apf\type\String as StringType;
		use apf\util\String as Sutil;

		class String extends Base{

			public static function isString($val){

				if(is_string($val)){

					return TRUE;

				}

				if(is_object($val)){

					return !Class_::hasMethod(get_class($val),'__toString') ? NULL : TRUE;

				}

				return NULL;
				
			}

			public static function parameterValidation($val){
				
				if(!self::isString($val)){

					return NULL;

				}

				return TRUE;
				
			}

			public static function mustBeString($val,$msg=NULL,$exCode=0){

				$isString	=	self::isString($val);

				if($isString){

					return TRUE;

				}

				parent::imperativeValidation($isString,$exCode,$msg);

			}
			
			public static function getStandardExceptionMessages() {

				return Array(
							Array(
									"value"	=>	NULL,
									"msg"		=>	"Given argument is not a string"
							),
							Array(
									"value"	=>	-10,
									"msg"		=>	'String length must be greater than 0'
							)
				);
				
			}

			/**
			*Check if a string is empty.
			*@param String $string The string to be checked.
			*@param boolean $useTrim wether to trim the string or not.
			*@param String $msg \apf\exception\Validate message.
			*@param Int $exCode \apf\exception\Validate code.
			*@throws \apf\exception\Validate in case the given string is effectively empty.
			*@return String The string (trimmed or not, this is specified with the $useTrim parameter).
			*/
			
			public static function isEmpty($string,$useTrim=TRUE){

				if($useTrim){

					$string	=	Sutil::trim($string);

				}
				
				$stdValidation	=	self::parameterValidation($string);

				if(!($stdValidation===TRUE)){

					return $stdValidation;

				}

				return empty($string);

			}

			public static function mustBeNotEmpty($string,$useTrim=TRUE,$msg=NULL,$exCode=0){

				if(!self::isEmpty($string,$useTrim)){

					return $useTrim ? Sutil::trim($string) : $string;

				}

				if(empty($msg)){

					$msg	=	'String can not be empty. Using trim: %s';
					$msg	=	sprintf($msg,$useTrim	?	'yes'	:	'no');

				}

				throw new \apf\exception\Validate($msg,$exCode);

			}

			/**
			*Checks if a string begins with a certain string
			*@param String $string String to be checked
			*@param String $begin String to compare if $string begins with this very same string.
			*@return boolean TRUE The $string begins with $begin
			*@return boolean FALSE The $string doesn't begins with $begin
			*/

			public static function beginsWith($string,$begin){

				$string	=	substr($string,0,strlen($begin));

				return $string==$begin;

			}

			/**
			*Check if the length of a string is between specified limits.
			*@param Int $min Minimum limit
			*@param Int $maximum Maximum limit
			*@param String $string The string to be checked
			*@param String $msg \apf\exception\Validate message.
			*@param Int $exCode \apf\exception\Validate code.
			*@throws \apf\exception\Validate in case the given string is not between specified limits
			*@return Int The string length 
			*/

			public static function mustHaveLengthBetween($string,$min,$max,$useTrim=TRUE,$msg=NULL,$exCode=0){

				if($useTrim){

					$string	=	Sutil::trim(StringType::cast($string));

				}

				$min	=	(int)$min;
				$max	=	(int)$max;

				$msg	=	empty($msg) ? sprintf('String length has to be between %d and %d characteres. String "%s" has a length of %d characters',$min,$max,$string,$len) : $msg;

				
				return Int::mustBeBetween(strlen($string),$min,$max,$msg,$exCode);

			}

			/**
			*Check if the length of a string has a minimum of $min characters
			*@param Int $min Amount of minimum characters
			*@param String $string The string to be checked
			*@param String $msg \apf\exception\Validate message.
			*@param Int $exCode \apf\exception\Validate code.
			*@throws \apf\exception\Validate in case the given string has not the amount of minimum characters.
			*@return Int The string length 
			*/

			public static function mustHaveMinLength($string,$min,$msg=NULL,$exCode=0){

				$min	=	(int)$min;
				$len	=	strlen($string);

				$msg	=	empty($msg) ? sprintf('String has to have a minimum of %d characteres. String "%s" has only %d characters',$min,$string,$len) : $msg;

				return Int::mustBeGreaterOrEqualThan($len,$min,$msg,$exCode);

			}

			/**
			*Check if the length of a string exceeds a maximum amount of characters
			*@param Int $max Maximum amount of characters
			*@param String $string The string to be checked
			*@param String $msg \apf\exception\Validate message.
			*@param Int $exCode \apf\exception\Validate code.
			*@throws \apf\exception\Validate in case the given string has exceeded the maximum amount of characters.
			*@return Int The string length 
			*/

			public static function maxLength($max,$string,$msg=NULL,$exCode=0){

				$max	=	(int)$max;
				$len	=	strlen($string);

				$msg	=	empty($msg) ? sprintf('String has exceeded the amount of %d characteres. String "%s" has %d characters',$max,$string,$len) : $msg;

				return Int::isLowerOrEqualThan($max,$len,$msg,$exCode);

			}

			public static function hasLengthEqualTo($string,$length){

				$stdVal	=	self::parameterValidation($string);

				if(!Int::isPositive($length)){

					return -10;

				}

				$length	=	(int)$length;

				if(!($stdVal===TRUE)){

					return $stdVal;

				}

				return strlen($string)==$length;

			}

			public static function mustHaveLengthEqualTo($string,$length,$useTrim=TRUE,$msg=NULL,$exCode=0){

				if($useTrim){

					$string	=	Sutil::trim($string);

				}

				$hasLengthEqualTo	=	self::hasLengthEqualTo($string,$length);

				parent::imperativeValidation($string,$exCode,$msg);

				return $string;
				
			}

		}

	}

