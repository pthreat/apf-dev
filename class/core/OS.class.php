<?php

	namespace apf\core{
	
		class OS{

			private	$name	=	NULL;

			public function getName(){

				if($this->name){

					return $this->name;

				}

				return $this->name	=	php_uname('s');

			}

			public static function getShortName(){

				$OS	=	strtolower($this->getOs());
				return substr($OS,0,strpos($OS,' '));

			}

			public function isWindows(){

				return $this->getShortName()=='windows';

			}

			public function isLinux(){

				return $this->getShortName()=='linux';

			}

			public function isFreeBSD(){

				return $this->getShortName()=='freebsd';

			}

		}

	}
