<?php

	namespace apf\iface{

		interface Log{

			public function error($message);
			public function warning($message);
			public function info($message);
			public function emergency($message);
			public function debug($message);
			public function success($message);
			public function logArray(Array $array,$separator=',',$type=0);
			public function log($msg=NULL,$type=0);
			public function repeat($string,$times,$type=0);

		}

	}

?>
