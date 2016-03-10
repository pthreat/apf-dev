<?php

	namespace apf\traits\config{

		trait onSetValue{

			public function onSetValue(Callable $callback){

				$this->onSetValue	=	$callback;
				return $this;

			}

			public function getOnSetValue(){

				return parent::getOnSetValue();

			}

		}

	}
