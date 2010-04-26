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
require_once FRONT_END . OBJECTS . 'JournalPaperGrid.php';

$j_v_id = $_GET['journal_volume_id'];
$journalVolumeNumber = new JournalVolumeNumber($j_v_id);
$journal = new Journal($journalVolumeNumber->getValue('journal_id'));
$j_name_display = $journal->getValue("name");


?>

<div class ="conf_back">
    <span class="solid_writting"><?php echo ($j_name_display); ?> </span> <br>

    <br>
<?php
// Printing out Volumes for JournalVolume Selected
$grid = new JournalPaperGrid();
// Getting Journal Paper
$journalPaper = new JournalPaper();
$journalPaper->loadJournalPapersByVolume($_GET['journal_id'], $_GET['volume']);

$grid->setColumnTitle('_volume', "");
$grid->createGridFromRecordset($journalPaper);
echo $grid->getGrid();


?>
</div>