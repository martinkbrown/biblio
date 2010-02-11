
<?php

//show the page header
require_once 'header.php';

//the country object is required to edit countries
require_once FRONTEND.OBJECTS.'Country.php';

//construct a new country object
$country = new Country();

//construct a new form validator to catch violations
$fv = new form_validator();

//if the form has been submitted by the user
if($_POST['submit'])
{
    //check for errors violating null constraint on country name
    $fv->violatesDBNull("country","name","Country Name");

    //if the form has errors
    if($fv->hasErrors())
    {
        //display the errors on the page
        $fv->listErrors();
    }
    else
    {
        //set the field "name" for the object created earlier to the value entered by the user
        $country->setValue("name",$_POST['name']);

        //save this record to the database
        if($country->save())
        {
            Utilities::redirect($_SERVER["SCRIPT_NAME"]);
        }
    }
}

//load the countries form
require_once FRONTEND.FORMS.'countries.php';

//show the page footer
require_once 'footer.php';
?>
