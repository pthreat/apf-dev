<?php

	namespace apf\iface\config\web{

		use \apf\web\core\Route;

		interface Routeable{

			public function addRoute(Route $asset);
			public function getRoute($name);
			public function hasRoute($name);
			public function getRoutes();

		}

	}
