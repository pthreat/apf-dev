<?php

	require '../../../boot.php';
	require './class/Engine.class.php';

	use \apf\core\Kernel;
	use \apf\core\kernel\Helper	as	KernelHelper;
	use \apf\core\kernel\Config	as	KernelConfig;

	use \apf\core\Log;
	use \apf\core\Directory			as	Dir;
	use \apf\core\log\File			as	FileLog;
	use \apf\core\File;


	$of		=	new \oldfashioned\Engine();
	$of->addTemplate('test/templates/test.tpl');
	$of->render();

