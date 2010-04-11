<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of State
 *
 * @author martin
 */
require_once FRONT_END.LIB.'Recordset.php';

class State extends Recordset{

    var $query = "SELECT s.*, c.name as country_name FROM state s
                    LEFT JOIN country c ON c.id = s.country_id
                    WHERE 1 ";

    /**
     * Loads all states
     */
    function loadStates()
    {
        $this->loadByQuery($this->query);
    }

    /**
     * Loads all states that match a $countryId
     * @param <type> $countryId
     */
    function loadStatesByCountryId($countryId)
    {
        $sql =& sql();
        $countryId = (int) $countryId;

        $this->query = $this->query . " AND c.id = $countryId";
        $this->loadByQuery($this->query);
    }

    /**
     * Loads all states that match a $keyword
     * @param string $keyword
     */
    function loadStatesByKeyword($keyword)
    {
        $sql =& sql();
        $keyword = $sql->escape($keyword);

        $this->query = $this->query . " AND s.name LIKE '{$keyword}%'";
        $this->loadByQuery($this->query);
    }
}
?>
