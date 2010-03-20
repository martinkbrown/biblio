<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../lib/Grid.php';
/**
 * Description of AdminCountryGrid
 *
 * @author martin
 */
class AdminCountryGrid extends Grid {
    //put your code here
    function handleOptions($row)
    {
        return '<a href="main.php?page=edit_country&country_id=' . $row['id'] . '">Edit</a>';
    }
}
?>
