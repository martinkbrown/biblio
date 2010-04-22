<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'header.php';
require_once LIB . 'Grid.php';
require_once OBJECTS . 'Journal.php';

$j_id = $_GET['journal_id'];
$journal = new Journal($j_id);
$j_name_display = $journal->getValue("name");
$j_acroynm_display = $journal->getValue("acronym");

?>
<div class ="conf_back">
 <span class="solid_writting"><?php echo ($j_name_display); echo " ("; echo ($j_acroynm_display); echo ")";?> </span> <br>

 <br>
<?php
echo "<a href=journal_paper.php?journal_id=$j_id>Click here to add a paper to $j_name_display</a><br></br>";
?>
</div>
