<?php

	namespace apf\traits\config{

		trait Value{

			public function setValue($value){

				$value	=	trim($value);

				if(empty($value)){

					throw new \InvalidArgumentException("Value can not be empty");

				}

				$this->value	=	$value;

				return $this;

			}

			public function getValue(){

				return parent::getValue();

			}

		}

	}
