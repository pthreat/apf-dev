<?php

	namespace apf\traits\config{

		trait ValueState{

			public function setValueState($state){

				$this->valueState	=	$state;
				return $this;

			}

			public function getValueState(){

				return parent::getValueState();

			}

		}

	}
