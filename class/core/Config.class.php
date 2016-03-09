<?php

	namespace apf\core{

		use \apf\core\config\Attribute;
		use \apf\core\config\Adapter;

		abstract class Config implements \Iterator{

			private	$attributes	=	NULL;

			final public function __construct(Config $config=NULL){

				$this->attributes	=	new \ArrayObject();
				$this->configureAttributes();

				if($config !== NULL){

					$this->merge($config);

				}

			}

			private function makeValidatorName($name){

				return sprintf('validate%s',ucwords($name));

			}

			public function hasValidator($name){

				return method_exists($this,$this->makeValidatorName($name));

			}

			public function getValidator($name){

				if(!$this->hasValidator($name)){

					throw new \InvalidArgumentException("No validator found for attribute $name");		

				}

				return $this->makeValidatorName($name);

			}

			public function validateAttribute($name,$value){

				$validator	=	$this->getValidator($name);
				return $this->$validator($value);

			}

			protected function addAttribute($attribute){

				$this->attributes->append(Attribute::factory($attribute));

			}

			/**
			 * Returns setters and getters according to the attributes set on the configuration
			 * This is provided due to hidden functionality in the __call magic method.
			 * When exploring a new framework, I particularly tend to var_dump(get_class_methods($class))
			 * I find it frustrating when certain functionality is not obvious.
			 * This method is provided to leverage said frustration.
			 */

			public function getMethods(){

				$methods	=	Array();

				foreach($this as $attribute){

					$methods[]	=	sprintf('set%s',ucwords($attribute->getName()));
					$methods[]	=	sprintf('get%s',ucwords($attribute->getName()));

				}

				return $methods;
				
			}

			public function hasAttribute($name){

				return (boolean)$this->getAttribute($name);

			}

			public function getAttribute($name){

				$name	=	strtolower($name);

				foreach($this->attributes as $attribute){

					if($attribute->getName() == $name){

						return $attribute;

					}

				}	

				throw new \InvalidArgumentException("Unknown configuration parameter \"$name\"");

			}

			public function getAttributes(){

				return $this->attributes;

			}

			public function merge(Config $config){

				foreach($config as $attribute){

					$this->setValue($attribute);

				}

				return $this;

			}

			public function configureAttributes(){

				return $this->__configureAttributes();

			}

			abstract protected function __configureAttributes();

			/**
			 * This method returns a configuration adapter internally to be able to save, export, import
			 * a configuration object.
			 *
			 * A configuration adapter is basically a configuration format in which a Configuration object 
			 * can be exported, said formats can be: JSON, XML, INI. More formats will be supported in the future.
			 */

			private static function __getAdapter($adapter){

				$adapter			=	ucwords($adapter);
				$exportClass	=	sprintf('\\apf\\core\\config\\adapter\\%s',$adapter);

				if(!class_exists($exportClass)){

					throw new \RuntimeException("Unknown configuration adapter \"$adapter\"");

				}

				return $exportClass;

			}

			public function export($format="ini"){

				$exportClass	=	self::__getAdapter($format);

				return $exportClass::export($this);

			}

			public function import($file){

				$config		=	Adapter::factory($file);

				foreach($config->parse() as $key=>$value){

					$this->$key	=	$value;

				}

			}

			public function save($format="ini"){
			}

			/************************************
			 *Iterator interface
			 ************************************/

			public function current(){

				return current($this->attributes);

			}

			public function key(){

				return key($this->attributes);

			}

			public function next(){

				return next($this->attributes);

			}

			public function rewind(){

				return reset($this->attributes);

			}

			public function valid(){

				$key	=	key($this->attributes);
				return $key!==NULL && $key!==FALSE;

			}

			/*************************************
			 *Magic methods
			 *************************************/

			public function __set($name,$value){

				$attribute	=	$this->getAttribute($name);
				return $attribute->setValue($value);

			}

			public function __get($name){

				return $this->getAttribute($name);

			}

			public function __call($method,$args){

				$isSetterOrGetter	=	strtolower(substr($method,0,3));
				$isSetter			=	$isSetterOrGetter === 'set';
				$isGetter			=	$isSetterOrGetter === 'get';

				if(!$isSetter && !$isGetter){

					throw new \BadMethodCallException("Call to undefined method: \"$method\"");

				}

				$attribute		=	$this->getAttribute(substr($method,3));

				if($isGetter){

					return $attribute->getValue();

				}

				if($isSetter){

					return call_user_func_array(Array($attribute,'setValue'),$args);

				}

			}

			public function __toString(){

				return $this->export("ini");

			}

		}

	}
