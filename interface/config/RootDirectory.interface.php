<?php

	namespace apf\iface\config{
	
		use \apf\core\Directory	as	Dir;

		interface RootDirectory{

			public function setDirectory(Dir $dir);
			public function getDirectory();

		}

	}
