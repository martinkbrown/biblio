<?php

require_once 'header.php';
require_once OBJECTS . 'AuthorPaperGrid.php';
require_once OBJECTS . 'Author.php';

$author = new Author($_GET['author_id']);

$conference_papers = $author->conference_papers;
$journal_papers = $author->journal_papers;

$grid = new AuthorPaperGrid();
$grid->setColumnTitle("_year","List of Papers by " . $author->getFullName());
$grid->setResultsPerPage($siteSettings->resultsPerPage);

$conference_array = $conference_papers->toArray();
$journal_array = $journal_papers->toArray();

$papers = array_merge($conference_array,$journal_array);

$grid->createGridFromArray($papers);

$grid->gridTitles = false;

?>

<h2><?php echo $author->getFullName() ?></h2>

<?php

echo $grid->getGrid();

?>