<?php

	namespace apf\core\project\module{

		use apf\core\Cmd;
		use apf\core\Directory	as	Dir;
		use apf\core\Config 		as BaseConfig;

		class Config extends BaseConfig{

			private	$subs		=	Array();
			private	$project	=	NULL;

			public function setProject(Project $project){

				$this->project	=	$project;
				return $this;

			}

			public static function getDefaultInstance(){
			}

			public function getProject(){

				return $this;

			}

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

				if($dir->exists() && !$dir->isWritable()){

					throw new \InvalidArgumentException("Directory \"$dir\" is not writable");

				}

				$this->directory	=	$dir;

				return $this;

			}

			public function getDirectory(){

				return parent::getDirectory();

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

				if($dir->exists() && !$dir->isWritable()){

					throw new \InvalidArgumentException("Directory \"$dir\" is not writable");

				}

				$this->subsDirectory	=	$dir;

				return $this;

			}

			public function getSubsDirectory(){

				return parent::getSubsDirectory();

			}

		}

	}

