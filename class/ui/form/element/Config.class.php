<?php

	namespace apf\ui\form\element{

		use \apf\core\Config											as	BaseConfig;
		use \apf\iface\ui\form\element\attribute\Container	as	ElementAttributeContainerInterface;
		use \apf\ui\form\element\Layout;
		use \apf\iface\ui\form\element\layout\Container		as	LayoutContainerInterface;
		use \apf\iface\ui\form\element\Config					as	ElementConfigInterface;

		abstract class Config extends BaseConfig implements ElementConfigInterface{

			public function validateName($name){

				$name	=	trim($name);

				if(empty($name)){

					throw new \InvalidArgumentException("Element name can not be empty");

				}

				return $name;

			}

			public function validateDescription($description){

				$description	=	trim($description);

				if(empty($description)){

					throw new \InvalidArgumentException("Element description can not be empty");

				}

				return $description;

			}	

			public function validateLayoutContainer(LayoutContainerInterface $container){

				if(!$container->getErrorLayout()){ 

					throw new \InvalidArgumentException("Your layout container is missing an ->error<- layout");

				}

				if(!$container->getSuccessLayout()){ 

					throw new \InvalidArgumentException("Your layout container is missing a ->success<- layout");

				}

				if(!$container->getNoValueLayout()){ 

					throw new \InvalidArgumentException("Your layout container is missing a ->no value<- layout");

				}

				return $container;

			}

			public function validateValueState($state){

				$states	=	Array('noval','success','error');

				if(!in_array($state,$states)){

					$msg	=	sprintf('Invalid element state: "%s" valid states are %s',$state,implode(',',$states));
					throw new \InvalidArgumentException($msg);

				}

				return $state;

			}

			protected function __configure(){

				$this->getAttributeContainer()
				->add(
						Array(
								'name'			=>	'name',
								'description'	=>	'Element name'		
						)
				)
				->add(
						Array(
								'name'			=>	'description',
								'description'	=>	'Element description'
						)
				)
				->add(
						Array(
								'name'			=>	'value',
								'description'	=>	'Element value'
						)
				)
				->add(
						Array(
								'name'			=>	'valueState',
								'description'	=>	'Element value state',
								'value'			=>	'noval',
								'exportable'	=>	FALSE
						)
				);

			}

		}

	}

