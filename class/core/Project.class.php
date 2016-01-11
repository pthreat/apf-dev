<?php

	namespace apf\core{

		use \apf\db\connection\Config	as	DatabaseConfig;
		use \apf\core\Cmd;

		class Project extends Configurable{

			public function create(){

				$this->validateConfig();

			}

			public function validateConfig(){
			}

		}

	}
