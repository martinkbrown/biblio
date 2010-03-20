<?php

    define('FRONT_END','./');
    define('BACK_END','admin/');
    require_once FRONT_END . 'config.php';

    require_once OBJECTS . 'Publisher.php';

    $publisher = new publisher();
    $publisher->loadPublishers();

    if($publisher->id)
    {
        do
        {
            echo $publisher->getValue("name") . "|" . $publisher->getId() . "\n";

        }while($publisher->next());
    }

?>
