<?php

	namespace apf\ui\form\element{

		use \apf\core\Config											as BaseConfig;
		use \apf\iface\ui\form\element\attribute\Container	as	ElementAttributeContainerInterface;
		use \apf\ui\form\element\Layout;
		use \apf\ui\form\element\layout\Container				as	LayoutContainer;

		abstract class Config extends BaseConfig{

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

			public function validateLayoutContainer(LayoutContainer $container){

				return $container;

			}

			public function validateValueState($state){

				$states	=	Array('noval','success','error');

				if(!in_array($state,$states)){

					$msg	=	sprintf('Invalid element state: "%s" valid states are %s',$state,implode(',',$states));
					throw new \InvalidArgumentException($msg);

				}

				return $this;

			}

			protected function configure(){

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
								'exportable'	=>	FALSE
						)
				);

			}

		}

	}

