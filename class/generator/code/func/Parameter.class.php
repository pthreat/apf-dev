<?php

	namespace apf\generator\code\func{

		class Parameter{

			private	$name				=	NULL;
			private	$hasDefault		=	FALSE;
			private	$defaultValue	=	NULL;
			private	$typeHint		=	NULL;

			public function __construct($name,$hasDefault=FALSE,$default=NULL){

				$this->setName($name);
				$this->setHasDefault($hasDefault);
				$this->setDefaultValue($default);

			}

			public function setTypeHint($hint){

				$this->typeHint	=	$hint;
				return $this;

			}

			public function getTypeHint(){

				return $this->typeHint;

			}

			public function setDefaultValue($default){

				$this->defaultValue	=	$default;
				return $this;

			}

			public function getDefaultValue(){

				return $this->defaultValue;

			}

			public function setHasDefault($boolean){

				$this->hasDefault	=	(boolean)$boolean;

				return $this;

			}

			public function getHasDefault(){

				return $this->hasDefault;

			}

			public function setName($name){

				$name			=	trim($name);

				if(empty($name)){

					throw new \InvalidArgumentException("Parameter name can not be empty");

				}

				$this->name	=	$name;

				return $this;

			}

			public function getName(){

				return $this->name;

			}

			public function render(){

				if($this->hasDefault){

					return sprintf('%s$%s = %s',$this->typeHint ? "{$this->typeHint} " : '',$this->name,$this->defaultValue);

				}

				return sprintf('%s$%s',$this->typeHint ? "{$this->typeHint} " : '', $this->name);

			}

			public function __toString(){

				return $this->render();

			}

		}

	}

