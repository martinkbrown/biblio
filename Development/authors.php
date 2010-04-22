<?php

require_once 'header.php';
require_once OBJECTS . 'AuthorGrid.php';
require_once OBJECTS . 'Author.php';

$author = new Author();
$author->getAuthorsByName($_GET['firstname'], $_GET['lastname']);

$grid = new AuthorGrid();
$grid->setColumnTitle("_name","Name");
$grid->createGridFromRecordset($author);

?>
<div class ="conf_back">
    <br>
<span class="solid_writting">Authors</span> <br>

<form name="author_search" method="GET">
    <table>
        <tr>
            <td colspane="2">Search Authors</td>
        </tr>
        <tr>
            <td>First Name</td>
            <td>
                <input type="text" name="firstname" value="<?php echo htmlspecialchars($_GET['firstname']) ?>"/>
            </td>
        </tr>
        <tr>
            <td>Last Name</td>
            <td>
                <input type="text" name="lastname" value="<?php echo htmlspecialchars($_GET['lastname']) ?>"/>
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" name="submit" value="Search"/>
            </td>
        </tr>
    </table>
</form>

<?php

echo $grid->getGrid();

?>
</div>