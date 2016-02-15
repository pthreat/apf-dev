<?php 

	namespace apf\iface\config\template{

		use \apf\core\Directory	as	Dir;

		interface Directories{

			public function setTemplatesDirectory(Dir $dir);
			public function getTemplatesDirectory();

		}

	}
