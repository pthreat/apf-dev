<?php

	namespace apf\generator\code{

		use apf\generator\code\Block;
		use apf\generator\code\Class_ as ClassBlock;

		class Namespace_ extends Block{

			private	$name			=	NULL;
			private	$aliases		=	Array();
			private	$classes		=	Array();

			public function setName($name){

				$this->name	=	$name;
				return $this;

			}

			public function getName(){

				return $this->name;

			}

			public function addClass(ClassBlock $class){

				$this->classes[]	=	$class;
				return $this;

			}

			public function addAlias($class,$as=NULL){

				foreach($this->aliases as $alias){

					if($alias['class']==$class){

						throw new \InvalidArgumentException("Class \"$class\" already exists as a class alias");

					}

				}

				$this->aliases[]	=	Array(
													'class'	=>	$class,
													'alias'	=>	$as
				);

				return $this;

			}

			public function getAliases(){

				return $this->aliases;

			}

			public function reset(){

				$this->lines	=	Array();
				return $this;

			}

			public function render($indent=NULL){

				$header	=	parent::getHeader();
				$this->setHeader(sprintf('namespace %s',is_array($header) ? implode('\\',$header) : $header));

				foreach($this->aliases as $alias){

					if($alias['alias']){

						parent::l(sprintf('use %s as %s;',$alias['class'],$alias['alias']));
						continue;

					}

					parent::l(sprintf('use %s;',$alias['class']));

				}

				foreach($this->classes as $class){

					parent::l($class);

				}

				return parent::render($indent);

			}

		}

	}

