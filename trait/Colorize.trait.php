<?php
	
	namespace apf\traits{

		trait Colorize{

			private $colors	=	Array();

			public function setSuccessColor($color){

				$this->colors['success']	=	$color;
				return $this;

			}

			public function getSuccessColor(){

				return $this->colors['success'];

			}

			public function setWarningColor($color){

				$this->colors['warning']	=	$color;
				return $this;

			}

			public function getWarningColor(){

				return $this->colors['warning'];

			}

			public function setErrorColor($color){

				$this->colors['error']	=	$color;
				return $this;

			}

			public function getErrorColor()

				return $this->colors['error'];

			}

			public function setInfoColor($color){

				$this->colors['info']	=	$color;

			}

			public function getInfoColor()

				return $this->colors['info'];

			}


		}

	}
