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

    var $loadPapers = true;

    var $conference_papers;
    var $journal_papers;

    function Author($id=0)
    {
        if($id)
        {
            $id = (int) $id;
            $this->query .= " AND a.id = $id";
            parent::Recordset($this->query,"author");
        }
        else
        {
            parent::Recordset(0,"author");
        }
    }

    function setLoadPapers($load = true)
    {
        $this->loadPapers = $load;
    }

    function loadAuthors()
    {
        $this->loadByQuery($this->query);
    }

    function getFullName()
    {
        return $this->firstname . " " . $this->initial . " " . $this->lastname;
    }

    function next()
    {
        if(parent::next())
        {
            if($this->loadPapers)
            {
                $this->conference_papers = new ConferencePaper();
                $this->conference_papers->getConferencePapersByAuthorId($this->getId());

                $this->journal_papers = new JournalPaper();
                $this->journal_papers->getJournalPapersByAuthorId($this->getId());
            }

            return true;
        }

        return false;
    }

    function getAuthorsByName($firstname,$lastname)
    {
        $sql =& sql();

        $firstname = $sql->escape($firstname);
        $lastname = $sql->escape($lastname);

        if(!$firstname && !$lastname)
        {
            return;
        }

        if($firstname)
        {
            $this->query .= " AND a.firstname REGEXP '[[:<:]]{$firstname}'";

        }

        if($lastname)
        {
            $this->query .= " AND a.lastname REGEXP '[[:<:]]{$lastname}'";
        }
        
        return $this->loadByQuery($this->query);
    }

    function getAuthorsByConferencePaperId($id)
    {
        $id = (int) $id;
        $this->query = "SELECT a.id, a.firstname, a.initial, a.lastname FROM author a, conference_paper cp,
                            author_conference_paper acp
                        WHERE cp.id = $id
                        AND a.id = acp.author_id AND acp.conference_paper_id = cp.id
                        ORDER BY main_author DESC, lastname";

        $this->loadByQuery($this->query);
    }

    function getAuthorsByJournalPaperId($id)
    {
        $id = (int) $id;
        $this->query = "SELECT a.id, a.firstname, a.initial, a.lastname FROM author a, journal_paper jp,
                            author_journal_paper ajp
                        WHERE jp.id = $id AND
                        a.id = ajp.author_id AND ajp.journal_paper_id = jp.id
                        ORDER BY main_author DESC, lastname";

        $this->loadByQuery($this->query);
    }
}
?>
