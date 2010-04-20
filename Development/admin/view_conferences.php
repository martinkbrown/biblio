<h2>View Conferences</h2>

<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once FRONT_END . OBJECTS . 'Conference.php';
require_once FRONT_END . OBJECTS . 'AdminConferenceGrid.php';
require_once 'jquery_lib.php';

$conference = new Conference();
$conference->loadConferences();
$grid = new AdminConferenceGrid();
$grid->setColumnTitle("_name", "Conference");
$grid->setColumnTitle("_acronym", "Acronym");
$grid->setColumnTitle("_approved", "Approved");
$grid->setColumnTitle("_contact", "Contact");
$grid->setColumnTitle("_updated", "Updated");
$grid->setColumnTitle("_options", "Options");
$grid->setGridTitles();
$grid->setGridSelect();
$grid->setResultsPerPage($adminSettings->resultsPerPage);

if($delete)
{
    $conference->delete($_POST['id']);
}

if($name)
{
    $conference->loadConferencesByKeyword($_POST['name']);
}

$grid->createGridFromRecordset($conference);

?>

<form name="search_conferences" method="POST" action="main.php?page=view_conferences">
    <table>
        <tr>
            <td>Conference Keyword</td>
            <td>
                <input name="name" id="check" type="text" value="<?php echo $conference->getFormValue('name'); ?>"/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input name="submit" type="submit" value="Search"/>
            </td>
        </tr>
    </table>
</form>

<a href="main.php?page=edit_conference">Click here to add a Conference</a><br/>

<form name="view_conferences" method="POST" action="main.php?page=view_conferences" onsubmit="return confirm('Are you sure you want to DELETE? This will DELETE the selected Conferences and ALL Conference Meetings associated with them.')">
    
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