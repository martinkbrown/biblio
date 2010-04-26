<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/
require_once LIB . 'Date.php';
/**
 * Description of JournalVolumeGrid
 *
 * @author Sihle
 */
class JournalPaperGrid extends Grid {

    function handle_volume($row) {
        echo "Volume". $row['volume'].", Number ". $row['number']. ", ".Date::getMonth($row['date'])." ". Date::getYear($row['date']);
        echo "<br>";
        $journal = new Journal($row['journal_id']);
        
        echo $row[''] ;
//        print_r($row);
    }
}
?>
