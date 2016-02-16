<?php

	namespace apf\traits\config{

		use \apf\core\Directory	as	Dir;
	
		trait RootDirectory{

			public function setRootDirectory(Dir $dir){

				$this->rootDirectory	=	$dir;
				return $this;

			}

			public function getRootDirectory(){

				return parent::getRootDirectory();

			}

		}	

	}
	
