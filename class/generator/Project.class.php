<?php

	namespace apf\generator{

		use \apf\db\connection\Config	as	DatabaseConfig;
		use \apf\core\Cmd;

		class Project{

			private	$dir	=	NULL;
			private	$log	=	NULL;

			public function __construct(Dir $dir,Log $log){

				$this->dir	=	$dir;
				$this->log	=	$log;

			}

			public function createAppDirectories(){

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

			public static function createProjectDirectories(){

				$folders	=	Array(
										'config',
										'config/databases',
										'config/namespaces'
				);

				return (new Dir($baseDir))
				->createTree($folders);

			}

			public function configureDatabases($saveAs=NULL){

				$flag	=	TRUE;

				do{

					$config	=	DatabaseConfig::consoleConfig($this->log);

					$log->success('[Database settings]');
					$log->info($config);

					if(Cmd::yesNo('Would you like to test this connection?')){

						try{

							$config->testConnection();
							$log->success('Connection succesfully established!');

							$config->save(
												new File(
															sprintf(
																		'%s%s%s%s',
																		$dir,
																		DIRECTORY_SEPARATOR,
																		$config->getDatabase(),
																		$config->isProduction() ? '_prod' : '_dev'
															)
												)
							);

							$log->info(sprintf('Saving config file to %s ...',$dir));

						}catch(\Exception $e){

							$log->error('Connection Failed!');
							$log->warning($e->getMessage());
							$log->debug($e->getTraceAsString());

							if(Cmd::yesNo('Would you like to try again and reconfigure?',$log)){

								continue;

							}

						}

					}

					$flag	=	Cmd::yesNo('Configure another database?',$log);

				}while($flag);

			}

		}

	}

