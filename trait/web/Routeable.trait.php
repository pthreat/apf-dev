<?php

	namespace apf\trait\web{

		trait Routeable{

			public function addRouter(Router $router){

				$this->router	=	$router;
				return $this;

			}

			public function getRouter(){

				return parent::getRouter();

			}

		}

	}

