<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

require_once 'header.php';
require_once LIB . 'Grid.php';
require_once OBJECTS . 'Journal.php';
require_once LIB . 'RecaptchaSettings.php';
require_once 'jquery_lib.php';
require_once 'jquery_calendar_lib.php';
require_once 'jquery_autocomplete_lib.php';
require_once('recaptcha/recaptchalib.php');

// Getting Journal ID from previous page
$j_id = $_GET['journal_id'];
$journal = new Journal($j_id);
$j_name_display = $journal->getValue("name");
$j_acroynm_display = $journal->getValue("acronym");

?>

<?php
// Declaring Variables
// Variables for using Recaptcha
$recaptchaSettings = new RecaptchaSettings();
// Variable for Storing Journal once all data is Good to Go :)

if ($_POST) {
    // Validating Data
    $fv = new FormValidator();
    $fv->violatesDbConstraints('journal_paper', 'title',$_POST['journal_paper_title'],'Paper Title');

    // Null Checks
    $fv->isNull($_POST['journal_paper_title'], 'Paper Title');
    $fv->isNull($_POST['journal_first_name'], 'First Name');
    $fv->isNull($_POST['journal_last_name'], 'Last Name');
    $fv->isNull($_POST['journal_paper_startpg'], 'Start Page');
    $fv->isNull($_POST['journal_paper_endpg'], 'End Page');
    $fv->isNull($_POST['journal_volume'], 'Volume');
        $fv->isNull($_POST['journal_date'], 'Date');

    $fv->isValidCaptcha($recaptchaSettings->private_key);

    // Validating email address
    if ($fv->isEqual($_POST['user_email'], $_POST['user_conf_email'], 'Confirm Email',"Email addresses do not match")) {
        $fv->isEmailAddress($email, $_POST['user_email'], 'Your email');
        $fv->isNull($_POST['user_email'], 'Your email');
        $fv->violatesDbConstraints('journal', 'email',$_POST['user_email'] ,'Your Email');
    }

    // If errors exist, inform user else save data and display confirmation page
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
            $fv->addError("name","There was an error saving this Journal entry");
            $fv->listErrors();
        }

        // Display confirmation page
        $util = new Utilities();
        $util->redirect("submit_journal_verify.php");

    }
}

?>
<h2>Add a Journal Paper</h2>
Fields marked with * are required <br>
<form name ="frm_name" method ="POST">
    <table>
        <tr>
            <td>Journal Name*</td>
            <td>
                <?php echo $j_name_display?>
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Paper Title*</td>
            <td>
                <input type= "text" name= "journal_paper_title" size="46" value="<?php echo $_POST ['journal_paper_title'] ?>"/>
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Authors</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <br>
        <tr>
            <td>Main Author*</td>
            <td>First Name*
                <input type= "text" name= "journal_first_name" size="33" value="<?php echo $_POST ['journal_first_name'] ?>"/>
            </td>
            <td> Middle Initial*
                <input type= "text" name= "journal_middle_init" size="1" value="<?php echo $_POST ['journal_middle_init'] ?>"/>
            </td>
            <td> Last Name*
                <input type= "text" name= "journal_last_name" size="30" value="<?php echo $_POST ['journal_last_name'] ?>"/>
            </td>
        </tr>
        <tr><td></td><td> <a href="index.php">Click here to add another author</a></td>
        <tr>
            <td>Start Page*</td>
            <td>
                <input type= "text" name= "journal_paper_startpg" size="46" value="<?php echo $_POST ['journal_paper_startpg'] ?>"/>
            </td>
            <td></td><td></td>
        </tr>
        <tr>
            <td>End Page*</td>
            <td>
                <input type= "text" name= "journal_paper_endpg" size="46" value="<?php echo $_POST ['journal_paper_endpg'] ?>"/>
            </td>
            <td></td><td></td>
        </tr>
        <tr>
            <td>Volume*</td>
            <td>
                <input type= "text" name= "journal_volume" size="46" value="<?php echo $_POST ['journal_volume'] ?>"/>
            </td>
            <td></td><td></td>
        </tr>
        <tr>
            <td>Number</td>
            <td>
                <input type= "text" name= "journal_number" size="46" value="<?php echo $_POST ['journal_number'] ?>"/>
            </td>
            <td></td><td></td>
        </tr>
        <tr>
            <td>Date*</td>
            <td>
                <input type= "text" name= "journal_date" size="46" value="<?php echo $_POST ['journal_date'] ?>"/>
            </td>
            <td></td><td></td>
        </tr>
        <tr>
            <td>Your Email*</td>
            <td>
                <input type= "text" name= "user_email" size="46" value="<?php echo $_POST ['user_email'] ?>"/>
            </td>
            <td></td><td></td>
        </tr>
        <tr>
            <td>Confirm Email*</td>
            <td>
                <input type= "text" name= "user_conf_email" size="46" value="<?php echo $_POST ['user_conf_email'] ?>"/>
            </td>
            <td></td><td></td>
        </tr>
         <tr>
            <td></td>
            <td>
                <?php echo recaptcha_get_html($recaptchaSettings->public_key, $error);?>
            </td>
            <td></td><td></td>
        </tr>
        <tr>
            <td></td>
            <td> <input type="submit" name="submit" value="Submit"/></td>
        </tr>

    </table>
</form>







