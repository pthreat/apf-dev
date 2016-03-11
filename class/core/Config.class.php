<?php

	namespace apf\core{

		use \apf\core\config\attribute\Container	as	AttributeContainer;
		use \apf\core\config\Attribute;
		use \apf\core\config\Adapter;

		abstract class Config{

			private	$attributeContainer	=	NULL;

			private	$isValidatedSoft		=	FALSE;
			private	$isValidatedHard		=	FALSE;
			private	$isValidatedExtra		=	FALSE;

			final public function __construct($parentObject,Config $config=NULL){

				$this->validateConfigurableObject($parentObject);

				$this->attributeContainer	=	new AttributeContainer($this);

				$this->attributeContainer->add(
															Array(
																	'name'			=>	'configurableObject',
																	'description'	=>	'The configurable object for which this configuration is meant.',
																	'value'			=>	$parentObject,
																	'exportable'	=>	FALSE,
																	'traversable'	=>	FALSE,
																	'readOnly'		=>	TRUE,
																	'validate'		=>	FALSE
															)
				);

				$this->configure();

				if($config !== NULL){

					$this->merge($config);

				}

			}

			public function getAttributeContainer(){

				return $this->attributeContainer;

			}

			abstract protected function configure();

			private function validateConfigurableObject($object){
					
				$parentObjectClass	=	strtolower(get_class($object));
				$configClass			=	strtolower(get_class($this));

				$configClass			=	substr($configClass,0,strrpos($configClass,'\\'));

				if($configClass !== $parentObjectClass){
			
					throw new \InvalidArgumentException("Invalid parent object, expected $configClass, got $parentObjectClass instead");
	
				}

				return $object;

			}

			public function validate($mode=NULL,$reValidate=FALSE){

				$mode		=	strtolower(trim($mode));

				$modes	=	Array(
										'soft',
										'hard',
										'extra',
										'none'
				);

				if(!in_array($mode,$modes)){

					throw new \InvalidArgumentException("Invalid validation mode specified: \"$validateMode\"");

				}

				if($validateMode=='none'){

					return;

				}

				$class	=	self::getValidatorClass();

				if(!class_exists($class)){

					throw new \LogicException("No validator class found for this configurable object");

				}

				switch($mode){

					case 'soft':

							if($this->isValidatedSoft){

								return TRUE;

							}

							return $this->isValidatedSoft		=	$class::softConfigValidation($this->getConfig());

					break;

					case 'hard':

						if($this->isValidatedHard && !$reValidate){

							return TRUE;

						}

						return $this->isValidatedHard		=	$class::hardConfigValidation($this->getConfig());
					break;

					case 'extra':

						if($this->isValidatedExtra && !$reValidate){

							return TRUE;

						}

						return $this->isValidatedExtra	=	$class::extraConfigValidation($this->getConfig());

					break;

					default:
						throw new \InvalidArgumentException("Unknown validation method");
					break;

				}

			}

			//The is validated method is a shortcut to check if the object has been validated in any way, soft or hard
			public function isValidated(){

				return $this->isValidatedSoft || $this->isValidatedHard || $this->isValidatedExtra;

			}

			//The isValidatedSoft method will tell you if the configurable object has been validated soft.
			public function isValidatedSoft(){

				return $this->isValidatedSoft;

			}

			//The isValidatedHard method will tell you if the configurable object has been validated the hard way.
			public function isValidatedHard(){

				return $this->isValidatedHard;

			}

			//The isValidatedHard method will tell you if the configurable object has passed extra validations
			public function isValidatedExtra(){

				return $this->isValidatedExtra;

			}

			public function getConfigurableObject(){

				return $this->attributeContainer->get('configurableObject')->getValue();

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

			public function merge(Config $config){

			}

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

				return $exportClass::export($this->attributeContainer);

			}

			public function import($file){

				$config		=	Adapter::factory($file);

				foreach($config->parse() as $key=>$value){

					$this->$key	=	$value;

				}

			}

			public function save($format="ini"){
			}

			public function __toString(){

				return $this->export("ini");

			}

		}

	}
