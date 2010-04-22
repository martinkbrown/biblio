<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

require_once 'header.php';
require_once LIB . 'Grid.php';
require_once OBJECTS . 'Author.php';
require_once OBJECTS . 'AuthorJournalPaper.php';
require_once OBJECTS . 'Journal.php';
require_once OBJECTS . 'JournalPaper.php';
require_once OBJECTS . 'JournalVolumeNumber.php';
require_once LIB . 'RecaptchaSettings.php';
require_once 'jquery_lib.php';
require_once 'jquery_calendar_lib.php';
require_once 'jquery_autocomplete_lib.php';
require_once('recaptcha/recaptchalib.php');
require_once 'jquery_timer_lib.php';
require_once 'jquery_blockui_lib.php';

?>

<?php
// Getting Journal ID from previous page
$j_id = $_GET['journal_id'];
$journal = new Journal($j_id);
$j_name_display = $journal->getValue("name");

// Variables for using Recaptcha
$recaptchaSettings = new RecaptchaSettings();

?>

<h2>Add a Journal Paper</h2>
Fields marked with * are required <br><br>

<?php
$journal_paper = new JournalPaper();
$fv = new FormValidator();
if ($_POST) {
    // Validating Data
    $fv = new FormValidator();
    $fv->violatesDbConstraints('journal_paper', 'title',$_POST['journal_paper_title'],'Paper Title');

    // Null Checks
    $fv->isNull($_POST['journal_paper_title'], 'Paper Title');
    $fv->isNull($_POST['journal_first_name'], 'First Name');
    $fv->isNull($_POST['journal_last_name'], 'Last Name');
    $fv->isNull($_POST['journal_paper_startpg'], 'Start Page');
    $fv->isNull($_POST['journal_paper_endpg'], 'End Page');
    $fv->isNull($_POST['journal_volume'], 'Volume');
    $fv->isNull($_POST['journal_date'], 'Date');
    if (sizeof($_POST['journal_first_name']) > 0) {
        foreach($_POST['journal_first_name'] as $key=>$firstname) {
            if (!is_numeric($firstname)) {
                $fv->isNull($firstname, 'First Name');
                // $isLastNameNull = $fv->isNull($_POST['journal_last_name'][$key], 'Last Name');
                // echo $isLastNameNull;
            }
        }
    }

    // Check if violates DB Contraints
    $fv->violatesDbConstraints('journal_paper', 'title', $_POST['journal_paper_title'], 'Title');
    $fv->violatesDbConstraints('journal_paper', 'start_page', $_POST['journal_paper_startpg'], 'Start Page');
    $fv->violatesDbConstraints('journal_paper', 'end_page', $_POST['journal_paper_endpg'], 'End Page');
    $fv->violatesDbConstraints('journal_paper', 'volume', $_POST['journal_volume'], 'Volume');
    if ($_POST['journal_number']) {
        $fv->isANumber('Number', $_POST['journal_number']);
        $fv->violatesDbConstraints('journal_paper', 'number', $_POST['journal_number'], 'Number');
    }
    // Validate that start page is less than end page
    $fv->isPositiveNumber('Start Page', $_POST['journal_paper_startpg']);
    $fv->isPositiveNumber('End Page', $_POST['journal_paper_endpg']);

    if ($_POST['journal_paper_startpg'] > $_POST['journal_paper_endpg'] ) {
        $fv->addError('Start Page', 'Start page must be less than or equal to the End Page');
    }
    $fv->isPositiveNumber('Volume',$_POST['journal_volume']);

    $fv->isValidCaptcha($recaptchaSettings->private_key);

    // Validating email address
    if ($fv->isEqual($_POST['user_email'], $_POST['user_conf_email'], 'Confirm Email',"Email addresses do not match")) {
        $fv->isEmailAddress($email, $_POST['user_email'], 'Your email');
        $fv->isNull($_POST['user_email'], 'Your email');
        $fv->violatesDbConstraints('journal_paper', 'email',$_POST['user_email'] ,'Your Email');
    }

    // If errors exist, inform user else save data and display confirmation page

    if ($fv->hasErrors()) {
        $fv->listErrors();
    } else {
        // Save Form Data

        $author = new Author();
        $journal_paper->setValue('journal_id', $j_id);
        $journal_paper->setValue('title', $_POST['journal_paper_title']);
        $journal_paper->setValue('start_page', $_POST['journal_paper_startpg']);
        $journal_paper->setValue('end_page', $_POST['journal_paper_endpg']);
        $journal_paper->setValue('create_date', $_POST['journal_date']);
        $journal_paper->setValue('approved', 0);
        $journal_paper->setValue('volume',$_POST['journal_volume']);

        if ($_POST['journal_number']) {
            $journal_paper->setValue('number', $_POST['journal_number']);
        } else { // If Number is Blank it must be automatically created
            $journal_paper->setValue('number', '1');
        }
        $journal_paper->setValue('email',$_POST['user_email']);
        $journal_paper->setValue('create_date', $_POST['journal_date']);

        // Saving All Authors
        if (sizeof($_POST['journal_first_name']) > 0) {
            foreach($_POST['journal_first_name'] as $key=>$firstname) {
                if (is_numeric($firstname)) {
                    echo $firstname;
                    $author = new Author($firstname);
                }
                else {
                    $author->setValue('firstname', $firstname);
                    $author->setValue('lastname', $_POST['journal_last_name'][$key]);
                    $author->setValue('initial', $_POST['journal_middle_init'][$key]);
                }
                $journal_paper->addAuthor($author);
            }
        }

        $journal_paper->setVolumeNumber($_POST['journal_volume'], $_POST['journal_date']);

        // FIXME: Add this confirmation page option to Shereen's submit_verify page
        if($journal_paper->save()) {
            // Display confirmation page
            $util = new Utilities();
            $util->redirect("submit_journal_paper_confirm.php");
        } else {
            $fv->addError("name","There was an error saving this Journal Paper");
            $fv->listErrors();
        }
    }
}
?>
<form name ="frm_name" action="" method ="POST">
    <table>
        <tr>
            <td>Journal Name*</td>
            <td>
                <?php echo $j_name_display?>
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Paper Title*</td>
            <td>
                <input type= "text" name= "journal_paper_title" size="46" value="<?php echo $_POST ['journal_paper_title'] ?>"/>
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Authors</td>
            <td id="similar_authors" colspan="3"> </td>
            <td></td>
            <td></td>
        </tr>
        <br>
        <tr class="author_row" id="0">
            <td class="author_label">Main Author*</td>
            <td>First Name*
                <input class="author_item"  type= "text" name= "journal_first_name[]" size="33" value="<?php echo $_POST ['journal_first_name'][0] ?>"/>
            </td>
            <td> Middle Initial
                <input class="author_item" type= "text" name= "journal_middle_init[]" size="1" value="<?php echo $_POST ['journal_middle_init'][0] ?>"/>
            </td>
            <td> Last Name*
                <input class="author_item" type= "text" name= "journal_last_name[]" size="30" value="<?php echo $_POST ['journal_last_name'][0] ?>"/>
            </td>
        </tr>
        <tr>
            <td>
                <?php
                $counter = count($_POST['first_name']);
                //echo $counter;
                for ($i=1;$i<$counter;$i++) {
                    echo '<tr class="author_row" id ="'.$i.'"><td class="author_label"><b>Coauthor </td><td><b>First Name*<input class="author_item" type ="text" name ="first_name[]" size ="33" id="fn" value ='.$_POST['first_name'][$i].' /></td><td><b>Middle Initial* <input type ="text" name ="mid_initial[]" size ="1" id="mi" value='.$_POST['mid_initial'][$i].' /></td><td><b>Last Name*<input class="author_item" type ="text" name ="last_name[]" size ="30" id="ln" value='.$_POST['last_name'][$i].' /></td><td><a href="javascript:;" onClick="removeFormField('.$i.');">Remove</a></td></tr>';
                }
                ?>
            </td>
        </tr>
        <tr id="authors_insert"><td></td><td><a id="adder" href="javascript:;">Click here to add another author</a></td>
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
                <input type= "text" name= "journal_date" id ="journal_date" size="46" value="<?php echo $_POST ['journal_date'] ?>"/>
            </td>
            <td></td><td></td>
        </tr>
        <tr>
            <td>Your Email*</td>
            <td>
                <input type= "text" name= "user_email" size="46" value="<?php echo $_POST ['user_email'] ?>"/>
            </td>
            <td></td><td></td>
        </tr>
        <tr>
            <td>Confirm Email*</td>
            <td>
                <input type= "text" name= "user_conf_email" size="46" value="<?php echo $_POST ['user_conf_email'] ?>"/>
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
        $("#authors_insert").before('<tr class="author_row" id ="'+counter+'"><td class="author_label">Coauthor</td><td>First Name* <input class="author_item" type ="text" name ="first_name[]" size ="33" id="fn" value="<?php echo ($_POST['first_name']['+counter+']); ?>" /></td><td>Middle Initial* <input class="author_item" type ="text" name ="mid_initial[]" size ="1" id="mi" value="<?php echo ($_POST['mid_initial']['+counter+']); ?>"/></td><td>Last Name* <input class="author_item" type ="text" name ="last_name[]" size ="30" id="ln" value="<?php echo ($_POST['last_name']['+counter+']); ?>"/></td><td><a href="javascript:;" onClick="removeFormField('+counter+');">Remove</a></td></tr>');
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
                                text +=  "<b>" + paper + ", ";
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

                                newHTML = '<td><input class="author_ids" type="hidden" name="journal_first_name[]" value="'+($(this).attr('id').substring(2))+'"/>'+name+"</td><td class=\"author_label\"><a href=\"javascript:;\" onClick=\"removeFormField('"+($(tr).attr('id'))+"');\">Remove</a><input name=\"journal_last_name[]\"/><input name=\"journal_middle_init\"/></td>";

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

    function setAuthorBlur()
    {
        $(".author_item").blur(getSimilarAuthors);
    }

    setAuthorBlur();

    // Function to enable user to pick date from a calendar drop down box
    $(function()
    {
        $("#journal_date").datepicker();
    });



</script>

