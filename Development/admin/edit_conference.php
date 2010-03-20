<?php

require_once FRONT_END . OBJECTS . 'Conference.php';

$conference = new Conference($_GET['conference_id']);

if($conference->id)
{
?>

<h2>Edit Conference <?php echo $conference->getValue('name'); ?></h2>

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
    $fv->violatesDbConstraints('conference', 'name', $conference->getFormValue('name'),'Conference Name');
    $fv->violatesDbConstraints('conference', 'acronym', $conference->getFormValue('acronym'),'Conference Acronym');
    $fv->violatesDbConstraints('conference', 'email', $conference->getFormValue('email'),'Contact Email');
    $fv->isEmailAddress("email", $conference->getFormValue('email'), "Contact Email");

    if($fv->hasErrors())
    {
        $fv->listErrors();
    }
    else
    {
        $conference->setValue('name',$conference->getFormValue('name'));
        $conference->setValue('acronym',$conference->getFormValue('acronym'));
        $conference->setValue('approved',(int) $conference->getFormValue('approved'));
        $conference->setValue('email', $conference->getFormValue('email'));
        
        if($conference->save())
        {
            $fv->addMessage("name", "Conference saved");
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

<form name="conferences" id="conferences" method="POST" action="main.php?page=edit_conference&conference_id=<?php echo $conference->id; ?>">
    <table>
        <tr>
            <td>Conference Name</td>
            <td><input type="text" name="name" value="<?php echo $conference->getFormValue('name');?>"/></td>
        </tr>
        <tr>
            <td>Conference Acronym</td>
            <td><input type="text" name="acronym" value="<?php echo $conference->getFormValue('acronym');?>"/></td>
        </tr>
        <tr>
            <td>Contact Email</td>
            <td><input type="text" name="email" value="<?php echo $conference->getFormValue('email');?>"/></td>
        </tr>
        <tr>
            <td>Approved</td>
            <td><input type="checkbox" name="approved" value="1" <?php echo $conference->getFormValue('approved') ? "checked " : "";?>/></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" name="submit" value="Submit"/>
            </td>
        </tr>
    </table>
</form>