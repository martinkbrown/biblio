<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ConferenceSession
 *
 * @author martin
 */
class ConferenceSession extends Recordset
{
    function ConferenceSession($id=0)
    {
        parent::Recordset($id,'conference_session');
    }
}
?>
