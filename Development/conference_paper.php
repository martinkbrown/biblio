<?php
/*
 * This file is to serve the purpose of adding a new conference paper
 * The page is dynamic in the sense of adding and remove co-authors
 * depending on the paper
 * Author: Sherene Campbell
 */

require_once 'header.php';
require_once LIB . 'Grid.php';
require_once LIB . 'RecaptchaSettings.php';
require_once FRONT_END . OBJECTS . 'Conference.php';
require_once 'jquery_lib.php';
require_once 'jquery_calendar_lib.php';
require_once 'jquery_autocomplete_lib.php';
require_once FRONT_END . OBJECTS . 'ConferenceMeeting.php';
require_once FRONT_END . OBJECTS . 'Publisher.php';
require_once('recaptcha/recaptchalib.php');

$recaptchaSettings = new RecaptchaSettings();

$conference = new Conference($_GET['conf_id'] ? $_GET['conf_id'] : $_POST['conf_id']);
$conference_meeting = new ConferenceMeeting();
$ff = new FormValidator();
$ff->isValidCaptcha($recaptchaSettings->private_key);
$conf_id = $conference->getId();
$conference_exisit = new Conference($conf_id);

echo "<h2>Add a Conference Paper</h2>";
echo "Fields marked with * are required";

if($_POST)
{
    $fv = new FormValidator();
    $fv->violatesDbConstraints('conference_meeting', 'title', $conference_meeting->getFormValue('paper_title'),'Paper Tile');
    //$fv->violatesDbConstraints('conference_meeting', 'author', $conference_meeting->getFormValue('conf_author'),'Author');
    $fv->violatesDbConstraints('conference_meeting', 'start_page', $conference->getFormValue('start_pg'),'Start Page');
    $fv->violatesDbConstraints('conference_meeting', 'end_page', $conference_meeting->getFormValue('end_pg'),'End Page');
    $check_email = $fv->oracle_string($conference->getFormValue('email'),$conference_meeting->getFormValue('confirm_email'));

    if ($check_email){
        $fv->violatesDbConstraints('conference_meeting', 'email', $conference_meeting->getFormValue('email'),'Email');
        $fv->isEmailAddress("email", $conference_meeting->getFormValue('email'), "Email");
    }
   // else {echo "Emails do not match";}
    //no errors then try save record
    $flag_recapcha = true;
    if($ff->hasErrors())
    {
        $flag_recapcha = false;
        $ff->listErrors();
    }
    if(($fv->hasErrors() || ($check_email == false || ($flag_recapcha==false))))
    {
        $fv->listErrors();
    }
    else {

        $conference_meeting->setValue('title',$conference_meeting->getFormValue('paper_title'));
        $conference_meeting->setValue('start_page',$conference_meeting->getFormValue('start_pg'));
        $conference_meeting->setValue('end_page',$conference_meeting->getFormValue('end_pg'));
        $conference_meeting->setValue('email',$conference_meeting->getFormValue('email'));
        $conference->setValue('approved',0);
        $util = new Utilities();
        $util->redirect("submit_verify.php");
    }
}
?>


<div class="form_message" id="conf_exists"><?php echo isset($_GET['ce']) ? $conf_exists_msg : "&nbsp;"?></div>

<form name ="frm_name" method="POST">
    <!---this creates the table for the layout of the form--->
    <table>
        <tr>
            <td>Conference Meeting</td>
            <td>
              <?php 
                  echo $conference_exisit->getValue("name");
                  print '<input type="hidden" id="conf_id" name="conf_id" value="' . ($_GET['conf_id'] ? $_GET['conf_id'] : $_POST['conf_id']) . '"/>';
              ?>
            </td>
        </tr>
        <tr>
            <td>Paper Title*</td>
            <td> <input type ="text" id="conf_paper" name ="paper_title" size="40" value ="<?php echo $conference_meeting->getFormValue('paper_title');?>" /></td>
        </tr>
        <tr>
            <td>Authors</td>
        </tr>
        <tr id="authors">
            <td>Main Author <input id ="id_add" type ="hidden" value="1"/></td>
            <td> First Name* <input type ="text" name ="first_name" size ="27" id="fn"/></td>
            <td> Middle Inital <input type ="text" name ="mid_initial" size ="10" id="mi"/></td>
            <td> Last Name* <input type ="text" name ="last_name" size ="27" id="ln"/></td>
        </tr>
        <tr>
            <td><a id="adder" href="javascript:void;">Click here to add another author</a></td><td>&nbsp;</td>
        </tr>
        <tr>
            <td>Start Page*</td>
            <td><input type ="text" name ="start_pg" size="40" value="<?php echo $conference_meeting->getFormValue('start_pg'); ?>"/></td>
        </tr>
        <tr>
            <td>End Page*</td>
            <td><input type ="text" name ="end_pg" size="40" value="<?php echo $conference_meeting->getFormValue('end_pg'); ?>"/></td>
        </tr>
        <tr>
            <td>Your Email*</td>
            <td><input type ="text" name ="email" size="40" value="<?php echo $conference_meeting->getFormValue('email'); ?>"/></td>
        </tr>
        <tr>
            <td>Confirm Email*</td>
            <td><input type ="text" name ="confirm_email" size="40"></td>
        </tr>
        <tr>
            <td></td>
            <td>
                    <?php echo recaptcha_get_html($recaptchaSettings->public_key, $error);?>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" name="submit" value="Submit"/>
            </td>
        </tr>
    </table>
</form>
<script>

function removeFormField(id) {
    //alert('#'+id);
    $(id).remove();
}

var counter = 0;
$("#adder").click(function()
{

    //alert('+counter+');
    $("#authors").after('<tr id ="'+counter+'"><td><td>First Name* <input type ="text" name ="first_name" size ="27" id="fn"/></td><td> Middle Inital <input type ="text" name ="mid_initial" size ="10" id="mi"/></td><td> Last Name* <input type ="text" name ="last_name" size ="27" id="ln"/></td><td><a href="javascript:void;" onClick="removeFormField($(this).parent());">Remove</a></td></td></tr>');
    counter++;
});

</script>
