<?php

	namespace apf\net\adapter{

		use \apf\core\Config		as	BaseConfig;
		use \apf\net\Connection	as	NetConnection;

		abstract class Config extends BaseConfig{

			public function setConnection(NetConnection $connection){

				$this->connection	=	$connection;
				return $this;

			}

			public function getConnection(){

				return parent::getConnection();

			}

		}

	}

