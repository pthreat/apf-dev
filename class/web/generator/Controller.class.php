<?php

	namespace apf\web\generator{

		use apf\generator\_Class	as	ClassGenerator;

		class Controller extends ClassGenerator{

			public function addAction($actionName=NULL,Array $parameters=Array()){

				if(in_array($actionName,$this->actions)){

					if($throw){

						throw new \InvalidArgumentException("Action \"$actionName\" was already added");

					}

				}

				$this->actions[]	=	Array(
													'name'			=>	$actionName,
													'parameters'	=>	$parameters
				);

				return $this;

			}

			public function hasAction($actionName){

				return in_array($actionName,$this->actions);

			}

			public function getActions(){

				return $this->actions;

			}

			public function render(){
				
			}

			public function __toString(){

				return $this->render();

			}

		}

	}

