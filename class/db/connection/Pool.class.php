<?php

	namespace apf\db\connection{

		use apf\net\connection\Pool	as	NetConnectionPool;
		use apf\db\Connection			as	DatabaseConnection;

		class Pool extends NetConnectionPool{

			const POOL_KEY	=	'database';

		}

	}
