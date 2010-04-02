<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once FRONT_END . LIB . 'Grid.php';
/**
 * Description of AuthorGrid
 *
 * @author martin
 */
class AuthorGrid extends Grid
{
    function handle_name($row)
    {
        return '<a href="author_papers.php?author_id='.$row['id'].'">'.
                    $row['firstname'] . ' ' . $row['initial'] . ' ' . $row['lastname'] . '</a>';
    }
}

?>
