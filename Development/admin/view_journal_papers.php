<h2>View Journal Papers</h2>

<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once FRONT_END . OBJECTS . 'JournalPaper.php';
require_once FRONT_END . OBJECTS . 'AdminJournalPaperGrid.php';

if($delete)
{
    $jp->delete($ids);
}

$jp = new JournalPaper();

$grid = new AdminJournalPaperGrid();
$grid->setColumnTitle("_title", "Title");
$grid->setColumnTitle("_authors", "Authors");
$grid->setColumnTitle("_source", "Journal");
$grid->setColumnTitle("_start_page", "Pages");
$grid->setColumnTitle("_create_date", "Updated");
$grid->setColumnTitle("_email", "Email");
$grid->setColumnTitle("_approved", "Approved");
$grid->setColumnTitle("_options", "Options");
$grid->setGridTitles();
$grid->setResultsPerPage($adminSettings->resultsPerPage);
$grid->setGridSelect();

if($name)
{
    $jp->loadJournalPapersByKeyword($name);
}

$grid->createGridFromRecordset($jp);

?>

<form name="search_journal_papers" method="POST" action="main.php?page=view_journal_papers">
    <table>
        <tr>
            <td>Journal Paper Name</td>
            <td>
                <input name="name" type="text" value="<?php echo $jp->getFormValue('name'); ?>"/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input name="submit" type="submit" value="Search"/>
            </td>
        </tr>
    </table>
</form>

<a href="main.php?page=edit_journal_paper">Click here to add a Journal Paper</a><br/><br/>

<form name="view_Journal Papers" method="POST" action="main.php?page=view_Journal Papers" onsubmit="return confirm('Are you sure you want to DELETE the selected Journal Papers?')">

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