<?php

	namespace apf\traits\config{

		use \apf\core\project\module\Sub;

		trait Subable{

			public function addSub(Sub $sub){

				$this->subs[$sub->getName()]	=	$sub;
				return $this;

			}

			public function setSubs(Array $subs){

				if(!parent::getSubs()){

					$this->subs	=	Array();

				}

				foreach($subs as $sub){

					if(!is_a($sub,'\\apf\\core\\project\\module\\Sub')){

						throw new \InvalidArgumentException("Given element is not a Sub!");

					}

					$this->subs->append($sub);

				}

				return $this;

			}

			public function getSubs(){

				return parent::getSubs();

			}

			//This method should be pretty much like a "sub" factory
			public function getSub($name){

				if(!$this->hasSub($name)){

					throw new \InvalidArgumentException("No sub named \"$name\" could be found in this module");

				}

				return $this->subs[$name];

			}

			public function hasSub($name){

				return array_key_exists($name,$this->subs);

			}

			public function hasSubs(){

				return parent::getSubs()	?	TRUE	:	FALSE;

			}

			public function setSubsDirectory(Dir $dir){

				$this->subsDirectory	=	$dir;

				return $this;

			}

			public function getSubsDirectory(){

				return parent::getSubsDirectory();

			}

		}

	}
