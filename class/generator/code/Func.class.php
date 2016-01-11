<?php

	namespace apf\generator\code{

		use apf\generator\code\func\Parameter;
		use apf\generator\code\func\Parameters;
		use apf\generator\Code;
		use apf\generator\code\Block as CodeBlock;

		class Func extends CodeBlock{

			private	$name			=	NULL;
			private	$parameters	=	NULL;
			private	$code			=	NULL;

			public function __construct($name,Parameters $parameters=NULL){

				$this->setName($name);

				if(!is_null($parameters)){

					$this->setParameters($parameters);

				}

			}

			public function setParameters(Parameters $parameters){

				$this->parameters	=	$parameters;
				return $this;

			}

			public function getParameters(){

				return $this->parameters;

			}

			public function setName($name){

				$name	=	trim($name);

				if(empty($name)){

					throw new \InvalidArgumentException("Function name can not be empty");

				}

				$this->name	=	$name;

				return $this;

			}

			public function getName(){

				return $this->name;

			}

			public function addParameter(Parameter $parameter){

				if(is_null($this->parameters)){

					$this->parameters	=	new Parameters();

				}

				$this->parameters->add($parameter);

				return $this;

			}

			public function getHeader(){

				return sprintf('function %s(%s)',$this->name,$this->parameters);

			}

			public function render($indent=NULL){

				return parent::render();

			}

		}

	}
