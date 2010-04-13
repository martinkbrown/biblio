<h2>View States</h2>

<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once FRONT_END . OBJECTS . 'State.php';
require_once FRONT_END . OBJECTS . 'Country.php';
require_once FRONT_END . OBJECTS . 'AdminStateGrid.php';

$state = new State();
$state->loadStates();

$country = new Country();
$country->loadCountries();

$grid = new AdminStateGrid();
$grid->setColumnTitle("name", "State");
$grid->setColumnTitle("abbreviation", "State Code");
$grid->setColumnTitle("country_name", "Country");
$grid->setColumnTitle("options", "Options");
$grid->setGridTitles();
$grid->setResultsPerPage($adminSettings->resultsPerPage);
$grid->setGridSelect();

if($delete)
{
    $state->delete($ids);
    $state = new State("SELECT * FROM state ORDER BY name");
}

$dd = new DropDownList('name');
$dd->setFieldName("country_id");
$dd->setSelectedValues($country_id);
$dd->setExtraValues(array("0"=>""));

if($name)
{
    $state->loadStatesByKeyword($name);
}

if($country_id)
{
    $state->loadStatesByCountryId($country_id);
}

$grid->createGridFromRecordset($state);

?>

<form name="search_states" method="POST" action="main.php?page=view_states">
    <table>
        <tr>
            <td>State Name</td>
            <td>
                <input name="name" type="text" value="<?php echo $state->getFormValue('name'); ?>"/>
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

<a href="main.php?page=edit_state">Click here to add a State</a><br/>

<form name="view_states" method="POST" action="main.php?page=view_states" onsubmit="return confirm('Are you sure you want to DELETE the selected States?')">

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