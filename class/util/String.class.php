<?php

	namespace apf\util{

		use apf\validate\String as StringValidate;

		class String{

			public static function tokenize($string,$delimiter,$num=0){

				StringValidate::mustBeNotEmpty($string,$trim=TRUE,"Must provide a string to find");
				StringValidate::mustBeNotEmpty($delimiter,$trim=TRUE,"Must provide a delimiter");

				$tok = strtok($string,$delimiter);

				$cnt	=	0;

				while ($tok !== FALSE) {

					if($cnt++==$num){
						return $tok;
						break;
					}

					$tok = strtok($delimiter);

				}

			}

			public static function tokenizeCallback($string,$delimiter,Callable $callBack){

				StringValidate::mustBeNotEmpty($string,$trim=TRUE,"Must provide a string to find");
				StringValidate::mustBeNotEmpty($delimiter,$trim=TRUE,"Must provide a delimiter");

				$tok = strtok($string,$delimiter);

				$cnt	=	0;

				while ($tok !== FALSE) {

					if($callBack($tok) === FALSE){
						break;
					}

					$tok = strtok($delimiter);

				}

			}

			public static function tabs($num=1){

				return str_repeat("\t",$num);

			}

			//Not supposed to be a stupid and simplistic approach but rather
			//to be wary of utf-8 encoded strings

			public static function find($haystack,$needle){

				$pos	=	strpos($haystack,$needle);

				return !($pos===FALSE);

			}

			public static function findTokenized($string,$delimiter,$find){

				StringValidate::mustBeNotEmpty($string,$trim=TRUE,"Must provide a string to find");
				StringValidate::mustBeNotEmpty($delimiter,$trim=TRUE,"Must provide a delimiter");
				StringValidate::mustBeNotEmpty($find,$trim=TRUE,"Must provide a string to find");

				//Remove duplicates
				$quoted	=	preg_quote($delimiter,'/');
				$string	=	preg_replace("/[$quoted]{2,}/",$delimiter,$string);

				$tok = strtok($string,$delimiter);

				while ($tok !== FALSE) {

					if($tok==$find){
						return TRUE;
						break;
					}

					$tok = strtok($delimiter);

				}

			}

			public static function findTokenizedValue($string,$delimiter,$find){

				StringValidate::mustBeNotEmpty($string,$trim=TRUE,"Must provide a string to find");
				StringValidate::mustBeNotEmpty($delimiter,$trim=TRUE,"Must provide a delimiter");
				StringValidate::mustBeNotEmpty($find,$trim=TRUE,"Must provide a string to find");

				//Remove duplicated delimiters
				$quoted	=	preg_quote($delimiter,'/');
				$string	=	preg_replace("/[$quoted]{2,}/",$delimiter,$string);

				$tok = strtok($string,$delimiter);

				while ($tok !== FALSE) {

					if($tok==$find){

						$tok = strtok($delimiter);
						return $tok;

					}

					$tok = strtok($delimiter);

				}

			}

			public static function trim($str,$chars=NULL){

				if(function_exists('mb_detect_encoding')){

					$encoding	=	mb_detect_encoding($str);

					if($encoding=='ASCII'){

						return $chars ? trim($str,$chars) : trim($str);

					}

				}

				//Basic trim ...
				return urldecode(trim(urlencode($str),'+'));

			}

			public static function toCamelCase($string){

				if(!isset($string[0])){

					return '';

				}

				$string		=	sprintf('%s',$string);	//in case we are dealing with an object having __toString
				$string		=	preg_replace('/[^a-zA-Z]/',' ',$string);
				$string		=	ucwords($string);
				$string[0]	=	strtolower($string[0]);
				return preg_replace('/\W/','',$string);

			}

			public static function toUpperCamelCase($string){

				if(!isset($string[0])){
					return '';
				}

				$string	=	self::toCamelCase($string);
				$string[0]	=	strtoupper($string[0]);
				return $string;

			}

			public static function toSlug($string, $char = '-'){

				StringValidate::mustBeNotEmpty($string,$trim=TRUE,"Must provide a string to slugify");

				$string 	=	preg_replace('/&/', '', $string);
				$string 	=	preg_replace('/\W/', $char, self::toAscii($string));
				$string 	=	strtolower(preg_replace('/[-]{2,}/', '-', $string));
				$string	=	preg_replace('/[^a-zA-Z0-9\-]/','',$string);

				return trim($string,'-');

			}

			public static function deSlug($string, $char = '-', $separator = ' ') {

				return preg_replace("/$char/", $separator, $string);

			}

			public static function toAscii($str = NULL) {

				$str = @iconv('UTF-8', 'ASCII//TRANSLIT', $str);
				return $str;

			}

			public static function minify($buffer){

				 $search = array(
					  '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
					  '/[^\S ]+\</s',  // strip whitespaces before tags, except space
					  '/(\s)+/s',       // shorten multiple whitespace sequences
					  '/(\t)+/s'
				 );

				 $replace = array(
					  '>',
					  '<',
					  '\\1'
				 );

				 $buffer = preg_replace($search, $replace, $buffer);

				 return $buffer;

			}

		}

	}
