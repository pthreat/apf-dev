<?php

	namespace apf\core\config{

		use apf\core\config\adapter\Ini	as	IniAdapter;		
		use apf\core\config\adapter\Json	as	JsonAdapter;
		use apf\core\Config					as	Section;

		abstract class Container implements \Iterator{

			private	$sections			=	Array();

			public function addSection(Section $section){

				$this->sections[$section->getName()]	=	$section;
				return $this->sections[$section->getName()];

			}

			public function getSection($section){

				foreach($this->sections as $objSection){

					if($objSection->getName()==$section){

						return $objSection;

					}

				}

				throw new \InvalidArgumentException("Invalid section \"$section\"");

			}

			public function getSections(){

				return $this->sections;

			}

			public static function importFromString($string,$format="ini"){

				$temp	=	sprintf('%s.%s',tempnam(sys_get_temp_dir(),'config'),$format);
				$fp	=	fopen($temp,'w');
				fwrite($fp,$string);
				fclose($fp);

				$return	=	NULL;

				try{

					$return	=	self::import($temp,$format);

				}catch(\Exception $e){
				}

				unlink($temp);

				return $return;

			}

			public static function import($file,$format="ini"){

				$adapter	=	self::getAdapter($format);
				return $adapter::import($file);

			}

			private static function getAdapter($adapter){

				$adapter			=	ucwords($adapter);
				$exportClass	=	sprintf('\\apf\\core\\config\\adapter\\%s',$adapter);

				if(!class_exists($exportClass)){

					throw new \RuntimeException("Unknown configuration adapter \"$adapter\"");

				}

				return $exportClass;

			}

			public function export($format="ini",$section=NULL){

				$exportClass	=	self::getAdapter($format);

				return $exportClass::export($this);

			}

			//////////////////////////////////////
			//Iterator interface
			/////////////////////////////////////

			public function current(){

				return current($this->sections);

			}

			public function key(){

				return key($this->sections);

			}

			public function next(){

				return next($this->sections);

			}

			public function rewind(){

				return reset($this->sections);

			}

			public function valid(){

				$key	=	key($this->sections);
				return $key!==NULL && $key!==FALSE;

			}

		}

	}

