<?php

    define('FRONT_END','./');
    define('BACK_END','admin/');
    require_once FRONT_END . 'config.php';

    require_once OBJECTS . 'Conference.php';

    $conference = new Conference();
    $conference->loadApprovedConferences();

    if($conference->id)
    {
        do
        {
            echo $conference->getValue("name") . " (" . $conference->getValue('acronym') . ")|" . $conference->getId() . "\n";

        }while($conference->next());
    }

?>
