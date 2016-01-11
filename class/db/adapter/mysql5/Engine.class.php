<?php

	namespace apf\db\adapter{

		use \apf\db\Adapter	as BaseAdapter;

		class Mysql5 extends BaseAdapter{

			public function getRootUser(){

				return 'root';

			}

		}

	}

