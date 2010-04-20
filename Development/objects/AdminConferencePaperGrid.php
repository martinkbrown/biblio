<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../lib/Grid.php';
/**
 * Description of AdminCountryGrid
 *
 * @author martin
 */
class AdminConferencePaperGrid extends Grid {
    //put your code here
    function handle_title($row)
    {
        return $row['title'];
    }

    function handle_create_date($row)
    {
        return Date::getDate($row['create_date']);
    }

    function handle_start_page($row)
    {
        return $row['start_page'] . " - " . $row['end_page'];
    }

    function handle_source($row)
    {
        $source = $row['source_name'];
        if($row['session_name'])    $source .= ": " . $row['session_name'];

        return $source;
    }

    function handle_email($row)
    {
        return $row['email'];
    }

    function handle_approved($row)
    {
        return (int) $row["approved"] == 0 ? "No" : "Yes";
    }
}
?>
