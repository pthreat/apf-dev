<?php

	namespace apf\core\config\adapter{

		use apf\core\Config;	
		use apf\core\config\Section;
		use apf\core\config\Value	as	ConfigValue;

		class Ini{

			public static function export(Config $config){

				$str		=	Array();

				foreach($config as $value){

					if($value->isMultiple()){

						foreach($value->getValue() as $val){

							$str[]	=	"{$value->getName()}[] = '{$val}'\n";

						}

						continue;

					}

					$str[]	=	"{$value->getName()} = '{$value->getValue()}'";

				}

				return sprintf('%s%s',implode("\n",$str),"\n");

			}

			public function save($file){

			}

		}

	}
