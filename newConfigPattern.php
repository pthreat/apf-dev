<?php

	abstract class Config{

		//Returns setters and getters according to the values returned by 
		//__getAttributes.

		public function getMethods(){
		}

		public function __set($name,$value){

		}

		public function __get($name){
				
		}

		public function getAttributes(){

			return $this->__getAttributes();

		}

		abstract public function __getAttributes();

	}

	class PersonConfig extends Config{

		public function validateName($value){

		}

		public function __getAttributes(){

			//Each attribute validation will be handled by a validate$attrName method
			//That will be located in this very same class
			return Array(
								'name'=>Array(
													'description'	=>'Person name'
													'validate'		=>	TRUE
								)
			);

		}

	}

	abstract class Configurable{

		public function __construct(Config $config){

			$this->setConfig($config);

		}

	}

	class Person extends Configurable{

		public function setConfig(PersonConfig $config){

			$this->config	=	$config;
			return $this;

		}

	}
