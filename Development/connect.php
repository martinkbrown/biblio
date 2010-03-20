<?php

require_once FRONT_END . LIB . 'DatabaseSettings.php';

$db = new DatabaseSettings(SETTINGS . "db_settings.txt");
$db->connect();

?>