<?php

require_once FRONT_END . OBJECTS . 'Country.php';

$country = new Country($_GET['country_id']);

if($country->id)
{
?>

<h2>Edit Country <?php echo $country->getValue('name'); ?></h2>

<?php
}
else
{
    ?>
<h2>Add a Country</h2>
<?php
}

if($_POST)
{
    $fv = new FormValidator();
    $fv->violatesDbConstraints('country', 'name', $country->getFormValue('name'),'Country Name');

    if($fv->hasErrors())
    {
        $fv->listErrors();
    }
    else
    {
        $country->setValue('name',$country->getFormValue('name'));
        
        if($country->save())
        {
            $fv->addMessage("name", "Country saved");
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

<form name="countries" id="countries" method="POST" action="main.php?page=edit_country&country_id=<?php echo $country->id; ?>">
    <table>
        <tr>
            <td>Country Name</td>
            <td><input type="text" name="name" value="<?php echo $country->getFormValue('name');?>"/></td>
        </tr>

        <tr>
            <td colspan="2">
                <input type="submit" name="submit" value="Submit"/>
            </td>
        </tr>
    </table>
</form>