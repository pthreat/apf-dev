<?php

	namespace apf\traits\config{
	
		use \apf\core\Project;

		trait Projectable{

			public function setProject(Project $project){

				$this->project	=	$project;
				return $this;

			}

			public function getProject(){

				return parent::getProject();

			}

		}

	}
