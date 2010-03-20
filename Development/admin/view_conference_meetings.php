<h2>View States</h2>

<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once FRONT_END . OBJECTS . 'ConferenceMeeting.php';
require_once FRONT_END . OBJECTS . 'Conference.php';
require_once FRONT_END . OBJECTS . 'Publisher.php';
require_once FRONT_END . OBJECTS . 'State.php';
require_once FRONT_END . OBJECTS . 'Country.php';
require_once FRONT_END . OBJECTS . 'AdminConferenceMeetingGrid.php';

$conferenceMeeting = new ConferenceMeeting();

if($delete)
{
    $conferenceMeeting->delete($ids);
    $conferenceMeeting = new ConferenceMeeting();
}

$conferenceMeeting->loadConferenceMeetings();

if($conference_id)  $conferenceMeeting->loadConferenceMeetingsByConferenceId($conference_id);
if($name)           $conferenceMeeting->loadConferenceMeetingsByKeyword($name);

$conference = new Conference();
$conference->loadConferences();

$state = new State();

$country = new Country();
$country->loadCountries();

$grid = new AdminConferenceMeetingGrid();
$grid->setColumnTitle("_name", "Conference Meeting");
$grid->setColumnTitle("_conference_name", "Conference");
$grid->setColumnTitle("_location", "Location");
$grid->setColumnTitle("_publisher_name", "Publisher");
$grid->setColumnTitle("_publisher_website", "Publisher Website");
$grid->setColumnTitle("_conference_website", "Conference Website");
$grid->setColumnTitle("_isbn", "ISBN");
$grid->setColumnTitle("_date", "Date");
$grid->setColumnTitle("_email", "Contact");
$grid->setColumnTitle("_approved", "Approved");
$grid->setColumnTitle("_options", "Options");

$grid->setGridTitles();
$grid->setResultsPerPage($adminSettings->resultsPerPage);
$grid->setGridSelect();

$conferenceDd = new DropDownList('name');
$conferenceDd->setFieldName("conference_id");
$conferenceDd->setSelectedValues($conference_id);
$conferenceDd->setExtraValues(array("0"=>""));

$countryDd = new DropDownList('name');
$countryDd->setFieldName("country_id");
$countryDd->setSelectedValues($country_id);
$countryDd->setExtraValues(array("0"=>""));

if($delete)
{
    $state->delete($state_id);
}

if($name)
{
    $state->loadStatesByKeyword($name);
}

if($country_id)
{
    $state->loadStatesByCountryId($country_id);
}

$grid->createGridFromRecordset($conferenceMeeting);

?>

<form name="search_conference_meetings" method="POST" action="main.php?page=view_conference_meetings">
    <table>
        <tr>
            <td>Conference Meeting Name</td>
            <td>
                <input name="name" type="text" value="<?php echo $conferenceMeeting->getFormValue('name'); ?>"/>
            </td>
        </tr>
        <tr>
            <td>Conference</td>
            <td>
                <?php echo $conferenceDd->getDropDownFromRecordset($conference); ?>
            </td>
        </tr>
        <tr>
            <td>Country</td>
            <td>
                <?php echo $countryDd->getDropDownFromRecordset($country); ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input name="submit" type="submit" value="Search"/>
            </td>
        </tr>
    </table>
</form>

<a href="main.php?page=edit_conference_meeting">Click here to add a Conference Meeting</a><br/>

<form name="view_conference_meetings" method="POST" action="main.php?page=view_conference_meetings" onsubmit="return confirm('Are you sure you want to DELETE the selected conference meetings?')">

        <?php

echo $grid->getGrid();

if($grid->getNumberOfResults() > 0)
{
?>

    <br/><br/><input name="delete" type="submit" value="Delete Selected"/>
<?php
}
?>
</form>