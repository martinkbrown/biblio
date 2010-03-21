<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of JournalGrid
 * FIXME
 * @author Sihle
 */
require_once FRONT_END . LIB . 'Grid.php';
class JournalGrid extends Grid {

    function handle_name($row) {
        return '<a href="journal_papers.php?journal_id='.$row['id'].'">'.$row['name'].'</a>';
    }
}
?>
