<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LayoutGrid
 *
 * @author ShaleciaSnow 
 */
require_once FRONT_END . LIB . 'Grid.php';
require_once FRONT_END . LIB . 'Date.php';

class ConferenceTableGrid extends Grid {

    function handleName($row){
        return '<a href="conference_meeting_toc.php?conference_id='.$row['id'].'">'.$row['name'].'</a>';
    }

    function handleCity($row){
        if ($row['state_name'] == ""){
            return '<a>'.$row['city'].", ".$row['country_name'].'</a>';
        }
        else
        return '<a>'.$row['city'].", ".$row['state_name'].", ".$row['country_name'].'</a>';
    }

    function handleStart_date($row){

        return Date::getDateRange(strtotime($row['start_date']),strtotime($row['end_date']));
    }
}
?>
