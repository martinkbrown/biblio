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
require_once 'jquery_timer_lib.php';
require_once 'jquery_blockui_lib.php';
        
$recaptchaSettings = new RecaptchaSettings();
//$conference_session = new ConferenceSession($conf_session->getFormValue('conf_sess_id'));
$author = new Author();
$conf_id = $_GET['conf_meet_id'];
$conf_meeting = new ConferenceMeeting();
$conf_meeting->loadById($conf_id);

$conference_paper = new ConferencePaper();
$author_conf = new AuthorConferencePaper($author->getId(),$conference_paper->getId());
$ff = new FormValidator();
$ff->isValidCaptcha($recaptchaSettings->private_key);

$conf_session = new ConferenceSession($_POST['conf_sess_id']); //get only the conference session for that id
//$query = "SELECT * FROM conference_session where conference_meeting_id=$conf_id";
//$conf_session->loadByQuery($query);

echo $conf_session->name; // prove i got the right conference

echo "<h2>Add a Conference Paper</h2>";
echo "<h4>Fields marked with * are required</h4>";


if($_POST)
{
    $fv = new FormValidator();
    $count = count($_POST['first_name']);        

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
    $fv->violatesDbConstraints('conference_session', 'name', $conf_session->getFormValue('conf_sess_name'),'Session Name');
    $fv->violatesDbConstraints('conference_paper', 'title', $conference_paper->getFormValue('paper_title'),'Paper Tile');
    $fv->violatesDbConstraints('conference_paper', 'start_page', $conference_paper->getFormValue('start_pg'),'Start Page');
    $fv->violatesDbConstraints('conference_paper', 'end_page', $conference_paper->getFormValue('end_pg'),'End Page');
    $check_email = $fv->oracle_string($conference_paper->getFormValue('email'),$conference_paper->getFormValue('confirm_email'));

    if ($check_email){
        $fv->violatesDbConstraints('conference_paper', 'email', $conference_paper->getFormValue('email'),'Email');
        $fv->isEmailAddress("email", $conference_paper->getFormValue('email'), "Email");
    }

    $flag_recapcha = true;
    if($ff->hasErrors())
    {
        $flag_recapcha = false;
        $ff->listErrors();
    }
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
        $conf_session->setValue('name',$conf_session->getFormValue('conf_sess_name'));
        $conf_session->setValue('conference_meeting_id',$conf_id);
        $conference_paper->setConferenceSession($conf_session);
        $count = count($_POST['first_name']);
        for ($i=0; $i <$count; $i++){
            $author = new Author();
            $author->setValue('firstname', $_POST['first_name'][$i]);
            $author->setValue('initial', $_POST['mid_initial'][$i]);
            $author->setValue('lastname', $_POST['last_name'][$i]);
            $conference_paper->addAuthor($author);
        }
        $getdate = getdate();
        $submit_date = $getdate[0]; //unix timestamp
        //echo  $submit_date;
        if (!($conf_session->id)){
            $conf_session->save();
        }
        $conference_paper->setValue('create_date', $submit_date);
        $conference_paper->save();
        $util = new Utilities();
        $util->redirect("submit_verify.php?submit_id=3");
    }
}
?>

<form name ="frm_name" method="POST">
    <!---this creates the table for the layout of the form--->
    <table>
        <tr>
            <td><b>Conference Meeting</b></td>
            <td>
                <?php
                  echo $conf_meeting->getValue("name");
                ?>
            </td>
        </tr>
        <tr>
            <td><b>Conference Session  </b></td>
            <td> <?php print"<input id='conf_sess_name' autocomplete='off' type='text' name='conf_sess_name' value='".$conf_session->getFormValue('conf_sess_name')."'; size='40'>\n"; ?>
                <input id="conf_sess_id" type="hidden" name="conf_sess_id" value="<?php echo $_POST['conf_sess_id'] ?>"/>
            </td>
        </tr>
        <tr>
            <td><b>Paper Title*</b></td>
            <td> <input type ="text" id="conf_paper" name ="paper_title" size="40" value ="<?php echo $conference_paper->getFormValue('paper_title');?>" /></td>
        </tr>
        <tr id="similar_authors_row">
            <td><b>Authors</b></td> <td id="similar_authors" colspan="3"> </td>
        </tr>
        <tr class="author_row" id="0">
            <td class="author_label"><b>Main Author</b></td>
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
                echo '<tr class="author_row" id ="'.$i.'"><td class="author_label"><b>Coauthor </b></td><td><b>First Name*</b><input class="author_item" type ="text" name ="first_name[]" size ="27" id="fn" value ='.$_POST['first_name'][$i].' /></td><td><b>Middle Inital</b><input type ="text" name ="mid_initial[]" size ="10" id="mi" value='.$_POST['mid_initial'][$i].' /></td><td><b>Last Name*</b><input class="author_item" type ="text" name ="last_name[]" size ="27" id="ln" value='.$_POST['last_name'][$i].' /></td><td><a href="javascript:;" onClick="removeFormField('.$i.');">Remove</a></td></tr>';
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
            <td><a id="adder" href="javascript:;">Click here to add another author</a></td><td>&nbsp;</td>
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
                    <?php echo recaptcha_get_html($recaptchaSettings->public_key, $error);?>
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

var main_author = $("#0").clone().get(0);

function removeFormField(id)
{
   //counter--;
   //to counteract the increase in counter after #adder counter++
    if(id == 0)
    {
        $('#'+id).remove();
        $("#similar_authors_row").after($(main_author).clone().get(0));
    }
    else
    {
        $('#'+id).remove(); //remove what I need to
    }

    setAuthorBlur();
  
}

$("#adder").click(function()
{
      $("#authors_insert").before('<tr class="author_row" id ="'+counter+'"><td class="author_label"><b>Coauthor</b></td><td><b>First Name*</b><input class="author_item" type ="text" name ="first_name[]" size ="27" id="fn" value="<?php echo ($_POST['first_name']['+counter+']); ?>" /></td><td><b>Middle Inital</b><input class="author_item" type ="text" name ="mid_initial[]" size ="10" id="mi" value="<?php echo ($_POST['mid_initial']['+counter+']); ?>"/></td><td><b>Last Name*</b><input class="author_item" type ="text" name ="last_name[]" size ="27" id="ln" value="<?php echo ($_POST['last_name']['+counter+']); ?>"/></td><td><a href="javascript:;" onClick="removeFormField('+counter+');">Remove</a></td></tr>');
      counter++;
      setAuthorBlur();
});

var results = 0;
var tr = null;

function getSimilarAuthors()
{
    var firstname = $(this).parents("tr").find("input").get(0).value;
    var lastname = $(this).parents("tr").find("input").get(2).value;

    if($.trim(firstname) == "" || $.trim(lastname) == "") return;

    var ids = "";

    $(".author_ids").each(function()
    {
        ids += "&id[]=" + ($(this).val());
    });

    var text;
    var it = this;

    results = 0;

    $.blockUI(
    { css:
            {
                border: 'none',
                padding: '15px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .5,
                color: '#fff'
            },
       message: 'Searching for matching authors...'
    });

    $.ajax(
    {
        type:"GET",
        url:"get_similar_authors.php?firstname="+firstname+"&lastname="+lastname+ids,
        dataType:"xml",
        complete:function()
        {
            $.unblockUI();
        },
        success:function(xml)
        {
            tr = null;
            $("#similar_authors").html("");
            
            $(xml).find('author').each(function()
            {
                    text = "";

                    var id = $(this).find('id').text();
                    var firstname = $(this).find('firstname').text();
                    var initial = $(this).find('initial').text();
                    var lastname = $(this).find('lastname').text();
                    
                    text += "<span id='suggest'>Did you mean <a id=a_"+id+" class=\"similar_authors\" href=\"javascript:;\">" + firstname + " " + initial + " " + lastname + "</a>, author of ";

                    var paper;
                    var got_paper = false;

                    $(this).find('papers').each(function()
                    {
                        $(this).find('paper').each(function()
                        {
                            got_paper = true;
                            paper = $(this).text();
                            if(paper != "")
                            {
                                text +=  "<b>" + paper + "</b>, ";
                            }
                        });
                    });

                    if(!got_paper) return;
                    else results++;

                    got_paper = false;
                    paper = "";

                    text = text.substring(0,text.length-2) + "?";

                    text += "<span><br/>";

                    $("#similar_authors").hide().fadeIn("slow").html($("#similar_authors").html()+text);

            });

            if(results > 0)
            {
                var ival = window.setInterval(function()
                {


                    if($(".similar_authors").size() == results)
                    {
                        window.clearInterval(ival);
                        $.unblockUI();

                        $(".similar_authors").click(function()
                        {
                            var name = $(this).html();
                            
                            $("#similar_authors").html("");
                            var newHTML = "";
                            var oldHTML = "";

                            if(tr==null)
                            {
                                tr = $(it).parents("tr");
                            }

                            $(it).parents("tr").find("td[class!='author_label']").remove();
                            
                            newHTML = '<td><input class="author_ids" type="hidden" name="author_id[]" value="'+($(this).attr('id').substring(2))+'"/>'+name+"</td><td class=\"author_label\"><a href=\"javascript:;\" onClick=\"removeFormField('"+($(tr).attr('id'))+"');\">Remove</a></td>";

                            $(tr).append(newHTML);
                        });
                    }
                },100);
            }
            else
            {
                $.unblockUI();
            }

        }
    });
    
}
$("#conf_sess_name").autocomplete("get_conf_session_autocomplete.php?conf_meet_id=<?php echo $conf_id ?>");
$('conf_sess_name').setOptions({ max: 5 });
$("#conf_sess_name").result(function(event, data, formatted)
{
    $("#conf_sess_id").val(data[1]);
    

});
function setAuthorBlur()
{
    $(".author_item").blur(getSimilarAuthors);
}

setAuthorBlur();

</script>
