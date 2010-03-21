<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/
require_once 'header.php';

require_once LIB . 'RecaptchaSettings.php';
require_once FRONT_END . OBJECTS . 'Journal.php';
require_once 'jquery_lib.php';
require_once 'jquery_calendar_lib.php';
require_once 'jquery_autocomplete_lib.php';
require_once('recaptcha/recaptchalib.php');
?>
<?php
// Declaring Variables

// Variables for using Recaptcha
$recaptchaSettings = new RecaptchaSettings();
$ff = new FormValidator();
$ff->isValidCaptcha($recaptchaSettings->private_key);

// Variable for Storing Journal once All data is Good to Go :)




// Validating Data

$fv = new FormValidator();
// Validating email address
if ($fv->isEqual($user_email, $user_conf_email, 'Confirm Email'," Email addresses do not match")) {
    $fv->isEmailAddress($email, $user_email, 'Your email');
    $fv->violatesDbConstraints('journal', 'email','validated_email' ,'Your Email');
}

$fv->violatesDbConstraints('journal', 'name','journal_name' ,'Journal Name');
$fv->violatesDbConstraints('journal', 'acronym','journal_acnym' ,'Acronym');



?>


<h2>Add a Journal</h2>
Fields marked with * are required <br>

<form name ="frm_name" method ="POST">
    <table>
        <tr>
            <td>Journal Name*</td>
            <td>
                <input type= text name= 'journal_name' value="<?php echo $_POST ["journal_name"] ?>"/>
            </td>
        </tr>
        <tr>
            <td> Acronym</td>
            <td> <input type= text name= 'journal_acnym' value="<?php echo $_POST ["journal_acnym"] ?>"/>
        </tr>
        <tr>
            <td> Your email*</td>
            <td> <input type= text name= 'user_email' value="<?php echo $_POST ["user_email"] ?>"/>
        </tr>
        <tr>
            <td> Confirm email*</td>
            <td> <input type= text name= 'user_conf_email' value="<?php echo $_POST ["user_conf_email"] ?>"/>
        </tr>
        <tr>
            <td></td>
            <td>
                <?php echo recaptcha_get_html($recaptchaSettings->public_key, $error);?>
            </td>
        </tr>
        <tr>
            <td></td>
            <td> <input type="submit" name="submit" value="Submit"/></td>
        </tr>
    </table>

</form>










