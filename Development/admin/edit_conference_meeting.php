<?php

require_once FRONT_END . OBJECTS . 'ConferenceMeeting.php';
require_once FRONT_END . OBJECTS . 'Conference.php';
require_once FRONT_END . OBJECTS . 'State.php';
require_once FRONT_END . OBJECTS . 'Country.php';
require_once FRONT_END . OBJECTS . 'Publisher.php';
require_once 'jquery_lib.php';
require_once 'jquery_calendar_lib.php';

$conferenceMeeting = new ConferenceMeeting($conference_meeting_id);

$conference = new Conference("SELECT * FROM conference ORDER BY name");

$state = new State();

$country = new Country("SELECT * FROM country ORDER BY name");

$publisher = new Publisher("SELECT * FROM publisher ORDER BY name");

$conferenceDd = new DropDownList('name');
$conferenceDd->setFieldName("conference_id");
$conferenceDd->setSelectedValues($conferenceMeeting->getFormValue("conference_id"));
$conferenceDd->setProperty("id","conference_id");

$stateDd = new DropDownList('name');
$stateDd->setFieldName("state_id");
$stateDd->setExtraValues(array(0=>"-- Select Country first --"));
$stateDd->setSelectedValues($conferenceMeeting->getFormValue("state_id"));
$stateDd->setProperty("id","state_id");

$countryDd = new DropDownList('name');
$countryDd->setFieldName("country_id");
$countryDd->setSelectedValues($conferenceMeeting->getFormValue("country_id"));
$countryDd->setProperty("id","country_id");

$publisherDd = new DropDownList('name');
$publisherDd->setFieldName("publisher_id");
$publisherDd->setSelectedValues($state->getFormValue("publisher_id"));

if($conferenceMeeting->id)
{
?>

<h2>Edit Conference Meeting <?php echo $conferenceMeeting->getValue('name'); ?></h2>

<?php
}
else
{
    ?>
<h2>Add a Conference</h2>
<?php
}

if($_POST)
{
    $fv = new FormValidator();
    $fv->violatesDbConstraints('conference_meeting', 'name', $conferenceMeeting->getFormValue('name'),'Conference Meeting');
    $fv->violatesDbConstraints('conference_meeting', 'conference_id', $conferenceMeeting->getFormValue('conference_id'),'Conference');
    $fv->violatesDbConstraints('conference_meeting', 'country_id', $conferenceMeeting->getFormValue('country_id'),'Country');
    $fv->violatesDbConstraints('conference_meeting', 'state_id', $conferenceMeeting->getFormValue('state_id'),'State');
    $fv->violatesDbConstraints('conference_meeting', 'publisher_id', $conferenceMeeting->getFormValue('publisher_id'),'Publisher');
    $fv->violatesDbConstraints('conference_meeting', 'publisher_website', $conferenceMeeting->getFormValue('publisher_website'),'Publisher Website');
    $fv->violatesDbConstraints('conference_meeting', 'conference_website', $conferenceMeeting->getFormValue('conference_website'),'Conference Website');
    $fv->violatesDbConstraints('conference_meeting', 'isbn', $conferenceMeeting->getFormValue('isbn'),'ISBN');
    $fv->isNull('conference_meeting', 'start_date', $conferenceMeeting->getFormValue('start_date'),'Start Date');
    $fv->isNull('conference_meeting', 'end_date', $conferenceMeeting->getFormValue('end_date'),'End Date');
    $fv->isValidDateRange("Start Date", "End Date", $conferenceMeeting->getFormValue('start_date'), $conferenceMeeting->getFormValue('end_date'));
    $fv->violatesDbConstraints('conference_meeting', 'email', $conferenceMeeting->getFormValue('email'),'Email');
    $fv->isEmailAddress("email", $conferenceMeeting->getFormValue('email'), "Email");

    if($fv->hasErrors())
    {
        $fv->listErrors();
    }
    else
    {
        $conferenceMeeting->setValue('name',$conferenceMeeting->getFormValue('name'));
        $conferenceMeeting->setValue('conference_id',$conferenceMeeting->getFormValue('conference_id'));
        $conferenceMeeting->setValue('city',$conferenceMeeting->getFormValue('city'));
        $conferenceMeeting->setValue('country_id',$conferenceMeeting->getFormValue('country_id'));
        $conferenceMeeting->setValue('state_id',$conferenceMeeting->getFormValue('state_id'));
        $conferenceMeeting->setValue('publisher_id',$conferenceMeeting->getFormValue('publisher_id'));
        $conferenceMeeting->setValue('publisher_website',$conferenceMeeting->getFormValue('publisher_website'));
        $conferenceMeeting->setValue('conference_website',$conferenceMeeting->getFormValue('conference_website'));
        $conferenceMeeting->setValue('isbn',$conferenceMeeting->getFormValue('isbn'));
        $conferenceMeeting->setValue('start_date',$conferenceMeeting->getFormValue('start_date'));
        $conferenceMeeting->setValue('end_date',$conferenceMeeting->getFormValue('end_date'));
        $conferenceMeeting->setValue('email',$conferenceMeeting->getFormValue('email'));
        $conferenceMeeting->setValue('approved',$conferenceMeeting->getFormValue('approved'));
        
        if($conferenceMeeting->save())
        {
            $fv->addMessage("name", "Conference Meeting saved");
            $fv->listMessages();
        }
        else
        {
            $fv->addError("name","There was an error saving the record");
            $fv->listErrors();
        }

    }
}


?>

<form name="conference_meetings" id="conference_meetings" method="POST" action="main.php?page=edit_conference_meeting&conference_meeting_id=<?php echo $conferenceMeeting->id; ?>">
    <table>
        <tr>
            <td>Conference Meeting</td>
            <td><input type="text" name="name" value="<?php echo $conferenceMeeting->getFormValue('name');?>"/></td>
        </tr>
        <tr>
            <td>Conference</td>
            <td><?php echo $conferenceDd->getDropDownFromRecordset($conference); ?></td>
        </tr>
        <tr>
            <td>City</td>
            <td><input type="text" name="city" value="<?php echo $conferenceMeeting->getFormValue('city');?>"/></td>
        </tr>
        <tr>
            <td>Country</td>
            <td><?php echo $countryDd->getDropDownFromRecordset($country); ?></td>
        </tr>
        <tr>
            <td>State</td>
            <td><span id="states"><?php echo $stateDd->getDropDownFromRecordset($state); ?></span></td>
        </tr>
        <tr>
            <td>Publisher</td>
            <td><?php echo $publisherDd->getDropDownFromRecordset($publisher); ?></td>
        </tr>
        <tr>
            <td>Publisher Website</td>
            <td><input type="text" name="publisher_website" value="<?php echo $conferenceMeeting->getFormValue('publisher_website');?>"/></td>
        </tr>
        <tr>
            <td>Conference Website</td>
            <td><input type="text" name="conference_website" value="<?php echo $conferenceMeeting->getFormValue('conference_website');?>"/></td>
        </tr>
        <tr>
            <td>ISBN</td>
            <td><input type="text" name="isbn" value="<?php echo $conferenceMeeting->getFormValue('isbn');?>"/></td>
        </tr>
        <tr>
            <td>Start Date</td>
            <td><input type="text" name="start_date" id="start_date" value="<?php echo $conferenceMeeting->getFormValue('start_date');?>"/></td>
        </tr>
        <tr>
            <td>End Date</td>
            <td><input type="text" name="end_date" id="end_date" value="<?php echo $conferenceMeeting->getFormValue('end_date');?>"/></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input type="text" name="email" value="<?php echo $conferenceMeeting->getFormValue('email');?>"/></td>
        </tr>
        <tr>
            <td>Approved</td>
            <td><input type="checkbox" name="approved" value="1" <?php echo $conferenceMeeting->getFormValue('approved') ? "checked " : "";?>/></td>
        </tr>

        <tr>
            <td colspan="2">
                <input type="submit" name="submit" value="Submit"/>
            </td>
        </tr>
    </table>
</form>

<script type="text/javascript">

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
    $("#states").load("../get_states_drop_down.php?state_id=<?php echo $conferenceMeeting->getFormValue("state_id");?>&country_id="+$('#country_id').val());
});

$("#states").load("../get_states_drop_down.php?state_id=<?php echo $conferenceMeeting->getFormValue("state_id");?>&country_id="+$('#country_id').val());


</script>