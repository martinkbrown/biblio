<?php

require_once FRONTEND.'lib/DatabaseSettings.php';

$db = new DatabaseSettings(BACKEND."dbConnection.txt");
$db->connect();

?>