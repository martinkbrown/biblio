<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once FRONT_END . LIB . 'Grid.php';
/**
 * Description of AuthorPaperGrid
 *
 * @author martin
 */
class AuthorPaperGrid extends Grid
{
    var $current_year;

    function handle_year($row)
    {
        $year = date("Y",$row['date']);
        $title = $row['title'];
        $pages = $row['start_page'] . " - " . $row['end_page'];
        $source = $row['source_name'];
        $cm_id = $row['conference_meeting_id'];

        if($this->current_year != $year)
        {
            $this->current_year = $year;
            $year = "<b>" . $year . "</b><br/>";
        }
        else
        {
            $year = "";
        }
        
        return "$year \"$title\"<br/><a href=\"conference_meeting_toc.php?conference_meeting_id=$cm_id\">$source</a> pages $pages<br/><br/>";
    }
}

?>
