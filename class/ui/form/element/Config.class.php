<?php

	namespace apf\ui\form\element{

		use \apf\core\Config											as BaseConfig;
		use \apf\iface\ui\form\element\attribute\Container	as	ElementAttributeContainerInterface;
		use \apf\ui\form\element\Layout;
		use \apf\ui\form\element\layout\Container				as	LayoutContainer;

		abstract class Config extends BaseConfig{

			public function setAttributeContainer(ElementAttributeContainerInterface $container){

				$this->attributeContainer	=	$container;
				return $this;

			}

			public function getAttributeContainer(){

				return parent::getAttributeContainer();

			}

			public function validateLayoutContainer(LayoutContainer $container){

				return $container;

			}

			public function validateValue($value){

				if($this->onSetValue !== NULL){

					try{

						$callback	=	&$this->onSetValue;
						$callback($value);

					}catch(\Exception $e){

						$this->setValueState('error');
						throw new \Exception($e->getMessage());

					}

				}

				$this->value	=	$value;
				$this->setValueState('success');

				return $this;

			}

			public function validateValueState($state){

				$states	=	Array('noval','success','error');

				if(!in_array($state,$states)){

					$msg	=	sprintf('Invalid element state: "%s" valid states are %s',$state,implode(',',$states));
					throw new \InvalidArgumentException($msg);

				}

				return $this;

			}

			protected function __configureAttributes(){

				parent::addAttribute(
											Array(
													'name'			=>	'name',
													'description'	=>	'Element name'		
											)
				);

				parent::addAttribute(
											Array(
													'name'			=>	'description',
													'description'	=>	'Element description'
											)
				);

				parent::addAttribute(
											Array(
													'name'			=>	'value',
													'description'	=>	'Element value'
											)
				);

				parent::addAttribute(
											Array(
													'name'			=>	'valueState',
													'description'	=>	'Element value state',
													'exportable'	=>	FALSE
											)
				);


			}

		}

	}

