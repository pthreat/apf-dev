<?php

	namespace apf\traits\config\sub{

		use \apf\core\Directory					as	Dir;

		trait Directories{

			public function setSubsDirectory(Dir $dir){

				$this->subsDirectory	=	$dir;

				return $this;

			}

			public function getSubsDirectory(){

				return parent::getSubsDirectory();

			}

		}

	}
