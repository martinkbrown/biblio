<h2>View Publishers</h2>

<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once FRONT_END . OBJECTS . 'Publisher.php';
require_once FRONT_END . OBJECTS . 'AdminPublisherGrid.php';

$publisher = new Publisher();
$publisher->loadPublishers();
$grid = new AdminPublisherGrid();
$grid->setColumnTitle("name", "Publisher");
$grid->setColumnTitle("options", "Options");
$grid->setGridTitles();
$grid->setResultsPerPage($adminSettings->resultsPerPage);
$grid->setGridSelect();

if($delete)
{
    $publisher->delete($ids);
    $publisher = new Publisher();
    $publisher->loadPublishers();
}

if($name)
{
    $publisher->loadPublishersByKeyword($_POST['name']);
}

$grid->createGridFromRecordset($publisher);

?>

<form name="search_publishers" method="POST" action="main.php?page=view_publishers">
    <table>
        <tr>
            <td>Publisher Name</td>
            <td>
                <input name="name" type="text" value="<?php echo $publisher->getFormValue('name'); ?>"/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input name="submit" type="submit" value="Search"/>
            </td>
        </tr>
    </table>
</form>

<a href="main.php?page=edit_publisher">Click here to add a Publisher</a><br/>

<form name="view_publishers" method="POST" action="main.php?page=view_publishers" onsubmit="return confirm('Are you sure you want to DELETE? This will DELETE the selected Publishers and ALL States associated with them.')">

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