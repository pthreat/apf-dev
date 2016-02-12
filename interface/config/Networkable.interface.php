<?php

	namespace apf\iface\config{

		use \apf\net\Connection	as	NetworkConnection;

		interface Networkable{

			public function addConnection(NetworkConnection $connection);
			public function getConnection($type,$name);		
			public function setConnections(Array $connections);
			public function getConnections();
			public function hasConnections();
			public function hasConnectionsOfType($type);

		}

	}
