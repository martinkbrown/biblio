<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../lib/Grid.php';
/**
 * Description of AdminPublisherGrid
 *
 * @author martin
 */
class AdminpublisherGrid extends Grid {
    //put your code here
    function handleOptions($row)
    {
        return '<a href="main.php?page=edit_publisher&publisher_id=' . $row['id'] . '">Edit</a>';
    }
}
?>
