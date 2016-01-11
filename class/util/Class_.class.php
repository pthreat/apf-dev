<?php

		namespace apf\util{

			class Class_{
				
				public static function removeNamespace($class,$validate=TRUE){

					if($validate){	

						\apf\validate\Class_::mustExist($class,"Class $class doesn't exists");

					}

					$rc	=	new \ReflectionClass($class);
					return $rc->getShortName();

				}

				public static function getNamespace($class,$validate=TRUE){

					if($validate){	

						\apf\validate\Class_::mustExist($class,"Class $class doesn't exists");

					}

					$rc	=	new \ReflectionClass($class);
					return $rc->getNamespaceName();

				}

				public static function getPublicMethods($class,$noMagic=TRUE){

					\apf\validate\Class_::mustExist($class,"Class $class doesn't exists");

					$rc		=	new \ReflectionClass($class);
					$methods	=	$rc->getMethods(\ReflectionMethod::IS_PUBLIC);
					$data		=	Array();

					$magic	=	Array(
												"__construct",
												"__wakeup",
												"__call",
												"__invoke",
												"__sleep",
												"__destruct",
												"__get",
												"__set",
												"__serialize",
												"__toString",
												"__set_state",
												"__clone",
												"__debugInfo",
												"__callStatic",
												"__isset"
					);

					foreach($methods as $m){

						if($noMagic && in_array($m,$magic)){

							continue;

						}

						$data[]	=	$m->getName();

					}

					return $data;

				}

			}

		}

?>
