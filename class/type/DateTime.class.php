<?php

	namespace apf\type{

		use	\apf\core\DI;

		class DateTime extends \DateTime{

			private	$time	=	NULL;

			public function __construct($time='now',\DateTimeZone $timezone=NULL){

				if(is_null($timezone)){

					$timezone	=	new \DateTimeZone(DI::get("config")->framework->timezone);

				}

				$this->time	=	$time;

				parent::__construct($time,$timezone);

			}

			public function format($format=NULL){

				if(is_null($format)){

					$cfg	=	DI::get("config")->framework;

					if(isset($cfg->date_format)){

						$format	=	$cfg->date_format;

					}

				}

				return parent::format($format);

			}


			public function __toString(){

				return (string)$this->format();

			}

		}

	}

?>
