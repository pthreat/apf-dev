<?php

	namespace apf\core\config{

		use apf\core\config\Section;

		class Value{

			private	$name				=	NULL;
			private	$value			=	NULL;
			private	$separator		=	'=';
			private	$isSecured		=	NULL;
			private	$securedText	=	'(secured attribute) xxxxxxx';

			public function __construct($name,$value,$isSecure=FALSE,$separator='='){

				$name	=	trim($name);

				if(empty($name)){

					throw new \InvalidArgumentException("A config value object's name can must be a non empty string");

				}

				$this->name			=	$name;
				$this->value		=	$value;
				$this->separator	=	$separator;
				$this->setSecure($isSecure);

			}

			public function setSecure($boolean){

				if(!is_null($this->isSecured)){

					throw new \BadMethodCallException("Can not use method setSecure on a configuration value that has been marked as secure");

				}

				$this->isSecured	=	(boolean)$boolean;

				return $this;

			}

			public function isSecured(){

				return $this->isSecured;

			}

			public function isSection(){

				return $this->isSection;

			}

			public function toArray(){

				return Array($this->name=>$this->getvalue());

			}

			public function getName(){

				return $this->name;

			}

			public function getValue(){

				return $this->isSecured	?	$this->securedText	:	$this->value;

			}

			//Available in PHP 5.6 ONLY

			public function __debugInfo(){

				return $this->toArray();

			}

			public function __toString(){

				return sprintf('%s',is_array($this->getValue()) ? implode(',',$this->getValue()) : $this->getValue());

			}

		}

	}

