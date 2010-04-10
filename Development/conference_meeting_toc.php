<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'header.php';
require_once LIB . 'Date.php';
$conf_id = $_GET['big_conf'];
require_once OBJECTS . 'Author.php';
$conf_meet_id = $_GET['conference_meeting_id'];
echo "<h2>Name of Conference</h2>";
echo '<a href="conference_paper.php?big_conf='.$conf_id.'&conf_meet_id='.$conf_meet_id.'">Add new Conference Paper </a>';

require_once FRONT_END . OBJECTS . 'ConferenceMeeting.php';
require_once FRONT_END . OBJECTS . 'ConferencePaper.php';

$conf_meeting = new ConferenceMeeting();
$conf_paper = new ConferencePaper();

//print_r($conf_paper);
//$conf_paper->getConferencePapersByConfMeetingId($conf_meet_id);
$author = new Author();
$conf_meeting->loadConferenceMeetingById($conf_meet_id);
$name = $conf_meeting->name;
$startdate = $conf_meeting->start_date;
$enddate = $conf_meeting->end_date;
$city = $conf_meeting->city;
$state = $conf_meeting->_state_name;
$country = $conf_meeting->_country_name;
$publisher = $conf_meeting->_publisher_name;
$isbn = $conf_meeting->isbn;

$conf_session = new ConferenceSession(); //get only the conference session for that id
$query = "SELECT * FROM conference_session where conference_meeting_id=$conf_meet_id";
$conf_session->loadByQuery($query);

$daterange = Date::getDateRange($startdate,$enddate);
$year = date('Y',$startdate);
$year2 = date('Y',$enddate);

if ($year != $year2)
    $year = $year. " - " . $year2;

echo "<h3>$name: $daterange $city,  $state, $country; $publisher  ISBN#:$isbn </h3>";

/*foreach ($conf_session as $i) {
    echo "Session: $conf_session->name <br/>";
    echo "$conf_paper->name";
}*/
echo '<table>';
do
{
      $conf_session_id = $conf_session->id;
      $conf_paper_id = "SELECT id,title,start_page,end_page from conference_paper where conference_meeting_id=$conf_meet_id and conference_session_id = $conf_session_id";
      $conf_paper->loadByQuery($conf_paper_id);
      //echo "Session: $conf_session->name <br/>";
      echo '<tr><td>'.$conf_session->name.'</td></tr>';
      do{
          echo '<tr><td></td><td>'.$conf_paper->title.'</td></tr>';
          //echo "..........$conf_paper->title <br/>";
          $id_paper=$conf_paper->id;
          $author->getAuthorsByConferencePaperId($id_paper);
          echo '<tr><td></td><td>';
                do{
                     echo "<a href=authors.php>$author->firstname.' '.$author->initial.' '.$author->lastname</a>";
                     echo " "; 
                     //echo "..........$author->firstname $author->initial $author->lastname ";
                }while($author->next()); echo '</td></tr>';
          echo '<tr><td></td><td>Pages '.$conf_paper->start_page.' - '.$conf_paper->end_page.'</td></tr>';
          //echo "..........Pages $conf_paper->start_page - $conf_paper->end_page <br/>";

      }while($conf_paper->next());

}while($conf_session->next());
echo '</table>';
;


//echo $conf_session->name; prove i got the right conference
?>