<?php

    define('FRONT_END','./');
    define('BACK_END','admin/');
    require_once FRONT_END.'config.php';

    require_once OBJECTS . 'State.php';
    require_once OBJECTS . 'Country.php';

    $state = new State();
    $state->loadStatesByCountryId($_GET['country_id']);

    $country = new Country($_GET['country_id']);

    if(!$country->getId())
    {
        echo 'Invalid Country';
        exit;
    }

    $dd = new DropDownList("name");
    $dd->setProperty("id","state_id");
    $dd->setFieldName("state_id");

    $dd->setSelectedValues($_GET['state_id']);

    if($state->id)
    {
        echo $dd->getDropDownFromRecordset($state);
    }
    else
    {
       echo 'No states available for ' . $country->getValue('name');
    }

?>
