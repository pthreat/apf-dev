<?php

	namespace apf\iface\config{

		use \apf\core\Project;

		interface Projectable{

			public function setProject(Project $project);
			public function getProject();

		}

	}
