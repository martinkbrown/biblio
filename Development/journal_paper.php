<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

require_once 'header.php';
require_once LIB . 'Grid.php';
require_once OBJECTS . 'Journal.php';
require_once LIB . 'RecaptchaSettings.php';
require_once 'jquery_lib.php';
require_once 'jquery_calendar_lib.php';
require_once 'jquery_autocomplete_lib.php';
require_once('recaptcha/recaptchalib.php');

?>
<?php
// Declaring Variables
// Variables for using Recaptcha
$recaptchaSettings = new RecaptchaSettings();
// Variable for Storing Journal once all data is Good to Go :)

if ($_POST) {
    // Validate paper to be added to journal
}

?>
<h2>Add a Journal Paper</h2>
Fields marked with * are required <br>
<form name ="frm_name" method ="POST">
    <table>
        <tr>
            <td>Journal Name*</td>
            <td>
                
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Paper Title*</td>
            <td>
                <input type= "text" name= "journal_paper_title" size="46" value="<?php echo $_POST ['journal_name'] ?>"/>
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Authors</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <br>
        <tr>
            <td>Main Author*</td>
            <td>First Name*
                <input type= "text" name= "journal_first_name" size="33" value="<?php echo $_POST ['journal_first_name'] ?>"/>
            </td>
            <td> Middle Initial*
                <input type= "text" name= "journal_middle_init" size="1" value="<?php echo $_POST ['journal_middle_init'] ?>"/>
            </td>
            <td> Last Name*
                <input type= "text" name= "journal_last_name" size="30" value="<?php echo $_POST ['journal_last_name'] ?>"/>
            </td>
        </tr>
        <tr><td></td><td> <a href="index.php">Click here to add another author</a></td>
        <tr>
            <td>Start Page*</td>
            <td>
                <input type= "text" name= "journal_paper_startpg" size="46" value="<?php echo $_POST ['journal_paper_startpg'] ?>"/>
            </td>
            <td></td><td></td>
        </tr>
        <tr>
            <td>End Page*</td>
            <td>
                <input type= "text" name= "journal_paper_endpg" size="46" value="<?php echo $_POST ['journal_paper_endpg'] ?>"/>
            </td>
            <td></td><td></td>
        </tr>
        <tr>
            <td>Volume*</td>
            <td>
                <input type= "text" name= "journal_volume" size="46" value="<?php echo $_POST ['journal_volume'] ?>"/>
            </td>
            <td></td><td></td>
        </tr>
        <tr>
            <td>Number</td>
            <td>
                <input type= "text" name= "journal_number" size="46" value="<?php echo $_POST ['journal_number'] ?>"/>
            </td>
            <td></td><td></td>
        </tr>
        <tr>
            <td>Date*</td>
            <td>
                <input type= "text" name= "journal_date" size="46" value="<?php echo $_POST ['journal_date'] ?>"/>
            </td>
            <td></td><td></td>
        </tr>
        <tr>
            <td>Your Email*</td>
            <td>
                <input type= "text" name= "journal_number" size="46" value="<?php echo $_POST ['journal_number'] ?>"/>
            </td>
            <td></td><td></td>
        </tr>
        <tr>
            <td>Confirm Email*</td>
            <td>
                <input type= "text" name= "journal_number" size="46" value="<?php echo $_POST ['journal_number'] ?>"/>
            </td>
            <td></td><td></td>
        </tr>
         <tr>
            <td></td>
            <td>
                <?php echo recaptcha_get_html($recaptchaSettings->public_key, $error);?>
            </td>
            <td></td><td></td>
        </tr>
        <tr>
            <td></td>
            <td> <input type="submit" name="submit" value="Submit"/></td>
        </tr>

    </table>
</form>







