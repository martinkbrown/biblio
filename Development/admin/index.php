<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

define('FRONTEND','../');
define('BACKEND','./');
require_once FRONTEND.'config.php';

$page = $_GET['page'];
require_once $page.".php";

?>
