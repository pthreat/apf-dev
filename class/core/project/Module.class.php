<?php

	namespace apf\core\project{

		use \apf\core\Configurable;
		use \apf\iface\Log								as	LogInterface;
		use \apf\core\project\module\Config			as	ModuleConfig;
		use \apf\core\project\module\Sub;
		use \apf\core\project\module\sub\Config	as	SubConfig;
		use \apf\core\Directory							as	Dir;

		class Module extends Configurable{

			private	$project	=	NULL;

			public function listSubs(){

				if(!$this->isValidated()){

					throw new \LogicException('Can not list subs without a valid directory for this module');

				}

				return $this->config->getDirectory()->getIterator();

			}

		}

	}

