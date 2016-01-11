<?php

	namespace apf\generator\code\func{

		class Parameters{

			private	$parameters	=	Array();

			public function add(Parameter $parameter){

				$this->parameters[]	=	$parameter;

				return $this;

			}

			public function get(){

				return $this->parameters;

			}

			public function render(){

				return implode(',',$this->parameters);

			}

			public function __toString(){

				return $this->render();

			}

		}

	}
