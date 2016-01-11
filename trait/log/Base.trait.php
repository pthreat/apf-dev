<?php

	namespace apf\traits\log{

		use apf\iface\Log	as	LogInterface;

		trait Base{

			private $logObj	=	NULL;

			public function setLog(LogInterface $log){

				$log->setPrepend('['.__CLASS__.']');
				$this->logObj	=	$log;

				return $this;

			}

			public function getLog(){

				return $this->logObj;

			}

		}
		
	}

