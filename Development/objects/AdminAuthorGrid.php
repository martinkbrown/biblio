<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../lib/Grid.php';
/**
 * Description of AdminAuthorGrid
 *
 * @author martin
 */
class AdminAuthorGrid extends Grid {
    //put your code here
    function handle_name($row)
    {
        $name = $row['firstname'] . " ";
        $name .= $row['initial'] ? $row['initial'] . " " : "";
        $name .= $row['lastname'];

        return $name;
    }

    function handle_options($row)
    {
        return '<a href="main.php?page=edit_author&author_id=' . $row['id'] . '">Edit</a>';
    }
}
?>
