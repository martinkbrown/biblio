<?php
/* 
 *This page is to display the conference's meetings in the database
 *E.g. IEEE has 3 meetings (Tallahassee FL, Miami FL, Portland, OR
 */
require_once 'header.php';
require_once LIB . 'Grid.php';
require_once OBJECTS . 'ConferenceMeeting.php';
require_once OBJECTS . 'Conference.php';
require_once OBJECTS . 'ConferenceTableGrid.php';

$conf_id = $_GET['conference_id'];
$conf = new Conference($conf_id);
$conf_name_display = $conf->getValue("name");
$conf_acrynm_display = $conf->getValue("acronym");
?>

<h2><?php echo ($conf_name_display); echo " ("; echo ($conf_acrynm_display); echo ")";?> </h2>
<?php 
echo "<a href=submit_conference.php?conf_id=$conf_id href= >Add a conference meeting to $conf_name_display</a><br></br>";
?>

<?php
$conf_meeting = new ConferenceMeeting();
$grid = new ConferenceTableGrid();
$conf_meeting->loadApprovedConferenceMeetings();
$conf_meeting->loadConferenceMeetingsByConferenceId($_GET['conference_id']);
$grid->setColumnTitle('_name', 'Conference Meeting');
$grid->setColumnTitle('_city', 'Location');
$grid->setColumnTitle('_start_date', 'Date');
$grid->createGridFromRecordset($conf_meeting);
//gives results to user
 echo $grid->getGrid();
//loadConferenceMeetingById($_GET['id']){

?>


