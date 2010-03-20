<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ConferenceGrid
 *
 * @author ShaleciaSnow
 */
require_once FRONT_END . LIB . 'Grid.php';
class ConferenceGrid extends Grid {
    function handle_name($row){
        return '<a href="conference_meetings.php?conference_id='.$row['id'].'">'.$row['name'].'</a>';
    }
}
?>
