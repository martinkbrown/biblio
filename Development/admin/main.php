<html>
    <head>
        <link rel="stylesheet" type="text/css" href="admin.css" />
    </head>
    <body id="body">
<?php

define('FRONT_END','../');
define('BACK_END','./');
define('SETTINGS','settings/');

require_once FRONT_END . 'config.php';
require_once FRONT_END . LIB . 'AdminSettings.php';
require_once FRONT_END . LIB . 'SiteSettings.php';
$adminSettings = new AdminSettings(SETTINGS . 'admin_settings.txt');
$siteSettings = new SiteSettings(SETTINGS . 'site_settings.txt');

foreach($_POST as $key=>$value)
{
    $$key = $value;
}

foreach($_GET as $key=>$value)
{
    $$key = $value;
}

if(trim($page))
{
    require_once $page.".php";
}
else
{
    echo 'welcome admin';
}

?>

    </body>
</html>