<?php

	namespace apf\iface\config\project{

		use \apf\core\project\Directories	as	ProjectDirectories;

		trait Directories{

			public function setDirectories(ProjectDirectories $projectDirectories);
			public function getProjectDirectories();

		}

	}
