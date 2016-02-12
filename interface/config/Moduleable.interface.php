<?php

	namespace apf\iface\config{

		use \apf\core\Directory	as	Dir;
		use \apf\core\project\Module;

		interface Moduleable{

			public function setModulesDirectory(Dir $dir);
			public function getModulesDirectory();
			public function addModule(Module $module);
			public function getModule($name);
			public function hasModule($name);
			public function hasModules();

		}

	}
