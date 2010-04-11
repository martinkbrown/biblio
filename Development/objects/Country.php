<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of country
 *
 * @author martin
 */
require_once FRONT_END.LIB.'Recordset.php';

class Country extends Recordset{

    /**
     * Load all countries from the database table country
     */
    function loadCountries()
    {
        $this->loadByQuery("SELECT * FROM country");
    }

    /**
     * Loads all countries that match a $keyword
     * @param string $keyword
     */
    function loadCountriesByKeyword($keyword)
    {
        $sql =& sql();
        $keyword = $sql->escape($keyword);

        $this->loadByQuery("SELECT * FROM country WHERE name LIKE '{$keyword}%'");
    }
}
?>
