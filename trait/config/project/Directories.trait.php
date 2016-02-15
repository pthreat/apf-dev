<?php

	namespace apf\traits\config\project{

		use \apf\core\project\Directories	as	ProjectDirectories;

		trait Directories{

			public function setDirectories(ProjectDirectories $projectDirectories){

				$this->projectDirectories	=	$projectDirectories;

			}

			public function getProjectDirectories(){

				return parent::getProjectDirectories();

			}

		}

	}
