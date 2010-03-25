<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once FRONT_END . LIB . 'Recordset.php';

/**
 * Description of Conference
 *
 * @author martin
 */
class Conference extends Recordset{

    var $query = "SELECT * FROM conference WHERE 1";

    function loadConferences()
    {
        $this->loadByQuery($this->query . " ORDER BY name");
    }

    /**
     * Allows you to only load conferences that have been approved by the administrator
     */
    function loadApprovedConferences()
    {
        $this->query .= " AND (approved = 1)";
        $this->loadByQuery($this->query . " ORDER BY name");
    }

    /**
     *
     * @param string $keyword   The string by which to search
     */
    function loadConferencesByKeyword($keyword)
    {
        $sql =& sql();
        $keyword = $sql->escape($keyword);
        $this->query .= " AND (name LIKE '{$keyword}%'
                            OR acronym LIKE '%{$keyword}%')";
        
        $this->loadByQuery($this->query . " ORDER BY name");
    }

    /**
     *
     * @param string $letter    The letter to match against the conference acronym
     */
    function loadConferencesByFirstLetter($letter)
    {
        $sql =& sql();
        $letter = $sql->escape($letter);

        $this->query .= " AND (acronym LIKE '{$letter}%')";

        $this->loadByQuery($this->query . " ORDER BY name");
    }

    function insert()
    {
        $this->setValue('create_date',mktime());
        return parent::insert();
    }

    function update()
    {
        $this->setValue('create_date',mktime());
        return parent::update();
    }
}
?>
