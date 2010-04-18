<h2>View Conference Papers</h2>

<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once FRONT_END . OBJECTS . 'ConferencePaper.php';
require_once FRONT_END . OBJECTS . 'Country.php';
require_once FRONT_END . OBJECTS . 'AdminConferencePaperGrid.php';

if($delete)
{
    $cp->delete($ids);
}

$cp = new ConferencePaper();

$country = new Country();
$country->loadCountries();

$grid = new AdminConferencePaperGrid();
$grid->setColumnTitle("_title", "Title");
$grid->setColumnTitle("_source", "Conference Meeting");
$grid->setColumnTitle("_start_page", "Pages");
$grid->setColumnTitle("_create_date", "Create Date");
$grid->setColumnTitle("_email", "Email");
$grid->setColumnTitle("_approved", "Email");
$grid->setGridTitles();
$grid->setResultsPerPage($adminSettings->resultsPerPage);
$grid->setGridSelect();

$dd = new DropDownList('name');
$dd->setFieldName("country_id");
$dd->setSelectedValues($country_id);
$dd->setExtraValues(array("0"=>""));

if($name)
{
    $cp->loadConferencePapersByKeyword($name);
}

if($country_id)
{
    $cp->loadConferencePapersByCountryId($country_id);
}

$grid->createGridFromRecordset($cp);

?>

<form name="search_conference_papers" method="POST" action="main.php?page=view_conference_papers">
    <table>
        <tr>
            <td>Conference Paper Name</td>
            <td>
                <input name="name" type="text" value="<?php echo $cp->getFormValue('name'); ?>"/>
            </td>
        </tr>
        <tr>
            <td>Country</td>
            <td>
                <?php echo $dd->getDropDownFromRecordset($country); ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input name="submit" type="submit" value="Search"/>
            </td>
        </tr>
    </table>
</form>

<a href="main.php?page=edit_conference_paper">Click here to add a Conference Paper</a><br/><br/>

<form name="view_Conference Papers" method="POST" action="main.php?page=view_Conference Papers" onsubmit="return confirm('Are you sure you want to DELETE the selected Conference Papers?')">

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