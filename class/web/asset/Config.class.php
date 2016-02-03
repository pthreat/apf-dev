<?php

	namespace apf\web\asset{

		use apf\core\Config			as	BaseConfig;
		use \apf\validate\String	as	StringValidate;

		abstract class Config extends BaseConfig{

			public function setName($name){

				$this->name	=	StringValidate::mustBeNotEmpty($name,$trim=TRUE,'Asset name can not be empty');
				return $this;

			}

			public function getName(){

				return parent::getName();

			}

			abstract public function getType();

			public function setDescription($description){

				$this->description	=	StringValidate::mustBeNotEmpty($name,$trim=TRUE,'Asset description can not be empty');
				return $this;

			}

			public function getDescription(){

				return parent::getDescription();

			}

			public function setURI($uri){

				$this->uri	=	StringValidate::mustBeNotEmpty($uri,$trim=TRUE,'Asset URI must be not empty');;
				return $this;

			}

			public function getURI(){

				return parent::getURI();

			}

			public function isLocal(){

				return is_file(parent::getURI());

			}

			public function isRemote(){

				return !$this->isLocal();

			}

			public function loadInHead($boolean){

				$this->loadInHead	=	(boolean)$boolean;
				return $this;

			}

			public function getLoadInHead(){

				return parent::loadInHead();

			}

			public function loadInFooter($boolean){

				$this->loadInFooter	=	(boolean)$boolean;
				return $this;

			}

			public function getLoadInFooter(){

				return parent::loadInFooter();

			}

			public function getNonExportableAttributes(){

				return Array();

			}

		}

	}
	
