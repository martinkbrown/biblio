<?php

require_once FRONT_END . OBJECTS . 'State.php';
require_once FRONT_END . OBJECTS . 'Country.php';

$state = new State($state_id);

$country = new Country();
$country->loadCountries();

$dd = new DropDownList('name');
$dd->setFieldName("country_id");
$dd->setSelectedValues($state->getFormValue("country_id"));

if($state->id)
{
?>

<h2>Edit State <?php echo $state->getValue('name'); ?></h2>

<?php
}
else
{
    ?>
<h2>Add a State</h2>
<?php
}

if($_POST)
{
    $fv = new FormValidator();
    $fv->violatesDbConstraints('state', 'name', $state->getFormValue('name'),'State Name');
    $fv->violatesDbConstraints('state', 'abbreviation', $state->getFormValue('abbreviation'),'State Code');
    $fv->violatesDbConstraints('state', 'country_id', $state->getFormValue('country_id'),'Country');

    if($fv->hasErrors())
    {
        $fv->listErrors();
    }
    else
    {
        $state->setValue('name',$state->getFormValue('name'));
        $state->setValue('abbreviation',$state->getFormValue('abbreviation'));
        $state->setValue('country_id',$state->getFormValue('country_id'));
        
        if($state->save())
        {
            $fv->addMessage("name", "State saved");
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

<form name="states" id="states" method="POST" action="main.php?page=edit_state&state_id=<?php echo $state->id; ?>">
    <table>
        <tr>
            <td>State Name</td>
            <td><input type="text" name="name" value="<?php echo $state->getFormValue('name');?>"/></td>
        </tr>
        <tr>
            <td>State Code</td>
            <td><input type="text" name="abbreviation" value="<?php echo $state->getFormValue('abbreviation');?>"/></td>
        </tr>
        <tr>
            <td>Country</td>
            <td><?php echo $dd->getDropDownFromRecordset($country) ?></td>
        </tr>

        <tr>
            <td colspan="2">
                <input type="submit" name="submit" value="Submit"/>
            </td>
        </tr>
    </table>
</form>