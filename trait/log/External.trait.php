<?php

	namespace apf\traits\log{

		use apf\iface\Log	as	LogInterface;

		trait External{

			use \apf\traits\log\Base;

			public function logDebug($text=NULL){

				if($this->logObj === NULL){

					return;

				}

				$this->logObj->debug($text);

			}

			public function logInfo($text=NULL){

				if($this->logObj===NULL){

					return;

				}

				return $this->logObj->info($text);

			}

			public function logWarning($text=NULL){

				if($this->logObj===NULL){

					return;

				}

				return $this->logObj->warning($text);

			}

			public function logError($text=NULL){

				if($this->logObj===NULL){

					return;

				}

				return $this->logObj->error($text);

			}

			public function logEmergency($text=NULL){

				if($this->logObj===NULL){

					return;

				}

				return $this->logObj->emergency($text,1,"red");

			}

			public function logSuccess($text=NULL){

				if($this->logObj===NULL){

					return;

				}

				return $this->logObj->success($text);
				
			}
	
		}

	}
