<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Settings manages any of the settings files located in the admin directory
 * All settings files contain lines of key and value pairs, which are separated
 * by an equal sign
 * @example key = value
 * @author martin
 */
class Settings
{
    /**
     * The constructor reads a file specified by the user,
     * parses the key value pairs, and stores them locally
     * @param string $pathToSettings    The relative or absolute path to which the settings file is located
     */
    function Settings($pathToSettings)
    {
        //open the file
        $handle = fopen($pathToSettings,"r");

        //loop until the end of the file
        while(!feof($handle))
        {
            //get the next line
            $s = fgets($handle);

            //create a key and value pair ising "=" as the delimiter
            $attr = explode("=",$s);

            $attr[0] = trim($attr[0]);
            $attr[1] = trim($attr[1]);

            //create a variable and assign it a value
            if($attr[0])
            {
                $this->$attr[0] = $attr[1];
                $GLOBALS[$attr[0]] = $attr[1];
            }
        }

        fclose($handle);
    }
}
?>
