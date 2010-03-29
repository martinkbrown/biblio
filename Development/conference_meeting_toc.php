<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'header.php';
$conf_id = $_GET['big_conf'];
$conf_meet_id = $_GET['conference_meeting_id'];
echo "<h2>Name of Conference</h2>";
echo '<a href="conference_paper.php?big_conf='.$conf_id.'&conf_meet_id='.$conf_meet_id.'">Add new Conference Paper</a>';
?>
<h3>Conference Meeting (e.g.proceedings of ...)City, State, Country, Month Days, Year, Publisher Year</h3>