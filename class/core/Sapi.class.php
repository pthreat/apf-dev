<?php

	namespace apf\core{

		class Sapi{

			private $name	=	NULL;

			public function getName(){

				if($this->name){

					return $this->name;

				}

				return $this->name	=	php_sapi_name();

			}

			public function isCli(){

				return strtolower($this->getName())=='cli';

			}

			public function isWeb(){

				return !$this->isCli();

			}

			public function __toString(){

				return sprintf('%s',$this->getName();

			}

		}

	}
