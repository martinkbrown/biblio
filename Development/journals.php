<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/
require_once 'header.php';
require_once LIB . 'Grid.php';
require_once OBJECTS . 'Journal.php';
require_once OBJECTS . 'JournalGrid.php';

?>

<h2>Journals</h2>
<a href="submit_journal.php">Click here to submit a Journal</a><br><br><br>

<form name ="frm_name" method="GET" >
    <table>
        <tr>
            <td><b>Search Journals</b></td>
            <td> <input type= "text" name= "journal_name" value="<?php echo $_POST['journal_name']?>"/> </td>
            <td> <input type="submit" name="Search" value="Search"/>  </td>
        </tr>
    </table>
</form>
<br><br>
<?php
// action ="journals.php"
$journal = new Journal();
$grid = new JournalGrid();
if ($_GET) {
    $jname = $_GET['journal_name'];

    // Loading approved journals by keyword
    $journal->loadApprovedJournals();
    $journal->loadJournalsByKeyword($_GET['journal_name']);

    $grid->setColumnTitle("_name","Name of Journal");
    $grid->createGridFromRecordset($journal);
    echo $grid->getGrid();
}
require_once 'footer.php';

?>

