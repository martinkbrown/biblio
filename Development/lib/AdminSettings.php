<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'Settings.php';

/**
 * Description of SiteSettings
 *
 * @author martin
 */
class AdminSettings extends Settings
{
    var $adminResultsPerPage;

    function SiteSettings($pathToSettings = 'admin/settings/admin_settings.txt')
    {
        parent::Settings($pathToSettings);
    }
}
?>
