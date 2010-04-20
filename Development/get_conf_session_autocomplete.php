<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * Author: Sherene Campbell
 */
    define('FRONT_END','./');
    define('BACK_END','admin/');
    require_once FRONT_END . 'config.php';
    require_once OBJECTS . 'ConferenceSession.php';

    $conf_id = $_GET['conf_meet_id'];
    $sql =& sql();
    $keyword = $sql->escape($_GET['q']);
    $limit = $_GET['limit'];

    $query = "SELECT * FROM conference_session WHERE
                        name REGEXP '[[:<:]]{$keyword}' and conference_meeting_id=$conf_id";

    $limit ? $query .= " LIMIT {$limit}" : "";

    $conf_session = new ConferenceSession($query);

    if($conf_session->id)
    {
        do
        {
            //$conf_session->getValue("conference_meeting_id") . " " . 
            echo $conf_session->getValue("name") . "|" . $conf_session->id . " " . "\n";
            
        }while($conf_session->next());
    }
?>
