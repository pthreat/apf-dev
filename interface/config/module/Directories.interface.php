<?php

	namespace apf\iface\config\module{

		use \apf\core\Directory	as	Dir;

		interface Directories{

			public function setModulesDirectory(Dir $dir);
			public function getModulesDirectory();

		}

	}
