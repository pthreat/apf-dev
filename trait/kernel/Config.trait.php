<?php

	namespace apf\traits\kernel{

		trait Config{

			public function setMemoryLimit($limit){

				$this->limit	=	$limit;
				return $this;

			}

			public function setErrorReporting($level){

				$this->errorReporting	=	$level;
				return $this;

			}

			public function setDevelopmentMode($boolean){

				$this->devMode	=	(boolean)$boolean;
				return $this;

			}

			public function setDisplayErrors($boolean){

				$this->displayErrors	=	(boolean)$boolean;
				return $this;

			}

		}

	}

