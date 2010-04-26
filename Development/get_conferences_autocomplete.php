<?php

    define('FRONT_END','./');
    define('BACK_END','admin/');
    require_once FRONT_END . 'config.php';

    require_once OBJECTS . 'Conference.php';

    $sql =& sql();
    $keyword = $sql->escape($_GET['q']);
    $limit = $_GET['limit'];

    $query = "SELECT * FROM conference WHERE
                        name REGEXP '[[:<:]]{$keyword}'";

    $limit ? $query .= " LIMIT {$limit}" : "";

    $conference = new Conference($query);

    if($conference->id)
    {
        do
        {
            echo $conference->getValue("name") . " (" . $conference->getValue('acronym') . ")|" . $conference->getId() . "\n";

        }while($conference->next());
    }

?>
