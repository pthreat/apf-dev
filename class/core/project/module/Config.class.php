<?php

	namespace apf\core\project\module{

		use \apf\core\Cmd;
		use \apf\core\Project;
		use \apf\core\Directory	as	Dir;
		use \apf\core\Config 	as BaseConfig;

		class Config extends BaseConfig{

			public function setName($name){

				$name	=	trim($name);

				if(empty($name)){

					throw new \InvalidArgumentException("Project name can not be empty");

				}

				$this->name	=	$name;

				return $this;

			}

			public function getName(){

				return parent::getName();

			}

			public function setDirectory(Dir $dir){

				$this->directory	=	$dir;
				return $this;

			}

			public function getDirectory(){

				return parent::getDirectory();

			}

			public function setProject(Project $project){

				$this->project	=	$project;
				return $this;

			}

			public function getProject(){

				return parent::getProject();

			}

			public function getNonExportableAttributes(){

				return Array(
								'project'
				);

			}

			public function setTemplatesDirectory(Dir $dir){

				$this->templatesDirectory	=	$dir;
				return $this;

			}

			public function getTemplatesDirectory(){

				return parent::getTemplatesDirectory();

			}

			public function setFragmentsDirectory(Dir $dir){

				$this->fragmentsDirectory	=	$dir;
				return $this;

			}

			public function getFragmentsDirectory(){

				return parent::getFragmentsDirectory();

			}

			public function addSub(Sub $sub){

				$this->subs[$sub->getName()]	=	$sub;
				return $this;

			}

			//This method should be pretty much like a "sub" factory
			public function getSub($name){

				if(!$this->hasSub($name)){

					throw new \InvalidArgumentException("No sub named \"$name\" could be found in this module");

				}

				return $this->subs[$name];

			}

			public function hasSub($name){

				return array_key_exists($name,$this->subs);

			}

			public function setSubsDirectory(Dir $dir){

				$this->subsDirectory	=	$dir;

				return $this;

			}

			public function getSubsDirectory(){

				return parent::getSubsDirectory();

			}

		}

	}

