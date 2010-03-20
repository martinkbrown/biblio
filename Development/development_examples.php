
        <?php

        require_once 'header.php';
        require_once 'jquery_lib.php';
        require_once 'jquery_calendar_lib.php';
        require_once 'jquery_autocomplete_lib.php';
        require_once OBJECTS . 'Country.php';
        require_once OBJECTS . 'State.php';
        require_once OBJECTS . 'ConferenceMeeting.php';
        require_once LIB . 'RecaptchaSettings.php';
        require_once OBJECTS . 'ISBNtest.php';
        require_once LIB . 'FormValidator.php';

        require_once('recaptcha/recaptchalib.php');

        class TestGrid extends Grid
        {
            function handleOptions($row)
            {
                return "View";
            }

            function handleName($row)
            {
                return "<a href=\"development_examples.php?id=".$row['id']."\">".$row['name']."</a>";
            }
        }
        
        ?>
<script type='text/javascript' src='js/jquery/jquery-autocomplete/jquery.autocomplete.js'></script>
Hello Team!

<p>On this page you will find some examples of some of the things you will need to do.</p>

<ol>
    <li>For every page you create, make sure you have included header.php in the beginning,
        and footer.php at the end of the file.</li>
    <li>Open this file in index.php and you will see how to create a drop down list</li>
    <li>You will also find how to create the beautiful calendar field on this page</li>
    <li>As you can see, if you select the country United States, the states field gets
        populated. Check it out</li>
    <li>And at the bottom, there's the recaptcha field!</li>
</ol>

<?php

    $country = new Country();
    $country->loadCountries();

    $recaptchaSettings = new RecaptchaSettings();

    $dd = new DropDownList('name');
    $dd->setProperty("name","country_id");
    $dd->setProperty("id","country_id");
    echo $dd->getDropDownFromRecordset($country);

?>

<span id="states">Select country first</span>



<p>Now try submitting this form. The country field is not supposed to be null,
    and it can't be more than 255 characters long. Also, the start date should not
    be later than the end date. Try violating those constraints</p><br/><br/><br/>

<?php

$country = new Country();
$ff = new FormValidator();

if($_POST['submit'])
{
    $ff->isValidIsbn("isbn", $_POST['isbn'], "ISBN");
    $ff->violatesDbConstraints("country", "name", $country->getFormValue('name'), "Country");
    //$ff->isValidDateRange("Start Date","End Date",$country->getFormValue('start_date'),$country->getFormValue('end_date'));
    //$ff->isValidCaptcha($recaptchaSettings->private_key);

    if($ff->hasErrors())
    {
        $ff->listErrors();
    }
    //else
    //{
    //    $country->setValue('name',$_GET['country_name']);
    //    $country->save();
        //here we would call $country->save(); but we don't want to save it right now!
    //}
}

?>

<form name="test_form" method="POST">
<p>Start Date: <input type="text" id="start_date" name="start_date" value="<?php echo $country->getFormValue('start_date');?>"></p>
<p>End Date  : <input type="text" id="end_date" name="end_date" value="<?php echo $country->getFormValue('end_date');?>"></p>

    Country <input type="text" name="country_name" value="<?php echo $country->getFormValue('name'); ?>"/> <br/><br/>
    ISBN <input type="text" name="isbn" /><br/><br/>
    Publisher <input id="publisher" type="text" name="publisher_name"/>
    <?php
    //echo recaptcha_get_html($recaptchaSettings->public_key, $error);
    ?>
    <br/><input type="submit" name="submit" value="Submit"/>
</form>

<?php

$table = new Grid();
$table->gridTitles = true;
$table->gridSortable = true;
$table->gridNumbered = true;
$cm = new Country();
$cm->loadCountries();
$table->setColumnTitle("name","Name");
$table->createGridFromRecordset($cm);
echo $table->getGrid();

?>

<?php

require_once 'footer.php';

?>

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
    $("#states").load("get_states_drop_down.php?country_id="+$('#country_id').val());
});

$("#publisher").autocomplete("get_publishers_autocomplete.php");
//end

</script>