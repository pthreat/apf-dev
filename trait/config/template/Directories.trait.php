<?php

	namespace apf\traits\config\template{

		use \apf\core\Directory	as	Dir;

		trait Directories{

			public function setTemplatesDirectory(Dir $dir){

				$this->templatesDirectory	=	$dir;

				return $this;

			}

			public function getTemplatesDirectory(){

				return parent::getTemplatesDirectory();

			}

		}

	}

