<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'Settings.php';

/**
 * DatabaseSettings handles connections to the database. It reads the database settings
 * file and makes the connection
 *
 * @author martin
 */
class DatabaseSettings extends Settings
{
    /**
     *
     * @var string      the database host
     */
    var $dbHost;

    /**
     *
     * @var string      the database username
     */
    var $dbUsername;

    /**
     *
     * @var string      the database password
     */
    var $dbPassword;

    /**
     *
     * @var string      the database name
     */
    var $dbName;

    /**
     * Constructor, calls the parent constructor. @see Settings.php
     * @param string $pathToSettings    the path to the settings file
     */
    function DatabaseSettings($pathToSettings = '../admin/settings/db_settings.txt')
    {
        parent::Settings($pathToSettings);
    }

    /**
     * Connect to the database
     */
    function connect()
    {
        $sql =& sql();
        
        $sql->connect($this->dbHost,$this->dbUsername,$this->dbPassword);
        $sql->selectDb($this->dbName);
    }

}
?>
