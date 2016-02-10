<?php

	namespace apf\net{

		use \apf\core\Configurable;
		use \apf\core\Log;
		use \apf\core\Cmd;
		use \apf\core\Config;
		use \apf\iface\Log					as	LogInterface;
		use \apf\net\connection\Config	as	ConnectionConfig;

		abstract class Connection extends Configurable{

			use \apf\traits\log\Inner;

			private	$connection		=	NULL;

			public function isConnected(){

				return !is_null($this->connection);

			}

			public function connect(){

				if($this->isConnected()){

					$this->__logDebug("Connection already established");

					return $this->connection;

				}

				if(!$this->isConfigured()){

					throw new \LogicException("Can not connect with a non-configured connection class, please provide a configuration");

				}

				$this->__logDebug("Attempting to connect using the following settings");
				$this->__logDebug($this->getConfig());

				return $this->connection	=	$this->__connect();

			}

			//Abstract methods

			abstract protected function __connect();
			abstract public function __toString();

		}

	}

