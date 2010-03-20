<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Settings.php';
/**
 * Description of RecaptchaSettings
 *
 * @author martin
 */
class RecaptchaSettings extends Settings{
    //put your code here

    var $public_key = '';
    var $private_key = '';

    function RecaptchaSettings($pathToSettings = 'admin/settings/recaptcha_settings.txt')
    {
        parent::Settings($pathToSettings);
    }
}
?>
