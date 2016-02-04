<?php

	namespace apf\iface\config{

		use \apf\iface\Log	as	LogInterface;

		interface Cli{

			public static function configure($config=NULL,LogInterface $log);

		}

	}
