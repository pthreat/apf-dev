<?php

	namespace apf\iface{

		use apf\iface\Log	as	LogInterface;

		interface Logable{

			public function setLog(LogInterface $log);
			public function getLog();

		}

	}
