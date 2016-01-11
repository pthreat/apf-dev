<?php

	namespace apf\core\project\plugin{

		use apf\core\Config	as	BaseConfig;

		class Config extends BaseConfig{

			use \apf\traits\kernel\Config;

			public function setAuthor($author){

				$this->author	=	$author;

			}

			public function getAuthor(){

				return $this->author;

			}

		}

	}

