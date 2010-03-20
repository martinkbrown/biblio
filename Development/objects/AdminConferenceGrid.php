<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../lib/Grid.php';
/**
 * Description of AdminConferenceGrid
 *
 * @author martin
 */
class AdminConferenceGrid extends Grid {
    //put your code here
    function handleOptions($row)
    {
        return '<a href="main.php?page=edit_conference&conference_id=' . $row['id'] . '">Edit</a>';
    }

    function handle_approved($row)
    {
        return $row['approved'] == 1 ? "Yes" : "No";
    }

    function handle_name($row)
    {
        return $row['name'];
    }

    function handle_acronym($row)
    {
        return $row['acronym'];
    }

    function handle_contact($row)
    {
        return $row['email'];
    }

    function handle_updated($row)
    {
        return Date::getDate($row['create_date']);
    }
}
?>
