<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JournalPaperGrid
 *
 * @author Sihle
 */
class JournalPaperGrid {
    var $current_year;

    function handle_year($row)
    {
        $year = date("Y",(int) $row['date']);
        $title = $row['title'];
        $pages = "Pages " . $row['start_page'] . " - " . $row['end_page'];
        $source = $row['source_name'];
        $cm_id = $row['conference_meeting_id'];
        $j_id = $row['journal_id'];

        if($this->current_year != $year)
        {
            $this->current_year = $year;
            $year = "<center><b>" . $year . "</b></center>";
        }
        else
        {
            $year = "";
        }

        $author = new Author();

        if($row['journal_id'])
        {
            $author->getAuthorsByJournalPaperId($row['journal_paper_id']);
            $source = "<a href=\"journal_papers.php?journal_id=$j_id\">$source</a>";
        }
        else
        {
            $author->getAuthorsByConferencePaperId($row['conference_paper_id']);
            $source = "<a href=\"conference_meeting_toc.php?conference_meeting_id=$cm_id\">$source</a>";
        }

        $author = $author->toArray();

        $authors = "";

        $counter = 1;
        foreach($author as $key=>$auth)
        {
            $name = $auth['firstname'];
            $name .= $auth['initial'] ? " " . $auth['initial'] : "";
            $name .= " " . $auth['lastname'];
            if($key != $_GET['author_id'])
            {
                $authors .=  "<a href=\"author_papers.php?author_id=$key\">$name</a>&nbsp;&nbsp;&nbsp;";
            }
            else
            {
                $authors .= $name . "&nbsp;&nbsp;&nbsp;";
            }
        }

        return "$year $title<br/>$authors<br/>$source $pages<br/><br/>";
    }
}
?>
