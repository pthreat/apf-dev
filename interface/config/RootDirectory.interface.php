<?php

	namespace apf\iface\config{
	
		use \apf\core\Directory	as	Dir;

		interface RootDirectory{

			public function setRootDirectory(Dir $dir);
			public function getRootDirectory();

		}

	}
