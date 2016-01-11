<?php

	namespace apf\generator\code\class_{

		use apf\generator\code\Func;

		class Method extends Func{

			private	$scope		=	NULL;
			private	$isFinal		=	NULL;
			private	$isStatic	=	NULL;


			public function setIsStatic($boolean){

				$this->isStatic	=	(boolean)$boolean;
				return $this;

			}

			public function getIsStatic(){

				return $this->isStatic;

			}

			public function isStatic(){

				return (boolean)$this->isStatic;

			}

			public function isPrivate(){

				return $this->scope=='private';

			}

			public function isProtected(){

				return $this->scope == 'protected';

			}

			public function isPublic(){

				return $this->scope == 'public';

			}

			public function setIsFinal($final){

				$this->isFinal	=	(boolean)$final;
				return $this;

			}

			public function isFinal(){

				return (boolean)$this->isFinal;

			}

			public function getIsFinal(){

				return $this->isFinal;

			}

			public function setScope($scope){

				$scope	=	trim($scope);

				if(!in_array($scope,Array('public','protected','private'))){

					throw new \InvalidArgumentException('Invalid scope provided for method');

				}

				$this->scope	=	$scope;

				return $this;

			}

			public function getScope(){

				return $this->scope;

			}

			public function render($indent=NULL){

				$this->setHeader(
										sprintf('%s%s%s%s',
																$this->isFinal		? 'final ' : '',
																$this->scope		? "{$this->scope} " : 'public ',
																$this->isStatic	?	'static '	:	'',
																parent::getHeader()

										)
				);

				return parent::render($indent);

			}

			public function __toString(){

				return $this->render();

			}

		}

	}
