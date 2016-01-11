<?php

namespace apf\util{

	class DateTime{

		public static function dateArrayFromSeconds($seconds){

			$diff = new \DateTime('@0');
			$date = new \DateTime(sprintf('@%s',$seconds));

			$array = Array(
					'days'		=>	$diff->diff($date)->format('%a'),
					'hours'		=>	$diff->diff($date)->format('%h'),
					'minutes'	=>	$diff->diff($date)->format('%i'),
					'seconds'	=>	$diff->diff($date)->format('%s')
			);

			return $array;

		}

		public static function getInstance($dateTime,$format="Y-m-d H:i:s"){

			if($dateTime instanceof $dateTime){

				return $dateTime;

			}

			$dateTime	=	\DateTime::createFromFormat($format,$dateTime);

			if(!$dateTime){

				throw new \Exception("Invalid date time");

			}

			return $dateTime;

		}

		public static function timeAgo($dateTime,$format="Y-m-d H:i:s",$full=FALSE,Array $string=Array()){

			$now	= new \DateTime();
			$ago	= self::getInstance($dateTime);
			$diff = $now->diff($ago);

			$diff->w = floor($diff->d / 7);
			$diff->d -= $diff->w * 7;

			if(empty($string)){

				$string = array(
									'y' => 'aÃo',
									'm' => 'mes',
									'w' => 'semana',
									'd' => 'dia',
									'h' => 'hora',
									'i' => 'minuto',
									's' => 'segundo',
				);

			}

			foreach ($string as $k => &$v) {

				if ($diff->$k) {

					$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
					continue;

				}

				unset($string[$k]);

			}

			if (!$full){

				$string = array_slice($string, 0, 1);

			}

			return $string ? 'Hace ' .implode(', ', $string) : 'Hace unos segundos';

		}

	}

}

