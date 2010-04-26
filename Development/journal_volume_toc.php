<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'header.php';
require_once FRONT_END . OBJECTS . 'Author.php';
require_once FRONT_END . OBJECTS . 'JournalPaper.php';
require_once FRONT_END . OBJECTS . 'Journal.php';
require_once FRONT_END . OBJECTS . 'JournalVolumeNumber.php';

$j_v_id = $_GET['journal_volume_id'];
$journalVolumeNumber = new JournalVolumeNumber($j_v_id);
$journal = new Journal($journalVolumeNumber->getValue('journal_id'));

$j_name_display = $journal->getValue("name");
?>

<h1><?php echo $j_name_display?></h1>

<a href="submit_journal.php">Click here to add a paper to a</a><br><br><br>


<?php



?>
