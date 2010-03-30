<?php
/*
 * This file is to serve the purpose of adding a new conference paper
 * The page is dynamic in the sense of adding and remove co-authors
 * depending on the paper
 * Author: Sherene Campbell
 */

require_once 'header.php';
require_once LIB . 'Grid.php';
require_once LIB . 'RecaptchaSettings.php';
require_once FRONT_END . OBJECTS . 'Conference.php';
require_once 'jquery_lib.php';
require_once 'jquery_autocomplete_lib.php';
require_once FRONT_END . OBJECTS . 'ConferenceMeeting.php';
require_once FRONT_END . OBJECTS . 'ConferencePaper.php';
require_once FRONT_END . OBJECTS . 'AuthorConferencePaper.php';
require_once FRONT_END . OBJECTS . 'ConferenceSession.php';
require_once FRONT_END . OBJECTS . 'Author.php';
require_once('recaptcha/recaptchalib.php');

$recaptchaSettings = new RecaptchaSettings();
$conference_session = new ConferenceSession();
$author = new Author();
$conf_id = $_GET['conf_meet_id'];
$conf_meeting = new ConferenceMeeting();
$conf_meeting->loadById($conf_id);

$conference_paper = new ConferencePaper();
$author_conf = new AuthorConferencePaper($author->getId(),$conference_paper->getId());
$ff = new FormValidator();
$ff->isValidCaptcha($recaptchaSettings->private_key);
//$conf_id = $_GET['conference_id'];

echo "<h2>Add a Conference Paper</h2>";
echo "<h4>Fields marked with * are required</h4>";


if($_POST)
{
    $fv = new FormValidator();
    $count = count($_POST['first_name']);        
    //echo $count;
    //for ($i=0; $i <$count; $i++){
    //      echo ($_POST['first_name'][0]);
    // }
    foreach($_POST['first_name'] as $key=>$value){
        //echo $value;
        $fv->violatesDbConstraints('author','firstname', $value, 'First Name');
    }
     foreach($_POST['mid_initial'] as $key=>$value){
        //echo $value;
        $fv->violatesDbConstraints('author','initial', $value, 'Mid Initial');
    }
    foreach($_POST['last_name'] as $key=>$value){
        //echo $value;
        $fv->violatesDbConstraints('author','lastname', $value, 'Last Name');
    }

    $fv->violatesDbConstraints('conference_paper', 'title', $conference_paper->getFormValue('paper_title'),'Paper Tile');
    $fv->violatesDbConstraints('conference_paper', 'start_page', $conference_paper->getFormValue('start_pg'),'Start Page');
    $fv->violatesDbConstraints('conference_paper', 'end_page', $conference_paper->getFormValue('end_pg'),'End Page');
    $check_email = $fv->oracle_string($conference_paper->getFormValue('email'),$conference_paper->getFormValue('confirm_email'));

    if ($check_email){
        $fv->violatesDbConstraints('conference_paper', 'email', $conference_paper->getFormValue('email'),'Email');
        $fv->isEmailAddress("email", $conference_paper->getFormValue('email'), "Email");
    }

   /* $flag_recapcha = true;
    if($ff->hasErrors())
    {
        $flag_recapcha = false;
        $ff->listErrors();
    }*/
    if(($fv->hasErrors() || ($check_email == false)))// || ($flag_recapcha==false))))
    {
        $fv->listErrors();
    }
    else {

        $conference_paper->setValue('title',$conference_paper->getFormValue('paper_title'));
        $conference_paper->setValue('start_page',$conference_paper->getFormValue('start_pg'));
        $conference_paper->setValue('end_page',$conference_paper->getFormValue('end_pg'));
        $conference_paper->setValue('email',$conference_paper->getFormValue('email'));
        $conference_paper->setValue('approved',0);
        $conference_paper->setValue('conference_meeting_id', $conf_id);
        $conference_paper->setValue('conference_session_id', $conference_session->getId());
        $conference_session->setValue('conference_meeting_id', $conf_id);
        $count = count($_POST['first_name']);
        for ($i=0; $i <$count; $i++){
            //if ($i==0) {$author_conf->setValue('main_author', '1');}
            //else {$author_conf->setValue('main_author', '0');}
            $author = new Author();
            $author->setValue('firstname', $_POST['first_name'][$i]);
            $author->setValue('initial', $_POST['mid_initial'][$i]);
            $author->setValue('lastname', $_POST['last_name'][$i]);
            $conference_paper->addAuthor($author);
        }
        $conference_paper->save();
        $util = new Utilities();
        $util->redirect("submit_verify.php");
    }
}
?>

<form name ="frm_name" method="POST">
    <!---this creates the table for the layout of the form--->
    <table>
        <tr>
            <td><b>Conference Meeting     </b></td>
            <td>
                <?php
                  echo $conf_meeting->getValue("name");
                ?>
            </td>
        </tr>
        <tr>
            <td><b>Paper Title*</b></td>
            <td> <input type ="text" id="conf_paper" name ="paper_title" size="40" value ="<?php echo $conference_paper->getFormValue('paper_title');?>" /></td>
        </tr>
        <tr>
            <td><b>Authors</b></td> <td id="auto_author" > </td>
        </tr>
        <tr>
            <td><b>Main Author</b></td>
            <td><b>First Name*</b><input id="test" class="author_item" type ="text" name ="first_name[]" size ="27" value="<?php echo ($_POST['first_name'][0]); ?>"/></td>
            <td><b>Middle Inital</b><input class="author_item" type ="text" name ="mid_initial[]" size ="10" id="mi" value="<?php echo ($_POST['mid_initial'][0]); ?>"/></td>
            <td><b>Last Name*</b><input class="author_item" type ="text" name ="last_name[]" size ="27" id="ln"value="<?php echo ($_POST['last_name'][0]); ?>"/></td>
        </tr>
        <tr>
            <td>
        <?php
            $counter = count($_POST['first_name']);
            //echo $counter;
            for ($i=1;$i<$counter;$i++){
                echo '<tr id ="'.$i.'"><td><b>Coauthor </b></td><td><b>First Name*</b><input class="author_item" type ="text" name ="first_name[]" size ="27" id="fn" value ='.$_POST['first_name'][$i].' /></td><td><b>Middle Inital</b><input type ="text" name ="mid_initial[]" size ="10" id="mi" value='.$_POST['mid_initial'][$i].' /></td><td><b>Last Name*</b><input type ="text" name ="last_name[]" size ="27" id="ln" value='.$_POST['last_name'][$i].' /></td><td><a href="javascript:void;" onClick="removeFormField('.$i.');">Remove</a></td></tr>';
            }
                /*//echo '<tr id ="'.$i.'"><td><b>Coauthor'.$i.'</b></td><td><b>First Name*</b><input type ="text" name ="first_name[]" size ="27" id="fn" value="<?php echo ($_POST['first_name']['.$i.']); ?>" /></td><td><b>Middle Inital</b><input type ="text" name ="mid_initial[]" size ="10" id="mi" value="<?php echo ($_POST['mid_initial']['.$i.']); ?>"/></td><td><b>Last Name*</b><input type ="text" name ="last_name[]" size ="27" id="ln" value="<?php echo ($_POST['last_name']['.$i.']); ?>"/></td><td><a href="javascript:void;" onClick="removeFormField('.$i.');">Remove</a></td></tr>';
            }
            /$count = count($_POST['first_name']);
            //for (i=1;i<count;i++){
             //   echo ('<tr id ="'+counter+'"><td><b>Coauthor'+i+'</b></td><td><b>First Name*</b><input type ="text" name ="first_name[]" size ="27" id="fn" value="<?php echo ($_POST['first_name']['+i+']); ?>" /></td><td><b>Middle Inital</b><input type ="text" name ="mid_initial[]" size ="10" id="mi" value="<?php echo ($_POST['mid_initial']['+i+']); ?>"/></td><td><b>Last Name*</b><input type ="text" name ="last_name[]" size ="27" id="ln" value="<?php echo ($_POST['last_name']['+i+']); ?>"/></td><td><a href="javascript:void;" onClick="removeFormField('+i+');">Remove</a></td></tr>');
             //    }*/
         ?>
            </td>
        </tr>
        <tr id="authors_insert">
            <td></td>
            <td><a id="adder" href="javascript:void;">Click here to add another author</a></td><td>&nbsp;</td>
        </tr>
        <tr>
            <td><b>Start Page*</b></td>
            <td><input type ="text" name ="start_pg" size="40" value="<?php echo $conference_paper->getFormValue('start_pg'); ?>"/></td>
        </tr>
        <tr>
            <td><b>End Page*</b></td>
            <td><input type ="text" name ="end_pg" size="40" value="<?php echo $conference_paper->getFormValue('end_pg'); ?>"/></td>
        </tr>
        <tr>
            <td><b>Email*</b></td>
            <td><input type ="text" name ="email" size="40" value="<?php echo $conference_paper->getFormValue('email'); ?>"/></td>
        </tr>
        <tr>
            <td><b>Confirm Email*</b></td>
            <td><input type ="text" name ="confirm_email" size="40"></td>
        </tr>
        <tr>
            <td></td>
            <td>
                    <?php //echo recaptcha_get_html($recaptchaSettings->public_key, $error);?>
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
<script>
var counter = <?php if (count($_POST['first_name']) == 0) echo 1; else echo count($_POST['first_name']);  ?>;
function removeFormField(id) {
    //delete item and update co-author's list
   // alert(id);
   counter--; //to counteract the increase in counter after #adder counter++
    $('#'+id).remove(); //remove what I need to
  //  var temp_count = counter; // how many values i had in my "list"
  //  counter--; //reduce counter due to deleting a row (2 lines above)
  //  var to_add = 0;
  //  for (i=id+1; i<=temp_count; i++){ //from the row infront of what i deleted
  //      $('#'+i).remove();
  //      counter--;
  //  }
   // alert (counter);
    //alert (temp_count);
   // to_add = counter; //how many rows should i add?
    //alert (to_add);
    //for (i=(to_add+1);i<=temp_count-1; i++){
     //   $("#authors_insert").before('<tr id ="'+i+'"><td><b>Coauthor'+i+'</b></td><td><b>First Name* </b><input type ="text" name ="first_name[]" size ="27" id="fn" value="<?php echo $_POST['first_name'][i]; ?>" /></td><td><b>Middle Inital</b></b><input type ="text" name ="mid_initial" size ="10" id="mi" value = "<?php echo $_POST['mid_initial']['+i+']; ?>" /></td><td><b>Last Name*</b><input type ="text" name ="last_name[]" size ="27" id="ln" value= "<?php echo $_POST['last_name']['+i+']; ?>" /></td><td><a href="javascript:void;" onClick="removeFormField('+i+');">Remove</a></td></tr>');
     //   counter++;
   // }
  //  counter++; //reset counter to what it would have been in the add function
}

$("#adder").click(function()
{
      $("#authors_insert").before('<tr id ="'+counter+'"><td><b>Coauthor</b></td><td><b>First Name*</b><input type ="text" name ="first_name[]" size ="27" id="fn" value="<?php echo ($_POST['first_name']['+counter+']); ?>" /></td><td><b>Middle Inital</b><input type ="text" name ="mid_initial[]" size ="10" id="mi" value="<?php echo ($_POST['mid_initial']['+counter+']); ?>"/></td><td><b>Last Name*</b><input type ="text" name ="last_name[]" size ="27" id="ln" value="<?php echo ($_POST['last_name']['+counter+']); ?>"/></td><td><a href="javascript:void;" onClick="removeFormField('+counter+');">Remove</a></td></tr>');
      counter++;
});

$(".author_item").blur(function()
{
    $("#auto_author").load("get_similar_authors.php?firstname=Late&lastname=hhhhj");
});

</script>
