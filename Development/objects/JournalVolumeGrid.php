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
class JournalVolumeGrid extends Grid {
    function handle_volume($row) {
        return '<a href="journal_volume_toc.php?journal_volume_id='.$row['id'].'">Volume '.$row['volume'].', '.Date::getYear($row['date']).' </a>';
    }
}
?>
