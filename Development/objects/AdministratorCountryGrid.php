<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdministratorCountryGrid
 *
 * @author martin
 */
class AdministratorCountryGrid extends Grid
{
    function handleOptions($row)
    {
        $link = "<a href='main.php?page=edit_country&country_id=" . $row['id'] . "'>Edit</a>";
        return $link;
    }
}
?>
