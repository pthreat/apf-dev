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
				return $name;

			}

			public function validateDescription($description){

				$description	=	trim($description);
				return $description;

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

			public function validateLayoutContainer(LayoutContainerInterface $container){

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

