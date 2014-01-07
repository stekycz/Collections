<?php

define('TEST_DIR', __DIR__);
define('LIBS_DIR', TEST_DIR . '/../vendor');

// Composer autoloading
$autoloader = require_once LIBS_DIR . '/autoload.php';
$autoloader->add("", TEST_DIR);

function run(Tester\TestCase $testCase)
{
	$testCase->run(isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : NULL);
}

return $autoloader;
