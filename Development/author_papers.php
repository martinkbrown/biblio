<?php

require_once 'header.php';
require_once OBJECTS . 'AuthorPaperGrid.php';
require_once OBJECTS . 'Author.php';

$author = new Author($_GET['author_id']);

$conference_papers = $author->conference_papers;

$grid = new AuthorPaperGrid();
$grid->setColumnTitle("_year","List of Papers by " . $author->getFullName());
$grid->createGridFromRecordset($conference_papers);
$grid->gridTitles = false;

?>

<h2><?php echo $author->getFullName() ?></h2>

<?php

echo $grid->getGrid();

?>