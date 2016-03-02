<?php

	namespace apf\console{

		class Ansi{

			/**
			 * @var $colors Array Different colors for console output
			 */

			private static $ansiColors = Array(
															"black"			=>	  "\33[0;30m",
															"blue"			=>	  "\33[0;34m",
															"light_blue"	=>	  "\33[1;34m",
															"green"			=>	  "\33[0;32m",
															"light_green"	=>	  "\33[1;32m",
															"cyan"			=>	  "\33[0;36m",
															"light_cyan"	=>	  "\33[1;36m",
															"red"				=>	  "\33[0;31m",
															"light_red"		=>	  "\33[0;31m",
															"purple"			=>	  "\33[0;35m",
															"light_purple"	=>	  "\33[1;35m",
															"brown"			=>	  "\33[0;33m",
															"gray"			=>	  "\33[1;30m",
															"light_gray"	=>	  "\33[0;37m",
															"yellow"			=>	  "\33[1;33m",
															"white"			=>	  "\33[1;37m"
			);

			public static function clear(){

				return print("\033[2J\033[;H");

			}

			public static function getColor($name){

				$name	=	trim($name);

				if(!in_array($name,array_keys(self::$ansiColors))){

					throw new \InvalidArgumentException("Unknown color \"$name\"");

				}

				return self::$ansiColors[$name];

			}

			public static function colorize($string,$color){

				return sprintf('%s%s',self::getColor($color),$string);

			}

		}	

	}

