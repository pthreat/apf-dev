<?php

	namespace apf\web\core\asset{

		use apf\core\Config	as	BaseConfig;

		abstract class Config extends BaseConfig{

			public function setName($name){

				$this->name	=	$name;
				return $this;

			}

			public function getName(){

				return parent::getName();

			}

			public function setURI($uri){

				$this->uri	=	$uri;

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

		}

	}
	
