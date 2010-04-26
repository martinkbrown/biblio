<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../lib/Grid.php';
require_once 'Author.php';
/**
 * Description of AdminCountryGrid
 *
 * @author martin
 */
class AdminJournalPaperGrid extends Grid {
    //put your code here
    function handle_title($row)
    {
        return $row['title'];
    }

    function handle_authors($row)
    {
        $author = new Author();
        $authors = "";

        $author->getAuthorsByJournalPaperId($row['journal_paper_id']);

        if($author->id)
        {
            do
            {
                $authors .= $author->firstname . " ";
                $authors .= $author->initial ? $author->initial . " " : "";
                $authors .= $author->lastname;
                $authors .= "<br/>";
                
            }while($author->next());
        }

        return $authors;
    }

    function handle_create_date($row)
    {
        return Date::getDate($row['create_date']);
    }

    function handle_start_page($row)
    {
        return "<nobr>" . $row['start_page'] . " - " . $row['end_page'] . "</nobr>";
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

    function handle_options($row)
    {
        return '<a href="main.php?page=edit_journal_paper&journal_paper_id=' . $row['id'] . '">Edit</a>';
    }
}
?>
