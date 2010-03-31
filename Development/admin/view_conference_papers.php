<h2>View Conference Papers</h2>

<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once FRONT_END . OBJECTS . 'ConferencePaper.php';
require_once FRONT_END . OBJECTS . 'Country.php';
require_once FRONT_END . OBJECTS . 'AdminConferencePaperGrid.php';

$cp = new ConferencePaper("SELECT * FROM conference_paper ORDER BY name");

$country = new Country();
$country->loadCountries();

$grid = new AdminConferencePaperGrid();
$grid->setColumnTitle("name", "ConferencePaper");
$grid->setColumnTitle("abbreviation", "ConferencePaper Code");
$grid->setColumnTitle("country_name", "Country");
$grid->setColumnTitle("options", "Options");
$grid->setGridTitles();
$grid->setResultsPerPage($adminSettings->resultsPerPage);
$grid->setGridSelect();

if($delete)
{
    $cp->delete($ids);
    $cp = new ConferencePaper("SELECT * FROM conference_paper ORDER BY name");
}

$dd = new DropDownList('name');
$dd->setFieldName("country_id");
$dd->setSelectedValues($country_id);
$dd->setExtraValues(array("0"=>""));

if($name)
{
    $cp->loadConference PapersByKeyword($name);
}

if($country_id)
{
    $cp->loadConference PapersByCountryId($country_id);
}

$grid->createGridFromRecordset($cp);

?>

<form name="search_Conference Papers" method="POST" action="main.php?page=view_Conference Papers">
    <table>
        <tr>
            <td>ConferencePaper Name</td>
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

<a href="main.php?page=edit_conference_paper">Click here to add a ConferencePaper</a><br/>

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