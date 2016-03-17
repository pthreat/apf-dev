<?php

	/**
	 * A configuration attribute container contains attributes for an \apf\core\Config object
	 * This class does not extends to \ArrayObject since every method in said class would have to be rewritten
	 * Instead, the \Iterator and \Countable interfaces are implemented.
	 */

	namespace apf\core\config\attribute{

		use \apf\core\Config;
		use \apf\core\config\Attribute;

		//@TODO: \ArrayAccess interface is also needed
		final class Container implements \Iterator,\Countable{

			private	$attributes	=	NULL;
			private	$config		=	NULL;

			/**
			 * The Attribute container constructor needs a configuration object for the purpose of passing said configuration object
			 * to each attribute composing the attribute container.
			 *
			 * Why?:
			 *-----------------------------------------------------------------------------------------------------------------------
			 * The configuration object is NEEDED in order for the \apf\core\Attribute to be able to validate the value being set to it
			 *
			 *	@see \apf\core\config\Attribute::setValue($value) method 
			 */

			public function __construct(Config $config){

				$this->config		=	$config;
				$this->attributes	=	new \ArrayObject();

			}

			/**
			 * Add an attribute to the container
			 */

			public function add($parameters){

				$parameters					=	!is_array($parameters)	?	Array('value'=>$parameters)	:	$parameters;
				$parameters['name']		=	array_key_exists('name',$parameters)	?	$parameters['name']		:	sprintf('params_%d',$this->count());
				$parameters['config']	=	$this->config;

				if(array_key_exists('value',$parameters)){
					echo get_class($parameters['value'])."\n";
				}

				$this->attributes->append(new Attribute($parameters));

				return $this;

			}

			public function has($name){

				return (boolean)$this->get($name);

			}

			/**
			 * Get an attribute by name
			 * @param string $name the name of the attribute to be fetched
			 * @return \apf\core\config\Attribute if the given attribute is found.
			 * @throws \InvalidArgumentException if the attribute is not found.
			 */

			public function get($name){

				$name	=	strtolower($name);

				foreach($this->attributes as $attribute){

					if($attribute->isMultiple()){

						if(strtolower($attribute->getItemName()) === $name){

							return $attribute;

						}

					}

					if(strtolower($attribute->getName()) == $name){

						return $attribute;

					}

				}	

				$configurationClass	=	get_class($this->config);
				$msg						=	'Unknown attribute "%s" in class "%s", please check %s::__configure method and add said attribute if necessary';
				$msg						= sprintf($msg,$name,get_class($this->config),$configurationClass,$configurationClass);

				throw new \InvalidArgumentException($msg);

			}

			/**
			 * Get all attributes
			 * @return \ArrayObject 
			 */

			public function getAttributes(){

				return $this->attributes;

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

			/************************************
			 * Countable interface 
			 ************************************/

			public function count(){

				return count($this->attributes);

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

						echo "Get $method\n";
						return $attribute->getValue();

					break;

					case 'set':

						return call_user_func_array(Array($attribute,'setValue'),$args);

					break;

					case 'add':

						return $attribute
						->getValue()
						->add(
								Array('value'=>$args[0])
						);

					break;

				}

			}

		}

	}
