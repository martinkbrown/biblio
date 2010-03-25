<?php

    define('FRONT_END','./');
    define('BACK_END','admin/');
    require_once FRONT_END . 'config.php';

    require_once OBJECTS . 'Publisher.php';

    $sql =& sql();
    $keyword = $sql->escape($_GET['q']);
    $limit = $_GET['limit'];

    $query = "SELECT * FROM publisher WHERE
                        name REGEXP '[[:<:]]{$keyword}'";
                                    
    $limit ? $query .= " LIMIT {$limit}" : "";

    $publisher = new Publisher($query);
    
    if($publisher->id)
    {
        do
        {
            echo $publisher->getValue("name") . "|" . $publisher->getId() . "\n";

        }while($publisher->next());
    }

?>
