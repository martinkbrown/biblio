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
    var $query = "SELECT a.id, a.firstname, a.initial, a.lastname FROM author a WHERE 1";

    var $conference_papers;
    var $journal_papers;

    function Author($id=0)
    {
        parent::Recordset($id,"author");
    }

    function next()
    {
        if(parent::next())
        {
            $this->conference_papers = new ConferencePaper();
            $this->conference_papers->getConferencePapersByAuthor($this->getId());
            
            return true;
        }

        return false;
    }

    function getSimilarAuthors()
    {
        $query = $this->query . "
                        AND a.firstname = '" . $this->getValue('firstname') . "'
                        AND a.lastname = '" . $this->getValue('lastname') . "'";

        $similar_authors = new Author($query);

        return $similar_authors;
    }
}
?>
