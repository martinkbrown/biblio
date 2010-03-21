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

// Variable for Storing Journal once All data is Good to Go :)

if ($_POST) {
    // Validating Data
    $fv = new FormValidator();
    $fv->violatesDbConstraints('journal', 'name','journal_name' ,'Journal Name');
    $fv->violatesDbConstraints('journal', 'acronym','journal_acnym' ,'Acronym');
    $fv->isValidCaptcha($recaptchaSettings->private_key);

    // Validating email address
    
    if ($fv->isEqual($_POST['user_email'], $_POST['user_conf_email'], 'Confirm Email',"Email addresses do not match")) {
        $fv->isEmailAddress($email, $_POST['user_email'], 'Your email');        
        $fv->violatesDbConstraints('journal', 'email',$_POST['user_email'] ,'Your Email');
    }

    if ($fv->hasErrors()) {
        $fv->listErrors();
    } else {
        // Save Form Data
        $journal = new Journal();
        $journal->setValue('name', $_POST['journal_name']);
        $journal->setValue('acronym', $_POST['journal_acnym']);
        $journal->setValue('approved', 0);
        $journal->setValue('email',$_POST['user_email']);
        $journal->setValue('create_date', mktime());

        if($journal->save()) {
            $fv->addMessage("name", "Great! Journal Entry Saved");
            $fv->listMessages();
        } else {
            $fv->addError("name","There was an error saving the publisher record");
            $fv->listErrors();
        }
        $util = new Utilities();
        $util->redirect("submit_journal_verify.php");

    }
}
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










