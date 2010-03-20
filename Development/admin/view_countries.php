<h2>View Countries</h2>

<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once FRONT_END . OBJECTS . 'Country.php';
require_once FRONT_END . OBJECTS . 'AdminCountryGrid.php';

$country = new Country();
$country->loadCountries();
$grid = new AdminCountryGrid();
$grid->setColumnTitle("name", "Country");
$grid->setColumnTitle("options", "Options");
$grid->setGridTitles();
$grid->setResultsPerPage($adminSettings->resultsPerPage);
$grid->setGridSelect();

if($delete)
{
    $country->delete($ids);
    $country = new Country();
    $country->loadCountries();
}

if($name)
{
    $country->loadCountriesByKeyword($_POST['name']);
}

$grid->createGridFromRecordset($country);

?>

<form name="search_countries" method="POST" action="main.php?page=view_countries">
    <table>
        <tr>
            <td>Country Name</td>
            <td>
                <input name="name" type="text" value="<?php echo $country->getFormValue('name'); ?>"/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input name="submit" type="submit" value="Search"/>
            </td>
        </tr>
    </table>
</form>

<a href="main.php?page=edit_country">Click here to add a Country</a><br/>

<form name="view_countries" method="POST" action="main.php?page=view_countries" onsubmit="return confirm('Are you sure you want to DELETE? This will DELETE the selected Countries and ALL States associated with them.')">
    
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