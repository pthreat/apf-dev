<?php

	namespace apf\ui\form{

		use \apf\core\Configurable;
		use \apf\iface\ui\form\element\Layout		as	ElementLayoutInterface;
		use \apf\iface\ui\form\Element				as	ElementInterface;
		use \apf\iface\ui\form\element\Attribute	as	ElementAttributeInterface;
		use \apf\validate\Vector						as	VectorValidate;

		abstract class Element implements ElementInterface{

			private	$name				=	NULL;
			private	$description	=	NULL;
			private	$attributes		=	Array();
			private	$value			=	NULL;
			private	$onSetValue		=	NULL;
			private	$valueState		=	'noval';	//Valid value states are: noval, success, and error

			/**
			 * Each element ->state<- has a different layout
			 * For no value, there a layout 
			 * For success, there is a layout
			 * and for error, there is a layout
			 * A layout is just the "way" how an element is rendered, 
			 * i.e where should the name be placed?, where should the value be placed?
			 *
			 * Example layouts: 
			 * name:separator:value:error
			 * [name]:separator :<value>:error!
			 */

			private	$noValueLayout	=	NULL;
			private	$valueLayout	=	NULL;
			private	$errorLayout	=	NULL;

			public function __construct($attrName,$description,Array $layouts=Array()){

				if($attrName){

					$this->setName($attrName);

				}

				if($description){

					$this->setDescription($description);

				}

				VectorValidate::mustHaveKeys(
														Array('noval','success','error'),
														$layouts,
														'Invalid layouts array, given array must contain the noval, success and error keys!'
				);

				$this->setNoValueLayout($layouts['noval']);
				$this->setValueLayout($layouts['success']);
				$this->setErrorLayout($layouts['error']);

			}

			public function setNoValueLayout(ElementLayoutInterface $layout){

				$this->noValueLayout	=	$layout;
				return $this;

			}

			public function getNoValueLayout(){

				return $this->noValueLayout;

			}

			public function setValueLayout(ElementLayoutInterface $layout){

				$this->valueLayout	=	$layout;
				return $this;

			}

			public function getValueLayout(){

				return $this->valueLayout;

			}

			public function setErrorLayout(ElementLayoutInterface $layout){

				$this->errorLayout	=	$layout;
				return $this;

			}

			public function getErrorLayout(){

				return $this->errorLayout;

			}

			public function setName($name){

				$this->name	=	$name;
				return $this;

			}

			public function getName(){

				return $this->name;

			}

			public function setDescription($description){

				$this->description	=	$description;
				return $this;

			}

			public function getDescription(){

				return $this->description;

			}

			public function onSetValue(Callable $callback){

				$this->onSetValue	=	$callback;
				return $this;

			}

			public function setValue($value){

				if($this->onSetValue !== NULL){

					try{

						$callback	=	&$this->onSetValue;
						$callback($value);

					}catch(\Exception $e){

						$this->setValueState('error');
						throw new \Exception($e->getMessage());

					}

				}

				$this->value	=	$value;
				$this->setValueState('success');

				return $this;

			}

			public function setValueState($state){

				$this->valueState	=	$state;
				return $this;

			}

			public function getValueState(){

				return $this->valueState;

			}

			public function getValue(){

				return $this->value;

			}

			public function addAttribute(ElementAttributeInterface $attribute){

				$this->attributes[]	=	$attribute;
				return $this;

			}

			public function getAttributes(){

				return $this->attributes;

			}

			public function setAttributes(Array $attributes){

				foreach($attributes as $attribute){

					$this->addAttribute($attribute);

				}

				return $this;

			}

			public function render(){

				switch($this->valueState){

					case 'noval':
						return $this->noValueLayout->render();
					break;

					case 'success':
						return $this->valueLayout->render();
					break;

					case 'error':
						return $this->errorLayout->render();
					break;

				}

			}

			public function __toString(){

				try{

					return $this->render();

				}catch(\Exception $e){

					return "Error: {$e->getMessage()}";

				}

			}

		}

	}

