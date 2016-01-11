<?php

	namespace apf\web\core{

		class Kernel extends \apf\core\Kernel{

			private static $dispatcher	=	NULL;

			public static function boot($dispatch=TRUE){

				parent::boot();

				self::$dispatcher	=	new \apf\web\core\Dispatcher();

				if($dispatch){

					return self::$dispatcher->dispatch();

				}

			}

			public static function getDispatcher(){

				return self::$dispatcher;

			}

		}

	}

?>
