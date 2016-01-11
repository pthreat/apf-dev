<?php

	namespace apf\core{

		class DI{

			private static $container	=	Array();

			public static function &get($key){

				if(!isset(self::$container[$key])){

					throw new \Exception("Invalid DI key");

				}

				return self::$container[$key];

			}

			public static function set($key,$value){

				self::$container[$key]	=	$value;

			}

			public static function getKeys(){

				return array_keys(self::$container);

			}

		}

	}

