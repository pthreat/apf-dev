<?php

	namespace apf\core\config{

		class Section{

			private	$name		=	NULL;
			private	$configs	=	Array();

			public function  __construct($name){

				$this->name	=	$name;

			}

			public function setName($name){

				$this->name	=	$name;
				return $this;

			}

			public function getName(){

				return $this->name;

			}

			public function addConfig(Config $config){

				$this->configs[]	=	$config;

			}

			public function getConfigs(){

				return $this->configs;

			}

		}
		
	}
