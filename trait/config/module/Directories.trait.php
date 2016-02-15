<?php

	namespace apf\traits\config\module{

		use \apf\core\Directory	as	Dir;

		trait Directories{

			public function setModulesDirectory(Dir $dir){

				$this->modulesDirectory	=	$dir;
				return $this;

			}

			public function getModulesDirectory(){

				return parent::getModulesDirectory();

			}

		}

	}
