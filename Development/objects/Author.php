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
        if($id)
        {
            $this->query .= " AND a.id = '" . $id . "'";
            parent::Recordset($this->query,"author");
        }
        else
        {
            parent::Recordset(0,"author");
        }
    }

    function getFullName()
    {
        return $this->firstname . " " . $this->initial . " " . $this->lastname;
    }

    function next()
    {
        if(parent::next())
        {
            $this->conference_papers = new ConferencePaper();
            $this->conference_papers->getConferencePapersByAuthorId($this->getId());
            
            return true;
        }

        return false;
    }

    function getAuthorsByName($firstname,$lastname)
    {
        $sql =& sql();

        $firstname = $sql->escape($firstname);
        $lastname = $sql->escape($lastname);

        $this->query .= " AND a.firstname = '" . $firstname . "'
                                AND a.lastname = '" . $lastname . "'";
        
        return $this->loadByQuery($this->query);
    }
}
?>
