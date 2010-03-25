<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Journal
 * Main Journal Class to be used as object to manipulate Journal objects
 *
 * @author Sihle
 */
require_once FRONT_END . LIB . 'Recordset.php';

class Journal extends Recordset {

    var $query = "SELECT * FROM journal WHERE 1";

    function loadJournals()
    {
        $this->loadByQuery($this->query . " ORDER BY name");
    }

    /**
     * Allows you to only load journals that have been approved by the administrator
     */
    function loadApprovedJournals()
    {
        $this->query .= " AND (approved = 1)";
        $this->loadByQuery($this->query . " ORDER BY name");
    }

    /**
     * Allows user to find all journals by a keyword
     * @param string $keyword   The string by which to search
     */
    function loadJournalsByKeyword($keyword)
    {
        $sql =& sql();
        $keyword = $sql->escape($keyword);
        $this->query .= " AND name REGEXP '[[:<:]]{$keyword}[[:>:]]'";

        $this->loadByQuery($this->query . " ORDER BY name");
    }

    /**
     * Allows user to find all Journals by the first letter of the journal's name
     * @param string $letter    The letter to match against the conference acronym
     */
    function loadJournalsByFirstLetter($letter)
    {
        $sql =& sql();
        $letter = $sql->escape($letter);

        $this->query .= " AND (acronym LIKE '{$letter}%')";

        $this->loadByQuery($this->query . " ORDER BY name");
    }
    
}
?>
