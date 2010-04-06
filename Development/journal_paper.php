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
    $fv->isNull($_POST['journal_first_name[0]'], 'First Name');   // Ask Martin if this is OK
    $fv->isNull($_POST['journal_middle_init[0]'], 'Middle Initial');
    $fv->isNull($_POST['journal_last_name[0]'], 'Last Name');

    // Check if violates DB Contraints
    $fv->violatesDbConstraints('journal_paper', 'title', $_POST['journal_paper_title'], 'Title');
    $fv->violatesDbConstraints('journal_paper', 'start_page', $_POST['journal_paper_startpg'], 'Start Page');
    $fv->violatesDbConstraints('journal_paper', 'end_page', $_POST['journal_paper_endpg'], 'End Page');
    $fv->violatesDbConstraints('journal_paper', 'volume', $_POST['journal_paper_volume'], 'Volume');
    if ($_POST['journal_number']) {
        $fv->isANumber('Number', $_POST['journal_number']);
        $fv->violatesDbConstraints('journal_paper', 'number', $_POST['journal_number'], 'Number');
    }
    $fv->violatesDbConstraints('author','firstname', $_POST['journal_first_name[0]'], 'First Name');
    $fv->violatesDbConstraints('author','initial',$_POST['journal_middle_init[0]'],$_POST['journal_middle_init[0]']);
    $fv->violatesDbConstraints('author','lastname',$_POST['journal_last_name[0]'],$_POST['journal_last_name[0]']);

    $fv->isValidCaptcha($recaptchaSettings->private_key);

    // Validating email address
    if ($fv->isEqual($_POST['user_email'], $_POST['user_conf_email'], 'Confirm Email',"Email addresses do not match")) {
        $fv->isEmailAddress($email, $_POST['user_email'], 'Your email');
        $fv->isNull($_POST['user_email'], 'Your email');
        $fv->violatesDbConstraints('journal_paper', 'email',$_POST['user_email'] ,'Your Email');
    }

    // If errors exist, inform user else save data and display confirmation page
    if ($fv->hasErrors()) {
        $fv->listErrors();
    } else {
        // Save Form Data
        $journal_paper = new JournalPaper();
        $author = new Author();
        $journal_paper->setValue('journal_id', $j_id);
        $journal_paper->setValue('title', $_POST['journal_paper_title']);
        $journal_paper->setValue('start_page', $_POST['journal_paper_startpg']);
        $journal_paper->setValue('end_page', $_POST['journal_paper_endpg']);
        $journal_paper->setValue('create_date', $_POST['journal_paper_endpg']);
        $journal_paper->setValue('approved', 0);
        $journal_paper->setValue('volume',$_POST['journal_volume']);




        if ($_POST['journal_number']) {
            $journal_paper->setValue('number', $_POST['journal_number']);
        }
        $journal_paper->setValue('email',$_POST['user_email']);
        // FIXME Put Date Checker
        $journal_paper->setValue('create_date', mktime());

        // Saving Author
        $author->setValue('firstname',$_POST['journal_first_name'][0]);
        $author->setValue('initial',$_POST['journal_middle_init'][0]);
        $author->setValue('lastname',$_POST['journal_last_name'][0]);

        if($journal_paper->save()) {
            $fv->addMessage("name", "Great! Journal Entry Saved");
            $fv->listMessages();
        } else {
            $fv->addError("name","There was an error saving this Journal Paper");
            $fv->listErrors();
        }
        if($author->save()) {
            $fv->addMessage("name", "Great! Author Saved");
            $fv->listMessages();
        } else {
            $fv->addError("name","There was an error saving this author");
            $fv->listErrors();
        }

        // Get IDs to create Author Journal Paper Class
        $journal_paper_id = $journal_paper->getId();
        $author_id = $author->getId();

        // FIXME : We need a AuthorJournalPaper Class
        $author_journal_paper = new AuthorJournalPaper();



        // FIXME: We need a Journal Volume Number Class
        // Saving Journal Volume
        $journal_volume_number = new JournalVolumeNumber();





        // Display confirmation page
        $util = new Utilities();
        $util->redirect("submit_journal_paper_confirm.php");

    }
}

?>
<h2>Add a Journal Paper</h2>
Fields marked with * are required <br>
<form name ="frm_name" action="confirm_journal_paper_submit.php" method ="POST">
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
                <input type= "text" name= "journal_first_name[]" size="33" value="<?php echo $_POST ['journal_first_name'][0] ?>"/>
            </td>
            <td> Middle Initial*
                <input type= "text" name= "journal_middle_init[]" size="1" value="<?php echo $_POST ['journal_middle_init'][0] ?>"/>
            </td>
            <td> Last Name*
                <input type= "text" name= "journal_last_name[]" size="30" value="<?php echo $_POST ['journal_last_name'][0] ?>"/>
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







