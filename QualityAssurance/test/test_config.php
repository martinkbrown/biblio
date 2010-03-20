<?php

error_reporting(E_ERROR | E_WARNING);

define('PHPUNIT','../../../../bin/php/php5.3.0/PEAR/PHPUnit/');
define('FRONT_END','../../Development/');
define('LIB',FRONT_END.'lib/');
define('OBJECTS',FRONT_END.'objects/');
define('BACK_END',FRONT_END.'admin/');
define('TEST_CASES','../test_cases/');
define('TEST_RESULTS','../test_results/');

require_once PHPUNIT.'Framework.php';

?>
