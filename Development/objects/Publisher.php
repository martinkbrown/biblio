<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of publisher
 *
 * @author martin
 */
require_once FRONT_END.LIB.'Recordset.php';

class Publisher extends Recordset{

    /**
     * Load all publishers from the database table publisher
     */
    function loadPublishers()
    {
        $this->loadByQuery("SELECT * FROM publisher");
    }

    function loadPublisherByName($name)
    {
        $name = stripslashes($name);
        $this->loadByQuery("SELECT * FROM publisher WHERE name = '$name'");
    }

    /**
     * Loads all publishers that match a $keyword
     * @param string $keyword
     */
    function loadPublishersByKeyword($keyword)
    {
        $sql =& sql();
        $keyword = $sql->escape($keyword);

        $this->loadByQuery("SELECT * FROM publisher WHERE name LIKE '%{$keyword}%'");
    }
}
?>
