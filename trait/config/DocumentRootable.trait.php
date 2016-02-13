<?php

	namespace apf\traits\config{

		use \apf\core\Directory	as	Dir;
	
		trait DocumentRootable{

			public function setDocumentRootDirectory(Dir $dir){

				$this->directory	=	$dir;
				return $this;

			}

			public function getDocumentRootDirectory(){

				return parent::getDocumentRootDirectory();

			}

		}	

	}
	
