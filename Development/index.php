<?php

require_once 'header.php';

require_once FRONT_END . OBJECTS . 'Journal.php';
require_once FRONT_END . OBJECTS . 'Conference.php';
require_once FRONT_END . OBJECTS . 'Author.php';

$journal = new Journal();
$conference = new Conference();
$author = new Author();

$journal->loadByQuery($journal->query . " AND approved = 1 ORDER BY RAND() limit 10");
$conference->loadByQuery($conference->query . " AND approved = 1 ORDER BY RAND() limit 10");
$author->loadByQuery($author->query . " ORDER BY RAND() limit 10");
echo '<div class ="home_white">';

echo '<table id="spaced_table">';
echo '<tr>';
echo '<td>';
echo " This Website Gives you";
echo '</td>';
echo '<td id="spaced">';
echo '<span class = "pretty_writting">Conferences & Journals</span>';
echo "<ol><li>Papers</li><li>Table of Contents</li></ol>";
echo '</td>';
echo '<td>';
echo '<span class = "pretty_writting">Authors </span>';
echo "<ol><li>Author's Papers</li><li>Author's Journals</li></ol>";
echo '</td>';
echo '</tr>';
echo '</table>';

echo '<table  class="center_table">';
echo '<tr>';  //ONE BIG ROW

echo '<td>';  // In each column have a category

echo '<table class="journal">';
echo '<tr class ="inner_cell_journ"><td>'."Journals".'</td></tr>';
do{ //mini table for journals
    echo '<tr class ="inner_cell" ><td><a href = "journal_papers.php?journal_id=' . $journal->id . '">'.$journal->name.'</td></tr>';
}while ($journal->next());
echo '</table>';
echo '</td>';

echo '<td>';
echo '<table class="conference">';
echo '<tr class ="inner_cell_conf"><td>'."Conferences".'</td></tr>';
do{
    echo '<tr><td class ="inner_cell"><a href = "conference_meetings.php?conference_id=' . $conference->id . '">' .$conference->name.'</a></td></tr>';
}while ($conference->next());
echo '</table>';
echo '</td>';

echo '<td>';
echo '<table class="author">';
echo '<tr class ="inner_cell_auth"><td>'."Authors".'</td></tr>';
do{
    echo '<tr class ="inner_cell" ><td><a href = "author_papers.php?author_id=' . $author->id . '">'. $author->firstname. " " .$author->initial . " " .$author->lastname .'</td></tr>';
}while ($author->next());
echo '</table>';
echo '</td>';

echo '</tr>';
echo '</table>';
echo '</div>';
?>

<?php

require_once 'footer.php';

?>