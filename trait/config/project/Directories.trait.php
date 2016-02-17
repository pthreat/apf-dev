<?php

	namespace apf\traits\config\project{

		use \apf\core\project\Directories	as	ProjectDirectories;

		trait Directories{

			public function setDirectories(ProjectDirectories $projectDirectories){

				$this->directories	=	$projectDirectories;

			}

			public function getDirectories(){

				return parent::getDirectories();

			}

		}

	}
