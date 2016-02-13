<?php

	namespace apf\traits\config\cli{

		use \apf\core\Cmd;
		use \apf\iface\Log					as	LogInterface;
		use \apf\iface\config\Nameable	as	NameableInterface;

		trait Nameable{

			/**
			 * Configure a Nameable object's name
			 * Any Nameable configuration must have a configured name for it to make sense. 
			 *
			 * @params \apf\iface\config\Nameable	An object implementing the nameable interface
			 * @params \apf\iface\Log					A log interface so we can display messages and prompts in the command line.
			 * 
			 */

			public static function configureName(NameableInterface &$config,LogInterface &$log){

				do{

					Cmd::clear();

					$log->info('Configure name');
					$log->repeat('-',80,'light_purple');

					try{

						$config->setName(Cmd::readWithDefault('name>',$config->getName(),$log));

					}catch(\Exception $e){

						$log->error($e->getMessage());
						Cmd::readInput('Press enter to continue ...');

					}

				}while(!$config->getName());

			}

		}

	}

