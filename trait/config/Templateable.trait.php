<?php

	namespace apf\traits\config{

		use \apf\core\Directory	as	Dir;

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
