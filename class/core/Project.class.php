<?php

	namespace apf\core{

		use \apf\iface\Log						as	LogInterface;
		use \apf\core\project\Config;
		use \apf\core\project\Module			as	ProjectModule;
		use \apf\core\project\module\Config	as	ModuleConfig;
		use \apf\core\project\Config			as	ProjectConfig;

		use \apf\web\asset\Javascript			as	JSAsset;
		use \apf\web\asset\Css					as	CSSAsset;

		use \apf\web\Asset;

		use \apf\core\Cmd;
		use \apf\core\Directory					as	Dir;

		class Project extends Configurable{

			const CONFIG_DIRECTORY	=	'config';

			public function create(LogInterface $log){

				$log->info("Creating project {$config->getName()}");
			
				$log->info('Creating project directory ...');
				$this->config->getDirectory()->create();

				$log->info('Creating modules directory ...');
				$this->config->getModulesDirectory()->create();

				$log->info('Creating modules ...');

				foreach($this->config->getModules() as $module){

					$module->create($log);

				}

				$log->success('Done creating project');

			}

		}

	}

