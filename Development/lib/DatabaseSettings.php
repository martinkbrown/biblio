<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of db_connector
 *
 * @author martin
 */

class DatabaseSettings
{
    var $dbhost;
    var $dbusername;
    var $dbpassword;
    var $dbname;

    function DatabaseSettings($path_to_settings)
    {
        $temp_settings = array();

        $handle = fopen($path_to_settings,"r");
		$string = '';
		while(!feof($handle))
		{
            $s = fgets($handle);
			$attr = explode("=",$s);
            $attr[0] = trim($attr[0]);
            $attr[1] = trim($attr[1]);
            $this->$attr[0] = $attr[1];
		}
        
		fclose($handle);
    }

    function connect()
    {
        $sql =& sql();
        
        $sql->connect($this->dbhost,$this->dbusername,$this->dbpassword);
        $sql->selectDb($this->dbname);
    }

}
?>
