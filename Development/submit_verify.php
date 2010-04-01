<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'header.php';
require_once LIB . 'Utilities.php';
$sub_id = $_GET[submit_id];
switch ($sub_id){
    case 1:
        $confirm_string = "Add A Conference";
        $submission_string = "Thank you for your Conference submission <br/> it is being reviewed by the administrator";
        break;
    case 2:
        $confirm_string = "Add A Conference Meeting";
        $submission_string = "Thank you for your Conference meeting submission <br/> it is being reviewed by the administrator";
        break;
     case 3:
        $confirm_string = "Add A Conference Paper";
        $submission_string = "Thank you for your Conference paper submission <br/> it is being reviewed by the administrator";
        break;
    default:
        $confirm_string = "Thank you for your submission";
        $submission_string = "It is being reveiwed by the admistrator";
}
echo "<h2>$confirm_string</h2>";
echo "<h3>$submission_string</h3>";
?>
<a href ="index.php">Click here to return home</a>
