<?php

	namespace apf\net{

		class Port{

			private	$number	=	NULL;

			public function __construct($number=NULL){

				if(!is_null($number)){

					$this->setNumber($number);

				}

			}

			public function setNumber($num){

				if($num < 0 || $num > 65535){

					throw new \InvalidArgumentException(sprintf("Invalid port number: %d",$number));

				}

				$this->number	=	$num;

				return $this;

			}

			public function getNumber(){

				return $this->number;

			}

			public function __toString(){

				return sprintf('%s',$this->number);

			}

		}

	}
