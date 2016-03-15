<?php

	/**
	 * A config attribute container contains attributes for an \apf\core\Config object
	 */

	namespace apf\core\config\attribute{

		use \apf\core\Config;
		use \apf\core\config\Attribute;

		class Container implements \Iterator,\Countable{

			private	$attributes	=	NULL;
			private	$config		=	NULL;

			public function __construct(Config $config){

				$this->config		=	$config;
				$this->attributes	=	new \ArrayObject();

			}

			public function count(){

				return count($this->attributes);

			}

			public function add(Array $parameters){

				$parameters['config']	=	$this->config;
				$this->attributes->append(new Attribute($parameters));

				return $this;

			}

			public function has($name){

				return (boolean)$this->get($name);

			}

			public function get($name){

				$name	=	strtolower($name);

				foreach($this->attributes as $attribute){

					if(strtolower($attribute->getName()) == $name){

						if($attribute->isMultiple()){

							return $attribute->getValue();

						}

						return $attribute;

					}

				}	

				throw new \InvalidArgumentException("Unknown attribute \"$name\"");

			}

			public function getAttributes(){

				return $this->attributes;

			}

			/************************************
			 *Iterator interface
			 ************************************/

			public function current(){

				$current	=	current($this->attributes);

				if(!$current->isTraversable()){

					next($this->attributes);

					return current($this->attributes);

				}

				return $current;

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

			/**
			 * Small internal helper for validating offsets inside the attributes array object
			 */

			private function validateOffset($offset){

				if(!array_key_exists($offset,$this->attributes)){

					throw new \InvalidArgumentException("Unknown attribute property \"$offset\"");

				}

				return $offset;

			}

			/****************************************
			 *Array Access interface methods
			 ****************************************/

			public function offsetExists($offset){

				$this->validateOffset($offset);				

			}

			public function offsetGet($offset){

				return $this->attributes[$this->validateOffset($offset)];

			}

			public function offsetSet($offset,$value){

				$this->attributes[$this->validateOffset($offset)]	=	$value;

			}

			public function offsetUnset($offset){

				$this->attributes[$this->validateOffset($offset)]	=	NULL;

			}

			/*************************************
			 *Magic methods
			 *************************************/

			public function __set($name,$value){

				return $this->get($name)->setValue($name);

			}

			public function __get($name){

				return $this->get($name);

			}

			public function __call($method,$args){

				$isSetterGetterOrAdd	=	strtolower(substr($method,0,3));

				$isSetter				=	$isSetterGetterOrAdd ===	'set';
				$isGetter				=	$isSetterGetterOrAdd ===	'get';
				$isAdd					=	$isSetterGetterOrAdd	===	'add';

				if(!$isSetter && !$isGetter && !$isAdd){

					throw new \BadMethodCallException("Call to undefined method: \"$method\"");

				}

				$attribute		=	$this->get(substr($method,3));

				switch($isSetterGetterOrAdd){

					case 'get':

						return $attribute->getValue();

					break;

					case 'set':

						return call_user_func_array(Array($attribute,'setValue'),$args);

					break;

					case 'add':

						return call_user_func_array(Array($attribute,'addValue'),$args);

					break;

				}

			}

		}

	}
