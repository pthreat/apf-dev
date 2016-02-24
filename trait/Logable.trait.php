<?php

	namespace apf\traits{

		use apf\iface\Log	as	LogInterface;

		trait Logable{

			private	$log	=	NULL;

			public function setLog(LogInterface $log){

				$this->log	=	$log;

			}

			public function getLog(){

				return $this->log;

			}

		}

	}
