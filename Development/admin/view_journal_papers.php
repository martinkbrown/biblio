<h2>View Journal Papers</h2>

<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once FRONT_END . OBJECTS . 'JournalPaper.php';
require_once FRONT_END . OBJECTS . 'Country.php';
require_once FRONT_END . OBJECTS . 'AdminJournalPaperGrid.php';

$jp = new JournalPaper("SELECT * FROM jp ORDER BY name");

$country = new Country();
$country->loadCountries();

$grid = new AdminJournalPaperGrid();
$grid->setColumnTitle("name", "JournalPaper");
$grid->setColumnTitle("abbreviation", "JournalPaper Code");
$grid->setColumnTitle("country_name", "Country");
$grid->setColumnTitle("options", "Options");
$grid->setGridTitles();
$grid->setResultsPerPage($adminSettings->resultsPerPage);
$grid->setGridSelect();

if($delete)
{
    $jp->delete($ids);
    $jp = new JournalPaper("SELECT * FROM jp ORDER BY name");
}

$dd = new DropDownList('name');
$dd->setFieldName("country_id");
$dd->setSelectedValues($country_id);
$dd->setExtraValues(array("0"=>""));

if($name)
{
    $jp->loadJournal PapersByKeyword($name);
}

if($country_id)
{
    $jp->loadJournal PapersByCountryId($country_id);
}

$grid->createGridFromRecordset($jp);

?>

<form name="search_jps" method="POST" action="main.php?page=view_jps">
    <table>
        <tr>
            <td>JournalPaper Name</td>
            <td>
                <input name="name" type="text" value="<?php echo $jp->getFormValue('name'); ?>"/>
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

<a href="main.php?page=edit_jp">Click here to add a JournalPaper</a><br/>

<form name="view_jps" method="POST" action="main.php?page=view_jps" onsubmit="return confirm('Are you sure you want to DELETE the selected Journal Papers?')">

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