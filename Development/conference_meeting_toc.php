<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'header.php';
require_once FRONT_END . OBJECTS . 'ConferenceMeeting.php';
require_once FRONT_END . OBJECTS . 'ConferencePaper.php';
require_once FRONT_END . OBJECTS . 'Conference.php';
require_once LIB . 'Date.php';
//$conference = new Conference();
//$conf_id = $_GET['big_conf'];
//$conference->loadById($conf_id);
require_once OBJECTS . 'Author.php';




$conf_meeting = new ConferenceMeeting();
$conf_paper = new ConferencePaper();
$no_session_paper = new ConferencePaper();
$nodataconference = true;
$author = new Author();
$conf_meet_id = (int)$_GET['conference_meeting_id'];
$conf_meeting->loadConferenceMeetingById($conf_meet_id);
$zero = 0;

echo '<div class ="conf_back">';
echo '<br>';
echo '<span class="strong_writting">'.  $conf_meeting->_conference_name  .'('.$conf_meeting->_conference_acronym.')'.'</span>';
echo '<p><a href="conference_paper.php?big_conf='.$conf_id.'&conf_meet_id='.$conf_meet_id.'">Add new Conference Paper </a></p>';
 
$name = $conf_meeting->name;
$startdate = $conf_meeting->start_date;
$enddate = $conf_meeting->end_date;
$city = $conf_meeting->city;
$state = $conf_meeting->_state_name;
$country = $conf_meeting->_country_name;
$publisher = $conf_meeting->_publisher_name;
$isbn = $conf_meeting->isbn;

function print_toc($conf_meet_id,$author,$conf_paper){
          do{
              echo '<tr><td></td><td>'.$conf_paper->title.'</td></tr>';
              $id_paper=$conf_paper->id;
              $author->getAuthorsByConferencePaperId($id_paper);
              echo '<tr><td></td><td>';
                    do{
                         echo "<a href=author_papers.php?author_id=$author->id>$author->firstname $author->initial $author->lastname</a>";
                         echo " ";
                    }while($author->next()); echo '</td></tr>';
              echo '<tr><td></td><td>Pages '.$conf_paper->start_page.' - '.$conf_paper->end_page.'</td></tr>';
          }while($conf_paper->next());
}

$conf_session = new ConferenceSession(); //get only the conference session for that id
$query = "SELECT * FROM conference_session where conference_meeting_id=$conf_meet_id";
$conf_session->loadByQuery($query);

$daterange = Date::getDateRange($startdate,$enddate);
$year = date('Y',$startdate);
$year2 = date('Y',$enddate);

if ($year != $year2)
    $year = $year. " - " . $year2;

echo "<h3>$name: $daterange $city,  $state, $country; $publisher  ISBN#:$isbn </h3>";
echo '<table>';
// print the papers that do no belong to conference session
$query = "SELECT cp.id,cp.title,cp.start_page,cp.end_page from conference_paper cp where cp.conference_meeting_id=$conf_meet_id and conference_session_id = $zero";
$no_session_paper->loadByQuery($query);
do
{
          if ($no_session_paper->id){
              echo '<tr><td>'."Papers not in a session".'</td></tr>';
              print_toc($conf_meet_id,$author,$no_session_paper);
              $nodataconference = false;
          }

}while($no_session_paper->next());


do
{
      $conf_session_id = (int) $conf_session->id;

          if ($conf_session_id){$conf_paper_id = "SELECT cp.id,cp.title,cp.start_page,cp.end_page from conference_paper cp
          left join conference_session cs on cs.id = cp.conference_session_id
          where cp.conference_meeting_id=$conf_meet_id and cp.conference_session_id = $conf_session_id ";}
          else {
              $conf_paper_id =  "SELECT cp.id,cp.title,cp.start_page,cp.end_page from conference_paper cp
              where cp.conference_meeting_id=$conf_meet_id" ;
          }
          $conf_paper->loadByQuery($conf_paper_id);
          if ($conf_paper->id){
              echo '<tr><td>'.$conf_session->name.'</td></tr>';
              print_toc($conf_meet_id,$author,$conf_paper);
              $nodataconference = false;
          }

}while($conf_session->next());

if ($nodataconference == true)
    echo "No results found";



echo '</table>';
;

echo '</div>';

//echo $conf_session->name; prove i got the right conference
?>