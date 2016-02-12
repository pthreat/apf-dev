<?php

	namespace apf\traits\config{

		use \apf\core\Directory	as	Dir;
	
		trait RootDirectory{

			public function setDirectory(Dir $dir){

				$this->directory	=	$dir;
				return $this;

			}

			public function getDirectory(){

				return parent::getDirectory();

			}

		}	

	}
	
