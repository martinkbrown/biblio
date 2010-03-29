<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'ConferencePaper.php';
require_once 'JournalPaper.php';

/**
 * Description of Author
 *
 * @author martin
 */
class Author extends Recordset
{
    var $query = "SELECT a.id, a.firstname, a.initial, a.lastname FROM author WHERE 1";

    var $conference_papers;
    var $journal_papers;

    function Author($id=0)
    {
        parent::Recordset($id,"author");
    }

    function next()
    {
        parent::next();

        $this->conference_papers = new ConferencePaper();
        $this->conference_papers->getConferencePapersByAuthor($this->getId());
        
    }

    function getSimilarAuthors()
    {
        $similar_authors = new Author($this->query . "
                        AND a.firstname LIKE '" . $this->getValue('firstname') . "'
                        AND a.lastname LIKE '" . $this->getValue('lastname') . "'");

        return $similar_authors();
    }
}
?>
