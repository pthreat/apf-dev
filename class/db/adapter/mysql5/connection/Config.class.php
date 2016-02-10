<?php

	namespace apf\db\adapter\mysql5\connection{

		use \apf\validate\String					as	StringValidate;
		use \apf\db\connection\Config				as	DatabaseConnectionConfig;
		use \apf\core\Log;
		use \apf\core\Cmd;

		class Config extends DatabaseConnectionConfig{

			public function getRootUsername(){

				return 'root';

			}

			public function setCharset($charset){

				$charset	=	trim($charset);

				if(empty($charset)){

					throw new \InvalidArgumentException("Invalid charset specified");

				}

				$this->charset	=	$charset;

				return $this;

			}

			public function getCharset(){

				return parent::getCharset();

			}

			public function setSocket($socket){

				$this->socket	=	$socket;
				return $this;

			}

			public function getSocket(){

				return parent::getSocket();

			}

			public function getNonExportableAttributes(){

				return Array();

			}

		}

	}

