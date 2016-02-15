<?php

	namespace apf\traits\config\fragment{

		use \apf\core\Directory	as	Dir;

		trait Directories{

			public function setFragmentsDirectory(Dir $dir){

				$this->fragmentsDirectory	=	$dir;

				return $this;

			}

			public function getFragmentsDirectory(){

				return parent::getFragmentsDirectory();

			}

		}

	}

