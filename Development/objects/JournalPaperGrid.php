<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/
require_once LIB . 'Date.php';
/**
 * Description of JournalVolumeGrid
 *
 * @author Sihle
 */
class JournalPaperGrid extends Grid {

    function handle_volume($row) {
        $paperInfo = "Volume ". $row['volume'].", Number ". $row['number']. ", ".Date::getMonth($row['date'])." ". Date::getYear($row['date']).
                "<br/>". $row['title']. ". &nbsp;   "."Pages ". $row['start_page']. " - ". $row['end_page'];

        // Displaying Authors

        $author = new Author();

        $author->getAuthorsByJournalPaperId($row['journal_paper_id']);

        $author = $author->toArray();
        $authors = "";
        $counter = 1;
        foreach($author as $key=>$auth) {
            $name = $auth['firstname'];
            $name .= $auth['initial'] ? " " . $auth['initial'] : "";
            $name .= " " . $auth['lastname'];
            $authors .=  "<a href=\"author_papers.php?author_id=$key\">$name</a>&nbsp;&nbsp;&nbsp;";
        }
        return $paperInfo . "<br/><br/>" . $authors;
    }
}
?>
