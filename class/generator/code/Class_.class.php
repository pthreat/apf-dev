<?php

	namespace apf\generator\code{

		use apf\util\String	as	StringUtil;
		use func\Parameters	as	Parameters;
		use apf\generator\code\class_\Attribute;
		use apf\generator\code\class_\Method;
		use apf\generator\code\Block;
		use apf\generator\Code;

		class Class_ extends Block{

			private	$name			=	NULL;
			private	$extendsTo	=	NULL;
			private	$interfaces	=	Array();
			private	$attributes	=	Array();
			private	$traits		=	Array();
			private	$methods		=	Array();

			public function __construct($name=NULL,$extendsTo=NULL,Array $attributes=Array(),Array $methods=Array()){

				if(!is_null($name)){

					$this->setName($name);

				}

				if(!is_null($extendsTo)){

					$this->setExtendsTo($extendsTo);

				}

				foreach($methods as $method){

					$this->addMethod($method);

				}

				foreach($attributes as $attribute){

					$this->addAttribute($attribute);

				}

			}

			public function addTrait($trait){

				if($this->hasTrait($trait)){

					throw new \InvalidArgumentException("Trait \"$trait\" already exists");

				}

				$this->traits[]	=	$trait;

				return $this;

			}

			public function hasTrait($trait){

				return in_array($trait,$this->traits);

			}

			public function getTraits(){

				return $this->traits;

			}

			public function addInterface($interface){

				if($this->hasInterface($interface)){
					throw new \InvalidArgumentException("Interface \"$interface\" already exists");
				}

				$this->interfaces[]	=	$interface;

				return $this;

			}

			public function hasInterface($interface){

				return in_array($interface,$this->interfaces);

			}

			public function getInterfaces(){

				return $this->interfaces;

			}

			public function addMethod(Method $method){

				$this->methods[]	=	$method;
				return $this;

			}

			public function getMethods(){

				return $this->methods;

			}

			public function addAttribute(Attribute $attribute){

				$this->attributes[]	=	$attribute;
				return $this;

			}

			public function getAttributes(){

				return $this->attributes;

			}

			public function setName($name){

				$this->name	=	$name;
				return $this;

			}

			public function getName(){

				return $this->name;

			}

			public function setExtendsTo($extends){

				$this->extendsTo	=	$extends;
				return $this;

			}

			public function getExtendsTo(){	

				return $this->extendsTo;

			}

			public function render($indent=NULL){

				parent::reset();

				$className	=	StringUtil::toUpperCamelCase($this->name);

				//class $name extends $extends implements $interfaces
				if($this->extendsTo && sizeof($this->interfaces)){

					$header	=	sprintf('class %s extends %s implements %s',$className,$this->extendsTo,implode(',',$this->interfaces));

				//class $name implements $interfaces
				}elseif(!$this->extendsTo && sizeof($this->interfaces)){

					$header	=	sprintf('class %s implements %s ',$className,implode(',',$this->interfaces));

				//class $name extends $extends
				}elseif($this->extendsTo){

					$header	=	sprintf('class %s extends %s ',$className,$this->extendsTo);

				//class $name
				}else{

					$header	=	sprintf('class %s ',$className);

				}

				$this->setHeader($header);

				foreach($this->attributes as $key=>$attribute){

					$this->l($attribute);

				}

				foreach($this->methods as $method){

					$this->l($method);

				}

				return parent::render($indent);

			}

			public function __toString(){

				return $this->render();

			}

		}

	}
