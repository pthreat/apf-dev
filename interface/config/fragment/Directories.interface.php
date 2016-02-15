<?php 

	namespace apf\iface\config\fragments{

		use \apf\core\Directory	as	Dir;

		interface Directories{

			public function setFragmentsDirectory(Dir $dir);
			public function getFragmentsDirectory();

		}

	
