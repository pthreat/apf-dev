<?php

	namespace apf\net{

		use \apf\validate\String	as	StringValidate;

		class Host{

			private	$name	=	NULL;

			public function __construct($hostName=NULL,$validate=FALSE){

				if(!is_null($hostName)){

					$this->setName($hostName);

				}

			}

			public function setName($hostName){

				$this->name	=	StringValidate::mustBeNotEmpty($hostName,$useTrim=TRUE,'Host name can not be empty');
				return $this;

			}

			public function getName(){

				return $this->name;

			}

			public function __toString(){

				return sprintf('%s',$this->name);

			}

		}

	}

