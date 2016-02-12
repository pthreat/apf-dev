<?php

	namespace apf\iface\config\web{

		use \apf\web\core\Controller;
		use \apf\core\Directory	as	Dir;

		interface Controllable{

			public function setControllersDirectory(Dir $dir);
			public function getControllersDirectory();
			public function addController(Controller $controller);
			public function getController($name);
			public function hasController($name);
			public function hasControllers();

		}

	}
