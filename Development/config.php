<?php

error_reporting(E_ERROR | E_WARNING);

define('LIB','lib/');
define('OBJECTS','objects/');
define('FORMS','forms/');
define('SETTINGS',BACK_END . 'settings/');
define('TP','');
define('RECAPTCHA','recaptcha/');

require_once LIB.'Utilities.php';
require_once LIB.'MySql.php';
require_once LIB.'Date.php';
require_once LIB.'Recordset.php';
require_once LIB.'FormValidator.php';
require_once LIB.'Settings.php';
require_once LIB.'Grid.php';
require_once LIB.'DropDownList.php';
require_once 'connect.php';

$input = array_merge($_GET,$_POST);

foreach($input as $key=>$value)
{
    $GLOBALS[trim($$key)] = $value;
}



?>