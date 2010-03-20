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
class SiteSettings extends Settings
{
    var $siteTitle;
    var $resultsPerPage;
    var $siteUrl;

    function SiteSettings($pathToSettings = 'admin/settings/site_settings.txt')
    {
        parent::Settings($pathToSettings);
    }
}
?>
