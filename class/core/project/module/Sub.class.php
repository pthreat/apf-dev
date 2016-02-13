<?php

	namespace apf\core\project\module{

		use \apf\iface\Log	as	LogInterface;
		use \apf\core\Configurable;

		class Sub extends Configurable{

			public function create(LogInterface $log){

				$this->validateConfig();

			}

			public function delete(LogInterface $log){
				
			}

			public function update(LogInterface $log){

			}

			public function validateConfig(){
			}


			public function listControllers(){

				return (new Dir($this->config->getControllersDirectory()))->getIterator();

			}

		}

	}

