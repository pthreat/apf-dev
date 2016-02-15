<?php

	namespace apf\iface\config\sub{

		use \apf\core\Directory	as	Dir;

		interface Directories{

			public function setSubsDirectory(Dir $dir);
			public function getSubsDirectory();

		}

	}
