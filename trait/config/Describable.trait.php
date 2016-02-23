<?php

	namespace apf\traits\config{

		trait Describable{

			public function setDescription($description){

				$description	=	trim($description);

				if(empty($description)){

					throw new \InvalidArgumentException("Description can not be empty");

				}

				$this->description	=	$description;

				return $this;

			}

			public function getDescription(){

				return parent::getDescription();

			}

		}

	}
