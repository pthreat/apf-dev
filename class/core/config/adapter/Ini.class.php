<?php

	namespace apf\core\config\adapter{

		use apf\core\Config;	
		use apf\core\config\Section;
		use apf\core\config\Value	as	ConfigValue;

		class Ini{

			public static function export(Config $config){

				$str		=	Array();

				foreach($config as $attribute){

					if($attribute->isMultiple()){

						$str[]	=	sprintf(';%s',$attribute->getDescription());

						foreach($attribute->getValue() as $attr){

							$str[]	=	"{$attribute->getName()}[] = '{$attr}'";

						}

						continue;

					}

					$str[]	=	"{$attribute->getName()} = '{$attribute->getValue()}' ;{$attribute->getDescription()}";

				}

				return sprintf('%s%s',implode("\n",$str),"\n");

			}

			public function save($file){

			}

		}

	}
