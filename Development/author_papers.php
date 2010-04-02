<?php

require_once 'header.php';
require_once OBJECTS . 'AuthorPaperGrid.php';
require_once OBJECTS . 'Author.php';

$author = new Author($_GET['author_id']);

$grid = new AuthorPaperGrid();
$grid->setColumnTitle("_name","Name");
$grid->createGridFromRecordset($author);

?>

<h2>Author: <?php echo $author->getFullName() ?></h2>