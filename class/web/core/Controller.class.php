<?php

	namespace apf\web\core{

		use \apf\web\core\Request;
		use \apf\core\Configurable;

		class Controller extends Configurable{

			public function setRequest(Request $request){

				$this->request	=	$request;

			}

			public function getRequest(){

				return $this->request;

			}

			//Deberiamos obtener los templates dentro del directorio que se llame como el controlador

			/**
			 * Devuelve una vista por defecto que el nombre coincide con controller/accion.tpl
			 * por parametro el archivo deseado
			 *
			 * @example getViewInstance(['directory/foo.tpl'])
			 * @param array $templates
			 * @return View
			 * @throws \Exception
			 */
			public function getViewInstance(Array $templates=Array(),$useActionNameAsTpl=TRUE){

			}

		}

	}

