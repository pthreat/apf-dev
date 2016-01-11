<?php

	namespace apf\generator\code\class_{

		class Attribute{

			private	$scope		=	NULL;
			private	$name			=	NULL;
			private	$hasDefault	=	FALSE;
			private	$default		=	NULL;

			public function __construct($scope,$name,$hasDefault=FALSE,$default=NULL){

				$this->setScope($scope);
				$this->setName($name);
				$this->hasDefault	=	(boolean)$hasDefault;
				$this->default		=	$default;

			}

			public function setScope($scope){

				$scope	=	trim(strtolower($scope));

				if(!in_array($scope,Array('private','public','protected'))){

					throw new \InvalidArgumentException("Invalid scope specified \"$scope\"");

				}

				$this->scope	=	$scope;

				return $this;

			}

			public function getScope(){

				return $this->scope;

			}

			public function setName($name){

				$name	=	trim($name);

				if(empty($name)){

					throw new \InvalidArgumentException("Attribute name can not be empty");

				}

				$this->name	=	$name;

				return $this;

			}

			public function getName(){

				return $this->name;

			}

			public function setDefaultValue($default){

				$this->default	=	$default;
				return $this;

			}

			public function getDefaultValue($default){

				return $this->default;

			}

			public function render($addSemiColon=TRUE){
			
				if($this->hasDefault){

					return sprintf('%s $%s=%s%s',$this->scope,$this->name,$this->default,$addSemiColon ? ';' : '');

				}

				return sprintf('%s $%s%s',$this->scope,$this->name,$addSemiColon ? ';' : '');

			}

			public function __toString(){

				return $this->render();

			}

		}

	}

