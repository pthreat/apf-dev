<?php

	namespace apf\core\config\adapter{

		use \apf\core\Config;	
		use \apf\core\config\Section;
		use \apf\core\config\Adapter;

		class Json extends Adapter{

			public static function exportSection(Section $section){

				$str		=	Array();
				$str[$section->getName()]	=	Array();

				foreach($section as $value){

					if($value->isMultiple()){

						foreach($value->getValue() as $val){

							$str[]	=	"{$value->getName()}[] = '{$val}'\n";

						}

						continue;

					}

					if($value->isSection()){

						$str[$section->getName()]	=	self::exportSection($value);
						continue;

					}

					$str[$section->getName()][]	=	$value->toArray();

				}

				return $str;

			}

			public function parse(){

				return json_decode(file_get_contents($this->getFile()),$assoc=TRUE);

			}

			public static function export(Config $config){

				$conf	=	Array();

				foreach($config as $section){

					$conf[]	=	self::exportSection($section);

				}

				return json_encode($conf);

			}

			public function save($file){

			}

		}

	}
