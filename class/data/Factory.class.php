<?php

	namespace apf\data{

		abstract class Factory{

			public static function getFilter(Filter $filter=NULL){

				$class	=	const(self::FILTER);
				$filter	=	is_null($filter)	?	new $class()	:	clone($filter);

				return $filter;

			}

		}

	}

