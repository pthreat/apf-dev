<?php

	namespace apf\db{

		use \apf\iface\Log					as	LogInterface;
		use \apf\net\Connection				as NetworkConnection;
		use \apf\net\connection\Config	as ConnectionConfig;
		use \apf\db\connection\Config		as	DatabaseConnectionConfig;
		use \apf\core\Cmd;
		use \apf\net\Host;
		use \apf\core\Config;

		abstract class Connection extends NetworkConnection{

			use \apf\traits\log\Inner;

			/**
			 * Gets the proper database adapter for this connection through the class configuration.
			 * The adapter type is specified in the configuration object for this class under the adapter property.
			 * This method works like a small "factory" (if you would wish to call it that way).
			 */

			final public function getAdapter(LogInterface $log=NULL){

				$adapterClass	=	sprintf('apf\\db\\adapter\\%s\\Adapter',strtolower($this->getConfig()->getAdapter()));

				return $adapterClass::getInstance($this,$log);

			}

			protected function ___validateConfig(){

				$config	=	$this->getConfig();

				if(!$config->getAdapter()){

					throw new \InvalidArgumentException("No database adapter specified");

				}

				if(!in_array($config->getAdapter(),Adapter::listAvailable())){

					$msg	=	sprintf('Invalid database adapter "%s" valid adapters: %s',$adapter,implode(',',$adapters));
					throw new \InvalidArgumentException($msg);

				}

				if(!$config->getUsername()){

					throw new \InvalidArgumentException("No database username specified");

				}

				if(!$config->getDatabase()){

					throw new \InvalidArgumentException("No database name specified");

				}

				if(is_null($config->getPassword())){

					throw new \InvalidArgumentException("No database password specified, even if it has no password set it as empty ''.");

				}

				if(is_null($config->getCharset())){

					throw new \InvalidArgumentException("No database charset specified");

				}

			}


			//Specific connection configurations that depend on each database adapter type
			abstract protected static function dbConnectionInteractiveConfig(LogInterface &$log,Config &$config);

			/**
			 * Interactive console configuration of a database connection
			 * Returns a configured database connection, according to the selected engine.
			 */

			public static function interactiveConfig(LogInterface $log,Config $config=NULL){

				/////////////////////////////////////////////////////////////////////////////////////////////
				//Select the database adapter first. Each adapter might have different defaults, for instance
				//the default port on a mysql adapter would be 3306 while it would be something completely 
				//different for, say,  SQL server.
				//For this reason it is necessary to select the database adapter first before anything else.
				/////////////////////////////////////////////////////////////////////////////////////////////

				$adapter		=	Cmd::select(Adapter::listAvailable(),"ENGINE>",$log);
				$config		=	sprintf('\\apf\\db\\adapter\\%s\\connection\\Config',$adapter);
				$connection	=	sprintf('\\apf\\db\\adapter\\%s\\Connection',$adapter);

				/////////////////////////////////////////////////////////////////////////////////////////////
				//Configure the network connection with the defaults obtained from the selected adapter
				/////////////////////////////////////////////////////////////////////////////////////////////

				$config		=	parent::interactiveConfig($log,$config::getDefaultInstance());

				/////////////////////////////////////////////////////////////////////////////////////////////
				//Configure database specific and general configurations (common to all database connections)
				/////////////////////////////////////////////////////////////////////////////////////////////

				//Configure Username
				do{

					$flag	=	FALSE;

					try{

						$config->setUsername(Cmd::readwithDefault('Username:',$config->getUsername(),$log));

						if($config->getUsername()==$config->getRootUsername() && $config->isProduction()){

							$log->warning("You are using the username \"{$config->getRootUsername()}\" on a production database.");
							$log->warning("This is a user with elevated privileges, database wise.");
							$log->warning("APF is a secure framework; However nothing is 100%% guaranteed in terms of what you ");
							$log->warning("or your team do. By using the root user and having a security problem someone could take ");
							$log->warning("FULL CONTROL of your server(s)");

							if(!Cmd::yesNo("Are you sure that you want to use the {$config->getRootUsername()} user?",$log)){

								$flag	=	FALSE;
								continue;

							}

						}

						$flag	=	TRUE;

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while($flag===FALSE);

				//Configure Password
				$config->setPassword(Cmd::readInput('Password:',$log));

				$connection::dbConnectionInteractiveConfig($log,$config);

				//Configure Database name, i.e which database is to be used
				//
				//@TODO: Fetch all databases available for the entered username and password 
				//Show a nice select prompt.
				do{

					try{

						$config->setDatabase(Cmd::readInput('Database name:',$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());

					}

				}while(!$config->getDatabase());

				$config->setIsProduction(Cmd::yesNo('Is this a production database?',$log));

				$config->setEnableLogging(Cmd::yesNo('Do you wish to enable query logging for this connection?',$log));

				return new $connection($config);

			}

			public function __clone(){

				throw(new \Exception("Cloning database connections is not possible"));

			}

		}

	}

