<form name="countries" id="countries" method="POST" action="">
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