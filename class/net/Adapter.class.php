<?php

	namespace apf\net{

		use \apf\net\Connection as NetConnection;	
		use \apf\core\Configurable;
		use \apf\traits\Inner;

		abstract class Adapter extends Configurable{

			public function connect(){

				$this->info("Attempting to connect ...");
				$this->__connect();

			}

			abstract protected function __connect();

		}

	}
