<?php

	namespace apf\generator{

		use \apf\core\Log;
		use \apf\core\Directory	as Dir;
		
		class Structure{

			public static function generateAppDirectories($baseDir, Log $log=NULL){

				$folders	=	Array(
										'config/namespaces',
										'template',
										'fragment',
										'public_html',
										'lib'
				);

				return (new Dir($baseDir))
				->createTree($folders);

			}

			public static function generateProjectDirectories($baseDir,Log $log=NULL){

				$folders	=	Array(
										'config',
										'config/databases',
										'config/namespaces'
				);

				return (new Dir($baseDir))
				->createTree($folders);

			}

		}

	}

