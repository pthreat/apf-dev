<?php

	namespace apf\traits\log{

		use apf\core\Log;

		trait Inner{

			use \apf\traits\log\Base;

			private function __logDebug($text=NULL){

				if($this->logObj === NULL){

					return;

				}

				$this->logObj->debug($text);

			}

			private function __logInfo($text=NULL){

				if($this->logObj===NULL){

					return;

				}

				return $this->logObj->info($text);

			}

			private function __logWarning($text=NULL){

				if($this->logObj===NULL){

					return;

				}

				return $this->logObj->warning($text);

			}

			private function __logError($text=NULL){

				if($this->logObj===NULL){

					return;

				}

				return $this->logObj->error($text);

			}

			private function __logEmergency($text=NULL){

				if($this->logObj===NULL){

					return;

				}

				return $this->logObj->emergency($text,1,"red");

			}

			private function __logSuccess($text=NULL){

				if($this->logObj===NULL){

					return;

				}

				return $this->logObj->success($text);
				
			}
	
		}

	}
