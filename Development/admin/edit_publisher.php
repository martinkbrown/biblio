<?php

require_once FRONT_END . OBJECTS . 'Publisher.php';

$publisher = new Publisher($_GET['publisher_id']);

if($publisher->id)
{
?>

<h2>Edit Publisher <?php echo $publisher->getValue('name'); ?></h2>

<?php
}
else
{
    ?>
<h2>Add a Publisher</h2>
<?php
}

if($_POST)
{
    $fv = new FormValidator();
    $fv->violatesDbConstraints('publisher', 'name', $publisher->getFormValue('name'),'Publisher Name');

    if($fv->hasErrors())
    {
        $fv->listErrors();
    }
    else
    {
        $publisher->setValue('name',$publisher->getFormValue('name'));

        if($publisher->save())
        {
            $fv->addMessage("name", "Publisher saved");
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

<form name="countries" id="countries" method="POST" action="main.php?page=edit_publisher&publisher_id=<?php echo $publisher->id; ?>">
    <table>
        <tr>
            <td>Publisher Name</td>
            <td><input type="text" name="name" value="<?php echo $publisher->getFormValue('name');?>"/></td>
        </tr>

        <tr>
            <td colspan="2">
                <input type="submit" name="submit" value="Submit"/>
            </td>
        </tr>
    </table>
</form>