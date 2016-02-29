<?php

	namespace apf\ui\form{

		use \apf\core\Kernel;
		use \apf\core\Configurable;
		use \apf\ui\Form;
		use \apf\ui\form\element\Factory	as	ElementFactory;

		class Factory{

			/**
			 * The createFromConfigurableObject form factory does the following: 
			 *
			 *	Gets a form instance through the self::getInstanceFromUIContext method according to the passed UI, 
			 *	if no UI is given, it will autodetermine what is the UI where it's been called from (cli, web, etc).
			 *
			 * Takes in a Configurable object as the first parameter (mandatory) and obtains it's attributes,
			 * this configurable object is used to obtain the configuration attributes/properties of said configurable object.
			 *
			 * Adds the proper form elements according to the previously mentioned properties/attributes 
			 *
			 * @params	\apf\core\Configurable	$object , An object that extends to \apf\core\Configurable class.
			 * @params	string						$ui A valid User Interface (cli, web, etc)
			 *
			 * @return	\apf\ui\form\Cli			If the autodetected context is cli or if the $ui argument is equal to cli
			 * @return	\apf\ui\form\Web			If the autodetected context is web or if the $ui argument is equal to web
			 *
			 */

			public static function createFromConfigurableObject(Configurable &$object,$ui=NULL){

				//Get a form instance according to the passed ui,
				//if the ui is NULL the UI will be autodetermined.

				//Create a new form instance of the corresponding form
				$form	=	self::getInstanceFromUIContext($ui);

				//Get the attributes from the configuration of the configurable object
				$attributes		=	$object->getConfig()->getAttributes();

				if(!sizeof($attributes)){

					throw new \InvalidArgumentException("No attributes were found, can not create from elements!");

				}

				foreach($attributes as $attribute){

					///////////////////////////////////
					//IMPORTANT:
					//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
					//How is the type determined?
					//Add ... multiple or singular characteristic to attributes?
					///////////////////////////////////
				
					$element		=	ElementFactory::getInstanceFromUIContext('input')
					->setName($attribute['name'])
					->setDescription($attribute['description']);

					$name			=	$attribute['name'];

					$callback	=	function($value) use ($name,&$object){

						return $object->getConfig()->$name	=	$value;

					};

					$element->onSetValue($callback);
					$form->addElement($element);

				}

				return $form;

			}

			/**
			 *Gets a form instance according to the context obtained from the within the Kernel::getSAPI method
			 */

			public static function getInstanceFromUIContext($ui=NULL){

				//If no $ui is specified, autodetect through the Kernel::getSAPI object, which is the UI context where this factory is being called from

				if($ui===NULL){

					$ui	=	Kernel::getSAPI()->getName();

				}

				$class	=	sprintf('\apf\ui\form\%s',ucwords($ui));

				//Corroborate that the given form UI does in fact exists

				if(!class_exists($class)){

					throw new \InvalidArgumentException("No UI named \"$ui\" was found, perhaps you'd like to contribute and create it? :)");

				}

				return new $class();

			}

		}

	}

