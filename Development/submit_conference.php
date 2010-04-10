<?php
/*
 * This page creates a form for the user to fill out information
 * to add a conference to the database.
 * Author: Sherene Campbell
 * Date: 3/1/2010
*/

require_once 'header.php';
require_once LIB . 'Grid.php';
require_once LIB . 'RecaptchaSettings.php';
require_once FRONT_END . OBJECTS . 'Conference.php';
require_once 'jquery_lib.php';
require_once 'jquery_calendar_lib.php';
require_once 'jquery_autocomplete_lib.php';
require_once FRONT_END . OBJECTS . 'ConferenceMeeting.php';
require_once FRONT_END . OBJECTS . 'ISBNtest.php';
require_once FRONT_END . OBJECTS . 'Publisher.php';
require_once('recaptcha/recaptchalib.php');
require_once OBJECTS.'State.php';
require_once OBJECTS.'Country.php';

//set up object creation for later
//function confirm_email($email,$confim_email){
//    if ($email != $confim_email){
//        return false;
//    } else return true;
//}
$recaptchaSettings = new RecaptchaSettings();
$country = new Country("SELECT * FROM country ORDER BY name");
$conference = new Conference($_GET['conf_id'] ? $_GET['conf_id'] : $_POST['conf_id']);
$conference_meeting = new ConferenceMeeting();
$publisher = new Publisher($_POST['publisher_id']);
$stateDd = new DropDownList('name');
$stateDd->setFieldName("state_id");
$stateDd->setExtraValues(array(0=>"-- Select Country first --"));
$stateDd->setSelectedValues($conference->getFormValue("state_id"));
$stateDd->setProperty("id","state_id");
$state = new State();
$countryDd = new DropDownList('name');
$countryDd->setFieldName("country_id");
$countryDd->setSelectedValues($conference->getFormValue("country_id"));
$countryDd->setProperty("id","country_id");
$ff = new FormValidator();
$ff->isValidCaptcha($recaptchaSettings->private_key);
$conf_id = $conference->getId();
$conference_exisit = new Conference($conf_id);

$conf_exists_msg = 'This Conference exists. You may ignore this message or click <a href="' . $siteSettings->siteUrl . 'conference_meetings.php?conference_id=' . $conf_id . '">here</a> if you wish to view it.';

if ($flag_conf_exsist == false){
    echo "<h2>Add a Conference</h2>";
}
else
    echo "<h2>Add a Conference Meeting</h2>";

echo "<h4>Fields marked with * are required</h4>";

if ($conf_id==""){
    //should i have submit a new conference or add a new conference meeting
    $flag_conf_exsist = false;
}
else $flag_conf_exsist = true;
//Time to check data!!!
if($_POST)
{
    $fv = new FormValidator();
    if ($flag_conf_exsist == false)
    {
        $fv->violatesDbConstraints('conference', 'name', $conference->getFormValue('conf_name'),'Conference Name');
        $fv->violatesDbConstraints('conference', 'acronym', $conference->getFormValue('conf_acnym'),'Acronym');
    }
    $fv->violatesDbConstraints('conference_meeting', 'name', $conference_meeting->getFormValue('conf_meet'),'Conference Meeting');
    $fv->violatesDbConstraints('conference_meeting', 'city', $conference_meeting->getFormValue('conf_city'),'City');
    $fv->violatesDbConstraints('conference_meeting', 'country_id', $conference_meeting->getFormValue('country_id'),'Country');
    $fv->violatesDbConstraints('conference_meeting', 'state_id', $conference_meeting->getFormValue('state_id'),'State');
    //changed from violatesDbConstraints to isNull because the user may enter a publisher
    //that already exists
    //DROP DOWN so i deleted - $fv->isNull('publisher', 'name', $publisher->getFormValue('pub'),'Publisher');
    $fv->violatesDbConstraints('conference_meeting', 'publisher_website', $conference->getFormValue('pub_web'),'Publisher Website');
    $fv->violatesDbConstraints('conference_meeting', 'isbn', $conference_meeting->getFormValue('pub_isbn'),'ISBN');
    $fv->isValidIsbn('isbn', $conference_meeting->getFormValue('pub_isbn'),'ISBN');
    $fv->violatesDbNull('conference_meeting', 'start_date', $conference_meeting->getFormValue('start_date'),'Start Date');
    $fv->violatesDbNull('conference_meeting', 'end_date', $conference_meeting->getFormValue('end_date'),'End Date');
    $fv->isValidDateRange("Start Date", "End Date", $conference_meeting->getFormValue('start_date'), $conference->getFormValue('end_date'));
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
        if ($flag_conf_exsist == false)
        {
            $conference->setValue('name',$conference->getFormValue('conf_name'));
            $conference->setValue('acronym',$conference->getFormValue('conf_acnym'));
        }
        $conference_meeting->setValue('name', $conference_meeting->getFormValue('conf_meet'));
        $conference_meeting->setValue('city',$conference_meeting->getFormValue('conf_city'));
        $conference_meeting->setValue('country_id',$conference_meeting->getFormValue('country_id'));
        $conference_meeting->setValue('state_id',$conference_meeting->getFormValue('state_id'));

        //if there is a value for publisher_id then
        //this is not a new publisher. Set the publisher_id
        //in the conference meeting
        if($_POST['publisher_id'])
        {
            $conference_meeting->setValue('publisher_id',$conference_meeting->getFormValue('publisher_id'));
        }
        else
        {
            $publisher->setValue('name',$publisher->getFormValue('pub'));
        }

        $conference_meeting->setValue('conference_website',$conference_meeting->getFormValue('conf_web'));
        $conference_meeting->setValue('publisher_website',$conference_meeting->getFormValue('pub_web'));
        $conference_meeting->setValue('isbn',$conference_meeting->getFormValue('pub_isbn'));
        $conference_meeting->setValue('start_date',$conference_meeting->getFormValue('start_date'));
        $conference_meeting->setValue('end_date',$conference_meeting->getFormValue('end_date'));
        $conference_meeting->setValue('email',$conference_meeting->getFormValue('email'));
        $conference->setValue('approved',0);
        if ($flag_conf_exsist == false)
        {
            if($conference->save())
            {
                $sql =& sql();
                $conference_meeting->setValue('conference_id', $sql->id());
                $fv->addMessage("name", "Conference saved");
                $fv->listMessages();
            }
            else
            {
                $fv->addError("name","There was an error saving the conference record");
                $fv->listErrors();
            }
        }
        else $conference_meeting->setValue('conference_id', $conf_id);

            if($conference_meeting->save())
            {
                $sql_conf =& sql();

                $fv->addMessage("name", "Conference Meeting saved");
                $fv->listMessages();
            }
            else
            {
                $fv->addError("name","There was an error saving the conference meeting record");
                $fv->listErrors();
            }

            //only save the publisher if it doesn't exist already
            if(!$_POST['publisher_id'])
            {
                    if($publisher->save())
                    {
                        $fv->addMessage("name", "Publisher saved");
                        $fv->listMessages();
                    }
                    else
                    {
                        $fv->addError("name","There was an error saving the publisher record");
                        $fv->listErrors();
                    }
            }

            $util = new Utilities();
            if ($flag_conf_exsist == false){
                $util->redirect("submit_verify.php?submit_id=1");
            }else
                $util->redirect("submit_verify.php?submit_id=2");
    }
}


?>

<div class="form_message" id="conf_exists"><?php echo isset($_GET['ce']) ? $conf_exists_msg : "&nbsp;"?></div>
<form name ="frm_name" method="POST">
    <!---this creates the table for the layout of the form--->
    <table>
        <tr>
            <td>Conference* <a id="help-button" href="javascript:;">Help</a></td>
            <td>
              <?php if ($flag_conf_exsist == false)
                  {print"<input id='conf_name' autocomplete='off' type='text' name='conf_name' value='".$conference->getFormValue('conf_name')."'; size='40'>\n";
              }
              else {
                  echo $conference_exisit->getValue("name");
              }
              print '<input type="hidden" id="conf_id" name="conf_id" value="' . ($_GET['conf_id'] ? $_GET['conf_id'] : $_POST['conf_id']) . '"/>';
              ?>
            </td>
            <td>
               <?php if ($flag_conf_exsist == false)
               {
                   echo "Acronym*";
               }?>
            </td>
            <td>
               <?php if ($flag_conf_exsist == false)
               {print"<input id='conf_acnym' type="."text"." name= 'conf_acnym' value='".$conference->getFormValue('conf_acnym')."'; size='25'>\n";
               }?>
            </td>
        </tr>
        <tr>
            <td>Conference Meeting*</td>
            <td> <input type ="text" id="conf_meet" name ="conf_meet" size="40" value ="<?php echo $conference_meeting->getFormValue('conf_meet');?>" /></td>
        </tr>
        <tr>
            <td> City* </td>
            <td> <input type ="text" name ="conf_city" size="40" value ="<?php echo $conference_meeting->getFormValue('conf_city'); ?>" /></td>
        </tr>
        <tr>
            <td>Country*</td>
            <td><?php echo $countryDd->getDropDownFromRecordset($country);?></td>
        </tr>
        <tr>
            <td>State/Province</td>
            <td><span id="states"><?php echo $stateDd->getDropDownFromRecordset($state);?></span></td>
        </tr>
        <tr>
            <td>Publisher*</td>
            <td>
                <input autocomplete="off" type ="text" id="publisher" name ="pub" size="40" value ="<?php echo $publisher->getFormValue('pub');?>"/>
                <input type="hidden" name="publisher_id" id="publisher_id"/>
            </td>
        </tr>
        <tr>
            <td>Publisher website</td>
            <td><input type ="text" name ="pub_web" size="40" value="<?php echo $conference_meeting->getFormValue('pub_web'); ?>"/></td>
        </tr>
        <tr>
            <td>Conference website</td>
            <td><input type ="text" name ="conf_web" size="40" value="<?php echo $conference_meeting->getFormValue('conf_web'); ?>"/></td>
        </tr>
        <tr>
            <td>ISBN*</td>
            <td><input type ="text" name ="pub_isbn" size="40" value="<?php echo $conference_meeting->getFormValue('pub_isbn'); ?>"/></td>
        </tr>
        <tr>
            <td>Date</td>
            <td>Starting*<input type="text" name="start_date" id="start_date" value="<?php echo $conference_meeting->getFormValue('start_date');?>" size="25"></td>
            <td>Ending*</td>
            <td><input type="text" name="end_date" id="end_date" value="<?php echo $conference_meeting->getFormValue('end_date');?>" size="25"></td>
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

<div id="help-box" title="Help">
    <p>
        The Conference field requires you to enter the name of the Conference.
        The Conference Meeting field requires you to specify a meeting that
        took place under the Conference.

        <h3>Examples</h3>
        <ol>
            <li><strong>Conference</strong>: ACM Southeast Regional Conference</li>
            <li><strong>Acronym</strong>: ACMSE</li>
            <li><strong>Conference Meeting</strong>: The 1st ACM Southeast Conference</li>
        </ol>

        <ol>
            <li><strong>Conference</strong>: Supercomputing</li>
            <li><strong>Acronym</strong>: SC</li>
            <li><strong>Conference Meeting</strong>: Supercomputing 2009</li>
        </ol>

        <ol>
            <li><strong>Conference</strong>: International Symposium on Performance Analysis of Systems and Software</li>
            <li><strong>Acronym</strong>: ISPASS</li>
            <li><strong>Conference Meeting</strong>: ISPASS 2003</li>
        </ol>

    </p>
</div>


<script type="text/javascript">

$(document).ready(function() {
	var $dialog = $('#help-box')
		.dialog({
			autoOpen: false,
                        modal: true,
                        resizable: false,
                        closeText: 'Close',
                        width: 350,
			title: 'Help for Add a Conference'
		});

	$('#help-button').click(function() {
		$dialog.dialog('open');
	});
});

//here we add the date picker
$(function()
{
    $("#start_date").datepicker();
});

$(function()
{
    $("#end_date").datepicker();
});

//this is all that's needed to load the states dynamically by country'
//of course once it is in the <script> tag
$("#country_id").change(function()
{
    $("#states").load("get_states_drop_down.php?state_id=<?php echo $conference_meeting->getFormValue("state_id");?>&country_id="+$('#country_id').val());
});

$("#states").load("get_states_drop_down.php?state_id=<?php echo $conference_meeting->getFormValue("state_id");?>&country_id="+$('#country_id').val());

$("#publisher").autocomplete("get_publishers_autocomplete.php");
$('publisher').setOptions({ max: 5 });
$("#publisher").result(function(event, data, formatted)
{
    $("#publisher_id").val(data[1]);
});

$("#conf_name").autocomplete("get_conferences_autocomplete.php");
$('conf_name').setOptions({ max: 5 });
$("#conf_name").result(function(event, data, formatted)
{
    $("#conf_id").val(data[1]);
    link = '<a href="<?php echo $siteSettings->siteUrl . "conference_meetings.php?conference_id="?>'+data[1]+'">here</a>';
    $("#conf_exists").html("This Conference exists. You may ignore this message or click " + link + " if you wish to view it.");

    link = "submit_conference.php?conf_id="+data[1]+"&ce";

    $("#conf_acnym").focus(function()
    {
        window.location.href=link;
    });

    $("#conf_meet").focus(function()
    {
        window.location.href=link;
    });




});

</script>