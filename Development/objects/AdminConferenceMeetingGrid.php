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
class AdminConferenceMeetingGrid extends Grid {
    //put your code here
    function handle_options($row)
    {
        return '<a href="main.php?page=edit_conference_meeting&conference_meeting_id=' . $row['id'] . '">Edit</a>';
    }

    function handle_approved($row)
    {
        return $row['approved'] == 1 ? "Yes" : "No";
    }

    function handle_date($row)
    {
        return Date::getDateRange(strtotime($row['start_date']),strtotime($row['end_date']));
    }

    function handle_location($row)
    {
        return $row['city'].", " . ($row['state_code'] ? $row['state_code'].", " : "") . $row['country_name'];
    }

    function handle_name($row)
    {
        return $row['name'];
    }

    function handle_conference_name($row)
    {
        return $row['conference_name'];
    }

    function handle_publisher_name($row)
    {
        return $row['publisher_name'];
    }

    function handle_publisher_website($row)
    {
        return $row['publisher_website'];
    }

    function handle_conference_website($row)
    {
        return $row['conference_website'];
    }

    function handle_isbn($row)
    {
        return $row['isbn'];
    }

    function handle_email($row)
    {
        return $row['email'];
    }
}
?>
