<?php

	namespace apf\db\adapter{

		use \apf\db\Connection	as	DatabaseConnection;
		use \apf\core\Config		as	BaseConfig;

		class Config extends BaseConfig{

			public function setConnection(DatabaseConnection $connection){

				$this->connection	=	$connection;
				return $this;

			}

			public function getConnection(){

				return parent::getConnection();

			}

			public function setEscapeValue($value){

				$this->escapeValue	=	$value;
				return $this;

			}

			public function getEscapeValue(){

				return parent::getEscapeValue();

			}

			public function setCommentOpenings(Array $openings){

				$this->commentOpenings	=	$openings;
				return $this;

			}

			public function getCommentOpenings(){

				return parent::getCommentOpenings();

			}

			public function setCommentClosings(Array $closings){

				$this->commentClosings	=	$closings;
				return $this;

			}

			public function getCommentClosings(){

				return parent::getCommentClosings();

			}

			protected function getNonExportableAttributes(){

				return Array();

			}

		}	

	}

