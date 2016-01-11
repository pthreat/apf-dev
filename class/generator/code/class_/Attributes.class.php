<?php

	namespace apf\generator\code\func{

		class Attributes{

			private	$attributes	=	Array();

			public function add($scope,$name,$hasDefault=FALSE,$default=NULL){

				$this->attributes[]	=	new Attribute($scope,$name,$hasDefault,$default);

				return $this;

			}

			public function get(){

				return $this->attributes;

			}

			public function render(){

				return implode(',',$this->attributes);

			}

			public function __toString(){

				return $this->render();

			}

		}

	}

