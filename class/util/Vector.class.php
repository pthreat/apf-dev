<?php

	namespace apf\util{

		class Vector{

			public static function trimValues(Array &$array){

				foreach($array as &$elm){

					$elm	=	is_string($elm)	?	trim($elm)	:	$elm;

				}

			}

		}		

	}
