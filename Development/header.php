<?php

    define('FRONT_END','./');
    define('BACK_END','admin/');
    require_once FRONT_END.'config.php';
    require_once LIB.'SiteSettings.php';
    $siteSettings = new SiteSettings();

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?php echo $siteSettings->siteTitle; ?></title>
        <link rel="stylesheet" type="text/css" href="style.css" />
    </head>
    <body>
        <div id="header_table">
        <table>
            <tr>
                <td id="bar">
                    <span id="title"><a href="<?php echo $siteSettings->siteUrl; ?>"><?php echo $siteSettings->siteTitle; ?></a></span>
                </td>
                <td id ="conf_bar">
                    <a href="conferences.php">Conferences</a>
                </td>
                <td>
                    <a href="journals.php">Journals</a>
                </td>
                <td>
                    <a href="authors.php">Authors</a>
                </td>
            </tr>
        </table>
       </div>
