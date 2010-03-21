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
                echo "<a href=conferences.php?confname=$letter href= > $letter </a>";
            }
            else{
                //Prints the letter instead of a link
                echo $letter;
            }
        }
    }
?>
    <h2>Conferences</h2>
    <a href="submit_conference.php?conf_id=">Click here to submit a confrence meeting to a confrence not in our records</a>
    <p> To submit a conference on our records, first locate the confrence by browsing or searching</p>
    <h4>Browse Conferences by Acronym: <?php print_alpha($_GET['confname']) ?> </h4>

<!this form allows the user to view what the search and allows search to occur>
    <form action ="conferences.php?confname="  name ="frm_name" method="GET" >
        <p>Search Conferences: <input  type="text" id ="conf_name" name ="conf_name" value ="<?php if ($_GET['conf_name'] == "")
            {echo "Enter conference name here";}else echo $_GET['conf_name'];?>"
        onclick="this.value=''"   size="65" >
        <input type="submit" value ="Search" name ="Search" > </p>
        
    </form>
<?php
// gets the conference name/letter from the link
$cname = $_GET['confname'];
$grid = new ConferenceGrid();
$conference = new Conference();
$grid->setColumnTitle("_name","Name of Conference");
$conference->loadApprovedConferences();
$conference->loadApprovedConferences();
if ($cname == ""){
  $conference->loadConferencesByKeyword($_GET['conf_name']);
}else{ $conference->loadConferencesByFirstLetter($cname); }

$grid->createGridFromRecordset($conference);
echo $grid->getGrid();
?>

<?php
    require_once 'footer.php';
?>
