<?php

	/**
	 * A config attribute container contains attributes for an \apf\core\Config object
	 */

	namespace apf\core\config\attribute{

		use \apf\core\Config;
		use \apf\core\config\Attribute;

		class Container implements \Iterator{

			private	$attributes	=	NULL;
			private	$config		=	NULL;

			public function __construct(Config $config){

				$this->config		=	$config;
				$this->attributes	=	new \ArrayObject();

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



		}

	}
