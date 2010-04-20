<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../lib/Grid.php';
/**
 * Description of AdminJournalPaperGrid
 *
 * @author martin
 */
class AdminConferenceGrid extends Grid {
    //put your code here
    function handle_name($row)
    {
        return $row['name'];
    }

    function handle_acronym($row)
    {
        return $row['acronym'];
    }

    function handle_approved($row)
    {
        return (int) $row['approved'] == 0 ? "No" : "Yes";
    }

    function handle_contact($row)
    {
        return $row['email'];
    }

    function handle_updated($row)
    {
        return Date::getDate($row['create_date']);
    }

    function handle_options($row)
    {
        return '<a href="main.php?page=edit_journal&journal_id=' . $row['id'] . '">Edit</a>';
    }
}
?>
