<?php
    /*
     * This page provides the conferences page to search confereneces by name
     * and also by acronym. It then displays the results of the search to the
     * user.
     * Author: Sherene Campbell
     * Date: 2/19/2010
     */
    require_once 'header.php';
    require_once LIB . 'Grid.php';
    require_once OBJECTS . 'Conference.php';
    require_once OBJECTS . 'ConferenceGrid.php';

    function print_alpha($cname){
        /*this function prints the alphabet to the user to search
        conferences by acronym */
        foreach(range('A','Z') as $letter){
            if ($cname != $letter){
                // link for a specific link
                echo '<span class = "norm_link"> <a href="conferences.php?confname='.$letter.'" >' . $letter . ' ' .'</a></span>';
            }
            else{
                //Prints the letter instead of a link
                echo '<span class = "clicked_text">'.$letter. '</span>';
            }
        }
    }
?>

<div class ="conf_back">
    <br>
    <span class="solid_writting">Conferences</span> To submit a conference on our records, first locate the conference by browsing or searching
    <br>
    <br>
    <span class = "norm_link"> <a href="submit_conference.php?conf_id=">Submit a confrence meeting to a confrence not in our records</a> </span>
   
    <h4>Browse Conferences by Acronym: <?php print_alpha($_GET['confname']) ?> </h4>
    <p id="strong_center_text" > OR </p>
<!this form allows the user to view what the search and allows search to occur>
    <form action ="conferences.php?confname=&reset=y"  name ="frm_name" method="GET" >
        <p>Search Conferences: <input  type="text" id ="conf_name" name ="conf_name" value ="<?php if ($_GET['conf_name'] == "")
            {echo "Enter conference name here";}else echo $_GET['conf_name'];?>"
        onclick="this.value=''"   size="65" >
        <input type="submit" value ="Search" name ="Search" > </p>
        
    </form>
<?php
// gets the conference name/letter from the link
$reset = $_GET['reset'];
$cname = $_GET['confname'];
$grid = new ConferenceGrid();
$conference = new Conference();
$grid->setColumnTitle("_name","Name of Conference");
//$conference->loadApprovedConferences();
//$conference->loadApprovedConferences();
if($_GET['conf_name']!=""){
    $conference->loadConferencesByKeyword($_GET['conf_name']);
    //$conference->loadApprovedConferences();

}
if ($cname != ""){
    $conference->loadConferencesByFirstLetter($cname);
    //$conference->loadApprovedConferences();
}

$grid->createGridFromRecordset($conference);
echo $grid->getGrid();
?>

<?php
    require_once 'footer.php';
?>
</div>
