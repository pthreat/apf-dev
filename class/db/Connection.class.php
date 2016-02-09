<?php

	namespace apf\db{

		use \apf\iface\Log					as	LogInterface;
		use \apf\net\Connection				as NetworkConnection;
		use \apf\net\connection\Config	as ConnectionConfig;
		use \apf\db\connection\Config		as	DatabaseConnectionConfig;
		use \apf\core\Cmd;
		use \apf\net\Host;
		use \apf\core\Config;

		class Connection extends NetworkConnection{

			use \apf\traits\log\Inner;

			public function __clone(){

				throw(new \Exception("Cloning database connections is not possible"));

			}

		}

	}

