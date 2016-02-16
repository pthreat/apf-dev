<?php 

	namespace apf\iface\config\fragment{

		use \apf\core\Directory	as	Dir;

		interface Directories{

			public function setFragmentsDirectory(Dir $dir);
			public function getFragmentsDirectory();

		}

	}	
