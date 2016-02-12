<?php

	namespace apf\traits{
	
		trait Templateable{

			public function setFragmentsDirectory(Dir $dir){

				$this->fragmentsDirectory	=	$dir;

				return $this;

			}

			public function getFragmentsDirectory(){

				return parent::getFragmentsDirectory();

			}

			public function setTemplatesDirectory(Dir $dir){

				$this->templatesDirectory	=	$dir;

				return $this;

			}

			public function getTemplatesDirectory(){

				return parent::getTemplatesDirectory();

			}

		}

	}
